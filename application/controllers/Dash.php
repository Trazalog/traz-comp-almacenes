<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dash extends CI_Controller {
    function __construct(){

      parent::__construct();
      $this->load->helper('menu_helper');
      $this->load->helper('file');
   }
   function index(){
     
      $data['menu'] = menu(file_get_contents("C:/xampp/htdocs/traz-comp-almacenes/menu.json"));
      //var_dump($data['menu']);die;
      $this->load->view('layout/Admin',$data);
   }
}
?>