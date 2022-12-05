<?php
/**
 * Auth Helper
 *
 * @author       Firoz Ahmad Likhon <likh.deshi@gmail.com>
 * @purpose      Auth Helper
 */

if(! function_exists("isAdmin")) {

    /**
     * Check if current user is logged in.
     *
     * @return bool
     */
    function isAdmin($role) {
   		if ($role == ROLE_ADMIN) {
   			return true;
   		} else {
   			return false;
   		}
 	  }
}
if(! function_exists("isDistributor")) {

    /**
     * Check if current user is logged in.
     *
     * @return bool
     */
    function isDistributor($role) {
   		if ($role == ROLE_DISTRIBUTOR) {
   			return true;
   		} else {
   			return false;
   		}
 	  }
}
if(! function_exists("isExecutor")) {

    /**
     * Check if current user is logged in.
     *
     * @return bool
     */
    function isExecutor($role) {
   		if ($role == ROLE_EXECUTOR) {
   			return true;
   		} else {
   			return false;
   		}
 	  }
}
if(! function_exists("getKyc") ) {

    /**
     * Check if current user is logged in.
     *
     * @return bool
     */
    function getKyc($user) {
      $ci = get_instance();
      $ci->load->model('common_model');
      return $ci->common_model->returnKyc($user);
 	  }
}

if(! function_exists("pre")) {

    /**
     * Check if current user is logged in.
     *
     * @return bool
     */
    function pre($data) {
   		echo "<pre>";
      print_r($data);
      echo "</pre>";
 	  }
}

if(! function_exists("check")) {

    /**
     * Check if current user is logged in.
     *
     * @return bool
     */
    function check()
    {
        $ci = get_instance();
        if ($ci->session->userdata('loginStatus')) {
            return true;
        } else {
            return false;
        }
    }
}

if(! function_exists("can")) {

    /**
     * Check if current user has a permission by its name.
     *
     * @param $permissions
     * @return bool
     */
    function can($permissions)
    {
        $auth = new Auth();
        return $auth->can($permissions);
    }
}

if(! function_exists("hasRole")) {

    /**
     * Checks if the current user has a role by its name.
     *
     * @param $roles
     * @return bool
     */
    function hasRole($roles)
    {
        $auth = new Auth();
        return $auth->hasRole($roles);
    }
}

if(! function_exists("randomPassword")) {

    /**
     * Checks if the current user has a role by its name.
     *
     * @param $roles
     * @return bool
     */
     function randomPassword() {
       $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
       $pass = array(); //remember to declare $pass as an array
       $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
       for ($i = 0; $i < 8; $i++) {
           $n = rand(0, $alphaLength);
           $pass[] = $alphabet[$n];
       }
       return implode($pass); //turn the array into a string
     }
}
