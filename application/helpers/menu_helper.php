<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('menu')){

    function menu($json)
    {
        $array =  json_decode($json);

        $html = '<ul>';

        foreach ($array as $i) {

            switch ($i['nivel']) {
                case 1:
                    $html .= '';
                    break;
                case 2:
                    $html .= '';
                    break;   
                default:
                    # code...
                    break;
            

        }

        return $html.'</ul>';
    }

    function submenu($data)
    {
        $html = '<ul>';
        foreach ($data as $i) {
            $html.= '';
        }
        return $html.'</ul>';
    }



}