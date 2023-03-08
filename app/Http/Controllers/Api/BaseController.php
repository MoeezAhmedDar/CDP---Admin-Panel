<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function responseSuccessApi($retailer_info, $data, $meta, $success, $message, $status)
    {
        return response()->json([
            'retailer_info' => $retailer_info,
            'data' => $data,
            'message' => $message,
            'meta' => $meta,
        ], $status = $status);
    }

    public function responseUnauthorizedApi($error_message, $status)
    {
        return response()->json([
            'error_message' => $error_message,
            'success' => 'false'
        ], $status);
    }

    public function responseApi($error_message, $success, $errors, $code)
    {
        return response()->json([
            'error_message' => $error_message,
            'errors' => $errors,
            'success' => $success
        ], $code);
    }
}
