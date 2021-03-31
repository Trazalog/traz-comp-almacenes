<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* - Clase de donde se enviaran los datos necesarios para el filtrado
* - Devuelve ademas los datos ya filtrados
*
* @autor Hugo Gallardo
*/
class Opcionesfiltros extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->helper('wso2_helper');
    $this->load->helper('sesion_helper');
  }

  /**
	* Trae lotes de un articulo en un determinado deposito
	* @param string art_id y depo_id
	* @return array con info de lotes encontrados
	*/
	function traerLotes($arti_id, $depo_id){

		$url = REST_ALM.'/deposito/'.$depo_id.'/articulo/'.$arti_id.'/lote/list';
		$aux = $this->rest->callAPI("GET",$url);
		$aux =json_decode($aux['data']);
		return $aux->lotes->lote;
	}

  /**
  * Develve info filtrada por parametros recibidos
  * @param array parametros para filtrar la lista
  * @return array con datos filtrados
  */
  function getHistoricoArticulos($data)
  {
    log_message('DEBUG','#TRAZA|TRAZ-COMP-ALMACENES|OPCIONESFILTROS|getHistoricoArticulos($data)| $data: >> '.json_encode($data));
    $desde = date("Y-m-d", strtotime($data["desde"]));
    $hasta = date("Y-m-d", strtotime($data["hasta"]));
    $depo_id = $data["depo_id"];
    $tipo = $data["tipo_mov"];
    $url = '/movimientos/tipo/'.$tipo.'/desde/'.$desde.'/hasta/'.$hasta.'/deposito/'.$depo_id;
    $aux = $this->rest->callAPI("GET",REST_ALM.$url);
    $aux =json_decode($aux["data"]);
    return $aux->movimientos->movimiento;
  }




  /**
  * Devuelveino filtrada por parametros recibidos
  * @param array con parametros para filtrar la lista
  * @return array con datos filtrados
  */
  function getArticulosVencidos($data)
  {
    log_message('DEBUG','#TRAZA|TRAZ-COMP-ALMACENES|OPCIONESFILTROS|getArticulosVencidos($data)| $data: >> '.json_encode($data));
    $fec1 = date("Y-m-d", strtotime($data["desde"]));
    $fec2 = date("Y-m-d", strtotime($data["hasta"]));
    //$esta_id = $data["esta_id"];
    $esta_id = 'TODOS';
    //$depo_id = $data["depo_id"];
    $depo_id = 'TODOS';
    //$arti_id = $data["arti_id"];
    $arti_id = '83';
    $tipo = $data["tipo"];
    $tipo = 'tipo_articuloMateria Prima';
    //$estado = $data["estado"];
    $url = urlencode('/lotes/articulos/'.$arti_id.'/tipo/'.$tipo.'/deposito/'.$depo_id.'/vencimiento/desde/'.$fec1.'/hasta/'.$fec2);
    //$url = '/lotes/articulos/'.$arti_id.'/tipo/'.$tipo.'/deposito/'.$depo_id.'/vencimiento/desde/'.$fec1.'/hasta/'.$fec2;
    $aux = $this->rest->callAPI("GET",REST_ALM.$url);
    $aux =json_decode($aux["data"]);
    return $aux->lotes->lote;
    //tipo_articuloMateria Prima
  }
}
