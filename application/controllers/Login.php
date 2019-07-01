<?php defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {

        parent::__construct();
        $this->load->model('Logins');
        $this->load->helper('menu_helper');
        $this->load->helper('file');
        $this->load->library('BPMALM');
    }

    public function index()
    {
        $this->load->view('general/login');
    }

    public function validarUsuario()
    {
        $data = $this->input->post();
        $user =$this->Logins->validarUsuario($data);
        if (!$user) {
            echo  json_encode(array('status'=>false));
        } else {
            $bpmUser = $this->bpmalm->getUser($user["nick"]);
            $user['userBpm'] = $bpmUser['id'];		
            $user['usrId'] = $bpmUser['id'];		
            $user['usrName'] = $bpmUser['firstname'];		
            $user['usrLastName'] = $bpmUser['lastname'];		
            $this->session->set_userdata('user_data', array($user));
            
            echo  json_encode(array('status'=>true));
        }
    }

    public function logout()
    {   
        $this->session->unset_userdata('user_data');
        redirect('Login');
    }
}