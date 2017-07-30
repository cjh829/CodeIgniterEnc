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

    public function edit($id){
        $this->load->model('adminuser');
        $this->load->model('admingroup');
        $data = array('groups'=> $this->admingroup->getlist());
        $data['is_edit'] = TRUE;
        $data['vdata'] = $this->adminuser->get($id);
        return view('add',$data); //share view
    }
    
    public function edit_submit(){
        $data = $this->input->post();
        $this->load->model('adminuser');
        $this->adminuser->edit($data);
    
        smart_redirect('lists');
    }
}