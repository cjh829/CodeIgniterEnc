<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admingroup_Model extends MY_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function getList(){
        return $this->db->get('adm_group')->result_array();
    }


}