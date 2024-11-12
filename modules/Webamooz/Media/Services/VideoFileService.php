<?php

namespace Webamooz\Media\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webamooz\Media\Contracts\FileServiceContract;
use Webamooz\Media\Models\Media;

class VideoFileService extends DefaultFileService implements FileServiceContract
{

    public static function upload(UploadedFile $file, $filename, $dir) :array
    {
        $extension = $file->getClientOriginalExtension();
        Storage::putFileAs($dir, $file, $filename . '.' . $extension);
        return ["video" => $filename . '.' . $extension];
    }

    public static function thumb(Media $media)
    {
        return asset('/img/mx-player.jpg');
    }

    public static function getFileName()
    {
        return (static::$media->is_private ? 'private/' : 'public/') . static::$media->files['video'];
    }
}
