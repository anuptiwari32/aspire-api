<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * method to perform the login by user and return the user details with token 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function login(Request $request): JsonResponse
    {
        try {
            if ($request->isMethod('post')) {
                $json = file_get_contents('php://input');
                $data = json_decode($json, TRUE);

                    $validator = Validator::make($data, [
                        'email' => "required",
                        'password' => "required",
                    ]);
                
                if ($validator->fails()) {
                        return sendError('Validation Error.', $validator->errors());
                }

                $crdentials['password'] = $data['password'];
                if (isset($data['email'])) {
                    $crdentials['email'] = strtolower($data['email']);
                } else if (isset($data['username'])) {
                    $crdentials['username'] = strtolower($data['username']);
                }

                if (Auth::attempt($crdentials)) {
                    // Authentication was successful...
                    $user_id = Auth::user()->id;
                    $user  = User::find($user_id);
                    $accessToken = $user->createToken('authToken')->plainTextToken;
                    $user->save();
                    $output = array();
                    $output['code'] = 200;
                    $output['msg'] = $user;
                    $output['access_token'] = $accessToken;
                    return    sendResponse($output,'User Login Successful');
                }
                else {
                    return sendError('Invalid Credentials');
                }
            }
            else
            {
                return sendError('Invalid Request');
            }
        } catch (\Throwable $th) {
            return sendError('Internal Server Error with.', $th->getMessage());

        }
    }

}