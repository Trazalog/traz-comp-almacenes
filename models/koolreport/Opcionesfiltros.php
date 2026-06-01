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
    $desde = !empty($data["desde"]) ? date("Y-m-d", strtotime($data["desde"])) : "1900-01-01";
    $hasta = !empty($data["hasta"]) ? date("Y-m-d", strtotime($data["hasta"])) : date("Y-m-d");
    $depo_id = $data["depo_id"];
    $tipo = $data["tipo_mov"];
    $arti_id = $data['arti_id'];
    $lote_id = $data['lote_id'];
    $empr_id = empresa();
    //http://10.142.0.13:8280/services/ALMDataService/movimientos/tipo/TODOS/desde/2021-04-01/hasta/2021-04-30/deposito/TODOS/articulo/TODOS/lote/TODOS
    $url = '/movimientos/tipo/'.$tipo.'/desde/'.$desde.'/hasta/'.$hasta.'/deposito/'.$depo_id.'/articulo/'.$arti_id.'/lote/'.$lote_id.'/empresa/'.$empr_id;
    $aux = $this->rest->callAPI("GET",REST_ALM.$url);
    $aux =json_decode($aux["data"]);
    return $aux->movimientos->movimiento;
  }

  /**
  * Devuelve información filtrada y paginada directamente desde WSO2
  * @param array parametros para filtrar, incluyendo limit y offset
  * @return array con datos filtrados y paginados
  */
  function getHistoricoArticulosPaginado($data)
  {
    log_message('DEBUG','#TRAZA|TRAZ-COMP-ALMACENES|OPCIONESFILTROS|getHistoricoArticulosPaginado($data)| $data: >> '.json_encode($data));
    $desde = !empty($data["desde"]) ? date("Y-m-d", strtotime($data["desde"])) : "1900-01-01";
    $hasta = !empty($data["hasta"]) ? date("Y-m-d", strtotime($data["hasta"])) : date("Y-m-d");
    $depo_id = !empty($data["depo_id"]) ? $data["depo_id"] : 'TODOS';
    $tipo = !empty($data["tipo_mov"]) ? $data["tipo_mov"] : 'TODOS';
    $arti_id = !empty($data['arti_id']) ? $data['arti_id'] : 'TODOS';
    $lote_id = !empty($data['lote_id']) ? $data['lote_id'] : 'TODOS';
    $empr_id = empresa();
    $offset = isset($data['offset']) ? $data['offset'] : '0';
    $limit = isset($data['limit']) ? $data['limit'] : '10';

    $url = '/movimientos/paginados/tipo/'.$tipo.'/desde/'.$desde.'/hasta/'.$hasta.'/deposito/'.$depo_id.'/articulo/'.$arti_id.'/lote/'.$lote_id.'/empresa/'.$empr_id.'/'.$offset.'/'.$limit;
    $aux = $this->rest->callAPI("GET", REST_ALM.$url);
    $aux = json_decode($aux["data"]);
    return isset($aux->movimientos->movimiento) ? $aux->movimientos->movimiento : array();
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
    $esta_id = $data["esta_id"];//No se usa en el service
    $depo_id = $data["depo_id"];
    $arti_id = $data["arti_id"];
    $tipo = urlencode($data["tipo"]);
    $estado = $data["estado"]; //No se usa en el service
    
    $url = '/lotes/articulos/'.$arti_id.'/tipo/'.$tipo.'/deposito/'.$depo_id.'/vencimiento/desde/'.$fec1.'/hasta/'.$fec2;

    $aux = $this->rest->callAPI("GET",REST_ALM.$url);
    $aux = json_decode($aux["data"]);

    return $aux->lotes->lote;
  }

  /**
  * devueleve datos de un movimiento interno por su demi_id
  * @param string demi_id
  * @return array con datos del movimiento interno
  */
  function getDataMovimientoInterno($data){
    $demi_id = $data;
    $url = '/movimientoInternoData/'.$demi_id;
    $aux = $this->rest->callAPI("GET",REST_ALM.$url);
    $aux = json_decode($aux["data"]);
    return $aux->detallesMovimientosInternos->detalleMovimientoInterno;
  }
}
