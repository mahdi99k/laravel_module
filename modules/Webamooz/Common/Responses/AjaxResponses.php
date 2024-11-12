<?php

namespace Webamooz\Common\Responses;

use Illuminate\Http\Response;

class AjaxResponses
{

    public static function successResponse($message)
    {
        return response()->json([
            'status' => 'موفقیت آمیز',
            'message' => $message,
        ], Response::HTTP_OK);  //use ajax
    }

    public static function failedResponse($message)
    {
        return response()->json([
            'status' => 'خطا',
            'message' => $message,
        ], Response::HTTP_INTERNAL_SERVER_ERROR);  //use ajax
    }

}
