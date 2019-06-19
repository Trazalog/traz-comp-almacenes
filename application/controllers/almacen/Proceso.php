<?php defined('BASEPATH') or exit('No direct script access allowed');

class Proceso extends CI_Controller
{
    public function __construct()
    {

        parent::__construct();

        $this->load->library('BPMALM');
        $this->load->model(CMP_ALM.'/Ordeninsumos');
        $this->load->model(CMP_ALM.'/Notapedidos');
        $this->load->model(CMP_ALM.'/new/Pedidos_Materiales');
        $this->load->model(CMP_ALM.'/Pedidoextra');
    }

    public function index()
    {

        //$this->pedidoExtraordinario();
        $data['device'] = "";
        $res = $this->bpmalm->getToDoList();
        $data['list'] = $res['data'];
        $this->load->view(CMP_ALM.'/proceso/tareas/list', $data);

    }

    public function detalleTarea($task_id)
    {

        //PERMISOS PANTALLA
        $data['permission'] = $this->session->userdata('user_data')[0]['permission'];

        //TIPO DISPOSITIVO
        $data['device'] = "";

        //INFORMACION DE TAREA
        $tarea = $this->bpmalm->getTarea($task_id)['body'];

        //INFORMACION DE TAREA
        $data['idTarBonita'] = $task_id;
        
        $data['TareaBPM'] = $tarea;//!QUITAR
        $data['tarea'] = $tarea;//!QUITAR
        $data['datos'] = '';//!QUITAR

        if ($tarea['processId'] == BPM_PROCESS_ID_PEDIDOS_EXTRAORDINARIOS) {

            $data['ot'] = $this->Pedidoextra->getXCaseId($tarea['rootCaseId'])['ortr_id'];

        } else {

            $data['ot'] = $this->Notapedidos->getXCaseId($tarea['rootCaseId'])['ortr_id'];

        }

        //LINEA DE TIEMPO
        $data['timeline'] = $this->bpmalm->ObtenerLineaTiempo($tarea['processId'],$tarea['rootCaseId']);

        //COMENTARIOS
        $data_aux = ['case_id' => $tarea['rootCaseId'], 'comentarios' => $this->bpmalm->ObtenerComentarios($tarea['rootCaseId'])];
        $data['comentarios'] = $this->load->view(CMP_ALM.'/proceso/tareas/componentes/comentarios', $data_aux, true);

        //DESPLEGAR VISTA
        $data['view'] = $this->deplegarVista($tarea);
        $this->load->view(CMP_ALM.'/proceso/tareas/view_', $data);
    }

    public function tomarTarea($id)
    {
        echo json_encode($this->bpmalm->setUsuario($id));
    }

    public function soltarTarea($id)
    {
        echo json_encode($this->bpmalm->setUsuario($id));   
    }

    public function cerrarTarea($task_id)
    {

        //Obtener Infomracion de Tarea
        $tarea = $this->bpmalm->getTarea($task_id)['body'];

        //Formulario desde la Vista
        $form = $this->input->post();

        //Mapeo de Contrato
        $contrato = $this->getContrato($tarea, $form);

    
        //Cerrar Tarea
       $this->bpmalm->cerrarTarea($task_id, $contrato);

    }

