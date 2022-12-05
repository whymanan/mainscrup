<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;
require APPPATH.'/libraries/REST_Controller.php';

class Wallet extends REST_Controller {
  
  public $client;

    function __construct() {
        parent::__construct();
        $this->data['serid'] = '40';
        $this->load->helper('api');
        $this->load->model('common_model');
        $this->load->model('users_model');
        
    }
    
    public function deduct_post(){
            
              $params = json_decode(file_get_contents('php://input'), true);
              
              $data = $this->security->xss_clean($params);
       
          
               $logme = [
                            'member_to' => $data["member"] ,
                            'member_from' =>  1,
                            'amount' => $data["amount"],
                            
                            'type'=> "debit",
                            'refrence' => "Null",
                            'trans_type' => "deduct",

                            'mode' => "Wallet Transfer",
                            'stock_type'=> "Main Bal",
                            'bank'=> "Null",

                            'narration'=> $data["narration"],
                            'date'=> date('Y-m-d'),
                ];
              
             $id= $this->common_model->insert($logme, 'wallet_transaction');
             $user_wallet=$data['balance'] - (float)$data['amount']; 
             $this->common_model->update(array('balance' =>$user_wallet ), 'member_id',$data["member"], 'wallet');  
             
              json_output(200, array('status' => 200, 'message' => 'Request Successfull.'));
              
            
             
    }
            
                   
    public function addbalance_post(){

            $params = json_decode(file_get_contents('php://input'), true);
              
            $data = $this->security->xss_clean($params);
            
            if(isset($data['user_id']) && isset($data['amount']) && isset($data['remark']) && isset($data['mode']) && isset($data['stock_type']) && isset($data['bank'])){
                  
                if ($data) {
                
                    $wallet_id= $this->common_model->get_wallet_id($data['user_id']);
              
                    $parent = $this->users_model->get_parent_wallet($data['user_id']);
             
                
                    if($parent){
             
                            $logme = [
                                
                                    'wallet_id'=>$wallet_id, 
                                    'member_to' =>  $data['user_id'],
                                    'member_from' => $parent->parent ,
                                    
                                    'amount' => $data["amount"],
                                    'type'=> "credit",
                                    'narration'=> $data["remark"],
                                    
                                    'refrence' =>  "App_".self::stan(),
                                    'mode' => $data["mode"],
                                    'stock_type'=> $data["stock_type"],
                                    
                                    'bank'=> $data["bank"],
                                    'date'=> date("Y-m-d h:i:sa"),
                                ];
                    }else{
                    
                    $this->response(
                                        array(
                                        "status" => 0,
                                        "msg" => "Enter Correct User Id"
                                        ), REST_Controller :: HTTP_NOT_FOUND
                                    ); 
                    
                    }  
                    
                $id= $this->common_model->insert($logme, 'wallet_transaction');
              
                $this->response(
                                    array(
                                    "status" => true,
                                    "msg" => 'Request Successfull.',
                                    "Request Id" => $id,
                                    "data" => $logme,
                                    ), REST_Controller :: HTTP_OK
                                ); 
            }
            
                
            }else{
                
                $this->response(
                                    array(
                                    "status" => 0,
                                    "msg" => "All field are needed"
                                    ), REST_Controller :: HTTP_NOT_FOUND
                                ); 
                
            }
    }  
    
    public function stan( ) {
        
            return mt_rand(100000,999999);
    
    }
    
