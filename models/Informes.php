<?php

use Google\Service\Analytics\Resource\Data;

 if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Informes extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function loteStock()
    {
        $this->db->select('codigo, arti_id, sum(cantidad) as stock');
        $this->db->group_by('codigo, arti_id');
        $this->db->where('empr_id', empresa());
        return $this->db->get('alm.alm_lotes')->result();
    }
				//* Informe entrega	de materiales *//
    public function entregaMateriales($fec_desde, $fec_hasta, $obra = null, $depo = null)
    {
						log_message('DEBUG', '#TRAZ-COMP-ALMACENES | Informes | entregaMateriales($fec_desde,$fec_hasta,$obra=null)');
						$resource = "/entrega/fec_desde/".urlencode($fec_desde)."/fec_hasta/".urlencode($fec_hasta)."/obra/".urlencode($obra)."/depo/".urlencode($depo)."/empr/".urlencode(empresa());
						$url = REST_ALM . $resource;
						$datos = wso2($url);
						return $datos['data'];
				}

}

