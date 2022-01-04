<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tipoajuste extends CI_Controller {

	function __construct() 
    {
		parent::__construct();
		$this->load->model('general/Tipoajustes');
    }
    /**
      * Obtiene listado de de los posibles tipos de ajsute para stock
      * @param 
      * @return array listado causas para ajuste de stock
    */
    function obtenerAjuste()
    {
        log_message('DEBUG','#TRAZA | TRAZ-COMP-ALMACENES | Tipoajuste | obtenerAjuste()');
        $datos = $this->Tipoajustes->obtenerAjustes();
        
        echo json_encode($datos);
    }
}