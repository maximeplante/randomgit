<?php

abstract class API
{
    static function random(RepoCache $repoCache, $limit = 1, $language = null)
    {
        $repoList = array();
        
        // Fills the array with the data of the repositories
        for ($i = 0; $i < $limit; $i++) {
            
            $randomRepo = $repoCache->randomRepo($language);
            
            $repo = array(
                'name' => $randomRepo->getName(),
                'user' => $randomRepo->getUser(),
                'description' => $randomRepo->getDescription(),
                'url' => $randomRepo->getUrl(),
                'lang' => $randomRepo->getLang(),
                'readme_html' => $randomRepo->getReadmeHTML()
            );
            
            array_push($repoList, $repo);
            
        }
        
        return json_encode($repoList);
    }
}