<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class acl_hook {
    public function post_controller_constructor(){
        $CI =& get_instance();

        //define acl-scope from URL
        if ($CI->router->directory == 'admin/') {

            //define excludes (use | as separator)
            $noACLRoutes = array('home|login','home|recaptcha','home|logout');

            //mapping class|xxx_sumit to class|xxx
            $method = str_cut_tail($CI->router->method,'_submit');

            $currentRoute = strtolower($CI->router->class .'|'.$method);

            if (!in_array($currentRoute,$noACLRoutes)) {
                $CI->load->library('session');

                //login
                if (empty($CI->session->user)) {
                    smart_redirect('admin/home/login');
                }

                //acl from db, saved when login
                $acls = $CI->session->acls;
                //add default home
                $acls[] = 'home|index'; 
                
                if (!in_array($currentRoute,$acls)){
                    show_error('foribbden',403);
                }
            }
        }
    }
}