<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ordeninsumos extends CI_Model {
	
    function __construct()
	{
		parent::__construct();
	}

	function getList()
    {
        $userdata  = $this->session->userdata('user_data');
        $empresaId = $userdata[0]['id_empresa'];
   
        $this->db->select('orden_insumos.id_orden, orden_insumos.fecha, orden_insumos.solicitante, orden_insumos.comprobante,
            orden_trabajo.id_orden as id_ot, orden_trabajo.descripcion');
        $this->db->from('orden_insumos');
        $this->db->join('orden_trabajo','orden_insumos.id_ot = orden_trabajo.id_orden');
        $this->db->where('orden_insumos.id_empresa', $empresaId);
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
        $userdata  = $this->session->userdata('user_data');
        $empresaId = $userdata[0]['id_empresa'];
        $sql       = "SELECT articles.artId, tbl_lote.loteid,articles.artBarCode, articles.artDescription
        	FROM articles
        	JOIN tbl_lote ON tbl_lote.artId= articles.artId  
        	WHERE tbl_lote.artId=articles.artId
            AND tbl_lote.lotestado='AC'
            AND articles.id_empresa = $empresaId
            GROUP BY tbl_lote.artId;
        	";
        $query = $this->db->query($sql);
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

	function getdescrip($data = null)
    {
		if($data == null)
		{
			return false;
		}
		else
		{
			$id = $data['artId'];
			//Datos del usuario
			$query = $this->db->get_where('articles', array('artId'=>$id));
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
        $userdata           = $this->session->userdata('user_data');
        $data['id_empresa'] = $userdata[0]['id_empresa'];
        $query              = $this->db->insert("orden_insumos", $data);
        return $query;
    }

    public function insert_detaordeninsumo($data2)
    {
        $userdata           = $this->session->userdata('user_data');
        $data2['id_empresa'] = $userdata[0]['id_empresa'];
        $query              = $this->db->insert("deta_ordeninsumos", $data2);
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
            $userdata  = $this->session->userdata('user_data');
            $empresaId = $userdata[0]['id_empresa'];
            $id        = $data['artId'];
            $sql       = "SELECT articles.artId, abmdeposito.depositoId, abmdeposito.depositodescrip
    			FROM articles
    			JOIN tbl_lote ON tbl_lote.artId = articles.artId
    			JOIN abmdeposito ON abmdeposito.depositoId = tbl_lote.depositoid
    			WHERE tbl_lote.artId = $id
                AND tbl_lote.id_empresa = $empresaId
                ";
			$query = $this->db->query($sql);
			if($query->num_rows()>0){
                return $query->result();
            }
            else{
                return false;
            }
		}
	}

    function getlotecant($id){
    	$sql="SELECT  tbl_lote.cantidad
    	FROM tbl_lote
    	WHERE tbl_lote.depositoid=$id AND tbl_lote.lotestado='AC'
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
    	 	dump($cantidadLote);
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
		dump($datos3);
					        	
		$this->update_tbllote($idLote,$datos3);
        return $idLote;
	}

    function traeIdLote($idarticulo,$iddeposito)
    {
        $this->db->select('tbl_lote.loteid');
        $this->db->from('tbl_lote');
        $this->db->where('tbl_lote.artId', $idarticulo);
        $this->db->where('tbl_lote.depositoid', $iddeposito);
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
  		$sql = "SELECT tbl_lote.cantidad
			FROM tbl_lote
			WHERE tbl_lote.loteid = $v";
   		$query = $this->db->query($sql);
   	  	foreach ($query->result() as $row)
        {
            $datos = $row->cantidad;
        }
  	    return $datos;
    }


    public function update_tbllote($id,$data3){
            $this->db->where('loteid', $id);
            $query = $this->db->update("tbl_lote",$data3);
            return $query;
    }

    public function alerta($codigo,$de)
    {
        $sql="SELECT tbl_lote.cantidad
			FROM tbl_lote
			WHERE tbl_lote.artId=$codigo AND tbl_lote.depositoid=$de
			";
		$query = $this->db->query($sql);
	  	foreach ($query->result() as $row)
        {          
            $datos = $row->cantidad;
        }
	    return $datos;
    }

    function getsolImps($id){

        $sql="SELECT orden_insumos.fecha,orden_insumos.solicitante,orden_insumos.comprobante, deta_ordeninsumos.id_ordeninsumo, deta_ordeninsumos.loteid, deta_ordeninsumos.cantidad
                  FROM orden_insumos
                  JOIN deta_ordeninsumos ON deta_ordeninsumos.id_ordeninsumo=orden_insumos.id_orden
                  
                 

                  WHERE orden_insumos.id_orden=$id
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
        
        $sql= "SELECT deta_ordeninsumos.loteid, deta_ordeninsumos.cantidad, deta_ordeninsumos.id_ordeninsumo, tbl_lote.artId, articles.artBarCode, articles.artDescription 
                FROM deta_ordeninsumos
                
                JOIN tbl_lote ON tbl_lote.loteid = deta_ordeninsumos.loteid
                JOIN articles ON articles.artId= tbl_lote.artId
                WHERE deta_ordeninsumos.id_ordeninsumo=$id
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
            FROM orden_insumos
            JOIN deta_ordeninsumos ON deta_ordeninsumos.id_ordeninsumo = orden_insumos.id_orden 	  
            WHERE orden_insumos.id_orden = $id
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
        $userdata  = $this->session->userdata('user_data');
        $empresaId = $userdata[0]['id_empresa'];
	    $sql       = "SELECT deta_ordeninsumos.id_detaordeninsumo, deta_ordeninsumos.id_ordeninsumo, deta_ordeninsumos.loteid, deta_ordeninsumos.cantidad, tbl_lote.codigo, tbl_lote.depositoid, articles.artId, articles.artBarCode, articles.artDescription, abmdeposito.depositodescrip
    		FROM deta_ordeninsumos
			JOIN tbl_lote ON tbl_lote.loteid = deta_ordeninsumos.loteid
			JOIN articles ON articles.artId = tbl_lote.artId
			JOIN abmdeposito ON abmdeposito.depositoid = tbl_lote.depositoid
			WHERE deta_ordeninsumos.id_ordeninsumo = $id
            AND deta_ordeninsumos.id_empresa = $empresaId
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
        $userdata  = $this->session->userdata('user_data');
        $empresaId = $userdata[0]['id_empresa'];
	    $sql       = "SELECT SUM(deta_ordeninsumos.cantidad) as cantidad
    		FROM deta_ordeninsumos
			JOIN orden_insumos ON orden_insumos.id_orden = deta_ordeninsumos. id_ordeninsumo
			WHERE deta_ordeninsumos.id_ordeninsumo = $id
            AND deta_ordeninsumos.id_empresa = $empresaId
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
