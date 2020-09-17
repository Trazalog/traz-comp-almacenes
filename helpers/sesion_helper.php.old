<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('empresa')){
    function empresa(){
        return 6; //!HARDCODE
        $ci =& get_instance();			
        $userdata  = $ci->session->userdata('user_data');
		return  $userdata[0]['id_empresa'];
    }
}

function usuario_bpm(){

    $ci =& get_instance();			
    $userdata  = $ci->session->userdata('user_data');
    return  $userdata[0]['userBpm'];
}