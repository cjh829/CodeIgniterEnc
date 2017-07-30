<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Philo\Blade\Blade;

if (!function_exists('view')) {
    function view($name = NULL, $data = [], $mergeData = []) {
        $CI =& get_instance();
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

if (!function_exists('get_paginate_data')) {
    function get_paginate_data($dbquery) {
        $data['data'] = $dbquery->result_array();

        $base_url = '//' . SITE_URL . '/' . smart_path();

        $current_page = $dbquery->paginateInfo['current_page'];

        $dbquery->paginateInfo['base_page_url'] = $base_url .'/';
        $dbquery->paginateInfo['first_page_url'] =  $base_url .'/1';
        if ($current_page == 1) 
            $dbquery->paginateInfo['first_page_url'] = 'javascript:void(0);';

        $dbquery->paginateInfo['last_page_url'] =  $base_url .'/'. $dbquery->paginateInfo['last_page'];
        if ($current_page == $dbquery->paginateInfo['last_page'])
             $dbquery->paginateInfo['last_page_url'] = 'javascript:void(0);';

        $dbquery->paginateInfo['prev_page_url'] =  !empty($dbquery->paginateInfo['prev_page']) ? $base_url .'/'. $dbquery->paginateInfo['prev_page'] : 'javascript:void(0);';
        $dbquery->paginateInfo['next_page_url'] =  !empty($dbquery->paginateInfo['next_page']) ? $base_url .'/'. $dbquery->paginateInfo['next_page'] : 'javascript:void(0);';

        $data['paginate_info'] = $dbquery->paginateInfo;

        return $data;
    }
}

if (!function_exists('checkboxvalue_transform')) {
    function checkboxvalue_transform(&$array, $field_name, $on_value = 1, $off_value = 0){
        if ($array[$field_name] == 'on') {
            $array[$field_name] = $on_value;
        } else {
            $array[$field_name] = $off_value; //add an off-value for unchecked;
        }
    }
}