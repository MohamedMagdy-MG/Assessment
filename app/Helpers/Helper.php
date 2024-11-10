<?php
namespace App\Helpers;

class Helper
{

    public static function ResponseData($data, $message, $status = false, $code = 500, $error = null)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $status,
            'errors' => $error,
        ])->setStatusCode($code)
            ->withHeaders([
                'Access-Control-Allow-Origin', '*',
                'Content-Type' => 'application/json'
            ]);
    }

}
