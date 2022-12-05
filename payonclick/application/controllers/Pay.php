<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;
require APPPATH.'/libraries/REST_Controller.php';

class Pay extends REST_Controller {
  
  public $client;

    function __construct() {
        parent::__construct();
        
      $this->tnxType = 'aepsTxn' ;
      $this->data['serid'] = 2;
      $this->data['active'] = 'AEPS2';
      
      
      $this->load->helper('api');
      
      $this->load->model('common_model');
      $this->load->model('users_model');
      $this->load->model('commission_model');
      
    }
    
    private function _prepare_class_validation(){
        
            $this->load->library('form_validation');
			 
            $this->form_validation->set_rules('latitude', 'latitude', 'required'); 
            $this->form_validation->set_rules('longitude', 'longitude', 'required');
            $this->form_validation->set_rules('mobilenumber', 'mobilenumber', 'required');
            $this->form_validation->set_rules('referenceno', 'referenceno', 'required');
            $this->form_validation->set_rules('ipaddress', 'ipaddress', 'required');
            $this->form_validation->set_rules('adhaarnumber', 'adhaarnumber', 'required');
            $this->form_validation->set_rules('accessmodetype', 'accessmodetype', 'required');
            $this->form_validation->set_rules('bankCode', 'bankCode', 'required');
            $this->form_validation->set_rules('pidData', 'pidData', 'required');
            $this->form_validation->set_rules('transcationtype', 'transcationtype', 'required');
            $this->form_validation->set_rules('member_id', 'member_id', 'required');
            $this->form_validation->set_rules('user_id', 'user_id', 'required');
            $this->form_validation->set_rules('api_key', 'api_key', 'required');
            $this->form_validation->set_rules('amount', 'amount', 'required');
			
    }
    
