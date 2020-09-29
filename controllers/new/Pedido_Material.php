<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pedido_Material extends CI_Controller {
    function __construct(){

      parent::__construct();
      $this->load->model(ALM.'new/Pedidosmateriales'); 
   }
   function index(){
      echo var_dump($this->Pedidosmateriales->obtener(1));
   }

   public function estado()
   {
       $id = $this->input->get('id');
       echo json_encode($this->Pedidosmateriales->obtener($id));
   }

   public function pedidoNormal()
   {
       $rsp =  $this->Pedidosmateriales->pedidoNormal($this->input->post('id'));
        echo json_encode($rsp);  
   }

   public function getPedidos($ot = null)
   {
     $data['list'] = $this->Pedidosmateriales->getListado($ot);
     $data['permission'] = 'View';
     $this->load->view(ALM.'notapedido/list', $data);
   }
}
?>