<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Establecimientos extends CI_Model
{
    function __construct()
    {
      parent::__construct();
    }

    /**
    * Devuelve listado de establecimientos de una Empresa
    * @param
    * @return array listado de depositos
    */
    public function listar(){
      log_message('DEBUG','#TRAZA | TRAZ-COMP-ALMACENES | Establecimientos | listar()');
      $empr_id = empresa();
      $url = REST_ALM.'/establecimientos/empresa/'.$empr_id;
      $array = $this->rest->callAPI("GET",$url);
      $resp =  json_decode($array['data']);
      return $resp;
    }
    public function listarTodo()
    {
        $resource = 'establecimiento';
        $url = REST.$resource;
        $array = $this->rest->callAPI("GET",$url);
        $resp =  json_decode($array['data']);
        return $resp;
    }
    /**
    * Devuelve listado de depositos por su encargado y establecimiento
    * @param
    * @return array listado de depositos
    */
    public function obtenerDepositos($idestablecimiento){
      $url = REST_ALM.'/depositos/empresa/'.empresa().'/encargado/'.$_SESSION['id'].'/establecimiento/'.$idestablecimiento;
      $array = $this->rest->callAPI("GET",$url);
      $resp =  json_decode($array['data']);
      return $resp;
    }
    /**
    * Devuelve listado de depositos de una Empresa y por encargado
    * @param
    * @return array listado de depositos
    */
    function obtenerDepositoPorEmp(){
      log_message('DEBUG','#TRAZA | TRAZ-COMP-ALMACENES | Establecimientos | obtenerDepositoPorEmp()');
      $empr_id = empresa();
      $aux = $this->rest->callAPI("GET",REST_ALM."/empresa/".$empr_id."/encargado/".$_SESSION['id']."/depositos/list");
      $aux =json_decode($aux["data"]);
      return $aux->depositos->deposito;
    }

}