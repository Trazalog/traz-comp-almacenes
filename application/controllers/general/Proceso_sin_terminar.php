<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Proceso extends CI_Controller {
    function __construct(){

      parent::__construct();
      $this->load->library('BPM'); 
   }

   function index()
   {
       // SUPERVISOR1 => 102 => Aprueba pedido de Recursos Materiales
       $data = ['userId' => 102,'device'=>'','permission'=>'Add-View-Del-Edit'];
       $this->session->set_userdata('user_data',[$data]);

    //  $contract = [
    //      'pIdPedidoMaterial' => 1
    //  ];

    //   $data = $this->bpm->LanzarProceso($contract);

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
    $res =  $this->bpm->getToDoList(620008);
    $data['list'] = $res['data'];
    $this->load->view('proceso/tareas/list',$data);

   }

   function detalleTarea($task_id){
     $data['device'] = "";
     $data['timeline'] = "";
     $data['comentarios'] = "";
     $data['tarea'] = $this->bpm->getTarea($task_id)['body'];
     $data['view'] = $this->deplegarVista($data['tarea']->displayName);
     $this->load->view('proceso/tareas/view_',$data);
   }

   function tomarTarea(){
     $id = $this->input->post('id');
     echo json_encode(['status'=>true]);
   }

   function deplegarVista($nombre){
     switch ($nombre) {
       case 'Aprueba pedido de Recursos Materiales':
         return $this->load->view('proceso/tareas/view_aprueba_pedido',null,true);
         break;
       case 'a':
        # code...
        break;
      case 'b':
      # code...
      break; 
       
      default:
        # code...
        break;
     }
   }
}
?>