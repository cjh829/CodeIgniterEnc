<?php
namespace CJH\Request;

defined('BASEPATH') OR exit('No direct script access allowed');

class RequestBase
{
    public function toArray(){
        return (array)$this;
    }
}