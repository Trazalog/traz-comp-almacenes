<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Tablas extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function obtener($id = false)
    {
        return $this->obtenerTabla($id);
    }

    public function obtenerTabla($tabla)
    {
        // cambio de metodo agergando empresa y unificando esta funcion como unica funcion para tablas, eliminando el uso de utl_tablas
        // nota: debe migrarse los valores de utl_tablas a core.tablas
        log_message('DEBUG','#TRAZA | ALMACENES | obtenerTabla() >> tabla: |'.$tabla);
        $url = REST_CORE . "/tabla/$tabla/empresa/".empresa();
        return wso2($url);
    }

}
