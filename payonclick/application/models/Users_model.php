<?php
/**
 * Author: Firoz Ahmad Likhon <likh.deshi@gmail.com>
 * Website: https://github.com/firoz-ahmad-likhon
 *
 * Copyright (c) 2018 Firoz Ahmad Likhon
 * Released under the MIT license
 *       ___            ___  ___    __    ___      ___  ___________  ___      ___
 *      /  /           /  / /  /  _/ /   /  /     /  / / _______  / /   \    /  /
 *     /  /           /  / /  /_ / /    /  /_____/  / / /      / / /     \  /  /
 *    /  /           /  / /   __|      /   _____   / / /      / / /  / \  \/  /
 *   /  /_ _ _ _ _  /  / /  /   \ \   /  /     /  / / /______/ / /  /   \    /
 *  /____________/ /__/ /__/     \_\ /__/     /__/ /__________/ /__/     /__/
 * Likhon the hackman, who claims himself as a hacker but really he isn't.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model
{
    /**
     * User constructor.
     */

    public $tbl_user;
    public $tbl_details;
    public function __construct()
    {
        parent::__construct();
        $this->tbl_user = "user";
        $this->tbl_details = "user_detail";
    }

    /**
     * Find data.
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $this->db->select('user.*, u.member_id as parent1,roles.role');
			$this->db->from($this->tbl_user);
			$this->db->where('user.user_id', $id);
			$this->db->JOIN('user u', 'u.user_id=user.parent', 'left');
			$this->db->JOIN('roles ', 'roles.roles_id=user.role_id', 'left');
			// $this->db->group_by('fabric.fabricName');
			// $this->db->order_by('fabric.id' ,'DESC');

			$rec = $this->db->get();
			//  echo $this->db->last_query($rec);exit;
			return $rec->row();
    }
    /**
     * Find data.
     *
     * @param $id
     * @return mixed
     */
    public function find_details($id)
    {
        return $this->db->get_where($this->tbl_details, array("fk_user_id" => $id))->row();
    }

    /**
     * Find all data.
     *
     * @return mixed
     */
    public function all()
    {
        return $this->db->get_where($this->tbl_user, array("deleted =" => 0))->result();
    }

    /**
     * Find all data.
     *
     * @return mixed
     */
    public function squad($parent)
    {
        return $this->db->get_where($this->tbl_user, array("parent" => $parent, "deleted" => 0))->result();
    }


