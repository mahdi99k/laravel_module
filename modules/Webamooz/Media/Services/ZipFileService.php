<?php

namespace Webamooz\Media\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webamooz\Media\Contracts\FileServiceContract;
use Webamooz\Media\Models\Media;

class ZipFileService extends DefaultFileService implements FileServiceContract
{

    public static function upload(UploadedFile $file, $filename, $dir): array
    {
        $extension = $file->getClientOriginalExtension();
        Storage::putFileAs($dir, $file, $filename . '.' . $extension);
        return ["zip" => $filename . '.' . $extension];
    }

    public static function thumb(Media $media)
    {
        return url('/img/zip.png');
    }

    public static function getFileName()
    {
        return (self::$media->is_private ? 'private/' : 'public/') . self::$media->files['zip'];
    }

}
