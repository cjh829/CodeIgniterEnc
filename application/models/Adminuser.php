<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminuser_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getPagedData($page=1, $perpage = 20){
        $query = $this->db
                        ->from('adm_user au')
                        ->join('adm_group ag','au.group_id = ag.id','left')
                        ->select('au.*,ag.name group_name')
                        ->paginate('',$page,$perpage);
        $data = get_paginate_data($query);
        return $data;
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
            SELECT *, ap.name AS parent_name
            , CASE aa.is_menu WHEN 0 THEN aa.parent_id ELSE aa.id END AS menu_id
            , CASE aa.is_menu WHEN 0 THEN ap.parent_id ELSE ap.id END AS menu_parent_id
            FROM adm_acl aa
            LEFT JOIN adm_acl ap ON aa.parent_id = ap.id
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
            AND LENGTH(aa.controller)>0
        ";
        $datas = $this->db
                    ->query($sql,array($userid))
                    ->result_array();

        $acls = array();
         //transform to map(for determine current menu)
        $ACLmenuMap = array();
        foreach($datas as $row) {
            $key = strtolower($row['controller'].'|'.$row['method']);
            $acls[] = $key;
            $ACLmenuMap[$key] = $row;
        }
        return array('acls'=>$acls,'aclmenumap'=>$ACLmenuMap);
    }

    public function getMenus($userid) {

        $sql = "
            SELECT aa.*
            , CASE aa.parent_id WHEN 0 THEN aa.sort ELSE CONCAT(ap.sort,',',aa.sort) END tree
            FROM adm_acl aa
            LEFT JOIN adm_acl ap ON aa.parent_id = ap.id
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
            AND aa.is_menu = 1
            ORDER BY tree
        ";
        $datas = $this->db
                    ->query($sql,array($userid))
                    ->result_array();

        //transform to tree(only two-level)
        $menutree = array();
        foreach($datas as $row) {
            if ($row['parent_id'] == 0) {
                $menutree[$row['id']] = array('me'=>$row, 'childs'=>array());
            } else {
                $menutree[$row['parent_id']]['childs'][$row['id']] = array('me'=>$row);
            }
        }
        return $menutree;
    }

}