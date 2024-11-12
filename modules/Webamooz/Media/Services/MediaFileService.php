<?php

namespace Webamooz\Media\Services;

use Webamooz\Media\Contracts\FileServiceContract;
use Webamooz\Media\Models\Media;
use Webamooz\Media\Providers\MediaServiceProvider;

class MediaFileService
{

    private static $file;
    private static $dir;
    private static $isPrivate;

    public static function privateUpload($file)
    {
        self::$file = $file;
        self::$dir = "private/";
        self::$isPrivate = true;
        return self::upload();
    }

    public static function publicUpload($file)
    {
        self::$file = $file;
        self::$dir = "public/";
        self::$isPrivate = false;
        return self::upload();
    }

    private static function upload()
    {
        $extension = self::normalizeExtension(self::$file);  //jpg,png,mp4,mkv,mp3

        foreach (config('mediaFile.MediaTypeServices') as $type => $mediaFile) {
            if (in_array($extension, $mediaFile['extensions'])) {
                return self::uploadByHandler(new $mediaFile['handler'], $type);
            }
        }
    }

    public static function stream(Media $media)
    {
        foreach (config('mediaFile.MediaTypeServices') as $type => $mediaFile) {
            if ($media->type == $type) {
                return $mediaFile['handler']::stream($media);  //$mediaFile['handler'] === Services/ZipFileService.php
            }
        }
    }

    public static function delete(Media $media)
    {
        foreach (config('mediaFile.MediaTypeServices') as $type => $mediaFile) {
            if ($media->type == $type) {
                return $mediaFile['handler']::delete($media);  //$mediaFile['handler'] === Services/ZipFileService.php
            }
        }
    }


    public static function thumb(Media $media)
    {
        foreach (config('mediaFile.MediaTypeServices') as $type => $mediaFile) {
            if ($media->type == $type) {
                return $mediaFile['handler']::thumb($media);
            }
        }
    }

    public static function getExtensions()
    {
        $extensions = [];
        foreach (config('mediaFile.MediaTypeServices') as $mediaFile) {
            foreach ($mediaFile['extensions'] as $extension) {
                $extensions[] = $extension;
            }
        }
        return implode(',', $extensions);  //array convert to string
    }

    //---------- method helper
    private static function uploadByHandler(FileServiceContract $mediaFile, $type): Media
    {
        $media = new Media();
        $media->user_id = auth()->id();  //auth()->user()->id;
        $media->files = $mediaFile::upload(self::$file, self::filenameGenerator(), self::$dir);
        $media->type = $type;
        $media->filename = self::$file->getClientOriginalName();  //slider1.jpg
        $media->is_private = self::$isPrivate;
        $media->save();
        return $media;
    }

    private static function normalizeExtension($file): string
    {
        return strtolower($file->getClientOriginalExtension());
    }

    private static function filenameGenerator(): string
    {
        return uniqid() . '_' . time() . rand(1, 1000);
    }

}

