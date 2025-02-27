<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Almtareas extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model(ALM . 'Ordeninsumos');
        $this->load->model(ALM . 'Notapedidos');
        $this->load->model(ALM . 'new/Pedidosmateriales');
        $this->load->model(ALM . 'Pedidoextra');

    }

    public function map($tarea)
    {
        $array = array();
        $rsp = $this->getInfoPedMateriales($tarea->caseId);

        if(!$rsp['data']){
            return $array;
        }

        $infoPema = $rsp['data'];
        
        if(isset($infoPema->pema_id)){
            
            $aux = new StdClass();
            $aux->color = 'warning';
            $aux->texto = "N° Pedido: $infoPema->pema_id";
            $array['info'][] = $aux;

            $aux = new StdClass();
            $aux->color = 'warning';
            $aux->texto = "Lote: $infoPema->lote_id";
            $array['info'][] =$aux;

            $aux = new StdClass();
            $aux->color = 'warning';
            $aux->texto = "Estado: $infoPema->estado";
            $array['info'][] = $aux;

            $aux = new StdClass();
            $aux->color = 'default';
            $aux->texto = "Fecha: ".formatFechaPG($infoPema->fecha);
            $array['info'][] = $aux;

            $array['descripcion'] = $infoPema->justificacion?$infoPema->justificacion:'Pedido Materiales sin Justificación';
        }else{

            $data = $this->Notapedidos->getXCaseId($tarea->caseId);

            $aux = new StdClass();
            $aux->color = 'warning';
            $aux->texto = "N° Pedido: ".$data['pema_id'];
            $array['info'][] = $aux;

            $aux = new StdClass();
            $aux->color = 'warning';
            $aux->texto = "Estado: ".$data['estado'];
            $array['info'][] = $aux;

            $aux = new StdClass();
            $aux->color = 'default';
            $aux->texto = "Fecha: ".formatFechaPG($data['fecha']);
            $array['info'][] = $aux;

            //guardo el deposito asociado al pedido de materiales 
            $aux = new StdClass();
            $aux->color = 'default';
            //$aux->texto ="Deposito: ". $data['depo_id'];
            $aux->depo_id = $data['depo_id'];
            $array['info'][] = $aux;

            $aux = new StdClass();
            $aux->color = 'default';
            $aux->texto = "Justificacion: ".$data['justificacion'];
            $array['info'][] = $aux;

        }


        return $array;
    }

    public function desplegarCabecera($tarea)
    {
        $resp = infoproceso($tarea);
        return $resp;
    }

    public function desplegarVista($tarea)
    {
        switch ($tarea->nombreTarea) {

            case 'Aprueba pedido de Recursos Materiales':

                $data['pema_id'] = $this->Notapedidos->getXCaseId($tarea->caseId)['pema_id'];

                return $this->load->view(ALM . 'proceso/tareas/pedido_materiales/view_aprueba_pedido', $data, true);

                break;

            case 'Entrega pedido pendiente':

                if (viewOT) {
                    $this->load->model('Otrabajos');
                }

                $proceso = $tarea->processId;

                $aux = null;

                if ($proceso == BPM_PROCESS_ID_PEDIDOS_EXTRAORDINARIOS) {

                    $aux = $this->Pedidoextra->getXCaseId($tarea->caseId);

                } else {

                    $aux = $this->Notapedidos->getXCaseId($tarea->caseId);

                }

                $data['pema_id'] = $aux['pema_id'];

                $data['list_deta_pema'] = $this->Ordeninsumos->get_detalle_entrega($data['pema_id']);

                if (viewOT) {
                    $ot = $this->Otrabajos->obtenerOT($aux['ortr_id']);

                    $data['estadoOT'] = !($ot->estado == 'T' || $ot->estado == 'CA');
                }
                return $this->load->view(ALM . 'proceso/tareas/pedido_materiales/view_entrega_pedido_pendiente', $data, true);

                break;

            case 'Comunica Rechazo':

                $proceso = $tarea->processId;
                $res = null;
                $obj = new stdClass();

                if ($proceso == BPM_PROCESS_ID_PEDIDOS_EXTRAORDINARIOS) {

                    $res = $this->Pedidoextra->getXCaseId($tarea->caseId);

                } else {

                    $res = $this->Notapedidos->getXCaseId($tarea->caseId);

                }

                $obj->pema_id = $res['pema_id'];
                $obj->motivo = $res['motivo_rechazo'];

                // $this->load->model('traz-comp/Componentes');
                $this->load->model(ALM.'traz-comp/Componentes');

                #COMPONENTE ARTICULOS
                $data['items'] = $this->Componentes->listaArticulos();
                $data['lang'] = lang_get('spanish', 'Ejecutar OT');

                $data['info'] = $obj;

                return $this->load->view(ALM . 'proceso/tareas/pedido_materiales/view_comunica_rechazo', $data, true);

                break;

            // ?PEDIDO MATERIALES EXTRAORDINARIOS

            case 'Aprueba pedido de Recursos Materiales Extraordinarios':

                $data = $this->Pedidoextra->getXCaseId($tarea->caseId);

                return $this->load->view(ALM . 'proceso/tareas/pedido_extraordinario/view_aprueba_pedido', $data, true);

                break;

            case 'Solicita Compra de Recursos Materiales Extraordiinarios':

                $data = $this->Pedidoextra->getXCaseId($tarea->caseId);

                return $this->load->view(ALM . 'proceso/tareas/pedido_extraordinario/view_aprueba_compras', $data, true);

                break;

            case 'Comunica Rechazo por Compras':

                $data['motivo'] = $this->Pedidoextra->getXCaseId($tarea->caseId)['motivo_rechazo'];

                return $this->load->view(ALM . 'proceso/tareas/pedido_materiales/view_comunica_rechazo', $data, true);

                break;

            case 'Generar Pedido de Materiales':

                $peex = $this->Pedidoextra->getXCaseId($tarea->caseId);

                // $this->load->model('traz-comp/Componentes');
                $this->load->model(ALM.'traz-comp/Componentes');

                $data = $this->Componentes->listaArticulos();

                $data['pema_id'] = $peex['pema_id'];

                $data['peex_id'] = $peex['peex_id'];

                return $this->load->view(ALM . 'proceso/tareas/pedido_extraordinario/view_generar_pedido_materiales', $data, true);

                break;

            default:
                # code...
                break;
        }
    }

    public function getContrato($tarea, $form)
    {

        switch ($tarea->nombreTarea) {
            case 'Aprueba pedido de Recursos Materiales':

                $this->Notapedidos->setMotivoRechazo($form['pema_id'], $form['motivo_rechazo']);

                $this->Pedidosmateriales->setEstado($form['pema_id'], $form['result'] == "true" ? 'Aprobado' : 'Rechazado');

                $contrato['apruebaPedido'] = $form['result'];

                return $contrato;

                break;

            case 'Entrega pedido pendiente':
                log_message('DEBUG', '#TRAZA | #TRAZ-COMP-ALMACENES | Almtareas | getContrato()  >> case: ' . $tarea->nombreTarea);

                $this->load->model(ALM . 'Ordeninsumos');
                $this->Ordeninsumos->insert_entrega_materiales($form);

                if ($form['completa'] == "true") {
                    $this->Pedidosmateriales->setEstado($form['pema_id'], $form['completa'] == "true" ? 'Entregado' : 'Ent. Parcial');
                    $contrato['entregaCompleta'] = $form['completa'];

                    return $contrato;
                    break;
                }else if ($form['parcial'] == "true") {
                    $this->Pedidosmateriales->setEstado($form['pema_id'], $form['parcial'] == "true" ? 'Finalizado Ent. Parcial' : 'Ent. Parcial');
                    $contrato['entregaCompleta'] = $form['completa'];

                    return $contrato;
                    break;
                }else if ($form['sinEntrega'] == "true") {
                    $this->Pedidosmateriales->setEstado($form['pema_id'], $form['sinEntrega'] == "true" ? 'Finalizado Sin Entrega' : 'Ent. Parcial');
                    $contrato['entregaCompleta'] = true;

                    return $contrato;
                    break;
               }else{
                $this->Pedidosmateriales->setEstado($form['pema_id'], $form['completa'] == "true" ? 'Entregado' : 'Ent. Parcial');
                $contrato['entregaCompleta'] = $form['completa'];

                return $contrato;
                break;
               } 
               
               

            // ?PEDIDO MATERIALES EXTRAORDINARIOS

            case 'Aprueba pedido de Recursos Materiales Extraordinarios':

                $this->Pedidoextra->setMotivoRechazo($form['peex_id'], $form['motivo_rechazo']);

                $contrato['apruebaPedido'] = $form['result'];

                return $contrato;

                break;

            case 'Comunica Rechazo':

                # ACCIONES ANTES DE CERRAR TAREA
                if ($form['result'] == "true") {
                    $this->load->model('Pedidosmateriales');
                    $this->Pedidosmateriales->pedidoNormal($form['pema_id']);
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

                $this->Notapedidos->setCaseId($form['pema_id'], $tarea->caseId);

                return;

                break;

            default:
                # code...
                break;
        }
    }

    public function getInfoPedMateriales($caseId)
    {
        $resource = '/pedidoMateriales';

        $url = REST_ALM . $resource . '/' . $caseId;

        $rsp = $this->rest->callAPI('GET', $url);

        if ($rsp['status']) {

            $rsp['data'] = json_decode($rsp['data'])->info;
           
        }

        return $rsp;
    }

    public function pedidoNormal($pemaId)
    {
        //? DEBE EXISTIR LA NOTA DE PEDIDO
        $contract = [
            'pIdPedidoMaterial' => $pemaId,
        ];

        $rsp['data'] = $this->bpm->lanzarProceso(BPM_PROCESS_ID_PEDIDOS_NORMALES, $contract);

        $this->Notapedidos->setCaseId($pemaId, $rsp['data']['caseId']);

        return $this->Pedidosmateriales->setEstado($pemaId, 'Creada');
    }

    public function pedidoExtraordinario($ot = 1)
    {
        $pedidoExtra = 'Soy un Pedido';
        //? SE DEBE CORRESPONDER CON UN ID EN LA TABLE ORDEN_TRABAJO SINO NO ANDA

        $contract = [
            'pedidoExtraordinario' => $pedidoExtra,
        ];

        $data = $this->bpm->lanzarProceso(BPM_PROCESS_ID_PEDIDOS_EXTRAORDINARIOS, $contract);

        $peex['case_id'] = $data['data']['caseId'];
        $peex['fecha'] = date("Y-m-d");
        $peex['detalle'] = $pedidoExtra;
        $peex['ortr_id'] = $ot;
        $peex['empr_id'] = empresa();

        return $this->Pedidoextra->set($peex);
    }
}