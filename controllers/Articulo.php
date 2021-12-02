<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Articulo extends CI_Controller {

	function __construct()
    {
		parent::__construct();

		$this->load->model(ALM.'Articulos');
		$this->load->model(ALM.'Lotes');
		$this->load->model('Tablas');

	}

	// Muestra listado de articulos
	public function index()
	{
		$data['list'] = $this->Articulos->getList();
		$data['unidades_medida'] = $this->Tablas->obtenerTabla('unidades_medida')['data'];
		$data['tipoArticulos'] = $this->Tablas->obtenerTabla('tipo_articulo')['data'];
		$this->load->view(ALM.'articulo/list', $data);
	}

	public function obtener($opt = false){
		$url =  REST_ALM . '/articulos/'.empresa();
		$data = wso2($url);
		if($data['status']){
			if($opt) $data['data'] = selectBusquedaAvanzada(false, false, $data['data'], 'arti_id', 'barcode',array('descripcion'));
			echo json_encode($data);
		}
	}

	public function guardar()
	{
		$data = $this->input->post();
		unset($data['arti_id']);
		$data = $this->Articulos->guardar($data);
		echo json_encode($data);
	}

	public function editar()
	{
		$data = $this->input->post();
		$rsp = $this->Articulos->editar($data);
		echo json_encode($rsp);
	}

	public function getdatosart() // Ok
	{
		$art = $this->Articulos->getUnidadesMedidas();
		if($art)
		{	
			$arre = array();
	        foreach ($art as $row ) 
	        {   
	           $arre[] = $row;
	        }
			echo json_encode($arre);
		}
		else echo "nada";
	}

	//
	public function getArticle() // Ok
	{
		$data['data']   = $this->Articulos->getArticle($this->input->post());
		$response['html'] = $this->load->view(ALM.'articulo/view_', $data, true);

		echo json_encode($response);
	}

	//
	public function getpencil() // Ok
	{
		$id     = $this->input->post('idartic');
		$result = $this->Articulos->getpencil($id);
		echo json_encode($result);
	}

	//
	public function editar_art()  // Ok
	{
		$datos  = $this->input->post('data');
		$id     = $this->input->post('ida');
		$result = $this->Articulos->update_editar($datos,$id);
		print_r(json_encode($result));	
	}


	public function setArticle(){
		$data = $this->input->post();
		$id = $this->Articulos->setArticle($data);


		if($id  == false)
		{
			echo json_encode(false);
		}
		else
		{
			
			echo json_encode(true);	
		}
	}


	public function baja_articulo()
	{
		$idarticulo = $_POST['idelim'];
		$result     = $this->Articulos->eliminar($idarticulo);
		print_r($result);
	}

	public function searchByCode() {
		$data = $this->Articulos->searchByCode($this->input->post());
		if($data  == false)
		{
			echo json_encode(false);
		}
		else
		{
			echo json_encode($data);	
		}
	} 

	public function searchByAll() {
		$data = $this->Articulos->searchByAll($this->input->post());
		if($data  == false)
		{
			echo json_encode(false);
		}
		else
		{
			echo json_encode($data);	
		}
	}

	public function getestado(){

		$response = $this->Articulos->getestados();

		echo json_encode($response);
		  
	}

	public function getLotes($id = null) //fleiva
	{
		if(!$id){ $this->load->view('no_encontrado');return;}

		$data['articulo'] = $this->Articulos->get($id);

		$data['list'] = $this->Articulos->getLotes($id);
			
		$this->load->view(ALM.'proceso/tareas/componentes/tabla_lote_deposito', $data);
	}

}