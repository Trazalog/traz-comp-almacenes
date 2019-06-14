<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pedido_Material extends CI_Controller {
    function __construct(){

      parent::__construct();
      $this->load->model(CMP_ALM.'/new/Pedidos_Materiales'); 
   }
   function index(){
      echo var_dump($this->Pedidos_Materiales->obtener(1));
   }

   public function estado()
   {
       $id = $this->input->post('id');
       echo json_encode($this->Pedidos_Materiales->obtener($id));
   }

   public function pedidoNormal()
   {
       $this->load->library('BPMALM');

       $pemaId = $this->input->post('id');

       //? DEBE EXISTIR LA NOTA DE PEDIDO 
       $contract = [
           'pIdPedidoMaterial' => $pemaId,
       ];

       $data = $this->bpmalm->LanzarProceso(BPM_PROCESS_ID_PEDIDOS_NORMALES,$contract);

       $this->Notapedidos->setCaseId($pemaId, $data['case_id']);
   }

}
?>