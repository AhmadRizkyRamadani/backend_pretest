<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MutasiPengguna;
use App\Models\User;
use App\Rules\Mutation;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as Validator;

class UserTransactionController extends ResponseController
{
    public function top_up(Request $request){
        $validation = Validator::make($request->all(), [
            "username" => "required_without:phone",
            "phone" => "required_without:username|numeric",
            "nominal" => "required|numeric"
        ],[
            "nominal.required" => "Nominal field parameter is required",
        ]);

        $errTitle = "Top Up Request Failed";

        if($validation->fails()){
            return $this->errorResponse($errTitle, $validation->errors());
        }

        $userModel = User::where(function($query)use($request){
            if(!empty($request->phone) && (substr($request->phone, 0, 2) == "08")){
                $request->phone = "628".substr($request->phone, 2);
            }
            $query->where("username", $request->username)->orWhere("phone", $request->phone);
        });

        try{
            $user = $userModel->first();
        }catch(QueryException $e){
            return $this->errorResponse($errTitle, $e->getMessage(), 403);
        }

        if($user){
            try{
                $query = $userModel->increment("saldo", str_replace(".", "", $request->nominal));
            }catch(Exception $e){
                return $this->errorResponse($errTitle, $e->getMessage());
            }

            $kode_mutasi = "topup_".date("Ymdhis");
            MutasiPengguna::create([
                "kode_mutasi" => $kode_mutasi,
                "user_sender" => "Top-Up",
                "user_receiver" => $user->user_id,
                "nominal" => str_replace(".", "", $request->nominal),
                "type" => "top_up",
                "in_out" => "in",
                "keterangan" => "Top Up",
                "status" => "success"
            ]);

            $userData = $userModel->first(["username", "name", "phone", "saldo"]);
            $userData->top_up_nominal = (int)$request->nominal;

            return $this->successResponse("Top Up Request Success", ["user_information" => $userData, "top_up_nominal" => $request->nominal]);
        }else{
            return $this->errorResponse($errTitle, "User Not Found");
        }

    }

    public function withdraw(Request $request){
        $validation = Validator::make($request->all(), [
            "username" => "required_without:phone",
            "phone" => "required_without:username|numeric",
            "rekening" => "rekening|numeric",
            "nominal" => "required|numeric"
        ],[
            "nominal.required" => "Nominal field parameter is required",
        ]);

        $errTitle = "Withdraw Request Failed";

        if($validation->fails()){
            return $this->errorResponse($errTitle, $validation->errors());
        }

        $userModel = User::where(function($query)use($request){
            if(!empty($request->phone) && (substr($request->phone, 0, 2) == "08")){
                $request->phone = "628".substr($request->phone, 2);
            }
            $query->where("username", $request->username)->orWhere("phone", $request->phone);
        });

        try{
            $user = $userModel->first();
        }catch(QueryException $e){
            return $this->errorResponse($errTitle, $e->getMessage(), 403);
        }

        if($user){
            try{
                $query = $userModel->decrement("saldo", str_replace(".", "", $request->nominal));
            }catch(Exception $e){
                return $this->errorResponse($errTitle, $e->getMessage());
            }

            $kode_mutasi = "withdraw_".date("Ymdhis");

            MutasiPengguna::create([
                "kode_mutasi" => $kode_mutasi,
                "user_sender" => "Withdraw",
                "user_receiver" => $user->user_id,
                "nominal" => str_replace(".", "", $request->nominal),
                "type" => "withdraw",
                "in_out" => "out",
                "keterangan" => json_encode(["no_rekening" => $request->rekening]),
                "status" => "success"
            ]);

            $userData = $userModel->first(["username", "name", "phone", "saldo"]);

            return $this->successResponse("Withdraw Request Success", ["user_information" => $userData, "withdraw_nominal" => $request->nominal]);
        }else{
            return $this->errorResponse($errTitle, "User Not Found");
        }
    }

