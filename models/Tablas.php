<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Tablas extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerTabla($tabla)
    {
        $url = REST_CORE . "/tablas/$tabla";
        return wso2($url);
    }

    public function obtener($id = false)
    {
        if ($id) {
            $this->db->where('tabla', $id);
        }
        $this->db->where('eliminado',false);
        return $this->db->get('alm.utl_tablas')->result();
    }
    /**
	* Obtiene los datos cargados en core.tablas por empr_id
	* @param string columna tabla a buscar
	* @return array listado de coincidencias
	*/
    public function obtenerTablaEmpr_id($tabla)
    {
        $url = REST_CORE."/tabla/$tabla/empresa/".empresa();
        return wso2($url);
    }
}
