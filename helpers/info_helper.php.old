<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('info_orden')) {
    function info_orden($id_OT=null){
        $ci =& get_instance();			
            //load databse library
            $ci->load->database();		
        if($id_OT){

            $resultOT = null;
       
            $ci->db->select('tareas.descripcion AS tareaDescrip,
                                            orden_trabajo.descripcion AS otDescrip,
                                            orden_trabajo.fecha,
                                            orden_trabajo.id_orden,
                                            orden_trabajo.duracion,
                                            orden_trabajo.estado');	
            $ci->db->from('orden_trabajo');		
            $ci->db->join('sim_test.tareas', 'tareas.id_tarea = orden_trabajo.id_tarea','left');			
            $ci->db->where('orden_trabajo.id_orden', $id_OT);
            $queryOT = $ci->db->get();			
            if($queryOT->num_rows() > 0){
                    $resultOT = $queryOT->row_array();
            }

            if(!$resultOT) return;
				
            return '

                        <div class="col-xs-12 col-sm-4">
                            <div class="form-group">
                                    <label style="margin-top: 7px;">Nº Orden Trabajo: </label>
                                    <input type="text" id="ot" class="form-control" value="'.$resultOT['id_orden'].'" disabled/>
                            </div>						
                        </div>
                            
                        <div class="col-xs-12 col-sm-4">
                            <div class="form-group">
                                    <label style="margin-top: 7px;">Descripción: </label>
                                    <input type="text"  class="form-control" value="'.$resultOT['otDescrip'].'" disabled/>
                            </div>						
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="form-group">
                                    <label style="margin-top: 7px;">Fecha: </label>
                                    <input type="text" id="codigo" class="form-control" value="'.$resultOT['fecha'].'" disabled/>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <label style="margin-top: 7px;">Duración: </label>
                            <input type="text" id="duracion" class="form-control"  value="'.$resultOT['duracion'].'" disabled/>
                        </div>
                        
                        <div class="col-xs-12 col-sm-4">
                            <label style="margin-top: 7px;">Tarea: </label>
                            <input type="text" class="form-control"  value="'.$resultOT['tareaDescrip'].'" disabled/>
                        </div>
                        
                        <div class="col-xs-12 col-sm-4">
                            <label style="margin-top: 7px;">Estado: </label>
                            <input type="text" class="form-control"  value="'.$resultOT['estado'].'" disabled/>
                        </div>';
        }	
    }

    function info_pedido($pema){
        $ci =& get_instance();			
        $ci->load->database();	

        $data = $ci->db->get_where('alm_pedidos_materiales',array('pema_id'=>$pema))->row_array();

        return  
        '<div class="col-xs-12 col-sm-12">
            <div class="form-group">
                    <label style="margin-top: 7px;">Justificación: </label>
                    <textarea class="form-control" disabled>'.$data['justificacion'].'</textarea>
            </div>						
        </div>
        <div class="col-xs-12 col-sm-6">
            <div class="form-group">
                    <label style="margin-top: 7px;">Fecha: </label>
                    <input type="text" id="ot" class="form-control" value="'.$data['fecha'].'" disabled/>
            </div>						
        </div>
        <div class="col-xs-12 col-sm-6">
            <div class="form-group">
                    <label style="margin-top: 7px;">Estado: </label>
                    <input type="text" class="form-control" value="'.$data['estado'].'" disabled/>
            </div>						
        </div>';
    }

    function estadoPedido($estado){
        switch ($estado) {
            case 'Creada':
              return bolita($estado ,'purple');
              break;
            case 'Solicitado':
                return bolita($estado ,'orange');
                break;
            case 'Aprobado':
                return bolita($estado ,'green');
                break;
            case 'Rechazado':
                return bolita($estado ,'red');
                break;
            case 'Ent. Parcial':
                return bolita($estado ,'blue');
                break;
            case 'Entregado':
                return bolita($estado ,'green');
                break;
            case 'Cancelado':
                return bolita($estado ,'red');
                break;
            default:
                return bolita('S/E' ,'');
                break;
        }
    }

    function msjStatus($status, $msj, $data=null){
        return array(
            'status'=> $status,
            'msj'=> $msj,
            'data'=> $data
        );
    }
}