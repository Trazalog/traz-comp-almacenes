<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* Representa Movimientos de Salida de Dapósitos
*
* @autor Hugo Gallardo
*/
class Movimientointerno extends CI_Controller {

	function __construct()
  {
		parent::__construct();
    // $this->load->model(ALM.'Movimientodeposalida');
		$this->load->model('general/Establecimientos');
		$this->load->model('traz-comp/Componentes');
		$this->load->model('Tablas');
		$this->load->model('Movimdeporecepcion');
		$this->load->model('Movimientosinternos');
	}
  

    function index(){

		//$empr_id = empresa();
		//$user_id = getUser();
		// Obtener el ID del usuario logueado
		$data = $this->session->userdata();
  		$user_id = $data['id'];        
		$empr_id = empresa();
		$search = '';;//$this->input->post('search');
		$lmit = 1;//$this->input->post('lmit');
		$offset = 10;//$this->input->post('offset');
		$data['movimientos'] = $this->Movimientosinternos->getMovimientosPaginados($empr_id, $user_id,$search, $lmit, $offset);
		$this->load->view(ALM.'/movimientosinternos/list', $data);

    }

    /**
	* Dibuja la pantalla movimiento Recepcion de deposito
	* @param 
	* @return 
	*/
    function movimientoRecepcion(){
        $data['items'] = $this->Componentes->listaArticulos();
		$estabList = $this->Establecimientos->listar();
		$data['establecimiento'] = $estabList->establecimientos->establecimiento;
		$data['depositos'] = $this->Establecimientos->obtenerDepositoPorEmp();
		$unidades = $this->Tablas->obtenerTabla('unidades_medida');
		$data['unidades'] = $unidades['data'];

		$this->load->view(ALM.'/depositos/MovimientoRecepcion', $data);
    }

    /**
	* Dibuja pantalla Salida de Deposito con stock de articulos por deposito
	* @param
	* @return view salida de depositos
	*/
	public function movimientoSalida()
	{
    	$data['items'] = $this->Componentes->listaArticulos();
		$estabList = $this->Establecimientos->listar();
		$data['establecimiento'] = $estabList->establecimientos->establecimiento;
		$data['depositos'] = $this->Establecimientos->obtenerDepositoPorEmp();
		$unidades = $this->Tablas->obtenerTabla('unidades_medida');
		$data['unidades'] = $unidades['data'];
		$this->load->view(ALM.'/depositos/MovimientoSalida', $data);
	}

	

}