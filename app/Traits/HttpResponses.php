<?php

namespace App\Traits;

trait HttpResponses
{
    protected function success($data, $message = null, $code = 200)
    {
        return response()->json(
            [
                "status" => "success",
                "message" => $message,
                "data" => $data
            ],
            $code
        );
    }
    protected function fail($data, $message = null, $code)
    {
        return response()->json(
            [
                "status" => "error has occurred",
                "message" => $message,
                "data" => $data
            ],
            $code
        );
    }
}
