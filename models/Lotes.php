<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Lotes extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getList() // Ok

    { 
        $empresa = empresa();
        $query_getList = " Select
        alm.alm_articulos.descripcion as artdescription,
                 alm.alm_articulos.barcode as artbarcode,
                 alm.alm_articulos.tipo as arttype,
                 alm.alm_lotes.fec_alta as fecha_nueva,
                 alm.alm_articulos.unidad_medida as un_medida,
                 alm.alm_lotes.*,
                 alm.alm_depositos.depo_id,
                 alm.alm_depositos.descripcion as depositodescrip,
                 prd.recipientes.reci_id,
                 prd.recipientes.nombre as nom_reci
    
       from alm.alm_articulos
    
       JOIN alm.alm_lotes ON alm_lotes.arti_id = alm_articulos.arti_id AND alm_lotes.cantidad != 0
       JOIN alm.alm_depositos ON alm_lotes.depo_id = alm_depositos.depo_id
       LEFT JOIN prd.lotes ON alm.alm_lotes.batch_id = prd.lotes.batch_id
       LEFT JOIN prd.recipientes ON prd.lotes.reci_id = prd.recipientes.reci_id
    
       WHERE alm.alm_articulos.empr_id =$empresa
       ";

    //     $query_getList_OLD = " Select
    //     alm.alm_articulos.descripcion as artdescription,
    //              alm.alm_articulos.barcode as artbarcode,
    //              alm.alm_articulos.tipo as arttype,
    //              alm.alm_lotes.fec_alta as fecha_nueva,
    //              alm.alm_articulos.unidad_medida as un_medida,
    //              alm.alm_lotes.*,
    //              alm.alm_depositos.depo_id,
    //              alm.alm_depositos.descripcion as depositodescrip,
    //              prd.recipientes.reci_id,
    //              prd.recipientes.nombre as nom_reci
    
    //    from alm.alm_articulos
    
    //    LEFT JOIN alm.alm_lotes ON alm_lotes.arti_id = alm_articulos.arti_id

    //    LEFT JOIN alm.alm_depositos ON alm_lotes.depo_id = alm_depositos.depo_id
    //    LEFT JOIN prd.lotes ON alm.alm_lotes.batch_id = prd.lotes.batch_id
    //    LEFT JOIN prd.recipientes ON prd.lotes.reci_id = prd.recipientes.reci_id
    
    //    WHERE alm.alm_articulos.empr_id =$empresa
    //    ";
     
    //   $this->db->where('alm.alm_lotes.empr_id', empresa());
        $query = $this->db->query($query_getList);

        return $query->result_array();


    }

    public function getPuntoPedido()
    {
        // OBTENER CANTIDADES RESERVADAS
        $this->db->select('arti_id, COALESCE(sum(resto),0) as cant_reservada');
        $this->db->from('alm.alm_deta_pedidos_materiales');
        $this->db->join('alm.alm_pedidos_materiales', 'alm.alm_deta_pedidos_materiales.pema_id = alm.alm_pedidos_materiales.pema_id');
        $this->db->where('estado!=', 'Entregado');
        $this->db->where('estado!=', 'Rechazado');
        $this->db->where('estado!=', 'Cancelado');
        $this->db->where('estado!=', 'Finalizado Ent. Parcial');       
        $this->db->where('alm.alm_pedidos_materiales.empr_id', empresa());
        $this->db->group_by('arti_id');
        $C = '(' . $this->db->get_compiled_select() . ') as "C"';
        $this->db->select('ART.arti_id, ART.barcode, ART.descripcion, ART.punto_pedido, COALESCE(sum("LOTE".cantidad), 0) as cantidad_stock, COALESCE(sum("LOTE".cantidad),0)-COALESCE(cant_reservada,0) as cantidad_disponible');
        $this->db->from('alm.alm_articulos as ART');
        $this->db->join('alm.alm_lotes as LOTE', 'LOTE.arti_id = ART.arti_id');
        $this->db->join($C, 'C.arti_id = ART.arti_id', 'left');
        $this->db->group_by('ART.arti_id, C.cant_reservada');
        $this->db->where('ART.empr_id',empresa());

        $sql = '(' . $this->db->get_compiled_select() . ') as "AUX"';
        $this->db->where('AUX.cantidad_disponible < AUX.punto_pedido');
        $this->db->from($sql);
        $data = $this->db->get()->result_array();
        // var_dump($data);die;
        return $data;
    }

    public function getMotion($data = null)
    {
        if ($data == null) {
            return false;
        } else {
            $action = $data['act'];
            $idStk = $data['id'];

            $data = array();

            //Datos del movimiento
            $query = $this->db->get_where('admstock', array('stkId' => $idStk));
            if ($query->num_rows() != 0) {
                $c = $query->result_array();
                $data['motion'] = $c[0];
            } else {
                $stk = array();
                $stk['stkCant'] = '';
                $stk['stkMotive'] = '';
                $data['motion'] = $stk;
            }

            //Readonly
            $readonly = false;
            if ($action == 'Del' && $action == 'View') {
                $readonly = true;
            }
            $data['read'] = $readonly;

            //Products
            $query = $this->db->get_where('admproducts', array('prodStatus' => 'AC'));
            if ($query->num_rows() != 0) {
                $data['products'] = $query->result_array();
            }

            return $data;
        }
    }

    public function setMotion($data = null)
    {
        if ($data == null) {
            return false;
        } else {
            $id = $data['id'];
            $act = $data['act'];
            $prodId = $data['prodId'];
            $cant = $data['cant'];
            $motive = $data['motive'];

            $data = array(
                'prodId' => $prodId,
                'stkCant' => $cant,
                'stkMotive' => $motive,
                'usrId' => 0, //!HARDCODE
                'stkDate' => date('Y-m-d H:i:s'),
            );

            switch ($act) {
                case 'Add':
                    //Agregar Movimiento
                    if ($this->db->insert('admstock', $data) == false) {
                        return false;
                    }
                    break;

            }
            return true;

        }
    }

    public function crearLoteSistema($data)
    {
        $aux = array(
            'codigo' => $data['barcode'],
            'arti_id' => $data['arti_id'],
            'prov_id' => 0,
            'depo_id' => 1,
            'cantidad' => 0,
            'estado_id' => 1,
            'empr_id' => empresa(),
        );
        return $this->db->insert('alm.alm_lotes', $aux);
    }

    public function verificarExistencia($arti, $lote, $depo)
    {
        $this->db->where('codigo', $lote);
        $this->db->where('depo_id', $depo);
        $this->db->where('arti_id', $arti);
        $this->db->where('empr_id', empresa());
        return $this->db->get('alm.alm_lotes')->num_rows() > 0 ? 1 : 0;
    }

    public function extraerCantidad($data)
    {
        $url = REST_ALM . '/extraer_cantidad_lote';
        $rsp = file_get_contents($url, false, http('POST', ['post_extraer_cantidad_lote' => $data]));
        $rsp = rsp($http_response_header, false, $rsp);
        return $rsp;
    }

    public function crear($data)
    {
        $url = REST_ALM . '/lotes/movimiento_stock';
        $rsp = file_get_contents($url, false, http('POST', ['post_lotes_movimiento_stock' => $data]));
        $rsp = rsp($http_response_header, false, $rsp);
        return $rsp;
    }

    public function crearBatch($data)
    {
        $batch_req = [];
        foreach ($data as $o) {
            $aux["lote_id"] = strval($o->id);
            $aux["arti_id"] = strval($o->producto);
            $aux["prov_id"] = strval($o->prov_id);
            $aux["batch_id_padre"] = strval($o->batch_id_padre);
            $aux["cantidad"] = strval($o->cantidad);
            $aux["cantidad_padre"] = strval($o->stock);
            $aux["num_orden_prod"] = "";
            $aux["reci_id"] = strval($o->reci_id);
            $aux["etap_id"] = strval(ETAPA_DEPOSITO);
            $aux["usuario_app"] = userNick();
            $aux["empr_id"] = strval(empresa());
            $aux["forzar_agregar"] = isset($o->forzar_agregar) ? $o->forzar_agregar : "FALSE";
            $aux["fec_vencimiento"] = FEC_VEN;
            $aux["recu_id"] = "0";
            $aux["tipo_recurso"] = "";
            $aux['batch_id'] = "0";
            $aux['planificado'] = "";


            $batch_req['_post_lote_batch_req']['_post_lote'][] = $aux;
        }

        log_message('DEBUG', "#TRAZA | #TRAZ-COMP-ALMACENES | Lotes | crearBatch()  batch_req: >> " . json_encode($batch_req));

        $url = REST_PRD_LOTE . '/lote/list_batch_req';//
        $rsp = $this->rest->callApi('POST', $url, $batch_req);
        wso2Msj($rsp);
        log_message('DEBUG','#TRAZA | #TRAZ-COMP-ALMACENES | Lotes | crearBatch() RSP: >>'.json_encode($rsp));
        return $rsp;
    }
    
    /**
        *  Llama a 1 stored procedure( cambiar_recipiente() ), que hace el cambio del recipiente origen al de destino creado en Recipientes->crear()
        * @param array $data con los lotes cargados en pantalla
        * @return array respuesta del servicio
    */
    public function guardarCargaCamion($data)
    {
        $batch_req = [];
        foreach ($data as $o) {

            $aux["batch_id_origen"] = strval($o->batch_id);
            $aux["reci_id"] = strval($o->reci_id);
            $aux["cantidad"] = strval($o->cantidad);
            $aux["etap_id_deposito"] = strval(ETAPA_DEPOSITO);
            $aux["empre_id"] = strval(empresa());
            $aux["usuario_app"] = userNick();
            $aux["forzar_agregar"] = "false";
            
            $batch_req['_post_lote_recipiente_cambiar_batch_req']['_post_lote_recipiente_cambiar'][] = $aux;
        }
        
        log_message('DEBUG', "#TRAZA | #TRAZ-COMP-ALMACENES | Lotes | guardarCargaCamion()  batch_req: >> " . json_encode($batch_req));

        $url = REST_PRD . '/lote/recipiente/cambiar_batch_req';
        $rsp = $this->rest->callApi('POST', $url, $batch_req);
        wso2Msj($rsp);
        if (!$rsp['status']) {
            return $rsp;
        }

        $rsp['data'] = json_decode($rsp['data'])->respuesta->resultado;
        return $rsp;

    }

    /**
        *  Llama a 1 stored procedure( crear_lote_v2() ), que genera un lote y finaliza los lotes padres en la cadena productiva
        * @param array $data de lote cargados en pantalla
        * @return int batch_id
    */
    public function crearLote($data){

        $aux['lote_id'] = $data->lote_id_origen;
        $aux['arti_id'] = $data->arti_id_origen;
        $aux['prov_id'] = strval($data->prov_id);
        $aux["batch_id_padre"] = strval($data->batch_id);
        $aux["batch_id"] = strval($data->batch_id);// 0
        $aux["cantidad"] = strval($data->cantidad_origen);
        $aux['cantidad_padre'] = "0";
        $aux['num_orden_prod'] = strval($data->orden_prod);
        $aux["reci_id"] = strval($data->reci_id);
        $aux["etap_id"] = strval(ETAPA_TRANSPORTE);
        $aux["usuario_app"] = userNick();
        $aux["empr_id"] = strval(empresa());
        $aux["recu_id"] = "0";
        $aux["forzar_agregar"] = "true";
        $aux['fec_vencimiento'] = FEC_VEN;
        $aux["tipo_recurso"] = "";
        $aux['planificado'] = "false";
        
        $post['_post_lote'] = $aux;
        
        log_message('DEBUG', "#TRAZA | #TRAZ-COMP-ALMACENES | Lotes | crearLote()  post: >> " . json_encode($post));

        $url = REST_PRD_LOTE . '/lote';
        $rsp = $this->rest->callApi('POST', $url, $post);

        wso2Msj($rsp);

        if (!$rsp['status']) {
            return $rsp;
        }

        $rsp['data'] = json_decode($rsp['data'])->respuesta->resultado;

        return $rsp;
    }

    public function listarPorArticulos($idarticulo,$iddeposito){

        log_message('DEBUG', '#MODEL > listarPorArticulos | ID_ARTICULO: ' .$idarticulo);
        $url = REST_ALM.'/deposito/'.$iddeposito.'/articulo/'.$idarticulo.'/lote/list';
		$array = $this->rest->callAPI("GET",$url);
		$resp =  json_decode($array['data']);
		return $resp;
    }

    public function filtrarListado($data){
        $empresa = empresa();
    //Articulo con stock 0
    if($data['stock0'] !='' && $data['stock0'] != NULL ){


    $query_getList = " Select
    alm.alm_articulos.descripcion as artdescription,
            alm.alm_articulos.barcode as artbarcode,
            alm.alm_articulos.tipo as arttype,
            alm.alm_articulos.fec_alta as fecha_nueva,
            alm.alm_articulos.unidad_medida as un_medida,
            alm.alm_lotes.*,
            alm.alm_depositos.depo_id,
            alm.alm_depositos.descripcion as depositodescrip,
            prd.recipientes.reci_id,
            prd.recipientes.nombre as nom_reci

    from alm.alm_articulos

    JOIN alm.alm_lotes ON alm_lotes.arti_id = alm_articulos.arti_id
    JOIN alm.alm_depositos ON alm_lotes.depo_id = alm_depositos.depo_id
    LEFT JOIN prd.lotes ON alm.alm_lotes.batch_id = prd.lotes.batch_id
    LEFT JOIN prd.recipientes ON prd.lotes.reci_id = prd.recipientes.reci_id
    WHERE  alm.alm_articulos.empr_id =$empresa AND  
    cantidad ='0'
    OR cantidad ISNULL AND alm.alm_articulos.empr_id =$empresa";


    $query = $this->db->query($query_getList);

    return $query->result_array();

    }else if($data['stock0'] == ''){
        
        $this->db->select('
        
            alm.alm_articulos.tipo as arttype,
            alm.alm_articulos.descripcion as artdescription,
            alm.alm_articulos.barcode as artbarcode,
            alm.alm_articulos.unidad_medida as un_medida,
            alm.alm_articulos.fec_alta as fecha_nueva,        
            alm.alm_lotes.*,
            COALESCE(alm.alm_lotes.cantidad, 0) as cantidad,
            alm.alm_depositos.depo_id,
            alm.alm_depositos.descripcion as depositodescrip,
            prd.recipientes.reci_id,
            prd.recipientes.nombre as nom_reci
    
        ');

        $this->db->from('alm.alm_articulos');
        $this->db->join('alm.alm_lotes', 'alm.alm_lotes.arti_id = alm.alm_articulos.arti_id');
        $this->db->join('alm.alm_depositos', ' alm.alm_lotes.depo_id = alm.alm_depositos.depo_id');
        $this->db->join('prd.lotes', ' alm.alm_lotes.batch_id = prd.lotes.batch_id', 'left');
        $this->db->join('prd.recipientes', ' prd.lotes.reci_id = prd.recipientes.reci_id', 'left');
        $this->db->where('alm.alm_lotes.empr_id', $empresa);
        $this->db->where('alm.alm_lotes.cantidad', '!=0');

        
        //FILTRADO
        //Nombre Articulo
        if($data['artDescrip'] !='' && $data['artDescrip'] != NULL ){
            $this->db->where('alm.alm_articulos.descripcion',$data['artDescrip']);
        }
        //Codigo del Articulo
        if($data['artBarCode'] !='' && $data['artBarCode'] != NULL && $data['artBarCode'] != "undefined" ){
            $this->db->where('alm.alm_articulos.barcode',$data['artBarCode']);
        }
        //Tipo Articulo
        if($data['artType'] !='' && $data['artType'] != NULL && $data['artType'] != "null" ){
            $this->db->where('alm.alm_articulos.tipo',$data['artType']);
        }
        //Fecha Creación
        if($data['fec_alta'] !='' && $data['fec_alta'] != NULL ){
            $this->db->where('DATE(alm.alm_articulos.fec_alta)',$data['fec_alta']);
        }
        //Nombre del Deposito
    if($data['depositodescrip'] !='' && $data['depositodescrip'] != NULL && $data['depositodescrip'] != "null" ){
            $this->db->where('alm.alm_depositos.depo_id',$data['depositodescrip']);
        }
        //Nombre Recipiente
        if($data['nom_reci'] !='' && $data['nom_reci'] != NULL && $data['nom_reci'] != "null" ){
            $this->db->where('prd.recipientes.reci_id',$data['nombre']);
        }
        //Nombre Articulo
        if($data['establecimiento'] !='' && $data['establecimiento'] != NULL && $data['establecimiento'] != "null" ){
            $this->db->where('alm.alm_depositos.esta_id',$data['establecimiento']);
        }
    }
            

        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }
}
