<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('lang_get')){

    function lang_get($leng, $page)
    {
        if($leng == 'spanish')
        {
            $resource = 'languageesp'; 
        }
        if($leng == 'english')
        {
            $resource = 'languageing'; 
        }
        $parametros["http"]["method"] = "GET";		 
        $param = stream_context_create($parametros);
       	
        $url = REST_PRD.$resource;
        $lang = file_get_contents($url, false, $param);
        $lang = json_decode($lang,true);
        $lang = $lang['labels']['label'];
        //var_dump($lang);die;
        $lenguaje =  array();
        if(!empty($lang)){
            for($i=0;$i<count($lang);$i++)
            { 
                $aux = array($lang[$i]['id']=> $lang[$i]['texto']);
                $lenguaje = array_merge($lenguaje,$aux);
            }
        }
        return $lenguaje;
    }
}