<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tablas extends CI_Model
{
	function __construct()
	{
		parent::__construct();
    }

    public function get()
    {
        $this->db->select('tabla');
        $this->db->from('utl_tablas');
        $this->db->group_by('tabla');

        return $this->db->get()->result_array();
    }

    public function set($data)
    {   
        return $this->db->insert($data);
    }

    public function edit($data)
    {
        $this->db->where('tabla_id',$data['id']);
        return $this->db->update($data);
    }

    public function delete($id)
    {
        $this->db->where('tabla_id',$id);
        $this->db->set('estado',false);
        return $this->db->update();
    }
}