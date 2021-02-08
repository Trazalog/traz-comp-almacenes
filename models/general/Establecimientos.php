<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Establecimientos extends CI_Model
{
    function __construct()
    {
      parent::__construct();
    }
    public function listar()
    {
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
    public function obtenerDepositos($idestablecimiento)
    {
      $url = REST_ALM.'/establecimiento/'.$idestablecimiento.'/deposito/list';
      $array = $this->rest->callAPI("GET",$url);
      $resp =  json_decode($array['data']);
      return $resp;
    }
    /**
    * Devuelve listado de depositos de una Empresa
    * @param
    * @return array listado de depositos
    */
    function obtenerDepositoPorEmp()
    {
      $empr_id = empresa();
      $aux = $this->rest->callAPI("GET",REST_ALM."/empresa/".$empr_id."/depositos/list");
      $aux =json_decode($aux["data"]);
      return $aux->depositos->deposito;
    }

}