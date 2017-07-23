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
        $data['lists'] = $dbquery->result_array();
        $data['paginate_info'] = $dbquery->paginateInfo;

        $base_url = '//' . SITE_URL . '/' . smart_path();

        $data['paginate_info']['base_page_url'] = $base_url .'/';
        $data['paginate_info']['first_page_url'] =  $base_url .'/1';
        $data['paginate_info']['last_page_url'] =  $base_url .'/'. $data['paginate_info']['last_page'];
        $data['paginate_info']['prev_page_url'] =  !empty($data['paginate_info']['prev_page']) ? $base_url .'/'. $data['paginate_info']['prev_page'] : '';
        $data['paginate_info']['next_page_url'] =  !empty($data['paginate_info']['next_page']) ? $base_url .'/'. $data['paginate_info']['next_page'] : '';

        return $data;
    }
}