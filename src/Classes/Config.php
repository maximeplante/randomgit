<?php

class Config
{
    function __construct()
    {
        $dbConnect = parse_ini_file(dirname(__FILE__) . '../Config/dbConnect.ini');
        
    }
}