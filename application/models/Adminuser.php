<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminuser extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getPasswordHash($userid) {
        $r = $this->db->where('id',$userid)
                ->select('password')
                ->get('admuser',1)->result_array();
        if (empty($r) || count($r) != 1)
            return FALSE;
        
        return $r[0]['password'];
    }

}