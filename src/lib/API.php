<?php

/* Converts data to the output format required by the client.
 * (without the json encoding)
 */
abstract class API
{
    // Convert an array of Repo objects
    static function convertRepoArray($repoList)
    {
        $data = array();
        
        foreach ($repoList as $repo) {
            array_push($data,
             self::convertRepo($repo)
            );
        }
        
        return $data;
    }
    
    // Converts a Repo object
    static function convertRepo(Repo $repo)
    {
        return array(
         'name' => $repo->getName(),
         'user' => $repo->getUser(),
         'description' => $repo->getDescription(),
         'url' => $repo->getUrl(),
         'lang' => $repo->getLang(),
         'readme_html' => $repo->getReadmeHTML()
        );
    }
    
    static function errorMessage($message)
    {
        return array(
         'error' => array(
           'message' => $message
          )
        );
    }
}