    public function getContrato($tarea, $form)
    {

        switch ($tarea['displayName']) {
            case 'Aprueba pedido de Recursos Materiales':

                $this->Notapedidos->setMotivoRechazo($form['pema_id'], $form['motivo_rechazo']);

                $this->Pedidos_Materiales->setEstado($form['pema_id'],$form['result']=="true"?'Aprobado':'Rechazado');

                $contrato['apruebaPedido'] = $form['result'];

                return $contrato;

                break;

            case 'Entrega pedido pendiente':
           
                $this->load->model(CMP_ALM.'/Ordeninsumos');
       
                $this->Ordeninsumos->insert_entrega_materiales($form);

                $this->Pedidos_Materiales->setEstado($form['pema_id'],$form['completa']=="true"?'Entregado':'Ent. Parcial');

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

                # ACCIONES ANTES DE CERRAR TAREA
                if($form['result'] == "true") {
                    $this->load->model('Pedidos_Materiales');
                    $this->Pedidos_Materiales->pedidoNormal($form['pema_id']);
                }
                # CREAR CONTRATO
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

                $data['pema_id'] = $this->Notapedidos->getXCaseId($tarea['rootCaseId'])['pema_id'];

                return $this->load->view(CMP_ALM.'/proceso/tareas/pedido_materiales/view_aprueba_pedido', $data, true);

                break;

            case 'Entrega pedido pendiente':

                $proceso = $tarea['processId'];

                if ($proceso == BPM_PROCESS_ID_PEDIDOS_EXTRAORDINARIOS) {

                    $data['pema_id'] = $this->Pedidoextra->getXCaseId($tarea['rootCaseId'])['pema_id'];

                } else {

                    $data['pema_id'] = $this->Notapedidos->getXCaseId($tarea['rootCaseId'])['pema_id'];

                }

                $data['list_deta_pema'] = $this->Ordeninsumos->get_detalle_entrega($data['pema_id']);

                return $this->load->view(CMP_ALM.'/proceso/tareas/pedido_materiales/view_entrega_pedido_pendiente', $data, true);

                break;

            case 'Comunica Rechazo':

                $proceso = $tarea['processId'];
                $res = null;
                $obj = new stdClass();
                
                if ($proceso == BPM_PROCESS_ID_PEDIDOS_EXTRAORDINARIOS) {

                    $res = $this->Pedidoextra->getXCaseId($tarea['rootCaseId']);

                } else {

                    $res = $this->Notapedidos->getXCaseId($tarea['rootCaseId']);

                }

                $obj->pema_id = $res['pema_id'];
                $obj->motivo = $res['motivo_rechazo'];

                $this->load->model('traz-comp/Componentes');
                $data = $this->Componentes-> listaArticulos();

                $data['info'] = $obj;


                return $this->load->view(CMP_ALM.'/proceso/tareas/pedido_materiales/view_comunica_rechazo', $data, true);

                break;

            // ?PEDIDO MATERIALES EXTRAORDINARIOS

            case 'Aprueba pedido de Recursos Materiales Extraordinarios':

                $data = $this->Pedidoextra->getXCaseId($tarea['rootCaseId']);

                return $this->load->view(CMP_ALM.'/proceso/tareas/pedido_extraordinario/view_aprueba_pedido', $data, true);

                break;

            case 'Solicita Compra de Recursos Materiales Extraordiinarios':

                $data = $this->Pedidoextra->getXCaseId($tarea['rootCaseId']);

                return $this->load->view(CMP_ALM.'/proceso/tareas/pedido_extraordinario/view_aprueba_compras', $data, true);

                break;

            case 'Comunica Rechazo por Compras':

                $data['motivo'] = $this->Pedidoextra->getXCaseId($tarea['rootCaseId'])['motivo_rechazo'];

                return $this->load->view(CMP_ALM.'/proceso/tareas/pedido_materiales/view_comunica_rechazo', $data, true);

                break;

            case 'Generar Pedido de Materiales':

                $peex  = $this->Pedidoextra->getXCaseId($tarea['rootCaseId']);

                $this->load->model('traz-comp/Componentes');

		        $data = $this->Componentes-> listaArticulos();

                $data['pema_id'] = $peex['pema_id'];

                $data['peex_id'] = $peex['peex_id'];

                return $this->load->view(CMP_ALM.'/proceso/tareas/pedido_extraordinario/view_generar_pedido_materiales', $data, true);

                break;

            default:
                # code...
                break;
        }
    }

    public function pedidoNormal($pemaId=1)
    {
        //? DEBE EXISTIR LA NOTA DE PEDIDO 
        $contract = [
            'pIdPedidoMaterial' => $pemaId,
        ];

        $data = $this->bpmalm->LanzarProceso(BPM_PROCESS_ID_PEDIDOS_NORMALES,$contract);

        $this->Notapedidos->setCaseId($pemaId, $data['case_id']);

        $this->index();
    }

    public function pedidoExtraordinario($ot=1)
    {
        $pedidoExtra = 'Soy un Pedido';
        //? SE DEBE CORRESPONDER CON UN ID EN LA TABLE ORDEN_TRABAJO SINO NO ANDA

        $contract = [
            'pedidoExtraordinario' =>   $pedidoExtra
        ];

        $data = $this->bpmalm->LanzarProceso(BPM_PROCESS_ID_PEDIDOS_EXTRAORDINARIOS,$contract);

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
        echo $this->bpmalm->guardarComentario($this->input->post());
    }
}