    public function get_ledger_get(){

        $uri = $this->security->xss_clean($_GET);
        
        if(isset($uri['user_id']) && isset($uri['role_id']) && isset($uri['api_key']) ){   
            
            user_ckeck($uri['api_key']);
            
            if(($uri['role_id'] == 98)){    
                
                if (!empty($uri) && $uri['user_id']) {
                    
                      $query = '';
                      $output = array();
                      $data = array();
                        if ($uri['user_id']) {
                           $query .= "SELECT wallet_transaction.*,u1.member_id as member1,u2.member_id as member2 from wallet_transaction
                                 left join user u1 on u1.user_id=wallet_transaction.member_to
                                 left join user u2 on u2.user_id=wallet_transaction.member_from where wallet_transaction.trans_type='add' AND u1.user_id=".$uri['user_id']." ";
                    
                        }
              
                    
                          $sql = $this->db->query($query);
                          $filtered_rows = $sql->num_rows();
                          $sql = $this->db->query($query);
                          $result = $sql->result_array();
                          
                        if($result){
                                     $this->response(
                                                array(
                                                "status" => true,
                                                "statusCode" => 0,
                                                "msg" => 'Request Successfull.',
                                                "data" => $result,
                                                ), REST_Controller :: HTTP_OK
                                            );
                        }else{
                             $this->response(
                                                array(
                                                "status" => true,
                                                "statusCode" => 110,
                                                "msg" => 'Request Successfull. Data Not Available ',
                                                ), REST_Controller :: HTTP_OK
                                            );
                        }
                        
                }else{
                
                $this->response(
                                    array(
                                    "status" => true,
                                    "statusCode" => 120,
                                    "msg" => "Something Missing"
                                    ), REST_Controller :: HTTP_NOT_FOUND
                                ); 
                }
            }else{
            
            $this->response(
                                    array(
                                    "status" => true,
                                    "statusCode" => 130,
                                    "msg" => "This Application Is Only For Retailers"
                                    ), REST_Controller :: HTTP_NOT_FOUND
                                ); 
            
            }    
        }else{
                
                $this->response(
                                    array(
                                    "status" => true,
                                     "statusCode" => 140,
                                    "msg" => "All field are needed"
                                    ), REST_Controller :: HTTP_NOT_FOUND
                                ); 
                
            }
            
    }
    
    public function wallet_balance_post(){

            $params = json_decode(file_get_contents('php://input'), true);
              
            $data = $this->security->xss_clean($params);
            
            if(isset($data['user_id']) && isset($data['api_key'])){
                
                user_ckeck($data['api_key']);
                  
                    $query = "Select balance,wallet_id from wallet where member_id = {$data['user_id']} " ;
                    
                        $sql = $this->db->query($query);
                 
                        $filtered_rows = $sql->num_rows();
                 
                        $sql = $this->db->query($query);
                 
                        $result = $sql->result_array(); 
                
                    if($result){
                        
                            $this->response(
                                                array(
                                                "status" => true,
                                                "statusCode" => 0,
                                                "msg" => 'Request Successfull.',
                                                "data" => $result,
                                                ), REST_Controller :: HTTP_OK
                                            );
                        
                    }else{
                        
                        $this->response(
                                            array(
                                            "status" => false,
                                            "statusCode" => 110,
                                            "msg" => "Wallet Not Found"
                                            ), REST_Controller :: HTTP_NOT_FOUND
                                        );
                        
                    }
                
            }else{
                
                $this->response(
                                    array(
                                    "status" => false,
                                    "status" => 140,
                                    "msg" => "All field are needed"
                                    ), REST_Controller :: HTTP_NOT_FOUND
                                ); 
                
            }
    }
    
    // Payout Start  here
    
    public function get_move_charge_get() {
        
        $data = $this->security->xss_clean($_GET);
        
        if( isset($data['api_key']) && isset($data['amount']) ){
        
            user_ckeck($data['api_key']);
        
            $charge = $this->common_model->get_charge_by_move_bank($this->data['serid'],$data['amount']);
            
            if($charge){
            
                $this->response(
                                array(
                                    "status" => true,
                                    "statusCode" => 0,
                                    "msg" => 'Success.',
                                    "Payout_charge" => $charge,
                                    ), REST_Controller :: HTTP_OK
                                );
        
            }else{
                
                $this->response(
                                array(
                                    "status" => false,
                                    "statusCode" => 110,
                                    "msg" => "Charge Not Found or Invalid Requset."
                                    ), REST_Controller :: HTTP_NOT_FOUND
                                );
                
            }
        
        }else{
                
                $this->response(
                                array(
                                    "status" => false,
                                    "status" => 140,
                                    "msg" => "All field are needed."
                                    ), REST_Controller :: HTTP_NOT_FOUND
                                ); 
                
        }
  
    }
   
