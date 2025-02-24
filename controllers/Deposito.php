<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Deposito extends CI_Controller {

	private $permission = 'Add-Edit-Del-View';

    public function __construct()
    {
		parent::__construct();
		
		$this->load->model(ALM.'Depositos');
    }

    //funcion que devuelve depositos dado un id de establecimiento
    public function getdepositosxestaid()
    {
        $id = $this->input->post('id_esta');
		$resp = $this->Depositos->Get_depo_x_estaid($id);
		echo json_encode($resp);
    }

    public function obtenerEstablecimientos()
    {
		$resp = $this->Depositos->obtenerEstablecimientos();
		echo json_encode($resp);
    }

    public function getDepositoxEncargado(){
      $data = $this->session->userdata();
      $id_esta = $this->input->post('id_esta');
  		$user_id = $data['id'];        
        // Verificar si el usuario tiene depósitos asociados
        $depositos = $this->Depositos->obtenerDepositoxEncargado( $user_id, $id_esta);        
        // Devolver el resultado en formato JSON
        echo json_encode($depositos);
    }
}