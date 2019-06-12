<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Entrega_Material extends CI_Controller {
    function __construct(){

      parent::__construct();
      $this->load->model(CMP_ALM.'/new/Entregas_Materiales'); 
   }
   function index(){
      $data['list'] = $this->Entregas_Materiales->listado();

      #echo var_dump($data['list']);die;
      $this->load->view(CMP_ALM.'/new/entregas_materiales/list',$data);
   }
}
?>