    public function transfer(Request $request){
        $validation = Validator::make($request->all(), [
            "username_sender" => "required_without:phone_sender",
            "phone_sender" => "required_without:username_sender|numeric",
            "username_receiver" => "required_without:phone_receiver",
            "phone_receiver" => "required_without:username_receiver|numeric",
            "nominal" => "required|numeric"
        ]);

        $errTitle = "Transfer Request Failed";

        if($validation->fails()){
            return $this->errorResponse($errTitle, $validation->errors());
        }

        $userSenderModel = User::where(function($query)use($request){
            if(!empty($request->phone_sender) && (substr($request->phone_sender, 0, 2) == "08")){
                $request->phone_sender = "628".substr($request->phone_sender, 2);
            }
            $query->where("username", $request->username_sender)->orWhere("phone", $request->phone_sender);
        });

        $userReceiverModel = User::where(function($query)use($request){
            if(!empty($request->phone_receiver) && (substr($request->phone_receiver, 0, 2) == "08")){
                $request->phone_receiver = "628".substr($request->phone_receiver, 2);
            }
            $query->where("username", $request->username_receiver)->orWhere("phone", $request->phone_receiver);
        });

        try{
            $get_sender = $userSenderModel->first();
        }catch(QueryException $e){
            return $this->errorResponse($errTitle, $e->getMessage(), 403);
        }

        try{
            $get_receiver = $userReceiverModel->first();
            if(!$get_receiver){
                return $this->errorResponse($errTitle, "Username/Phone receiver not found");
            }
        }catch(QueryException $e){
            return $this->errorResponse($errTitle, $e->getMessage(), 403);
        }

        if($get_sender){
            try{
                $query = $userSenderModel->decrement("saldo", str_replace(".", "", $request->nominal));
                $query2 = $userReceiverModel->increment("saldo", str_replace(".", "", $request->nominal));
            }catch(Exception $e){
                return $this->errorResponse($errTitle, $e->getMessage());
            }

            $senderData = $userSenderModel->first(["username", "name", "phone", "saldo"]);
            $receiverData = $userReceiverModel->first(["name"]);
            if(!empty($request->phone_receiver)){
                $receiverData->phone = $request->phone_receiver;
            }else{
                $receiverData->username = $request->username_receiver;
            }

            $kode_mutasi = "transfer_".date("Ymdhis");

            MutasiPengguna::create([
                "kode_mutasi" => $kode_mutasi,
                "user_sender" => $senderData->user_id,
                "user_receiver" => $receiverData->user_id,
                "nominal" => str_replace(".", "", $request->nominal),
                "type" => "Transfer",
                "in_out" => "out",
                "keterangan" => "sender",
                "status" => "success"
            ]);

            MutasiPengguna::create([
                "kode_mutasi" => $kode_mutasi,
                "user_sender" => $senderData->user_id,
                "user_receiver" => $senderData->user_id,
                "nominal" => str_replace(".", "", $request->nominal),
                "type" => "Transfer",
                "in_out" => "in",
                "keterangan" => "receiver",
                "status" => "success"
            ]);

            return $this->successResponse("Transfer Request Success", ["sender_information " => $senderData, "receiver_information" => $receiverData, "transfer_nominal" => $request->nominal]);
        }else{
            return $this->errorResponse($errTitle, "Username/Phone sender not found");
        }
    }

    public function get_mutation_report(Request $request){
        $validation = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required",
            "mutation_type" => ["required", function($attribute, $value, $fail){
                $should = ["in", "out", "all"];
                if(!in_array($value, $should)){
                    $fail('Mutation type must be in/out/all');
                }
            }]
        ]);

        $errTitle = "Request User Mutation Failed";

        if($validation->fails()){
            return $this->errorResponse($errTitle, $validation->errors());
        }

        try{
            $user = User::where("email", $request->email)->first();
        }catch(QueryException $e){
            return $this->errorResponse($errTitle, $validation->errors());
        }

        if($user){
            if(decrypt($user->password) != $request->password){
                return $this->errorResponse($errTitle, "Wrong Password", 403);
            }

            $user_mutation['mutation_lists'] = MutasiPengguna::where(function($query)use($user, $request){
                $query->where('user_sender', $user->user_id)->orWhere('user_receiver', $user->user_id);
                if($request->mutation_type != "all"){
                    $query->where("type", $request->mutation_type);
                }
            })->get();

            return $this->successResponse("Request User Mutation Success", $user_mutation);
        }else{
            return $this->errorResponse($errTitle, "Email doesn't match with our records");
        }
    }
}
