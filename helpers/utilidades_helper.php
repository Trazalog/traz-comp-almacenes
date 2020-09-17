<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('msj')){

    function msj($status, $msj, $data = false)
    {
        return array(
            'status' => $status,
            'msj' => $msj,
            'data' => $data
        );
    }
}
