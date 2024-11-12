<?php

namespace Webamooz\Media\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Webamooz\Media\Models\Media;
use Webamooz\Media\Services\MediaFileService;

class MediaController extends Controller
{

    public function download(Media $media, Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }
        return MediaFileService::stream($media);
    }

}
