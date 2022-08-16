<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiBaseMethod extends Model
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public static function sendResponse($result, $message,$flag= false)
    {

       
        $api_status =1;
        $response = [];
        if ($api_status != 0) {
            if(!$flag)
            $response = [
                'success' => true,
                'data'    => $result,
                'message' => $message,
            ];
            else $response = $result;
        } else {
            $response = [

                'success' => false,
                'message' => 'Api Disabled',
            ];
        }



        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public static function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

    // Return url
    public static function checkUrl($url)
    {
        $data = explode('/', $url);
        if (in_array('api', $data)) {
            return true;
        } else {
            return false;
        }
    }
}