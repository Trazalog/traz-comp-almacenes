<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {
    function __construct(){

      parent::__construct();
      
   }
   function index(){
      
		$this->load->model(CMP_ALM.'/Articulos');
	
		echo var_dump($this->Articulos->getList());
   }
}
?>