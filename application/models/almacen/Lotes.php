<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lotes extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}
	
	function Lotes_List() // Ok
	{
		//$userdata  = $this->session->userdata('user_data');
        //$empresaId = $userdata[0]['id_empresa'];
		$this->db->select('tbl_lote.*, articles.artDescription, articles.artBarCode,tbl_lote.cantidad,abmdeposito.depositodescrip,tbl_lote.lotestado');
		$this->db->from('tbl_lote');
		$this->db->join('articles', 'tbl_lote.artId = articles.artId');
		$this->db->join('abmdeposito', ' tbl_lote.depositoId = abmdeposito.depositoid');
		//$this->db->where('tbl_lote.id_empresa', $empresaId);
		$query = $this->db->get();
		if ($query->num_rows()!=0)
		{
			return $query->result_array();	
		}
		else
		{	
			return false;
		}
	}

	function puntoPedListado()
	{
		//$userdata  = $this->session->userdata('user_data');
        //$empresaId = $userdata[0]['id_empresa'];
		$this->db->select('tbl_lote.*, 
			articles.artDescription, articles.artBarCode, articles.punto_pedido, tbl_lote.cantidad, abmdeposito.depositodescrip, tbl_lote.lotestado');
		$this->db->from('tbl_lote');
		$this->db->join('articles', 'tbl_lote.artId = articles.artId');
		$this->db->join('abmdeposito', ' tbl_lote.depositoId = abmdeposito.depositoid');
		$this->db->where('articles.punto_pedido >= tbl_lote.cantidad');
		//$this->db->where('tbl_lote.id_empresa', $empresaId);
		$query = $this->db->get();

		if ($query->num_rows()!=0)
		{
			return $query->result_array();	
		}
		else
		{	
			return false;
		}
	}
	
	function getMotion($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$action = $data['act'];
			$idStk = $data['id'];

			$data = array();

			//Datos del movimiento
			$query= $this->db->get_where('admstock',array('stkId'=>$idStk));
			if ($query->num_rows() != 0)
			{
				$c = $query->result_array();
				$data['motion'] = $c[0];
			} else {
				$stk = array();
				$stk['stkCant'] = '';
				$stk['stkMotive'] = '';
				$data['motion'] = $stk;
			}

			//Readonly
			$readonly = false;
			if($action == 'Del' || $action == 'View'){
				$readonly = true;
			}
			$data['read'] = $readonly;

			//Products
			$query= $this->db->get_where('admproducts',array('prodStatus'=>'AC'));
			if ($query->num_rows() != 0)
			{
			 	$data['products'] = $query->result_array();	
			}
			
			return $data;
		}
	}
	
	function setMotion($data = null){
		if($data == null)
		{
			return false;
		}
		else
		{
			$id = $data['id'];
            $act = $data['act'];
            $prodId = $data['prodId'];
            $cant = $data['cant'];
            $motive = $data['motive'];

           // $userdata = $this->session->userdata('user_data');
			//$usrId = $userdata[0]['usrId'];

			$data = array(
				   'prodId' => $prodId,
				   'stkCant' => $cant,
				   'stkMotive' => $motive,
				   'usrId' => 0, //!HARDCODE
				   'stkDate' => date('Y-m-d H:i:s')
				);

			switch($act){
				case 'Add':
					//Agregar Movimiento 
					if($this->db->insert('admstock', $data) == false) {
						return false;
					} 
					break;
				
				/*	
				case 'Del':
				 	//Eliminar Articulo
				 	if($this->db->delete('admproducts', array('prodId'=>$id)) == false) {
				 		return false;
				 	}
				 	break;
				*/	
			}
			return true;

		}
	}
	
}
