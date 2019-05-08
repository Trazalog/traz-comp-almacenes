<?php defined('BASEPATH') or exit('No direct script access allowed');

class Proceso extends CI_Controller
{
    public function __construct()
    {

        parent::__construct();

        $this->load->library('BPM');

        $this->load->model('almacen/Ordeninsumos');

        $this->load->model('almacen/Notapedidos');

        // SUPERVISOR1 => 102 => Aprueba pedido de Recursos Materiales
        $data = ['userId' => 102, 'userName' => 'Fernando', 'userLastName' => 'Leiva', 'device' => '', 'permission' => 'Add-View-Del-Edit'];
        $this->session->set_userdata('user', $data);
    }

    public function index()
    {

        // $pema_id = 1 ;

        // $contract = [
        //     'pIdPedidoMaterial' => $pema_id,
        // ];

        // $data = $this->bpm->LanzarProceso($contract);

        // $this->Notapedidos->setCaseId($pema_id, $data['case_id']);

        ////////////////////////////////////////////////

        //   $data = $this->bpm->setUsuario(620002,102);

        //   echo var_dump($data);

        //   $contract = [
        //      'apruebaPedido' => true
        //   ];

        //   $data = $this->bpm->cerrarTarea(620002,$contract);

        //   echo var_dump($data);

        // $data = $this->bpm->setUsuario(620004,102);

        // echo var_dump($data);

        //     $contract = [
        //      'entregaCompleta' => true
        //       ];

        //   $data = $this->bpm->cerrarTarea(620004,$contract);

        //   echo var_dump($data);

        $data['device'] = "";
        $res = $this->bpm->getToDoList();
        $data['list'] = $res['data'];
        $this->load->view('proceso/tareas/list', $data);

    }

    public function detalleTarea($task_id)
    {

        //PERMISOS PANTALLA
        $data['permission'] = $this->session->userdata('user')['permission'];

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
        $data['timeline'] = $this->bpm->ObtenerLineaTiempo($tarea['rootCaseId']);

        //COMENTARIOS
        $data_aux = ['case_id' => $tarea['rootCaseId'], 'comentarios' => $this->bpm->ObtenerComentarios()];
        $data['comentarios'] = $this->load->view('proceso/tareas/componentes/comentarios', $data_aux, true);

        //DESPLEGAR VISTA
        $data['view'] = $this->deplegarVista($tarea);
        $this->load->view('proceso/tareas/view_', $data);
    }

    public function tomarTarea()
    {
        $id = $this->input->post('id');
        echo json_encode($this->bpm->setUsuario($id, $this->session->userdata('user')['userId']));
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
        $contrato = $this->getContrato($tarea['displayName'], $form);

        //Cerrar Tarea
        $this->bpm->cerrarTarea($task_id, $contrato);

    }

    public function getContrato($nombre, $form)
    {

        switch ($nombre) {
            case 'Aprueba pedido de Recursos Materiales':

                $this->load->model('almacen/Notapedidos');
                $this->Notapedidos->setMotivoRechazo($form['pema_id'], $form['motivo_rechazo']);
                $contrato['apruebaPedido'] = $form['result'];

                return $contrato;

                break;

            case 'Entrega pedido pendiente':

                $contrato['entregaCompleta'] = $form['completa'];
                return $contrato;
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

                return $this->load->view('proceso/tareas/view_aprueba_pedido', null, true);

                break;

            case 'Entrega pedido pendiente':
                $this->load->model('almacen/Notapedidos');

                $data['pema_id'] = $this->Notapedidos->getXCaseId($tarea['rootCaseId'])['pema_id'];

                $data['list_deta_pema'] = $this->Ordeninsumos->get_detalle_entrega($data['pema_id']);

                return $this->load->view('proceso/tareas/view_entrega_pedido_pendiente', $data, true);

                break;

            case 'Comunica Rechazo':

                $this->load->model('almacen/Notapedidos');

                $data['motivo'] = $this->Notapedidos->getXCaseId($tarea['rootCaseId'])['motivo_rechazo'];

                return $this->load->view('proceso/tareas/view_comunica_rechazo', $data, true);

                break;

            default:
                # code...
                break;
        }
    }
}
