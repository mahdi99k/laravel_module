<?php

namespace Webamooz\Media\Services;

use Illuminate\Support\Facades\Storage;
use Webamooz\Media\Models\Media;

abstract class DefaultFileService
{

    public static $media;  //stream درون این فانگشن مقدار دهی میشه مدیا  +  getFileName مقدار ستون فایل ها و زیپ میگیره

    public static function delete(Media $media)
    {
        foreach ($media->files as $file) {
            if ($media->is_private) {
                Storage::delete('private\\' . $file);  //Storage -> storage\app\
            } else {
                Storage::delete('public\\' . $file);  //Storage -> storage\app\
            }
        }
    }

    abstract static function getFileName();


    public static function stream(Media $media)
    {
        static::$media = $media;
        $stream = Storage::readStream(static::getFileName());  //self::getFileName() همین متود درون این کلاس میخونه -> static::getFileName() -> متودی که باز نویسی کردیم در سرویس زیپ میخونه
        return response()->stream(function () use ($stream) {  //
            while (ob_get_level() > 0) ob_get_flush();
            //ob_get_level -> مقدار رم برای دانلود اگر بزرگتر از صفر یا در حال استفاده بود
            // ob_get_flush بیا مقدار فضای استفاده رم برای دانلود مثلا یک گیگ بیا صفر کن تا بتونه فایل بزرگتر از یک گیگ دانلود کنه
            fpassthru($stream);  //for download in client -> دیتا میفرسته سمت خروجی یا کاربر
        },
        200,
        [
            'Content-Type' => Storage::mimeType(static::getFileName()),
            "Content-disposition => 'attachment; filename='" . static::$media->filename . "'"
        ]);
    }

}
