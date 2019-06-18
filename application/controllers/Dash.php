<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dash extends CI_Controller {
    function __construct(){

      parent::__construct();
      $this->load->helper('menu_helper');
      $this->load->helper('file');
   }
   function index(){

      //!USUARIO HARDCODEADO
      // $session = ["usrId"=>"1","usrNick"=>"mantenedor1","usrName"=>"mantenedor","usrLastName"=>"mantenedor apellido","id_empresa"=>"6","descripcion"=>"Frankenstein","grpId"=>"1","usrimag"=>"","userBpm"=>"102","permission"=>"Add-Edit-Del-View"];
      // $this->session->set_userdata('user_data', array($session));
      $this->load->view('general/login');
   }
   function dash(){
     
      $data['menu'] = menu(file_get_contents("C:/xampp/htdocs/traz-comp-almacenes/menu.json"));

      //!USUARIO HARDCODEADO
      $session = ["usrId"=>"1","usrNick"=>"mantenedor1","usrName"=>"mantenedor","usrLastName"=>"mantenedor apellido","id_empresa"=>"6","descripcion"=>"Frankenstein","grpId"=>"1","usrimag"=>"","userBpm"=>"102","permission"=>"Add-Edit-Del-View"];
      $this->session->set_userdata('user_data', array($session));
      $this->load->view('layout/Admin',$data);
   }
}
?>