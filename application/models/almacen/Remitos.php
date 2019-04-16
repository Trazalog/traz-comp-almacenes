<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Remitos extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
    function getRemitosList()
    {
        $userdata  = $this->session->userdata('user_data');
        $empresaId = $userdata[0]['id_empresa'];

        $this->db->select('remitos.remitoId, remitos.fecha, remitos.comprobante,
            abmproveedores.provid, abmproveedores.provnombre');
        $this->db->from('remitos');
        $this->db->join('abmproveedores','remitos.provid = abmproveedores.provid');
        $this->db->where('remitos.id_empresa', $empresaId);
        $query = $this->db->get();
        if( $query->num_rows() > 0)
        {
            $data['data']   = $query->result_array();  
            return $data;
        } 
        else {
            return false;
        }
    }

    function getcodigo()
    {
        $userdata  = $this->session->userdata('user_data');
        $empresaId = $userdata[0]['id_empresa'];

       	$sql = "SELECT articles.artId, articles.artBarCode, articles.artDescription
            FROM articles
            WHERE articles.artEstado='AC'
            AND articles.id_empresa = $empresaId
            GROUP BY articles.artBarCode;
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

    function getdeposito()
    {
        $userdata  = $this->session->userdata('user_data');
        $empresaId = $userdata[0]['id_empresa'];
     
        $query     = $this->db->get_where('abmdeposito', array('id_empresa' => $empresaId));
            if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }

	function getproveedor()
    {
        $userdata  = $this->session->userdata('user_data');
        $empresaId = $userdata[0]['id_empresa'];
		$query     = $this->db->get_where('abmproveedores', array('id_empresa' => $empresaId));
			if($query->num_rows()>0){
	   	 	return $query->result();
	    }
	    else{
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
			$query = $this->db->get_where('articles', array('artId' => $id));
			if($query->num_rows()>0){
                return $query->result();
            }
            else{
                return false;
            }
		}
	}

    function getConsulta($id) // 
    {
        $this->db->select('remitos.comprobante, remitos.fecha, abmproveedores.provnombre');
        $this->db->from('remitos');
        $this->db->join('abmproveedores', 'abmproveedores.provid = remitos.provid'); 
        $this->db->where('remitos.remitoId', $id);
        $query= $this->db->get();   

        if ($query->num_rows()!=0)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }

    function getDetaRemitos($idRemito)
    {
        //$userdata  = $this->session->userdata('user_data');
        //$empresaId = $userdata[0]['id_empresa'];
        $sql       = "SELECT deta_remito.detaremitoid, deta_remito.id_remito, deta_remito.loteid, deta_remito.cantidad, 
            tbl_lote.codigo, tbl_lote.depositoid, 
            articles.artId, articles.artBarCode, articles.artDescription, 
            abmdeposito.depositodescrip
            FROM deta_remito
            JOIN tbl_lote ON tbl_lote.loteid = deta_remito.loteid
            JOIN articles ON articles.artId = tbl_lote.artId
            JOIN abmdeposito ON abmdeposito.depositoid = tbl_lote.depositoid
            WHERE deta_remito.id_remito = $idRemito
            ";
            //AND deta_remito.id_empresa = $empresaId
        $query = $this->db->query($sql);
        if( $query->num_rows() > 0)
        {
            return $query->result_array();  
        } 
        else {
            return 0;
        }
    }

    function alerta($codigo,$de)
    {
        //arriba es artId, prodId
        $userdata  = $this->session->userdata('user_data');
        $empresaId = $userdata[0]['id_empresa'];

        $sql       = "SELECT  tbl_lote.cantidad
            FROM tbl_lote
            WHERE tbl_lote.id_empresa = $empresaId
            AND tbl_lote.artId        = $codigo 
            AND tbl_lote.depositoid   = $de 
            AND tbl_lote.lotestado    = 'AC'
        ";
        $query = $this->db->query($sql);
        foreach($query->result() as $row)
        {   
            $datos = $row->cantidad;
        }
        return $datos;
    }

	function getlote($idHerramienta,$idDeposito)
    {
		$userdata  = $this->session->userdata('user_data');
        $empresaId = $userdata[0]['id_empresa'];

        $sql       = "SELECT tbl_lote.loteid
    		FROM tbl_lote
            WHERE tbl_lote.id_empresa = $empresaId
            AND tbl_lote.artId        = $idHerramienta 
            AND tbl_lote.depositoid   = $idDeposito";
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

    function insert_orden($data)
    {
        $userdata           = $this->session->userdata('user_data');
        $data['id_empresa'] = $userdata[0]['id_empresa'];

        $query = $this->db->insert("remitos", $data);
        return $query;
    }

    /**
     * @param   Int     $ultimoId   id del remito insertado.
     * @param   Int     $co         cantidad
     * @param   Int     $dep        id de deposito
     * @param   Array   $indice     ids de insumos
     * @param   Array   $ar         id de articulos
     */
    function detaorden($ultimoId,$co,$dep,$indice,$ar)
    {
    	$i = 1;
    	//$res=array();
    	foreach ($indice as $row) {
    		if($ar[$i]){
    			if($dep[$i]){
                    $a   = $ar[$i];                 
                    $d   = $dep[$i];                
                    $res = $this->loteres($a,$d);   //saca id de lote con articulo y deposito
    				print_r($res);
    				if($res > 0){ //si tiene id de lote
    					if($co[$i]){ //cant
    						$datos2 = array(
                             'id_remito' => $ultimoId, 
                             'loteid'    => $res,
                             'cantidad'  => $co[$i]
    		        		);
        		        	print_r($datos2);
        		        	$result = $this->insert_detaremito($datos2);
        		        	//print_r($result);
    					}
    					$this->sumarlote($res,$co[$i]);
    				}
    				else {
                        $cod    = $this->traercodigoart($ar[$i]);
                        $datos3 = array(
                            'codigo'     => $cod,		
                            'fecha'      => date("Y-m-d H:i:s"),
                            'cantidad'   => $co[$i],
                            'artId'      => $ar[$i],  //artId arriba/ local prodId 
                            'lotestado'  => 'AC',
                            'depositoId' => $dep[$i]
    						);
    					$this->insert_lote($datos3); //inserta en lote
                        $ultimolote = $this->db->insert_id();
                        print_r($ultimolote);
                        $datos2     = array(
                            'id_remito' => $ultimoId, 
                            'loteid'    => $ultimolote,
                            'cantidad'  => $co[$i]
    		        	);
    					$result = $this->insert_detaremito($datos2); //inserta en detaremito
    				}
    			}
    		}
    	$i++;
    	}
    	return $result ;
    }

    function loteres($idArticulo,$idDeposito)
    {
        $userdata  = $this->session->userdata('user_data');
        $empresaId = $userdata[0]['id_empresa'];

        $sql       = "SELECT tbl_lote.loteid
            FROM tbl_lote
            WHERE tbl_lote.id_empresa = $empresaId
            AND tbl_lote.artId        = $idArticulo 
            AND tbl_lote.depositoid   = $idDeposito
            ";
        $query = $this->db->query($sql);
        if($query->result()){
            foreach ($query->result() as $row){       
                $datos = $row->loteid;     
            }
            return $datos;
        }
        else 
        {
            return 0;
        }   
    }

    function insert_detaremito($data2)
    {
        $userdata            = $this->session->userdata('user_data');
        $data2['id_empresa'] = $userdata[0]['id_empresa'];

        $query = $this->db->insert("deta_remito", $data2);
        return $query;
    }

    function sumarlote($res,$co)
    {
        $sql = "SELECT tbl_lote.cantidad
            FROM tbl_lote
            WHERE tbl_lote.loteid = $res";
        $query = $this->db->query($sql); //aca esta la canidad
        foreach ($query->result() as $row)
        {         
            $datos = $row->cantidad;     
        }
        $re = $datos + $co;
        print_r($re);
        $this->updatelote($re,$res);
        return $re; 
    }

    function updatelote($re,$res)
    {
        $sql = "UPDATE tbl_lote 
            SET tbl_lote.cantidad = $re 
            WHERE tbl_lote.loteid = $res
            ";
        $query = $this->db->query($sql);
         return $query;
    }

    function traercodigoart($d)
    {
        //artId
        $sql = "SELECT artBarCode
            FROM articles
            WHERE articles.artId = $d
        ";
        $query = $this->db->query($sql);
        if($query->result() ){
            foreach ($query->result() as $row){     
                $datos = $row->artBarCode;//artDescription;     
            } 
        return $datos;
        }
        else 
        {
            return 0;
        }
    }

    function insert_lote($data3)
    {
        $userdata            = $this->session->userdata('user_data');
        $data3['id_empresa'] = $userdata[0]['id_empresa'];
        
        $query               = $this->db->insert("tbl_lote",$data3);
        return $query;
    }







    function getsolicitante(){
    	$query= $this->db->get_where('solicitud_reparacion');
    		if($query->num_rows()>0){
       	 	return $query->result();
        }
        else{
        	return false;
        }
    }

    function getlotecant($id){
    	$sql="SELECT  tbl_lote.cantidad
    	FROM tbl_lote
    	WHERE tbl_lote.depositoid=$id AND tbl_lote.lotestado='AC'
    	";
    	$query= $this->db->query($sql);
    	/*if($query->num_rows()>0){
    	   	 	return $query->result();
    	    }
    	    else{
    	    	return false;
    	    }*/
    	$i=0;
                   foreach ($query->result_array() as $row)
                   {   
                       
                       $datos[$i]= $row['cantidad'];
                       $i++;
                   }
    	    return $datos;
    }

    function lote($val,$co,$d){
    	if ($val!=0) {
    	 	$cant= $this->lotecantidad($val); //obtengo la cantidad segun el lote
    	 	print_r($cant);
    	}
    	if ($cant >=$co) {
    		$res=$cant - $co;
    	}	
    	else {
    		echo  "No hay insumos suficientes"; 
    		//$res=$co - $cant;
    	}	
    	$datos3 = array(	        			
    		'cantidad'=>$res
    	);		        	
    	print_r($datos3);	        	
    	$this->update_tbllote($val,$datos3);
    }

    function lotecantidad($v){
    	$sql="SELECT  tbl_lote.cantidad
    			FROM tbl_lote
    			WHERE tbl_lote.loteid=$v";
    	$query= $this->db->query($sql);
      	foreach ($query->result() as $row){     
            $datos= $row->cantidad;   
        }
        return $datos;
    }

    function update_tbllote($id,$data3){
        $this->db->where('loteid', $id);
        $query = $this->db->update("tbl_lote",$data3);
        return $query;
    }

}
