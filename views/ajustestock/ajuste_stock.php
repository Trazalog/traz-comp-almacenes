<form autocomplete="off" method="POST" id="formTotal">
    <?php 
        $this->load->view(ALM.'ajustestock/componentes/cabecera');
    ?>

    <div class="row">
        <div class="col-md-6">
            <?php
                $this->load->view(ALM.'ajustestock/componentes/entrada');
            ?>
        </div>
        <div class="col-md-6">
            <?php
            $this->load->view(ALM.'ajustestock/componentes/salida');
            ?>
        </div>
    </div>

    <?php 
        $this->load->view(ALM.'ajustestock/componentes/justificacion');
    ?>
</form>

<script>
obtenerArticulos();

function obtenerArticulos() {
    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: 'index.php/<?php echo ALM ?>Articulo/obtener',
        success: function(rsp) {

            if (!rsp.status) {
                error('Error','No hay artículos disponibles!');
                return;
						}
            rsp.data.forEach(function(e, i) {
                $('.articulos').append(
                    `<option data-json='${JSON.stringify(e)}' value="${e.arti_id}" data="${e.unidad_medida}">${e.barcode} | ${e.titulo}</option>`
                );
            });
        },
        error: function(rsp) {
            error('Error!', rsp.msj);
            console.log(rsp.msj);
        }
    });
}

$(".select2").select2();

$("#articuloent").on('change', function() {
    $("#unidadesent").val($("#articuloent>option:selected").attr("data"));
});
$("#articulosal").on('change', function() {
    $("#unidadsal").val($("#articulosal>option:selected").attr("data"));
});

// Trae lotes por articulo de Salida
$("#articulosal").on('change', function() {
		wo('Buscando lotes activos...');
		var dato = $("#unidadsal").val();
    var $idarticulo = $("#articulosal option:selected").val();
    var $iddeposito = $("#deposito option:selected").val();

		$.ajax({
        type: 'GET',
        dataType: 'json',
        url: '<?php echo ALM ?>Lote/listarPorArticulo?arti_id=' + $idarticulo + '&depo_id=' +
            $iddeposito,
        success: function(result) {
            if (result == null) {
                var option_lote = '<option value="" disabled selected>-Sin lotes-</option>';
                console.log("Sin lotes");
            } else {
                var option_lote = '<option value="" disabled selected>-Seleccione opción-</option>';
                for (let index = 0; index < result.length; index++) {
                    option_lote += '<option value="' + result[index].lote_id + '">' + result[index]
                        .codigo +
                        '</option>';
                }
            }
						$('#lotesal').html(option_lote);
						wc();
        },
        error: function() {
						wc();
            alert('Error');
        }
    });
});

$("#articuloent").on('change', function() {

		wo('Buscando lotes activos...');

    $idarticulo = $("#articuloent>option:selected").val();
    $iddeposito = $("#deposito>option:selected").val();

    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: '<?php echo ALM ?>Lote/listarPorArticulo?arti_id=' + $idarticulo + '&depo_id=' +
            $iddeposito,
        success: function(result) {
            if (result == null) {
                var option_lote = '<option value="" disabled selected>Sin lotes</option>';
            } else {
                var option_lote = '<option value="" disabled selected>-Seleccione opción-</option>';
                for (let index = 0; index < result.length; index++) {
                    option_lote += '<option value="' + result[index].lote_id + '">' + result[index]
                        .codigo +
                        '</option>';
                }
            }
						$('#loteent').html(option_lote);
						wc();
        },
        error: function() {
						wc();
            alert('Error');
        }
    });
});

function guardar(){
		wo();
		var formdata = new FormData($("#formTotal")[0]);
		var formobj = formToObject(formdata);
		formobj.tipo_ent_sal  = $("#tipoajuste>option:selected").attr("data");

		$.ajax({
				type: 'POST',
				dataType: 'json',
        data: {
            data: formobj
        },
        url: '<?php echo ALM ?>Ajustestock/guardarAjuste',
        success: function(rsp) {
						wc();
						debugger;
						alertify.success(rsp.data);
        },
        error: function(rsp) {
						wc();
           	alertify.error(rsp.data);
        },
        complete: function() {}
    });
}

</script>