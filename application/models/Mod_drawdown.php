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

    function drawdown_finish_list(){
        $this->db->select("*");
        $this->db->from("drawdown");
        $this->db->where("status='ON_APPROVE'");
        $this->db->order_by("id","desc");
        $query = $this->db->get();
        return $query->result();
    }

    function drawdown_request_by_shop($id_shop){
        $this->db->select("*");
        $this->db->from("drawdown");
        $this->db->where("id_shop='" . $id_shop . "'");
        $this->db->order_by("id","desc");
        $this->db->limit(15);
        $query = $this->db->get();
        return $query->result();
    }

    function drawdown_update($id,$data){
        $this->db->where('id', $id);
        $this->db->update('drawdown', $data);  
        return $this->db->affected_rows();
    }

    function drawdown_delete($where){
        $this->db->where($where);
        $this->db->delete('drawdown');
        return $this->db->affected_rows();
    }
}
