<?php defined('BASEPATH') or exit('No direct script access allowed');

class Notapedido extends CI_Controller
{

    private $permission = "Add-Edit-Del-View";

    public function __construct()
    {

        parent::__construct();
        $this->load->model(CMP_ALM.'/Notapedidos');
    }

    public function index()
    {
        $data['list'] = $this->Notapedidos->notaPedidos_List();
        $data['permission'] = $this->permission;
        $this->load->view(CMP_ALM.'/notapedido/list', $data);
    }

    public function ObtenerNotasPedidosxOT($idot)
    {
        $data['permission'] = "Add-Edit-Del-View";
        $data['list'] = $this->Notapedidos->getNotasxOT($idot);
        $this->load->view(CMP_ALM.'/notapedido/list', $data);
    }

    public function getNotasxOT($idot)
    {
        $data['permission'] = $this->permission;
        $data['list'] = $this->Notapedidos->getNotasxOT($idot);
        $this->load->view(CMP_ALM.'/notapedido/listOt', $data);
    }

    public function agregarNota($idot)
    {
        $data['permission'] = $this->permission;
        $data['ot'] = $this->Notapedidos->getOTporId($idot);
        $this->load->view(CMP_ALM.'/notapedido/view_', $data);
    }

    // devuelve plantilla de insumos a pedir por cliente
    public function agregarListInsumos($ot)
    {
        $this->load->model(CMP_ALM.'/Articulos');
        $data['ot'] = $ot;
        $data['permission'] = $this->permission;
        $data['plantilla'] = $this->Articulos->getList();
        $this->load->view(CMP_ALM.'/notapedido/insumolist', $data);
    }

    // agregar pedido especial carga vista
    public function pedidoEspecial()
    {

        $this->load->view(CMP_ALM.'/notapedido/viewPedidoEspecial_');
    }

    // guardar pedido especial
    public function setPedidoEspecial()
    {

        $pedido = $this->input->post('pedido').'&'.$this->input->post('justif');
        $ot = $this->input->post('ot');

        echo $this->pedidoExtraordinario($ot,$pedido);

    }

    public function pedidoExtraordinario($ot, $pedidoExtra)
    {
        //? SE DEBE CORRESPONDER CON UN ID EN LA TABLE ORDEN_TRABAJO SINO NO ANDA
        $this->load->library('BPMALM');
        $this->load->model(CMP_ALM.'/Pedidoextra');

        $contract = [
            'pedidoExtraordinario' =>  $pedidoExtra
        ];

        $data = $this->bpmalm->LanzarProceso(BPM_PROCESS_ID_PEDIDOS_EXTRAORDINARIOS,$contract);

        $peex['case_id'] = $data['case_id'];
        $peex['fecha'] = date("Y-m-d");
        $peex['detalle'] = $pedidoExtra;    
        $peex['ortr_id'] = $ot; 
        $peex['empr_id'] = 1; //!HARDCODE

        return $this->Pedidoextra->set($peex);

    }
  

    public function getOrdenesCursos()
    {
        $response = $this->Notapedidos->getOrdenesCursos();
        echo json_encode($response);
    }

    public function getDetalle()
    {
        $response = $this->Notapedidos->getDetalles($this->input->post());
        echo json_encode($response);
    }

    public function getArticulo()
    {
        $response = $this->Notapedidos->getArticulos($this->input->post());
        echo json_encode($response);
    }

    public function getProveedor()
    {
        $response = $this->Notapedidos->getProveedores();
        echo json_encode($response);
    }

    public function getNotaPedidoId()
    {
        $response = $this->Notapedidos->getNotaPedidoIds($this->input->post('id'));
        echo json_encode($response);
    }

    public function setNotaPedido()
    {
        $ids = $this->input->post('idinsumos');
        $cantidades = $this->input->post('cantidades');
        $idOT = $this->input->post('idOT');
        $justificacion = $this->input->post('justificacion');

        $cabecera = array(
            'fecha' => date('Y-m-d'),
            'ortr_id' => $idOT,
            'empr_id' => empresa(),
            'justificacion' => $justificacion
        );

        $idnota = $this->Notapedidos->setCabeceraNota($cabecera);

        // SET EN PEDIDO EXTRA EL PEDIDO MATERILES
        $peex_id = $this->input->post('peex_id');
    
        if($peex_id){$this->load->model(CMP_ALM.'/Pedidoextra'); $this->Pedidoextra->setPemaId($peex_id, $idnota);}

        for ($i = 0; $i < count($ids); $i++) {
            $deta[$i]['pema_id'] = $idnota;
            $deta[$i]['arti_id'] = $ids[$i];
            $deta[$i]['cantidad'] = $cantidades[$i];
            $deta[$i]['fecha_entrega'] = date('Y-m-d');
        }

        $response = $this->Notapedidos->setDetaNota($deta);
        
        echo json_encode(['pema_id'=>$idnota]);
    }

    
    public function pedidoNormal($pemaId)
    {
        $this->load->library('BPMALM');

        //? DEBE EXISTIR LA NOTA DE PEDIDO 
        $contract = [
            'pIdPedidoMaterial' => $pemaId,
        ];

        $data = $this->bpmalm->LanzarProceso(BPM_PROCESS_ID_PEDIDOS_NORMALES,$contract);

        $this->Notapedidos->setCaseId($pemaId, $data['case_id']);
    }

    public function editarPedido()
    {

        $this->load->model(CMP_ALM.'/Articulos');
        $data['permission'] = $this->permission;
        $data['plantilla'] = $this->Articulos->getList();
        $this->load->view(CMP_ALM.'/notapedido/edit_pedido', $data);

    }

    public function editPedido()
    {
        $ids = $this->input->post('idinsumos');
        $cantidades = $this->input->post('cantidades');
        $idOT = $this->input->post('idOT');

        $idnota = $this->input->post('pema');

        for ($i = 0; $i < count($ids); $i++) {
            $deta[$i]['pema_id'] = $idnota;
            $deta[$i]['arti_id'] = $ids[$i];
            $deta[$i]['cantidad'] = $cantidades[$i];
            $deta[$i]['fecha_entrega'] = date('Y-m-d');
        }

        $response = $this->Notapedidos->setDetaNota($deta);
        echo json_encode($response);
    }

    public function getTablaDetalle($pema=null)
    {
        $this->load->model(CMP_ALM.'/Ordeninsumos');

        $data['list_deta_pema'] = $this->Ordeninsumos->get_detalle_entrega($pema);

        $aux = $this->load->view(CMP_ALM.'/proceso/tareas/componentes/tabla_detalle_entregas', $data, true);

        echo $aux;
    }

    public function editarDetalle()
    {
        $id = $this->input->post('id');
        $data['cantidad'] = $this->input->post('cantidad');
        echo $this->Notapedidos->editarDetalle($id, $data);
    }

    public function eliminarDetalle()
    {
        $id = $this->input->post('id');
        echo $this->Notapedidos->eliminarDetalle($id);
    }

    public function crearPedido()
    {   
        $this->load->model('traz-comp/Componentes');
        $data = $this->Componentes-> listaArticulos();
        $this->load->view(CMP_ALM.'/notapedido/generar_pedido', $data);
       
    }
}
