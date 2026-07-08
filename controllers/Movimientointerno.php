<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* Representa Movimientos de Salida de Dapósitos
*
* @autor Hugo Gallardo
*/
class Movimientointerno extends CI_Controller {

	function __construct()
  {
		parent::__construct();
    // $this->load->model(ALM.'Movimientodeposalida');
		$this->load->model('general/Establecimientos');
		$this->load->model('traz-comp/Componentes');
		$this->load->model('Tablas');
		$this->load->model('Movimdeporecepcion');
		$this->load->model('Movimientosinternos');
		$this->load->model(ALM.'koolreport/Opcionesfiltros');
    	$this->load->model(ALM.'Movimdeposalida');
	}
  

    function index(){
		$data['logo'] = $this->getLogo();
		$this->load->view(ALM.'/movimientosinternos/list', $data);
    }

    /**
	* Dibuja la pantalla movimiento Recepcion de deposito
	* @param 
	* @return 
	*/
    function movimientoRecepcion(){
        $data['items'] = $this->Componentes->listaArticulos();
		$estabList = $this->Establecimientos->listar();
		$data['establecimiento'] = $estabList->establecimientos->establecimiento;
		$data['depositos'] = $this->Establecimientos->obtenerDepositoPorEmp();
		$unidades = $this->Tablas->obtenerTabla('unidades_medida');
		$data['unidades'] = $unidades['data'];

		$this->load->view(ALM.'/depositos/MovimientoRecepcion', $data);
    }

    /**
	* Dibuja pantalla Salida de Deposito con stock de articulos por deposito
	* @param
	* @return view salida de depositos
	*/
	public function movimientoSalida()
	{
    	$data['items'] = $this->Componentes->listaArticulos();
		$estabList = $this->Establecimientos->listar();
		$data['establecimiento'] = $estabList->establecimientos->establecimiento;
		$data['depositos'] = $this->Establecimientos->obtenerDepositoPorEmp();
		$unidades = $this->Tablas->obtenerTabla('unidades_medida');
		$data['unidades'] = $unidades['data'];
		$this->load->view(ALM.'/depositos/MovimientoSalida', $data);
	}

	
    /**
	* Trae los movimientos internos paginados
	* @param
	* @return view movimientos internos
	*/
	public function getMovimientoInternoPaginado($empr_id = null, $user_id = null, $search = null, $lmit = null, $offset = null){
		$data = $this->session->userdata();
		$user_id = $data['id'];        
		$empr_id = empresa();

		$search = $this->input->post('search');
		if (is_array($search)) {
			$search = isset($search['value']) ? $search['value'] : "";
		}
		if (empty($search)) {
			$search = "";
		}

		$lmit = $this->input->post('lmit');
		if (empty($lmit)) {
			$lmit = $this->input->post('length');
		}
		if (empty($lmit)) {
			$lmit = 10;
		}

		$offset = $this->input->post('offset');
		if (empty($offset)) {
			$offset = $this->input->post('start');
		}
		if (empty($offset)) {
			$offset = 0;
		}

		$order = $this->input->post('order');
		$columnMap = [
			1 => 'num_comprobante',
			2 => 'fec_alta',
			3 => 'nombre_depo_origen',
			4 => 'nombre_depo_destino',
			5 => 'estado'
		];
		$orderColumn = 'num_comprobante';
		$orderDir = 'DESC';

		if (!empty($order)) {
			$index = (int)$order[0]['column'];

			if (isset($columnMap[$index])) {
				$orderColumn = $columnMap[$index];
			}

			$orderDir = strtoupper($order[0]['dir']);
		}

		$resultado = $this->Movimientosinternos->getMovimientosPaginados($empr_id, $user_id, $search, $lmit, $offset, $orderColumn, $orderDir);
		
		$this->load->helper('traz-prod-trazasoft/admin');

		if (!empty($resultado)) {
			foreach ($resultado as &$row) {
				if (isset($row->estado)) {
					$estado_normalizado = trim(strtoupper($row->estado));
					$estado_normalizado = str_replace('_', ' ', $estado_normalizado);
					
					$color = '';
					$texto = $estado_normalizado;
					switch ($estado_normalizado) {
						
						case 'EN CURSO':
							$texto = 'En Curso';
							$color = 'green';
							break;
						
						case 'RECIBIDO':
							$texto = 'Recibido';
							$color = 'blue';
							break;
							
						default:
							$texto = $row->estado; // Fallback al estado original si no coincide
							$color = 'default';
							break;
					}
					

					/* funcion bolita de admin_helper */
					if (function_exists('bolita')) {
						$row->estado = bolita($texto, $color);
					} else {
						$row->estado = "<span class='badge bg-$color estado'>$texto </span>";
					}
				}
			}
		}

		$recordsTotal = 0;
		if (!empty($resultado)) {
			$firstRow = (array)$resultado[0];
			$recordsTotal = isset($firstRow['total_count']) ? intval($firstRow['total_count']) : count($resultado);
		}

		$json_data = array(
			"draw"            => intval($this->input->post('draw')),
			"recordsTotal"    => intval($recordsTotal),
			"recordsFiltered" => intval($recordsTotal),
			"data"            => !empty($resultado) ? $resultado : array()
		);
		echo json_encode($json_data);
	}

