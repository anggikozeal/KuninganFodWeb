<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_shop extends CI_Model {

    function shop_insert($data){
        $this->db->insert('shop',$data);
        return $this->db->affected_rows();
    }

    function shop_detail($where){
        $this->db->select("*");
        $this->db->from("shop");
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }


}
