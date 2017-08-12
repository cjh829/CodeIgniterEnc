<?php
namespace CJH\Request;

defined('BASEPATH') OR exit('No direct script access allowed');

use Illuminate\Validation\Factory AS ValidatorFactory;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\MessageSelector;

class RequestBase
{
    //ref: https://stackoverflow.com/questions/28573889/illuminate-validator-in-stand-alone-non-laravel-application

    public function getValidationRules(){
        return array();
    }
    public function getValidationMessages(){
        return array();
    }
    
    public function toArray(){
        return (array)$this;
    }
    
    protected $_validator;
    
    public function isValid($updateValidator=FALSE){
        if (empty($this->_validator) || $updateValidator) {
            $this->_validator = validator($this->toArray(), $this->getValidationRules(), $this->getValidationMessages());
        }
        return !($this->_validator->fails());
    }

    public function validateFailMessages(){
        if (empty($this->_validator)) {
            return array();
        }
        $errors =  $this->_validator->errors();
        return $errors->all();
    }

}