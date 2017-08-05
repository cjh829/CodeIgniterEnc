<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); //default: load database
        $this->load->library('model_adapter',null,'m');
    }
}