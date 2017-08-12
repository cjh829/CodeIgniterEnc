<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//model helper
class model_adapter{

    private $_CI = null;

    function __construct() {
        $this->_CI = & get_instance();
    }

    //load model if not exists
    function __get($name) {
        if (!isset($this->_CI->$name)){
            $this->_CI->load->model($name);
        }
       
        return $this->_CI->$name;
    }

}