<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

    public function lists($page = 1){
        $this->load->model('adminuser');
        $data = $this->adminuser->getPagedData($page,10);
        return view('',$data);
    }

    public function add(){
        $this->load->model('admingroup');
        $data = array('groups'=> $this->admingroup->getlist());
        return view('',$data);
    }

    public function add_submit(){
        $data = $this->input->post();
        $this->load->model('adminuser');
        $this->adminuser->add($data);
    
        smart_redirect('lists');
    }
    
}