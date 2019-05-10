<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Articulo extends CI_Controller {

	function __construct() 
    {
		parent::__construct();

		$this->load->model('almacen/Articulos');

	}

	// Muestra listado de articulos
	public function index()
	{
		$data['list'] = $this->Articulos->list();
		$data['permission'] = 'Add-Edit-Del-View';
		$this->load->view('almacen/articulo/list', $data);
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
		$response['html'] = $this->load->view('almacen/articulo/view_', $data, true);

		echo json_encode($response);
	}

	//
	public function getpencil() // Ok
	{
		$id     = $this->input->post('idartic');
		$result = $this->Articulos->getpencil($id);
		print_r(json_encode($result));
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
		$data = $this->Articulos->setArticle($this->input->post());
		if($data  == false)
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
			
		$this->load->view('proceso/tareas/componentes/tabla_lote_deposito', $data);
	}

	public function nuevaEntregaMaterial(){

		$this->load->model('almacen/Ordeninsumos');

		$info = json_decode($this->input->post('info_entrega'),true);

		$detalle = json_decode($this->input->post('detalle'),true);

		$info['enma_id'] = $this->input->post('enma_id');

		echo json_encode(['id' => $this->Ordeninsumos->insert_entrega_materiales($info,$detalle)]);

	}

}