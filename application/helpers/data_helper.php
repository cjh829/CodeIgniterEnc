<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
        if (is_array($array[$field_name])) {
            foreach($array[$field_name] as $key => $value) {
                checkboxvalue_transform($array[$field_name], $key, $on_value, $off_value);
            }
        } else {
            if ($array[$field_name] == 'on') {
                $array[$field_name] = $on_value;
            } else {
                $array[$field_name] = $off_value; //add an off-value for unchecked;
            }
        }
    }
}

if (!function_exists('mapping_to_object')) {
    function mapping_to_object($className, &$dataArray){
        $obj = new $className;
        $mapping = array();
        if(in_array('_prop_class_map',get_class_methods($obj))) {
            $mapping = $obj->_prop_class_map();
        }
        $properties = get_object_vars($obj);
        foreach($dataArray as $name => $value) {
            if (array_key_exists($name,$properties)) {
                if (array_key_exists($name,$mapping)){
                    $obj->$name = mapping_to_object($mapping[$name],$value);
                } else {
                    $obj->$name = $value;
                }
            }
        }
        return $obj;
    }
}