    public function submitTransection_post() {

        $data = $this->security->xss_clean($_POST);
        
        $this->_prepare_class_validation(); 
        
        if ($this->form_validation->run() === TRUE){

              user_ckeck($data['api_key']);
            
            $this->data['submitTransection'] = [
                      'latitude' => $data['latitude'],
                      'longitude' => $data['longitude'],
                      'mobilenumber' => $data['mobilenumber'],
                      'referenceno' => $data['referenceno'],
                      'ipaddress' => '148.66.132.29',
                      'adhaarnumber' =>$data['adhaarnumber'],
                      'accessmodetype' => 'SITE',
                      'nationalbankidentification' => $data['bankCode'],
                      'data' => $data['pidData'],
                      'pipe' => 'bank1',
                      'timestamp' => date("Y-m-d h:i:sa"),
                      'transcationtype' => $data['transcationtype'],
                      'amount' => $data['amount'],
                      'submerchantid' => $data['member_id'],
                      'key' => '0c32b64a516ff7da',
                      'iv' =>'26f61978092f49ca',
                      'transection_id' => $data['adhaarnumber'].self::utan($data['adhaarnumber']),
                      'api_key' => $data['api_key']
                      
                ];

            if ($this->data['submitTransection']['latitude']) {
                $location = $this->data['submitTransection']['latitude'].'|'.$this->data['submitTransection']['longitude'];
            }

            $this->data['saveTransection'] = [
             
                'transection_id' => $this->data['submitTransection']['transection_id'],
                'transection_type' => $data['transcationtype'],
                'member_id' => $this->data['submitTransection']['submerchantid'],
                'transection_amount' => $data['amount'],
                'transection_bank_code' => $data['bankCode'],
                'transection_bank_ifsc' => $data['bankCode'],
                'reference_number' =>   $this->data['submitTransection']['referenceno'],
                'transection_mobile' => $data['mobilenumber'],
                'api_requist' => 'aepsTxn',
                'location' => $location,
                'service_id' => $this->data['serid'],
                'created' => $this->data['submitTransection']['timestamp']
            
            ];

            if ($transaction_primary = $this->common_model->insert( $this->data['saveTransection'], 'submit_transection')) {
                $biomatric = [
                  'transection_primary' => $transaction_primary,
                  'device_type' => "APP",
                  'bio_data' => $data['pidData'],
                ];
                if ($this->common_model->insert($biomatric, 'biometric_data')) {
                    if($data['transcationtype'] == "BE" ){
                         $response = self::transection_service($this->data['submitTransection']);
                    }
                    elseif($data['transcationtype'] == "CW"){
                         $response = self::apescashwithdraw($this->data['submitTransection']);
                  
                    }elseif($data['transcationtype'] == "MS"){
                        $response = self::apesmini($this->data['submitTransection']);
                    }else{
                       $response = self::aadharpay($this->data['submitTransection']);
                    }
                    
                    
                    if (isJson($response)) {
                      $result = json_decode($response);
                      $result->transection_id = $this->data['saveTransection']['transection_id'];
                      $result->aadharno = $data['adhaarnumber'];
                      $result->mobile = $this->data['saveTransection']['transection_mobile'];
                      $result->transactionType = $this->data['saveTransection']['transection_type'];
                      $this->data['transaction'] = $result;
                      $action = [
                        'transection_status' =>  $result->status,
                        'transection_msg' => $result->message,
                        'transection_respcode' => $result->response_code,
                        'transection_response' => $response,
                      ];
                      $this->common_model->update($action, 'primary_id', $transaction_primary, 'submit_transection');
                      
                        if ($result->status == true ) {
                          
                            if($data['transcationtype'] == "CW"){
                              
                               self::commition_distribute( $data['user_id'],  $this->data['saveTransection']['transection_amount']);
                               
                            }
                            
                            if($data['transcationtype'] == "M"){
                            
                                $userWallet = $this->common_model->get_user_wallet_balance($data['user_id']);
                              
                                $subcharge  = $this->common_model->sub_charge($data['amount'],4);
                            
                                $service = 4;
                            
                                $total =  $data['amount'] - $subcharge->charge;
                            
                        
                        
                                if ($userWallet != 'none') {
                                    
                                    $updateBalance = $userWallet->balance + $total;    //Deduct balance
                                    $updateWallet = [
                                                        'balance' => $updateBalance,
                                                    ];
                                                    
                                    if($this->common_model->update($updateWallet, 'member_id',$data['user_id'], 'wallet')) { //update deducted balance
                                  
                                  
                                        $logme = [
                                              'wallet_id' => $userWallet->wallet_id,
                                              'member_to' =>  $data['user_id'],
                                              'amount' =>  $data['amount'],
                                              'surcharge' => $subcharge->charge,
                                              'refrence' =>  $this->data['submitTransection']['referenceno'],
                                              'service_id' => $service,
                                              'stock_type'=> $this->tnxType,
                                              'status' => 'success',
                                              'balance' =>  $userWallet->balance,
                                              'closebalance' => $updateBalance,
                                             'type' => 'credit',
                                             'mode' => 'Aadhar Pay',
                                             'bank' =>  'Aadhar Pay',
                                             'narration' => 'Aadhar Pay Transaction',
                                             'date'=> date("Y-m-d h:i:sa") ,
                                            ];
                        
                        
                                        $this->common_model->insert($logme, 'wallet_transaction');
                                    }
                                } 
                                
                                self::aadharpay_charge( $data['user_id'],  $this->data['saveTransection']['transection_amount']);
                                
                                echo json_encode(['status' => $result->status, 'statusCode' => $result->response_code , 'msg' => $result->message ,'data' => $response ] );
                         
                                
                            }
                      
                        }
                        
                         
                            $this->response(
                                            array(
                                                    'status' => $result->status,
                                                    'statusCode' => $result->response_code , 
                                                    'msg' => $result->message ,
                                                    'data' => $response
                                    ), REST_Controller :: HTTP_OK);            
                            
                            
                      
                    }else{
                        
                        echo json_encode(['status' => false, 'msg' => "Result Not In Json OR Not Available" ] );
                        
                    }
                }else{
                    
                    echo encode_json([ "statue" => false , "msg" => "BioMatric Data Not Correct" ]);
                    
                }
            
            }else{
                
                echo json_encode(['status' => false, 'msg' => 'Data Not Correct Check Data']);
                
            }
            
        }else{
            
            $this->response(
                                            array(
                                            "status" => false,
                                            "msg" => "All field are needed"
                            ), REST_Controller :: HTTP_NOT_FOUND);            
                            
        }
    }
  
