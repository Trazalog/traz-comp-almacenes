<?php defined('BASEPATH') or exit('No direct script access allowed');

class Proceso extends CI_Controller
{
    public function __construct()
    {

        parent::__construct();

        $this->load->library('BPM');

        $this->load->model('almacen/Ordeninsumos');

        $this->load->model('almacen/Notapedidos');

        $this->load->model('almacen/Pedidoextra');

        // SUPERVISOR1 => 102 => Aprueba pedido de Recursos Materiales
        $data = ['userId' => 102, 'userName' => 'Fernando', 'userLastName' => 'Leiva', 'device' => '', 'permission' => 'Add-View-Del-Edit','id_empresa'=>1];
        $this->session->set_userdata('user_data', $data);
    }

    public function index()
    {

        //$this->pedidoExtraordinario();

        $data['device'] = "";
        $res = $this->bpm->getToDoList();
        $data['list'] = $res['data'];
        $this->load->view('proceso/tareas/list', $data);

    }

    public function detalleTarea($task_id)
    {

        //PERMISOS PANTALLA
        $data['permission'] = $this->session->userdata('user_data')['permission'];

        //TIPO DISPOSITIVO
        $data['device'] = "";

        //INFORMACION DE TAREA
        $tarea = $this->bpm->getTarea($task_id)['body'];

        //INFORMACION DE TAREA
        $data['idTarBonita'] = $task_id;
        $data['TareaBPM'] = $tarea;
        $data['tarea'] = $tarea;
        $data['datos'] = '';

        //LINEA DE TIEMPO
        $data['timeline'] = $this->bpm->ObtenerLineaTiempo($tarea['processId'],$tarea['rootCaseId']);

        //COMENTARIOS
        $data_aux = ['case_id' => $tarea['rootCaseId'], 'comentarios' => $this->bpm->ObtenerComentarios($tarea['rootCaseId'])];
        $data['comentarios'] = $this->load->view('proceso/tareas/componentes/comentarios', $data_aux, true);

        //DESPLEGAR VISTA
        $data['view'] = $this->deplegarVista($tarea);
        $this->load->view('proceso/tareas/view_', $data);
    }

    public function tomarTarea()
    {
        $id = $this->input->post('id');
        echo json_encode($this->bpm->setUsuario($id, $this->session->userdata('user_data')['userId']));
    }

    public function soltarTarea()
    {
        $id = $this->input->post('id');
        echo json_encode($this->bpm->setUsuario($id, ""));

    }

    public function cerrarTarea($task_id)
    {

        //Obtener Infomracion de Tarea
        $tarea = $this->bpm->getTarea($task_id)['body'];

        //Formulario desde la Vista
        $form = $this->input->post();

        //Mapeo de Contrato
        $contrato = $this->getContrato($tarea, $form);

        //Cerrar Tarea
        $this->bpm->cerrarTarea($task_id, $contrato);

    }

    public function getContrato($tarea, $form)
    {

        switch ($tarea['displayName']) {
            case 'Aprueba pedido de Recursos Materiales':

                $this->Notapedidos->setMotivoRechazo($form['pema_id'], $form['motivo_rechazo']);

                $contrato['apruebaPedido'] = $form['result'];

                return $contrato;

                break;

            case 'Entrega pedido pendiente':
           
                $this->load->model('almacen/Ordeninsumos');
       
                $this->Ordeninsumos->insert_entrega_materiales($form);

                $contrato['entregaCompleta'] = $form['completa'];

                return $contrato;

                break;

            // ?PEDIDO MATERIALES EXTRAORDINARIOS

            case 'Aprueba pedido de Recursos Materiales Extraordinarios':

                $this->Pedidoextra->setMotivoRechazo($form['peex_id'], $form['motivo_rechazo']);

                $contrato['apruebaPedido'] = $form['result'];

                return $contrato;

                break;

            case 'Comunica Rechazo':

                $contrato['motivo'] = $form['motivo'];

                return $contrato;

                break;

            case 'Solicita Compra de Recursos Materiales Extraordiinarios':

                $this->Pedidoextra->setMotivoRechazo($form['peex_id'], $form['motivo_rechazo']);

                $contrato['apruebaCompras'] = $form['result'];

                return $contrato;

                break;

            case 'Comunica Rechazo por Compras':

                $contrato['motivo'] = $form['motivo'];

                return $contrato;

                break;

            case 'Generar Pedido de Materiales':

                $this->Pedidoextra->setPemaId($form['peex_id'], $form['pema_id']); 

                $this->Notapedidos->setCaseId($form['pema_id'], $tarea['rootCaseId']);

                return;

                break;

            default:
                # code...
                break;
        }
    }

