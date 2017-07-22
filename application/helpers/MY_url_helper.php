<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('smart_path')) {
    function smart_path($path= null) {
        $CI =& get_instance();
        if (empty($path))
            $path = $CI->router->method;

        if (str_contains($path,'/')) {
            return $path;
        } else {
            $CI =& get_instance();
            return $CI->router->directory . $CI->router->class .'/'. $path;
        }
    }
}

if (!function_exists('smart_redirect')) {
    function smart_redirect($path= null) {
        $path = smart_path($path);
        redirect('//'.SITE_URL.'/'.$path);
    }
}