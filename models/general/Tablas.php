<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tablas extends CI_Model
{
	function __construct()
	{
		parent::__construct();
    }

		/**
		* - Devuelve valores de tabla segun parametro reecibido
		* @param string tabl_id
		* @return array con valores devueltos
		*/
		function getTabla($tabla)
		{
			log_message('DEBUG','#TRAZA|TRAZ-COMP-ALMACENES|GENERAL|TABLAS|getTabla() $tabla: >> '.json_encode($tabla));

			$aux = $this->rest->callAPI("GET",REST_CORE."/tablas/".$tabla);
			$aux =json_decode($aux["data"]);
			return $aux->tablas->tabla;

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