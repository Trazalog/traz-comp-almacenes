<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {
    function __construct(){

      parent::__construct();
      $this->load->model(CMP_ALM.'/Ordeninsumos'); 
   }
   function index(){
      $this->Ordeninsumos->get_cantidad_disponible(1);
   }
}
?>  