<?php

namespace ON;

class Crypt
{
    // Criptografar string
    public static function strCrypt($str)
    {
        $str = base64_encode($str);
        $str = base64_decode($str);
        $str = str_rot13($str);
        $str = base64_encode($str);
        $str = str_rot13($str);

        return $str;
    }

    // Descriptografar string
    public static function strDecrypt($str)
    {
        $str = str_rot13($str);
        $str = base64_decode($str);
        $str = str_rot13($str);
        $str = base64_encode($str);
        $str = base64_decode($str);

        return $str;
    }

    public static function blowfish($string, $custo = 10)
    {
        $seed = uniqid(rand(), true);
        $salt = base64_encode($seed);
        $salt = str_replace('+', '.', $salt);
        $salt = substr($salt, 0, 22);
        $crypt = crypt($string, '$2a$'.$custo.'$'.$salt.'$');

        return $crypt;
    }

    public static function blowfishCheck($string, $hash)
    {
        return crypt($string, $hash) === $hash;
    }
}
