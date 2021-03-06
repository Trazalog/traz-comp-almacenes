<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Notapedidos extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function setMotivoRechazo($pema, $motivo)
    {

        $this->db->where('pema_id', $pema);
        $this->db->set('motivo_rechazo', $motivo);
        return $this->db->update('alm.alm_pedidos_materiales');

    }

    public function notaPedidos_List($ot = null)
    {
        $this->db->select('T.pema_id as id_notaPedido,T.fecha,T.ortr_id as id_ordTrabajo,T.justificacion, T.estado');
        if ($ot) {
            $this->db->select('orden_trabajo.descripcion');
        }

        $this->db->from('alm.alm_pedidos_materiales as T');
        if ($ot) {
            $this->db->join('orden_trabajo', 'T.ortr_id = orden_trabajo.id_orden', 'left');
        }

        $this->db->where('T.empr_id', empresa());
        if ($ot) {
            $this->db->where('orden_trabajo.id_orden', $ot);
        }

        $query = $this->db->get();

        if ($query->num_rows() != 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function get($id)
    {
        $this->db->where('pema_id', $id);
        return $this->db->get('alm.alm_pedidos_materiales')->row_array();
    }

    public function getXCaseId($case)
    {
        $this->db->where('case_id', $case);
        return $this->db->get('alm.alm_pedidos_materiales')->row_array();
    }

    public function setCaseId($id, $case)
    {
        log_message('DEBUG', "ALM#NOTAPEDIDOS > setCaseId | ID: $id | CASE: $case");
        $this->db->set('case_id', $case);
        $this->db->set('estado', $case ? 'Solicitado' : 'Reintentar');
        $this->db->where('pema_id', $id);
        $this->db->update('alm.alm_pedidos_materiales');
    }

    //
    public function getNotasxOT($id)
    {
        $empId = empresa();
        $this->db->select('
            alm.alm_pedidos_materiales.pema_id as id_notaPedido,
            alm.alm_pedidos_materiales.fecha,
            alm.alm_pedidos_materiales.ortr_id as id_ordTrabajo,
            solicitud_reparacion.solicitante,
            orden_trabajo.descripcion
        ');
        $this->db->from('alm.alm_pedidos_materiales');
        $this->db->join('orden_trabajo', 'alm.alm_pedidos_materiales.ortr_id = orden_trabajo.id_orden');
        $this->db->join('solicitud_reparacion', 'orden_trabajo.id_solicitud = solicitud_reparacion.id_solicitud', 'left');
        $this->db->where('alm.alm_pedidos_materiales.empr_id', $empId);
        $this->db->where('orden_trabajo.id_orden', $id);

        $query = $this->db->get();

        if ($query->num_rows() != 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    // Trae lista de articulos por id de nota de pedido
    public function getNotaPedidoIds($id)
    {
        if (!$id) {
            return false;
        }

        $this->db->select('alm.alm_pedidos_materiales.pema_id as id_notaPedido,
                          alm.alm_pedidos_materiales.fecha,
                          alm.alm_pedidos_materiales.ortr_id as id_ordTrabajo,
                          alm.alm_pedidos_materiales.justificacion,
                          alm.alm_deta_pedidos_materiales.cantidad,
                          (alm.alm_deta_pedidos_materiales.cantidad - alm.alm_deta_pedidos_materiales.resto) as entregado,
                          alm.alm_deta_pedidos_materiales.fecha_entrega,
                          alm.alm_deta_pedidos_materiales.fecha_entregado,
                          alm.alm_articulos.barcode,
                          alm.alm_articulos.arti_id,
                          alm.alm_articulos.descripcion as artDescription,
                          alm.alm_deta_pedidos_materiales.depe_id'
        );

        if (viewOT) {
            $this->db->select('orden_trabajo.descripcion');
        }

        $this->db->from('alm.alm_pedidos_materiales');
        if (viewOT) {
            $this->db->join('orden_trabajo', 'alm.alm_pedidos_materiales.ortr_id = orden_trabajo.id_orden', 'left');
        }

        $this->db->join('alm.alm_deta_pedidos_materiales', 'alm.alm_deta_pedidos_materiales.pema_id = alm.alm_pedidos_materiales.pema_id');
        $this->db->join('alm.alm_articulos', 'alm.alm_deta_pedidos_materiales.arti_id = alm.alm_articulos.arti_id');
        $this->db->where('alm.alm_pedidos_materiales.pema_id', $id);
        $query = $this->db->get();

        if ($query->num_rows() != 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function getArticulos()
    {
        $query = $this->db->query("SELECT alm.alm_articulos;.artId, alm.alm_articulos;.artBarCode,alm.alm_articulos;.artDescription FROM alm.alm_articulos;");
        $i = 0;
        foreach ($query->result() as $row) {

            $insumos[$i]['value'] = $row->artId;
            $insumos[$i]['label'] = $row->artBarCode;
            $insumos[$i]['descripcion'] = $row->artDescription;
            $i++;
        }
        return $insumos;
    }

    public function getProveedores()
    {

        $this->db->select('alm.alm_proveedores.provid, alm.alm_proveedores.provnombre');
        $this->db->from('alm.alm_proveedores');
        $query = $this->db->get();
        if ($query->num_rows() != 0) {

            return $query->result_array();
        }
    }

    public function setNotaPedidos($data)
    {
        $empId = empresa();

        $orden = (int) $data['orden_Id'][0];
        $notaP = array(
            'fecha' => date('Y-m-d H:i:s'),
            'empr_id' => $empId,
        );

        if ($orden) {
            $notaP['ortr_id'] = $orden;
        }

        $this->db->insert('alm.alm_pedidos_materiales', $notaP);
        $idNota = $this->db->insert_id();

        for ($i = 0; $i < count($data['insum_Id']); $i++) {

            $insumo = $data['insum_Id'][$i];
            $cant = $data['cant_insumos'][$i];
            $proveed = $data['proveedid'][$i];
            $date = $data['fechaentrega'][$i];
            $newDate = date("Y-m-d", strtotime($date));

            $nota = array(
                'pema_id' => $idNota,
                'arti_id' => $insumo,
                'cantidad' => $cant,
                'resto' => $cant,
                'prov_id' => $proveed,
                'fechaEntrega' => $newDate,
                'fechaEntregado' => $newDate,
            );
            $this->db->insert('alm.alm_deta_pedidos_materiales', $nota);
        }

        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }

    } // fin setNotaPedidos

    // devuelve plantilla por Id de cliente
    public function getPlantillaPorCliente($idcliente)
    {
        //FIXME: DESHARDCODEAR ESTE CLIENTE!!!!
        $idcliente = 21;
        $this->db->select('asp_detaplantillainsumos.artId,
												alm.alm_articulos;.artDescription,
												asp_plantillainsumos.plant_id');
        $this->db->from('asp_detaplantillainsumos');
        $this->db->join('asp_plantillainsumos', 'asp_detaplantillainsumos.plant_id = asp_plantillainsumos.plant_id');
        $this->db->join('alm.alm_articulos;', 'alm.alm_articulos;.artId = asp_detaplantillainsumos.artId');
        $this->db->join('admcustomers', 'asp_plantillainsumos.plant_id = admcustomers.plant_id');
        $this->db->where('admcustomers.plant_id', '(SELECT admcustomers.plant_id WHERE admcustomers.cliId = ' . $idcliente . ')', false);
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }
    // guarda nota pedido (desde tareas de bpm)
    public function setCabeceraNota($cabecera)
    {
        $cabecera['ortr_id'] = (int) $cabecera['ortr_id'];
        $this->db->insert('alm.alm_pedidos_materiales', $cabecera);
        $idInsert = $this->db->insert_id();
        return $idInsert;
    }
    // guarda detalle nota pedido (desde tareas de bpm)
    public function setDetaNota($deta)
    {
        foreach ($deta as $o) {
            $o['resto'] = $o['cantidad'];
            if ($this->db->get_where('alm.alm_deta_pedidos_materiales', array('pema_id' => $o['pema_id'], 'arti_id' => $o['arti_id']))->num_rows() == 1) {

                $this->db->where(array('pema_id' => $o['pema_id'], 'arti_id' => $o['arti_id']));
                $this->db->update('alm.alm_deta_pedidos_materiales', $o);

            } else {
                $this->db->insert('alm.alm_deta_pedidos_materiales', $o);
            }
        }
        return true;
    }

    public function editarDetalle($id, $data)
    {
        $this->db->where('depe_id', $id);
        $data['resto'] = $data['cantidad'];
        return $this->db->update('alm.alm_deta_pedidos_materiales', $data);
    }

    public function eliminarDetalle($id)
    {
        $this->db->where('depe_id', $id);
        return $this->db->delete('alm.alm_deta_pedidos_materiales');
    }

    public function nuevo($data)
    {
        #CABECERA PEDIDO
        $pemaId = $this->setCabeceraNP();
        if(!$pemaId) return;

        #DETALLE PEDIDO
        $rsp = $this->setDetaNP($pemaId, $data);
  
        if($rsp['status']){
            return $pemaId;
        }
    }

    public function asociarOrigen($pemaId, $origen, $id)
    {
        $data['_post_notapedido_origen'] = array(
            'pema_id' => "$pemaId",
            'orig_id' => "$id",
            'tipo' => $origen
        );
        $url =  REST_ALM.'/notapedido/origen';
        return wso2($url, 'POST', $data);
    }

    // Guarda cabecera de Nota de pedido
    public function setCabeceraNP()
    {
        $data['_post_notapedido'] = array('fecha' => date('Y-m-d'), 'empr_id' => (string) empresa(), 'estado' => 'Creada');
        $resource = '/notapedido/nuevo';
        $url = REST_ALM . $resource;
        return wso2($url, 'POST', $data)['data'];
    }
    // Guarda detalle de Nota de pedido
    public function setDetaNP($pemaId, $materia)
    {
        foreach ($materia as $o) {
            if ($cantidad !== "") {
                $det['pema_id'] = $pemaId;
                $det['arti_id'] = (string) $o['arti_id'];
                $det['cantidad'] = $o['cantidad'];
                $detalle['_post_notapedido_detalle'][] = $det;
            }
        }
        $arrayDeta['_post_notapedido_detalle_batch_req'] = $detalle;

        $resource = '/_post_notapedido_detalle_batch_req';
        $url = REST_ALM . $resource;
        return wso2($url, 'POST', $arrayDeta);
    }

    public function obtenerXOrigen($origen, $origId)
    {
        $url = REST_ALM."/notapedidos/origen/$origen/$origId";
        return wso2($url);
    }
}
