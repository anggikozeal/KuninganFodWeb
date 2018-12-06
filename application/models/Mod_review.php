<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_review extends CI_Model {

    function review_insert($data){
        $this->db->insert('review',$data);
        return $this->db->affected_rows();
    }

    function review($where){
        $this->db->select("*");
        $this->db->from("review");
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }


}
