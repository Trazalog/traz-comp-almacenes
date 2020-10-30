

ALTER TABLE alm.alm_deta_recepcion_materiales ADD CONSTRAINT alm_deta_recepcion_materiales_lote_id_fk FOREIGN KEY (lote_id) REFERENCES alm.alm_lotes(lote_id);