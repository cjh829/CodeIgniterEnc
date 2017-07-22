<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminuser_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getPasswordHash($userid) {
        $r = $this->db->where('id',$userid)
                ->select('password')
                ->get('adm_user',1)->result_array();
        if (empty($r) || count($r) != 1)
            return FALSE;
        
        return $r[0]['password'];
    }

    public function getACLs($userid){
        $sql = "
            SELECT *
            FROM adm_acl aa
            JOIN 
            (
            SELECT aga.group_id, aga.acl_id
            FROM adm_group_acl aga
            JOIN adm_group ag ON aga.group_id = ag.id
            JOIN adm_user au ON ag.id = au.group_id
            WHERE au.id = ? 
            AND ag.is_enabled = 1 
            AND aga.is_enabled = 1
            ) bb ON aa.id = bb.acl_id
            WHERE aa.is_enabled = 1
        ";
        return $this->db
                    ->query($sql,array($userid))
                    ->result_array();
    }

}