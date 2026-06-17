<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/modules/'.ALM."reports/historico_articulos/Historico_articulos.php";
require APPPATH . '/modules/'.ALM."reports/articulos_vencidos/Articulos_vencidos.php";
require_once('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
* - Controller general para todos los reportes del submodulo
*
* @autor Hugo Gallardo
*/
class Reportes extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model(ALM.'koolreport/Koolreport');
    $this->load->model(ALM.'koolreport/Opcionesfiltros');
		$this->load->model(ALM.'traz-comp/Componentes');
    $this->load->model(ALM.'general/Establecimientos');
    $this->load->model(ALM.'general/Tipoajustes');
    $this->load->model(ALM.'Ajustestocks');
    $this->load->model(ALM.'Movimdeposalida');
    $this->load->model('Tablas');
  }

  /**
  * Devuelve array con establecimientos por empresa
  * @param
  * @return array con listado de establecimientos
  */
  public function getEstablecimientos()
  {
    $estab = $this->Establecimientos->listar();
    $data = $estab->establecimientos->establecimiento;
    echo json_encode($data);
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

  /**
  * Trae listado de articulos
  * @param
  * @return array con listado de articulos
  */
  function cargaArticulos()
  {     
    $data['items'] = $this->Componentes->listaArticulos();
    $this->load->view(ALM.'articulo/componente',$data);
  }

	/**
	* Trae lotes de un articulo en un determinado deposito
	* @param strin art_id y depo_id
	* @return array con info de lotes encontrados
	*/
	public function traerLotes(){

		$arti_id = $this->input->post('arti_id');
		$depo_id = $this->input->post('depo_id');
		$resp = $this->Opcionesfiltros->traerLotes($arti_id, $depo_id);
		echo json_encode($resp);
	}

  /**
  * - Levanta vista reporte de Historico de articulos
  * - Recarga con datos filtrados
  * @param
  * @return view historico_articulos
  */
  function historicoArticulos(){
    $data = $this->input->post('data');
    if (empty($data)) {
      $json = array();
    } else {
      $json = $this->Opcionesfiltros->getHistoricoArticulos($data);
    }
    $reporte = new Historico_articulos($json);
    $reporte->run()->render();
  }

  /**
  * Devuelve listado de movimientos de stock paginado, filtrado y ordenado para DataTables
  * consumiendo el nuevo servicio paginado de WSO2
  * @param
  * @return json
  */
  public function getHistoricoPaginado()
  {
    $params = $this->input->post();
    
    // Preparar el array de búsqueda / filtros para getHistoricoArticulosPaginado
    $data = array(
      'desde' => !empty($params['desde']) ? $params['desde'] : '',
      'hasta' => !empty($params['hasta']) ? $params['hasta'] : '',
      'tipo_mov' => !empty($params['tipo_mov']) ? $params['tipo_mov'] : 'TODOS',
      'depo_id' => !empty($params['depo_id']) ? $params['depo_id'] : 'TODOS',
      'arti_id' => !empty($params['arti_id']) ? $params['arti_id'] : 'TODOS',
      'lote_id' => !empty($params['lote_id']) ? $params['lote_id'] : 'TODOS',
      'offset' => isset($params['start']) ? $params['start'] : '0',
      'limit' => isset($params['length']) ? $params['length'] : '10',
    );
    
    // Obtener los registros paginados desde WSO2
    $json = $this->Opcionesfiltros->getHistoricoArticulosPaginado($data);
    
    // Si no es un array o está vacío, retornar respuesta vacía
    if (!is_array($json)) {
      $json = array();
    }
    
    // Extraer total count del primer elemento utilizando COUNT(*) OVER() de la base de datos
    $recordsFiltered = 0;
    $recordsTotal = 0;
    if (!empty($json)) {
      $firstRow = (array)$json[0];
      $recordsFiltered = isset($firstRow['total_count']) ? intval($firstRow['total_count']) : count($json);
      $recordsTotal = $recordsFiltered; // Al ser filtrado desde la DB, usamos el total filtrado como total
    }
    
    // Mapear y formatear los registros
    $formattedData = array();
    foreach ($json as $row) {
      $row = (array)$row;
      
      // Aplicar reglas de negocio para formateo de cantidad
      $cantidad = floatval(isset($row['cantidad']) ? $row['cantidad'] : 0);
      $tipo_mov = isset($row['tipo_mov']) ? trim($row['tipo_mov']) : '';
      
      if ($tipo_mov === 'MOV.SALIDA') {
        $cantidad_formateada = '-'.number_format(abs($cantidad), 2, ',', '');
      } else {
        $cantidad_formateada = number_format($cantidad, 2, ',', '');
      }
      
      // Formateo de fecha (dd-mm-yyyy)
      $fecha_formateada = '';
      if (!empty($row['fec_alta_formatted'])) {
        $aux = explode("T", $row['fec_alta_formatted']);
        $fecha_formateada = date("d-m-Y", strtotime($aux[0]));
      } elseif (!empty($row['fec_alta'])) {
        $aux = explode("T", $row['fec_alta']);
        $fecha_formateada = date("d-m-Y", strtotime($aux[0]));
      }
      
      // Generar columna de acciones
      $acciones = '';
      $referencia = isset($row['referencia']) ? $row['referencia'] : '';
      if ($tipo_mov === 'MOV.SALIDA') {
        $acciones = '<i class="fa fa-search" style="cursor: pointer; margin: 3px;" title="Ver detalle movimiento" onclick="clipMovimiento(' . $referencia . ')"></i>'
                  .'<i class="fa fa-print" style="cursor: pointer; margin: 3px;" title="Imprimir Remito" onclick="modalReimpresion(this)"></i>';
      } elseif ($tipo_mov === 'AJUSTE') {
        $acciones = '<i class="fa fa-search" style="cursor: pointer; margin: 3px;" title="Ver Ajuste Stock" onclick="verAjuste(' . $referencia . ')"></i>';
      } elseif ($tipo_mov === 'MOV.ENTRADA') {
        $acciones = '<i class="fa fa-search" style="cursor: pointer; margin: 3px;" title="Ver detalle movimiento" onclick="clipMovimiento(' . $referencia . ')"></i>'
                    . '<i class="fa fa-print" style="cursor: pointer; margin: 3px;" title="Imprimir Remito" onclick="modalReimpresion(this)"></i>';
      }
      elseif ($tipo_mov === 'INGRESO') {
        $acciones = '<i class="fa fa-search" style="cursor: pointer; margin: 3px;" title="Ver Ingreso" onclick="verIngreso(' . $referencia . ')"></i>';
      }
      elseif ($tipo_mov === 'EGRESO') {
        $acciones = '<i class="fa fa-search" style="cursor: pointer; margin: 3px;" title="Ver Egreso" onclick="verEgreso(' . $referencia . ')"></i>';
      }
      elseif ($tipo_mov === 'INGRESOPRODUCTO') {
        $acciones = '<i class="fa fa-search" style="cursor: pointer; margin: 3px;" title="Ver Egreso" onclick="verEgresoConsumoMP(' . $referencia . ')"></i>';
      }
      elseif ($tipo_mov === 'ETAPAPRODINGRESO') {
        $acciones = '<i class="fa fa-search" style="cursor: pointer; margin: 3px;" title="Ver Egreso" onclick="verEgresoConsumoMP(' . $referencia . ')"></i>';
      }
      elseif ($tipo_mov === 'ETAPAPRODEGRESO') {
        $acciones = '<i class="fa fa-search" style="cursor: pointer; margin: 3px;" title="Ver Egreso" onclick="verSalidaEtapaProd(' . $referencia . ')"></i>';
      }
      
      $formattedData[] = array(
        'acciones' => $acciones,
        'referencia' => $referencia,
        'codigo' => isset($row['codigo']) ? $row['codigo'] : '',
        'descripcion' => isset($row['descripcion']) ? $row['descripcion'] : '',
        'lote' => isset($row['lote']) ? $row['lote'] : '',
        'cantidad' => $cantidad_formateada,
        'deposito' => isset($row['deposito']) ? $row['deposito'] : '',
        'fecha' => $fecha_formateada,
        'tipo_mov' => $tipo_mov,
        // Guardamos el objeto completo en data-json por compatibilidad
        'DT_RowAttr' => array(
          'data-json' => json_encode($row)
        )
      );
    }
    
    $response = array(
      "draw" => isset($params['draw']) ? intval($params['draw']) : 1,
      "recordsTotal" => $recordsTotal,
      "recordsFiltered" => $recordsFiltered,
      "data" => $formattedData
    );
    
    echo json_encode($response);
  }


  /**
  * - Trae tipos de articulos
  * @param
  * @return array con tipos de articulos
  */
  function getTiposArticulos()
  {
    $this->load->model(ALM.'general/Tablas');
    $resp = $this->Tablas->getTabla('tipo_articulo');
    echo json_encode($resp);
  }

  /**
	* Carga la view de trazabilidad de lotes (módulo producción) para mostrar
	* en el modal de Salida de Etapa Productiva del histórico de artículos.
	* @param GET batch_id - ID del batch a trazar
	* @return view trazabilidad del módulo traz-prod-trazasoft
	*/
	public function verSalidaEtapaProd(){
    log_message('DEBUG', '#TRAZA | TRAZ-COMP-ALMACENES | REPORTES | verSalidaEtapaProd() | INICIO');
    $batch_id = $this->input->get('batch_id');
    $data['batch_id'] = $batch_id;
    // Usamos path absoluto ya que PRD no está definida en este módulo
    $this->load->view(PRD.'produccion/lotes/trazabilidad', $data);
	}

  /**
  * - Levanta vista reporte de Articulos Vencidos
  * - Recarga vista con datos filtrados
  * @param
  * @return view articulos Vencidos
  */
  function articulosVencidos()
  {     
    log_message('INFO','#TRAZA|REPORTES|articulosVencidos() >> ');
    $data = $this->input->post('data');
    $json = $this->Opcionesfiltros->getArticulosVencidos($data);
    $reporte = new Articulos_vencidos($json);
    $reporte->run()->render();
  }

   /**
  * - Genera Archivo Excel con la data filtrada en la vista
  * - Descarga el excel automaticamente
  * @param
  * @return view articulos Vencidos
  */
  public function excelTest() {

    // $data = $this->input->post("data");
    $data['desde'] = $this->input->get('fec1');
    $data['hasta'] = $this->input->get('fec2');
    $data['depo_id'] = $this->input->get('depo');
    $data['arti_id'] = $this->input->get('arti');
    $data['tipo'] = $this->input->get('tpoArt');
    $data['estado'] = $this->input->get('estado'); //FALTA EN LA CONSULTA

    log_message('DEBUG','#TRAZA|REPORTES|excelTest() >> '. json_encode($data));
    $json = $this->Opcionesfiltros->getArticulosVencidos($data);
    

    $spreadsheet = new Spreadsheet(); // Creo la instancia de Spreadsheet
    $sheet = $spreadsheet->getActiveSheet(); // Me posiciono en la hoja activa

    //Formateo del Excel con la data de la consulta
    //Formateo titulo
    $sheet->setCellValue('A1', 'Reporte de Artículos Vencidos');
    $sheet->getStyle('A1')->getFont()->setSize(20);
    $sheet->getStyle('A1')->getFont()->setBold(true);
    $sheet->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('B4C6E7');

    
    //Formateo Headers tabla y rellenado
    $sheet->getStyle('A3:G3')->getFont()->setBold(true);
    $sheet->setCellValue('A3', "Tipo de Artículo");
    $sheet->setCellValue('B3', "Código");
    $sheet->setCellValue('C3', "Descripción");
    $sheet->setCellValue('D3', "Cantidad Stock");
    $sheet->setCellValue('E3', "Fecha Vencimiento");
    $sheet->setCellValue('F3', "Déposito");
    $sheet->setCellValue('G3', "Estado");
    $sheet->getColumnDimension('A')->setWidth(17);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);
    $sheet->getColumnDimension('E')->setAutoSize(true);
    $sheet->getColumnDimension('F')->setAutoSize(true);
    $sheet->getColumnDimension('G')->setAutoSize(true);
    $sheet->getStyle('A3:G3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');

    //Relleno la Tabla
    $i = 4;
    foreach ($json as $key => $value) {
      $sheet->setCellValue('A'.$i, $value->desc_tipo_articulo);
      $sheet->setCellValue('B'.$i, $value->barcode);
      $sheet->setCellValue('C'.$i, $value->descripcion);
      $sheet->setCellValue('D'.$i, $value->cantidad);
      $sheet->setCellValue('E'.$i, $value->fec_vencimiento);
      $sheet->setCellValue('F'.$i, $value->deposito);
      $sheet->setCellValue('G'.$i, $value->estado);
      $i++; 
    }
        
    $writer = new Xlsx($spreadsheet); // instancio Xlsx
 
    $filename = 'Reporte_Articulos_Vencidos'; // Nombre del archivo con el cual sera descargado
 
    header('Content-Type: application/vnd.ms-excel'); // generamos las cabeceras para que el navegador interprete de que tipo de archivo se trata
    header('Content-Disposition: attachment;filename="'. $filename."_". date('d-m-Y') .'.xlsx"'); 
    header('Cache-Control: max-age=0');
        
    $writer->save('php://output');	// descargamos el excel generado
  }
  
   /**
  * - Genera Archivo Excel con la data filtrada en la vista
  * - Descarga el excel automaticamente
  * - NOTA: Se genera de esta manera debido a que no se puede descargar un archivo
  * - directamente como respuesta de un ajax porque infringe politicas de seguridad
  * @param
  * @return view Historico Articulos
  */
  public function exportarExcelHistorico() {

    $data['desde'] = $this->input->get('fec1');
    $data['hasta'] = $this->input->get('fec2');
    $data['depo_id'] = $this->input->get('depo');
    $data['arti_id'] = $this->input->get('arti');
    $data['tipo_mov'] = $this->input->get('tpoMov');
    $data['lote_id'] = $this->input->get('lote');

    $json = $this->Opcionesfiltros->getHistoricoArticulos($data);
    
    $spreadsheet = new Spreadsheet(); // Creo la instancia de Spreadsheet
    $sheet = $spreadsheet->getActiveSheet(); // Me posiciono en la hoja activa

    //Formateo del Excel con la data de la consulta
    //Formateo titulo
    $sheet->setCellValue('A1', 'Reporte de Artículos Vencidos');
    $sheet->getStyle('A1')->getFont()->setSize(20);
    $sheet->getStyle('A1')->getFont()->setBold(true);
    $sheet->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('B4C6E7');

    
    //Formateo Headers tabla y rellenado
    $sheet->getStyle('A3:I3')->getFont()->setBold(true);
    $sheet->setCellValue('A3', "Referencia");
    $sheet->setCellValue('B3', "Código Artículo");
    $sheet->setCellValue('C3', "Descripción");
    $sheet->setCellValue('D3', "Lote");
    $sheet->setCellValue('E3', "Cantidad");
    $sheet->setCellValue('F3', "Stock");
    $sheet->setCellValue('G3', "Depósito");
    $sheet->setCellValue('H3', "Fecha");
    $sheet->setCellValue('I3', "Tipo Movimiento");
    $sheet->getColumnDimension('A')->setWidth(13);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);
    $sheet->getColumnDimension('E')->setAutoSize(true);
    $sheet->getColumnDimension('F')->setAutoSize(true);
    $sheet->getColumnDimension('G')->setAutoSize(true);
    $sheet->getColumnDimension('H')->setAutoSize(true);
    $sheet->getColumnDimension('I')->setAutoSize(true);
    $sheet->getStyle('A3:I3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('D9D9D9');

    //Relleno la Tabla
    $i = 4;
    foreach ($json as $key => $value) {

      $aux = explode("T",$value->fec_alta);
      $fecha = date("d-m-Y",strtotime($aux[0]));
      
      $sheet->setCellValue('A'.$i, $value->referencia);
      $sheet->setCellValue('B'.$i, $value->codigo);
      $sheet->setCellValue('C'.$i, $value->descripcion);
      $sheet->setCellValue('D'.$i, $value->lote);
      $sheet->setCellValue('E'.$i, $value->cantidad);
      $sheet->setCellValue('F'.$i, $value->stock_actual);
      $sheet->setCellValue('G'.$i, $value->deposito);  
      $sheet->setCellValue('H'.$i, $fecha);
      $sheet->setCellValue('I'.$i, $value->tipo_mov);
      $i++; 
    }
        
    $writer = new Xlsx($spreadsheet); // instancio Xlsx
 
    $filename = 'Reporte_Histórico_Artículos'; // Nombre del archivo con el cual sera descargado
 
    header('Content-Type: application/vnd.ms-excel'); // generamos las cabeceras para que el navegador interprete de que tipo de archivo se trata
    header('Content-Disposition: attachment;filename="'. $filename."_". date('d-m-Y') .'.xlsx"'); 
    header('Cache-Control: max-age=0');
        
    $writer->save('php://output');	// descargamos el excel generado
  }

    /**
  * Trae listado de articulos
  * @param
  * @return array con listado de articulos
  */
  function modalReImpresion()
  {     
    $this->load->view(ALM.'depositos/modal_remito_salida');
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
	* Trae datos del ajuste por el deaj_id
	* @param 
	* @return array datos del ajuste
	*/
	public function getDataAjuste(){

    $deaj_id = $this->input->post('deaj_id');
		$data = $this->Ajustestocks->getDataAjusteStock($deaj_id);
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


}