    public function get_bank_list_get(){

        $data = $this->security->xss_clean($_GET);
        
        if (isset($data['api_key']) && isset($data['user_id']) ) {
            
            user_ckeck($data['api_key']);
                
                        $query = "SELECT * FROM `user_bank_details` WHERE `fk_user_id` = {$data['user_id']} ";
                
                        $sql = $this->db->query($query);
                 
                        $filtered_rows = $sql->num_rows();
                 
                        $sql = $this->db->query($query);
                 
                        $result = $sql->result_array(); 
                
                        if($result){
                        
                            $this->response(
                                                array(
                                                "status" => true,
                                                "statusCode" => 0,
                                                "msg" => 'Request Successfull.',
                                                "data" => $result,
                                                ), REST_Controller :: HTTP_OK
                                            );
                        
                        }else{
                        
                            $this->response(
                                            array(
                                            "status" => false,
                                            "statusCode" => 110,
                                            "msg" => "User Id Invalid or Bank Detail Not Available"
                                            ), REST_Controller :: HTTP_NOT_FOUND
                                        );
                        
                        }
              
        }else{
            
            $this->response(
                            array(
                                "status" => false,
                                "status" => 140,
                                "msg" => "All field are needed"
                                ), REST_Controller :: HTTP_NOT_FOUND
                            ); 
            
        }
    }
    
    public function widthdraw_post(){
        
        $params = json_decode(file_get_contents('php://input'), true);
        
        $data = $this->security->xss_clean($params);
         
            if ( isset($data['api_key']) && isset($data['user_id']) && isset($data['amount']) && isset($data['charge']) && isset($data['wallet_balance']) ) {
                
                 user_ckeck($data['api_key']);
                
                if($data["amount"]<$data['wallet_balance']){
                    
                    $userWallet = $this->common_model->bank_get($data["user_id"]);
                    
                    $user_wallet = $data['wallet_balance'] - (float)$data['amount'] - (float)$data['charge']; 
                    
                    
                       $logme = [
                           
                            'member_to' => $data["user_id"] ,
                            'member_from' =>  1,
                            'amount' => $data["amount"],
                            'type'=> "debit",
                            'refrence' => "Widthdraw_".self::stan(),
                            'trans_type' => "widthdraw",
                            'mode' => "Wallet Transfer",
                            'stock_type'=> "Main Bal",
                            'surcharge' => (float)$data['charge'],
                            'balance' => $data['wallet_balance'],
                            'closebalance' => $user_wallet,
                            'bank'=> $userWallet->bank_name,
                            'account_no' => $userWallet->account_no,
                            'narration'=> "Move To Bank Request",
                            'date'=> date("Y-m-d h:i:sa"),
                      
                        ];
                        
                        if( $id= $this->common_model->insert($logme, 'wallet_transaction') && $this->common_model->update(array('balance' =>$user_wallet ), 'member_id',$data['user_id'], 'wallet') )
                            $this->response(
                                            array(
                                                "status" => true,
                                                "statusCode" => 0,
                                                "msg" => 'Widthdraw Request Success.',
                                                ), REST_Controller :: HTTP_OK
                                            );
                        else
                        $this->response(
                                    array(
                                        "status" => false,
                                        "statusCode" => 120,
                                        "msg" => "Something Went Wrong"
                                        ), REST_Controller :: HTTP_NOT_FOUND
                                    );
                        
        
                }else{
                    
                   $this->response(
                                    array(
                                        "status" => false,
                                        "statusCode" => 110,
                                        "msg" => "Your Wallet Balance Low"
                                        ), REST_Controller :: HTTP_NOT_FOUND
                                    );
                }
                
            }else{
                
                $this->response(
                                    array(
                                    "status" => false,
                                    "status" => 140,
                                    "msg" => "All field are needed"
                                    ), REST_Controller :: HTTP_NOT_FOUND
                                ); 
                
            }
             
    }
    
    // Payout End Here
   
                     
        
   
}
?>