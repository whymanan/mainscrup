<?php

class Main extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->load->database();
  }

  public function get_gstfile(){

    $this->db->select("*");
    $this->db->from("gstfile");
    $query = $this->db->get();

    return $query->result();
  }

   public function get_sendfile_data(){
        $query = $this->db->query("SELECT g.member_id, u.phone ,u.user_id FROM gstfile g,user u WHERE g.member_id =u.member_id AND u.role_id='98'");
         return $query->result();

  }

 public function insert_gstfile($data = array()){

       return $this->db->insert("gstfile", $data);
   }

   public function insert_img($data_insert){
$this->db->insert('gstfile',$data_insert);
}

public function update_gstfile($remark,$sl_id){
 $this->db->where('sl_id', $sl_id);
         $this->db->update('state', $data);
}



  public function get_adminlist($role_id){
 $query = $this->db->query("SELECT * FROM `user` WHERE role_id ='$role_id'");
         return $query->result();
  }

   public function get_summarylist(){
 $query = $this->db->query("SELECT *,SUM(IF(type = 'debit', amount, 0)) as total_debit, SUM(IF(type = 'credit', amount, 0)) as total_credit FROM wallet_transaction LEFT JOIN user_detail ON user_detail.user_detail_id = wallet_transaction.member_to LEFT JOIN user ON user.user_id = wallet_transaction.member_to LEFT JOIN wallet ON wallet.member_id = wallet_transaction.member_to GROUP BY wallet_transaction.member_to;");
         return $query->result();
  }

   // public function insert_director($data = array()){

   //     return $this->db->insert("diectors", $data);
   // }
     function create($data) {
     return $this->db->insert_batch('diectors', $data);
  }

     function insert_Returnfile($data) {
     return $this->db->insert_batch('gstreturn', $data);
  }


}




?>