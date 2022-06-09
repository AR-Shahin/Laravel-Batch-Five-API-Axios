<?php

function test($name)
{
    return "Hello $name";
}


function sendSuccessResponse($result, $message = 'Data Retrieve Successfully', $code = 200)
{
    $response = [
        'code' => $code,
        'message' => $message,
        'data' => $result,
        'success' => true
    ];
    return response()->json($response);
}


function sendErrorResponse($err, $errMessage = [], $code = 404)
{
    $response = [
        'code' => $code,
        'message' => $err,
        'success' => false
    ];
    if (!empty($errMessage)) {
        $response['data'] = $errMessage;
    }

    return response()->json($response);
}
