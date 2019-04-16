<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lote extends CI_Controller {

	private $permission = "Add-Edit-Del-View";

	private $path = 'almacen/';

	function __construct()
  {
		parent::__construct();
		$this->load->model($this->path.'Lotes');
	}

	public function index()
	{
		$data['list']       = $this->Lotes->Lotes_List();
		$data['permission'] = $this->$permission;
		$this->load->view($this->path.'lotes/list', $data);
	}
	
	public function puntoPedList($permission)
	{
		$data['list']       = $this->Lotes->puntoPedListado();
		$data['permission'] = $this->$permission;
		$this->load->view($this->path.'lotes/list_punto_ped', $data);
	}

	public function getMotion(){
		$data['data'] = $this->Stocks->getMotion($this->input->post());
		$response['html'] = $this->load->view($this->path.'stock/view_', $data, true);

		echo json_encode($response);
	}
	
	public function setMotion(){
		$data = $this->Stocks->setMotion($this->input->post());
		if($data  == false)
		{
			echo json_encode(false);
		}
		else
		{
			echo json_encode(true);	
		}
	}
}