<?php defined('BASEPATH') or exit('No direct script access allowed');

class Ajustestock extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('general/Establecimientos');
        $this->load->model(ALM.'Ajustestocks');
    }
    public function index()
    {
        $data['establecimientos'] = $this->Establecimientos->listar()->establecimientos->establecimiento;
		$data['ajustes'] = $this->Ajustestocks->ajusteList('10','','0');
        $this->load->view(ALM.'ajustestock/list',$data);
        
        //$this->load->view(ALM.'ajustestock/ajuste_stock',$data);
    }

    public function guardarAjuste()
    {
				$cabecera = $this->input->post('cabecera');
				$detalle = $this->input->post('detalle');

				//ya no se usa tipo de ajuste en la cabecera por eso lo mando vacio
				$cabecera['tipoajuste'] = '';

				// Guardar cabecera
				$rsp = $this->Ajustestocks->guardarAjustes($cabecera);

				if($rsp == null){
					log_message('ERROR','#TRAZA|TRAZ-COMP-ALMACENES|AJUSTESTOCK|guardarAjuste() >> ERROR no guardo cabecera de ajuste de stock');
					echo json_encode(['status' => false, 'data' => 'Error al guardar Cabecera Ajuste Stock...']);
					return;
				}

				$ajus_id = (string)$rsp;

				// Guardar detalle: iterar cada artículo con su tipo_ajuste
				$rsp_deta = $this->Ajustestocks->guardarDetalleAjustes($ajus_id, $detalle);
				if($rsp_deta == null){
					log_message('ERROR','#TRAZA|TRAZ-COMP-ALMACENES|AJUSTESTOCK|guardarAjuste() >> ERROR no guardo detalle de ajuste de stock');
					echo json_encode(['status' => false, 'data' => 'Error al guardar Detalle Ajuste Stock...']);
					return;
				}

        echo json_encode(['status' => true, 'data' => 'Ajuste Stock Guardado Exitosamente ...']);
    }

		/**
		* Trae listado de depositos por id de Estabelcimiento
		* @param int id establecimiento
		* @return array listado de depositos
		*/
		public function traerDepositos()
		{
			$id = $this->input->post('id_esta');
			$resp = $this->Establecimientos->obtenerDepositos($id);
			echo json_encode($resp->depositos->deposito);
		}

		public function nuevoAjuste()
		{
			$data['establecimientos'] = $this->Establecimientos->listar()->establecimientos->establecimiento;
			$this->load->view(ALM.'ajustestock/ajuste_stock', $data);
		}

		public function getAjustesList()
		{
			$draw = $this->input->post('draw');
			$start = $this->input->post('start');
			$length = $this->input->post('length');
			$search = $this->input->post('search');
			$searchValue = isset($search['value']) ? $search['value'] : '';

			$order = $this->input->post('order');
			$orderColumn = 'ajus_id';
			$orderDir = 'DESC';
			if (!empty($order)) {
				$columnIndex = intval($order[0]['column']);
				$orderDir = isset($order[0]['dir']) ? strtoupper($order[0]['dir']) : 'DESC';
				switch ($columnIndex) {
					case 1:
						$orderColumn = 'ajus_id';
						break;
					case 2:
						$orderColumn = 'fec_alta_ajuste';
						break;
					case 3:
						$orderColumn = 'establecimiento';
						break;
					case 4:
						$orderColumn = 'deposito';
						break;
				}
			}

			$list = $this->Ajustestocks->ajusteList($length, $searchValue, $start, $orderColumn, $orderDir);

			$recordsTotal = 0;
			if (!empty($list)) {
				// Normalize to array if it is a single object
				if (!is_array($list)) {
					$list = array($list);
				}

				$firstRow = (array)$list[0];
				if (isset($firstRow['total_count'])) {
					$recordsTotal = intval($firstRow['total_count']);
				} else if (isset($firstRow['total'])) {
					$recordsTotal = intval($firstRow['total']);
				} else {
					if (count($list) < $length) {
						$recordsTotal = $start + count($list);
					} else {
						$recordsTotal = $start + count($list) + 10;
					}
				}
			}

			$response = array(
				"draw" => intval($draw),
				"recordsTotal" => intval($recordsTotal),
				"recordsFiltered" => intval($recordsTotal),
				"data" => !empty($list) ? $list : array()
			);

			echo json_encode($response);
		}

		public function getDetalleAjuste()
		{
			$id = $this->input->post('id');
			$detalle = $this->Ajustestocks->getDataAjusteStock($id);
			echo json_encode($detalle);
		}

	/**
	* Trae datos del ajuste por el deaj_id
	* @param 
	* @return array datos del ajuste
	*/
	public function getDataAjuste(){
    	$deaj_id = $this->input->post('deaj_id');
		$data = $this->Ajustestocks->getDataAjusteStock($deaj_id);
		echo json_encode($data);
	}

}