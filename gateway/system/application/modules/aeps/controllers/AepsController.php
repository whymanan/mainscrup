<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;

class AepsController extends Vite {


  public $data = array();

  public $client;


  public function __construct() {
      parent::__construct();
      $this->data['active'] = 'AEPS';
      $this->load->model('common_model');
      $this->load->model('users_model');
      $this->load->model('commission_model');
      $this->data['breadcrumbs'] = [array('url' => base_url('dashboard'), 'name' => 'Dashboard')];
  }

  public function index() {
    $this->data['param'] = $this->paremlink('add');
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
       $data1['start_range']=$data['start'];
        $data1['end_range']=$data['end'];
        $data1['role_id']=$data['user_role'];
       $data1['g_commission']=$data['commision'];
       $data1['service_id']=$data['service_id'];
       $data1['max_commission']=$data['max'];
       $data1['c_flat	']=isset($data['flat'])?1:0;
        $data1['created	']=date('Y-m-d hh:mm:ss');
    $this->common_model->insert($data1,'service_commission');
    redirect('commission', 'refresh');
        
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
            
          $query .= "SELECT * from service_commission  where role_id = '$role_id' ";

          $recordsFiltered = $this->users_model->row_count($query);

        }else{
                
          $query .= "SELECT * from service_commission where role_id = '$role_id' ";

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
        		//	echo $query;exit;

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

  public function aepsTransectionForm() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);

      if (isset($data['bankCode'])) {

        $this->data['bankCode'] = $data['bankCode'];

        echo $this->load->view('add', $this->data, true);

      }else{
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

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
                if (isJson($response)) {
                  $result = json_decode($response);
                  $result->transection_id = $saveTransection['transection_id'];
                  $this->data['transaction'] = $result;
                  $action = [
                    'transection_status' =>  $result->status,
                    'transection_msg' => $result->msg,
                    'transection_respcode' => $result->respcode,
                    'transection_response' => $response,
                  ];
                  $this->common_model->update($action, 'primary_id', $transaction_primary, 'submit_transection');
                  if ($result->status) {
                    self::commition_distribute($saveTransection['member_id'], $saveTransection['transection_amount']);
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
                
          $query = "select * from submit_transection where service_id = 1 ";

          $recordsFiltered = $this->users_model->row_count($query);

        }else{
          
          $member_id=$this->session->userdata('member_id');
          
          $query .= "SELECT * FROM submit_transection WHERE service_id = '1' AND member_id='$member_id'";

          $recordsFiltered = $this->users_model->row_count($query);

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

  			if(!empty($_GET["order"]))

  			{

  				$query .= 'ORDER BY '.$_GET['order']['0']['column'].' '.$_GET['order']['0']['dir'].' ';

  			}

  			else

  			{

  				$query .= 'ORDER BY submit_transection.created DESC ';

  			}



  			if($_GET["length"] != -1)

  			{

  				$query .= 'LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];

  			}

  			$sql = $this->db->query($query);

  			$result = $sql->result_array();

  		

        $i = 1;

        foreach ($result as $row) {

          $sub_array[] = '<a href="' . base_url('users/edit?q=') . $row['primary_id'] . '"> <button type="button" class="btn btn-sm btn-secondary"  data-placement="bottom" title="Edit Menu Information"><i class="fa fa-pencil-alt"></i></button></a>';

           

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

        $service = 1;

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
        $logme['c_flat'] = $form['flat'];
        $logme['role_id'] = $form['role_id'];
        $logme['service_id'] = $form['service_id'];
      


      if ($this->common_model->update($logme, "service_commission_id", $field , 'service_commission')) {
       $this->session->set_flashdata(
              array(
                'status' => 1,
                'msg' => " Updated Successfully"
              )
            );
            redirect('commission', 'refresh');
    }
  }
  


// 

}