    public function deplegarVista($tarea)
    {
        switch ($tarea['displayName']) {

            case 'Aprueba pedido de Recursos Materiales':

                return $this->load->view('proceso/tareas/pedido_materiales/view_aprueba_pedido', null, true);

                break;

            case 'Entrega pedido pendiente':

                $proceso = $tarea['processId'];

                if ($proceso == BPM_PROCESS_ID_PEDIDOS_EXTRAORDINARIOS) {

                    $data['pema_id'] = $this->Pedidoextra->getXCaseId($tarea['rootCaseId'])['pema_id'];

                } else {

                    $data['pema_id'] = $this->Notapedidos->getXCaseId($tarea['rootCaseId'])['pema_id'];

                }

                $data['list_deta_pema'] = $this->Ordeninsumos->get_detalle_entrega($data['pema_id']);

                return $this->load->view('proceso/tareas/pedido_materiales/view_entrega_pedido_pendiente', $data, true);

                break;

            case 'Comunica Rechazo':

                $proceso = $tarea['processId'];

                if ($proceso == BPM_PROCESS_ID_PEDIDOS_EXTRAORDINARIOS) {

                    $data['motivo'] = $this->Pedidoextra->getXCaseId($tarea['rootCaseId'])['motivo_rechazo'];

                } else {

                    $data['motivo'] = $this->Notapedidos->getXCaseId($tarea['rootCaseId'])['motivo_rechazo'];

                }

                return $this->load->view('proceso/tareas/pedido_materiales/view_comunica_rechazo', $data, true);

                break;

            // ?PEDIDO MATERIALES EXTRAORDINARIOS

            case 'Aprueba pedido de Recursos Materiales Extraordinarios':

                $data = $this->Pedidoextra->getXCaseId($tarea['rootCaseId']);

                return $this->load->view('proceso/tareas/pedido_extraordinario/view_aprueba_pedido', $data, true);

                break;

            case 'Solicita Compra de Recursos Materiales Extraordiinarios':

                $data = $this->Pedidoextra->getXCaseId($tarea['rootCaseId']);

                return $this->load->view('proceso/tareas/pedido_extraordinario/view_aprueba_compras', $data, true);

                break;

            case 'Comunica Rechazo por Compras':

                $data['motivo'] = $this->Pedidoextra->getXCaseId($tarea['rootCaseId'])['motivo_rechazo'];

                return $this->load->view('proceso/tareas/pedido_materiales/view_comunica_rechazo', $data, true);

                break;

            case 'Generar Pedido de Materiales':

                $peex  = $this->Pedidoextra->getXCaseId($tarea['rootCaseId']);

                $data['pema_id'] = $peex['pema_id'];

                $data['peex_id'] = $peex['peex_id'];

                return $this->load->view('proceso/tareas/pedido_extraordinario/view_generar_pedido_materiales', $data, true);

                break;

            default:
                # code...
                break;
        }
    }

    public function pedidoNormal()
    {
        $pema_id = 1 ;

        $contract = [

            'pIdPedidoMaterial' => $pema_id,
        ];

        $data = $this->bpm->LanzarProceso(BPM_PROCESS_ID_PEDIDOS_NORMALES,$contract);

        $this->Notapedidos->setCaseId($pema_id, $data['case_id']);

        $this->index();
    }

    public function pedidoExtraordinario()
    {
        $ot = 36; //!HARDCODE

        $pedidoExtra = 'Soy un Pedido Extraordinario';  //!HARDCODE    

        $contract = [
            'pedidoExtraordinario' =>  $pedidoExtra
        ];

        $data = $this->bpm->LanzarProceso(BPM_PROCESS_ID_PEDIDOS_EXTRAORDINARIOS,$contract);

        $peex['case_id'] = $data['case_id'];
        $peex['fecha'] = date("Y-m-d");
        $peex['detalle'] = $pedidoExtra;    
        $peex['ortr_id'] = $ot; 
        $peex['empr_id'] = 1; //!HARDCODE

        $this->Pedidoextra->set($peex);

        $this->index();
    }

    public function guardarComentario()
    {
        echo $this->bpm->guardarComentario($this->input->post());
    }
}
