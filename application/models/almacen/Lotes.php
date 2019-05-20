<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lotes extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}
	
	function list() // Ok
	{
		//$userdata  = $this->session->userdata('user_data');
		$empresaId = 1;//$userdata[0]['id_empresa'];
		
		$this->db->select('alm_lotes.*, alm_articulos.descripcion as artDescription, alm_articulos.barcode as artBarCode,alm_lotes.cantidad,alm_depositos.descripcion as depositodescrip,C.valor as lotestado');
		$this->db->from('alm_lotes');
		$this->db->join('alm_articulos', 'alm_lotes.arti_id = alm_articulos.arti_id');
		$this->db->join('alm_depositos', ' alm_lotes.depo_id = alm_depositos.depo_id');
		$this->db->join('utl_tablas C','alm_lotes.estado_id = C.tabl_id');
		$this->db->where('alm_lotes.empr_id', $empresaId);

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
		$this->db->select('alm_lotes.*,alm_articulos.descripcion as artDescription, alm_articulos.barcode as artBarCode, alm_articulos.punto_pedido, alm_lotes.cantidad, alm_depositos.descripcion as depositodescrip, T.valor as lotestado');
		$this->db->from('alm_lotes');
		$this->db->join('alm_articulos', 'alm_lotes.arti_id = alm_articulos.arti_id');
		$this->db->join('alm_depositos', ' alm_lotes.depo_id = alm_depositos.depo_id');
		$this->db->join('utl_tablas T','T.tabl_id = alm_lotes.estado_id');
		$this->db->where('alm_articulos.punto_pedido>= alm_lotes.cantidad');
		//$this->db->where('alm_lotes.empr_id', $empresaId);
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

	public function crearLoteSistema($data)
	{
		$empr = 1;
		$aux  = array(
			'codigo'=> $data['barcode'],
			'arti_id'=>$data['arti_id'],
			'prov_id'=>0,
			'depo_id'=>1,
			'cantidad'=>0,
			'estado_id'=>1,
			'empr_id'=>$empr
		);
		return $this->db->insert('alm_lotes',$aux);
	}
	
}
