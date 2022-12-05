<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;
require FCPATH.'vendor/autoload.php';
   
use PhpOffice\PhpSpreadsheet\Spreadsheet;
   
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DmtvtwoController extends Vite {


  public $data = array();

  public $client;


  public function __construct() {
      parent::__construct();
      $this->data['active'] = 'DMT';
      $this->data['serid'] = '7';
      $this->tnxType = 'dmtTx' ;
      $this->load->model('common_model');
      $this->load->model('users_model');
      $this->ClientId = 'doj5SPOCA001lgu' ;
      $this->Secret = 'XsE0Mh2RNkiSbanac632ef26e7c9d3' ;
      $this->load->model('commission_model');
      $this->data['breadcrumbs'] = [array('url' => base_url('dashboard'), 'name' => 'Dashboard')];
      $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));
  }

  public function index() {
    $this->data['param'] = $this->paremlink('add'); 
    $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));
    //pre($this->data['bal']);exit;
    $this->data['main_content'] = $this->load->view('index', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }

  public function add() {
    $this->data['param'] = $this->paremlink('/');
    $this->data['main_content'] = $this->load->view('add', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }

public function history() {
    $this->data['param'] = $this->paremlink('/');
    $this->data['member_list']=self::memberselect();
    $this->data['main_content'] = $this->load->view('list', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }

public function surcharge() {
    $this->data['param'] = $this->paremlink('/');
     $this->data['charge'] =$this->commission_model->get_surcharge(7);
     //pre($this->data['charge']);exit;
    $this->data['main_content'] = $this->load->view('subcharge/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('subcharge/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  
  public function customers() {

    if ($_GET) {

      $data = $this->security->xss_clean($_GET);

      if (isset($data['mobile']) && validate_phone_number($data['mobile'])) {

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
            echo $this->load->view('customer-details', $this->data, true);

          } elseif ($response->Status == 'FAILED') {

            $this->data['response'] = $response;
            $this->data['mobile'] = $data['mobile'];
            echo $this->load->view('add', $this->data, true);
          }else{
            echo json_encode(['error' => 1, 'msg' => 'Internal Error']);
          }

        // }


      }else{
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed please, check mobile number']);
      }

    }

  }

   public function beneficiaryList($mobile) {

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

  public function addCustomer() {
   

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);

      if (isset($data['phone_no']) && validate_phone_number($data['phone_no'])) {
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
                 
                 $this->session->set_flashdata(
              array(
                'status' => 1,
                'msg' => "Internal Save Success"
              )
            );
             echo json_encode(['error' => 0, 'msg' => 'Internal Save Success']);
            }

          }else{
                  $this->session->set_flashdata(
              array(
                'status' => 0,
                'msg' => "Number Already Register"
              )
            );
            echo json_encode(['error' => 1, 'msg' => 'Number Already Register']);
          }

      }else{
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }
  }
  
    public function customeradd($customer) {
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

    public function addBeneficiary() {

        if ($_POST) {
    
          $data = $this->security->xss_clean($_POST);
    
    
          if (isset($data['mobile']) && validate_phone_number($data['mobile'])) {
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
                  
                     $this->session->set_flashdata(
                  array(
                    'status' => 1,
                    'msg' => "Internal Save Success"
                  )
                );
                  echo json_encode(['error' => 0, 'msg' => 'Internal Save Success']);
              }else{
                    echo json_encode(['error' => 1, 'msg' => 'beneficiary not add try again later']);
                  }
    
          }else{
            echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
          }
    
        }
    }
    
     public function Beneficiaryadd($beneficiary) {
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
    
  public function customerVerification($mobile, $otp) {

      if (validate_phone_number($mobile)) {
        $submitForms = [
          'customerOTP' => $otp,
          'customerMobile' => $mobile
        ];
        $submitForms = $this->security->xss_clean($submitForms);

        $this->client = new Client();

        $url = _SERVICE_API_V2_ . 'customerverification';
        #guzzle
        try {
            $response = $this->client->request('post', $url, [
              'headers' => [
                 'Authorization' => 'Bearer ' . $this->session->userdata('token'),
              ],
              'decode_content' => false,
              'form_params' => $submitForms,

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

          $response->error = 0;

          $varify = [
            'verified' => 1
          ];

          if ($this->common_model->update($varify, 'customer_mobile', $submitForms['customerMobile'], 'customer_details')) {
            $response->msg = 'Internal Update Success';
          }else{
            $response->msg = 'Internal Update FAILED';
          }


          echo json_encode($response);


        } elseif ($response->Status == 'FAILED') {

          $response->error = 1;
          $response->msg = 'Internal Action not Done';

          echo json_encode($response);

        }else{

          echo json_encode(['error' => 1, 'msg' => 'Internal Error']);

        }


      }else{

        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);

      }
      redirect(base_url() . 'dmtv2', 'refresh' );
  }

  public function beneficiaryVerification($mobile, $otp) {
      
      if (validate_phone_number($mobile)) {

        $beneficiaryId = $this->common_model->getBeneficiaryId($mobile);
        
        if ($beneficiaryId) {
          // code...
          
          $submitForms = [
            'customerOTP' => $otp,
            'customerMobile' => $mobile,
            'beneficiaryId' => $beneficiaryId
          ];
          $submitForms = $this->security->xss_clean($submitForms);

          $this->client = new Client();

          $url = _SERVICE_API_V2_ . 'beneficiaryverify';
          #guzzle
          try {
            $response = $this->client->request('post', $url, [
              'headers' => [
                'Authorization' => 'Bearer ' . $this->session->userdata('token'),
              ],
              'decode_content' => false,
              'form_params' => $submitForms,

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

            $response->error = 0;

            $varify = [
              'mobile_verify' => 1
            ];

            if ($this->common_model->update($varify, 'beneficiaryId', $beneficiaryId, 'beneficiary_list')) {
              $response->msg = 'Internal Update Success';
            }else{
              $response->msg = 'Internal Update FAILED';
            }

            echo json_encode($response);


          } elseif ($response->Status == 'FAILED') {

            $response->error = 1;
            $response->msg = 'Internal Action not Done';

            echo json_encode($response);

          }else{

            echo json_encode(['error' => 1, 'msg' => 'Internal Error']);

          }
        }else{

        }


      }else{

        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);

      }
  }
  
public function submitTransection() {

    if ($_POST) {

        $data = $this->security->xss_clean($_POST);
        
        if (isset($data['mobile']) && !empty($data['mobile'])) {
        
            if ($this->session->userdata('latitude')) {
                $location = $this->session->userdata('latitude').'|'.$this->session->userdata('longitude');
            }
            
            if( $this->data['bal']>0 && $this->data['bal']>$data['amount']){
                
                $transection_id = "MTnx".self::stan() ;
        
                $saveTransection = [
                  'transection_id' =>  $transection_id,
                  'reference_number' => $transection_id,
                  'transaction_bank_account_no' => $data['beneficiary_account_number'] ,
                  'service_id' => $this->data['serid'],
                  'transection_type' => $this->tnxType,
                  'member_id' => $this->session->userdata('member_id'),
                  'transection_amount' => $data['amount'],
                  'api_requist' => 'dmtv2',
                  'location' => $location,
                  'created' => date("Y-m-d h:i:sa"),
                ];
        
                $beneficiaryDetails = $this->common_model->getBeneficiaryDetails($data['beneficiaryid']);
                
                $this->data['submitTransection'] = [
                    
                  'amount' => $data['amount'],
                  'submerchant_id' => $this->session->userdata('member_id') ,
                  'transactioonId' => $transection_id,
                  'account' => $data['beneficiary_account_number'],
                  'ifsc' => $data['beneficiary_ifsc'],
                  'name' => $data['beneficiary_name'],
                  'partnerId' => 'POCA001',
                  'phone' => $data['mobile'],
                  'email' => "",
                //   'location' => $location,
                
                ];
                if ($beneficiaryDetails) {
                    $saveTransection['transection_bank_code'] = $beneficiaryDetails->beneficiary_ifsc;
                    $saveTransection['transection_bank_ifsc'] = $beneficiaryDetails->beneficiary_ifsc;
                    $saveTransection['reference_number'] =   $this->data['submitTransection']['orderId'];
                    $saveTransection['transection_mobile'] =  $beneficiaryDetails->beneficiary_mobile;
                }else{
                    
                     $saveTransection['transection_bank_code'] = $data['beneficiary_ifsc'];
                    $saveTransection['transection_bank_ifsc'] = $data['beneficiary_ifsc'];
                    $saveTransection['reference_number'] =  $transection_id;
                    $saveTransection['transection_mobile'] = $data['mobile'];
                    
                }
        
        
                  if ($transaction_primary = $this->common_model->insert($saveTransection, 'submit_transection')) {
        
                      $response = self::transection_service();
                        if (isJson($response)) {
                          $result = json_decode($response);
                          if ($result->status == 'PENDING') {
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
                          if ($result->status == 'PENDING') {
                              
                                $userWallet = $this->common_model->get_user_wallet_balance($this->session->userdata('user_id'));
                              
                                $subcharge  = $this->common_model->sub_charge($data['amount'],$this->data['serid']);
                                
                                $service = 7;
                                
                                $total =  $data['amount'] + $subcharge->charge;
                                
                        
                            
                                if ($userWallet != 'none') {
                                    $updateBalance = $userWallet->balance - $total;    //Deduct balance
                                    $updateWallet = [
                                                        'balance' => $updateBalance,
                                                    ];
                                    if($this->common_model->update($updateWallet, 'member_id',$this->session->userdata('user_id'), 'wallet')) { //update deducted balance
                                      $message = [
                                        'msg' => 'Your wallet balance debited Rs. ' . $total. ' available balance is ' . $updateBalance,
                                        'user_id' => $this->session->userdata('user_id')
                                      ];
                                         $this->set_notification($message);
                                        $logme = [
                                              'wallet_id' => $userWallet->wallet_id,
                                              'member_to' =>  $this->session->userdata('user_id'),
                                              'amount' =>  $data['amount'],
                                              'surcharge' => $subcharge->charge,
                                              'refrence' =>  $transection_id,
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
                                
                                
                                self::commition_distribute($this->session->userdata('user_id'),$service,$data['amount']);  
                                
                                // self::sms($data['amount'],$saveTransection['transection_mobile']);
                                
                                echo json_encode(['error' => 0, 'msg' => 'Transaction Successfully']);
                              
                            } else {
                              echo json_encode(['error' => 1, 'msg' => 'Transaction Faild']);
                          }
                        }else{
                            
                            echo json_encode(['error' => 1, 'msg' => 'Transaction Faild Kandly Connect Admin']);
                            
                        }
                  }else{
                    echo json_encode(['error' => 1, 'msg' => 'Check Data']);
                  }
              }else{
                  
                  echo json_encode(['error' => 1, 'msg' => 'Insufficient Balance']);
                 
              }
            }else{
                echo json_encode(['error' => 1, 'msg' => 'Mobile number Required']);
              }
    }
  }
  
    public function commition_distribute($id, $service,$transection) {
        
        $parentsList = self::checkparent($id);
        
        $i = 0;
        foreach ($parentsList as $key => $value) {
  
            if($key != 1){
                $commision = $this->commission_model->get_commision_by_role($value['role_id'], $service,$transection);
                // pre($commision);
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
                      $this->set_notification($message);
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

    public function checkparent($id, &$parents = array(), $level = 1) {
      $data = $this->users_model->get_parent_recharge($id);
        // if($data->parent != 1){
              if (isset($data)) {
                $parents[$level]['user_id'] = $data->user_id;
                $parents[$level]['member_id'] = $data->member_id;
                $parents[$level]['parent'] = $data->parent;
                $parents[$level]['role_id'] = $data->role_id;
                // echo $data['parent'];
                
               self::checkparent($data->parent, $parents, $level+1);
                  
              }
        // }      
      return $parents;
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

    private function token(){
        
       $this->client = new Client();
        
            $url = "https://vitefintech.com/viteapi/auth/token";
            
            $send = [
                
                'partnerId' => 'POCA001',
                'Client-Secret' => $this->Secret
                
                ];
            
          #guzzle
            try {
                $response = $this->client->request('POST', $url, [
                'form_params' => $send
              ]);
            
              return $response->getBody()->getContents();
            
            } catch (GuzzleHttp\Exception\BadResponseException $e) {
              #guzzle repose for future use
              $response = $e->getResponse();
              $responseBodyAsString = $response->getBody()->getContents();
              echo $responseBodyAsString ;
          
            }
        
    }

    private function transection_service() {
       $this->client = new Client();
        
            $url = "https://vitefintech.com/viteapi/dmt/transaction";
            $token = json_decode(self::token());
            
            $header = [
                'Content-Type' => 'application/json',
                        'token' => $token->token, 
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

    public function stan( ) {
        return mt_rand(99999999999, 999999999999);
    }

  public function utan( $node ) {

    return $node . '00' . round(microtime(true));

  }

  public function aepsBiometricForm() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);

      if (isset($data['bioMetric'])) {

        $this->data['bioMetric'] = $data['bioMetric'];

        $this->data['devices'] = []; // here fatch bioMetric devices list from database

        echo $this->load->view('add-biometric', $this->data, true);

      }else{
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }

  public function tHistory() {
    $this->data['breadcrumbs'] = [array('url' => base_url('aeps'), 'name' => 'AePS'), array('url' => base_url('thistory'), 'name' => 'Transection History')];
    $this->data['param'] = $this->paremlink('/');
    $this->data['main_content'] = $this->load->view('list', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }

   public function get_history() {
  		$uri = $this->security->xss_clean($_GET);

  		if (isset($uri['key']) && !empty($uri['key'])) {

  			$query = '';
  			
  			$output = array();
  			
  			$duid = $uri['key'];
  			
  			$list = $uri['list'];
  			
  			$data = array();
  			
        $service_id = $this->data['serid'];


        if (isAdmin($this->session->userdata('user_roles'))) {

          $query .= "SELECT * FROM submit_transection WHERE service_id = '{$service_id}' ";

          $recordsFiltered = $this->users_model->row_count($query);

        } else {

          $query .= "SELECT * FROM submit_transection WHERE service_id = '{$service_id}' AND member_id = '{$duid}' ";


          $recordsFiltered = $this->users_model->row_count($query);

        }

        if(!empty($uri['member']))
        {
           $query .=" AND member_id='".$uri['member']."' ";
        }
         if(!empty($uri['from']) && !empty($uri['to']))
        {
           $query.=" AND (CAST(`created` as date) BETWEEN '".$uri['from']."' AND '".$uri['to']."') "; 
        }
        if(!empty($uri['default_a']) && !empty($uri['default_v']))
        {
            $query .=" AND ".$uri['default_a']."='".$uri['default_v']."' ";
        }
         if(!empty($uri['status']))
              {  
                  if($uri['status']=="processing" or $uri['status']=="processed")
                  {
                       $query.=" AND transection_msg='".$uri['status']."' ";  
                  }
                  else
                  {
                      $query.=" AND (`transection_msg` NOT LIKE '%processed%' AND `transection_msg` NOT LIKE '%processing%') ";
                  }
                 
              }
  			switch ($list) {


  				case 'all':

  					break;

  				case 'Failed':

  					$query .= " AND submit_transection.transection_status = 0 ";

  					break;

  				case 'verify':

  					$query .= " AND submit_transection.transection_status = 1 ";



  					break;

  				default:

            echo json_encode(['error' => 1, 'msg' => 'request not allowed']);

            break;

  			}

  	    if (!empty($_GET["search"]["value"])) {
          $query .= ' AND( user.member_id LIKE "%' . $_GET["search"]["value"] . '%" ';
          $query .= ' OR u.member_id LIKE "%' . $_GET["search"]["value"] . '%" ';
          $query .= ' OR user.phone LIKE "%' . $_GET["search"]["value"] . '%" ';
          $query .= ' OR user.email LIKE "%' . $_GET["search"]["value"] . '%" ';
          $query .= ' OR user.user_status LIKE "%' . $_GET["search"]["value"] . '%" ';
          $query .= ' OR user.kyc_status LIKE "%' . $_GET["search"]["value"] . '%" )';
        }
        if(isset($_GET["searchByCat"]) ) {
           $query .= " AND user.".$_GET["searchByCat"]." = '". $_GET["searchValue"]."'  ";
        }
        if(isset($_GET["date_from"]) ) {
           $query .= " AND created >= '". $_GET["date_from"]."'  ";
        }
        if(isset($_GET["date_to"]) ) {
           $query .= " AND user.created <= '". $_GET["date_to"]."'  ";
        }
  			if(!empty($_GET["order"])) {
  				$query .= ' ORDER BY '.$_GET['order']['0']['column'].' '.$_GET['order']['0']['dir'].' ';
  			} else 	{
  				$query .= ' ORDER BY primary_id DESC ';
  			}
  			if($_GET["length"] != -1) {
  				$query .= 'LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];
  			}
            
  			$sql = $this->db->query($query);
  			
  			$result = $sql->result_array();
  		



        $i = 1;

        foreach ($result as $row) {

          $sub_array = array();

         
          if($row['fail_status']!=1)
          {
             $status='<a href="javascript:void(0)" onclick="status('.$row['primary_id'].')"> <button type="button" class="btn btn-sm btn-secondary"  data-placement="bottom" title="Refresh"><i class="fa fa-refresh"></i></button></a>'; 
          }
          else
          {
              $status='';
          }

          $sub_array[] = '<a href="' . base_url('dmtv2/print/') . $row['primary_id'] . '"> <button type="button" class="btn btn-sm btn-secondary"  data-placement="bottom" title="Print Menu Information"><i class="fa fa-print"></i></button></a>
            '.$status;

          
          $sub_array[] = $row['member_id'];

          $sub_array[] = $row['transection_id'];
            
            if($row['transection_msg'] == "PENDING"){
              $sub_array[] = "Success";
            }else{
                $sub_array[] = $row['transection_msg'];
            }

          $sub_array[] = $row['transection_mobile'];

          $sub_array[] = $row['transection_amount'];
          
          $sub_array[] = $row['transaction_bank_account_no'];

          $sub_array[] = $row['transection_bank_ifsc'];
          
          $sub_array[] = $row['reference_number'];

          $sub_array[] = $row['created'];





          $data[] = $sub_array;

          $i++;

        }







  			$output["draw"] = intval($_GET["draw"]);

        $output["recordsFiltered" ] =$recordsFiltered;

  			$output["recordsTotal"] =$recordsFiltered;

  			$output["data"] = $data;



  			echo json_encode($output);

  		}

  	}


  public function commission() {
    $this->data['param'] = $this->paremlink('/');
    $this->data['main_content'] = $this->load->view('commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }

  public function addCommissionForm() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);

      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 1;
        $this->data['role_id'] = $baseRole; 

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('commission/add', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }

 public function get_list()
  {

    $uri = $this->security->xss_clean($_GET);
    
    $role_id = $uri['id'];
  
   // pre($uri);exit;
    if (!empty($uri)) {
      $query = '';

      $output = array();


      

      $data = array();

    if (isAdmin($this->session->userdata('user_roles'))) {
            
          $query .= "SELECT * from service_commission  where role_id = '$role_id' AND service_id= 7 ";

          $recordsFiltered = $this->users_model->row_count($query);

        }else{
                
          $query .= "SELECT * from service_commission where role_id = '$role_id' AND service_id=7 ";

          $recordsFiltered = $this->users_model->row_count($query);

        }

      if (!empty($_GET["search"]["value"])) {
       $query .= 'AND start_range LIKE "%' . $_GET["search"]["value"] . '%" ' ;
        // $query .= 'OR end_range "%' . $_GET["search"]["value"] . '%" ';
        // $query .= 'OR g_commission LIKE "%' . $_GET["search"]["value"] . '%" ';
        // $query .= 'OR max_commission LIKE "%' . $_GET["search"]["value"] . '%" ';
        // $query .= 'OR c_flat LIKE "%' . $_GET["search"]["value"] . '%" ';
        
      }

      if (!empty($_GET["order"])) {
        $query .= 'ORDER BY ' . $_GET['order']['0']['column'] . ' ' . $_GET['order']['0']['dir'] . ' ';
      }
      $sql = $this->db->query($query);
      $filtered_rows = $sql->num_rows();
      if ($_GET["length"] != -1) {
        $query .= 'LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];
      }
      $sql = $this->db->query($query);
      $result = $sql->result_array();

      $i = 1;
      foreach ($result as $row) {
        $sub_array = array();

      $sub_array[] = '<button type="button" class="btn btn-sm btn-info"  data-placement="bottom" onclick="Edit(' . $row['service_commission_id'] . ')" title="Edit Commission Information"><i class="fa fa-pencil-alt"></i></button>
           <button type="button" class="btn btn-sm btn-primary"  data-placement="bottom" onclick="Delete(' . $row['service_commission_id'] . ')" title="Delete Commission Information"><i class="fa fa-trash-alt"></i></button>';
       
        $sub_array[] = $row['start_range'];
        $sub_array[] = $row['end_range'];
        $sub_array[] = $row['g_commission'];
        $sub_array[] = $row['max_commission'];
        $sub_array[] = $row['c_flat'];
 


        $data[] = $sub_array;
        $i++;
      }

      $output["draw"] = intval($_GET["draw"]);
      $output["recordsTotal"] = $filtered_rows;
      $output["recordsFiltered"] = $filtered_rows;
      $output["data"] = $data;

      echo json_encode($output);
    }
  }
  
  
   public function insert(){
        if ($_POST) {
       $data = $this->security->xss_clean($_POST);
       $data1['role_id']=$data['role_id'];
       $data1['service_id']=$this->data['serid'];
       $data1['start_range']=$data['start'];
       $data1['end_range']=$data['end'];
       $data1['g_commission']=$data['commision'];
       $data1['max_commission']=$data['max'];
       $data1['c_flat']=isset($form['flat'])?1:0;
       if($this->common_model->insert($data1,'service_commission')){
        $this->session->set_flashdata(
              array(
                'status' => 1,
                'msg' => " Commission Created Successfully"
              )
            );
            redirect('dmtv2/commission', 'refresh');
        }
        
    }
  }
  
  public function delete($id)
  {
    if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
       echo 1;
    } else {
      echo 0;
    }
  }
  
   
  
   public function edit($id)
      {
        $menu= $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
        echo json_encode($menu[0]);
      }

     public function addupdate() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);

      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 7;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('commission/edit', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
  
  public function update()
  {
    $data=array();
    $form = $this->security->xss_clean($_POST);
 
        $logme['start_range'] = $form['start'];
        $logme['end_range'] = $form['end'];
        $logme['g_commission'] = $form['commision'];
        $field = $form['service_commission_id']; 
     
        $logme['max_commission'] =$form['max'];
        $logme['c_flat'] = isset($form['flat'])?1:0;
        $logme['role_id'] = $form['role_id'];
        $logme['service_id'] = $this->data['serid'];
      


      if ($this->common_model->update($logme, "service_commission_id", $field , 'service_commission')) {
       $this->session->set_flashdata(
              array(
                'status' => 1,
                'msg' => " Updated Successfully"
              )
            );
            redirect('dmtv2/commission', 'refresh');
    }
  }
  
//   start sub charge
    public function add_surcharge(){
            if ($_POST) {
           $data = $this->security->xss_clean($_POST);
           $data1['service_id']=$data['service_id'];
           $data1['start_range']=$data['start'];
           $data1['end_range']=$data['end'];
           $data1['charge']=$data['charge'];
           $data1['c_flate']=isset($data['flat'])?1:0;
           if($this->common_model->insert($data1,'service_charge')){
            $this->session->set_flashdata(
                  array(
                    'status' => 1,
                    'msg' => " Charge Created Successfully"
                  )
                );
                redirect('dmtv2/surcharge', 'refresh');
            }
            
        }
    }
  
  public function addSubForm() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);

      if (isset($data['aepsCommissionForm'])) {


        echo $this->load->view('subcharge/add', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
  
  public function deletesub($id)
  {
    if ($this->db->where("service_charge_id", $id)->delete('service_charge')) {
       echo 1;
    } else {
      echo 0;
    }
  }
  
     public function editsub($id)
      {
        $menu= $this->common_model->select_option($id, 'service_charge_id', 'service_charge');
        echo json_encode($menu[0]);
      }
      
        public function addupdatesub() {
      
        echo $this->load->view('subcharge/edit', $this->data, true);
            
    if ($_POST) {

      
      $data = $this->security->xss_clean($_POST);

    //   if (isset($data['addupdate'])) {

        // $baseRole = $data['addupdate'];

        // $service = 7;

        // $commissionList = $this->commission_model->get_list($service, $baseRole);



    //   } else {
    //     echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
    //   }

    }

  }
  
    function update_surcharge(){
        if ($_POST) {
           $data = $this->security->xss_clean($_POST);
           $field = $data['service_charge_id'];
           $logme['service_id']=$this->data['serid'];
           $logme['start_range']=$data['start'];
           $logme['end_range']=$data['end'];
           $logme['charge']=$data['charge'];
           $logme['c_flate']=isset($data['flat'])?1:0;
           
           if ($this->common_model->update($logme, "service_charge_id", $field , 'service_charge')) {
                $this->session->set_flashdata(
                  array(
                    'status' => 1,
                    'msg' => " Updated Successfully"
                  )
                );
                redirect('dmtv2/surcharge', 'refresh');
                
            }
        }
    }
    
    public function print($id){
        
        $query = "SELECT * FROM submit_transection WHERE primary_id = '{$id}'";
      
      	$sql = $this->db->query($query);
  			
  		$this->data['result'] = $sql->row();
  		
  		// echo json_encode($result);
  		echo $this->load->view('print', $this->data, true);
  		
    }
    
      public function memberselect()
    {
      $query="Select `member_id` FROM `user` where `role_id`!=94 && `delete_user`!=1";
      $sql = $this->db->query($query);
      $result = $sql->result_array();
      return $result;
    }
  
   public function export()
   {
   		$uri = $this->security->xss_clean($_GET);
   		
   	  	ini_set('memory_limit', '44M');
         $fileName = 'employee.xlsx';
  		

  		if (isset($uri['key']) && !empty($uri['key'])) {

  			$query = '';
  			
  			$output = array();
  			
  			$duid = $uri['key'];
  			
  			$list = $uri['list'];
  			
  			$data = array();
  			
        $service_id = $this->data['serid'];


        if (isAdmin($this->session->userdata('user_roles'))) {

          $query .= "SELECT * FROM submit_transection WHERE service_id = '{$service_id}' ";

          $recordsFiltered = $this->users_model->row_count($query);

        } else {

          $query .= "SELECT * FROM submit_transection WHERE service_id = '{$service_id}' AND member_id = '{$duid}' ";


          $recordsFiltered = $this->users_model->row_count($query);

        }

        if(!empty($uri['member']))
        {
           $query .=" AND member_id='".$uri['member']."' ";
        }
         if(!empty($uri['from']) && !empty($uri['to']))
        {
           $query.=" AND (CAST(`created` as date) BETWEEN '".$uri['from']."' AND '".$uri['to']."') "; 
        }
        if(!empty($uri['default_a']) && !empty($uri['default_v']))
        {
            $query .=" AND ".$uri['default_a']."='".$uri['default_v']."' ";
        }
         if(!empty($uri['status']))
              {  
                  if($uri['status']=="processing" or $uri['status']=="processed")
                  {
                       $query.=" AND transection_msg='".$uri['status']."' ";  
                  }
                  else
                  {
                      $query.=" AND (`transection_msg` NOT LIKE '%processed%' AND `transection_msg` NOT LIKE '%processing%') ";
                  }
                 
              }
  			switch ($list) {


  				case 'all':

  					break;

  				case 'Failed':

  					$query .= " AND submit_transection.transection_status = 0 ";

  					break;

  				case 'verify':

  					$query .= " AND submit_transection.transection_status = 1 ";



  					break;

  				default:

            echo json_encode(['error' => 1, 'msg' => 'request not allowed']);

            break;

  			}

  	  
        if(isset($_GET["searchByCat"]) ) {
           $query .= " AND user.".$_GET["searchByCat"]." = '". $_GET["searchValue"]."'  ";
        }
        if(isset($_GET["date_from"]) ) {
           $query .= " AND created >= '". $_GET["date_from"]."'  ";
        }
        if(isset($_GET["date_to"]) ) {
           $query .= " AND user.created <= '". $_GET["date_to"]."'  ";
        }
  			if(!empty($_GET["order"])) {
  				$query .= ' ORDER BY '.$_GET['order']['0']['column'].' '.$_GET['order']['0']['dir'].' ';
  			} else 	{
  				$query .= ' ORDER BY primary_id DESC ';
  			}
  		
  			$sql = $this->db->query($query);
  			
  			$result = $sql->result_array();
  		
  			$spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'S.No');
            $sheet->setCellValue('B1', 'MEMBER ID');
            $sheet->setCellValue('C1', 'Transition ID');
            $sheet->setCellValue('D1', 'Transition Msg');
            $sheet->setCellValue('E1', 'Transition Mobile');
            $sheet->setCellValue('F1', 'Transition Amount');
            $sheet->setCellValue('G1', 'Account Number');
            $sheet->setCellValue('H1', 'IFSC Code');
            $sheet->setCellValue('I1','Reference Number');
            $sheet->setCellValue('J1','Created');
            $rows = 2;
            $i=1;
            foreach ($result as $val){
               $sheet->setCellValue('A'. $rows, $i);
               $sheet->setCellValue('B'. $rows,$val['member_id']);
               $sheet->setCellValue('C' . $rows, $val['transection_id']);
               $sheet->setCellValue('D' . $rows,$val['transection_msg']);
               $sheet->setCellValue('E' . $rows,$val['transection_mobile']);
               $sheet->setCellValue('F' . $rows,$val['transection_amount']);
               $sheet->setCellValue('G' . $rows,$val['transaction_bank_account_no']);
               $sheet->setCellValue('H' . $rows,$val['transection_bank_ifsc']);
               $sheet->setCellValue('I' . $rows, $val['reference_number']);
               $sheet->setCellValue('J' . $rows, $val['created']);
              $rows++;
              $i++;
            }
       $writer = new Xlsx($spreadsheet);
//   $writer->save("php://output");
       $writer->save("uploads/".$fileName);
      header("Content-Type: application/vnd.ms-excel");
     echo base_url()."/uploads/".$fileName;
  }
}
   public function varify()
   {
   $bank_details=$this->security->xss_clean($_POST);
   $userid=$bank_details['userid'];
   $data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));
   if($data['bal']>3.36){
       $data1=[
             'accountNumber'=>trim($bank_details['account']),
             'ifsc'=>trim($bank_details['beneficiary_ifsc']),
             'bname'=>trim($bank_details['name']),
             'purpose'=>'DMT Verification',
             'api_key'=>'POCA001'
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
    $this->db->select('*')->from('wallet')->where('member_id',$this->session->userdata('user_id'));
    $query = $this->db->get();
    $wallet= $query->result_array();
     $updateBalance=$wallet[0]['balance']-3.36;
    $updateWallet = [
    'balance' => $updateBalance,
    ];
  if($this->common_model->update($updateWallet, 'member_id', $this->session->userdata('user_id'), 'wallet')){
     if($response1->data->status=='Success'){
          $this->data['submitTransection']=['varification'=>1,'beneficiary_name'=>$response1->data->beneficiaryName,'api_key'=>'POCA001','beneficiary_id'=>$userid];
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
         'member_to' => $this->session->userdata('user_id'),
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
        //  $this->db->select('*')->from('beneficiary_list')->where('customer_mobile',$bank_details[0]['customer_mobile']);
        //  $query = $this->db->get();
        //  $beneficiary= $query->result_array();
        //  $res=['status'=>true,'beneficiary_list'=>$beneficiary];
        //  echo Json_encode($res);
        //  redirect('dmtv2/', 'refresh');
        echo $response2;
  }
  
 }
 elseif($response1!='' && !empty($response1->msg)){
      echo $response1->msg;
      }
   else
      {
           echo "SOME ISSUE CREATED";
      }
}
   else
   {
 echo  "Insufficience Balance";
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
  
   //sms
   private function sms($amount,$phone)
   {
             $text="Dear Customer Your Account has been Credited with Rs. ".$amount.". at Date and time: ".date("Y-m-d h:i:sa")." through Moon Pe.";
              $this->client = new Client();
              try {
                 $response = $this->client->request('GET', "http://sms.vitefintech.com/api/sendmsg.php?user=&pass=&sender=&phone=".$phone."&text=".$text."&priority=ndnd&stype=normal");

                  $result = $response->getBody()->getContents();
                  echo "true";
              } catch (GuzzleHttp\Exception\BadResponseException $e) {
                  #guzzle repose for future use
                  $response = $e->getResponse();
                  $responseBodyAsString = $response->getBody()->getContents();
                  print_r($responseBodyAsString);
               }
  }
   //status 
  public function status($id)
  {
    if(isset($id) && !empty($id))
    {
     $transection_details=$this->common_model->select_option1($id,'primary_id','submit_transection');
     if(count($transection_details)==1)
     {
      $transection_details=$transection_details[0];
      if($transection_details['fail_status']!=1)
      {
        $member_id=$transection_details['member_id'];
        $wallet_transion=$this->common_model->select_option($transection_details['reference_number'],'refrence','wallet_transaction');
        if(count($wallet_transion)==1)
        {
        $wallet_transion=$wallet_transion[0];
        $response1=self::status_check($transection_details['reference_number']);
        $user_id=$this->common_model->select_option($member_id,'member_id','user');
        $wallet_balance= $this->common_model->wallet_balance($user_id[0]['user_id']);                                  
        if(isjson($response1))
        {
         $response=Json_decode($response1);
         if(isset($response->status) && isset($response->response_code))
         {
          if($response->status==0 && $response->response_code==5)//fail after update api
          {
            if($this->common_model->update(['fail_status'=>1],'primary_id',$id,'submit_transection'))
            {
              $updateBalance=(float)$wallet_balance+(float)$wallet_transion['surcharge']+(float)$transection_details['transection_amount'];
              $updateWallet = [
                'balance' => $updateBalance,
                ];
              if($this->common_model->update($updateWallet, 'member_id', $user_id[0]['user_id'],'wallet')){
                  $logme = [
                            'wallet_id' => $wallet_transion['wallet_id'],
                            'member_to' => $wallet_transion['member_to'],
                            'amount' => $transection_details['transection_amount'],
                            'surcharge' => $wallet_transion['surcharge'],
                            'refrence' =>  "Refund".$wallet_transion['refrence'],
                            'service_id' =>$wallet_transion['service_id'],
                            'stock_type'=> $wallet_transion['stock_type'],
                            'status' => $wallet_transion['status'],
                            'balance' => $wallet_balance,
                            'closebalance' => $updateBalance,
                            'type' => 'credit',
                            'mode' => 'DMT',
                            'bank' =>  'DMT',
                            'narration' => 'DMT Fail',
                            'date'=> date('Y-m-d'),
                          ];
                     $this->common_model->insert($logme, 'wallet_transaction');
              }
              $update=[
                'transection_respcode'=>$response->response_code,
                'transection_msg'=>'Failed',
                'transection_response'=>$response1
              ];
              $this->common_model->update($update,'reference_number',$transection_details['reference_number'],'submit_transection');
            }     
          }
          elseif($response->status==0 && $response->response_code==0)//fail after update api
          {
            if($this->common_model->update(['fail_status'=>1],'primary_id',$id,'submit_transection'))
            {
              $updateBalance=(float)$wallet_balance+(float)$wallet_transion['surcharge']+(float)$transection_details['transection_amount'];
              $updateWallet = [
                'balance' => $updateBalance,
                ];
              if($this->common_model->update($updateWallet, 'member_id', $user_id[0]['user_id'],'wallet')){
                  $logme = [
                            'wallet_id' => $wallet_transion['wallet_id'],
                            'member_to' => $wallet_transion['member_to'],
                            'amount' => $transection_details['transection_amount'],
                            'surcharge' => $wallet_transion['surcharge'],
                            'refrence' =>  "Refund".$wallet_transion['refrence'],
                            'service_id' =>$wallet_transion['service_id'],
                            'stock_type'=> $wallet_transion['stock_type'],
                            'status' => $wallet_transion['status'],
                            'balance' => $wallet_balance,
                            'closebalance' => $updateBalance,
                            'type' => 'credit',
                            'mode' => 'DMT',
                            'bank' =>  'DMT',
                            'narration' => 'DMT Fail',
                            'date'=> date('Y-m-d'),
                          ];
                     $this->common_model->insert($logme, 'wallet_transaction');
              }
              $update=[
                'transection_respcode'=>$response->response_code,
                'transection_msg'=>$response->msg,
                'transection_response'=>$response1
              ];
              $this->common_model->update($update,'reference_number',$transection_details['reference_number'],'submit_transection');
            }     
          }
         }
         elseif($response->data->transfer->status=='FAILED')//fail by api
         {
          if($this->common_model->update(['fail_status'=>1],'primary_id',$id,'submit_transection'))
          {
            $updateBalance=(int)$wallet_balance+(int)$wallet_transion['surcharge']+(int)$transection_details['transection_amount'];
            $updateWallet = [
              'balance' => $updateBalance,
              ];
            if($this->common_model->update($updateWallet, 'member_id', $user_id[0]['user_id'],'wallet')){
                $logme = [
                          'wallet_id' => $wallet_transion['wallet_id'],
                          'member_to' => $wallet_transion['member_to'],
                          'amount' => $transection_details['transection_amount'],
                          'surcharge' => $wallet_transion['surcharge'],
                          'refrence' =>  "Refund".$wallet_transion['refrence'],
                          'service_id' =>$wallet_transion['service_id'],
                          'stock_type'=> $wallet_transion['stock_type'],
                          'status' => $wallet_transion['status'],
                          'balance' => $wallet_balance,
                          'closebalance' => $updateBalance,
                          'type' => 'credit',
                          'mode' => 'DMT',
                          'bank' =>  'DMT',
                          'narration' => 'DMT Fail',
                          'date'=> date('Y-m-d'),
                        ];
                  $this->common_model->insert($logme, 'wallet_transaction');
            }
            $update=[
              'transection_respcode'=>$response->subCode,
              'transection_msg'=>$response->data->transfer->status,
              'transection_response'=>$response1
             ];
            $this->common_model->update($update,'reference_number',$transection_details['reference_number'],'submit_transection');
          }  
         }
         else
         {
          $update=[
            'transection_respcode'=>$response->subCode,
            'transection_msg'=>$response->data->transfer->status,
            'transection_response'=>$response1
          ];
          $this->common_model->update($update,'reference_number',$transection_details['reference_number'],'submit_transection');
         }
         echo "1";
        }
        else
        {
          echo "server error";
        }
        }
        else
        {
         echo "wallet not fount";
        }
      }
      else
      {
        echo "transition already update"; 
      }
     }
     else
     {
      echo "Transition not found";
     }
    }
    else
    {
      echo "All field required";
    }
  }

  private function status_check($ref)
  {
     $this->client = new Client();
     try
      {
      $response = $this->client->request('GET', "https://vitefintech.com/viteapi/dmt/dmt_status?id=".$ref, [
      ]);
      return  $response->getBody()->getContents();
      }
      catch (GuzzleHttp\Exception\BadResponseException $e) 
      {
      #guzzle repose for future use
      $response = $e->getResponse();
      $responseBodyAsString = $response->getBody()->getContents();
      print_r($responseBodyAsString);
      }
  } 
  
}
