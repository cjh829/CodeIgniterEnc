<?php
namespace CJH\Request\Admin;

defined('BASEPATH') OR exit('No direct script access allowed');

use CJH\Request\RequestBase as RequestBase;

class AddUserRequest extends RequestBase
{
    public $id;
    public $password;
    public $group_id;
    public $is_enabled;

    public function getValidationRules(){
        return array(
            'id'=> 'required|between:5,50',
            'password'=> 'required|between:8,20',
            'group_id'=> 'required|integer|min:1',
        );
    }
    public function getValidationMessages(){
        return array(
            'required' => 'The :attribute field is required.',
            'between' => 'The :attribute must be between :min - :max.'
        );
    }
}