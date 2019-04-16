<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Articulos extends CI_Controller {

	function __construct() 
    {
		parent::__construct();
		$this->load->model('Articulos');
	}

	// Muestra listado de articulos
	public function index()
	{
		$data['list'] = $this->Articulos->Articles_List();
		$this->load->view('articles/list', $data);
	}
	
	public function getArticulos()//getdatosart() // Ok
	{
		$art = $this->Articulos->getdatosarts();
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
		$data['data']     = $this->Articulos->getArticle($this->input->post());
		$response['html'] = $this->load->view('articles/view_', $data, true);

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




	
	public function getdatosfam(){
		
		$art = $this->Articulos->getdatosfams();
		//echo json_encode($Customers);

		if($art)
		{	
			$arre=array();
	        foreach ($art as $row ) 
	        {   
	           $arre[]=$row;
	        }
			echo json_encode($arre);
		}
		else echo "nada";
	}

	public function baja_articulo()
	{
		$idarticulo = $_POST['idelim'];
		$datos      = array('artEstado'=>"AN");
		$result     = $this->Articulos->update_articulo($datos, $idarticulo);
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

	public function getestado()
	{
		$response = $this->Articulos->getestados();
      	echo json_encode($response);
	}

}