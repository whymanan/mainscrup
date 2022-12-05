<?php
class Transaction_model extends CI_Model {

    public $submit_transaction;
    public function __construct()  {
      $this->load->database();
      $this->submit_transaction = 'submit_transection';
    }

    public function get_transactionById($transaction_primary){
      return $this->db->get_where($this->submit_transaction, array('primary_id' => $transaction_primary))->row();
    }
    
 public function get_surcharge_slab($service){
      $this->db->select("*");
      $this->db->from("service_charge");
      $this->db->where("service_id",$service);
      $query = $this->db->get();
        return $query->result_array();
    }
}
