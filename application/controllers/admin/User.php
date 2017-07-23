<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

    public function lists($page = 1){
        $this->load->model('adminuser');
        $data = $this->adminuser->getPagedData($page,10);
        return view('',$data);
    }
    
}