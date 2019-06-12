<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Entregas_Materiales extends CI_Model
{
    private $tabla = 'alm_entrega_materiales';
    private $key = 'enma_id';
    private $columnas = '*';

    public function __construct()
    {
        parent::__construct();
    }

    function listado() {
        $this->db->select('T.enma_id, T.fecha, T.comprobante, T.solicitante');
        $this->db->select('A.pema_id, A.estado, A.ortr_id');
        $this->db->from($this->tabla.' T');
        $this->db->join('alm_pedidos_materiales A','A.pema_id = T.pema_id');
        $this->db->where('T.eliminado',0);
        $this->db->where('T.empr_id',empresa());
        return $this->db->get()->result_array();
    }

    public function obtener($id)
    {
        $this->db->select($this->columnas);
        $this->db->from('');
        $this->db->where('eliminado',false);
        $this->db->where('empr_id',empresa());
        $this->db->where($this->key, $id);
        return $this->db->get($this->tabla)->row();
    } 
}
