<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Philo\Blade\Blade;

if (!function_exists('view')) {
    function view($name = NULL, $data = [], $mergeData = []) {
        $CI =& get_instance();
        //errormsg
        if (!array_key_exists('errormsg',$data)) {
            $errormsg = $CI->session->flashdata('errormsg');
            if (!empty($errormsg)) {
                $data['errormsg'] = $errormsg ;
            }
        }

        //menu
        if(isset($CI->session->user)) {
            $data['_menus'] = $CI->session->menus;

            $method = str_cut_tail($CI->router->method,'_submit');
            $currentRoute = strtolower($CI->router->class .'|'.$method);

            if (array_key_exists($currentRoute, $CI->session->aclmenumap)) {
                $data['_current_menu'] = $CI->session->aclmenumap[$currentRoute];
            } else {
                $data['_current_menu'] = null;
            }
        }

        $name = smart_path($name);
        if (!isset($CI->blade)) {
            $views = APPPATH . '/views';
            $cache = APPPATH . '/views_cached';
            $CI->blade = new Blade($views, $cache);
        }
        echo $CI->blade->view()->make($name, $data, $mergeData)->render();
    }
}

if (!function_exists('jsonview')) {
    function jsonview($data = [], $status = NULL, $msg = '') {
        if (empty($status) && array_key_exists('status',$data)){
            $result = $data;
        } else {
            $result = ['status'=>$status,'data'=>$data];
            if (!empty($msg)){
                $result['msg'] = $msg;
            }
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }
}