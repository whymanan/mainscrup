<?php
class Commission_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }


    public function get_commision($request)  {
      $this->db->select('rate');
      $this->db->from('service_commission');
      $this->db->where('role_id', $request['role']);
      $this->db->where('slab_id', $request['slab']);
      $this->db->where('service_id', $request['service']);
      $result = $this->db->get();
      if ($result->num_rows()) {
        return $result->row()->rate;
      }else{
        return 0;
      }
    }
 public function get_surcharge($service) {
      $this->db->select('*');
      $this->db->from('service_charge');
      $this->db->where('service_id', $service);
      $result = $this->db->get();
      return $result->result_array();
    }


    public function get_list($service, $baseRole) {
      $this->db->select('*');
      $this->db->from('service_commission');
      $this->db->where('role_id', $baseRole);
      $this->db->where('service_id', $service);
      $result = $this->db->get();
      return $result->result_array();
    }

    public function get_service_name($service_id) {
        $this->db->select('name');
        $this->db->from('services');
        $this->db->where('id', $service_id);
        $result = $this->db->get();
       if ($result->num_rows() == 1) {
           return $result->row()->name;
            } else {
            return;
            }

    }

    function get_commision_by_role($role, $service, $range) {
          $this->db->select('g_commission, max_commission, c_flat');
          $this->db->from('service_commission');
          $this->db->where('service_id',$service);
          $this->db->where('role_id',$role);
          $this->db->where('start_range <=',$range);
          $this->db->where('end_range >=',$range);
          $query = $this->db->get();
        //   pre( $this->db->last_query());exit;
          return $query->result_array();
       }
    
    function get_commision_by_role_recharge($role, $service, $range,$operator) {
          $this->db->select('g_commission, max_commission, c_flat');
          $this->db->from('service_commission');
          $this->db->where('service_id',$service);
          $this->db->where('role_id',$role);
          $this->db->where('operator',$operator);
          $this->db->where('start_range <=',$range);
          $this->db->where('end_range >=',$range);
          $query = $this->db->get();
        //   pre( $this->db->last_query());exit;
          return $query->result_array();
       }
       
        function get_commision_by_role_bill($role, $service, $range) {
          $this->db->select('g_commission, max_commission, c_flat');
          $this->db->from('service_commission');
          $this->db->where('service_id',$service);
          $this->db->where('role_id',$role);
          $this->db->where('start_range <=',$range);
          $this->db->where('end_range >=',$range);
          $query = $this->db->get();
        //   pre( $this->db->last_query());exit;
          return $query->result_array();
       }


}
