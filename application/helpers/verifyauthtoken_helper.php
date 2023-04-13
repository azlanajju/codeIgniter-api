<?php
if(!function_exists('verifyauthtoken_helper')){
    function verifyauthtoken_helper($token){
        $jwt= new JWT();
		$jwtSecretKey ="mysecretkey";
        $verification = $jwt->decode($token, $jwtSecretKey, 'HS256');
        return $verification;

    }


}