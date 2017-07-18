<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('SITE_URL', $_SERVER['HTTP_HOST']);

class MY_Controller extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
    }
}