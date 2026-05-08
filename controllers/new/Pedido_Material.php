<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pedido_Material extends CI_Controller {
  function __construct(){

    parent::__construct();
    $this->load->model(ALM.'new/Pedidosmateriales'); 
  }

  function index(){
    echo var_dump($this->Pedidosmateriales->obtener(1));
  }
  /**
	* Obtiene el detalle de un pedido de materiales por ID 
	* @param integer id del pedido de materiales
	* @return array listado de choferes coincidentes
	*/
  public function estado(){
    log_message('DEBUG', '#TRAZA | #TRAZ-COMP-ALMACENES | Pedido_Material | estado()');
    $id = $this->input->get('id');
    echo json_encode($this->Pedidosmateriales->obtener($id));
  }

  public function pedidoNormal(){
    log_message('DEBUG', '#TRAZA | #TRAZ-COMP-ALMACENES | Pedido_Material | pedidoNormal()');
    $pema_id = $this->input->post('id');

    // Evitar lanzamientos duplicados
    $this->load->model(ALM . 'Notapedidos');
    $pema = $this->Notapedidos->get($pema_id);
    if($pema && isset($pema['case_id']) && $pema['case_id']){
        log_message('DEBUG', '#TRAZA | Pedido_Material | Proceso ya lanzado para pema_id: '.$pema_id);
        echo json_encode(['status' => true, 'msj' => 'OK', 'data' => ['caseId' => $pema['case_id']]]);
        return;
    }

    $rsp =  $this->Pedidosmateriales->pedidoNormal($pema_id);

    // AVANZA PROCESO A TAREA SIGUIENTE (Aprobación Automática) si viene desde entrega directa de materiales
    if ($rsp['status'] && ($this->input->post('directo') == 'true')) {
        $this->load->library('BPM');
        $this->aceptarPedidoMateriales($rsp['data']['caseId']);
    }

    echo json_encode($rsp);  
  }

  /**
   * Aprueba el pedido de materiales en el proceso y avanza a entrega de materiales
   * @param integer $case_id ID del caso en el proceso
   */
  public function aceptarPedidoMateriales($case_id)
  {
      $taskId = $this->bpm->ObtenerTaskidXNombre(BPM_PROCESS_ID_PEDIDOS_NORMALES, $case_id, 'Aprueba pedido de Recursos Materiales');
      $user = userId();
      if ($taskId && $user) {
          $resultSetUsuario = $this->bpm->setUsuario($taskId, $user);
          $contract["apruebaPedido"] = "true";
          $contract["gApruebaPedido"] = true;

          if ($resultSetUsuario['status']) {
              $this->bpm->cerrarTarea($taskId, $contract);
          }
      }
  }

  public function getPedidos($ot = null){
  $data['list'] = $this->Pedidosmateriales->getListado($ot);
  $data['permission'] = 'View';
  $this->load->view(ALM.'notapedido/list', $data);
  }
}
?>