    <?php
    
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    function sendResponse($result, $message,$flag= false,$code=200)
    {

        $response = [];
        if(!$flag)
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        else $response = $result;
       
        return response()->json($response, $code);
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

   