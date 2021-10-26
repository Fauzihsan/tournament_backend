<?php

namespace App\Http\Response;

use Illuminate\Support\Facades\Response;

class ResponseSuccess
{
    public static function make($data)
    {
        return Response::json([
            'status' => 1,
            'message' => 'Berhasil',
            'data' => $data,
        ], 200);
    }
}
