<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Logins extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function validarUsuario($data)
    {
        $this->db->where('pass', $data['pass']);
        $this->db->where('nick', $data['nick']);
        return $this->db->get('sys_usuarios')->num_rows() > 0;
    }

}
