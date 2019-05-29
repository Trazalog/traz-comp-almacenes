<?php defined('BASEPATH') or exit('No direct script access allowed');

class Notapedido extends CI_Controller
{

    private $permission = "Add-Edit-Del-View";

    public function __construct()
    {

        parent::__construct();
        $this->load->model(CMP_ALM.'/Notapedidos');
        //$this->load->model('Bonitas');
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
        $data['plantilla'] = $this->Articulos->list();
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

        $pedido = $this->input->post('pedido');
        $justif = $this->input->post('justif');

        // Lanza proceso (retorna case_id)
        $result = $this->lanzarProcesoBPM($inspectorid);
        $caseId = json_decode($result, true)['caseId'];

    }
    // lanza proceso en BPM (inspección)
    public function lanzarProcesoBPM($inspectorid)
    {

        $parametros = $this->Bonitas->conexiones();
        $parametros["http"]["method"] = "POST";
        $idInspector = array(
            "idInspector" => $inspectorid,
        );
        $parametros["http"]["content"] = json_encode($idInspector);
        $param = stream_context_create($parametros);
        $result = $this->Inspecciones->lanzarProcesoBPM($param);
        return $result;
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

        //$userdata = $this->session->userdata('user_data');
        $empId = 1; //$userdata['id_empresa'];

        $ids = $this->input->post('idinsumos');
        $cantidades = $this->input->post('cantidades');
        $idOT = $this->input->post('idOT');

        $cabecera['fecha'] = date('Y-m-d');
        $cabecera['ortr_id'] = $idOT;
        $cabecera['empr_id'] = $empId;
        $idnota = $this->Notapedidos->setCabeceraNota($cabecera);

        // SET EN PEDIDO EXTRA EL PEDIDO MATERILES
        $peex_id = $this->input->post('peex_id');
    
        if($peex_id){$this->load->model(CMP_ALM.'/Pedidoextra'); $this->Pedidoextra->setPemaId($peex_id, $idnota);}

        for ($i = 0; $i < count($ids); $i++) {
            $deta[$i]['pema_id'] = $idnota;
            $deta[$i]['arti_id'] = $ids[$i];
            $deta[$i]['cantidad'] = $cantidades[$i];
            $deta[$i]['fecha_entrega'] = date('Y-m-d');
            //$deta[$i]['estado'] = 'P';
        }

        $response = $this->Notapedidos->setDetaNota($deta);
        
        echo json_encode(['pema_id'=>$idnota]);
    }

    public function editarPedido()
    {

        $this->load->model(CMP_ALM.'/Articulos');
        $data['permission'] = $this->permission;
        $data['plantilla'] = $this->Articulos->list();
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
}
