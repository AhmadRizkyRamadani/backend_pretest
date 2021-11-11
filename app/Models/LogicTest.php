<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogicTest extends Model
{
    use HasFactory;

    public function primeCheck($val){
        if($val == 2){
            return true;
        }else if($val < 2){
            return false;
        }

        for($i = 2; $i<$val; $i++){
            if($val % $i === 0){
                return false;
            }else{
                return $val > 1;
            }
        }
    }

    public function checker($a, $b, $c){
        if($a && $b){
            if($this->primeCheck(substr($c, 3)) && ($this->last3Digits(substr($c, -3)) == FALSE)){
                return "TENGAH";
            }else if($this->last3Digits(substr($c, -3))){
                return "KANAN";
            }else if($this->last2Digits(substr($c, 3))){
                return "KIRI";
            }
        }else{
            return "REJECT";
        }
    }

    function last3Digits($x){
        $num = substr($x, -1);
        $sameDigits = 0;
        for($i = 0; $i<strlen($x); $i++){
            if($x[$i] == $num) $sameDigits++;
        }
        if($sameDigits == strlen($x)) return true;
        return false;
    }

    function last2Digits($x){
        $lastDigit = (int)substr($x, -1);
        $primeNum = $lastDigit;
        for($i = 1; $i<$lastDigit; $i++){
            $res = $this->primeCheck($i+$primeNum);
            if($res){
                $x += (string)$i+$primeNum;
                return true;
            }
        }
    }
}
