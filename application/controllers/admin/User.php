<?php

use CJH\Request\Admin\AddUserRequest;

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

    public function lists($page = 1){
        $data = $this->m->adminuser->getPagedData($page,10);
        return view('',$data);
    }

    public function add(){
        $data = array('groups'=> $this->m->admingroup->getlist());
        return view('',$data);
    }

    public function add_submit(AddUserRequest $request){
        $data = (array)$request;//$this->input->post();
        $this->m->adminuser->add($data);
    
        smart_redirect('lists');
    }

    public function edit($id){
        $data = array('groups'=> $this->m->admingroup->getlist());
        $data['is_edit'] = TRUE;
        $data['vdata'] = $this->m->adminuser->get($id);

        $t = $this->m->adminuser->get($id);
        return view('add',$data); //share view
    }
    
    public function edit_submit(){
        $data = $this->input->post();
        $this->m->adminuser->edit($data);
    
        smart_redirect('lists');
    }
}