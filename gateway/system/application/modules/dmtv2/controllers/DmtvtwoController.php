<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;

class DmtvtwoController extends Vite {


  public $data = array();

  public $client;


  public function __construct() {
      parent::__construct();
      $this->data['active'] = 'DMT';
      $this->data['serid'] = '1';
      $this->load->model('common_model');
      $this->load->model('users_model');
      $this->load->model('commission_model');
      $this->data['breadcrumbs'] = [array('url' => base_url('dashboard'), 'name' => 'Dashboard')];
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
     $this->data['charge'] =$this->commission_model->get_surcharge(16);
     //pre($this->data['charge']);exit;
    $this->data['main_content'] = $this->load->view('subcharge/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('subcharge/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }

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

  public function customers() {

    if ($_GET) {

      $data = $this->security->xss_clean($_GET);

      if (isset($data['mobile']) && validate_phone_number($data['mobile'])) {

        $this->client = new Client();

        $url = _SERVICE_API_V2_ . 'customers/' . $data['mobile'];
        #guzzle
        try {
            $response = $this->client->request('get', $url, [
              'headers' => [
                 'Authorization' => 'Bearer ' . $this->session->userdata('token'),
              ],
              'decode_content' => false,
            ]);

            $response = $response->getBody()->getContents();

        } catch (GuzzleHttp\Exception\BadResponseException $e) {
          #guzzle repose for future use
          $response = $e->getResponse();
          $responseBodyAsString = $response->getBody()->getContents();
          print_r($responseBodyAsString);
        }

        // if ($response == UNAUTHORIZED) {
        //   redirect('auth');
        // }else{

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

        $url = _SERVICE_API_V2_ . 'beneficiarylist/' . $mobile;
        #guzzle
        try {
            $response = $this->client->request('get', $url, [
              'headers' => [
                 'Authorization' => 'Bearer ' . $this->session->userdata('token'),
              ],
              'decode_content' => false,
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
        $submitForms = [
          'customerName' => $full_name,
          'customerMobile' => $data['phone_no']
        ];

        $this->client = new Client();

        $url = _SERVICE_API_V2_ . 'customerregistration';
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

          if (!$this->common_model->exists('user_detail', ['phone_no' => $customer['phone_no']])) {

            if ($this->common_model->insert($customer, 'user_detail')) {
              $response->msg = 'Internal Save Success';
            }

          }else{

            if ($this->common_model->update($customer, 'phone_no', $customer['phone_no'], 'user_detail')) {
              $response->msg = 'Internal Update Success';
            }

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

    }
    redirect(base_url() . 'dmtv2', 'refresh' );
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
        $submitForms = [
          'customerMobile' => $data['mobile'],
          'beneficiaryName' => $data['beneficiary_name'],
          'beneficiaryNo' => $data['beneficiary_mobile'],
          'beneficiaryAcc' => $data['beneficiary_account'],
          'ifscCode' => $data['beneficiary_ifsc_code'],
        ];

        $this->client = new Client();

        $url = _SERVICE_API_V2_ . 'addbeneficiary';
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
          $beneficiary['beneficiaryId'] = $response->beneficiaryId;

          if ($this->common_model->insert($beneficiary, 'beneficiary_list')) {
              $response->msg = 'Internal Save Success';
          }

          $response->Mobile = $beneficiary['beneficiary_mobile'];

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

      if ($data) {

        if ($this->session->userdata('latitude')) {
          $location = $this->session->userdata('latitude').'|'.$this->session->userdata('longitude');
        }

        $this->data['submitTransection'] = [
          'bankCode' => $data['bankCode'],
          'ifscCode' => $data['bankCode'],
          'location' => $location,
          'txType' => $data['selectTransactionsTypes'],
          'aadharNo' => '0'.$data['adharCardNumber'],
          'amount' => $data['transectionAmount'],
          'stan' => self::stan(),
          'data' => $data['biodata']
        ];

        $saveTransection = [
            'transection_id' => self::utan($data['adharCardNumber']),
            'transection_type' => $data['selectTransactionsTypes'],
            'member_id' => $this->session->userdata('member_id'),
            'transection_amount' => $data['transectionAmount'],
            'transection_bank_code' => $data['bankCode'],
            'transection_bank_ifsc' => $data['bankCode'],
            'reference_number' =>   $this->data['submitTransection']['stan'],
            'transection_mobile' => $data['tmobilenumber'],
            'api_requist' => 'aepsTxn',
            'location' => $location
          ];

          if ($transaction_primary = $this->common_model->insert($saveTransection, 'submit_transection')) {
            $biomatric = [
              'transection_primary' => $transaction_primary,
              'device_type' => $data['biodevice'],
              'bio_data' => $data['biodata'],
            ];
            if ($this->common_model->insert($biomatric, 'biometric_data')) {
              $response = self::transection_service();

               echo $response;

                if (isJson($response)) {
                  $result = json_decode($response);
                  $action = [
                    'transection_status' =>  $result->status,
                    'transection_msg' => $result->msg,
                    'transection_respcode' => $result->respcode,
                    'transection_response' => $response,
                  ];
                  $this->common_model->update($action, 'primary_id', $transaction_primary, 'submit_transection');
                  if ($result->status) {
                    // code...
                  } else {

                  }
              }else{

                }
            }
          }else{
            echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
          }
      }
    }
  }


  public function transection_service() {
      $this->client = new Client();

      $url = _SERVICE_API_ . 'aepstransection';
      #guzzle
      try {
          $response = $this->client->request('POST', $url, [
            'headers' => [
               'Authorization' => 'Bearer ' . $this->session->userdata('token'),
            ],
            'decode_content' => false,
            'form_params' => $this->data['submitTransection'],
          ]);

          return $response->getBody()->getContents();

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

          $query .= "SELECT * FROM submit_transection WHERE service_id = 16 ";

          $recordsFiltered = $this->users_model->row_count($query);

        } else {

          $query .= "SELECT * FROM submit_transection WHERE service_id = 16 AND member_id = '{$duid}' ";


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

         


          $sub_array[] = '<a href=""> <button type="button" class="btn btn-sm btn-secondary"  data-placement="bottom" title="Edit Menu Information"><i class="fa fa-pencil-alt"></i></button></a>

            ';

          $sub_array[] = $row['member_id'];

          $sub_array[] = $row['parent1'];

          $sub_array[] = $row['email'];

          $sub_array[] = $row['phone'];

          $sub_array[] = $row['role'];

          $sub_array[] = $status ;

          $sub_array[] =  $kyc;

          $sub_array[] = $row['created_at'];





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
            
          $query .= "SELECT * from service_commission  where role_id = '$role_id' AND service_id= 16 ";

          $recordsFiltered = $this->users_model->row_count($query);

        }else{
                
          $query .= "SELECT * from service_commission where role_id = '$role_id' AND service_id=16 ";

          $recordsFiltered = $this->users_model->row_count($query);

        }

      if (!empty($_GET["search"]["value"])) {
        $query .= 'OR start_range_id LIKE "%' . $_GET["search"]["value"] . '%" ';
        $query .= 'OR end_range "%' . $_GET["search"]["value"] . '%" ';
        $query .= 'OR g_commission LIKE "%' . $_GET["search"]["value"] . '%" ';
        $query .= 'OR max_commission LIKE "%' . $_GET["search"]["value"] . '%" ';
        $query .= 'OR c_flat LIKE "%' . $_GET["search"]["value"] . '%" ';
        
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
       $data1['role_id']=$data['user_role'];
       $data1['service_id']=16;
       $data1['start_range']=$data['start'];
       $data1['end_range']=$data['end'];
       $data1['g_commission']=$data['commision'];
       $data1['max_commission']=$data['max'];
       $data1['c_flat']=$data['flat'];
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
  
  
}
