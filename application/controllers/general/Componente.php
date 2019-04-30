<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Componente extends CI_Controller {
    function __construct(){

      parent::__construct();
      //$this->load->model(''); 
   }
   function index(){
      $this->load->view('test');
   }
}
?>