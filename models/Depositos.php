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


    public function obtenerDepositoxEncargado($user_id , $esta_id)
    {
        $this->db->select('
        ad.depo_id as depo_id,
        ad.descripcion as descripcion,
        u.id as user_id,
        CASE 
            WHEN u.id IS NULL THEN \'\' 
            ELSE CONCAT(u.first_name, \' \', u.last_name) 
        END as responsable
    ');
    $this->db->from('alm.alm_depositos as ad');
    $this->db->join('core.encargados_depositos as ed', 'ed.depo_id = ad.depo_id', 'left');
    $this->db->join('seg.users as u', 'u.id = ed.user_id', 'left');
    $this->db->join('prd.establecimientos as e', 'e.esta_id = ad.esta_id', 'left');
    $this->db->where('u.id', $user_id);
    $this->db->where('ad.esta_id', $esta_id);

    $query = $this->db->get();
        return $query->result_array();  

    }

}
