<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_transaction extends CI_Model {

    function transaction_insert($data){
        $this->db->insert('transaction',$data);
        return $this->db->affected_rows();
    }

    function transaction($where){
        $this->db->select("*");
        $this->db->from("transaction");
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }

    function transaction_detail_insert($data){
        $this->db->insert('transaction_detail',$data);
        return $this->db->affected_rows();
    }

    function transaction_detail_list($id_transaction){
        $this->db->select("*");
        $this->db->from("transaction_detail");
        $this->db->join('product', 'product.id = transaction_detail.id_product', 'inner');
        $this->db->where("id_transaction ='" . $id_transaction . "'");
        $query = $this->db->get();
        return $query->result();
    }

    function transaction_on_keranjang($id_shop, $id_user){
        $this->db->select("*");
        $this->db->from("transaction");
        $this->db->where("id_shop ='" . $id_shop . "' and id_user='" . $id_user . "'  and status='ON_KERANJANG'");
        $query = $this->db->get();
        return $query->result();
    }

    function transaction_detail_on_keranjang($id_transaction, $id_product){
        $this->db->select("*");
        $this->db->from("transaction_detail");
        $this->db->where("id_transaction ='" . $id_transaction . "' and id_product='" . $id_product . "'");
        $query = $this->db->get();
        return $query->result();
    }

    function transaction_detail_update($id,$data){
        $this->db->where('id_transaction', $id);
        $this->db->update('transaction_detail', $data);  
        return $this->db->affected_rows();
    }

    function transaction_update_status($id,$data){
        $this->db->where('id', $id);
        $this->db->update('transaction', $data);  
        return $this->db->affected_rows();
    }

    //SELECT DISTINCT(id_product) as idp , (select sum(qty) as qty from transaction_detail where id_product=idp) as jumlah_beli   FROM transaction_detail
    function transaction_hot(){
        $query = $this->db->query("SELECT DISTINCT(id_product) as idp , (select sum(qty) as qty from transaction_detail where id_product=idp) as jumlah_beli  FROM transaction_detail");
        return $query->result();
    }

    function transaction_history_insert($data){
        $this->db->insert('transaction_history',$data);
        return $this->db->affected_rows();
    }

    function transaction_history_list($id_shop){
        $query = $this->db->query(
            "SELECT 
                transaction.id, 
                transaction.id_shop, 
                transaction.status, 
                transaction_history.total, 
                transaction_history.datetime  
            from 
                transaction  
            inner join 
                transaction_history 
            on 
                transaction.id = transaction_history.id_transaction 
            where 
                transaction.id_shop = '" . $id_shop . "' and transaction.status='ON_FINISH'");
        return $query->result();
    }

    function transaction_history($where){
        $this->db->select("*");
        $this->db->from("transaction_history");
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }
    
}
