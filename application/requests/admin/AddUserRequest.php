<?php
namespace CJH\Request\Admin;

defined('BASEPATH') OR exit('No direct script access allowed');

class AddUserRequest extends CJH\Request\RequestBase
{
    public $id;
    public $password;
    public $group_id;
    public $is_enabled;
}