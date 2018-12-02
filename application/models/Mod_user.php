<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_user extends CI_Model {

    function is_registered($username){
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('username',$username);
        $query = $this->db->get();
        return $query->result();
    }
    
    function user_detail($where){
        $this->db->select("*");
        $this->db->from("user");
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }

    function register($data){
        $this->db->insert('user',$data);
        return $this->db->affected_rows();
    }

}
