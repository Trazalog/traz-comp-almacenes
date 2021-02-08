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
        
        $this->load->view(ALM.'ajustestock/ajuste_stock',$data);
    }

    public function guardarAjuste()
    {
				$data = $this->input->post('data');

				$cabecera = $data;
				unset($cabecera['ajus_id']);
				unset($cabecera['tipo_ent_sal']);
				$rsp = $this->Ajustestocks->guardarAjustes($cabecera);

				if($rsp == null){
					log_message('ERROR','#TRAZA|TRAZ-COMP-ALMACENES|AJUSTESTOCK|guardarAjuste() >> ERROR no guardo cabecera de ajuste de stock');
					echo json_encode(['status' => false, 'data' => 'Error al guardar Cabecera Ajuste Stock...']);
					return;
				}

				$data['ajus_id'] = (string)$rsp;
				$rsp_deta = $this->Ajustestocks->guardarDetalleAjustes($data);
				if($rsp == null){
					log_message('ERROR','#TRAZA|TRAZ-COMP-ALMACENES|AJUSTESTOCK|guardarAjuste() >> ERROR no guardo detalle de ajuste de stock');
					echo json_encode(['status' => false, 'data' => 'Error al guardar Detalle Ajuste Stock...']);
					return;
				}

        echo json_encode(['status' => false, 'data' => 'Ajuste Stock Guardado Exitosamente ...']);
    }

}