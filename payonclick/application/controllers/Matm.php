<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;
require APPPATH.'/libraries/REST_Controller.php';

class Matm extends REST_Controller {
  
  public $client;

    function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        parent::__construct();
        $this->load->helper('api');
        $this->load->model('common_model');
        $this->data['serid'] = 8;
        $this->tnxType = "MATM";
        $this->load->model('commission_model');
        $this->load->model('users_model');

    }
    
    
    private function _prepare_class_validation(){
        
            $this->load->library('form_validation');
			 
            $this->form_validation->set_rules('latitude', 'latitude', 'required'); 
            $this->form_validation->set_rules('longitude', 'longitude', 'required');
            $this->form_validation->set_rules('mobileNumber', 'mobileNumber', 'required');
            $this->form_validation->set_rules('bankRrn', 'bankRrn', 'required');
            $this->form_validation->set_rules('txnid', 'txnid', 'required');
            $this->form_validation->set_rules('transAmount', 'transAmount', 'required');
            
            
            $this->form_validation->set_rules('status', 'status', 'required');
            $this->form_validation->set_rules('message', 'message', 'required');
            $this->form_validation->set_rules('response_code', 'response_code', 'required');
            $this->form_validation->set_rules('response', 'response', 'required');
            
            
            
            $this->form_validation->set_rules('cardType', 'cardType', 'required');
            $this->form_validation->set_rules('transactionType', 'transactionType', 'required');
            $this->form_validation->set_rules('submerchantid', 'submerchantid', 'required');
            $this->form_validation->set_rules('api_key', 'api_key', 'required');
            $this->form_validation->set_rules('user_id', 'user_id', 'required');
			
    }
    
    public function transactionBE_post(){
        
        $data = $this->security->xss_clean($_POST);
        
         $this->_prepare_class_validation(); 
        
            if ($this->form_validation->run() === TRUE){ 
                    
                user_ckeck($data['api_key']);
                            
                    $location = $data['latitude'].'|'.$data['longitude'];
                 
                        $save = [
                                
                                'member_id' => $data['submerchantid'],
                                'transection_id' => $data['txnid'],
                                'transection_type' => $data['transactionType'],
                                'card_type' =>     $data['cardType'],
                                'transection_amount' => 0,
                                'reference_number' =>   $data['bankRrn'],
                                'transection_mobile' => $data['mobileNumber'],
                                'api_requist' => 'matmTxn',
                                'location' => $location,
                    
                                'transection_status' =>  $data['status'],
                                'transection_msg' => $data['message'],
                                'transection_respcode' => $data['response_code'],
                                'transection_response' => $data['response'],
                                
                                'service_id' => $this->data['serid'],
                                'created' => date("Y-m-d h:i:sa")
                            ];
                            
                        $request = [
                                
                                'api_key' => $data['api_key'],
                                'submerchantid' => $data['submerchantid'],
                                'latitude' => $data['latitude'],
                                'longitude' => $data['longitude'],
                                'txnid' => $data['txnid'],
                                'transactionType' => $data['transactionType'],
                                'cardType' =>     $data['cardType'],
                                'bankRrn' =>   $data['bankRrn'],
                                'mobileNumber' => $data['mobileNumber'],
                                'transAmount' => $data['transAmount'] ,
                                'status' =>  $data['status'],
                                'message' => $data['message'],
                                'response_code' => $data['response_code'],
                                'response' => $data['response'],
                            ];    
                                   

                    if ($transaction_primary = $this->common_model->insert( $save, 'submit_transection')) {           
                        
                                $response = self::balanceEq($request);
                                
                            
                        $this->response(
                                    array(
                                        "status_code" => 0,
                                        "ststus" => true,
                                        "msg" => "Successfull Transaction"
                                        ), REST_Controller :: HTTP_OK); 
                        
                    
                    }else{
                        
                        $this->response(
                                    array(
                                        "status_code" => 120,
                                        "ststus" => false,
                                        "msg" => "Transaction Faild"
                                        ), REST_Controller :: HTTP_OK); 
                        
                    } 
                        
            } else {
                        $this->response(
                                    array(
                                        "status_code" => 110,
                                        "ststus" => false,
                                        "msg" => "All field are needed"
                                        ), REST_Controller :: HTTP_NOT_FOUND);            
                    }
                        
                        
    }
    
    public function transactionCW_post(){
        
        
        $data = $this->security->xss_clean($_POST);
        
         $this->_prepare_class_validation(); 
        
            if ($this->form_validation->run() === TRUE){ 
                    
                user_ckeck($data['api_key']);
                            
                    $location = $data['latitude'].'|'.$data['longitude'];
                    $member_id = $data['api_key'] ;
                 
                    $save = [
                                
                                'member_id' => $data['submerchantid'],
                                'transection_id' => $data['txnid'],
                                'transection_type' => $data['transactionType'],
                                'card_type' =>     $data['cardType'],
                                'transection_amount' => $data['transAmount'],
                                'reference_number' =>   $data['bankRrn'],
                                'transection_mobile' => $data['mobileNumber'],
                                'api_requist' => 'matmTxn',
                                'location' => $location,
                    
                                'transection_status' =>  $data['status'],
                                'transection_msg' => $data['message'],
                                'transection_respcode' => $data['response_code'],
                                'transection_response' => $data['response'],
                                
                                'service_id' => $this->data['serid'],
                                'created' => date("Y-m-d h:i:sa")
                            ];
                            
                            $request = [
                                
                                'latitude' => $data['latitude'],
                                
                                'longitude' => $data['longitude'],
                                
                                'mobileNumber' => $data['mobileNumber'],
                                
                                'transAmount' => $data['transAmount'],
                                
                                'bankRrn' =>   $data['bankRrn'],
                                
                                'txnid' => $data['txnid'],
                                
                                'status' =>  $data['status'],
                                
                                'message' => $data['message'],
                                
                                'response_code' => $data['response_code'],
                                
                                'response' => $data['response'],
                                
                                'cardType' =>     $data['cardType'],
                                
                                'transactionType' => $data['transactionType'],
                                
                                'submerchantid' => $data['submerchantid'],
                                
                                'api_key' => $data['api_key'],
                            ];  
        
        
                    if ($transaction_primary = $this->common_model->insert( $save, 'submit_transection')) {           
                        
                        
                        if ($save['transection_status'] == TRUE && $save['transection_respcode'] == 1 ) {
                                 
                                if($data['transactionType'] == "ATMCW"){
                                     
                                        $response = self::balanceCW($request);
                                     
                                    self::commition_distribute( $data['user_id'],  $data['transAmount']);
                                
                                }    
                        }
                        
                        $this->response(
                                    array(
                                        "status_code" => 0,
                                        "ststus" => true,
                                        "msg" => "Successfull Transaction"
                                        ), REST_Controller :: HTTP_OK); 
                        
                    
                    }else{
                        
                        $this->response(
                                    array(
                                        "status_code" => 120,
                                        "ststus" => false,
                                        "msg" => "Transaction Faild"
                                        ), REST_Controller :: HTTP_OK); 
                        
                    } 
                        
            } else {
                        $this->response(
                                    array(
                                        "status_code" => 110,
                                        "ststus" => false,
                                        "msg" => "All field are needed"
                                        ), REST_Controller :: HTTP_NOT_FOUND);            
                    }
                        
                        
    }
    
    private function commition_distribute($id,  $transection ) {
      
      $parentsList = self::checkparent($id);
      $i = 0;    
      
      foreach ($parentsList as $key => $value) {
        $commision = $this->commission_model->get_commision_by_role($value['role_id'], $this->data['serid'], $transection);
        
        
        if (!empty($commision)) {
            
              $userWallet = $this->common_model->get_user_wallet_balance($value['user_id']);
      
            if ($userWallet != 'none') {
                
                if($commision->c_flat == 0){
                    if($value['user_id'] == $id){
                        $nonflate = $transection * $commision->g_commission / 100;
                        
                        $updateBalance = $userWallet->balance + $nonflate + $transection;
                    }else{
                        
                        $nonflate = $transection * $commision->g_commission / 100;
                        
                        $updateBalance = $userWallet->balance + $nonflate;
                        
                    }
                
                }else{
                    
                    if($value['user_id'] == $id){
                        
                        $nonflate = $commision->g_commission;
                        $updateBalance = $userWallet->balance + $nonflate + $transection;
                    
                        
                    }else{
                        
                        $nonflate = $commision->g_commission;
                        $updateBalance = $userWallet->balance + $nonflate;
                        
                    }
                }
                
                if($value['user_id'] == $id){
                    
                    $createdbalance = $nonflate + $transection;
                
                }else{
                
                    $createdbalance = $nonflate;
                    
                }
                
                $updateWallet = [
                  'balance' => $updateBalance,
                ];
            
                $logme = [
                      'wallet_id' => $userWallet->wallet_id,
                      'member_to' =>  $value['user_id'],
                      'member_from' =>  $value['user_id'],
                      'amount' =>  $transection,
                      'refrence' =>  "MATMCASH_".self::walletrrn(),
                      'commission' =>  $nonflate,
                      'balance' => $userWallet->balance,
                      'closebalance' => $updateBalance,
                      'service_id' => $this->data['serid'],
                      'status' => 'success',
                      'stock_type'=> $this->tnxType,
                      'status' => 'success',
                      'type' => 'credit',
                      'mode' => 'MATM Cash',
                      'bank' => 'MATM',
                      'narration' => 'MATM Commission Credit',
                      'date'=> date("Y-m-d h:i:sa"),
                    
                    ];
                
                
            if($id = $this->common_model->insert($logme, 'wallet_transaction')) {
                
                $this->common_model->update($updateWallet, 'member_id', $value['user_id'], 'wallet');
                
                return $id;
              
            }

            }else{
              
                $this->response(
                                            array(
                                            "status" => false,
                                            "statusCode" => 130,
                                            "msg" => "User Wallet not Found"
                            ), REST_Controller :: HTTP_NOT_FOUND);
            }
          
        }else{
          
           $this->response(
                                            array(
                                            "status" => false,
                                            "statusCode" => 120,
                                            "msg" => "Commission Not Found Contact Admin"
                            ), REST_Controller :: HTTP_NOT_FOUND);
          
        }
      }
    }

    private function checkparent($id, &$parents = array(), $level = 1) {
        $data = $this->users_model->get_parent_aeps($id);
 
      if (isset($data)) {
        $parents[$level]['user_id'] = $data->user_id;
        $parents[$level]['member_id'] = $data->member_id;
        $parents[$level]['parent'] = $data->parent;
        $parents[$level]['role_id'] = $data->role_id;
   
       $ak = self::checkparent($data->parent, $parents, $level+1);
      }
      return $parents;
    }
    
    private function walletrrn(){
        
        return mt_rand(999999, 9999999);
        
    }

    private function balanceEq($request) {
        
      $this->client = new Client();
      
      $url = 'https://vitefintech.com/viteapi/matm/transactionBE';
      #guzzle
      try {
          $response = $this->client->request('POST', $url, [
                
            'form_params' => $request,]);

          return $response->getBody()->getContents() ;
               

      } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
      }
      
    }
    
    private function balanceCW($request) {
        
      $this->client = new Client();
      
      $url = 'https://vitefintech.com/viteapi/matm/transactionCW';
      #guzzle
      try {
          $response = $this->client->request('POST', $url, [
                
            'form_params' => $request,]);

          return $response->getBody()->getContents() ;
               

      } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
      }
      
    }    
    
    public function transectionlist_get() {

  		$uri = $this->security->xss_clean($_GET);
  		
  		$query =  '' ;

  		if (isset($uri['member_id']) && isset($uri['api_key']) && isset($uri['start']) && isset($uri['length'])  ) {
  		    
              $member_id = $uri['member_id'];
              
              $query .= "SELECT * FROM submit_transection WHERE service_id = {$this->data['serid']} AND member_id='$member_id'";
    
              $recordsFiltered = $this->users_model->row_count($query);
              
    
    	    if (!empty($_GET["search"]["value"])) {
                $query .= ' AND submit_transection.member_id LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= ' OR submit_transection.member_id LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= ' OR submit_transection.transection_mobile LIKE "%' . $_GET["search"]["value"] . '%" ';
           
            }
    
    	    if(isset($_GET["searchByCat"]) ) {
                $query .= " AND submit_transection.".$_GET["searchByCat"]." = '". $_GET["searchValue"]."'  ";
            }
            
            if(isset($_GET["date_from"]) ) {
                $query .= " AND submit_transection.created >= '". $_GET["date_from"]."'  ";
            }
            if(isset($_GET["date_to"]) ) {
                $query .= " AND submit_transection.created <= '". $_GET["date_to"]."'  ";
            }
    
      		    if(!empty($_GET["order"])){
    
      				$query .= 'ORDER BY '.$_GET['order']['0']['column'].' '.$_GET['order']['0']['dir'].' ';
    
      			}
    
      			else
    
      			{
    
      				$query .= 'ORDER BY submit_transection.primary_id DESC ';
    
      			}
    
    
    
      			if($_GET["length"] != -1){
    
      				$query .= 'LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];
    
      			}
    
      			$sql = $this->db->query($query);
      			
      			$result = $sql->result_array();
      			
      			if(!$result){
      			    
      			    echo json_encode([ 'status' => true  , 'msg' => 'Success' , 'data' => "Data Not Available" ]);
      			    
      			}else{
      			    
      			    echo json_encode([ 'status' => true  , 'msg' => 'Success' , 'data' => $result ]);
      			
      			}
    

  		}else{
  		    
  		    $this->response(
                                            array(
                                            "status" => false,
                                            "msg" => "All field are needed"
                            ), REST_Controller :: HTTP_NOT_FOUND); 
  		    
  		}

    }
    
    
}
