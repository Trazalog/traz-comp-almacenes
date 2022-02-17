<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Tipoajustes extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerAjustes()
    {
        $url = REST_ALM.'/stock/ajuste/tipo/list';
        $rsp = $this->rest->callAPI("GET", $url);
        if(!$rsp["status"]) return $rsp;
        
        $rsp["data"] = json_decode($rsp["data"])->tiposAjuste->tipoAjuste;

        log_message('DEBUG','#TRAZA | TRAZ-COMP-ALMACENES | Tipoajustes | obtenerAjustes() >> DATA rsp  '.json_encode($rsp['data']));

        return $rsp;
        
    }
    
}