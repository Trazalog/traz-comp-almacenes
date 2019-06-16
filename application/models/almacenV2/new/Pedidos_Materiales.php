<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pedidos_Materiales extends CI_Model
{
    private $tabla = 'alm_pedidos_materiales';
    private $key = 'pema_id';
    private $columnas = '*';

    public function __construct()
    {
        parent::__construct();
    }

    public function pedidoNormal($pemaId)
    {
        $this->load->library('BPMALM');

        $contract = [
            'pIdPedidoMaterial' => $pemaId
        ];

        $data = $this->bpmalm->LanzarProceso(BPM_PROCESS_ID_PEDIDOS_NORMALES, $contract);

        $this->setCaseId($pemaId, $data['case_id']);
    }

    function list() {
        $this->db->select($this->columnas);
        $this->db->where('eliminado', false);
        return $this->db->get($this->tabla)->result_array();
    }

    public function obtener($id)
    {
        $this->db->select('*');
        $this->db->from('alm_deta_pedidos_materiales T');
        $this->db->join('alm_articulos A', 'A.arti_id = T.arti_id');
        $this->db->where($this->key, $id);
        $this->db->where('T.eliminado', false);
        return $this->db->get()->result_array();
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
        $this->db->update('alm_pedidos_materiales');
    }
}
