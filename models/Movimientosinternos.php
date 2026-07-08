<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Movimientosinternos extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

    public function getMovimientosPaginados($empr_id, $user_id, $search,$lmit,$offset, $orderColumn, $orderDir){
	    
        $url = REST_ALM."/getmovimientointerno/paginado/".$empr_id."/".$user_id."/".$lmit."/".$offset."/".urlencode($search)."/".$orderColumn."/".$orderDir;
		$aux = $this->rest->callAPI("GET",$url);
		$aux = json_decode($aux['data']);
		return $aux->movimientos->movimiento;
    }
}