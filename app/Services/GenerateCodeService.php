<?php
    namespace App\Services;
    class GenerateCodeService{
        public static function getCode(){
            return (config('app.env')!='local')? mt_rand(1000,9999) : mt_rand(1000,9999);
        }
    }
