<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Representa Movimientos de Salida de Dapósitos
*
* @autor Hugo Gallardo
*/
class Movimdeposalida extends CI_Model {

	function __construct()
	{
		parent::__construct();
  }

	/**
	* Trae lotes de un articulo en un determinado deposito
	* @param string art_id y depo_id
	* @return array con info de lotes encontrados
	*/
	function traerLotes($arti_id, $depo_id){
		log_message('DEBUG','#TRAZA | TRAZ-COMP-ALMACEN | Movimdeposalida | traerLotes');
		$url = REST_ALM.'/deposito/'.$depo_id.'/articulo/'.$arti_id.'/lote/list';
		$aux = $this->rest->callAPI("GET",$url);
		$aux = json_decode($aux['data']);
		return $aux->lotes->lote;
	}

	/**
	* Guarda cabecera moviemiento depposito
	* @param array detalle cabecera
	* @return bool id de movimiento deposito
	*/
	function guardarCabecera($cabecera){

		log_message('DEBUG','#TRAZA|TRAZ-COMP-ALMACEN|MOVIMIENTODEPOSITOSAL|guardarCabecera($cabecera)  $cabecera: >> '.json_encode($cabecera));

		$aux = $this->rest->callAPI("POST",REST_ALM."/movimientoInterno", $cabecera);
		$id =json_decode($aux["data"]);
		return $id->GeneratedKeys->Entry[0]->ID;
	}

	/**
	* Guarda detalle de movimiento deposito
	* @param array detalle de articulos
	* @return bool true or false respuesta de servicios
	*/
	function guardarDetalle($detalle){

		$post['_post_movimientointerno_detalle_batch_req']['_post_movimientointerno_detalle'] = $detalle;
		log_message('DEBUG','#TRAZA|TRAZ-COMP-ALMACEN|MOVIMIENTODEPOSITOSSAL|guardarDetalle($detalle)  $post: >> '.json_encode($post));
		$aux = $this->rest->callAPI("POST",REST_ALM."/_post_movimientointerno_detalle_batch_req", $post);
		$aux =json_decode($aux["status"]);
		return $aux;
	}

	/**
	* Descuenta de lotes las cantidaddes porarticulo
	* @param array con art_id y cantidaddes
	* @return bool true o false segun respuesta de servicio
	*/
	function descontarEnLote($descuento){

		$post['_post_lote_descontar'] = $descuento;
		log_message('DEBUG','#TRAZA|TRAZ-COMP-ALMACEN|MOVIMIENTODEPOSITOSSAL  descontarEnLote($descuento)  $post >> '.json_encode($post));
		$aux = $this->rest->callAPI("POST",REST_ALM."/lote/descontar", $post);
		$aux =json_decode($aux["data"]);
		return $aux->UpdatedRowCount->Value;
	}
	
	/**
	* Trae todos los articulos para un determinado deposito seleccionado
	* @param string depo_id
	* @return array de articulos encontrados para un deposito
	*/
	function getArticulosDeposito($depo_id){
		$url = REST_ALM.'/stock/articulo/TODOS/deposito/'.$depo_id.'/tipo/TODOS';
		$aux = $this->rest->callAPI("GET",$url);
		$aux = json_decode($aux['data']);
		return $aux->stock->articulo;
	}

	/**
	* Trae el siguiente numero de comprobante por empresa
	* @param int empr_id
	* @return array suiguiente numero de comprobante 
	*/
	function getUltimoNroComprobante($empr_id){
		$url = REST_ALM.'/movimientosalida/nrocomprobante/'.$empr_id;
		$aux = $this->rest->callAPI("GET",$url);
		$aux = json_decode($aux['data']);
		return $aux->siguiente_numerocomprobante->numerocomprobante;
	}

	/**
	* Obtiene los datos cargados en core.tablas por empr_id
	* @param string columna tabla a buscar
	* @return array listado de coincidencias
	*/
    public function obtenerTablaEmpr_id($tabla)
    {
        $url = REST_CORE."/tabla/$tabla/empresa/".empresa();
		$aux = $this->rest->callAPI("GET",$url);
		$aux = json_decode($aux['data']);
		return $aux->tablas->tabla;
        //return wso2($url);
    }

	/**
	* Obtiene los datos de movimientos internos por demi_id para reimprimir el remito
	* @param string demi_id
	* @return array listado de coincidencias
	*/
	public function getDatosRemito($data){
		$url = REST_ALM."/getmovimientointerno/demi/$data/$data/$data/".empresa();
		$aux = $this->rest->callAPI("GET",$url);
		$aux = json_decode($aux['data']);
		return $aux->movimientosInternos->movimientoInterno;
	}
}