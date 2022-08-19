    <?php
    
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    function sendResponse($result, $message,$flag= false)
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
    function sendError($error, $errorMessages = [], $code = 404)
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

   