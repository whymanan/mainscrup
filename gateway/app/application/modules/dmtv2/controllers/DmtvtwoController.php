<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;

class DmtvtwoController extends Vite {


  public $data = array();

  public $client;


  public function __construct() {
      parent::__construct();
      $this->data['active'] = 'DMT';
      $this->data['serid'] = '2';
      $this->tnxType = 'DMT';
      $this->load->model('common_model');
      $this->load->model('users_model');
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
    $this->data['main_content'] = $this->load->view('list', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }

public function surcharge() {
    $this->data['param'] = $this->paremlink('/');
     $this->data['charge'] =$this->commission_model->get_surcharge(2);
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

        $url = 'https://emopay.co.in/vite/dmt/';
        
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
        
            // $response->Status = "SUCCESS";
             
            //  pre($response);exit;

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

        $url = 'https://emopay.co.in/vite/dmt/beneficiarylist';
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
        // pre($response);exit;
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

          if (!$this->common_model->exists('user_detail', ['phone_no' => $customer['phone_no']])) {

            if ($this->common_model->insert($customer, 'user_detail')) {
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
          
        // pre($response);exit;

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
                
                $this->data['submitTransection'] = [
                  'amount' => $data['amount'],
                  'transferMode' => $data['mode'],
                  'beneficiaryid' => $data['beneficiaryid'],
                  'customerMobile' => $data['mobile'],
                  'beneficiaryAccount' => $data['beneficiary_account_number'],
                  'beneficiaryIFSC' => $data['beneficiary_ifsc'],
                  'orderId' => "ORDERID_".self::stan(),
                ];
        
                $saveTransection = [
                  'transection_id' =>  $this->data['submitTransection']['orderId'],
                  'service_id' => $this->data['serid'],
                  'transection_type' => $this->tnxType,
                  'member_id' => $this->session->userdata('member_id'),
                  'transection_amount' => $data['amount'],
                  'api_requist' => 'dmtv2',
                  'location' => $location
                ];
        
                $beneficiaryDetails = $this->common_model->getBeneficiaryDetails($data['beneficiaryid']);
                if ($beneficiaryDetails) {
                    $saveTransection['transection_bank_code'] = $beneficiaryDetails->beneficiary_ifsc;
                    $saveTransection['transection_bank_ifsc'] = $beneficiaryDetails->beneficiary_ifsc;
                    $saveTransection['reference_number'] =   $this->data['submitTransection']['orderId'];
                    $saveTransection['transection_mobile'] =  $beneficiaryDetails->beneficiary_mobile;
                }
        
        
                  if ($transaction_primary = $this->common_model->insert($saveTransection, 'submit_transection')) {
        
                      $response = self::transection_service();
        
                        if (isJson($response)) {
                          $result = json_decode($response);
                          if ($result->status == 'ACCEPTED') {
                            $status = 1;
                          }else{
                            $status = 0;
                          }
                          $action = [
                            'transection_status' =>  $status,
                            'transection_msg' => $result->status,
                            'transection_respcode' => $result->statusCode,
                            'transection_response' => $response,
                          ];
                          $this->common_model->update($action, 'primary_id', $transaction_primary, 'submit_transection');
                          if ($result->status == 'ACCEPTED') {
                              
                                $userWallet = $this->common_model->get_user_wallet_balance($this->session->userdata('user_id'));
                              
                                $subcharge  = $this->common_model->sub_charge($data['amount'],$this->data['serid']);
                                
                                $service = 2;
                                
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
                                              'amount' =>  $updateBalance,
                                              'surcharge' => $subcharge->charge,
                                              'refrence' =>  $this->data['submitTransection']['orderId'],
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
                                
                                echo json_encode(['error' => 0, 'msg' => 'Transaction Successfully']);
                                
                                redirect('dmtv2', 'refresh');
                              
                              
                          } else {
                              echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
                          }
                        }else{
        
                        }
                  }else{
                    echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
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
                // pre($commision);exit;
                if (!empty($commision)) {
                  $userWallet = $this->common_model->get_user_wallet_balance($value['user_id']);
            
                  if ($userWallet != 'none') {
                      
                    $updateBalance = $userWallet->balance + $commision[$i]['g_commission'];    // add commission
                    $updateWallet = [
                      'balance' => $updateBalance,
                    ];
                    if($this->common_model->update($updateWallet, 'member_id', $value['user_id'], 'wallet')) {
                      $message = [
                        'msg' => 'Your wallet balance credited ' . $commision[$i]['g_commission'] . ' available balance is ' . $updateBalance,
                        'user_id' => $value['user_id']
                      ];
                      $this->set_notification($message);
                         $logme = [
                                      'wallet_id' => $userWallet->wallet_id,
                                      'member_to' =>  $value['user_id'],
                                      'member_from' =>  $value['parent'],
                                      'amount' =>  $updateBalance,
                                    //   'surcharge' => $data['surcharge'],
                                      'refrence' =>  $this->data['submitTransection']['orderId'],
                                      'commission' =>  $commision[$i]['g_commission'],
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

        $this->data['beneficiary'] = $this->common_model->getBeneficiaryMobile($data['bId']);

        echo $this->load->view('add-transection', $this->data, true);

      }else{
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }

  public function transection_service() {
      $this->client = new Client();
      
      $url = 'https://emopay.co.in/vite/dmt/dmtTransection';
      #guzzle
      try {
          $response = $this->client->request('GET', $url, [
                
                'query' => $this->data['submitTransection'],]);

                return $response->getBody()->getContents() ;
            
               
                // $result = $response->getBody()->getContents() ;
                // pre($result);exit;
               

      } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
      }
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

          $query .= "SELECT * FROM submit_transection WHERE service_id = 2 ";

          $recordsFiltered = $this->users_model->row_count($query);

        } else {

          $query .= "SELECT * FROM submit_transection WHERE service_id = 2 AND member_id = '{$this->session->userdata('member_id')}' ";


          $recordsFiltered = $this->users_model->row_count($query);

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
  				$query .= 'ORDER BY '.$_GET['order']['0']['column'].' '.$_GET['order']['0']['dir'].' ';
  			} else 	{
  				$query .= 'ORDER BY created DESC ';
  			}
  			if($_GET["length"] != -1) {
  				$query .= 'LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];
  			}

  			$sql = $this->db->query($query);

  			$result = $sql->result_array();



        $i = 1;

        foreach ($result as $row) {

          $sub_array = array();

          $sub_array[] = $i ;

          $sub_array[] = $row['member_id'];

          $sub_array[] = $row['transection_id'];

          $sub_array[] = $row['transection_msg'];

          $sub_array[] = $row['transection_mobile'];

          $sub_array[] = $row['transection_amount'];

          $sub_array[] = $row['transection_bank_code'];

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

        $service = 2;
        
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
            
          $query .= "SELECT * from service_commission  where role_id = '$role_id' AND service_id= 2 ";

          $recordsFiltered = $this->users_model->row_count($query);

        }else{
                
          $query .= "SELECT * from service_commission where role_id = '$role_id' AND service_id=2 ";

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
           $data1['service_id']=2;
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

        $service = 2;

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
        $logme['service_id'] = $form['service_id'];
      


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
           $data1['service_id']=2;
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

        // $service = 16;

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
           $logme['service_id']=$data['service_id'];
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
    
   
  
  
}
