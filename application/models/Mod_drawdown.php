<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_drawdown extends CI_Model {

    function drawdown_insert($data){
        $this->db->insert('drawdown',$data);
        return $this->db->affected_rows();
    }

    function drawdown($where){
        $this->db->select("*");
        $this->db->from("drawdown");
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }


    function drawdown_detail($id){
        $this->db->select("*");
        $this->db->from("drawdown");
        $this->db->where("id='".$id."'");
        $query = $this->db->get();
        return $query->result();
    }

    function drawdown_request_list(){
        $this->db->select("*");
        $this->db->from("drawdown");
        $this->db->where("status='ON_REQUEST'");
        $this->db->order_by("id","desc");
        $query = $this->db->get();
        return $query->result();
    }

}
