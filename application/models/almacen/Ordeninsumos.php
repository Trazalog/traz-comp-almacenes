<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ordeninsumos extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getList()
    {
        //$userdata  = $this->session->userdata('user_data');
        $empresaId = 1; //$userdata[0]['id_empresa'];

        $this->db->select('A.enma_id as id_orden, B.ortr_id as id_ot, A.fecha, A.solicitante, A.comprobante');
        $this->db->from('alm_entrega_materiales A');
        $this->db->join('alm_pedidos_materiales B', 'B.pema_id = A.pema_id');
        $this->db->where('A.empr_id', $empresaId);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data['openBox'] = 1;
            $data['data'] = $query->result_array();
            return $data;
        } else { $data['openBox'] = 1;
            return $data;
        }
    }

    public function getcodigo()
    {
        //$userdata  = $this->session->userdata('user_data');
        $empresaId = 1; //$userdata[0]['id_empresa'];
        $this->db->select('alm_articulos.arti_id as artId ,alm_lotes.lote_id as loteid,alm_articulos.barcode as artBarCode, alm_articulos.descripcion as artDescription');

        $this->db->from('alm_articulos');
        $this->db->join('alm_lotes ', 'alm_lotes.arti_id = alm_articulos.arti_id');
        $this->db->join('utl_tablas', 'utl_tablas.tabl_id = alm_lotes.estado_id');
        $this->db->where('alm_lotes.arti_id = alm_articulos.arti_id');
        $this->db->where('alm_articulos.empr_id', $empresaId);
        $this->db->group_by('alm_lotes.arti_id');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function getsolicitante()
    {
        $userdata = $this->session->userdata('user_data');
        $empresaId = $userdata[0]['id_empresa'];

        $this->db->select('id_solicitud, solicitante');
        $this->db->from('solicitud_reparacion');
        $this->db->where('id_empresa', $empresaId);
        $this->db->group_by('solicitante');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    // devuelve las ot de la empresa y que esten curso o asignadas
    public function getOT()
    {

        $userdata = $this->session->userdata('user_data');
        $empresaId = $userdata[0]['id_empresa'];

        $this->db->select('orden_trabajo.id_orden, orden_trabajo.descripcion');
        $this->db->from('orden_trabajo');
        $this->db->where('orden_trabajo.id_empresa', $empresaId); //de la empresa
        $this->db->where('orden_trabajo.estado', 'C'); //que estan en curso
        $this->db->or_where('orden_trabajo.estado', 'AS'); //que asignadas

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function getdescrip($data = null)
    {
        if ($data == null) {
            return false;
        } else {
            $id = $data['arti_id'];
            //Datos del usuario
            $query = $this->db->get_where('alm_articulos', array('arti_id' => $id));
            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
        }
    }

    public function insert_orden($data)
    {
        //$userdata           = $this->session->userdata('user_data');
        $data['empr_id'] = 1; //$userdata[0]['id_empresa'];
        $query = $this->db->insert("alm_entrega_materiales", $data);
        return $query;
    }

    public function insert_entrega_materiales($info, $detalle)
    {
        //$userdata           = $this->session->userdata('user_data');
        $data['empr_id'] = 1; //$userdata[0]['id_empresa'];

        if (!$info['enma_id']) {
            $query = $this->db->insert("alm_entrega_materiales", $info);

            $id = $this->db->insert_id();
        }else{
            $id = $info['enma_id'];
        }

        if (!$id) return false;

        foreach ($detalle as $key => $o) {
            $detalle[$key]['enma_id'] = $id;
        }

        $this->db->insert_batch('alm_deta_entrega_materiales', $detalle);
        $this->actualizar_lote($detalle);

        return $id;

    }

    public function actualizar_lote($detalle)
    {
        foreach ($detalle as $o) {
            $this->db->set('cantidad', 'cantidad - ' . $o['cantidad'], false);
            $this->db->where('lote_id', $o['lote_id']);
            $this->db->update('alm_lotes');
        }

    }

    public function get_detalle_entrega($pema)
    {
        // FILTRAR ARTICULOS PEDIDO MATERIALES
        $this->db->select('arti_id, cantidad as cant_pedida');
        $this->db->from('alm_deta_pedidos_materiales');
        $this->db->where('pema_id', $pema);
        $A = '(' . $this->db->get_compiled_select() . ') A';

        // SUMAR ENTREGAS
        $this->db->select('arti_id, sum(cantidad) as cant_entregada');
        $this->db->from('alm_entrega_materiales A');
        $this->db->join('alm_deta_entrega_materiales B', 'B.enma_id = A.enma_id');
        $this->db->where('A.pema_id', $pema);
        $this->db->group_by('B.arti_id');
        $B = '(' . $this->db->get_compiled_select() . ') B';

        // OBTENER CANTIDADES RESERVADAS
        $this->db->select('arti_id, sum(cantidad) as cant_reservada');
        $this->db->from('alm_deta_pedidos_materiales');
        $this->db->where('pema_id != ', $pema);
        $this->db->group_by('arti_id');
        $C = '(' . $this->db->get_compiled_select() . ') C';

        // SUMAR ENTREGAS DE OTRAS NOTAS PEDIDOS
        $this->db->select('arti_id, sum(cantidad) as aux_cant_entrega');
        $this->db->from('alm_entrega_materiales A');
        $this->db->join('alm_deta_entrega_materiales B', 'B.enma_id = A.enma_id');
        $this->db->where('A.pema_id !=', $pema);
        $this->db->group_by('B.arti_id');
        $D = '(' . $this->db->get_compiled_select() . ') D';

        // OBTENER EXISTENCIAS
        $this->db->select('ART.barcode, ART.descripcion, LOTE.arti_id, A.cant_pedida, sum(LOTE.cantidad) -  IFNULL(C.cant_reservada,0) + IFNULL(D.aux_cant_entrega,0) as cant_disponible, B.cant_entregada');
        $this->db->from('alm_lotes LOTE');
        $this->db->join($A, 'A.arti_id = LOTE.arti_id');
        $this->db->join($B, 'B.arti_id = LOTE.arti_id', 'left');
        $this->db->join($C, 'C.arti_id = LOTE.arti_id ', 'left');
        $this->db->join($D, 'D.arti_id = LOTE.arti_id ', 'left');
        $this->db->join('alm_articulos ART', 'ART.arti_id = LOTE.arti_id', 'left');
        $this->db->group_by('LOTE.arti_id');

        //  $aux=$this->db->get_compiled_select();

        return $this->db->get()->result_array();
    }

    public function insert_detaordeninsumo($data2)
    {
        //$userdata           = $this->session->userdata('user_data');
        $data2['empr_id'] = 1; //$userdata[0]['id_empresa'];
        $query = $this->db->insert("alm_deta_entrega_materiales", $data2);
        return $query;
    }

    public function getdeposito($data = null)
    {
        if ($data == null) {
            return false;
        } else {
            //$userdata  = $this->session->userdata('user_data');
            $empresaId = 1; //$userdata[0]['id_empresa'];
            $id = $data['artId'];

            $this->db->select('alm_articulos.arti_id as artId, alm_depositos.depo_id as depositoId, alm_depositos.descripcion as depositodescrip');
            $this->db->from('alm_articulos');
            $this->db->join('alm_lotes', 'alm_lotes.arti_id = alm_articulos.arti_id');
            $this->db->join('alm_depositos', 'alm_depositos.depo_id = alm_lotes.depo_id');
            $this->db->where('alm_lotes.arti_id', $id);
            $this->db->where('alm_lotes.empr_id', $empresaId);

            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
        }
    }

    public function getlotecant($id)
    {
        $sql = "SELECT  alm_lotes.cantidad
    	FROM alm_lotes
    	WHERE alm_lotes.depo_id=$id AND alm_lotes.lotestado='AC'
    	";
        $query = $this->db->query($sql);

        $i = 0;
        foreach ($query->result_array() as $row) {

            $datos[$i] = $row['cantidad'];
            $i++;
        }

        return $datos;
    }

    public function lote($idarticulo, $cantidadOrdenInsumo, $iddeposito)
    {
        $result = $this->traeIdLote($idarticulo, $iddeposito);

        $idLote = $result[0]["loteid"];
        if ($idLote != 0) {
            $cantidadLote = $this->lotecantidad($idLote); //obtengo la cantidad segun el lote
            //dump($cantidadLote);
        } else {
            echo "No hay insumos";
        }
        if ($cantidadLote >= $cantidadOrdenInsumo) {
            $res = $cantidadLote - $cantidadOrdenInsumo;
        } else {
            echo "No hay insumos suficientes";
            //$res=$cantidadOrdenInsumo - $cantidadLote;
        }

        $datos3 = array(
            'cantidad' => $res,
        );
        //dump($datos3);

        $this->update_tbllote($idLote, $datos3);
        return $idLote;
    }

    public function traeIdLote($idarticulo, $iddeposito)
    {
        $this->db->select('alm_lotes.lote_id as loteid');
        $this->db->from('alm_lotes');
        $this->db->where('alm_lotes.arti_id', $idarticulo);
        $this->db->where('alm_lotes.depo_id', $iddeposito);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function lotecantidad($v)
    {
        $sql = "SELECT alm_lotes.cantidad
			FROM alm_lotes
			WHERE alm_lotes.lote_id = $v";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $datos = $row->cantidad;
        }
        return $datos;
    }

    public function update_tbllote($id, $data3)
    {
        $this->db->where('lote_id', $id);
        $query = $this->db->update("alm_lotes", $data3);
        return $query;
    }

    public function alerta($codigo, $de)
    {
        $sql = "SELECT alm_lotes.cantidad
			FROM alm_lotes
			WHERE alm_lotes.arti_id=$codigo AND alm_lotes.depo_id=$de
			";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $datos = $row->cantidad;
        }
        return $datos;
    }

    public function getsolImps($id)
    {

        $sql = "SELECT T.fecha,T.solicitante,T.comprobante, A.cantidad
                  FROM alm_entrega_materiales T
                  JOIN alm_deta_entrega_materiales A ON A.enma_id=T.enma_id
                  WHERE T.ortr_id=$id
              ";

        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row) {

            $data['fecha'] = $row['fecha'];
            $data['solicitante'] = $row['solicitante'];
            $data['comprobante'] = $row['comprobante'];
            $data['cantidad'] = $row['cantidad'];

            return $data;
        }
    }

    public function getequiposBycomodato($id)
    {

        $sql = "SELECT alm_deta_entrega_materiales.loteid, alm_deta_entrega_materiales.cantidad, alm_deta_entrega_materiales.enma_id as ortr_idinsumo, alm_lotes.arti_id, alm_articulos.artBarCode, alm_articulos.artDescription
                FROM alm_deta_entrega_materiales

                JOIN alm_lotes ON alm_lotes.loteid = alm_deta_entrega_materiales.loteid
                JOIN alm_articulos ON alm_articulos.arti_id= alm_lotes.arti_id
                WHERE alm_deta_entrega_materiales.enma_id=$id
                    ";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return 0;
        }
    }

    public function getConsult($id)
    {
        $sql = "SELECT *
            FROM alm_entrega_materiales
            JOIN alm_deta_entrega_materiales ON alm_deta_entrega_materiales.enma_id = alm_entrega_materiales.enma_id
            WHERE alm_entrega_materiales.enma_id = $id
            ";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return 0;
        }
    }

    public function getequipos($id)
    {
        //$userdata  = $this->session->userdata('user_data');
        $empresaId = 1; //$userdata[0]['id_empresa'];
        $sql = "SELECT T.deen_id as id_detaordeninsumo, T.enma_id as ortr_idinsumo, T.lote_id as loteid, T.cantidad, alm_lotes.codigo, alm_lotes.depo_id, art.arti_id, art.barcode as artBarCode, art.descripcion as artDescription, alm_depositos.descripcion as depositodescrip
    		FROM alm_deta_entrega_materiales T
			JOIN alm_lotes ON alm_lotes.lote_id = T.lote_id
			JOIN alm_articulos art ON art.arti_id = alm_lotes.arti_id
			JOIN alm_depositos ON alm_depositos.depo_id = alm_lotes.depo_id
			WHERE T.enma_id = $id
            AND T.empr_id = $empresaId
			";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return 0;
        }
    }

    public function total($id)
    {
        //$userdata  = $this->session->userdata('user_data');
        $empresaId = 1; //$userdata[0]['id_empresa'];
        $sql = "SELECT SUM(alm_deta_entrega_materiales.cantidad) as cantidad
    		FROM alm_deta_entrega_materiales
			JOIN alm_entrega_materiales ON alm_entrega_materiales.enma_id = alm_deta_entrega_materiales. enma_id
			WHERE alm_deta_entrega_materiales.enma_id = $id
            AND alm_deta_entrega_materiales.empr_id = $empresaId
			";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return 0;
        }
    }

}
