<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notapedidos extends CI_Model
{
	function __construct()
	{
		parent::__construct();
    }
    
    function setMotivoRechazo($pema, $motivo){

        $this->db->where('pema_id', $pema);
        $this->db->set('motivo_rechazo', $motivo);
        return $this->db->update('alm_pedidos_materiales');
        
    }
	
    function notaPedidos_List()
    {
    //    $userdata = $this->session->userdata('user_data');
        $empId    = 1;// $userdata[0]['id_empresa'];

        $this->db->select('T.pema_id as id_notaPedido,T.fecha,T.ortr_id asid_ordTrabajo,orden_trabajo.descripcion');
        $this->db->from('alm_pedidos_materiales T');
        $this->db->join('orden_trabajo','T.ortr_id = orden_trabajo.id_orden');
        $this->db->where('T.empr_id', $empId);
        $query = $this->db->get();

        if ($query->num_rows()!=0)
        {
            return $query->result_array();          
        }
        else
        { 
            return array();
        }
    }

    function get($id){
        $this->db->where('pema_id',$id);
        return $this->db->get('alm_pedidos_materiales')->row_array();
    }

    function getXCaseId($case){
        $this->db->where('case_id',$case);
        return $this->db->get('alm_pedidos_materiales')->row_array();
    }

    function setCaseId($id,$case){
        $this->db->set('case_id',$case);
        $this->db->where('pema_id',$id);
        $this->db->update('alm_pedidos_materiales');
    }

        //
    function getNotasxOT($id)
    {
        //$userdata = $this->session->userdata('user_data');
        $empId  =  1;// = $userdata[0]['id_empresa'];
        $this->db->select('
            alm_pedidos_materiales.pema_id as id_notaPedido,
            alm_pedidos_materiales.fecha,
            alm_pedidos_materiales.ortr_id as id_ordTrabajo,
            solicitud_reparacion.solicitante,
            orden_trabajo.descripcion
        ');
        $this->db->from('alm_pedidos_materiales');
        $this->db->join('orden_trabajo','alm_pedidos_materiales.ortr_id = orden_trabajo.id_orden');
        $this->db->join('solicitud_reparacion', 'orden_trabajo.id_solicitud = solicitud_reparacion.id_solicitud','left');
        $this->db->where('alm_pedidos_materiales.empr_id', $empId);
        $this->db->where('orden_trabajo.id_orden', $id);

        $query = $this->db->get();

        if ($query->num_rows()!=0)
        {
            return $query->result_array();          
        }
        else
        { 
            return array();
        }
    }

    // Trae lista de articulos por id de nota de pedido 
    function getNotaPedidoIds($id){
      
      $this->db->select('alm_pedidos_materiales.pema_id as id_notaPedido,
                          alm_pedidos_materiales.fecha,
                          alm_pedidos_materiales.ortr_id as id_ordTrabajo,
                          orden_trabajo.descripcion,
                          alm_deta_pedidos_materiales.cantidad,
                          alm_deta_pedidos_materiales.fecha_entrega,
                          alm_deta_pedidos_materiales.fecha_entregado,
                          alm_articulos.barcode,
                          alm_articulos.descripcion as artDescription'
                        );
      $this->db->from('alm_pedidos_materiales');
      $this->db->join('orden_trabajo', 'alm_pedidos_materiales.ortr_id = orden_trabajo.id_orden');
      $this->db->join('alm_deta_pedidos_materiales', 'alm_deta_pedidos_materiales.pema_id = alm_pedidos_materiales.pema_id');
      $this->db->join('alm_articulos', 'alm_deta_pedidos_materiales.arti_id = alm_articulos.arti_id');
      $this->db->where('alm_pedidos_materiales.pema_id', $id);
      $query = $this->db->get();
      
      if ($query->num_rows()!=0){      
        return $query->result_array();       
      }
      else{ 
        return array();
      }
    }	
            
    function getArticulos()
    {
        $query = $this->db->query("SELECT alm_articulos;.artId, alm_articulos;.artBarCode,alm_articulos;.artDescription FROM alm_articulos;");
        $i     = 0;
        foreach ($query->result() as $row){

            $insumos[$i]['value']       = $row->artId;
            $insumos[$i]['label']       = $row->artBarCode;
            $insumos[$i]['descripcion'] = $row->artDescription;
            $i++;
        }
        return $insumos;
    }  

    function getProveedores(){
        
			$this->db->select('alm_proveedores.provid, alm_proveedores.provnombre');
			$this->db->from('alm_proveedores');        
			$query = $this->db->get();
			if ($query->num_rows() != 0){
					
					return $query->result_array();             
			}   
    }  

    function setNotaPedidos($data)
    {
        $userdata = $this->session->userdata('user_data');
        $empId    = $userdata[0]['id_empresa'];

        $orden = $data['orden_Id'][0]; 
        $notaP = array(
            'fecha'         => date('Y-m-d H:i:s'),
            'id_ordTrabajo' => $orden,
            'id_empresa'    => $empId
            );
        $this->db->insert('alm_pedidos_materiales', $notaP);
        $idNota = $this->db->insert_id();
       
        for($i=0; $i<count($data['insum_Id']); $i++){

            $insumo  = $data['insum_Id'][$i];
            $cant    = $data['cant_insumos'][$i];
            $proveed = $data['proveedid'][$i];
            $date    = $data['fechaentrega'][$i]; 
            $newDate = date("Y-m-d", strtotime($date));

            $nota = array(
                    'id_notaPedido' => $idNota,
                    'artId' => $insumo,
                    'cantidad' => $cant,
                    'provid' => $proveed,
                    'fechaEntrega' => $newDate,
                    'fechaEntregado' => $newDate,
                    'remito' => 1,
                    'estado' => 'P' // Estado Pedido
                    );
            $this->db->insert('alm_deta_pedidos_materiales', $nota);
        }

      if ($this->db->trans_status() === FALSE)
      {
           return false;  
      }
      else{
           return true;
      }    

    } // fin setNotaPedidos   
    
    // devuelve plantilla por Id de cliente
    function getPlantillaPorCliente($idcliente){
			//FIXME: DESHARDCODEAR ESTE CLIENTE!!!!
			$idcliente = 21;
			$this->db->select('asp_detaplantillainsumos.artId,
												alm_articulos;.artDescription,
												asp_plantillainsumos.plant_id');
			$this->db->from('asp_detaplantillainsumos'); 
			$this->db->join('asp_plantillainsumos', 'asp_detaplantillainsumos.plant_id = asp_plantillainsumos.plant_id');
			$this->db->join('alm_articulos;', 'alm_articulos;.artId = asp_detaplantillainsumos.artId');
			$this->db->join('admcustomers', 'asp_plantillainsumos.plant_id = admcustomers.plant_id');
			$this->db->where('admcustomers.plant_id','(SELECT admcustomers.plant_id WHERE admcustomers.cliId = '.$idcliente.')', false);       
			$query = $this->db->get();
			if ($query->num_rows() != 0){					
				return $query->result_array();             
			}else {
				return array();
			}
    }
    // guarda nota pedido (desde tareas de bpm)
    function setCabeceraNota($cabecera){

			$this->db->insert('alm_pedidos_materiales', $cabecera);
			$idInsert = $this->db->insert_id();
			return $idInsert;
		}
		// guarda detalle nota pedido (desde tareas de bpm)
		function setDetaNota($deta){
			$response = $this->db->insert_batch('alm_deta_pedidos_materiales',$deta);
			return $response;
		}		

}
