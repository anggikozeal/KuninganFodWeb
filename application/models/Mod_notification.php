<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_notification extends CI_Model {

    function notification_insert($data){
        $this->db->insert('notification',$data);
        return $this->db->affected_rows();
    }

    function notification_by_user($where){
        $this->db->select("*");
        $this->db->from("notification");
        $this->db->where($where);
        $this->db->order_by("id","desc");
        $this->db->limit(20);
        $query = $this->db->get();
        return $query->result();
    }

    function notification_detail($where){
        $this->db->select("*");
        $this->db->from("notification");
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }

    function notification_delete($where){
        $this->db->where($where);
        $this->db->delete('notification');
        return $this->db->affected_rows();
    }



}
