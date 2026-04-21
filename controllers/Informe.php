<?php


 defined('BASEPATH') or exit('No direct script access allowed');

class Informe extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(ALM.'Informes');
        $this->load->model('core/Valores');
								$this->load->model('general/Establecimientos');
    }
    public function loteStock()
    {
        $data = $this->Informes->loteStock();

        echo var_dump($data);
    }


        

			//* Informe entrega	de materiales *//
				public	function entregaMaterial()
				{					
						$data['establecimientos'] = $this->Establecimientos->listar()->establecimientos->establecimiento;//bien
						$data['obras'] = $this->Valores->getValor('Obras');
						$this->load->view(ALM.'informes/info_entrega_materiales', $data);
				}

				public	function cargaTabla()
				{
						$desde = $this->input->post('desde');						
						$hasta = $this->input->post('hasta');						
						$obra = $this->input->post('obra');	
						$depo = $this->input->post('depo');
						$datos['data'] = $this->Informes->entregaMateriales($desde, $hasta, $obra, $depo);
						$this->load->view(ALM.'informes/list', $datos);			
				}

}
