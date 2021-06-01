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
    $json = $this->Opcionesfiltros->getHistoricoArticulos($data);
    $reporte = new Historico_articulos($json);
    $reporte->run()->render();
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
}
