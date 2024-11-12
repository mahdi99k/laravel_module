<?php
return [
    "MediaTypeServices" => [

        "image" => array(  //key(image) => [value]
            "extensions" => array(
                "png", "jpg", "jpeg", "svg"
            ),
            "handler" => \Webamooz\Media\Services\ImageFileService::class
        ),

        "video" => [
            "extensions" => [
                "avi", "mp4", "mkv"
            ],
            "handler" => \Webamooz\Media\Services\VideoFileService::class
        ],

        "zip" => [
            "extensions" => [
                "zip", "rar", "tar"
            ],
            "handler" => \Webamooz\Media\Services\ZipFileService::class
        ]

    ]
];
