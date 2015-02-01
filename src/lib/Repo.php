<?php

class Repo
{
    private $id;
    private $name;
    private $description;
    private $user;
    // The main programming language used in the repository
    private $lang;
    // The HTML version of the readme
    private $readme_html;
    
    function __construct($id, $name, $description, $user, $lang, $readme_html)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->user = $user;
        $this->lang = $lang;
        $this->readme_html = $readme_html;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function getLang()
    {
        return $this->lang;
    }
    
    public function getReadmeHTML()
    {
        return $this->readme_html;
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