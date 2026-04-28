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
      echo json_encode(['status' => true]);
   }
}
?>