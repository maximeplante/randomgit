<?php

class Repo
{
    private $id;
    private $name;
    private $user;
    // The main programming language used in the repository
    private $lang;
    
    function __construct($id, $name, $user, $lang)
    {
        $this->id = $id;
        $this->name = $name;
        $this->user = $user;
        $this->lang = $lang;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getLang()
    {
        return $this->lang;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function getFullName()
    {
        return $this->user . '/' . $this->name;
    }
    
    public function getUrl()
    {
        return 'https://github.com/' . $this->getFullName();
    }
}