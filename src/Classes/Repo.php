<?php

class Repo
{
    private $id;
    private $url;
    // The main programming language used in the repository
    private $lang;
    
    function __construct($id, $url, $lang)
    {
        $this->id = $id;
        $this->url = $url;
        $this->lang = $lang;
    }
    
    function getId()
    {
        return $this->id;
    }
    
    function getUrl()
    {
        return $this->url;
    }
    
    function getLang()
    {
        return $this->lang;
    }
}