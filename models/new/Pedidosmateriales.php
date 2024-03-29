<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pedidosmateriales extends CI_Model
{
    private $tabla = 'alm.alm_pedidos_materiales';
    private $key = 'pema_id';
    private $columnas = '*';

    public function __construct()
    {
        parent::__construct();
    }

    public function getListado($ot = null)
    {
        $this->db->select('T.pema_id as id_notaPedido,T.fecha,T.ortr_id as id_ordTrabajo,T.justificacion, T.estado');
        if($ot) $this->db->select('orden_trabajo.descripcion');
        $this->db->from('alm.alm_pedidos_materiales T');
        if($ot)$this->db->join('orden_trabajo', 'T.ortr_id = orden_trabajo.id_orden','left');
        $this->db->where('T.empr_id', empresa());
        if($ot)  $this->db->where('orden_trabajo.id_orden', $ot);
        $query = $this->db->get();

        if ($query->num_rows() != 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function pedidoNormal($pemaId)
    {
        
        $contract = [
            'pIdPedidoMaterial' => $pemaId,
        ];

        $rsp = $this->bpm->lanzarProceso(BPM_PROCESS_ID_PEDIDOS_NORMALES, $contract);

        if (!$rsp['status']) {
            return $rsp;
        }

        $rsp['data']['pemaId'] = $pemaId ;

        $this->setCaseId($pemaId, $rsp['data']['caseId']);

        $this->setEstado($pemaId, 'Solicitado');

        return $rsp;

    }

    function listado() {
        $this->db->select($this->columnas);
        $this->db->where('eliminado', false);
        return $this->db->get($this->tabla)->result_array();
    }
    /**
	* Obtiene el detalle de un pedido de materiales a partir de ID
	* @param integer pema_id
	* @return array detalle del pedido de materiales
	*/
    public function obtener($id){
        log_message('DEBUG', '#TRAZA | TRAZ-COMP-ALMACENES | Pedidosmateriales | obtener($id) >> $id -> '. $id);
        $this->db->select('*');
        $this->db->from('alm.alm_deta_pedidos_materiales T');
        $this->db->join('alm.alm_articulos A', 'A.arti_id = T.arti_id');
        $this->db->where($this->key, $id);
        $this->db->where('T.eliminado', false);
        return $this->db->get()->result_array();
    }

    public function eliminar($id)
    {
        $this->db->where('pema_id', $id);
        $this->db->delete('alm.alm_deta_pedidos_materiales');

        $this->db->where('pema_id', $id);
        return $this->db->delete('alm.alm_pedidos_materiales');

    }

    public function getPedidoMaterialesOT($ot)
    {
        $this->db->where('empr_id', empresa());
        $this->db->where('ortr_id', $ot);
        return $this->db->get($this->tabla)->first_row();
    }

    public function crear($ot)
    {
        $pema = array(
            'fecha' => date('Y-m-d H:i:s'),
            'ortr_id' => $ot,
            'estado' => 'Creada',
            'empr_id' => empresa(),
        );
        $this->db->insert($this->tabla, $pema);
        return $this->db->insert_id();
    }

    public function setEstado($id, $estado)
    {
        $this->db->where('pema_id', $id);
        $this->db->set('estado', $estado);
        $this->db->update($this->tabla);
    }

    public function setCaseId($id, $case)
    {
        $this->db->set('case_id', $case);
        $this->db->where('pema_id', $id);
        $this->db->update('alm.alm_pedidos_materiales');
    }
		//ahora es funcion de libreria BOM Lanzar predido
    // function setCaseEmpresa($caseid){

		// 	$data = array("case_id"=>$caseid,
		// 								"empr_id"=>empresa());
		// 	$resp = $this->db->insert('core.case_empresa', $data);
		// 	return $resp;
		// }

    public function getInsumosOT($ot)
    {
        $this->db->select('artId as arti_id, cantidad');
        $this->db->where('id_empresa', empresa());
        $this->db->where('otId', $ot);
        return $this->db->get('tbl_otinsumos')->result();
    }

    public function crearPedidoOT($ot)
    {
        $result = $this->getInsumosOt($ot);

        if (!$result) {
            return false;
        }

        $pema_id = $this->crear($ot);

        foreach ($result as $o) {
            $detalle = array(
                'pema_id' => $pema_id,
                'arti_id' => $o->arti_id,
                'cantidad' => $o->cantidad,
                'resto' => $o->cantidad
            );
            $this->db->insert('alm.alm_deta_pedidos_materiales', $detalle);
        }

        return $pema_id;

    }
}