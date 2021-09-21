<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lote extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(ALM . 'Lotes');
        $this->load->model(ALM. 'general/Establecimientos');
        $this->load->model('traz-prod-trazasoft/general/Recipientes');
        $this->load->model('traz-comp/Componentes');
        $this->load->model('Tablas');
    }

    public function index()
    {
        #COMPONENTE ARTICULOS
        $data['items'] = $this->Componentes->listaArticulos();
        $data['list'] = $this->Lotes->getList();
        $data['permission'] = "Add-Edit-Del-View";
        $data['tipoArticulos'] = $this->Tablas->obtenerTabla('tipo_articulo')['data'];
        $data['establecimientos'] = $this->Establecimientos->listar()->establecimientos->establecimiento;
        $this->load->view(ALM . 'lotes/list', $data);
    }

    public function puntoPedList()
    {
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

    public function filtrarListado()
    {
        //Recipiente
        if(!empty($this->input->post('nom_reci'))){
            $data['nom_reci'] = $this->input->post('nom_reci');
        }
        //Deposito
        if(!empty($this->input->post('depositodescrip'))){
            $data['depositodescrip'] = $this->input->post('depositodescrip');
        }
        //Descripcion Articulo
        if(!empty($this->input->post('artDescrip'))){
            $data['artDescrip'] = $this->input->post('artDescrip');
        }
        //Codigo Articulo
        if(!empty($this->input->post('artBarCode'))){
            $data['artBarCode'] = $this->input->post('artBarCode');
        }
        //Tipo Articulo
        if(!empty($this->input->post('artType'))){
            $data['artType'] = $this->input->post('artType');
        }
        //Fecha Creacion
        if(!empty($this->input->post('fec_alta'))){
            $data['fec_alta'] = $this->input->post('fec_alta');
        }
     
         //Arcticulos con stock 0
         if(!empty($this->input->post('stock0'))){
            $data['stock0'] = $this->input->post('stock0');
        }
        
        $response = $this->Lotes->filtrarListado($data);
        log_message('DEBUG','#TRAZA | STOCK | filtrarListado() $response >> '.json_encode($response));
        
        echo json_encode($response);
        // var_dump($data['list']);
        // $this->load->view(ALM . 'lotes/filtered_list', $data);
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
