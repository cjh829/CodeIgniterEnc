<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('model_adapter',null,'m');
    }

    public function _remap(...$params) {
        $method = $params[0];
        //return 404 just like CI default action
        if (! method_exists($this, $method)){
            show_404($this->route->directory.$this->route->class.'/'.$method);
        }

        //var_dump($params);

        $url_params = $params[1]; 
        $req_params = array_merge($this->input->get(),$this->input->post());

        //no params, call directly
        if (empty($url_params ) && empty($req_params)){
            //echo "call directly";
            return call_user_func_array(array(&$this, $method),array());
        }

        $rm = new ReflectionMethod($this, $method);
        $rm_params = $rm->getParameters();

        //not single-object-param, call with url params
        if (count($rm_params) != 1) {
            //echo "call with url params";
            return call_user_func_array(array(&$this, $method), $url_params);
        }
        $dataclass = $rm_params[0]->getClass();
        if (empty($dataclass) || !class_exists($dataclass->name)){
            //echo "call with url params";
            return call_user_func_array(array(&$this, $method), $url_params);
        }

        //only one object param, mapping it!
        $dataObject = mapping_to_object($dataclass->name,$req_params);
        return call_user_func_array(array(&$this, $method), array($dataObject));
    }
}