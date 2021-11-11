<?php

namespace App\Http\Controllers;

use App\Models\LogicTest;
use Illuminate\Http\Request;

class LogicTestController extends Controller
{
    public function index(){
        return view("logic_test/index");
    }

    public function check(Request $request){
        $logic_test = new LogicTest();

        $user_input = $request->user_input;
        $a = $b = $c = "";
        $true_case = 0;
        $false_case = 0;
        for($i = 0; $i<strlen($user_input); $i++){
            if($user_input[$i] == 2){
                $true_case++;
            }else{
                $result = $logic_test->primeCheck($user_input[$i]);
                if($result === false){
                    $false_case++;
                }else{
                    $true_case++;
                }
            }
        }
        $a = $true_case > 1;
        $b = !str_contains($user_input, 0);
        $c = $user_input;
        $result = $logic_test->checker($a, $b, $c);

        return json_encode(["status" => "success", "data" => $result]);
    }
}
