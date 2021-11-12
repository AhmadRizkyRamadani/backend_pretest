<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ResponseController as ResponseController;
use App\Http\Controllers\Controller;
use App\Mail\EmailVerification;
use App\Models\LogActivity;
use App\Models\PreviousPassword;
use App\Models\User;
use Exception;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Whoops\Run;

class LoginController extends ResponseController
{
    public function login(Request $request){
        try{
            $user = User::where("email", $request->email)->first();
        }catch(QueryException $e){
            return $this->errorResponse("Login Failed.", $e->getMessage(),$e->getCode());
        }

        if($user){
            if($user->status == 0){
                return $this->errorResponse("Email not verified", "Please verify your email address first.", 403);
            }
            if(decrypt($user->password) == $request->password){
                LogActivity::create([
                    "user_id" => $user->user_id,
                    "token" => $user->token,
                    "login_date" => date("Y-m-d H:i:s")
                ]);
                session()->put('unique_id', $user->user_id);
				session()->put('nama_karyawan', $user->name);
                return $this->successResponse("Login Success.", $user);
            }else{
                return $this->errorResponse("Login Failed.", "Wrong Password", "403");
            }
        }else{
            return $this->errorResponse("Login Failed.", "User Not Found", "404");
        }

    }

    public function register(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => "required",
            'username' => "required",
            'email' => "required|email",
            'phone' => "required|numeric",
            'password' => ["required", Password::min(7)->mixedCase()->numbers()->symbols()],
        ],[
            'name' => "Nama harus diisi",
            'username' => "Username harus diisi",
            'phone' => "No.Telp harus diisi",
            'email' => [
                "required" => "Email harus diisi",
                "unique" => "Email sudah digunakan, gunakan email lain"
            ],
        ]);

        if($validation->fails()){
            return $this->errorResponse("Validation failed.", $validation->errors());
        }

        $user_input = $request->all();
        $user_id = "user_".date("ymdhis");
        $user_input['user_id'] = $user_id;
        $user_input['password'] = encrypt($user_input['password']);
        $user_input['saldo'] = 0;
        $user_input['status'] = 0;
        $token = Crypt::encrypt([
            "user_id" => $user_id,
            "email" => $user_input["email"],
            "access_date" => date("Y-m-d H:i:s"),
            "expiration_time" => "30 minutes",
            "token-type" => 'verify-email'
        ]);
        $url_verification = url("/api/verify_email?token=".$token);
        $user_input["token"] = $token;
        if(substr($user_input['phone'], 0, 2) == "08"){
            $user_input['phone'] = "628".substr($user_input['phone'], 2);
        }
        
        try{
            $query = User::create($user_input);
        }catch(QueryException $e){
            return $this->errorResponse("Register Failed", $e->errorInfo[2], 403);
        }
        if($query){
            Mail::to($user_input["email"])->send(new EmailVerification(["token" => $token, "url" => $url_verification, "user_data" => $user_input, "markdown" => "mail.email-verification"]));
        }
        $success["access_token"] = $token;
        $success["message"] = "Please verify your email";

        return $this->successResponse($success, "User registered successfully!");
    }

    public function verify_email(Request $request){
        $validation = Validator::make($request->all(), [
            'token' => "required",
        ],[
            'token' => "Token Required",
        ]);

        if($validation->fails()){
            return $this->errorResponse("Token Required", $validation->errors(), 403);
        }

        try{
            $token = Crypt::decrypt($request->token);
            if($token["token-type"] != "verify-email"){
                return $this->errorResponse("Token Invalid", "Token is invalid", 401);
            }
        }catch(Exception $e){
            return $this->errorResponse("Token Invalid", "Token is invalid", 401);
        }
        
        $user = User::where("token", $request->token)->first();
        if($user){
            $expired_token = date('Y-m-d H:i:s', strtotime('+0 hour +1 minutes', strtotime($token["access_date"])));
            if(date("Y-m-d H:i:s") > $expired_token){
                return $this->errorResponse("Token Expired", "Your token has expired. Resend your verification email", 401);
            }

            try{
                $query = User::where("email", $token["email"])->update(["status" => "1", "email_verified_at" => date("Y-m-d H:i:s"), "token" => ""]);
            }catch(QueryException $e){
                return $this->errorResponse("Verify failed.", $e->getMessage(), 500);
            }

            return $this->successResponse("Email Verified", "Email verified successfully", 200);
        }else{
            return $this->errorResponse("Token Invalid", "Token is invalid", 401);
        }

    }

    public function resend_verification(Request $request){
        $validation = Validator::make($request->all(), [
            'email' => "required|email",
        ]);

        if($validation->fails()){
            return $this->errorResponse("Resend Failed", $validation->errors());
        }

        $user = User::where("email", $request->email)->first();
        if(!$user){
            return $this->errorResponse("Not Found", "Email not registered");
        }

        try{
            $new_token = Crypt::encrypt([
                "email" => $request->email,
                "access_date" => date("Y-m-d H:i:s"),
                "expiration_time" => "30 minutes",
                "token-type" => 'verify-email'
            ]);
            $query = User::where("email", $request->email)->update([
                "token" => $new_token
            ]);
        }catch(Exception $e){
            return $this->errorResponse("Resend Failed", $e->getMessage());
        }

        if($query){
            $url = url("/api/verify_email?token=".$new_token);
            Mail::to($request->email)->send(new EmailVerification(["token" => $new_token, "user_data" => $user, "url" => $url, "markdown" => "mail.email-verification"]));
        }

        return $this->successResponse("Resend Verification Success.", ["token" => $new_token], 200);
    }

    public function request_reset_password(Request $request){
        $validation = Validator::make($request->all(),[
            "email" => "required|email",
        ]);

        if($validation->fails()){
            return $this->errorResponse("Request reset password Failed", $validation->errors(), 401);
        }

        try{
            $user = User::where("email", $request->email)->first();
            
            if(!$user){
                return $this->errorResponse("Request reset password failed", "Email doesn't match with our records.", 404);
            }
        }catch(QueryException $e){
            return $this->errorResponse("Request reset password failed", $e->getMessage());
        }

        $token = Crypt::encrypt([
            "email" => $user->email,
            "token-type" => "reset-password",
            "access_date" => date("Y-m-d H:i:s"),
            "expiration_time" => "30 minutes"
        ]);

        try{
            $query = User::where("email", $request->email)->update([
                "token" => $token
            ]);
        }catch(QueryException $e){
            return $this->errorResponse("Request reset password failed", $e->getMessage());
        }

        if($query){
            // $url = url("/api/reset_password?token=".$token);
            // Mail::to($request->email)->send(new EmailVerification(["token" => $token, "user_data" => $user, "url" => $url, "markdown" => "mail.reset-password"]));
            return $this->successResponse("Request reset password success.", [
                "token" => $token,
            ]);
        }
    }

    public function reset_password(Request $request){
        $validation = Validator::make($request->all(),[
            "token" => "required",
            "email" => "required|email",
            "current_password" => "required",
            'new_password' => ["required", Password::min(7)->mixedCase()->numbers()->symbols()],
            'confirm_password' => "required|same:new_password"
        ],[
            "token.required" => "Token is required",
            "current_password.required" => "Current Passowrd required",
            "new_password.required" => "New Passowrd required",
            "confirm_password." => [
                "required" => "Confirm Password Required",
            ]
        ]);

        if($validation->fails()){
            return $this->errorResponse("Failed to reset password", $validation->errors(), 401);
        }

        try{
            $token = Crypt::decrypt($request->token);
        }catch(Exception $e){
            return $this->errorResponse("Failed to reset password", $e->getMessage());
        }

        if($token["token-type"] != "reset-password"){
            return $this->errorResponse("Failed to reset password", "Token Invalid");
        }

        $user = User::where("email", $request->email)->first();
        
        if($user){
            if(decrypt($user->password) == $request->current_password){
                if(similar_text(decrypt($user->password), $request->new_password) > 4){
                    return $this->errorResponse("Failed to reset password", "Your new password is similar with your current password, try another one.", 401);
                }else if(PreviousPassword::where("user_id", $user->user_id)->count() > 0){
                    $prev = PreviousPassword::select("last_password")->where("user_id", $user->user_id)->get();
                    foreach($prev as $p){
                        if(decrypt($p->last_password) == $request->new_password){
                            return $this->errorResponse("Failed to reset password", "It looks like you've used this password before, try another one.", 401);
                        }
                    }
                }
                PreviousPassword::create([
                    "user_id" => $user->user_id,
                    "last_password" => $user->password
                ]);
                User::where("email", $request->email)->update([
                    "password" => encrypt($request->new_password),
                    "token" => ""
                ]);
                return $this->successResponse("Password Reset Successfully", "New password has been saved.");
            }else{
                return $this->errorResponse("Password Reset Failed", "Wrong Password");
            }
        }else{
            return $this->errorResponse("Password Reset Failed", "User not found", 404);
        }
    }
}
