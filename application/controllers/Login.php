<?php defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {

        parent::__construct();
        $this->load->model('Logins');
        $this->load->helper('menu_helper');
      $this->load->helper('file');
    }

    public function validarUsuario()
    {

        $data = $this->input->post();

        if ($this->Logins->validarUsuario($data)) {
         $data['menu'] = menu(file_get_contents(base_url("menu.json")));

         //!USUARIO HARDCODEADO
         $session = ["usrId"=>"1","usrNick"=>"mantenedor1","usrName"=>"mantenedor","usrLastName"=>"mantenedor apellido","id_empresa"=>"6","descripcion"=>"Frankenstein","grpId"=>"1","usrimag"=>"","userBpm"=>"102","permission"=>"Add-Edit-Del-View"];
         $this->session->set_userdata('user_data', array($session));
         $this->load->view('layout/Admin',$data);
        }else{
           redirect('Dash');
        }
    }
}