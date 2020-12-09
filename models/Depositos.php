<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Depositos extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function obtener($id = false)
    {
        if ($id) {
            $this->db->where('depo_id', $id);
            return $this->db->get('alm.alm_depositos')->result();
        } else {

						$empr_id = empresa();
            $query = $this->db->get_where('alm.alm_depositos', array('empr_id' => $empr_id));
            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
        }

    }
}
