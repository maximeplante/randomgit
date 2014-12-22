<?php

class Repo
{
    private $id;
    private $url;
    
    function __construct($id, $url)
    {
        $this->id = $id;
        $this->url = $url;
    }
    
    function getId()
    {
        return $this->id;
    }
    
    function getUrl()
    {
        return $this->url;
    }
}