<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Philo\Blade\Blade;

if (!function_exists('view')) {
    function view($name = NULL, $data = [], $mergeData = []) {
        $name = smart_path($name);
        $CI =& get_instance();
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