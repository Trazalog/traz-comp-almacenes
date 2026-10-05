<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ajustestocks extends CI_Model {
    function __construct(){

      parent::__construct();
   }

   function ajusteList($limit, $search, $offset, $order_column = 'ajus_id', $order_dir = 'ASC'){
      $order_column = $order_column ? $order_column : 'ajus_id';
      $order_dir = $order_dir ? $order_dir : 'ASC';
      $url = REST_ALM.'/getajustes/'.empresa().'/'.$limit.'/'.$offset.'/'.$order_column.'/'.$order_dir.'/'.$search;
      $aux = $this->rest->callAPI("GET",$url);
      $aux =json_decode($aux["data"]);
      log_message('DEBUG', 'Ajustestocks/getajustes (datos)-> '.json_encode($aux));
      return $aux->ajustes->ajuste;
   }


   function guardarAjustes($data)
   {
			$data = array(
				'ajuste' => array(
					'empr_id' => strval(empresa()),
					'usuario_app' => userNick(),
					'justificacion' => $data['justificacion'],
					'tipo_ajuste' => $data['tipoajuste']
					)
			);

			log_message('DEBUG', 'Ajustestocks/guardarAjuste (datos)-> '.json_encode($data));
			$resource = '/stock/ajuste';
			$url = REST_ALM.$resource;
			$array = $this->rest->callAPI("POST", $url, $data);
			$data = json_decode($array['data']);
			$id = $data->GeneratedKeys->Entry[0]->ID;

			return $id;
	 }


   function guardarDetalleAjustes($ajus_id, $detalle)
   {
      $ajuste_detalles = array();

      foreach ($detalle as $item) {
         $cantidad = $item['cantidad'];
         $tipo_ent_sal = isset($item['tipo_ent_sal']) ? $item['tipo_ent_sal'] : '';

         // Si es SALIDA aseguramos que la cantidad vaya en negativo
         if ($tipo_ent_sal == 'SALIDA') {
            $cantNum = floatval($cantidad);
            $cantidad = strval(-abs($cantNum));
         }
         
         $ajuste_detalles[] = array(
            'ajus_id' => $ajus_id,
            'lote_id' => $item['lote_id'],
            'cantidad' => $cantidad,
            'tipo_ajuste' => $item['tipo_ajuste']
         );
      }

      $dato = array(
         'ajuste_detalles' => array(
            'ajuste_detalle' => $ajuste_detalles
         )
      );

      log_message('DEBUG', 'Ajustestocks/guardarDetalleAjustes (datos)-> '.json_encode($dato));
      $resource = '/stock/ajuste/detalle_batch_req';
      $url = REST_ALM.$resource;
      $array = $this->rest->callAPI("POST", $url, $dato);
      return json_decode($array['status']);
   }


   /**
	* Trae datos del ajuste por el deaj_id
	* @param 
	* @return array datos del ajuste
	*/
function getDataAjusteStock($deaj_id){
   $url = REST_ALM.'/ajuste/'.$deaj_id;
   $aux = $this->rest->callAPI("GET",$url);
   $aux =json_decode($aux["data"]);
   log_message('DEBUG', 'Ajustestocks/getDataAjusteStock (datos)-> '.json_encode($aux));
   return $aux->ajustes->ajuste;
   /* return json_decode($url); */
}
}


?>