    private function transection_service($data) {
      $this->client = new Client();
    
    
      $url = 'https://vitefintech.com/viteapi/pay/aepsbalanceenquiry';
      #guzzle
      try {
          $response = $this->client->request('POST', $url, [
            'form_params' => $data,
            
          ]);


          return $response->getBody()->getContents();

      } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
      }
    }
  
    private function apesmini($data) {
      $this->client = new Client();
    
    
      $url = 'https://vitefintech.com/viteapi/pay/aepsmini';
      #guzzle
      try {
          $response = $this->client->request('POST', $url, [
            'form_params' => $data,
            
          ]);


          return $response->getBody()->getContents();

      } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
      }
    }
    
    private function apescashwithdraw($data) {
      $this->client = new Client();
    
    
      $url = 'https://vitefintech.com/viteapi/pay/apescashwithdraw';
      #guzzle
      try {
          $response = $this->client->request('POST', $url, [
            'form_params' => $data,
            
          ]);


          return $response->getBody()->getContents();

      } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
      }
    }

    private function stan( ) {
     
        date_default_timezone_set("Asia/Calcutta");
        $today = date("H");
        $year = date("Y"); 
        $year =  $year;
        $year = substr( $year, -1);   
        $daycount =  date("z")+1;
        $ref = $year . $daycount. $today. mt_rand(100000, 999999);
        return $ref;
    }

    private function utan( $node ) {

        return mt_rand(100000, 999999);

    }
    
    private function commition_distribute($id,  $transection ) {
      
      $parentsList = self::checkparent($id);
      $i = 0;    
        // print_r($parentsList);exit;
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
            if($this->common_model->update($updateWallet, 'member_id', $value['user_id'], 'wallet')) {
              $message = [
                'msg' => 'Your wallet balance credited ' . $createdbalance . ' available balance is ' . $updateBalance,
                'user_id' => $value['user_id']
              ];
            //   $this->set_notification($message);

              $logme = [
                  'wallet_id' => $userWallet->wallet_id,
                  'member_to' =>  $value['user_id'],
                  'member_from' =>  $value['user_id'],
                  'amount' =>  $transection,
                  'refrence' =>  "AAEPSCASH_".self::walletrrn(),
                  'commission' =>  $nonflate,
                  'balance' => $userWallet->balance,
                  'closebalance' => $updateBalance,
                  'service_id' => $this->data['serid'],
                  'status' => 'success',
                  'stock_type'=> $this->tnxType,
                  'status' => 'success',
                  'type' => 'credit',
                  'mode' => 'Aeps Cash',
                  'bank' => 'Aeps',
                  'narration' => 'Aeps Commission Credit',
                  'date'=> date("Y-m-d h:i:sa"),
                
                ];
                
                $id = $this->common_model->insert($logme, 'wallet_transaction');
                // print_r($id);
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
    
    public function testCom_get(){
        // print_r(0);
        $result = self::commition_distribute(68,500);
        
        print_r($result);exit;
        
    }
    
    private function aadharpay_charge($id, $service,  $transection ) {

        $parentsList = self::checkparent($id);
        $i = 0; 
        
        foreach ($parentsList as $key => $value) {
  
            if($key != 1){
                $commision = $this->commission_model->get_commision_by_role($value['role_id'], $service,$transection);
                // pre($commision);exit;
                if (!empty($commision)) {
                        $userWallet = $this->common_model->get_user_wallet_balance($value['user_id']);
            
                  if ($userWallet != 'none') {
                      
                    if($commision->c_flat == 0){
                        
                        $amountc = $commision->g_commission;
                        
                        $updateBalance = $userWallet->balance + $commision->g_commission;    // add commission
                        $updateWallet = [
                          'balance' => $updateBalance,
                        ];
                        
                    }else{
                        
                        $amountc = $transection *  $commision->g_commission / 100;
                        
                         $updateBalance = $userWallet->balance + $amountc;    // add commission
                         $updateWallet = [
                          'balance' => $updateBalance,
                        ];
                        
                    }
                    
                                $logme = [
                                        'wallet_id' => $userWallet->wallet_id,
                                        'member_to' =>  $value['user_id'],
                                        'member_from' =>  $value['parent'],
                                        'amount' =>  $transection,
                                        //   'surcharge' => $data['surcharge'],
                                        'refrence' =>  "ACASH_".self::walletrrn().$this->data['submitTransection']['referenceno'],
                                        'commission' =>  $amountc,
                                        'service_id' => $service,
                                        'stock_type'=> $this->tnxType,
                                        'status' => 'success',
                                        'balance' =>  $userWallet->balance,
                                        'closebalance' => $updateBalance,
                                        'type' => 'credit',
                                        'mode' => 'Aadhar Pay',
                                        'bank' =>  'Aadhar Pay',
                                        'narration' => 'Aadhar Pay Commision',
                                        'date'=> date("Y-m-d h:i:sa"),
                                    ];
                        
                    
                    if($this->common_model->insert($logme, 'wallet_transaction')) {
                      $message = [
                        'msg' => 'Your wallet balance credited ' . $amountc . ' available balance is ' . $updateBalance,
                        'user_id' => $value['user_id']
                      ];
                      $this->set_notification($message);
                      
                        $this->common_model->update($updateWallet, 'member_id', $value['user_id'], 'wallet');
                           
                    }
        
                  }else{
                    $message = [
                      'msg' => 'User Wallet not Found',
                      'user_id' => $value['user_id']
                    ];
                    $this->set_notification($message);
                  }
                }else{
                  $message = [
                    'msg' => 'Commission Not Found',
                    'user_id' => $value['user_id']
                  ];
                  $this->set_notification($message);
                }
            }else{
                
            }
          
        }
    }
    
    private function aadharpay($data) {
      $this->client = new Client();
    
    
      $url = 'https://vitefintech.com/viteapi/pay/aadharpay';
      #guzzle
      try {
          $response = $this->client->request('POST', $url, [
            'form_params' => $data,
            
          ]);


          return $response->getBody()->getContents();

      } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
      }
    }

    
}