<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_admin extends CI_Model {

    function is_registered($username){
        $this->db->select('*');
        $this->db->from('admin');
        $this->db->where('username',$username);
        $query = $this->db->get();
        return $query->result();
    }

    function admin_detail($id){
        $this->db->select('*');
        $this->db->from('admin');
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->result();
    }

    function get_admin_by_username($data){
        $this->db->select('*');
        $this->db->from('admin');
        $this->db->where('username',$data);
        $query = $this->db->get();
        return $query->result();
    }
    
    function update_login_timestamp($id,$data){
        $this->db->where('id', $id);
        $this->db->update('admin', $data);  
    }
    
    function last_login($id){
        $query_str = "select admin.last_login from admin where id='".$id."'";
        $query = $this->db->query($query_str);
        return $query->result();
    } 
}
