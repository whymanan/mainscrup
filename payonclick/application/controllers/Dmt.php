<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;
require APPPATH.'/libraries/REST_Controller.php';

class Dmt extends REST_Controller {
  
  public $client;

    function __construct() {
        parent::__construct();
        $this->data['serid'] = '7';
        $this->tnxType = 'dmtTx' ;
        $this->load->helper('api');
        $this->ClientId = 'doj5SPOCA001lgu' ;
        $this->Secret = 'XsE0Mh2RNkiSbanac632ef26e7c9d3' ;
        $this->load->model('common_model');
        $this->load->model('users_model');
        $this->load->model('commission_model');
        $this->load->library('jwt');
        
    }
    
    public function auth_post(){
        
        $data = $this->security->xss_clean($_POST);
        if(isset($data['partnerId']) && isset($data['Client-Secret'])  ){
        user_ckeck($data['partnerId']);
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://vitefintech.com/viteapi/auth/token',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('partnerId' => $data['partnerId'],'Client-Secret' => $data['Client-Secret'] ),
              CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=d05f63930209ffae3e14c75dd6c0008c505c9292'
              ),
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);
            echo $response;
            
        }else{
            
            $this->response(
                                            array(
                                            "status" => false,
                                            'statusCode' => 140,
                                            "msg" => "All field are needed"
                            ), REST_Controller :: HTTP_NOT_FOUND); 
            
        }

    }
    
    public function customers_get() {

    
          $data = $this->security->xss_clean($_GET);
          
        if (isset($data['mobile'])) {
    
              if (validate_phone_number($data['mobile'])) {
        
                $this->client = new Client();
        
                $url = 'https://vitefintech.com/viteapi/dmt/';
                
                #guzzle
                try {
                    $response = $this->client->request('GET', $url, [
                        
                        'query' =>[ 
                                  
                                  'mobile' => $data['mobile'],
                                 
                                 ]
                            
                            ]);
                     
        
                    $response = $response->getBody()->getContents();
                    
                } catch (GuzzleHttp\Exception\BadResponseException $e) {
                  #guzzle repose for future use
                  $response = $e->getResponse();
                  $responseBodyAsString = $response->getBody()->getContents();
                  print_r($responseBodyAsString);
                }
        
                  $response = json_decode($response);
        
                  if ($response->Status == 'SUCCESS') {
                    $this->data['response'] = $response;
                    $this->data['beneficiarylist'] = self::beneficiaryList($data['mobile']);
                    
                    echo json_encode([ 'status'=> true , 'statusCode' => 0 , 'msg' => "Success" , 'customer'=> $this->data['response'], 'beneficiaryDeatils'=>$this->data['beneficiarylist']  ]);
        
                  } elseif ($response->Status == 'FAILED') {
        
                    $this->data['response'] = $response;
                    $this->data['mobile'] = $data['mobile'];
                    
                    echo json_encode([ 'status'=> false , 'statusCode' => 110 , 'msg' => "Customer Not Found ! Register First " , 'customer'=> $this->data['response'] , "Mobile" => $this->data['mobile'] ]);
                    
                    
                  }else{
                      
                    echo json_encode([ 'status'=> false , 'statusCode' => 120 , 'msg' => 'Internal Error']);
                    
                  }
        
        
              }else{
                echo json_encode([ 'status'=> false , 'statusCode' => 130 , 'msg' => 'requeste not allowed please, check mobile number']);
              }
    
        }else{
             $this->response(
                                            array(
                                            "status" => false,
                                            'statusCode' => 140,
                                            "msg" => "All field are needed"
                            ), REST_Controller :: HTTP_NOT_FOUND);    
        }

    }

    private function beneficiaryList($mobile) {

      if (validate_phone_number($mobile)) {

        $this->client = new Client();

        $url = 'https://vitefintech.com/viteapi/dmt/beneficiarylist';
        #guzzle
        try {
             $response = $this->client->request('GET', $url, [
                
                'query' =>[ 
                          
                          'mobile' => $mobile,
                         
                         ]
                    
                    ]);

            $response = $response->getBody()->getContents();

        } catch (GuzzleHttp\Exception\BadResponseException $e) {
          #guzzle repose for future use
          $response = $e->getResponse();
          $responseBodyAsString = $response->getBody()->getContents();
          print_r($responseBodyAsString);
        }
        
        return json_decode($response);
        
      }else{
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed please, check mobile number']);
      }

    }

    public function addCustomer_post() {
   
        $data = $this->security->xss_clean($_POST);

        if (isset($data['phone_no']) && isset($data['first_name']) && isset($data['last_name']) && isset($data['customer_address']) && isset($data['title']) ) {
    
    
            if (validate_phone_number($data['phone_no'])) {
                
                $full_name = $data['title'] ." ". $data['first_name'] ." ". $data['last_name'];
                $customer = [
                  
                  'first_name' => $data['first_name'],
                  'last_name' => $data['last_name'],
                  'phone_no' => $data['phone_no'],
                  'address' => $data['customer_address'],
                  'created_at' => current_datetime(),
                ];
            
                $customeradd = [
                  
                  'first_name' => $data['first_name'],
                  'last_name' => $data['last_name'],
                  'phone_no' => $data['phone_no'],
                  'address' => $data['customer_address'],
                  'created_at' => current_datetime(),
                  'title' => $data['title']
                ];
    
                if (!$this->common_model->exists('user_detail', ['phone_no' => $customer['phone_no']])) {
    
                    if ($this->common_model->insert($customer, 'user_detail')) {
                        
                        $response = self::customeradd($customeradd);
                         
                        echo json_encode([ 'status' => true , 'statusCode' => 0 , 'msg' => 'Customer Registetion SuccessFul']);
                     
                    }
    
                }else{
                  
                    echo json_encode([ 'status' => false , 'statusCode' => 150, 'msg' => 'Number Already Register']);
                
                }
    
            }else{
                
                echo json_encode([ 'status' => false , 'statusCode' => 130, 'msg' => 'Requeste not allowed please, check mobile number']);
                
            }
    
        }else{
            
            $this->response(
                                            array(
                                            "status" => false,
                                            'statusCode' => 140,
                                            "msg" => "All field are needed"
                            ), REST_Controller :: HTTP_NOT_FOUND); 
            
        }
    }
  
    private function customeradd($customer) {
        
      $this->client = new Client();
      
      $url = 'https://vitefintech.com/viteapi/dmt/addCustomer';
      #guzzle
      try {
          $response = $this->client->request('POST', $url, [
                
            'form_params' => $customer,]);
            
          return $response->getBody()->getContents() ;
               

      } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
      }
    }

    public function addBeneficiary_post() {

          $data = $this->security->xss_clean($_POST);
          
        if (isset($data['mobile']) && isset($data['beneficiary_name']) && isset($data['beneficiary_account']) && isset($data['beneficiary_mobile']) && isset($data['beneficiary_ifsc_code']) && isset($data['beneficiary_bank']) ) {
    
            if (validate_phone_number($data['mobile']) && validate_phone_number($data['beneficiary_mobile']) ) {
              
                $beneficiary = [
                  'customer_mobile' => $data['mobile'],
                  'beneficiary_name' => $data['beneficiary_name'],
                  'beneficiary_account_number' => $data['beneficiary_account'],
                  'beneficiary_mobile' => $data['beneficiary_mobile'],
                  'beneficiary_ifsc' => $data['beneficiary_ifsc_code'],
                  'beneficiary_bank_name' => $data['beneficiary_bank'],
                  'created' => current_datetime(),
                ];
    
                if($this->common_model->insert($beneficiary, 'beneficiary_list')) {
                  
                    $response = self::Beneficiaryadd($beneficiary);
                        
                    echo json_encode([ 'status' => true , 'statusCode' => 0 , 'msg' => 'Beneficiary Added']);
                  
                }else{
                  
                    echo json_encode([ 'status' => false , 'statusCode' => 160, 'msg' => 'Beneficiary not add try again later']);
                    
                }
    
            }else{
                
                echo json_encode([ 'status' => false , 'statusCode' => 130, 'msg' => 'Requeste not allowed please, check customer mobile or beneficiary number']);
            
            }
    
        }else{
            
                $this->response(
                                            array(
                                            "status" => false,
                                            'statusCode' => 140,
                                            "msg" => "All field are needed"
                            ), REST_Controller :: HTTP_NOT_FOUND); 
            
        }
        
    }
    
    private function Beneficiaryadd($beneficiary) {
        
      $this->client = new Client();
      
      $data = [
                  'customer_mobile' => $beneficiary['customer_mobile'],
                  'beneficiary_name' => $beneficiary['beneficiary_name'],
                  'beneficiary_account_number' => $beneficiary['beneficiary_account_number'],
                  'beneficiary_mobile' => $beneficiary['beneficiary_mobile'],
                  'beneficiary_ifsc' => $beneficiary['beneficiary_ifsc'],
                  'beneficiary_bank_name' => $beneficiary['beneficiary_bank_name'],
                ];
      
      $url = 'https://vitefintech.com/viteapi/dmt/addBeneficiary';
      #guzzle
      try {
          $response = $this->client->request('POST', $url, [
                
            'form_params' => $data,]);
            
          return $response->getBody()->getContents() ;
               

      } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
      }
    }
    
    public function submitTransection_post() {
        
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: token, Content-Type, Accept,");
        $header = $this->input->request_headers();
        
        if(isset($header['Client-Secret']) && isset($header['Secret-Key']) && isset($header['Token']) ){
            
                $token =   $this->jwt->decode($header['Token'],$header['Secret-Key'] , ['HS256']);
                
                $key = find_Secret($token->partnerId , $header['Secret-Key']);
            
            $data = $this->security->xss_clean($_POST);
            
            if ( isset($data['api_key']) && isset($data['beneficiary_name']) && isset($data['beneficiary_ifsc']) && isset($data['mobile']) && isset($data['user_id']) && isset($data['member_id']) && isset($data['amount']) && isset($data['beneficiary_account_number']) && isset($data['beneficiaryid']) ) {
            
                user_ckeck($data['api_key']);
            
                if ($this->session->userdata('latitude')) {
                    $location = $this->session->userdata('latitude').'|'.$this->session->userdata('longitude');
                }
                
                $this->data['bal'] = $this->common_model->wallet_balance($data['user_id']);
                
                if (validate_phone_number($data['mobile']) ) {
                    
                    if( $this->data['bal']>0 && $this->data['bal']>$data['amount']){
                        
                        $transection_id = "MTnx".self::stan() ;
                
                        $saveTransection = [
                          'transection_id' =>  $transection_id,
                          'reference_number' => $transection_id,
                          'transaction_bank_account_no' => $data['beneficiary_account_number'] ,
                          'service_id' => $this->data['serid'],
                          'transection_type' => $this->tnxType,
                          'member_id' => $data['member_id'],
                          'transection_amount' => $data['amount'],
                          'api_requist' => 'dmtv2',
                        //   'location' => $location
                            'created' => date("Y-m-d h:i:sa"),
                            
                        ];
                
                        $beneficiaryDetails = $this->common_model->getBeneficiaryDetails($data['beneficiaryid']);
                        
                        $this->data['submitTransection'] = [
                            
                          
                           
                          
                                'amount' => $data['amount'],
                                'submerchant_id' => $data['member_id'] ,
                                'transactioonId' => $transection_id,
                                'account' => $data['beneficiary_account_number'],
                                'ifsc' => $data['beneficiary_ifsc'],
                                'name' => $data['beneficiary_name'],
                                'partnerId' => 'POCA001',
                                'phone' => $data['mobile'],
                                'email' => "vvnmsindia@gmail.com",
                            //   'location' => $location,
                        
                        ];
                        
                        // print_r($this->data['submitTransection']);exit;
                        
                        if ($beneficiaryDetails) {
                            $saveTransection['transection_bank_code'] = $beneficiaryDetails->beneficiary_ifsc;
                            $saveTransection['transection_bank_ifsc'] = $beneficiaryDetails->beneficiary_ifsc;
                            $saveTransection['reference_number'] =   $this->data['submitTransection']['transactioonId'];
                            $saveTransection['transection_mobile'] =  $beneficiaryDetails->beneficiary_mobile;
                        }else{
                            
                            $saveTransection['transection_bank_code'] = $data['beneficiary_ifsc'];
                            $saveTransection['transection_bank_ifsc'] = $data['beneficiary_ifsc'];
                            $saveTransection['reference_number'] =  $transection_id;
                            $saveTransection['transection_mobile'] = $data['mobile'];
                            
                        }
                
                
                          if ($transaction_primary = $this->common_model->insert($saveTransection, 'submit_transection')) {
                
                              $response = self::transection_service($header['Token']);
                            //   print_r($response);//exit;
                                if (isJson($response)) {
                                  $result = json_decode($response);
                                  if ($result->status == 'PENDING' || $result->status == 'SUCCESS' ) {
                                    $status = 1;
                                  }else{
                                    $status = 0;
                                  }
                                  $action = [
                                        'transection_status' =>  $status,
                                        'transection_msg' => $result->status,
                                        'transection_respcode' => $result->subCode,
                                        'transection_response' => $response,
                                  ];
                                  $this->common_model->update($action, 'primary_id', $transaction_primary, 'submit_transection');
                                  
                                    if ($result->status == 'PENDING' || $result->status == 'SUCCESS' ) {
                                      
                                        $userWallet = $this->common_model->get_user_wallet_balance($data['user_id']);
                                      
                                        $subcharge  = $this->common_model->sub_charge($data['amount'],$this->data['serid']);
                                        
                                        $service = 7;
                                        
                                        $total =  $data['amount'] + $subcharge->charge;
                                        
                                
                                    
                                        if ($userWallet != 'none') {
                                            $updateBalance = $userWallet->balance - $total;    //Deduct balance
                                            $updateWallet = [
                                                                'balance' => $updateBalance,
                                                            ];
                                            if($this->common_model->update($updateWallet, 'member_id',$data['user_id'], 'wallet')) { //update deducted balance
                                              $message = [
                                                'msg' => 'Your wallet balance debited Rs. ' . $total. ' available balance is ' . $updateBalance,
                                                'user_id' => $data['user_id']
                                              ];
                                                $logme = [
                                                      'wallet_id' => $userWallet->wallet_id,
                                                      'member_to' =>  $data['user_id'],
                                                      'amount' =>  $data['amount'],
                                                      'surcharge' => $subcharge->charge,
                                                      'refrence' =>  $this->data['submitTransection']['transactioonId'],
                                                      'service_id' => $service,
                                                      'stock_type'=> $this->tnxType,
                                                      'status' => 'success',
                                                      'balance' =>  $userWallet->balance,
                                                      'closebalance' => $updateBalance,
                                                     'type' => 'debit',
                                                     'mode' => 'DMT',
                                                     'bank' =>  'DMT',
                                                     'narration' => 'DMT Charge',
                                                     'date'=> date('Y-m-d'),
                                                    ];
                                
                                
                                                $this->common_model->insert($logme, 'wallet_transaction');
                                            }
                                        } 
                                        
                                        
                                        self::commition_distribute($data['user_id'],$service,$data['amount']);  
                                        
                                        $response =   '{"status":"PENDING","subCode":"201","message":"Transfer request pending at the Vitefintech","data":{"referenceId":"","utr":"","acknowledged":0}}';

                                        echo $response;
                                      
                                    } else {
                                      echo json_encode([ 'status' => false , 'statusCode' => 110 , 'msg' => 'Transaction Faild']);
                                  }
                                }else{
                                    
                                    echo json_encode([ 'status' => false , 'statusCode' => 120 , 'msg' => 'Transaction Faild Kandly Connect Admin']);
                                    
                                }
                          }else{
                            echo json_encode([ 'status' => false , 'statusCode' => 130 , 'msg' => 'Check Data']);
                          }
                      }else{
                          
                          echo json_encode([ 'status' => false , 'statusCode' => 150 , 'msg' => 'Insufficient Balance']);
                         
                      }
                      
                }else{
                    
                    echo json_encode([ 'status' => false , 'statusCode' => 130, 'msg' => 'Requeste not allowed please, check customer mobile number']);      
                }    
                      
            }else{
                    
                        $this->response(
                                            array(
                                            "status" => false,
                                            'statusCode' => 140,
                                            "msg" => "All field are needed"
                            ), REST_Controller :: HTTP_NOT_FOUND); 
                            
                    }
        
        }else{
                
                $this->response(
                                        array(
                                        "status" => False,
                                        "statusCode" => 202,
                                        "msg" => "Invalid Token or data"
                                        ), REST_Controller :: HTTP_NOT_FOUND
                                    ); 
                
            }              
        
    }
  

    private function token(){
        
       $this->client = new Client();
        
            $url = "https://vitefintech.com/viteapi/auth/token";
            
            $send = [
                
                'partnerId' => 'POCA01',
                'Client-Secret' => $this->Secret
                
                ];
            
          #guzzle
            try {
                $response = $this->client->request('POST', $url, [
                'form_params' => $send
              ]);
                print_r($response);exit;
              return $response->getBody()->getContents();
            
            } catch (GuzzleHttp\Exception\BadResponseException $e) {
              #guzzle repose for future use
              $response = $e->getResponse();
              $responseBodyAsString = $response->getBody()->getContents();
              echo $responseBodyAsString ;
          
            }
        
    }

    private function transection_service($token) {
        
        $this->client = new Client();
        
            $url = "https://vitefintech.com/viteapi/dmt/transaction";
            $header = [
                'Content-Type' => 'application/json',
                        'token' => $token, 
                        'Secret-key' => $this->Secret ,
                        'Client-Secret' =>  $this->Secret
                    ];
            $send = self::encode($this->Secret);
            
                $send = [
                        
                        'body'=>$send
                    
                    ];
                    
          #guzzle
            try {
                $response = $this->client->request('POST', $url, [
                'headers' => $header,
                'body' => json_encode($send)
              ]);
            
            
              return $response->getBody()->getContents();
            
            } catch (GuzzleHttp\Exception\BadResponseException $e) {
              #guzzle repose for future use
              $response = $e->getResponse();
              $responseBodyAsString = $response->getBody()->getContents();
              echo $responseBodyAsString ;
          
            }
    }
    
    private function encode($key){
        
        $plaintext = json_encode($this->data['submitTransection']);
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
        $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
        
        return $ciphertext;
        
    }

    public function dmtTForm() {

        if ($_GET) {
    
          $data = $this->security->xss_clean($_GET);
    
          if (isset($data['bId'])) {
    
            $this->data['bid'] = $data['bId'];
            
            
            $this->client = new Client();
    
            $url = 'https://vitefintech.com/viteapi/dmt/checkBeneficiary';
            #guzzle
            try {
                 $response = $this->client->request('GET', $url, [
                    
                    'query' =>[ 
                              
                              'id' => $this->data['bid'],
                             
                             ]
                        
                        ]);
    
                $response = $response->getBody()->getContents();
    
            } catch (GuzzleHttp\Exception\BadResponseException $e) {
              #guzzle repose for future use
              $response = $e->getResponse();
              $responseBodyAsString = $response->getBody()->getContents();
              print_r($responseBodyAsString);
            }
    
            $this->data['beneficiary'] = json_decode($response);
    
            echo $this->load->view('add-transection', $this->data, true);
    
          }else{
            echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
          }
    
        }

    }
    
    public function stan( ) {
        return mt_rand(99999999999, 999999999999);
    }
    
    public function check_get(){
        // print_r($_GET);exit;
        $data = $_GET;
        $service = 7;
       $ak = self::commition_distribute($data['user_id'],$service,$data['amount']);
       print_r($ak);
    }
    
    public function commition_distribute($id, $service,$transection) {
        $parentsList = self::checkparent($id);
        
        $i = 0;
        foreach ($parentsList as $key => $value) {
  
            if($key != 1){
                $commision = $this->commission_model->get_commision_by_role($value['role_id'], $service,$transection);
                if (!empty($commision)) {
                        $userWallet = $this->common_model->get_user_wallet_balance($value['user_id']);
            
                  if ($userWallet != 'none') {
                      
                    if($commision[$i]['c_flat'] == 0){
                        
                        $amountc = $commision[$i]['g_commission'];
                        
                        $updateBalance = $userWallet->balance + $commision[$i]['g_commission'];    // add commission
                        $updateWallet = [
                          'balance' => $updateBalance,
                        ];
                    }else{
                        
                        $amountc = $transection *  $commision[$i]['g_commission'] / 100;
                        
                         $updateBalance = $userWallet->balance + $amountc;    // add commission
                         $updateWallet = [
                          'balance' => $updateBalance,
                        ];
                        
                    }
                    if($this->common_model->update($updateWallet, 'member_id', $value['user_id'], 'wallet')) {
                      $message = [
                        'msg' => 'Your wallet balance credited ' . $amountc . ' available balance is ' . $updateBalance,
                        'user_id' => $value['user_id']
                      ];
                         $logme = [
                                      'wallet_id' => $userWallet->wallet_id,
                                      'member_to' =>  $value['user_id'],
                                      'member_from' =>  $value['parent'],
                                      'amount' =>  $transection,
                                    //   'surcharge' => $data['surcharge'],
                                      'refrence' =>  $this->data['submitTransection']['orderId'],
                                      'commission' =>  $amountc,
                                      'service_id' => $service,
                                      'stock_type'=> $this->tnxType,
                                      'status' => 'success',
                                      'balance' =>  $userWallet->balance,
                                      'closebalance' => $updateBalance,
                                     'type' => 'credit',
                                     'mode' => 'DMT',
                                     'bank' =>  'DMT',
                                     'narration' => 'DMT Commision',
                                     'date'=> date('Y-m-d'),
                                    ];
                        
                        
                      $this->common_model->insert($logme, 'wallet_transaction');
                    }
        
                  }else{
                    $message = [
                      'msg' => 'User Wallet not Found',
                      'user_id' => $value['user_id']
                    ];
                  }
                }else{
                  $message = [
                    'msg' => 'Commission Not Found',
                    'user_id' => $value['user_id']
                  ];
                }
            }else{
                
            }
          
        }
    }    

    public function checkparent($id, &$parents = array(), $level = 1) {
      $data = $this->users_model->get_parent_recharge($id);
        if (isset($data)) {
                  
                $parents[$level]['user_id'] = $data->user_id;
                $parents[$level]['member_id'] = $data->member_id;
                $parents[$level]['parent'] = $data->parent;
                $parents[$level]['role_id'] = $data->role_id;
                
                self::checkparent($data->parent, $parents, $level+1);
                  
        }     
      return $parents;
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
    
    public function varify_account_post()
    {
      $bank_details=$this->security->xss_clean($_POST);
      if(isset($bank_details['member_id']) && isset($bank_details['api_key']) && isset($bank_details['account']) && isset($bank_details['beneficiary_ifsc']) && isset($bank_details['name']) && isset($bank_details['beneficiary_id']))
      { 
        user_ckeck($bank_details['api_key']); 
        $user=$this->common_model->select_option($bank_details['member_id'],'member_id','user');
        $data['bal']=$this->common_model->wallet_balance($user[0]['user_id']);
        if($data['bal']>3.36){
              $data1=[
                 'accountNumber'=>trim($bank_details['account']),
                 'ifsc'=>trim($bank_details['beneficiary_ifsc']),
                 'bname'=>trim($bank_details['name']),
                 'purpose'=>'DMT Verification',
                 'api_key'=>$bank_details['api_key']
                ];
              $curl = curl_init();
              curl_setopt_array($curl, array(
                   CURLOPT_URL => 'https://vitefintech.com/viteapi/payu/accountVerify',
                   CURLOPT_RETURNTRANSFER => true,
                   CURLOPT_ENCODING => '',
                   CURLOPT_MAXREDIRS => 10,
                   CURLOPT_TIMEOUT => 0,
                   CURLOPT_FOLLOWLOCATION => true,
                   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                   CURLOPT_CUSTOMREQUEST => 'POST',
                   CURLOPT_POSTFIELDS =>$data1,
                   CURLOPT_HTTPHEADER => array(
                        'Content-Type=application/x-www-form-urlencoded'
                    ),
                ));
               $response = curl_exec($curl);
               curl_close($curl);
               $response1=json_decode($response);
               if(isset($response1->status) && $response1->status==0 && !isset($response1->statusCode)){
                          $this->db->select('*')->from('wallet')->where('member_id',$user[0]['user_id']);
                          $query = $this->db->get();
                          $wallet= $query->result_array();
                          $updateBalance=$wallet[0]['balance']-3.36;
                          $updateWallet = [
                                'balance' => $updateBalance,
                           ];
                   if($this->common_model->update($updateWallet, 'member_id', $user[0]['user_id'], 'wallet')){
                      if($response1->data->status=='Success'){
                          $this->data['submitTransection']=['varification'=>1,'beneficiary_name'=>$response1->data->beneficiaryName,'api_key'=>$bank_details['api_key'],'beneficiary_id'=>$bank_details['beneficiary_id']];
                          $response3 = self::varify_update();
                          $response4=json_decode($response3);
                         if($response4->status==true)
                         {
                           $response2='Varify';
                         }
                         else
                        {
                          $response2=$response4->msg;
                        }
                    }
                      elseif($response1->data->status=='Failure' && isset($response1->data->error))
                      {
                       $response2=$response1->data->error;
                      }
                      $log=[
                         'wallet_id' => $wallet[0]['wallet_id'],
                         'member_to' =>$user[0]['user_id'],
                         'stock_type' => 'Main Bal',
                         'status' => 'success',
                         'balance' =>  $data['bal'],
                         'closebalance' => $updateBalance,
                         'type' => 'debit',
                         'mode' => 'DMT Account Varification',
                         //   'bank' =>  $bank_details[0]['beneficiary_bank_name'],
                        'narration' => 'DMT Account Varification',
                        'trans_type'=>'add',
                        'amount'=>3.36,
                        'date' => date('Y-m-d'),
                        //   'payout_response'=>$response,
                       ];
                     $this->common_model->insert($log,'wallet_transaction');
                     echo $response;
                   }
  
                }
               else
               {
                      $this->response(
                                            array(
                                            "Response_code"=>6,
                                            "status" => false,
                                            "msg" => "SOME ISSUE CREATED"
                            ), REST_Controller :: HTTP_BAD_REQUEST);  
               }
            }
        else
        {
                             $this->response(
                                            array(
                                            "Response_code"=>0,
                                            "status" => false,
                                            "msg" => "Insufficience Balance"
                            ), REST_Controller :: HTTP_BAD_REQUEST);  
        }  
    }  
    else
    {
                           $this->response(
                                            array(
                                            "status" => false,
                                            "msg" => "All field are needed"
                            ), REST_Controller :: HTTP_NOT_FOUND);  
    }
   }
   
    public function varify_update()
    {
         $this->client = new Client();
         try {
         $response = $this->client->request('POST', "https://vitefintech.com/viteapi/Dmt/dmt_varification_account", [

         'form_params' => $this->data['submitTransection'],
        ]);
         return  $response->getBody()->getContents();
       } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
    }
  }
    
    
    
}