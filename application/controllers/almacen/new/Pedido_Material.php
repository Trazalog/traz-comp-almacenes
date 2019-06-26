<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pedido_Material extends CI_Controller {
    function __construct(){

      parent::__construct();
      $this->load->model(CMP_ALM.'/new/Pedidos_Materiales'); 
   }
   function index(){
     
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
       $this->Pedidos_Materiales->pedidoNormal($this->input->post('id'));
   }
}
?>