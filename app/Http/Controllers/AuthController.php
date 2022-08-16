<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ApiBaseMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request) //changes done by irfan
    {
      
        try {
            if ($request->isMethod('post')) {
                $json = file_get_contents('php://input');
                $data = json_decode($json, TRUE);

                if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                    $validator = Validator::make($data, [
                        'email' => "required",
                        'password' => "required",
                    ]);
                }
                if ($validator->fails()) {
                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
                    }
                }

                $password = $data['password'];
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
                    $accessToken = $user->createToken('authToken')->plainTextToken; //Auth::user()->createToken
                   //plainTextToken
                    $user->save();
                    $output = array();
                    $output['code'] = 200;
                    $output['msg'] = $user;
                    $output['access_token'] = $accessToken;
                    return    ApiBaseMethod::sendResponse($output,'User Login Successful');
                }

                else {
                    return ApiBaseMethod::sendError('Invalid Credentials');
                }


            }
            else
            {
                return ApiBaseMethod::sendError('Invalid Request');

            }
          //code...
        } catch (\Throwable $th) {
            //throw $th;
            return ApiBaseMethod::sendError('Internal Server Error with.', $th->getMessage());

        }
    }

}