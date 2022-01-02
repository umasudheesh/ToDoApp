<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\User;
use Validator;

class AuthController extends Controller
{
    private $apiToken;
    private $sucess_status = 200;

    public function __construct()
    {
        // Unique Token
        $this->apiToken = uniqid(base64_encode(str_random(60)));
    }

    /**
     * Client Login
     */
    public function postLogin(Request $request)
    {
        // Validations
        $rules = [
        'email'=>'required|email',
        'password'=>'required|min:8'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
        // Validation failed
        return response()->json([
            'validation_errors' => $validator->messages(),
        ]);
        }
        else
        {
            // Fetch User
            $user = User::where('email',$request->email)->first();
            if($user)
            {
                // Verify the password
                if( password_verify($request->password, $user->password) ) {
                // Update Token
                $postArray = ['api_token' => $this->apiToken];
                $login = User::where('email',$request->email)->update($postArray);
                
                if($login)
                {
                    return response()->json(["status" => $this->sucess_status, "success" => true, "data" => [
                        'name'         => $user->name,
                        'email'        => $user->email,
                        'access_token' => $this->apiToken,
                        ]]);
                }
                } else {
                return response()->json([
                    'validation_errors' => 'Invalid Password',
                ]);
                }
            }
            else
            {
                return response()->json(["status" => "failed", "success" => false, "message" => "User not found."]);
            }
        }
    }

    /**
     * Logout
     */
    public function postLogout(Request $request)
    {
        $token = $request->header('Authorization');
        $user = User::where('api_token',$token)->first();
        if($user)
        {
            $postArray = ['api_token' => null];
            $logout = User::where('id',$user->id)->update($postArray);
            if($logout)
            {
                return response()->json(["status" => $this->sucess_status, "success" => true, "message" => "User Logged Out"]);
            }
        }
        else
        {
            return response()->json(["status" => "failed", "success" => false, "message" => "User not found."]);
        }
    }
}
