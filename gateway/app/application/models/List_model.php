<?php
class List_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }
    //-- insert function
    public function insert($data, $table)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }
    public function get_vendor()
    {
        $this->db->select('id,name');
        $this->db->from('user');
        $this->db->where("role_id", 1);
        $this->db->or_where("role_id", 2);
        $result = $this->db->get();
        return $result->result_array();
    }
    public function listViewById($id)
    {
        $this->db->select('*');
        $this->db->from('menu_permission');
        $this->db->where("parent_id", $id);
        $result = $this->db->get();
        return $result->result_array();
    }

    public function get_user_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where("user_id", $id);
        $result = $this->db->get();
        return $result->row();
    }

    public function get_user_detail_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('user_detail');
        $this->db->where("fk_user_id", $id);
        $result = $this->db->get();
        // print_r($this->db->last_query());
        // exit;
        return $result->row();
    }
    public function get_states()
    {
        $this->db->select('id,name');
        $this->db->from('states');
        $this->db->where("country_id", 101);
        $result = $this->db->get();
        return $result->result_array();
    }

    public function get_last_id()
    {
        $this->db->select('max(id) as id');
        $this->db->from('user');

        $result = $this->db->get();
        return $result->row()->id;
    }
    public function get_city($data)
    {

        $this->db->select('*');
        $this->db->from('cities');
        $this->db->where("state_id", $data);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }
}
