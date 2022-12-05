<?php

class Setting_model extends CI_Model{

  public $tbl_setting;
  public function __construct() {
        $this->load->database();
        $this->tbl_setting = 'setting';
  }

  public function settings() {
    $data = array();
    $result = $this->db->select('setting_name, setting_value')->get_where($this->tbl_setting, array('autoload' => 'yes'))->result();
    foreach ($result as $value) {
      $data[$value->setting_name] = $value->setting_value;
    }
    return (object)$data;
  }
   public function get_image($data){
       
            $this->db->select('name');
            $this->db->from('documents');
            $this->db->where("root",$data);
            $this->db->where("type","photo");
            $query = $this->db->get();
            $result = $query->row();
        // echo $result->name; exit;
            if ($query->num_rows()) {
            return $query->row()->name;
            } else {
            return false;
            }
        } 
  public function get_menu()
  {
    $this->db->select('menu_permission_id,data_menu');
    $this->db->from('menu_permission');
    $this->db->where("menu_name !=", "");
    $result = $this->db->get();
    return $result->result_array();
  }

  public function get_sub_menu($id)
  {
    $this->db->select('menu_permission_id,data_sub_menu,sub_menu_name');
    $this->db->from('menu_permission');
    $this->db->where("parent_id", $id);
    $result = $this->db->get();
    return $result->result_array();
  }

  public function get_role_permission($id)
  {
    $this->db->select('fk_menu_id');
    $this->db->from('role_permission');
    $this->db->where("fk_role_id ", $id);
    $result = $this->db->get();
    return $result->result_array();
  }
  

}
