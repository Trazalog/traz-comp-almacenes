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
    public function obtenerEstablecimientos()
    {
        $this->db->select('T.esta_id as esta_id, T.nombre as nombre');
        $this->db->from('prd.establecimientos as T');
        $this->db->where('T.empr_id', empresa());
        $this->db->where('T.eliminado',false);
        $query= $this->db->get();   

        if ($query->num_rows()!=0)
        {
            return $query->result();
        }
        else
        {
            return false;
        }
    }

    public function Get_depo_x_estaid($esta_id)
    {
        $this->db->select('D.depo_id as depo_id, D.descripcion as descripcion');
        $this->db->from('alm.alm_depositos as D');
        $this->db->where('D.esta_id', $esta_id);
        $this->db->where('D.empr_id',empresa());
        $this->db->where('D.eliminado',false);
        $query= $this->db->get();   

        if ($query->num_rows()!=0)
        {
            return $query->result();
        }
        else
        {
            return false;
        }
    }
}
