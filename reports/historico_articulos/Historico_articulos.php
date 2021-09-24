<?php

require  APPPATH . "/modules/".ALM. "/libraries/koolreport/core/autoload.php";

//Specify some data processes that will be used to process
// use \koolreport\processes\Group;
use \koolreport\processes\Sort;
use \koolreport\processes\Limit;
// use \koolreport\processes\RemoveColumn;
use \koolreport\processes\OnlyColumn;
use \koolreport\processes\Custom;

/**
* Definicion de Clase, configuracion
*
* @autor Hugo Gallardo
*/
class Historico_articulos extends \koolreport\KoolReport
{
    use \koolreport\codeigniter\Friendship;
    /*Filtros Avanzados*/
    /*Enlace de datos entre los parámetros del informe y los Controles de entrada */

    function cacheSettings()
    {
			return array(
					"ttl" => 60, //determina cuántos segundos será válido el caché
			);
    }

    protected function settings()    {

				log_message('INFO','#TRAZA|HISTORICOARTICULOS|SETTINGS >> ');
        $json = $this->params;
        $data = json_encode($json);

        return array(
            "dataSources" => array(
                "apiarray" => array(
                    "class" => '\koolreport\datasources\ArrayDataSource',
                    "dataFormat" => "associate",
                    "data" => json_decode($data, true),
                )
            )
        );
    }

		/**
		* Definicion de los componentes donde se van a mostrar los datos
		* @param
		* @return
		*/
    protected function setup()
    {
        log_message('DEBUG', '#TRAZA| #INGRESOS|#SETUP| #INGRESO');
        $this->src("apiarray")
            ->pipe($this->dataStore("data_historico_table"));

        $this->src("apiarray")
            ->pipe($this->dataStore("data_salidas_pieChart"));

        $this->src("apiarray")
            ->pipe(new OnlyColumn(array(
                "nombre", "neto"
            )))
            ->pipe(new Sort(array(
                "neto" => "desc"
            )))
            ->pipe(new Limit(
                array(6)
            ))
            ->pipe($this->dataStore("data_salidas_clumnChart"));
    }
}
