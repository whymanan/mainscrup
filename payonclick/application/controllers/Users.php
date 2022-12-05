<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;
require APPPATH.'/libraries/REST_Controller.php';

class Users extends REST_Controller {
  
  public $client;

    function __construct() {
        parent::__construct();
        $this->load->helper('api');
        $this->load->model('common_model');
        $this->load->model('users_model');
        $this->load->model('commission_model');
    }
    
    public function getUserDetails_get(){
        
        $data = $this->security->xss_clean($_GET);
        
        if( isset($data['api_key']) && isset($data['member_id']) && isset($data['user_id'])  ){
            
              user_ckeck($data['api_key']);
              
            $member_id = $data['member_id'];  
                
            $query = "Select * From user where member_id = '$member_id' ";
                
            $sql = $this->db->query($query);
      			
      		$result = $sql->row();
      		
      		    if($result && $result->user_id == $data['user_id'] ){
      		        
      		        $profile=$this->common_model->get_image($data['user_id']);
      		        
                    if($profile){
                        
                      $this->data['profile'] = 'https://payonclick.co.in/gateway/uploads/photo/'.$profile;
                    
                    }else{
                    
                      $this->data['profile'] = "https://payonclick.co.in/gateway/assets/img/theme/avtar.png";
                    
                    }
      		        
      		        $this->response(
                                            array(
                                            "status" => true,
                                            "statusCode" => 0,
                                            "profileImage" => $this->data['profile'] ,
                                            "msg" => "User Found",
                                            "data" => $result,
                            ), REST_Controller :: HTTP_OK);
      		        
      		        
      		    }else{
      		        
      		        $this->response(
                                            array(
                                            "status" => false,
                                            "statusCode" => 110,
                                            "msg" => "User Not Found! Check Your User Id"
                            ), REST_Controller :: HTTP_NOT_FOUND);
      		        
      		    }
            
        }else{
            
                $this->response(
                                            array(
                                            "status" => false,
                                            "statusCode" => 140,
                                            "msg" => "All field are needed"
                            ), REST_Controller :: HTTP_NOT_FOUND); 
            
        }

        
        
    }
    
    public function checkPassword_post(){
        
        $params = json_decode(file_get_contents('php://input'), true);
        
        $data = $this->security->xss_clean($params);
        
        if(isset($data['api_key']) && isset($data['user_id']) && isset($data['password']) ) {
            
            user_ckeck($data['api_key']);
    
            $log =	$this->common_model->check_user($data['user_id']);
            
            if (password_verify($data['password'], $log->password)) {
                
              $this->response(
                                                array(
                                                "status" => true,
                                                "statusCode" => 0,
                                                "msg" => "Password Verified ! Now You Can Enter New Password To change"
                                ), REST_Controller :: HTTP_OK);
              
            }else{
                
              $this->response(
                                                array(
                                                "status" => false,
                                                "statusCode" => 110,
                                                "msg" => "Your Password is Incorrect Check Your Password and try again!"
                                ), REST_Controller :: HTTP_NOT_FOUND); 
              
            }
        
        }else{
                
                    $this->response(
                                                array(
                                                "status" => false,
                                                "statusCode" => 140,
                                                "msg" => "All field are needed"
                                ), REST_Controller :: HTTP_NOT_FOUND); 
                
            }
            
    }
    
    public function ChangePassword_post(){
        
        $params = json_decode(file_get_contents('php://input'), true);
        
        $data = $this->security->xss_clean($params);
        
        if(isset($data['api_key']) && isset($data['user_id']) && isset($data['password']) ) {
            
            user_ckeck($data['api_key']);
        
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
                
                if($this->common_model->resetpass($data['user_id'],$password)){
                    
                    $this->response(
                                                array(
                                                "status" => true,
                                                "statusCode" => 0,
                                                "msg" => "Password Change Successfull"
                                ), REST_Controller :: HTTP_OK);
                    
                }else{
                    
                     $this->response(
                                                array(
                                                "status" => true,
                                                "statusCode" => 0,
                                                "msg" => "Password Not Change Try Again Later"
                                ), REST_Controller :: HTTP_NOT_FOUND);
                    
                }
                
        }else{
                
                    $this->response(
                                                array(
                                                "status" => false,
                                                "statusCode" => 140,
                                                "msg" => "All field are needed"
                                ), REST_Controller :: HTTP_NOT_FOUND); 
                
            }
        
    }
    
    
}
?>