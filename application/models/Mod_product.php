<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_product extends CI_Model {

    function product_insert($data){
        $this->db->insert('product',$data);
        return $this->db->affected_rows();
    }

    function product_latest($limit){
        $this->db->select("*");
        $this->db->from("product");
        $this->db->order_by('id','desc');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

    function product_search($val){
        $this->db->select("*");
        $this->db->from("product");
        $this->db->where("product.name like '%" . $val . "%' ");
        $this->db->order_by('id','desc');
        $query = $this->db->get();
        return $query->result();
    }

    function product_by_shop_no_val($id_shop){
        $this->db->select("*");
        $this->db->from("product");
        $this->db->where("product.id_shop='".$id_shop."'");
        $this->db->order_by('id','desc');
        $query = $this->db->get();
        return $query->result();
    }

    function product_by_shop_with_val($id_shop,$val){
        $this->db->select("*");
        $this->db->from("product");
        $this->db->where("product.id_shop='".$id_shop."' and product.name like '%" . $val . "%' ");
        $this->db->order_by('id','desc');
        $query = $this->db->get();
        return $query->result();
    }

    function product_detail($where){
        $this->db->select("*");
        $this->db->from("product");
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }

    function product_update($id,$data){
        $this->db->where('id', $id);
        $this->db->update('product', $data);  
        return $this->db->affected_rows();
    }

}
