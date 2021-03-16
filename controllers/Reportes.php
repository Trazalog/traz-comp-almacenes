<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/modules/'.ALM."reports/historico_articulos/Historico_articulos.php";

/**
* - Controller general
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

}
