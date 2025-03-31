<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Articulos extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	public function obtenerTodos()
	{
		$url = REST_ALM.'/articulos/'.empresa();
		return wso2($url);
	}
	/**
	* Trae el listado de articulos por tipo
	* @param string tipos articulos
	* @return array articulos por tipo
	*/
	public function obtenerXTipo($tipo)
	{
		log_message('DEBUG', '#TRAZA | #TRAZ-COMP-ALMACENES | Articulos | obtenerXTipo()');
		$resource = "/articulos/tipo/".urlencode($tipo)."/".empresa();
        $url = REST_ALM . $resource;
        return wso2($url);
	}
	/**
	* Arma el listado de articulos por tipos
	* @param string tipos articulos
	* @return array con articulos coincidentes a los tipos
	*/
	public function obtenerXTipos($tipos)
	{
		$res = [];
		foreach ($tipos as $o) {
			$aux = $this->obtenerXTipo($o);
			$res = array_merge(($aux['status']?$aux['data']:[]), $res);
		}
		log_message('DEBUG', '#TRAZA | #TRAZ-COMP-ALMACENES | Articulos | obtenerXTipos() >> resp'.json_encode($res));
		return $res;
	}

	function getList()
	{
		$this->db->select('A.*, coalesce(sum(cantidad),0) as stock, T.valor, T1.descripcion as unidad_medidam, P.nombre as proveedor');
		$this->db->from('alm.alm_articulos A');
		$this->db->join('alm.alm_lotes C', 'C.arti_id = A.arti_id', 'left');
		$this->db->join('alm.alm_proveedores P', 'C.prov_id = P.prov_id');
		$this->db->join('core.tablas T', 'A.tiar_id = T.tabl_id', 'left');
		$this->db->join('core.tablas T1', 'A.unme_id = T1.tabl_id', 'left');
		$this->db->where('A.empr_id', empresa());
		$this->db->where('A.eliminado', false);
		$this->db->group_by('A.arti_id, T.valor, T1.descripcion, P.nombre');


		$query = $this->db->get();


		if ($query && $query->num_rows() > 0) {
			return $query->result();
		} else {
			return array();
		}
	}

	public function guardar($data)
	{
		$data['es_caja'] = isset($data['cantidad_caja']);
		$data['es_loteado'] = isset($data['es_loteado']);
		$data['empr_id'] = empresa();
		log_message('DEBUG',"#TRAZA | TRAZ-COMP-ALMACENES | Articulos | guardar()".json_encode($data) );

		$this->db->insert('alm.alm_articulos', $data);
		return $this->db->insert_id();
	}

	public function editar($data)
	{
		$this->db->where('arti_id', $data['arti_id']);
		return $this->db->update('alm.alm_articulos', $data);
	}

	function get($id)
	{
		$this->db->where('arti_id', $id);
		return $this->db->get('alm.alm_articulos')->row_array();
	}

	function getLotes($id)
	{
		$data = $this->session->userdata();
		$idUser = $data['id'];

		$this->db->where('arti_id', $id);
		$this->db->select('*');
		$this->db->from('alm.alm_lotes T');
		$this->db->join('alm.alm_depositos A', 'T.depo_id = A.depo_id');
		$this->db->join('core.encargados_depositos E', 'T.depo_id = E.depo_id and E.user_id = ' . $idUser);
		return $this->db->get()->result_array();
	}

	function getpencil($id) // Ok
	{
		$empresaId = empresa();

		$this->db->select('A.*, B.tabl_id as unidadmedida,B.descripcion as unidad_descripcion');
		$this->db->from('alm.alm_articulos A');
		$this->db->join('alm.alm.utl_tablas B', 'A.unidad_id = B.tabl_id', 'left');
		$this->db->where('arti_id', $id);
		$this->db->where('empr_id', $empresaId);



		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return 0;
		}
	}

	
	//Articulo con stock 0
	function verificarStock($id) // Ok
	{
		log_message('DEBUG', "#TRAZA | #TRAZ-COMP-ALMACENES | Articulos | verificarStock()  id: >> " . json_encode($id));

		$empresa = empresa();

	$this->db->select('SUM(alm.alm_lotes.cantidad) as cantidad');

	$this->db->from('alm.alm_lotes');

	$this->db->where('alm.alm_lotes.empr_id', $empresa);
	
	$this->db->where('alm.alm_lotes.arti_id', $id);


$query = $this->db->get();
    
	if ($query->num_rows() && $query->num_rows() != 0) {
		return $query->result_array();

		log_message('DEBUG', "#TRAZA | #TRAZ-COMP-ALMACENES | Articulos | verificarStock()  Cantidad: >> " . json_encode($query->result_array()));
	} else {
		log_message('DEBUG', "#TRAZA | #TRAZ-COMP-ALMACENES | Articulos | verificarStock()  Cantidad: >> " . json_encode('0'));
		return false;
	}
}

	function eliminar($id)
	{
		$this->db->where('arti_id', $id);
		$this->db->set('eliminado', true);
		return $this->db->update('alm.alm_articulos');
	}

	function getUnidadesMedidas()
	{
		$this->db->select('A.tabl_id as id_unidadmedida, A.descripcion, A.valor');
		$this->db->where('tabla', 'unidad');
		$query  = $this->db->get('alm.alm.utl_tablas A');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	function getUM()
	{
		$this->db->select('A.tabl_id as id_unidadmedida, A.descripcion, A.valor');
		$this->db->where('tabla', 'unidades_medida');
		$query  = $this->db->get('alm.utl_tablas A');
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}


	function getArticle($data = null)
	{

		if ($data == null || strpos('Add', $data['act']) == 0) {
			return false;
		} else {
			$empresaId = empresa();
			$action    = $data['act'];
			$idArt     = $data['id'];
			$data      = array();

			$this->db->select('A.*,B.valor as unidad');
			$this->db->from('alm.alm_articulos A');
			$this->db->join('alm.alm.utl_tablas B', 'A.unidad_id = B.tabl_id');
			$this->db->join('alm.alm.utl_tablas C', 'A.estado_id = C.tabl_id');
			$this->db->where('C.valor', 'AC');

			$query = $this->db->get();

			if ($query->num_rows() != 0) {
				//echo "if ".$empresaId;
				$c               = $query->result_array();
				$data['article'] = $c[0];
			} else {
				//echo "else ".$empresaId;
				$art                       = array();
				$art['artId']              = '';
				$art['artBarCode']         = '';
				$art['artDescription']     = '';
				$art['artCoste']           = '';
				$art['artMargin']          = '';
				$art['artMarginIsPorcent'] = '';
				$art['artIsByBox']         = '';
				$art['artCantBox']         = '';
				$art['artEstado']          = 'AC';
				$art['unidadmedida']       = '';
				$art['punto_pedido']       = '';
				$art['id_empresa']		   = $empresaId;
				$data['article']           = $art;
			}
			$data['article']['action'] = $action;
			//Readonly
			$readonly = false;
			if ($action == 'Del' || $action == 'View') {
				$readonly = true;
			}
			$data['read']   = $readonly;
			$data['action'] = $action;


			return $data;
		}
	}

	function setArticle($data = null)
	{
		if ($data == null) {
			return false;
		} else {
			$empresaId = empresa();
			$id        = $data['id'];
			$act       = $data['act'];
			$name      = $data['name'];
			$status    = $data['status'];
			$box       = $data['box'];
			$boxCant   = $data['boxCant'];
			$code      = $data['code'];

			$unidmed   = $data['unidmed'];
			$puntped   = $data['puntped'];
			$data      = array(
				'barcode'     => $code,
				'descripcion' => $name,
				'estado_id'      => $status,
				'es_caja'     => ($box === 'true'),
				'cantidad_caja'     => $boxCant,
				'unidad_id'   => $unidmed,
				'punto_pedido'   => $puntped,
				'es_loteado' => $data['es_loteado'],
				'empr_id'	 => $empresaId
			);

			switch ($act) {
				case 'Add':
					if ($this->db->get_where('alm.alm_articulos', ['barcode' => $code, 'empr_id' => $empresaId])->num_rows() > 0) return false;

					if ($this->db->insert('alm.alm_articulos', $data)) {
						return $this->db->insert_id();
					}
					break;
				case 'Edit':
					//Actualizar Artículo
					if ($this->db->update('alm.alm_articulos', $data, array('artId' => $id)) == false) {
						return false;
					}
					break;
				case 'Del':
					//Eliminar Artículo
					if ($this->db->delete('alm.alm_articulos', array('artId' => $id)) == false) {
						return false;
					}
					break;
			}
			return true;
		}
	}

	function getdatosfams()
	{
		$empresaId = empresa();
		$query     = $this->db->get_where('conffamily', array('id_empresa' => $empresaId));
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	function update_articulo($data, $idarticulo)
	{
		$empresaId          = empresa();
		$data['id_empresa'] = $empresaId;
		$this->db->where('artId', $idarticulo);
		$query = $this->db->update("articles", $data);
		return $query;
	}



	function update_editar($data, $id)
	{
		$this->db->where('arti_id', $id);
		$query = $this->db->update("alm.alm_articulos", $data);
		return $query;
	}

	function searchByCode($data = null)
	{
		$str = '';
		if ($data != null) {
			$str = $data['code'];
		}

		$articles = array();

		$this->db->select('*');
		$this->db->from('articles');
		$this->db->where(array('artBarCode' => $str, 'artEstado' => 'AC'));
		$query = $this->db->get();
		if ($query->num_rows() != 0) {
			if ($query->num_rows() > 1) {
				//Multiples coincidencias
			} else {
				//Unica coincidencia
				$a = $query->result_array();
				$articles = $a[0];

				//Calcular precios 
				$pUnit = $articles['artCoste'];
				if ($articles['artIsByBox'] == 1) {
					$pUnit = $articles['artCoste'] / $articles['artCantBox'];
				}

				if ($articles['artMarginIsPorcent'] == 1) {
					$articles['pVenta'] = $pUnit + ($pUnit * ($articles['artMargin'] / 100));
				} else {
					$articles['pVenta'] = $pUnit + $articles['artMargin'];
				}
			}
			return $articles;
		}

		return $articles;
	}

	function searchByAll($data = null)
	{
		$str = '';
		if ($data != null) {
			$str = $data['code'];
		}

		$art = array();

		$this->db->select('*');
		$this->db->from('articles');
		$this->db->where('artEstado', 'AC');
		if ($str != '') {
			$this->db->like('artBarCode', $str);
			$this->db->or_like('artDescription', $str);
		}
		$query = $this->db->get();
		if ($query->num_rows() != 0) {
			foreach ($query->result_array() as $a) {
				$articles = $a;

				//Calcular precios 
				$pUnit = $articles['artCoste'];
				if ($articles['artIsByBox'] == 1) {
					$pUnit = $articles['artCoste'] / $articles['artCantBox'];
				}

				if ($articles['artMarginIsPorcent'] == 1) {
					$articles['pVenta'] = $pUnit + ($pUnit * ($articles['artMargin'] / 100));
				} else {
					$articles['pVenta'] = $pUnit + $articles['artMargin'];
				}

				$art[] = $articles;
			}
		}

		return $art;
	}

	function getestados()
	{
		$empresaId = empresa();

		$this->db->select('articles.artEstado, tbl_estado.estadoid, tbl_estado.descripcion');
		$this->db->from('articles');
		$this->db->join('tbl_estado', 'tbl_estado.estado = articles.artEstado');
		$this->db->where('articles.id_empresa', $empresaId);
		$this->db->group_by('articles.artEstado');
		$query = $this->db->get();
		if ($query->num_rows() != 0) {
			return $query->result_array();
		} else {
			return array();
		}
	}

	public function getArtiService()
	{
		$url =  REST_ALM . '/articulos/'.empresa();
		$data = wso2($url);
		if ($data['status']) {	
			return $data['data'];
		}
	}

	#FLEIVA
	public function obtener($id = false)
	{
		$recurso = REST_ALM.'/articulos/'.($id?$id:empresa());
		return wso2($recurso);
	}

	/**
	* Consulta al service si el codigo insertado, ya esta creado para la empresa
	* @param string código Artículo; empr_id
	* @return array respuesta del servicio
	*/
	public function validarArticulo($barcode){
        
		$url = REST_ALM."/articulo/validar/". urlencode($barcode) . "/empresa/".empresa();
	
		$aux = $this->rest->callAPI("GET",$url);
		$resp = json_decode($aux['data']);
	
		log_message('DEBUG', "#TRAZA | #TRAZ-COMP-ALMACENES | Articulos | validarArticulo() >> resp ".json_encode($resp));
	
		return $resp->resultado;
	}
	/**
	* Genera el listado de los articulos paginado
	* @param integer;integer;string start donde comienza el listado; length cantidad de registros; search cadena a buscar
	* @return array listado paginado y la cantidad
	*/
	public function articulosPaginados($start,$length,$search, $ordering)
	{
			// Obtener columna y dirección desde $ordering
			// valor a la que hace referencia cada columna 
			$columns = [
				1 => 'A.barcode',       //Codigo  
				2 => 'A.descripcion',   //Descripcion
				3 => 'T.valor',         //tipo de producto
				4 => 'T1.descripcion'  	//unidad de medida
			];
		
			$columnIndex = $ordering[0]['column']; // obtengo indice de la columna
			$direction = $ordering[0]['dir'];     // dirección (asc o desc)
		
			// Validar índice de columna
			$column = isset($columns[$columnIndex]) ? $columns[$columnIndex] : 'A.descripcion';
		
			// Validar dirección
			$direction = in_array(strtolower($direction), ['asc', 'desc']) ? $direction : 'asc';
		
			// Consulta total de datos
			$this->db->select('A.*, coalesce(sum(cantidad),0) as stock, T.valor, T1.descripcion as unidad_medida');
			$this->db->from('alm.alm_articulos A');
			$this->db->join('alm.alm_lotes C', 'C.arti_id = A.arti_id', 'left');
			$this->db->join('core.tablas T', 'A.tiar_id = T.tabl_id', 'left');
			$this->db->join('core.tablas T1', 'A.unme_id = T1.tabl_id', 'left');
			$this->db->where('A.eliminado', false);
		
			if (!empty($search)) {
				$this->db->group_start();
				$this->db->like('LOWER("A"."barcode")', strtolower($search));
				$this->db->or_like('LOWER("A"."descripcion")', strtolower($search));
				$this->db->group_end();
			}
		
			$this->db->where('A.empr_id', empresa());
			$this->db->group_by('A.arti_id, T.valor, T1.descripcion');
		
			// Contar resultados
			$query_total = $this->db->get();
			//log_message('DEBUG', "CONSULTA TOTAL: " . $this->db->last_query());
		
			$total_rows = $query_total ? $query_total->num_rows() : 0;
		
			// Consulta datos paginados
			$this->db->select('A.*, coalesce(sum(cantidad),0) as stock, T.valor, T1.descripcion as unidad_medida');
			$this->db->from('alm.alm_articulos A');
			$this->db->join('alm.alm_lotes C', 'C.arti_id = A.arti_id', 'left');
			$this->db->join('core.tablas T', 'A.tiar_id = T.tabl_id', 'left');
			$this->db->join('core.tablas T1', 'A.unme_id = T1.tabl_id', 'left');
			$this->db->where('A.eliminado', false);
		
			if (!empty($search)) {
				$this->db->group_start();
				$this->db->like('LOWER("A"."barcode")', strtolower($search));
				$this->db->or_like('LOWER("A"."descripcion")', strtolower($search));
				$this->db->group_end();
			}
		
			$this->db->where('A.empr_id', empresa());
			$this->db->group_by('A.arti_id, T.valor, T1.descripcion');
			$this->db->order_by($column, $direction); 
			$this->db->limit($length, $start);
		
			$articulosPaginados = $this->db->get();
			//log_message('DEBUG', "CONSULTA PAGINADA: " . $this->db->last_query());
		
			return [
				'numDataTotal' => $total_rows,
				'datos' => $articulosPaginados
			];
		
		
	}
}
