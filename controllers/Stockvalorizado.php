<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stockvalorizado extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(ALM . 'Lotes');
        $this->load->model(ALM. 'general/Establecimientos');
        $this->load->model('traz-prod-trazasoft/general/Recipientes');
        $this->load->model('traz-comp/Componentes');
        $this->load->model('StockValorizados');
        $this->load->model('Tablas');

        // si esta vencida la sesion redirige al login
		$data = $this->session->userdata();
		// log_message('DEBUG','#Main/login | '.json_encode($data));
		if(!$data['email']){
			log_message('DEBUG','#TRAZA|DASH|CONSTRUCT|ERROR  >> Sesion Expirada!!!');
			redirect(DNATO.'main/login');
		}
    }
    /**
	* Levanta pantalla de STOCK en almacenes
	* @param 
	* @return view
	*/
    public function index(){
        log_message('DEBUG','#TRAZA | #TRAZ-COMP-ALMACENES | Stockvalorizado | index()');        
        #COMPONENTE ARTICULOS
        $data['items'] = $this->Componentes->listaArticulos();
      //  $data['list'] = $this->Lotes->getList();
        $data['permission'] = "Add-Edit-Del-View";
        // $data['tipoArticulos'] = $this->Tablas->obtenerTabla('tipo_articulo')['data'];
        $data['tipoArticulos'] = $this->Tablas->obtenerTablaEmpr_id('tipo_articulo')['data'];
        $data['establecimientos'] = $this->Establecimientos->listar()->establecimientos->establecimiento;
        $this->load->view(ALM . 'stockvalorizado/list', $data);
       
    }
    /**
	* Carga la tabla en pantalla de STOCK
	* @param 
	* @return view
	*/
    public function Listar_tabla(){
        log_message('DEBUG','#TRAZA | #TRAZ-COMP-ALMACENES | Stockvalorizado | Listar_tabla()');
	    $data['list'] = $this->StockValorizados->getStockValorizado();
        $this->load->view(ALM . 'stockvalorizado/table_list', $data);
    }

	/**
	* Levanta pantalla de punto de pedido en almacenes
	* @param 
	* @return view
	*/
    public function puntoPedList(){
        log_message('DEBUG','#TRAZA | #TRAZ-COMP-ALMACENES | Lote | puntoPedList()');
        $data['list'] = $this->Lotes->getPuntoPedido();
        $data['permission'] = "Add-Edit-Del-View";
        $this->load->view(ALM . 'lotes/list_punto_ped', $data);
    }

    public function getMotion()
    {
        $data['data'] = $this->Stocks->getMotion($this->input->post());
        $response['html'] = $this->load->view(ALM . 'stock/view_', $data, true);

        echo json_encode($response);
    }

    public function setMotion()
    {
        $data = $this->Stocks->setMotion($this->input->post());
        if ($data == false) {
            echo json_encode(false);
        } else {
            echo json_encode(true);
        }
    }

    public function verificarExistencia()
    {
        $lote = $this->input->post('lote');
        $depo = $this->input->post('depo');
        $arti = $this->input->post('arti');
        echo $this->Lotes->verificarExistencia($arti, $lote, $depo);
    }

    public function listarPorArticulo(){

        $idarticulo = $this->input->get("arti_id");
        $iddeposito = $this->input->get("depo_id");
        $datos = $this->Lotes->listarPorArticulos($idarticulo,$iddeposito)->lotes->lote;
        echo json_encode($datos);
    }

    /**
	* Filtra listado Movimientos de Stock
	* @param array con los filtros seleccionados
	* @return array listado con listado filtrado
	*/

    public function filtrarListado(){
        log_message('DEBUG','#TRAZA | TRAZ-COMP-ALMACENES | Stockvalorizado | filtrarListado()');
        
        //Deposito
        if(!empty($this->input->get('depositodescrip'))){
            $data['depositodescrip'] = $this->input->get('depositodescrip');
        }
        //Fecha Creacion DESDE
        if(!empty($this->input->get('fec_desde'))){
            $data['fec_desde'] = $this->input->get('fec_desde');
        }
        //Fecha Creacion HASTA
        if(!empty($this->input->get('fec_hasta'))){
            $data['fec_hasta'] = $this->input->get('fec_hasta');
        }        
        //Establecimiento
        if(!empty($this->input->get('establecimiento'))){
            $data['establecimiento'] = $this->input->get('establecimiento');
        }
        //Tipo deposito
        if(!empty($this->input->get('tipo_deposito'))){
            $data['tipo_deposito'] = $this->input->get('tipo_deposito');
        }        
        
        $data['list'] = $this->StockValorizados->filtrarListado($data);

        log_message('DEBUG','#TRAZA | TRAZ-COMP-ALMACENES | Stockvalorizado | filtrarListado()'. json_encode($data['list']));

        
      $this->load->view(ALM . 'stockvalorizado/table_list', $data);
    }

    public function getDepositos(){
        $esta_id = $this->input->post('esta_id');
        $response = $this->Establecimientos->obtenerDepositos($esta_id);
        log_message('DEBUG','#TRAZA | STOCK | getDepositos() $response >> '.json_encode($response));
        echo json_encode($response->depositos->deposito);
    }

    public function getRecipientesPorEstablecimiento(){
        $esta_id = $this->input->post('esta_id');
        $response = $this->Recipientes->listarPorEstablecimiento($esta_id);
        log_message('DEBUG','#TRAZA | STOCK | getRecipientesPorEstablecimiento() $response >> '.json_encode($response));
        echo json_encode($response->recipientes->recipiente);

    }
}
