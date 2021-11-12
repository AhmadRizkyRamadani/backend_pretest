<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function successResponse($msg, $result){
        $response = [
            "success" => true,
            "data" => $result,
            "message" => $msg
        ];

        return response()->json($response, 200);
    }

    public function errorResponse($error, $errMsg = [], $code = 404){
        $response = [
            "success" => false,
            "message" => $error,
        ];

        if(!empty($errMsg)){
            $response["data"] = $errMsg;
        }

        return response()->json($response, $code);
    }
}
