<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Entrega_Material extends CI_Controller {
    function __construct(){

      parent::__construct();
      $this->load->model(ALM.'new/Entregasmateriales'); 
   }
   function index(){
      $data['list'] = $this->Entregasmateriales->listado();
      $this->load->view(ALM.'new/entregas_materiales/list',$data);
   }

   public function detalle()
   {
      $id = $this->input->get('id');
      echo json_encode($this->Entregasmateriales->obtenerDetalles($id));
   }

   public function getEntregasPedido($pema)
   {
      $data['list'] = $this->Entregasmateriales->getEntregasPedido($pema);
      $this->load->view(ALM.'new/entregas_materiales/list', $data);
   }
   public function getEntregasPedidoOffline()
   {
      $pema = $this->input->get('pema');
      $data['list'] = $this->Entregasmateriales->getEntregasPedido($pema);
      $this->load->view(ALM.'new/entregas_materiales/list', $data);
      
   }
}
?>