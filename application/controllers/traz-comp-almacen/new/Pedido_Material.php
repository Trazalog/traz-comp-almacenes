<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pedido_Material extends CI_Controller {
    function __construct(){

      parent::__construct();
      $this->load->model(CMP_ALM.'new/Pedidos_Materiales'); 
   }
   function index(){
      echo var_dump($this->Pedidos_Materiales->obtener(1));
   }

   public function obtener($pema = null){

    $pema = $pema?$pema:$this->input->post('id');

    if(!$pema) { echo 'ID Pedido Material Nulo'; return; }
    
    $res = $this->Pedidos_Materiales->obtener($pema,true);
    
    echo json_encode($res);
   }

   public function estado()
   {
       $id = $this->input->post('id');
       echo json_encode($this->Pedidos_Materiales->obtener($id));
   }

   public function pedidoNormal()
   {
      $rsp =  $this->Pedidos_Materiales->pedidoNormal($this->input->post('id'));

      echo json_encode($rsp);  
   }

   public function getPedidos($ot = null)
   {
     $data['list'] = $this->Pedidos_Materiales->getListado($ot);
     $data['permission'] = 'View';
     $this->load->view(CMP_ALM.'notapedido/list', $data);
   }
}
?>