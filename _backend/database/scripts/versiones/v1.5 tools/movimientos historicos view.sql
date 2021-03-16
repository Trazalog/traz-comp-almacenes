CREATE OR REPLACE VIEW alm.movimientos_historicos_vw
AS SELECT adem.enma_id AS referencia,
    adem.cantidad,
    adem.fec_alta,
    aa.barcode AS codigo,
    aa.descripcion,
    al.codigo AS lote,
    al.cantidad AS stock_actual,
    ad.descripcion AS deposito,
    al.depo_id,
    'EGRESO'::text AS tipo_mov
   FROM alm.alm_deta_entrega_materiales adem,
    alm.alm_articulos aa,
    alm.alm_lotes al,
    alm.alm_depositos ad
  WHERE aa.arti_id = adem.arti_id AND al.lote_id = adem.lote_id AND al.depo_id = ad.depo_id
UNION ALL
 SELECT adrm.rema_id AS referencia,
    adrm.cantidad,
    adrm.fec_alta,
    aa.barcode AS codigo,
    aa.descripcion,
    al.codigo AS lote,
    al.cantidad AS stock_actual,
    ad.descripcion AS deposito,
    al.depo_id,
    'INGRESO'::text AS tipo_mov
   FROM alm.alm_deta_recepcion_materiales adrm,
    alm.alm_articulos aa,
    alm.alm_lotes al,
    alm.alm_depositos ad
  WHERE aa.arti_id = adrm.arti_id AND al.lote_id = adrm.lote_id AND al.depo_id = ad.depo_id
UNION ALL
 SELECT dmi.demi_id AS referencia,
    dmi.cantidad_recibida AS cantidad,
    dmi.fec_alta,
    aa.barcode AS codigo,
    aa.descripcion,
    al.codigo AS lote,
    al.cantidad AS stock_actual,
    ad.descripcion AS deposito,
    al.depo_id,
    'MOV. ENTRADA'::text AS tipo_mov
   FROM alm.deta_movimientos_internos dmi,
    alm.alm_articulos aa,
    alm.alm_lotes al,
    alm.alm_depositos ad
  WHERE aa.arti_id = dmi.arti_id AND al.lote_id = dmi.lote_id_destino AND ad.depo_id = al.depo_id
UNION ALL
 SELECT dmi.demi_id AS referencia,
    dmi.cantidad_cargada AS cantidad,
    dmi.fec_alta,
    aa.barcode AS codigo,
    aa.descripcion,
    al.codigo AS lote,
    al.cantidad AS stock_actual,
    ad.descripcion AS deposito,
    al.depo_id,
    'MOV. SALIDA'::text AS tipo_mov
   FROM alm.deta_movimientos_internos dmi,
    alm.alm_articulos aa,
    alm.alm_lotes al,
    alm.alm_depositos ad
  WHERE aa.arti_id = dmi.arti_id AND al.lote_id = dmi.lote_id_destino AND ad.depo_id = al.depo_id
UNION ALL
 SELECT da.deaj_id AS referencia,
    da.cantidad,
    da.fec_alta,
    aa.barcode AS codigo,
    aa.descripcion,
    al.codigo AS lote,
    al.cantidad AS stock_actual,
    ad.descripcion AS deposito,
    al.depo_id,
    'AJUSTE'::text AS tipo_mov
   FROM alm.deta_ajustes da,
    alm.alm_lotes al,
    alm.alm_articulos aa,
    alm.alm_depositos ad
  WHERE da.lote_id = al.lote_id AND al.arti_id = aa.arti_id AND ad.depo_id = al.depo_id
UNION ALL
 SELECT lh.batch_id AS referencia,
    lh.cantidad,
    lh.fec_alta,
    aa.barcode AS codigo,
    aa.descripcion,
    lh.batch_id::character varying AS lote,
    al.cantidad AS stock_actual,
    ad.descripcion AS deposito,
    al.depo_id,
    'INGRESO PRODUCTO'::text AS tipo_mov
   FROM prd.lotes_hijos lh,
    prd.lotes lpadre,
    prd.lotes l2,
    alm.alm_lotes al,
    alm.alm_articulos aa,
    alm.alm_depositos ad
  WHERE lh.batch_id_padre = lpadre.batch_id AND lh.batch_id = l2.batch_id AND l2.batch_id = al.batch_id AND al.arti_id = aa.arti_id AND lpadre.etap_id < 1000 AND al.depo_id = ad.depo_id
UNION ALL
 SELECT lh.batch_id AS referencia,
    lh.cantidad,
    lh.fec_alta,
    aa.barcode AS codigo,
    aa.descripcion,
    lh.batch_id::character varying AS lote,
    al.cantidad AS stock_actual,
    ad.descripcion AS deposito,
    al.depo_id,
    'INGRESO PROD. EN PROCESO'::text AS tipo_mov
   FROM prd.lotes_hijos lh,
    prd.lotes l,
    alm.alm_lotes al,
    alm.alm_articulos aa,
    alm.alm_depositos ad
  WHERE lh.batch_id = l.batch_id AND l.batch_id = al.batch_id AND al.arti_id = aa.arti_id AND l.etap_id < 1000 AND al.depo_id = ad.depo_id
UNION ALL
 SELECT lh.batch_id_padre AS referencia,
    lh.cantidad_padre AS cantidad,
    lh.fec_alta,
    aa.barcode AS codigo,
    aa.descripcion,
    lh.batch_id::character varying AS lote,
    al.cantidad AS stock_actual,
    ad.descripcion AS deposito,
    al.depo_id,
    'EGRESO PROD. EN PROCESO'::text AS tipo_mov
   FROM prd.lotes_hijos lh,
    prd.lotes l,
    alm.alm_lotes al,
    alm.alm_articulos aa,
    alm.alm_depositos ad
  WHERE lh.batch_id_padre = l.batch_id AND l.etap_id < 1000 AND l.batch_id = al.batch_id AND al.arti_id = aa.arti_id AND lh.cantidad_padre <> 0::double precision;