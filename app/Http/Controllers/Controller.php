<?php

namespace App\Http\Controllers;

use GrahamCampbell\ResultType\Result;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function SuccessRespone($result, $message)
    {
        $content = [
            'success' => true,
            'message' => $message,
            'data' => $result
        ];
        return response()->json($content, 200);
    }
    public function ErrorRespone($error, $errormessage =[],$code = 403)
    {
        $content = [
            'success' => false,
            'message' => $error,
        ];
        if (!empty($errormessage)) {
            $content['data'] = $errormessage;
        }
        return response()->json($content, $code);
    }
}
