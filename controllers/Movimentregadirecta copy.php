<?php defined('BASEPATH') or exit('No direct script access allowed');

class Movimentregadirecta extends CI_Controller
{

			private $permission = "Add-Edit-Del-View";

			public function __construct()
			{

							parent::__construct();
							$this->load->model(ALM . 'Notapedidos');
							$this->load->model(ALM . 'Articulos');
							$this->load->model(ALM.'Depositos');
			}
			/**
		* Carga el listado de los pedidos de materiales
		* @param 
		* @return view listado pedidos de materiales
		*/
			// public function index(){
			// 		log_message('DEBUG', '#TRAZA | #TRAZ-COMP-ALMACENES | Movimentregadirecta | index()');
			// 		$this->load->model('traz-comp/Componentes');
			// 		#COMPONENTE ARTICULOS
			// 		$data['items'] = $this->Componentes->listaArticulos();
			// 		$data['lang'] = lang_get('spanish', 'Ejecutar OT');

			// 		$data['list'] = $this->Notapedidos->notaPedidos_List();
			// 		$data['permission'] = $this->permission;
			// 		$this->load->view(ALM . 'entregadirecta/list', $data);
			// }

			public function index($ot = null)
    {
        $this->load->model('traz-comp/Componentes');

        #COMPONENTE ARTICULOS
        $data['items'] = $this->Componentes->listaArticulos();
        $data['lang'] = lang_get('spanish', 'Ejecutar OT');
        $data['establecimientos'] = $this->Depositos->obtenerEstablecimientos();

        if ($ot) {
            $info = new stdClass();
            $info->ortr_id = $ot;
            $data['info'] = $info;
        }
        $data['hecho'] = false;
        $this->load->view(ALM . 'entregadirecta/generar_entrega', $data);

    }
}