<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* Representa Movimientos de Entrada de Dapósitos
*
* @autor Hugo Gallardo
*/
class Movimientodeporecepcion extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		//$this->load->model(ALM.'/Movimientodeporecepcion');
		$this->load->model('general/Establecimientos');
		$this->load->model('traz-comp/Componentes');
		$this->load->model('Tablas');
		$this->load->model('Movimdeporecepcion');
	}

	/**
	* Dibuja la pantalla movimiento Recepcion de deposito
	* @param 
	* @return 
	*/
	public function index()
	{
    $data['items'] = $this->Componentes->listaArticulos();
		$estabList = $this->Establecimientos->listar();
		$data['establecimiento'] = $estabList->establecimientos->establecimiento;
		$data['depositos'] = $this->Establecimientos->obtenerDepositoPorEmp();
		$unidades = $this->Tablas->obtenerTabla('unidades_medida');
		$data['unidades'] = $unidades['data'];

		$this->load->view(ALM.'/depositos/MovimientoRecepcion', $data);
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
	* Trae info de movimientos salida de deposito segun depo y estab receptor
	* @param  string id establecimiento e id deposito destino
	* @return array con info de movimiento salida
	*/
	public function traerRecepciones()
	{
		$id_dest_esta = $this->input->post('id_esta_dest');
		$id_dest_depo = $this->input->post('id_depo_dest');
		$otralist = $this->Movimdeporecepcion->Get_Recpciones($id_dest_esta, $id_dest_depo);
		$movim = $otralist[0]->detallesMovimientosInternos->detalleMovimientoInterno;
		echo json_encode($otralist);
	}

	public function traerLotes(){
		$arti_id = $this->input->post('arti_id');
		$depo_id = $this->input->post('depo_id');
		$resp = $this->Movimdeporecepcion->traerLotes($arti_id, $depo_id);
		echo json_encode($resp);
	}
	/**
	* Actualiza datos de movimientos de recepcion
	* @param array con datos de actualizacion
	* @return array respuesta devolucion de servicios
	*/
	public function guardar(){

		// actualizacion cabecera movimiento
		$cabecera = $this->input->post("cabecera");
		$resp = $this->Movimdeporecepcion->guardarCabecera($cabecera);
		if (!$resp) {
			log_message('ERROR','#TRAZA|TRAZ-COMP-ALMACEN|MOVIMIENTODEPORECEP|guardar() >> ERROR de no actualizó la cabecera de movimiento de deposito');
			echo json_encode(['status' => $resp, 'data' => 'Error en guardado de cabecera...']);
			return;
		}

		// modificacion lotes y de detalle movimiento
		$resp = $this->updateLotesDetalles($this->input->post("detalle"));
		if (!$resp['status']) {
			log_message('ERROR','#TRAZA|TRAZ-COMP-ALMACEN|MOVIMIENTODEPORECEP|guardar() >> ERROR de no actualizó la cabecera de movimiento de deposito');
			echo json_encode($resp);
			return;
		}

		echo json_encode($resp);
	}

	/**
	* Updatea lotes y detalles de movimientos de recepcion
	* @param array detalle de movimiento
	* @return array con respuesta de servicios
	*/
	function updateLotesDetalles($detalote){

		$error = 0;

		foreach ($detalote as $value) {

			// actualiza lotes
			$lotes = $this->armarDatosLote($value);// probablemente no ande pide fecha-hora
			$resp_set_lote = $this->Movimdeporecepcion->actualizarLotes($lotes);
			if(!$resp_set_lote){
				log_message('ERROR','#TRAZA|TRAZ-COMP-ALMACEN|MOVIMIENTODEPORECEP|updateLotesDetalles($detalote) >> ERROR: no actualizo lote en modelo');
				$resp = array('status' => $resp, 'data' => 'Error No Actualizó Lote...');
				$error = 1;
				break;
			}

			//traer id de lote
			$lote_id = $this->getLoteIdGenerado($value);// cuando no encuentra devuelve null
			if ($lote_id == null) {
				log_message('ERROR','#TRAZA|TRAZ-COMP-ALMACEN|MOVIMIENTODEPORECEP|updateLotesDetalles($detalote) >> ERROR: no devolvio $lote_id desde modelo');
				$resp = array('status' => false, 'data' => 'Error No Devuelve Codigo de Lote...');
				$error = 1;
				break;
			}

			// armar detalle
			$detalle = $this->armarDatosDetalle($lote_id, $value);
			$resp_detalle = $this->Movimdeporecepcion->guardarDetalle($detalle);
			if (!$resp_detalle) {
				log_message('ERROR','#TRAZA|TRAZ-COMP-ALMACEN|MOVIMIENTODEPORECEP|updateLotesDetalles($detalote) >> ERROR: no guardo detalle de movimiento...');
				$resp = array('status' => $resp, 'data' => 'Error en Guardado detalle de Movimiento Interno...');
				$error = 1;
				break;
			}
		}

		// respuesta
		if($error){
			return $resp;
		}else{
			$resp = array('status' => true, 'data' => 'Guardado con Exito...');
			return $resp;
		}
	}

	/**
	* Trata datos y arma array para actualizar lotes
	* @param array con datos
	* @return array con datos tratados
	*/
	function armarDatosLote($data){

		$data['empr_id'] = empresa();
		$data['cantidad'] = $data['cantidad_recibida'];
		unset($data["cantidad_recibida"]);
		unset($data["demi_id"]);
		unset($data["cantidad_cargada"]);
		return $data;
	}

	/**
	* Devuleve lote_id generado segn parametros
	* @param array con datos
	* @return string lote_id
	*/
	function getLoteIdGenerado($data){

		$prov_id = $data['prov_id'];
		$arti_id = $data['arti_id'];
		$depo_id = $data['depo_id'];
		$cod_lote = $data['cod_lote'];
		$lote_id = $this->Movimdeporecepcion->getLoteIdGenerado($prov_id, $arti_id, $depo_id, $cod_lote);
		if ($lote_id == null) {
				log_message('ERROR','#TRAZA|TRAZ-COMP-ALMACEN|MOVIMIENTODEPORECEP|getLoteIdGenerado($data) >> ERROR NO DEVOLVIO $lote_id desde servicio');
		}
		return $lote_id;
	}

	/**
	* Trata datos para insertar en detalles de movimientos
	* @param array con datos detalle , id_lote
	* @return array con datos tratados
	*/
	function armarDatosDetalle($lote_id, $value){

		$detalle['demi_id'] = $value['demi_id'];
		$detalle['cantidad_recibida'] = $value['cantidad_recibida'];
		$detalle['lote_id_destino'] = $lote_id;
		return $detalle;
	}

}