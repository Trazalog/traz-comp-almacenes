<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Representa Movimientos de Entrada de Dapósitos
*
* @autor Hugo Gallardo
*/
class Movimdeporecepcion extends CI_Model {

	function __construct()
	{
		parent::__construct();
  }

	/**
	* Trae info de movimientos salida de deposito segun depo y estab receptor
	* @param  string id establecimiento e id deposito destino
	* @return array con info de movimiento salida
	*/
	public function Get_Recpciones($id_dest_esta, $id_dest_depo)
	{
			$empr_id = empresa();
			$url = REST_ALM.'/movimientosInternos/estado/EN_CURSO/origen/TODOS/destino/'.$id_dest_depo.'/moin_id/TODOS/empresa/'.$empr_id;
      $array = $this->rest->callAPI("GET",$url);
      $resp =  json_decode($array['data']);
      return $resp->movimientosInternos->movimientoInterno;
	}

	function traerLotes($arti_id, $depo_id){

		$url = REST_ALM.'/deposito/'.$depo_id.'/articulo/'.$arti_id.'/lote/list';
		$aux = $this->rest->callAPI("GET",$url);
		$aux =json_decode($aux['data']);
		return $aux->lotes->lote;
	}

	/**
	* Actualiza cabecera de movimiento interno
	* @param array con datos principales
	* @return bool true o false rsultado de servicio
	*/
	function guardarCabecera($cabecera){

		$post['_put_movimientointerno'] = $cabecera;
		log_message('DEBUG','#TRAZA|TRAZ-COMP-ALMACEN|MOVIMIENTODEPOSITOSRECEP|guardarCabecera($cabecera) $post: >> '.json_encode($post));
		$url = REST_ALM.'/movimientoInterno';
		$aux = $this->rest->callAPI("PUT", $url, $post);
		$aux =json_decode($aux["status"]);
		return $aux;
	}

	/**
	* Actualiza lotes (agregando o creandolotes nuevos sino los hay)
	* @param	array con datos de lotes
	* @return bool true o false resultado del servicio
	*/
	function actualizarLotes($lotes)
	{
		$post["_post_lote_agregaractualizar"] = $lotes;
		log_message('DEBUG','#TRAZA|TRAZ-COMP-ALMACEN|MOVIMIENTODEPOSITOSRECEP|actualizarLotes($lotes) $post: >> '.json_encode($post));
		$url = REST_ALM.'/lote/agregarActualizar';
		$aux = $this->rest->callAPI("POST",$url, $post);
		$aux =json_decode($aux["status"]);
		return $aux;
	}

	/**
	* Averigua el lote_id generadoal insertarun movim de deposito
	* @param string $prov_id, $arti_id, $depo_id, $cod_lote
	* @return string lote_id
	*/
	function getLoteIdGenerado($prov_id, $arti_id, $depo_id, $cod_lote){

		$empr_id = empresa();
		$url = REST_ALM.'/lote/id/prov_id/'.$prov_id.'/arti_id/'.$arti_id.'/depo_id/'.$depo_id.'/empr_id/'.$empr_id.'/cod_lote/'.$cod_lote;
		$aux = $this->rest->callAPI("GET",$url);
		$aux =json_decode($aux["data"]);
		return $aux->lote->lote_id;
	}

	/**
	* Actualiza detalle del movimiento interno
	* @param array detalle de movimiento
	* @return bool true o false resultado del servicio
	*/
	function guardarDetalle($detalle){

		$post['_put_movimientointerno_detalle'] = $detalle;
		log_message('DEBUG','#TRAZA|TRAZ-COMP-ALMACEN|MOVIMIENTODEPOSITOSRECEP|guardarDetalle($detalle) $post:  >> '.json_encode($post));
		$url = REST_ALM.'/movimientoInterno/detalle';
		$aux = $this->rest->callAPI("PUT", $url, $post);
		$aux = json_decode($aux["status"]);
		return $aux;
	}
}