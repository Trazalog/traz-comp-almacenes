<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {
    function __construct(){

      parent::__construct();
    
   }
   function index(){
      $this->load->model(CMP_ALM.'/new/Entregas_Materiales'); 
      echo var_dump($this->Entregas_Materiales->obtenerDetalles(6));
   }
}
?>  