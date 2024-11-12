<?php

namespace Webamooz\Media\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Webamooz\Media\Contracts\FileServiceContract;
use Webamooz\Media\Models\Media;

class ImageFileService extends DefaultFileService implements FileServiceContract
{

    private static array $sizes = ['300', '600'];

    public static function upload(UploadedFile $file, $filename, $dir): array
    {
        return self::resize($file, "app\\$dir", $filename, $file->getClientOriginalExtension());  //images list -> [original + 300 + 600]
    }

    private static function resize($file, $dir, $filename, $extension)
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getRealPath());  // read image from file system
        $imgs['original'] = $filename . '.' . $extension;  //path images

        foreach (self::$sizes as $size) {
            $imgs[$size] = $filename . "_$size." . $extension;
            $image->scale(width: $size);  // resize image proportionally to 300px width
            $image->size()->aspectRatio();  //میاد بر اسا ارتفاع که ندادیم به قسمت اسکیل میاد اتوماتیک اندازه نسبت به عرض میده بهش
            $image->save(storage_path($dir) . $filename . "_$size." . $extension);
        }
//      Storage::putFileAs($dir, $file, $filename . '.' . $extension);  //use file in testing
        $file->move(storage_path($dir), $filename . '.' . $extension);  //move(path , imagename)
        return $imgs;
    }

    public static function thumb(Media $media)
    {
        return '/storage/' . $media->files['300'];  //get image size 300(thumb بند انگشتی)
    }

    public static function getFileName()
    {
        return (static::$media->is_private ? 'private/' : 'public/') . static::$media->files['original'];
    }

}
