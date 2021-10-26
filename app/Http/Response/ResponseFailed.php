<?php

namespace App\Http\Response;

use Illuminate\Support\Facades\Response;

class ResponseFailed
{
    /* List Error of Code */
    const ERRORS = [
        "200" => "OK",
        "201" => "Success",
        "204" => "Success",
        "301" => "Login required",
        "304" => "Not Modified",
        "400" => "Bad Request, There was a missing reference, There was a serialization error, There was a validation error (see Validation errors)",
        "401" => "Unauthorized, You are not authorized to make this request",
        "403" => "Forbidden, This service has not been enabled for your site, XSRF Protection Failure",
        "404" => "The requested resource was not found.",
        "409" => "There was a conflict.",
        "412" => "The resource you are attempting to delete has dependencies, and cannot be deleted",
        "413" => "Storage space exceeded.",
        "429" => "Too Many Requests",
        "500" => "The service has encountered an error, Internal Server Error",
        "502" => "Bad Gateway",
        "503" => "Service Unavailable, There was a timeout processing the request",
        "123" => "terjadi kesalahan pada server"
    ];

    public static function make($data = null, $code = '403')
    {
        return Response::json([
            'status' => 0,
            'message' => static::ERRORS[$code],
            'reason' => $data
        ], $code);
    }
}