	 /**
	* Trae datos de la empresa para el remito
	* @param 
	* @return array datos de empresa de core.tablas
	*/
	public function getDatosCabeceraRemito(){
		$data['logo'] = $this->Movimdeposalida->obtenerTablaEmpr_id('remito_logo')[0];
		$data['direccion'] = $this->Movimdeposalida->obtenerTablaEmpr_id('remito_direccion')[0];
		$data['telefono'] = $this->Movimdeposalida->obtenerTablaEmpr_id('remito_telefono')[0];
		$data['email'] = $this->Movimdeposalida->obtenerTablaEmpr_id('remito_email')[0];
		$data['texto_pie_remito'] = $this->Movimdeposalida->obtenerTablaEmpr_id('texto_pie_remito')[0];
		
		echo json_encode($data);
	}

	/**
	* Trae datos de un movimiento interno por su demi_id
	* @param 
	* @return array datos del movimiento interno
	*/
	public function getDataMovimientoInterno(){

    $demi_id = $this->input->post('demi_id');
		$data = $this->Opcionesfiltros->getDataMovimientoInterno($demi_id);
		echo json_encode($data);
	}
	
	/**
	* Obtiene los datos de movimientos internos por demi_id para reimprimir el remito
	* @param string demi_id
	* @return array listado de coincidencias
	*/
	function datosMovimientoRemito()
	{     
		$empr_id = empresa();
		$data = $this->input->post('data');
		$resp = $this->Movimdeposalida->getDatosRemito($data);
		echo json_encode($resp);
	}

	    /**
		 * Modal impresion Remito
		* @param
		* @return array con listado de articulos
		*/
		function modalReImpresion()
		{     
			$this->load->view(ALM.'depositos/modal_remito_salida');
		}

	/**
	* Trae url del logo desde core tablas y realiza la conversion
	*/
    public function getLogo()
    {
        $logo = $this->Tablas->obtenerTablaEmpr_id('logo_pdf_movimientointerno');
        if (!empty($logo['data']) && !empty($logo['data'][0]->valor)) {
            $valor = $logo['data'][0]->valor;
            // Si ya es un dataURL (base64), lo usamos directo
            if (substr($valor, 0, 5) === 'data:') {
                return $valor;
            } else {
                // Si es un path, lo convertimos a base64
                $path = $valor;
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $fileData = file_get_contents($path);
                    return 'data:image/' . $type . ';base64,' . base64_encode($fileData);
                }
            }
        }
        return '';
    }

}