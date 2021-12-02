<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Tablas extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /** Obtiene los valores para una lista desplegable de core.tablas
     *  @author rruiz
     */
    public function obtenerTabla($tabla)
    {
        // cambio de metodo agergando empresa y unificando esta funcion como unica funcion para tablas, eliminando el uso de utl_tablas
        // nota: debe migrarse los valores de utl_tablas a core.tablas
        log_message('DEBUG','#TRAZA | ALMACENES | obtenerTabla() >> tabla: |'.$tabla);
        $url = REST_CORE . "/tabla/$tabla/empresa/".empresa();
        return wso2($url);
    }


    /** Obtiene los valores para una lista desplegable de core.tablas si es una tabla de sistema
     *  @author rruiz
     */
    public function obtenerSysTabla($tabla)
    {
        log_message('DEBUG','#TRAZA | ALMACENES | obtenerTabla() >> tabla: |'.$tabla);
        $url = REST_CORE . "/tabla/$tabla/empresa/";
        return wso2($url);
    }
}
