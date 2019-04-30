<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ordeninsumos extends CI_Model {
	
    function __construct()
	{
		parent::__construct();
	}

	function getList()
    {
        //$userdata  = $this->session->userdata('user_data');
        $empresaId = 1;//$userdata[0]['id_empresa'];
   
        $this->db->select('alm_entrega_materiales.enma_id as id_orden,alm_entrega_materiales.ortr_id as id_ot, alm_entrega_materiales.fecha, alm_entrega_materiales.solicitante, alm_entrega_materiales.comprobante');
        $this->db->from('alm_entrega_materiales');
        $this->db->where('alm_entrega_materiales.empr_id', $empresaId);

        $query = $this->db->get();
	    if( $query->num_rows() > 0)
	    {
            $data['openBox'] = 1;
            $data['data']    = $query->result_array();	
            return $data;
	    } 
	    else { $data['openBox'] = 1;
            return $data;
	    }
	}

    function getcodigo()
    {
        //$userdata  = $this->session->userdata('user_data');
        $empresaId = 1;//$userdata[0]['id_empresa'];
        $this->db->select('alm_articulos.arti_id as artId ,alm_lotes.lote_id as loteid,alm_articulos.barcode as artBarCode, alm_articulos.descripcion as artDescription');
       
       $this->db->from('alm_articulos');
       $this->db->join('alm_lotes ','alm_lotes.arti_id = alm_articulos.arti_id');
       $this->db->join('utl_tablas', 'utl_tablas.tabl_id = alm_lotes.estado_id');
       $this->db->where('alm_lotes.arti_id = alm_articulos.arti_id');
      // $this->db->where('utl_tablas.valor','AC');
       $this->db->where('alm_articulos.empr_id' ,$empresaId);
       $this->db->group_by('alm_lotes.arti_id');
        
        $query = $this->db->get();

		if($query->num_rows()>0)
        {
	       return $query->result();
	    }
	    else
        {
	       return false;
	    }
	}
	
    function getsolicitante()
    {
        $userdata  = $this->session->userdata('user_data');
        $empresaId = $userdata[0]['id_empresa'];

        $this->db->select('id_solicitud, solicitante');
        $this->db->from('solicitud_reparacion');
        $this->db->where('id_empresa', $empresaId);
        $this->db->group_by('solicitante');

        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->result();
        }
        else
        {
            return false;
        }
    }

    	// devuelve las ot de la empresa y que esten curso o asignadas
	function getOT(){

		$userdata  = $this->session->userdata('user_data');
		$empresaId = $userdata[0]['id_empresa'];		
		
		$this->db->select('orden_trabajo.id_orden, orden_trabajo.descripcion');
		$this->db->from('orden_trabajo');
		$this->db->where('orden_trabajo.id_empresa', $empresaId); //de la empresa
		$this->db->where('orden_trabajo.estado', 'C'); //que estan en curso
		$this->db->or_where('orden_trabajo.estado', 'AS'); //que asignadas
		
		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->result();
		}
		else
		{
			return false;
		}     
	}

	function getdescrip($data = null)
    {
		if($data == null)
		{
			return false;
		}
		else
		{
			$id = $data['arti_id'];
			//Datos del usuario
			$query = $this->db->get_where('alm_articulos', array('arti_id'=>$id));
			if($query->num_rows()>0){
                return $query->result();
            }
            else
            {
                return false;
            }
		}
	}

	public function insert_orden($data)
    {
        //$userdata           = $this->session->userdata('user_data');
        $data['empr_id'] = 1;//$userdata[0]['id_empresa'];
        $query              = $this->db->insert("alm_entrega_materiales", $data);
        return $query;
    }

    public function insert_detaordeninsumo($data2)
    {
        //$userdata           = $this->session->userdata('user_data');
        $data2['empr_id'] = 1;//$userdata[0]['id_empresa'];
        $query              = $this->db->insert("alm_deta_entrega_materiales", $data2);
        return $query;
    }

    function getdeposito($data = null)
    {
	    if($data == null)
		{
			return false;
		}
		else
		{
            //$userdata  = $this->session->userdata('user_data');
            $empresaId = 1;//$userdata[0]['id_empresa'];
            $id        = $data['artId'];

            $this->db->select('alm_articulos.arti_id as artId, alm_depositos.depo_id as depositoId, alm_depositos.descripcion as depositodescrip');
            $this->db->from('alm_articulos');
            $this->db->join('alm_lotes','alm_lotes.arti_id = alm_articulos.arti_id');
            $this->db->join('alm_depositos','alm_depositos.depo_id = alm_lotes.depo_id');
            $this->db->where('alm_lotes.arti_id',$id);
            $this->db->where('alm_lotes.empr_id',$empresaId);

			$query = $this->db->get();
			if($query->num_rows()>0){
                return $query->result();
            }
            else{
                return false;
            }
		}
	}

    function getlotecant($id){
    	$sql="SELECT  alm_lotes.cantidad
    	FROM alm_lotes
    	WHERE alm_lotes.depo_id=$id AND alm_lotes.lotestado='AC'
    	";
    	$query= $this->db->query($sql);

    	$i=0;
                   foreach ($query->result_array() as $row)
                   {   
                       
                       $datos[$i]= $row['cantidad'];
                       $i++;
                   }

    		
    	    return $datos;    
    }


    function lote($idarticulo,$cantidadOrdenInsumo,$iddeposito)
    {
        $result = $this->traeIdLote($idarticulo,$iddeposito);
 

        $idLote = $result[0]["loteid"];
    	if ($idLote!=0) {
    	 	$cantidadLote = $this->lotecantidad($idLote); //obtengo la cantidad segun el lote
    	 	//dump($cantidadLote);
    	} else {
            echo  "No hay insumos"; 
        }
    	if ($cantidadLote >= $cantidadOrdenInsumo) {
    		$res = $cantidadLote - $cantidadOrdenInsumo;
		}	
		else {
			echo  "No hay insumos suficientes"; 
			//$res=$cantidadOrdenInsumo - $cantidadLote;
		}	

		$datos3 = array(
			'cantidad'=>$res
		);
		//dump($datos3);
					        	
		$this->update_tbllote($idLote,$datos3);
        return $idLote;
	}

    function traeIdLote($idarticulo,$iddeposito)
    {
        $this->db->select('alm_lotes.lote_id as loteid');
        $this->db->from('alm_lotes');
        $this->db->where('alm_lotes.arti_id', $idarticulo);
        $this->db->where('alm_lotes.depo_id', $iddeposito);
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->result_array();
        }
        else
        {
            return false;
        }    
    }

	function lotecantidad($v)
    {
  		$sql = "SELECT alm_lotes.cantidad
			FROM alm_lotes
			WHERE alm_lotes.lote_id = $v";
   		$query = $this->db->query($sql);
   	  	foreach ($query->result() as $row)
        {
            $datos = $row->cantidad;
        }
  	    return $datos;
    }


    public function update_tbllote($id,$data3){
            $this->db->where('lote_id', $id);
            $query = $this->db->update("alm_lotes",$data3);
            return $query;
    }

    public function alerta($codigo,$de)
    {
        $sql="SELECT alm_lotes.cantidad
			FROM alm_lotes
			WHERE alm_lotes.arti_id=$codigo AND alm_lotes.depo_id=$de
			";
		$query = $this->db->query($sql);
	  	foreach ($query->result() as $row)
        {          
            $datos = $row->cantidad;
        }
	    return $datos;
    }

    function getsolImps($id){

        $sql="SELECT T.fecha,T.solicitante,T.comprobante, A.cantidad
                  FROM alm_entrega_materiales T
                  JOIN alm_deta_entrega_materiales A ON A.enma_id=T.enma_id
                  WHERE T.ortr_id=$id
              ";
        
        $query= $this->db->query($sql);
        foreach ($query->result_array() as $row){ 

            $data['fecha'] = $row['fecha'];
            $data['solicitante'] = $row['solicitante'];
            $data['comprobante'] = $row['comprobante'];
            $data['cantidad'] = $row['cantidad'];
           
           
           return $data; 
        }
    }

    function getequiposBycomodato($id){
        
        $sql= "SELECT alm_deta_entrega_materiales.loteid, alm_deta_entrega_materiales.cantidad, alm_deta_entrega_materiales.enma_id as ortr_idinsumo, alm_lotes.arti_id, alm_articulos.artBarCode, alm_articulos.artDescription 
                FROM alm_deta_entrega_materiales
                
                JOIN alm_lotes ON alm_lotes.loteid = alm_deta_entrega_materiales.loteid
                JOIN alm_articulos ON alm_articulos.arti_id= alm_lotes.arti_id
                WHERE alm_deta_entrega_materiales.enma_id=$id
                    ";
        
        $query= $this->db->query($sql);
        
        if( $query->num_rows() > 0)
        {
          return $query->result_array();    
        } 
        else {
          return 0;
        }
    }

    function getConsult($id)
    {
	    $sql = "SELECT * 
            FROM alm_entrega_materiales
            JOIN alm_deta_entrega_materiales ON alm_deta_entrega_materiales.enma_id = alm_entrega_materiales.enma_id 	  
            WHERE alm_entrega_materiales.enma_id = $id
            ";
	    $query = $this->db->query($sql);
	    if( $query->num_rows() > 0)
	    {
            return $query->result_array();	
	    } 
	    else {
            return 0;
	    }
	}

	function getequipos($id)
    {
        //$userdata  = $this->session->userdata('user_data');
        $empresaId = 1;//$userdata[0]['id_empresa'];
	    $sql       = "SELECT T.deen_id as id_detaordeninsumo, T.enma_id as ortr_idinsumo, T.lote_id as loteid, T.cantidad, alm_lotes.codigo, alm_lotes.depo_id, art.arti_id, art.barcode as artBarCode, art.descripcion as artDescription, alm_depositos.descripcion as depositodescrip
    		FROM alm_deta_entrega_materiales T
			JOIN alm_lotes ON alm_lotes.lote_id = T.lote_id
			JOIN alm_articulos art ON art.arti_id = alm_lotes.arti_id
			JOIN alm_depositos ON alm_depositos.depo_id = alm_lotes.depo_id
			WHERE T.enma_id = $id
            AND T.empr_id = $empresaId
			";
	    $query = $this->db->query($sql);
	    if( $query->num_rows() > 0)
	    {
            return $query->result_array();	
	    } 
	    else {
            return 0;
	    }
	}
	
	function total($id)
    {
        //$userdata  = $this->session->userdata('user_data');
        $empresaId = 1;//$userdata[0]['id_empresa'];
	    $sql       = "SELECT SUM(alm_deta_entrega_materiales.cantidad) as cantidad
    		FROM alm_deta_entrega_materiales
			JOIN alm_entrega_materiales ON alm_entrega_materiales.enma_id = alm_deta_entrega_materiales. enma_id
			WHERE alm_deta_entrega_materiales.enma_id = $id
            AND alm_deta_entrega_materiales.empr_id = $empresaId
			";
	    $query = $this->db->query($sql);
	    if( $query->num_rows() > 0)
	    {
            return $query->result_array();	
	    } 
	    else {
            return 0;
	    }
	}

}
