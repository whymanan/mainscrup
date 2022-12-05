<?php
class Role_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_role_by_id($id)
    {
        $this->db->select('roles_id,role,role_status');
        $this->db->from('roles');
        $this->db->where("roles_id", $id);
        $result = $this->db->get();
        return $result->row();
    }
    public function get_role_priority($id)
    {
        $this->db->select('priority');
        $this->db->from('roles');
        $this->db->where("roles_id", $id);
        $result = $this->db->get();
        return $result->row();
    }
    public function get_sort_name_id($id)
    {
        $this->db->select('sort_name');
        $this->db->from('roles');
        $this->db->where("roles_id", $id);
        $result = $this->db->get();
        if ($result->num_rows()) {
            // code...
            return $result->row()->sort_name;
        } else {
            return "UNDFD";
        }
    }

 public function menu_exists($role,$id)
    {
        $this->db->select('sort_name');
        $this->db->from('roles');
        $this->db->where("fk_role_id", $role);
        $this->db->where("fk_menu_id", $id);
        $result = $this->db->get();
        if ($result->num_rows()>0) {
            // code...
            return 1;
        } else {
            return 0;
        }
    }
    public function get_role($name="",$priority)
    {
      $this->db->select('roles_id as id,role as text');
      $this->db->from('roles');
        $this->db->where('priority >=', $priority);
      $this->db->where('role LIKE', $name . '%');
      $result = $this->db->get();
      return $result->result();
    }
   

}
