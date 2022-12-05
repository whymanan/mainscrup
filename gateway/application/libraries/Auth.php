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

class Auth
{
    /*
    |--------------------------------------------------------------------------
    | Auth Library
    |--------------------------------------------------------------------------
    |
    | This Library handles authenticating users for the application and
    | redirecting them to your home screen.
    |
    */
    protected $CI;

    public $user = 'user';
    public $user_detail = 'user_detail';
    public $data = null; 
    public $customer_id = null;
    public $primary_id = null;
    public $email = null;
    public $first_name   = null;
    public $middle_name   = null;
    public $last_name   = null;
    public $phone = null;
    public $kyc_status = null;
    protected $password = null;
    public $user_type = null;
    public $roles = 0;  // [ public $roles = null ] codeIgniter where_in() omitted for null.
    public $permissions = null;
    public $token = null;
    public $loginStatus = false;
    public $error = array();

    public function __construct()
    {
        $this->CI=& get_instance();
        $this->init();
    }

    /**
     * Initialization the Auth class
     */
    protected function init()
    {
        if ($this->CI->session->has_userdata("customer_id") && $this->CI->session->loginStatus) {
            $this->customer_id = $this->CI->session->customer_id;
            $this->phone = $this->CI->session->phone;
            $this->roles = $this->CI->session->roles;
            $this->loginStatus = true;
        }
        return;
    }

    /**
     * Handle Login
     *
     * @param $request
     * @return array|bool|void
     */
    public function login($request) {


        if ($this->validate($request)) {
            $this->data = $this->credentials($this->phone, $this->password);
            if ($this->data) {
                $result = $this->setUser();
                if ($result['status']) {

                  $this->set_token();
                  return $this->setUser();

                } else {

                  return $this->failedLogin($request);

                }
            } else {
                return $this->failedLogin($request);
            }
        }

        return false;
    }


     protected function set_token() {
        $token = [
          'ip_address' => get_client_ip(),
          'created' => current_datetime(),
          'token' => $this->CI->session->userdata('__ci_last_regenerate'),
          'user_id' => $this->primary_id
        ];
       $id = $this->CI->db->insert('token_details', $token);
       if($id) {
         return true;
       }else{
         return false;
       }
     }

     /**
      * Validate the login form
      *
      * @param $request
      * @return bool
      */


    protected function validate($request)
    {
        $this->CI->form_validation->set_rules('phone', 'Mobile Number', 'required');
        $this->CI->form_validation->set_rules('password', 'Password', 'required');
        if ($this->CI->form_validation->run() == TRUE) {
            $this->phone = $this->CI->input->post("phone", TRUE);
            $this->password = $this->CI->input->post("password", TRUE);
            return true;
        }

        return false;
    }

    /**
     * Check the credentials
     *
     * @param $phone
     * @param $password
     * @return mixed
     */
    protected function credentials($phone, $password) {
        $user = $this->CI->db->select("*")
        ->where("phone", $phone)
        ->where("user_status !=", 'deactive')
        ->get($this->user)
        ->row();
        if($user && password_verify($password, $user->password)) {
            return $user;
        }else{
          return false;
        }
    }

    /**
     * Setting session for authenticated user
     */
    protected function setUser()   {
        $this->primary_id = $this->data->user_id;
        $this->customer_id = $this->data->customer_id;
        $data =  $this->CI->session->set_userdata(array(
            "user_id" => $this->primary_id,
            "customer_id" => $this->data->customer_id,
            "user_name" => $this->get_name(),
         
            "phone" => $this->data->phone,
            "status" => $this->data->user_status,
            "kyc_status" => $this->data->kyc_status,
            "user_roles" => $this->data->role_id,
            'menu_permission' => $this->get_menu_config(),
            "loginStatus" => 1,
          ));
        $data['status'] = 1;
        return $data;
    }

