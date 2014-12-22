<?php

abstract class Helper
{
    public static function randomAlphaNumString($length)
    {
        $chars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
        
        $string = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomChar = $chars[rand(0, count($chars) - 1)];
            $string .= $randomChar;
        }
        
        return $string;
    }
}