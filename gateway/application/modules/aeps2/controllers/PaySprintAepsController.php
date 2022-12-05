<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;

require FCPATH.'vendor/autoload.php';
   
use PhpOffice\PhpSpreadsheet\Spreadsheet;
   
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class PaySprintAepsController extends Vite {


  public $data = array();

  public $client;


  public function __construct() {
      parent::__construct();
      $this->tnxType = 'aepsTxn' ;
      $this->data['serid'] = 2;
      $this->data['api_key'] = 'POCA001';
      $this->data['active'] = 'AEPS2';
      $this->load->model('common_model');
      $this->load->model('users_model');
      $this->load->model('commission_model');
      $this->data['breadcrumbs'] = [array('url' => base_url('dashboard'), 'name' => 'Aeps')];
      $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));
  }

  public function index() {
    $this->data['param'] = $this->paremlink('add');
    $this->data['rrn'] =  self::stan();
    $this->data['main_content'] = $this->load->view('index', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);

  }
  
  public function aadhar_pay() {
    $this->data['param'] = $this->paremlink('add');
    $this->data['rrn'] =  self::stan();
    $this->data['main_content'] = $this->load->view('aadhar_pay', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);

  }

  public function add() {
    $this->data['param'] = $this->paremlink('/');
    $this->data['main_content'] = $this->load->view('add', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  
  public function driver_download() {
    $this->data['param'] = $this->paremlink('/');
    $this->data['main_content'] = $this->load->view('driver', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  
public function surcharge() {
    $this->data['param'] = $this->paremlink('/');
     $this->data['charge'] =$this->commission_model->get_surcharge(1);
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
            redirect('aeps/surcharge', 'refresh');
        }
        
    }
  }

  public function history() {
    $this->data['breadcrumbs'] = [array('url' => base_url('aeps'), 'name' => 'AePS'), array('url' => base_url('thistory'), 'name' => 'Transaction History')];
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
           $query .= " AND user.created_at >= '". $_GET["date_from"]."'  ";
        }
        if(isset($_GET["date_to"]) ) {
           $query .= " AND user.created_at <= '". $_GET["date_to"]."'  ";
        }
  			if(!empty($_GET["order"])) {
  				$query .= 'ORDER BY '.$_GET['order']['0']['column'].' '.$_GET['order']['0']['dir'].' ';
  			} else 	{
  				$query .= 'ORDER BY user.created_at DESC ';
  			}
  			if($_GET["length"] != -1) {
  				$query .= 'LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];
  			}

  			$sql = $this->db->query($query);

  			$result = $sql->result_array();



        $i = 1;

        foreach ($result as $row) {

          $sub_array = array();

          $status = '';

          $kyc = '';

          if ($row['kyc_status'] == 'verify') {

            $kyc = '<i class="fa fa-circle text-success font-12" data-toggle="tooltip" data-placement="top" title="" data-original-title="Active"></i>';

          } elseif ($row['kyc_status'] == 'pending') {

            $kyc = '<i class="fa fa-circle text-warning font-12" data-toggle="tooltip" data-placement="top" title="" data-original-title="Pending"></i>';

          } else {

            $kyc = '<i class="fa fa-circle text-danger font-12" data-toggle="tooltip" data-placement="top" title="" data-original-title="Not uploaded"></i>';

          }

          if ($row['user_status'] == 'active') {

            $status = '<span class="badge badge-success">Active</span>';

          } elseif ($row['user_status'] == 'pending') {

            $status = '<span class="badge badge-warning">Pending</span>';

          } else {

            $status = '<span class="badge badge-danger">Deactive</span>';

          }



          $sub_array[] = '<a href="' . base_url('users/edit?q=') . $row['user_id'] . '"> <button type="button" class="btn btn-sm btn-secondary"  data-placement="bottom" title="Edit Menu Information"><i class="fa fa-pencil-alt"></i></button></a>

             <button type="button" class="btn btn-sm btn-primary"  data-placement="bottom" onclick="Delete(' . $row['user_id'] . ')" title="Delete Menu Information"><i class="fa fa-trash-alt"></i></button>';

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





//
  
  
  

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
      //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('commission/add', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
   public function loadForm() {


        echo $this->load->view('commission/add', $this->data, true);


    

  }
  public function insert(){
      
    if ($_POST) {
   
       $data = $this->security->xss_clean($_POST);
       $data1['start_range'] = $data['start'];
       $data1['end_range'] = $data['end'];
       $data1['role_id'] = $data['role_id'];
       $data1['g_commission'] = $data['commision'];
       $data1['service_id'] = $this->data['serid'];
       $data1['c_flat	'] = isset($data['flat'])?1:0;
       $data1['created	'] = date("Y-m-d h:i:sa");
       
      if ($this->common_model->insert($data1,'service_commission')) {
       $this->session->set_flashdata(
              array(
                'status' => 1,
                'msg' => " Insert Successfully"
              )
            );
            redirect('aeps2/commission', 'refresh');
        }
    }
        
  }
  
   public function get_list()
  {

    $uri = $this->security->xss_clean($_GET);
    
    $role_id = $uri['id'];
    
    if (!empty($uri)) {
      $query = '';

      $output = array();
      
      $data = array();

    if (isAdmin($this->session->userdata('user_roles'))) {
            
          $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id = {$this->data['serid']} ";

          $recordsFiltered = $this->users_model->row_count($query);

        }else{
                
          $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = {$this->data['serid']} ";

          $recordsFiltered = $this->users_model->row_count($query);

        }

      if (!empty($_GET["search"]["value"])) {
        $query .= 'AND start_range LIKE "%' . $_GET["search"]["value"] . '%" ' ;
        // $query .= 'OR end_range "%' . $_GET["search"]["value"] . '%" ';
        // $query .= 'OR g_commission LIKE "%' . $_GET["search"]["value"] . '%" ';
        // $query .= 'OR max_commission LIKE "%' . $_GET["search"]["value"] . '%" ';
        // $query .= 'OR c_flate LIKE "%' . $_GET["search"]["value"] . '%" ';
        
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

   public function submitTransection() {

        if ($_POST) {
    
          $data = $this->security->xss_clean($_POST);
        //   print_r($data);
        //   exit();
          
            if ($data) {
                
            $this->data['submitTransection'] = [
                  'latitude' => $this->session->userdata('latitude'),
                  'longitude' => $this->session->userdata('longitude'),
                  'mobilenumber' => $data['tmobilenumber'],
                  'referenceno' => $data['referenceno'],
                  'ipaddress' => '148.66.132.29',
                  'adhaarnumber' =>$data['adharCardNumber'],
                  'accessmodetype' => 'SITE',
                  'nationalbankidentification' => $data['bankCode'],
                  'data' => $data['pidData'],
                  'pipe' => 'bank1',
                  'timestamp' => date("Y-m-d h:i:sa"),
                  'transcationtype' => $data['selectTransactionsTypes'],
                  'amount' => $data['transectionAmount'],
                  'submerchantid' => $this->session->userdata('member_id'),
                  'key' => '0c32b64a516ff7da',
                  'iv' =>'26f61978092f49ca',
                  'transection_id' => $data['adharCardNumber'].self::utan($data['adharCardNumber']),
                  'api_key' => $this->data['api_key']
                ];
    
            if ($this->session->userdata('latitude')) {
              $location = $this->session->userdata('latitude').'|'.$this->session->userdata('longitude');
            }
    
    
    
             $this->data['saveTransection'] = [
                'transection_id' => $this->data['submitTransection']['transection_id'],
                'transection_type' => $data['selectTransactionsTypes'],
                'member_id' => $this->session->userdata('member_id'),
                'transection_amount' => $data['transectionAmount'],
                'transection_bank_code' => $data['bankCode'],
                'transection_bank_ifsc' => $data['bankCode'],
                'reference_number' =>   $this->data['submitTransection']['referenceno'],
                'transection_mobile' => $data['tmobilenumber'],
                'api_requist' => 'aepsTxn',
                'location' => $location,
                'service_id' => $this->data['serid'],
                'created' => date("Y-m-d h:i:sa")
              ];
    
              if ($transaction_primary = $this->common_model->insert( $this->data['saveTransection'], 'submit_transection')) {
                $biomatric = [
                  'transection_primary' => $transaction_primary,
                  'device_type' => $data['biodevice'],
                  'bio_data' => $data['biodata'],
                ];
                if ($this->common_model->insert($biomatric, 'biometric_data')) {
                    if($data['selectTransactionsTypes'] == "BE" ){
                         $response = self::transection_service($this->data['submitTransection']);
                    }
                    elseif($data['selectTransactionsTypes'] == "CW"){
                         $response = self::apescashwithdraw($this->data['submitTransection']);
                  
                    }elseif($data['selectTransactionsTypes'] == "MS"){
                        $response = self::apesmini($this->data['submitTransection']);
                    }else{
                        $response = self::aadharpay($this->data['submitTransection']);
                    }
                    
                    
                    if (isJson($response)) {
                      $result = json_decode($response);
                      $result->transection_id = $this->data['saveTransection']['transection_id'];
                      $result->aadharno = $data['adharCardNumber'];
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
                      if ($result->status) {
                          if($data['selectTransactionsTypes'] == "CW"){
                    
                               self::commition_distribute( $this->session->userdata('user_id'),  $this->data['saveTransection']['transection_amount']);
                         
                            }
                            
                            if($data['selectTransactionsTypes'] == "M"){
                                
                                $userWallet = $this->common_model->get_user_wallet_balance($this->session->userdata('user_id'));
                                  
                                $subcharge  = $this->common_model->sub_charge($this->data['saveTransection']['transection_amount'],4);
                                
                                $service = 4;
                                
                                if($subcharge->c_flate == 0){
                                    
                                    $flate_charge = $this->data['saveTransection']['transection_amount'] * $subcharge->charge / 100 ;
                                    $charge = $flate_charge ;
                                    $total = $this->data['saveTransection']['transection_amount'] - $flate_charge ;
                                    
                                }else{
                                    
                                    $total =  $this->data['saveTransection']['transection_amount'] - $subcharge->charge;
                                    $charge = $subcharge->charge;
                                    
                                }
                                
                                
                                if ($userWallet != 'none') {
                                    
                                    $updateBalance = $userWallet->balance + $total;    //Deduct balance
                                    $updateWallet = [
                                                        'balance' => $updateBalance,
                                                    ];
                                    $logme = [
                                              'wallet_id' => $userWallet->wallet_id,
                                              'member_to' =>  $this->session->userdata('user_id'),
                                              'amount' =>  $this->data['saveTransection']['transection_amount'],
                                              'surcharge' => $charge,
                                              'refrence' =>  $this->data['submitTransection']['referenceno'],
                                              'service_id' => $service,
                                              'stock_type'=> 'Aadhar Pay',
                                              'status' => 'success',
                                              'balance' =>  $userWallet->balance,
                                              'closebalance' => $updateBalance,
                                             'type' => 'debit',
                                             'mode' => 'Aadhar Pay',
                                             'bank' =>  'Aadhar Pay',
                                             'narration' => 'Aadhar Pay Transaction ',
                                             'date'=> date("Y-m-d h:i:sa") ,
                                            ];
                            
                                  
                                                    
                                    if($this->common_model->insert($logme, 'wallet_transaction')) { //update deducted balance
                                      
                                      $message = [
                                        'msg' => 'Your wallet balance Credited Rs. ' . $total. ' available balance is ' . $updateBalance,
                                        'user_id' => $this->session->userdata('user_id')
                                      ];
                                         $this->set_notification($message);
                                         
                                        $this->common_model->update($updateWallet, 'member_id',$this->session->userdata('user_id'), 'wallet');
                                        
                                    }
                                } 
                                
                                
                    
                              self::aadharpay_charge( $this->session->userdata('user_id'), 4,  $this->data['saveTransection']['transection_amount']);
                         
                            }
                          
                     
                      } else {
                        // $message = [
                        //   'msg' => 'Your wallet balance credited ' . $commision->g_commission . ' available balance is ' . $updateBalance,
                        //   'user_id' => $value['user_id']
                        // ];
                        // $this->set_notification($message);
    
                      }
                      echo $this->load->view('transection-summary', $this->data, true);
                  }else{
    
                  }
                }
              }else{
                echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
              }
          }
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

    public function stan( ) {
     
        date_default_timezone_set("Asia/Calcutta");
        $today = date("H");
        $year = date("Y"); 
        $year =  $year;
        $year = substr( $year, -1);   
        $daycount =  date("z")+1;
        $ref = $year . $daycount. $today. mt_rand(100000, 999999);
        return $ref;
        // return mt_rand(99999999999, 999999999999);
    }

  public function utan( $node ) {

    // return $node . '00' . round(microtime(true));
    return mt_rand(100000, 999999);

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



// ak
public function get_transectionlist() {



  		$uri = $this->security->xss_clean($_GET);

  		if (isset($uri['key']) && !empty($uri['key'])) {

  			$query = '';



  			$output = array();



  			$duid = $uri['key'];



  			$list = $uri['list'];



  			$data = array();


           


        if (isAdmin($this->session->userdata('user_roles'))) {
                
          $query = "select * from submit_transection where service_id = {$this->data['serid']} ";

          $recordsFiltered = $this->users_model->row_count($query);

        }else{
          
          $member_id=$this->session->userdata('member_id');
          
          $query .= "SELECT * FROM submit_transection WHERE service_id = {$this->data['serid']} AND member_id='$member_id'";

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
                  if($uri['status']=="success")
                  {
                       $query.=" AND (`transection_msg` LIKE '%success%' OR `transection_msg` LIKE '%Request Completed%') ";  
                  }
                  elseif($uri['status']=="failure")
                  {
                       $query.=" AND `transection_msg` LIKE '%failure%' ";  
                  }
                  else
                  {
                      $query.=" AND (`transection_msg` NOT LIKE '%success%' AND `transection_msg` NOT LIKE '%Request Completed%' AND `transection_msg` NOT LIKE '%failure%') ";
                  }
                 
              }
        
        switch ($list) {



          case 'all':



          break;



  				case 'new':

  					$query .= " AND user.kyc_status = 'new' ";

  					break;



  				case 'pending':

  					$query .= " AND user.kyc_status = 'pending' ";

  					break;



  				case 'verify':

  					$query .= " AND user.kyc_status = 'verify' ";



  					break;



  				default:

            echo json_encode(['error' => 1, 'msg' => 'request not allowed']);

            break;



  			}

    	    if (!empty($_GET["search"]["value"])) {
                $query .= ' AND submit_transection.reference_number LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= ' OR submit_transection.transection_mobile LIKE "%' . $_GET["search"]["value"] . '%" ';
           
    	    }

  			if(!empty($_GET["order"])){
  				$query .= 'ORDER BY '.$_GET['order']['0']['column'].' '.$_GET['order']['0']['dir'].' ';
  			}

  			else{
  				$query .= 'ORDER BY submit_transection.primary_id DESC ';
  			}

  			if($_GET["length"] != -1){
  				$query .= 'LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];
  			}

  			$sql = $this->db->query($query);
  			$result = $sql->result_array();
  			

        $i = 1;

        foreach ($result as $row) {
         if(!empty($row['transection_response']))
        {
         $adhar=json_decode($row['transection_response']);
         if(isset($adhar->last_aadhar))
         {
  		 $adharstring="XXXXXXXX".$adhar->last_aadhar;
         }
         else
         {
             $adharstring='';
         }
        }
        else
        {
            $adharstring="XXXXXXXXXXXX";
        }
        $sub_array = array();
        
        //   $sub_array[] = '<a href="' . base_url('aeps2/print/') . $row['reference_number'] . '"> <button type="button" class="btn btn-sm btn-secondary"  data-placement="bottom" title="Edit Menu Information"><i class="fa fa-print"></i></button></a>';

        //   $sub_array = array();
        
            if($row['transection_type'] == 'CW' && $row['transection_status'] == 0 && $row['transection_respcode'] == 0)
              $sub_array[] = '<a href="' . base_url('aeps2/print/') . $row['reference_number'] . '"> <button type="button" class="btn btn-sm btn-secondary"  data-placement="bottom" title="Edit Menu Information"><i class="fa fa-print"></i></button></a>
                                       <button type="button" class="btn btn-sm btn-secondary" onclick="transaction_updated(`' . $row['reference_number'] . '`)" ><i class="fa fa-refresh"></i></button>';
            else
                $sub_array[] = '<a href="' . base_url('aeps2/print/') . $row['reference_number'] . '"> <button type="button" class="btn btn-sm btn-secondary"  data-placement="bottom" title="Edit Menu Information"><i class="fa fa-print"></i></button></a>';

          $sub_array[] = $row['member_id'];

          $sub_array[] = $row['reference_number'];

          $sub_array[] = $row['transection_msg'];

          $sub_array[] = $row['transection_mobile'];

          $sub_array[] = $row['transection_amount'];
          
        //   $sub_array[] = $row['transection_bank_code'];

          $sub_array[] = $this->common_model->select_option($row['transection_bank_code'], 'iinno', 'bank_list')[0]['bankName'];
          
          $sub_array[] = $adharstring;
          
          $sub_array[] = $row['transection_id'];

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
  	
  	  public function edit($id)
      {
        $menu= $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
        echo json_encode($menu[0]);
      }



            
    public function delete($id)
  {
    if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
       echo 1;
    } else {
      echo 0;
    }
  }
  
   public function addupdate() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);

      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = $this->data['serid'];

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
            redirect('aeps2/commission', 'refresh');
    }
  }
  
//   
    public function get_commission($role,$service,$amount) {
      $slab =  $this->commission_model->get_commision_by_role($role,$service, $amount);
  }
  
  public function commition_distribute($id,  $transection ) {


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
                  'refrence' =>  "AEPSCASH_".self::walletrrn(),
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
                
              
            
            if($id= $this->common_model->insert($logme, 'wallet_transaction')) {
                
              $message = [
                'msg' => 'Your wallet balance credited ' . $createdbalance . ' available balance is ' . $updateBalance,
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
      }
    }
    
    
    public function aadharpay_charge($id, $service,  $transection ) {

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

    public function checkparent($id, &$parents = array(), $level = 1) {
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
    
    public function onboarding(){
        
        $this->data['main_content'] = $this->load->view('onboarding', $this->data, true);
        $this->data['is_script'] = $this->load->view('script', $this->data, true);
        $this->load->view('layout/index', $this->data);
        
        
    }
  
    public function onboardsubmit(){
      if($_POST){
            $this->client = new Client();
            $data = $this->security->xss_clean($_POST);
            $data1 = [
                'mobile' => $data['number'] ,
                'email' => $data['email'] ,
                'name' => $data['name'] , 
                'merchant' => $data['merchant'],
                'api_key' => $this->data['api_key']
                ];
        
              $url = 'https://vitefintech.com/viteapi/pay/onbording';
              #guzzle
              try {
                  $response = $this->client->request('POST', $url, [
                    'form_params' => $data1,
                    
                  ]);
        
                    echo $response->getBody()->getContents();
        
              } catch (GuzzleHttp\Exception\BadResponseException $e) {
                #guzzle repose for future use
                $response = $e->getResponse();
                $responseBodyAsString = $response->getBody()->getContents();
                print_r($responseBodyAsString);
              }
      }
      
    }
    
     public function aadharpaycharge(){
         
        $this->data['charge'] =$this->commission_model->get_surcharge($this->data['serid']);
        $this->data['main_content'] = $this->load->view('subcharge/index', $this->data, true);
        $this->data['is_script'] = $this->load->view('subcharge/script', $this->data, true);
        $this->load->view('layout/index', $this->data);
        
        
    }
    
    public function addupdatesub() {
      
        echo $this->load->view('subcharge/edit', $this->data, true);

    }
    
    public function editsub($id)
      {
        $menu= $this->common_model->select_option($id, 'service_charge_id', 'service_charge');
        echo json_encode($menu[0]);
      }
      
       function update_surcharge(){
            if ($_POST) {
                
               $data = $this->security->xss_clean($_POST);
               $field = $data['service_charge_id'];
               $logme['service_id']=$data['service_id'];
               $logme['start_range']=$data['start'];
               $logme['end_range']=$data['end'];
               $logme['charge']=$data['charge'];
                   if ($this->common_model->update($logme, "service_charge_id", $field , 'service_charge')) {
                    $this->session->set_flashdata(
                      array(
                        'status' => 1,
                        'msg' => " Updated Successfully"
                      )
                    );
                    redirect('aeps2/aadharpaycharge', 'refresh');
                    
                }
            }
        }
        
    public function print($id){
        
        $query = "SELECT * FROM submit_transection WHERE reference_number = '{$id}'";
      
      	$sql = $this->db->query($query);
  			
  		$this->data['result'] = $sql->row();
  		
  		echo $this->load->view('print', $this->data, true);
  		
    }
    
    public function check_status(){

      $this->client = new Client();

      $data = $this->security->xss_clean($_POST);

      if($_POST){

        $member_id = $this->session->userdata('member_id'); 

        $data = [

          'api_key' => $this->data['api_key'],
          'reference' => $data['referenceno']
        
        ];
        
        $url = 'https://vitefintech.com/viteapi/pay/query';
        #guzzle
        try {
            $response = $this->client->request('POST', $url, [
              'form_params' => $data,
              
            ]);

            $result = json_decode($response->getBody()->getContents());

            $action = [
              
              'transection_status' =>  $result->status,
              'transection_msg' => $result->message,
              'transection_respcode' => $result->response_code,
              'transection_response' => $response->getBody()->getContents(),
              
            ];
             
            if($this->common_model->update($action, 'reference_number', $data['reference'], 'submit_transection')){
              
              if ($result->status == TRUE) {
                
                self::commition_distribute( $member_id, $result->amount );
                
              }
              
            } 
            
            return $response->getBody()->getContents();

        } catch (GuzzleHttp\Exception\BadResponseException $e) {
          #guzzle repose for future use
          $response = $e->getResponse();
          $responseBodyAsString = $response->getBody()->getContents();
          print_r($responseBodyAsString);
        }
      }
    

    }
    
    public function transaction_update($rrn){

      $this->client = new Client();

        // $user_id = $this->session->userdata('user_id');
        // transaction_check($rrn);pre("fds");exit;
        
        if($user_id = transaction_check($rrn)){
            
            $user_id = $this->common_model->find_member('user','member_id',$user_id->member_id);
            // pre($user_id);exit;
            
            $data = [
    
              'api_key' => $this->data['api_key'],
              'reference' => $rrn
            
            ];
            
            
            $url = 'https://vitefintech.com/viteapi/pay/query';
            #guzzle
            try {
                $response = $this->client->request('POST', $url, [
                  'form_params' => $data,
                  
                ]);
                
                
                $result = json_decode($response->getBody()->getContents());
                
                $action = [
                  
                  'transection_status' =>  $result->status,
                  'transection_msg' => $result->message,
                  'transection_respcode' => $result->response_code,
                  'transection_response' => $response->getBody()->getContents(),
                  
                ];
                 
                if($this->common_model->update($action, 'reference_number', $data['reference'], 'submit_transection')){
                  
                    if ($result->status == true && $result->response_code == 1) {
                    
                        self::commition_distribute( $user_id->user_id, $result->amount );
                        
                        $result = [
                              "Status" => 200 ,
                              "Msg" => "Transaction Updated."
                              ];
                          
                          echo json_encode($result);
                    
                    }else{
                      
                      $result = [
                          "Status" => 200 ,
                          "Msg" => "Transaction Updated! Commission Not Receive For Some Technical Issue  "
                          ];
                      
                      echo json_encode($result);
                      
                    }
                  
                }else{
                    
                    $result = [
                          "Status" => 403 ,
                          "Msg" => "Transaction Update Problem!"
                          ];
                      
                      echo json_encode($result);
                    
                } 
                
                // echo 1 ;
                
                // return $response->getBody()->getContents();
    
            } catch (GuzzleHttp\Exception\BadResponseException $e) {
              #guzzle repose for future use
              $response = $e->getResponse();
              $responseBodyAsString = $response->getBody()->getContents();
              print_r($responseBodyAsString);
            }
        }else{
            
            $result = [
                          "Status" => 403 ,
                          "Msg" => "Transaction Already Updated!"
                          ];
                      
                      echo json_encode($result);
            
        }

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


           


        if (isAdmin($this->session->userdata('user_roles'))) {
                
          $query = "select * from submit_transection where service_id = {$this->data['serid']} ";

          $recordsFiltered = $this->users_model->row_count($query);

        }else{
          
          $member_id=$this->session->userdata('member_id');
          
          $query .= "SELECT * FROM submit_transection WHERE service_id = {$this->data['serid']} AND member_id='$member_id'";

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
                  if($uri['status']=="success")
                  {
                       $query.=" AND (`transection_msg` LIKE '%success%' OR `transection_msg` LIKE '%Request Completed%') ";  
                  }
                  elseif($uri['status']=="failure")
                  {
                       $query.=" AND `transection_msg` LIKE '%failure%' ";  
                  }
                  else
                  {
                      $query.=" AND (`transection_msg` NOT LIKE '%success%' AND `transection_msg` NOT LIKE '%Request Completed%' AND `transection_msg` NOT LIKE '%failure%') ";
                  }
                 
              }
        
        
        switch ($list) {



          case 'all':



          break;



  				case 'new':

  					$query .= " AND user.kyc_status = 'new' ";

  					break;



  				case 'pending':

  					$query .= " AND user.kyc_status = 'pending' ";

  					break;



  				case 'verify':

  					$query .= " AND user.kyc_status = 'verify' ";



  					break;



  				default:

            echo json_encode(['error' => 1, 'msg' => 'request not allowed']);

            break;



  			}

	    if (!empty($_GET["search"]["value"])) {
        $query .= ' AND submit_transection.member_id LIKE "%' . $_GET["search"]["value"] . '%" ';
        $query .= ' OR submit_transection.transection_id LIKE "%' . $_GET["search"]["value"] . '%" ';
        $query .= ' OR submit_transection.reference_number LIKE "%' . $_GET["search"]["value"] . '%" ';
        $query .= ' OR submit_transection.transection_mobile LIKE "%' . $_GET["search"]["value"] . '%" ';
       
      }
  			if(!empty($_GET["order"]))

  			{

  				$query .= 'ORDER BY '.$_GET['order']['0']['column'].' '.$_GET['order']['0']['dir'].' ';

  			}

  			else

  			{

  				$query .= 'ORDER BY submit_transection.primary_id DESC ';

  			}


            if(isset($_GET['start']) && isset($_GET['length'])){
  		    	if($_GET["length"] != -1 && $_GET['start']!='' && $_GET['length']!='')

  			    {

  			    	$query .= 'LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];
  
  		    	}
            }

  			$sql = $this->db->query($query);
  			

  			$result = $sql->result_array();
  		
  			$spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'S.No');
            $sheet->setCellValue('B1', 'MEMBER ID');
            $sheet->setCellValue('C1', 'Transition ID');
            $sheet->setCellValue('D1', 'Status');
            $sheet->setCellValue('E1', 'Transition Mobile');
            $sheet->setCellValue('F1', 'Transition Amount');
            $sheet->setCellValue('G1', 'Adhar Numner');
            $sheet->setCellValue('H1', 'Bank Name');
            $sheet->setCellValue('I1','Reference Number');
            $sheet->setCellValue('J1','Created');
            $rows = 2;
            $i=1;
            foreach ($result as $val){
                  if(!empty($val['transection_response']))
                 {
         $adhar=json_decode($val['transection_response']);
         if(isset($adhar->last_aadhar))
         {
  		 $adharstring="XXXXXXXX".$adhar->last_aadhar;
         }
         else
         {
             $adharstring='';
         }
        }
                 else
                 {
            $adharstring="XXXXXXXXXXXX";
        }
                 $bank=$this->common_model->select_option($val['transection_bank_code'], 'iinno', 'bank_list');
                 if(isset($bank))
                 {
                  if(!empty($bank[0]['bankName']))
                  {
                      $bank_list=$bank[0]['bankName'];
                  }
                  else
                  {
                       $bank_list=' ';
                  }
                  
              }
                 else
                 {
                  $bank_list=' '; 
                 }
               $sheet->setCellValue('A'. $rows, $i);
               $sheet->setCellValue('B'. $rows,$val['member_id']);
               $sheet->setCellValue('C' . $rows, $val['transection_id']);
               $sheet->setCellValue('D' . $rows,$val['transection_msg']);
               $sheet->setCellValue('E' . $rows,$val['transection_mobile']);
               $sheet->setCellValue('F' . $rows,$val['transection_amount']);
              $sheet->setCellValue('G' . $rows,$adharstring);
    
               $sheet->setCellValue('H' . $rows,$bank_list);
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
  

}
