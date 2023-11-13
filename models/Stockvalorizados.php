<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class StockValorizados extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
	* Obtiene el reporte de stock por empr_id
	* @param 
	* @return view
	*/
    
    public function getStockValorizado(){ 
        log_message('DEBUG','#TRAZA | TRAZ-COMP-ALMACENES | StockValorizado | getStockValorizado()');
        $empresa = empresa();
 
        
        $this->db->select('aa.barcode, aa.descripcion, al.cantidad, adrm.p_pesos, adrm.p_dolar, (adrm.p_pesos * al.cantidad) as t_pesos, (adrm.p_dolar*al.cantidad) as t_dolar');
        $this->db->from('alm.alm_articulos aa');
        $this->db->join('alm.alm_lotes al', 'aa.arti_id = al.arti_id AND al.cantidad != 0');
        $this->db->join('alm.alm_deta_recepcion_materiales adrm', 'al.lote_id  = adrm.lote_id and adrm.cantidad !=0');
        $this->db->where('al.empr_id', empresa());
        $this->db->where('aa.eliminado', false);
        $this->db->where('adrm.p_pesos is not NULL', null);
        $this->db->where('adrm.p_dolar is not NULL', null);
        $this->db->group_by('adrm.p_pesos, adrm.p_dolar,aa.barcode, aa.descripcion, al.cantidad');
        $query = $this->db->get();

        if ($query->num_rows() != 0) {
            return $query->result();
        } else {
           return false;
        }
    }
   

    public function filtrarListado($data){
        log_message('DEBUG', "#TRAZA | #TRAZ-COMP-ALMACENES | Lotes | filtrarListado()  data: >> " . json_encode($data));

        $empresa = empresa();
        $empresa = empresa();
 
        
        $this->db->select('aa.barcode, aa.descripcion, al.cantidad, adrm.p_pesos, adrm.p_dolar, (adrm.p_pesos * al.cantidad) as t_pesos, (adrm.p_dolar*al.cantidad) as t_dolar');
        $this->db->from('alm.alm_articulos aa');
        $this->db->join('alm.alm_lotes al', 'aa.arti_id = al.arti_id AND al.cantidad != 0');
        $this->db->join('alm.alm_deta_recepcion_materiales adrm', 'al.lote_id  = adrm.lote_id and adrm.cantidad !=0');
        $this->db->join('alm.alm_depositos ad', ' al.depo_id = ad.depo_id');
        $this->db->where('al.empr_id', empresa());
        $this->db->where('aa.eliminado', false);
        $this->db->where('adrm.p_pesos is not NULL', null);
        $this->db->where('adrm.p_dolar is not NULL', null);
        
        
        //FILTRADO
        //Fecha Creación DESDE
        if($data['fec_desde'] !='' && $data['fec_desde'] != NULL ){
            $this->db->where('DATE(al.fec_alta) >',$data['fec_desde']);
        }
        //Fecha Creación HASTA
        if($data['fec_hasta'] !='' && $data['fec_hasta'] != NULL ){
            $this->db->where('DATE(al.fec_alta) <',$data['fec_hasta']);
        }
        //Nombre del Deposito
        if($data['depositodescrip'] !='' && $data['depositodescrip'] != NULL && $data['depositodescrip'] != "null" ){
            $this->db->where('al.depo_id',$data['depositodescrip']);
        }
        //Establecimiento
        if($data['establecimiento'] !='' && $data['establecimiento'] != NULL && $data['establecimiento'] != "null" ){
            $this->db->where('ad.esta_id',$data['establecimiento']);
        }
        $this->db->group_by('adrm.p_pesos, adrm.p_dolar,aa.barcode, aa.descripcion, al.cantidad');
        $query = $this->db->get();

        if (!empty($query->num_rows()) && $query->num_rows() != 0) {
            return $query->result();
        } else {
            return false;
        }
    }
}
