<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* Representa Movimientos de Salida de Dapósitos
*
* @autor Hugo Gallardo
*/
class Movimientodeposalida extends CI_Controller {

	function __construct()
  {
		parent::__construct();
    // $this->load->model(ALM.'Movimientodeposalida');
		 $this->load->model('general/Establecimientos');
		 $this->load->model('traz-comp/Componentes');
		 $this->load->model('Tablas');
		 $this->load->model('Movimdeposalida');
	}

	/**
	* Dibuja pantalla Salida de Deposito con stock de articulos por deposito
	* @param
	* @return view salida de depositos
	*/
	public function index()
	{
    	$data['items'] = $this->Componentes->listaArticulos();
		$estabList = $this->Establecimientos->listar();
		$data['establecimiento'] = $estabList->establecimientos->establecimiento;
		$data['depositos'] = $this->Establecimientos->obtenerDepositoPorEmp();
		$unidades = $this->Tablas->obtenerTabla('unidades_medida');
		$data['unidades'] = $unidades['data'];
		$this->load->view(ALM.'/depositos/MovimientoSalida', $data);
	}

  /**
	* Trae listado de depositos por id de Estabelcimiento
	* @param int id establecimiento
	* @return array listado de depositos
	*/
	public function traerDepositos()
	{
		$id = $this->input->post('id_esta');
		$resp = $this->Establecimientos->obtenerDepositos($id);
		echo json_encode($resp->depositos->deposito);
	}

	/**
	* Trae listado de depositos por id de Estabelcimiento
	* @param int id establecimiento
	* @return array listado de depositos
	*/
	public function obtenerDepositosAll()
	{
		$id = $this->input->post('id_esta');
		$resp = $this->Establecimientos->obtenerDepositosAllxEstablecimiento($id);
		echo json_encode($resp->depositos->deposito);
	}

	/**
	* Trae lotes de un articulo en un determinado deposito
	* @param strin art_id y depo_id
	* @return array con info de lotes encontrados
	*/
	public function traerLotes(){

		$arti_id = $this->input->post('arti_id');
		$depo_id = $this->input->post('depo_id');
		$resp = $this->Movimdeposalida->traerLotes($arti_id, $depo_id);
		echo json_encode($resp);
	}

	/**
	* Guarda salida deposito y descuenta en lote
	* @param array detalle de artic, lotes
	* @return bool true or false respuesta de servicios
	*/
	public function guardar(){

		$data['_post_movimientointerno'] = $this->input->post('cabecera');
		$data['_post_movimientointerno']['empr_id'] = empresa();
		$data['_post_movimientointerno']['usuario_app'] = userNick();
		log_message('DEBUG','#TRAZA|ALM|MOVIMIENTODEPOSALIDAIDA|guardar()  $data >> '.json_encode($data));
		$moin_id = (string) $this->Movimdeposalida->guardarCabecera($data);
		if ($moin_id == null) {
			log_message('ERROR','#TRAZA|TRAZ-COMP-ALMACEN|MOVIMIENTODEPOSALIDA|guardar() >> ERROR EN GUARDADO DE CABECERA');
			echo json_encode(['status' => false, 'data' => 'Error al guardar Cabecera de Movimiento...']);
			return;
		}

		$post_detalle = $this->input->post('detalle');
		$detalle = $this->armarDetalle($post_detalle, $moin_id);
		$resp = $this->Movimdeposalida->guardarDetalle($detalle);
		if (!$resp) {
			log_message('ERROR','#TRAZA|TRAZ-COMP-ALMACEN|MOVIMIENTODEPOSALIDA|guardar() >> ERROR EN GUARDADO DE DETALLE');
			echo json_encode(['status' => false, 'data' => 'Error al guardar Detalle de Movimiento...']);
			return ;
		}

		$resp_desc = $this->descontarLote($post_detalle);
		echo json_encode(['status' => $resp_desc, 'data' => 'Guardado Exitoso...']);
	}

	/**
	* Commpleta array con detalle a guardar
	* @param arrary con detalle
	* @return array completo
	*/
	function armarDetalle($detalle, $moin_id){

		foreach ($detalle as $value) {

				$a = json_decode($value);
				$tmp['codigo'] = $a->codigo;
				$tmp['cantidad'] = $a->cantidad;
				$tmp['arti_id'] = $a->arti_id;
				$tmp['lote_id_origen'] = $a->lote_id_origen;
				$tmp['moin_id'] = $moin_id;
				$tmp['usuario_app'] = userNick();
				$item[] = $tmp;
		}

		return $item;
	}

	/**
	* Descuenta articulos de lote por arti_id
	* @param array articulos
	* @return bool true o false segun respuesta de servicio
	*/
	function descontarLote($detalle){

		foreach ($detalle as $value) {

			$a = json_decode($value);
			$tmp['cantidad'] = $a->cantidad;
			$tmp['lote_id'] = $a->lote_id_origen;
			$item[] = $tmp;
			$resp_det = $this->Movimdeposalida->descontarEnLote($item);
			if (!$resp_det) {
				return $resp_det;
				log_message('ERROR','#TRAZA|TRAZ-COMP-ALMACENES|MOVIMIENTODEPOSALIDA|descontarLote($detalle) >> ERROR: No desconto en lote la cantidad enviada ');
			}
			unset($item);
		}

		return $resp_det;
	}

	/**
	* Levanta vista de impresion
	* @param array datos de salida deposito
	* @return view para impresion
	*/
	function imprimir()
	{     
		log_message('INFO','#TRAZA|TRAZ-COMP-ALMACEN|MOVIMIENTODEPOSALIDA|IMPRIMIR  >> ');

		$datos['cabecera'] = $this->input->post('cabecera');
		$datos['detalle'] = $this->input->post('detalle');

		$this->load->view(ALM.'/notapedido/printSalidaDeposito');
	}

	/**
	* Trae todos los articulos para deposito seleccionado con su stock correspondiente
	* @param strin depo_id
	* @return array de articulos encontrados para un deposito
	*/
	public function getArticulosDeposito(){

		$depo_id = $this->input->post('depo_id');
		$resp = $this->Movimdeposalida->getArticulosDeposito($depo_id);
		echo json_encode($resp);
	}

	/**
	* Trae siguiente numero de comprobante por empresa
	* @param int empr_id
	* @return array siguiente numero de comprobante
	*/
	public function getNroComprobante(){

		$empr_id = empresa();
		$resp = $this->Movimdeposalida->getUltimoNroComprobante($empr_id);
		echo json_encode($resp);
	}

	/**
	* Trae datos de la empresa para el remito
	* @param 
	* @return array datos de empresa de core.tablas
	*/
	public function getDatosCabeceraRemito(){
		$data['logo'] = $this->Movimdeposalida->obtenerTablaEmpr_id('remito_logo')[0];
		$data['direccion'] = $this->Movimdeposalida->obtenerTablaEmpr_id('remito_direccion')[0];
		$data['telefono'] = $this->Movimdeposalida->obtenerTablaEmpr_id('remito_telefono')[0];
		$data['email'] = $this->Movimdeposalida->obtenerTablaEmpr_id('remito_email')[0];
		$data['texto_pie_remito'] = $this->Movimdeposalida->obtenerTablaEmpr_id('texto_pie_remito')[0];
		
		echo json_encode($data);
	}
}