    protected function get_name() {
      $result = $this->CI->db->select('CONCAT(first_name, " ",  last_name) AS user_name')->get_where($this->user_detail, array("fk_user_id" => $this->data->user_id));
      if ($result->num_rows() > 0) {
        return $result->row()->user_name;
      }else{
        return null;
      }
    }
public function get_menu_config()
  {
        $this->CI->db->select('fk_menu_id');
        $this->CI->db->from('role_permission');
        $this->CI->db->where("fk_role_id ", $this->data->role_id );
        $result = $this->CI->db->get();
        $role= $result->result_array();
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
     * Get the error message for failed login
     *
     * @param $request
     * @return array
     */
    protected function failedLogin($request)
    {
        $this->error["status"] = false;
        $this->error["failed"] = "phone or Password Incorrect.";

        return $this->error;
    }

    /**
     * Check login status
     *
     * @return bool
     */
    public function loginStatus()
    {
      return $this->loginStatus;
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function authenticate()
    {
        if (!$this->loginStatus()) {
            return redirect('login');
        }

        return true;
    }

    /**
     * Determine if the current user is authenticated. Identical of authenticate()
     *
     * @return bool
     */
    public function check($methods = 0)
    {
        if (is_array($methods) && count(is_array($methods))) {
            foreach ($methods as $method) {
                if ($method == (is_null($this->CI->uri->segment(2)) ? "index" : $this->CI->uri->segment(2))) {
                    return $this->authenticate();
                }
            }
        }
        return $this->authenticate();
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest()
    {
        return !$this->loginStatus();
    }

    /**
     * Read authenticated user ID
     *
     * @return int
     */
    public function customer_id()
    {
        return $this->customer_id;
    }

    /**
     * Read authenticated user Name
     *
     * @return string
     */
    public function phone()
    {
        return $this->phone;
    }

    /**
     * Read authenticated user roles
     *
     * @return array
     */
    public function roles()
    {
        return $this->roles;
    }

    /**
     * Read authenticated user permissions
     *
     * @return array
     */
    public function permissions()
    {
        return $this->permissions;
    }

    /**
     * Read the current user roles ID
     *
     * @param $customer_id
     * @return string
     */
    protected function userWiseRoles()
    {
        return array_map(function ($item) {
            return $item["role_id"];
        }, $this->CI->db->get_where("roles_users", array("user_id" => $this->customer_id()))->result_array());

    }
    protected function getUserRoles()
    {
        $temp = $this->CI->db->select("role_id")
            ->from("logme")
            ->where("logid",  $this->customer_id())
            ->get()->row();
            return $temp->role_id;
    }

    /**
     * Read the current user roles name
     *
     * @return array
     */
    public function userRoles()
    {
        return array_map(function ($item) {
            return $item["name"];
        }, $this->CI->db->select("roles.*")
            ->from("roles")
            ->join("roles_users", "roles.id = roles_users.role_id", "inner")
            ->where(array("roles_users.user_id" => $this->customer_id(),"roles.status" => 1, "deleted_at" => null))
            ->get()->result_array());
    }

    /**
     * Read current user permissions name
     *
     * @return mixed
     */
    public function userPermissions()
    {
        return array_map(function ($item) {
            return $item["name"];
        }, $this->CI->db
        ->select("permissions.*")
        ->from("permissions")
        ->join("permission_roles", "permissions.id = permission_roles.permission_id", "inner")
        ->where_in("permission_roles.role_id", $this->roles())
        ->where(array("permissions.status" => 1, "deleted_at" => null))
        ->group_by("permission_roles.permission_id")
        ->get()->result_array());
    }

    /**
     * Determine if the current user is authenticated for specific methods.
     *
     * @param array $methods
     * @return bool
     */
    public function only($methods = array())
    {
        if (is_array($methods) && count(is_array($methods))) {
            foreach ($methods as $method) {
                if ($method == (is_null($this->CI->uri->segment(2)) ? "index" : $this->CI->uri->segment(2))) {
                    return $this->route_access();
                }
            }
        }

        return true;
    }

    /**
     * Determine if the current user is authenticated except specific methods.
     *
     * @param array $methods
     * @return bool
     */
    public function except($methods = array())
    {
        if (is_array($methods) && count(is_array($methods))) {
            foreach ($methods as $method) {
                if ($method == (is_null($this->CI->uri->segment(2)) ? "index" : $this->CI->uri->segment(2))) {
                    return true;
                }
            }
        }

        return $this->route_access();
    }

    /**
     * Determine if the current user is authenticated to view the route/url
     *
     * @return bool|void
     */
    public function route_access()
    {
        $this->check();

        $routeName = (is_null($this->CI->uri->segment(2)) ? "index" : $this->CI->uri->segment(2)) . "-" . $this->CI->uri->segment(1);

        if ($this->CI->uri->segment(1) == 'home')
            return true;

        if($this->can($routeName))
            return true;

        return redirect('exceptions/custom_404', 'refresh');
    }

    /**
     * Checks if the current user has a role by its name.
     *
     * @param $roles
     * @param bool $requireAll
     * @return bool
     */
    public function hasRole($roles, $requireAll = false)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->checkRole($role) && !$requireAll)
                    return true;
                elseif (!$this->checkRole($role) && $requireAll) {
                    return false;
                }
            }
        }
        else {
            return $this->checkRole($roles);
        }
        // If we've made it this far and $requireAll is FALSE, then NONE of the perms were found
        // If we've made it this far and $requireAll is TRUE, then ALL of the perms were found.
        // Return the value of $requireAll;
        return $requireAll;
    }

    /**
     * Check current user has specific role
     *
     * @param $role
     * @return bool
     */
    public function checkRole($role)
    {
        return in_array($role, $this->userRoles());
    }

    /**
     * Check if current user has a permission by its name.
     *
     * @param $permissions
     * @param bool $requireAll
     * @return bool
     */
    public function can($permissions, $requireAll = false)
    {
        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                if ($this->checkPermission($permission) && !$requireAll)
                    return true;
                elseif (!$this->checkPermission($permission) && $requireAll) {
                    return false;
                }
            }
        }
        else {
            return $this->checkPermission($permissions);
        }
        // If we've made it this far and $requireAll is FALSE, then NONE of the perms were found
        // If we've made it this far and $requireAll is TRUE, then ALL of the perms were found.
        // Return the value of $requireAll;
        return $requireAll;
    }

    /**
     * Check current user has specific permission
     *
     * @param $permission
     * @return bool
     */
    public function checkPermission($permission)
    {
        return in_array($permission, $this->userPermissions());
    }

    /**
     * Logout
     *
     * @return bool
     */
    public function logout()
    {
        $this->CI->session->unset_userdata(array("customer_id", "phone", "loginStatus"));
        $this->CI->session->sess_destroy();

        return true;
    }
}
