<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Articulo extends CI_Controller {

	function __construct()
    {
		parent::__construct();

		$this->load->model(ALM.'Articulos');
		$this->load->model(ALM.'Lotes');
		$this->load->model('Tablas');

	}

	// Muestra listado de articulos
	public function index()
	{
		$data['list'] = $this->Articulos->getList();
		$data['unidades_medida'] = $this->Tablas->obtenerTabla('unidades_medida')['data'];
		$data['tipoArticulos'] = $this->Tablas->obtenerTabla('tipo_articulo')['data'];
		$this->load->view(ALM.'articulo/list', $data);
	}

	public function obtener($opt = false){
		$url =  REST_ALM . '/articulos/'.empresa();
		$data = wso2($url);
		if($data['status']){
			if($opt) $data['data'] = selectBusquedaAvanzada(false, false, $data['data'], 'arti_id', 'barcode',array('descripcion'));
			echo json_encode($data);
		}
	}

	public function guardar()
	{
		$data = $this->input->post();
		$unidadMedidas = $this->Tablas->obtenerTablaEmpr_id('unidades_medida')['data'];

		foreach($unidadMedidas as $um){
			log_message('DEBUG',"#TRAZA | TRAZ-COMP-ALMACENES | Articulo | guardar() um: ".$um->valor );
			if($data["unme_id"]  === $um->tabl_id ){
				$data["unidad_medida"] = $um->valor;
				break;
			}			
		}

		log_message('DEBUG',"#TRAZA | TRAZ-COMP-ALMACENES | Articulo | guardar() Undidad_Medida: ".json_encode($unidadMedidas) );		
		log_message('DEBUG',"#TRAZA | TRAZ-COMP-ALMACENES | Articulo | guardar() Data: ".json_encode($data) );
		log_message('DEBUG',"#TRAZA | TRAZ-COMP-ALMACENES | Articulo | guardar() Data: ".$data["unme_id"] );
		
		unset($data['arti_id']);
		$data = $this->Articulos->guardar($data);
		echo json_encode($data);
	}

	public function editar()
	{
		$data = $this->input->post();
		$unidadMedidas = $this->Tablas->obtenerTablaEmpr_id('unidades_medida')['data'];

		foreach($unidadMedidas as $um){
			log_message('DEBUG',"#TRAZA | TRAZ-COMP-ALMACENES | Articulo | editar() um: ".$um->valor );
			if($data["unme_id"]  === $um->tabl_id ){
				$data["unidad_medida"] = $um->valor;
				break;
			}			
		}

		$data['punto_pedido'] = empty($data['punto_pedido']) ? 0 : $data['punto_pedido']; 
		
		log_message('DEBUG',"#TRAZA | TRAZ-COMP-ALMACENES | Articulo | editar() Data: ".json_encode($data) );

		$rsp = $this->Articulos->editar($data);
		echo json_encode($rsp);
	}

	public function getdatosart() // Ok
	{
		$art = $this->Articulos->getUnidadesMedidas();
		if($art)
		{	
			$arre = array();
	        foreach ($art as $row ) 
	        {   
	           $arre[] = $row;
	        }
			echo json_encode($arre);
		}
		else echo "nada";
	}

	//
	public function getArticle() // Ok
	{
		$data['data']   = $this->Articulos->getArticle($this->input->post());
		$response['html'] = $this->load->view(ALM.'articulo/view_', $data, true);

		echo json_encode($response);
	}

	//
	public function getpencil() // Ok
	{
		$id     = $this->input->post('idartic');
		$result = $this->Articulos->getpencil($id);
		echo json_encode($result);
	}

	//
	public function editar_art()  // Ok
	{
		$datos  = $this->input->post('data');
		$id     = $this->input->post('ida');
		$result = $this->Articulos->update_editar($datos,$id);
		print_r(json_encode($result));	
	}


	public function setArticle(){
		$data = $this->input->post();
		$id = $this->Articulos->setArticle($data);


		if($id  == false)
		{
			echo json_encode(false);
		}
		else
		{
			
			echo json_encode(true);	
		}
	}

//devuelve valor false si no tiene stock.
//sino devuelve cantidad en stock.
//esta funcion es para verificar si un articulo esta con stock.
	public function verificar_articulo()
	{
		$idarticulo = $_POST['idelim'];
		$result     = $this->Articulos->verificarStock($idarticulo)[0]['cantidad'];
	//	print_r($result);

			if($result  == NULL)
			{
				echo json_encode($result);
			}
			else
			{
				echo json_encode($result);	
			}
	}

	public function baja_articulo()
	{
		$idarticulo = $_POST['idelim'];
		$result     = $this->Articulos->eliminar($idarticulo);
		print_r($result);
	}

	public function searchByCode() {
		$data = $this->Articulos->searchByCode($this->input->post());
		if($data  == false)
		{
			echo json_encode(false);
		}
		else
		{
			echo json_encode($data);	
		}
	} 

	public function searchByAll() {
		$data = $this->Articulos->searchByAll($this->input->post());
		if($data  == false)
		{
			echo json_encode(false);
		}
		else
		{
			echo json_encode($data);	
		}
	}

	public function getestado(){

		$response = $this->Articulos->getestados();

		echo json_encode($response);
		  
	}

	public function getLotes($id = null) //fleiva
	{
		if(!$id){ $this->load->view('no_encontrado');return;}

		$data['articulo'] = $this->Articulos->get($id);

		$data['list'] = $this->Articulos->getLotes($id);
			
		$this->load->view(ALM.'proceso/tareas/componentes/tabla_lote_deposito', $data);
	}

	/**
	* Recibe codigo de Artículo, para validar si ya existe para una empresa
	* @param string código Artículo
	* @return array respuesta del servicio
	*/
	public function validarArticulo(){
		log_message('INFO','#TRAZA | #TRAZ-COMP-ALMACENES | Articulo | validarArticulo()');
	
		$barcode = $this->input->post('barcode');
		$resp = $this->Articulos->validarArticulo($barcode);
			
		echo json_encode($resp);
	}
	/**
	* Genera el listado de los articulos paginado
	* @param integer;integer;string start donde comienza el listado; length cantidad de registros; search cadena a buscar
	* @return array listado paginado y la cantidad
	*/
	public function paginado(){//server side processing

		$start = $this->input->post('start');
		$length = $this->input->post('length');
		$search = $this->input->post('search')['value'];

		$r = $this->Articulos->articulosPaginados($start,$length,$search);

		$resultado =$r['datos'];
		$totalDatos = $r['numDataTotal'];

		$datos = !empty($resultado) ? $resultado->result_array() : array();
		$datosPagina = !empty($resultado) ? $resultado->num_rows() : array();

		$json_data = array(
			"draw" 				=> intval($this->input->post('draw')),
			"recordsTotal"  	=> intval($datosPagina),
			"recordsFiltered"	=> intval($totalDatos),
			"data" 				=> $datos
		);
		echo json_encode($json_data);
	}
}