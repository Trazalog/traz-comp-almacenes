<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dash extends CI_Controller {
    function __construct(){

      parent::__construct();
      $this->load->helper('menu_helper');
      $this->load->helper('file');
   }
   function index(){

      $user = $this->session->userdata('user_data');
      if(!$user){
         $this->load->view('general/login', array('status'=>false)); return;
      }

      $this->load->view('layout/Admin',$user[0]);
   }

}
?>