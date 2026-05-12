<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Entrega_Material extends CI_Controller
{
   function __construct()
   {

      parent::__construct();
      $this->load->model(ALM . 'new/Entregasmateriales');
      $this->load->model(ALM . 'new/Pedidosmateriales');
      $this->load->model(ALM . 'Ordeninsumos');
      $this->load->model(ALM . 'Tablas');
      $this->load->model('core/Valores');
       $this->load->model(ALM.'Depositos');
      $this->load->model(ALM.'traz-comp/Componentes');


   }
   function index()
   {
      $data['list'] = $this->Entregasmateriales->listado();
      $data['entrega_directa'] = $this->Valores->getTablaValor('configuraciones','habilitar_entrega_directa');
      $this->load->view(ALM . 'new/entregas_materiales/list', $data);
   }

   public function detalle()
   {
      $id = $this->input->get('id');
      $data['detalles'] = $this->Entregasmateriales->obtenerDetalles($id);
      $data['logo'] = $this->Tablas->obtenerTablaEmpr_id('logo_impresion_entrega_materiales');
      echo json_encode($data);
   }

   public function getEntregasPedido($pema)
   {
      $data['list'] = $this->Entregasmateriales->getEntregasPedido($pema);
      $this->load->view(ALM . 'new/entregas_materiales/list', $data);
   }
   public function getEntregasPedidoOffline()
   {
      $pema = $this->input->get('pema');
      $data['list'] = $this->Entregasmateriales->getEntregasPedido($pema);
      $this->load->view(ALM . 'new/entregas_materiales/list', $data);

   }

   public function entregaMaterialesDirecto()
   {
      $data['form_id'] = $this->Valores->getTablaValor('configuraciones', 'formulario_entrega_materiales');
      #COMPONENTE ARTICULOS
      $data['items'] = $this->Componentes->listaArticulos();

      $data['establecimientos'] = $this->Depositos->obtenerEstablecimientos();
      $this->load->view(ALM . 'new/entregas_materiales/view_entrega_pedido', $data);
   }

   /* Guarda la entrega del pedido de materiales y actualiza el estado */
   public function guardarEntrega()
   {
      $form = $this->input->post();
      $res = $this->Ordeninsumos->insert_entrega_materiales($form);
      $this->Pedidosmateriales->setEstado($form['pema_id'], $form['completa'] == "true" ? 'Entregado' : 'Ent. Parcial');

      // Si es entrega directa, cerramos la tarea de entrega en BPM
      if(isset($form['directo']) && $form['directo'] == 'true'){
          $this->load->model(ALM . 'Notapedidos');
          $pema = $this->Notapedidos->get($form['pema_id']); // Obtenemos el registro para sacar el case_id
          if($pema && isset($pema['case_id']) && $pema['case_id']){
              $this->load->library('BPM');
              $this->cerrarTareaEntrega($pema['case_id']);
          }
      }

      echo json_encode(['status' => true]);
   }

   public function cerrarTareaEntrega($caseId)
   {
       // Buscamos la tarea directamente. En entrega directa, el tiempo entre la aprobación y este paso 
       // suele ser suficiente para que el motor de BPM cree la tarea.
       $taskId = $this->bpm->ObtenerTaskidXNombre(BPM_PROCESS_ID_PEDIDOS_NORMALES, $caseId, "Entrega pedido pendiente");
       
       log_message('DEBUG', "#TRAZA | Entrega_Material | cerrarTareaEntrega | caseId: $caseId | taskId: " . ($taskId ? $taskId : 'NO ENCONTRADO'));

       $user = userId();
       if ($taskId && $user) {
           $resultSetUsuario = $this->bpm->setUsuario($taskId, $user);
           $contract['entregaCompleta'] = "true";
           $contract['gEntregaCompleta'] = true;

           if ($resultSetUsuario['status']) {
               $this->bpm->cerrarTarea($taskId, $contract);
               log_message('DEBUG', "#TRAZA | Entrega_Material | cerrarTareaEntrega | Tarea $taskId cerrada");
           }
       }
   }
}
?>