public function exportList() {
            $this->db->select('user.member_id,user.email,user.phone,user.role_id,user.kyc_status,
            user_detail.first_name,user_detail.last_name,user_detail.aadhar,user_detail.pan,user_detail.gstno,user_detail.organisation
            ,user_detail.address,user_detail.state,city,user_detail.pincode,user_detail.home_address,,user_detail.home_state,user_detail.home_city,user_detail.home_area,
            user_detail.home_pincode,user_detail.area,user_detail.area,user_bank_details.account_holder_name,user_bank_details.account_no,
            user_bank_details.bank_name,user_bank_details.bank_name,user_bank_details.bank_name,user_bank_details.bank_name,user_bank_details.ifsc_code');
            $this->db->from('user');
            $this->db->join('user_detail','user_detail.fk_user_id =user.user_id');
            $this->db->join('user_bank_details','user_bank_details.fk_user_id =user.user_id');
            $query = $this->db->get();
            return $query->result_array();
        }
    /**
     * Insert data.
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        $data["password"] = password_hash($data["password"], PASSWORD_BCRYPT);

        return $this->db->insert('users', $data);
    }

    /**
     * Edit data.
     *
     * @param $data
     * @return mixed
     */
    public function edit($data)
    {
        return $this->db->update('users', $data, array('id' => $data['id']));
    }

    /**
     * Delete data.
     *
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        $data['deleted_at'] = date("Y-m-d H:i:s");

        return $this->find($id) ? $this->db->update('users', $data, array('id' => $id)) : 0;
    }

    /**
     * Insert roles.
     *
     * @param $user_id
     * @param $roles
     * @return int
     */
    public function addRoles($user_id, $roles) {
        $data["role_id"] = $roles;
        return $this->addRole($data, 'logid', $user_id);
    }

    /**
     * Insert role.
     *
     * @param $data
     * @return mixed
     */
    public function addRole($action, $field, $id){
        return $this->db->where($field,$id)->update($this->tbl_user, $action);
    }

    /**
     * Edit roles.
     *
     * @param $user_id
     * @param $roles
     * @return int
     */
    public function change_status($status, $id)
    {
   return $this->db->where("user_id", $id)->update("user", array("kyc_status" => $status));
    }
  
  public function get_user_bank($user_id)
  {
       
    return $this->db->get_where("user_bank_details", array("fk_user_id" => $user_id))->result_array();;

  }
  public function editRoles($user_id, $roles)
  {
    if ($this->deleteRoles($user_id, $roles))
      $this->addRoles($user_id, $roles);

    return 1;
  }
    /**
     * Delete roles.
     *
     * @param $user_id
     * @param $roles
     * @return mixed
     */
    public function deleteRoles($user_id, $roles)
    {

        return $this->db->delete('roles_users', array('user_id' => $user_id));
    }
  public function get_name($user_id)
  {
    $result = $this->db->select('CONCAT(first_name, " ",  last_name) AS user_name')->get_where($this->tbl_details, array("fk_user_id" => $user_id));
    if ($result->num_rows() > 0) {
      return $result->row()->user_name;
    } else {
      return null;
    }
  }
  public function get_bank($user_id)
  {
    $result = $this->db->select('*')->get_where("user_bank_details", array("fk_user_id" => $user_id));
    if ($result->num_rows() > 0) {
      return $result->result_array();
    } else {
      return null;
    }
  }
  public function get_parent_bank($user_id)
  {
       $this->db->select('parent');
    $this->db->from('user');
    $this->db->where("user_id ", $user_id);
    $result = $this->db->get();
    //pre($result);exit;
    return $this->db->get_where("user_bank_details", array("fk_user_id" => $result->row('parent')))->result_array();;

  }
  
  public function get_menu_config($role_id)
  {
    $this->db->select('fk_menu_id');
    $this->db->from('role_permission');
    $this->db->where("fk_role_id ", $role_id);
    $result = $this->db->get();
    $role = $result->result_array();
    //  pre($role);exit;
    if (!empty($role)) {
      foreach ($role as $row) {
        $data['role'][] = $row['fk_menu_id'];
      }
    } else {
      $data['role'][0] = "null";
    }
    return  $data['role'];
  }
    /**
     * Delete role.
     *
     * @param $user_id
     * @param $role_id
     * @return mixed
     */
    public function deleteRole($user_id, $role_id)
    {

        return $this->db->delete('roles_users', array('user_id' => $user_id, 'role_id' => $role_id));
    }

    /**
     * Find roles associated with user.
     *
     * @param $id
     * @return array
     */
    public function userWiseRoles($id)
    {
        return array_map(function($item){
            return $item["role_id"];
        }, $this->db->get_where("roles_users", array("user_id" => $id))->result_array());
    }

    /**
     * Find role details associated with user.
     *
     * @param $id
     * @return array
     */
    public function userWiseRoleDetails($id)
    {
        return array_map(function($item){
            $user = new User();
            return $user->findRole($item);
        }, $this->userWiseRoles($id));
    }

    /**
     * Find role.
     *
     * @param $id
     * @return mixed
     */
    public function findRole($id)
    {
        return $this->db->get_where("roles", array("id" => $id, "deleted_at" => null))->row(0);
    }

    public function nameById($id)
    {
        $result = $this->db->select('name')->get_where($this->tbl_user, array("logid" => $id));
        if ($result->num_rows()) {
          return $result->row()->name;
        }else{
          return null;
        }
    }


    // row Countable

    public function row_count($query){

      if (!empty($query)) {
        $result = $this->db->query($query);
        if ($result->num_rows()) {
          return $result->num_rows();
        }else{
          return 0;
        }

      }
    }

    public function totel_count($parent = '') { // 1 = all

      if (empty($parent)) {
        $sql = "SELECT kyc_status, COUNT(user_id) AS totel FROM user GROUP BY kyc_status UNION SELECT 'all', COUNT(user_id) AS totel FROM user";
        $result = $this->db->query($sql);
        return $result->result();
      } else {

        $sql = "SELECT kyc_status, COUNT(user_id) AS totel FROM user WHERE parent = {$parent} GROUP BY kyc_status
        UNION
        SELECT 'all', COUNT(user_id) AS totel FROM user WHERE parent = {$parent}";
        $result = $this->db->query($sql);
        return $result->result();
      }
    }

    public function userKycDetails($id){
      return $this->db->select('kyc')->get_where("user_details", array("user_id" => $id))->row();
    }
    public function userKycstatus($id){
      return $this->db->select('kyc')->get_where($this->tbl_user, array("logid" => $id))->row(0);
    }
    public function userKycUpdate($id, $action){
      return $this->db->where("logid", $id)->update($this->tbl_user, $action);
    }
    public function pendingUser(){
      return $this->db->get_where($this->tbl_user, ['role_id' => 2, 'status' => 'pending'])->num_rows();
    }
    public function KycPending($parent = 1) {
      return $this->db->get_where($this->tbl_user, ['parent' => $parent, 'kyc_status' => 'pending'])->result();
    }
    public function executorCount($parent){
      return $this->db->get_where($this->tbl_user, ['parent' => $parent, 'role_id' => 3])->num_rows();
    }
    public function executorPendingCount($parent) {
      return $this->db->get_where($this->tbl_user, ['parent' => $parent, 'role_id' => 3, 'kyc !=' => 'varify'])->num_rows();
    }
    public function executorVarifyCount($parent) {
      return $this->db->get_where($this->tbl_user, ['parent' => $parent, 'role_id' => 3, 'kyc' => 'varify'])->num_rows();
    }
    public function allExecutorCount(){
      return $this->db->get_where($this->tbl_user, ['role_id' => 3])->num_rows();
    }
    public function newExecutorCount(){
      return $this->db->get_where($this->tbl_user, ['role_id' => 3, 'kyc' => "NULL"])->num_rows();
    }
    public function pendingExecutorCount(){
      return $this->db->get_where($this->tbl_user, ['role_id' => 3, 'status' => 'pending'])->num_rows();
    }
    public function activeExecutorCount(){
      return $this->db->get_where($this->tbl_user, ['role_id' => 3, 'kyc' => "varify", 'status' => 'active'])->num_rows();
    }
    
        
        public function exportsingle($id) {
              $this->db->select('user.user_id,user.member_id,user.counter,user.email,user.phone,user.role_id,user.kyc_status,
            user_detail.first_name,user_detail.last_name,user_detail.aadhar,user_detail.pan,user_detail.gstno,user_detail.organisation
            ,user_detail.address,user_detail.state,city,user_detail.pincode,user_detail.home_address,,user_detail.home_state,user_detail.home_city,user_detail.home_area,
            user_detail.home_pincode,user_detail.area,user_detail.area,user_bank_details.account_holder_name,user_bank_details.account_no,
            user_bank_details.bank_name,user_bank_details.bank_name,user_bank_details.bank_name,user_bank_details.bank_name,user_bank_details.ifsc_code');
            $this->db->from('user');
            $this->db->join('user_detail','user_detail.fk_user_id =user.user_id');
            $this->db->join('user_bank_details','user_bank_details.fk_user_id =user.user_id');
            $this->db->where('kyc_status' , 'verify');
            $this->db->where('member_id' , "$id");
            $query = $this->db->get();
            return $query->result_array();
  }
  
  public function images($id){
      $this->db->select('name');
      $this->db->from('documents');
      $this->db->where('root',$id);
      $query = $this->db->get();
      return $query->result_array();
      
  }
  public function get_parent($id){
      $this->db->select('user_id, member_id, parent, role_id');
      $this->db->from('user');
      $this->db->where('member_id',$id);
      $this->db->where('kyc_status', 'verify');
      $query = $this->db->get();
      return $query->row();

  }
  
  public function get_parent_role($id){
      $this->db->select('role_id');
      $this->db->from('user');
      $this->db->where('user_id',$id);
      $query = $this->db->get();
      // echo $this->db->last_query();exit;
      return $query->row('role_id');
      
  }
      public function kycdata($id) {
              $this->db->select('user.user_id,user.member_id,user.counter,user.email,user.phone,user.role_id,user.kyc_status,
            user_detail.first_name,user_detail.last_name,user_detail.aadhar,user_detail.pan,user_detail.gstno,user_detail.organisation
            ,user_detail.address,user_detail.state,city,user_detail.pincode,user_detail.home_address,,user_detail.home_state,user_detail.home_city,user_detail.home_area,
            user_detail.home_pincode,user_detail.area,user_detail.area,user_bank_details.account_holder_name,user_bank_details.account_no,
            user_bank_details.bank_name,user_bank_details.bank_name,user_bank_details.bank_name,user_bank_details.bank_name,user_bank_details.ifsc_code');
            $this->db->from('user');
            $this->db->join('user_detail','user_detail.fk_user_id =user.user_id');
            $this->db->join('user_bank_details','user_bank_details.fk_user_id =user.user_id');
            $this->db->where('kyc_status' , 'verify');
            $this->db->where('user_id' , "$id");
            $query = $this->db->get();
            return $query->result_array();
  }
  
  public function get_parent_recharge($id){
      $this->db->select('user_id, member_id, parent, role_id');
      $this->db->from('user');
      $this->db->where('user_id',$id);
      $this->db->where('kyc_status', 'verify');
      $query = $this->db->get();
    //   pre($this->db->last_query());exit;
      return $query->row();

  }
  
  public function get_parent_aeps($id){
      $this->db->select('user_id, member_id, parent, role_id');
      $this->db->from('user');
      $this->db->where('user_id',$id);
      $this->db->where('kyc_status', 'verify');
      $query = $this->db->get();
      return $query->row();

  }
  
}
