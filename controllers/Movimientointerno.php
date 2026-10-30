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
		$this->load->model('core/Valores');
		$this->load->model('Tablas');
		$this->load->model('Movimdeporecepcion');
		$this->load->model('Movimientosinternos');
		$this->load->model(ALM.'koolreport/Opcionesfiltros');
    	$this->load->model(ALM.'Movimdeposalida');
	}
  

    function index(){
		$data['logo'] = $this->getLogo();

		// Configuracion del QR de movimientos internos (core.tablas con fallback)
		$qr_pixel = $this->Valores->getTablaValor('configuraciones', 'qr_movimientointerno_pixel');
		$qr_level = $this->Valores->getTablaValor('configuraciones', 'qr_movimientointerno_level');
		$qr_frame = $this->Valores->getTablaValor('configuraciones', 'qr_movimientointerno_framsize');

		$data['qr_movimiento_pixel']    = (!empty($qr_pixel)) ? $qr_pixel : '7';
		$data['qr_movimiento_level']    = (!empty($qr_level)) ? $qr_level : 'L';
		$data['qr_movimiento_framsize'] = (!empty($qr_frame)) ? $qr_frame : '2';

		$data['empresa_nombre'] = $this->getNombreEmpresa();

		$this->load->view(ALM.'/movimientosinternos/list', $data);
    }

    /**
    * Devuelve la descripcion de la empresa actual para mostrarla en el modal QR.
    * @param
    * @return string
    */
    private function getNombreEmpresa()
    {
        try {
            $empr_id = empresa();
            if (empty($empr_id)) {
                return '';
            }

            $resp = $this->rest->callAPI("GET", REST_CORE . "/empresa/" . $empr_id);
            if (!empty($resp['data'])) {
                $data = json_decode($resp['data']);
                if (isset($data->empresa->descripcion)) {
                    return (string) $data->empresa->descripcion;
                }
            }
        } catch (Exception $e) {
            log_message('ERROR', '#TRAZA|TRAZ-COMP-ALMACENES|Movimientointerno|getNombreEmpresa >> ' . $e->getMessage());
        }

        return '';
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

    /**
    * Vista publica de prueba accesible al escanear el QR de un movimiento interno.
    * No valida sesion: el acceso se resuelve por el token registrado en qru.urls.
    * @param
    * @return view movimientosinternos/vista_qr_movimiento
    */
    public function vistaQrMovimiento()
    {
        $demi_id = $this->input->get('id');
        log_message('DEBUG', '#TRAZA|TRAZ-COMP-ALMACENES|Movimientointerno|vistaQrMovimiento demi_id: ' . $demi_id);

        // Sin sesion no hay empresa en session. Se puede pasar por parametro y,
        // como ultimo recurso, se usa la empresa configurada para esta funcionalidad.
        $empr_id = $this->input->get('empr');
        if (empty($empr_id)) {
            $empr_id = empresa();
        }
        if (empty($empr_id)) {
            $empr_id = '1';
        }

        $data['demi_id'] = $demi_id;
        $data['empr_id'] = $empr_id;
        $data['movimiento'] = array();

        if (!empty($demi_id)) {
            try {
                $resp = $this->Movimdeposalida->getDatosRemito($demi_id, $empr_id);
                if (is_object($resp)) {
                    $resp = array($resp);
                }
                $data['movimiento'] = is_array($resp) ? $resp : array();
            } catch (Exception $e) {
                log_message('ERROR', '#TRAZA|TRAZ-COMP-ALMACENES|Movimientointerno|vistaQrMovimiento getDatosRemito >> ' . $e->getMessage());
            }
        }

        // Cabecera de la empresa (mismos datos que usa el remito)
        $data['empresa'] = $this->getDatosEmpresaRemito($empr_id);

        // Transportistas: la tabla se relaciona con moin_id, que viene en el
        // propio registro del movimiento (no usar demi_id).
        $data['transportistas'] = array();
        if (!empty($data['movimiento']) && isset($data['movimiento'][0]->moin_id)) {
            try {
                $data['transportistas'] = $this->Movimdeposalida->getTransportistasMovimiento($data['movimiento'][0]->moin_id);
            } catch (Exception $e) {
                log_message('ERROR', '#TRAZA|TRAZ-COMP-ALMACENES|Movimientointerno|vistaQrMovimiento transportistas >> ' . $e->getMessage());
            }
        }

        $this->load->view(ALM . 'movimientosinternos/vista_qr_movimiento', $data);
    }

    /**
    * Devuelve los transportistas asociados a un movimiento interno.
    * @param POST moin_id
    * @return json
    */
    public function getTransportistasMovimiento()
    {
        $moin_id = $this->input->post('moin_id');
        $rows = array();

        if (!empty($moin_id)) {
            try {
                $rows = $this->Movimdeposalida->getTransportistasMovimiento($moin_id);
            } catch (Exception $e) {
                log_message('ERROR', '#TRAZA|TRAZ-COMP-ALMACENES|Movimientointerno|getTransportistasMovimiento >> ' . $e->getMessage());
            }
        }

        echo json_encode($rows);
    }

    /**
    * Guarda un transportista asociado a un movimiento interno.
    * @param POST moin_id, transportista
    * @return json
    */
    public function guardarTransportistaMovimiento()
    {
        $moin_id = $this->input->post('moin_id');
        $transportista = trim((string) $this->input->post('transportista'));

        if (empty($moin_id) || $transportista === '') {
            echo json_encode(array('status' => false, 'msg' => 'Datos incompletos.'));
            return;
        }

        try {
            $rsp = $this->Movimdeposalida->guardarTransportistaMovimiento($moin_id, $transportista);
            echo json_encode(array(
                'status'         => true,
                'msg'            => 'Transportista guardado.',
                'fec_alta_txt'   => date('d/m/Y H:i'),
                'data'           => $rsp
            ));
        } catch (Exception $e) {
            log_message('ERROR', '#TRAZA|TRAZ-COMP-ALMACENES|Movimientointerno|guardarTransportistaMovimiento >> ' . $e->getMessage());
            echo json_encode(array('status' => false, 'msg' => 'No se pudo guardar el transportista.'));
        }
    }

    /**
    * Trae los datos de cabecera de la empresa para el remito.
    * @param string empr_id
    * @return array
    */
    private function getDatosEmpresaRemito($empr_id)
    {
        $datos = array(
            'logo'            => '',
            'direccion'       => '',
            'telefono'        => '',
            'email'           => '',
            'texto_pie_remito' => ''
        );

        $tablas = array(
            'remito_logo'       => 'logo',
            'remito_direccion'  => 'direccion',
            'remito_telefono'   => 'telefono',
            'remito_email'      => 'email',
            'texto_pie_remito'  => 'texto_pie_remito'
        );

        foreach ($tablas as $tabla => $clave) {
            try {
                $filas = $this->Movimdeposalida->obtenerTablaEmpr_id($tabla, $empr_id);
                if (!empty($filas) && isset($filas[0]->valor)) {
                    $datos[$clave] = (string) $filas[0]->valor;
                }
            } catch (Exception $e) {
                log_message('ERROR', '#TRAZA|TRAZ-COMP-ALMACENES|Movimientointerno|getDatosEmpresaRemito ' . $tabla . ' >> ' . $e->getMessage());
            }
        }

        // Si el logo es un path, se convierte a data URL para que sea visible
        // en la vista publica (donde no hay acceso al filesystem del servidor).
        if ($datos['logo'] !== '' && substr($datos['logo'], 0, 5) !== 'data:') {
            $path = $datos['logo'];
            if (file_exists($path)) {
                $tipo = pathinfo($path, PATHINFO_EXTENSION);
                $contenido = @file_get_contents($path);
                if ($contenido !== false) {
                    $datos['logo'] = 'data:image/' . $tipo . ';base64,' . base64_encode($contenido);
                }
            } elseif (!preg_match('#^(https?:)?//#', $path)) {
                // Path relativo al root del sitio: se resuelve con base_url()
                $datos['logo'] = base_url($path);
            }
        }

        return $datos;
    }

}