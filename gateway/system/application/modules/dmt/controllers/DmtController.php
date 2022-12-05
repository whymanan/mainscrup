<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;

class DmtController extends Vite {


  public $data = array();

  public $client;

  public $tnxType = 'dmtTx';


  public function __construct() {
      parent::__construct();
      $this->data['active'] = 'DMT';
      $this->data['serid'] = '2';
      $this->load->model('common_model');
      $this->load->model('commission_model');
      $this->load->model('transaction_model');
      $this->load->model('users_model');
        $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));

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
 public function get_surcharge_value() {
    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      $amount = $data['amount'];
      $commision=0;
        $slab=$this->transaction_model->get_surcharge_slab($data['service']);
        //pre($slab);exit;
        if(!empty($slab)){
             foreach($slab as $row){
                    if($amount>=$row['start_range'] && $amount<=$row['end_range']){
                       
                     $commision  = $row['charge'];
                    
                     break;
                    }
                }
                echo $commision;
        }else{
            echo $commision;
        }
        
    }else{
            echo 0;
        }
  }




  public function dmtAddBeneficiaryForm() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);

      if (isset($data['mobile'])) {
        $this->data['phone_no'] = $data['mobile'];
        if ($beneficiary = $this->common_model->beneficiary_exist($data['mobile'])) {
          $beneficiary->error = 0;
          $beneficiary->msg = 'Beneficiary Account Already Added';
          echo json_encode($beneficiary);
        }else{
          echo $this->load->view('add', $this->data, true);
        }
      }else{
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
public function get_commission($role,$service,$amount){
     $slab=  $this->common_model->get_commision_by_role($role,$service);
                // pre($slab);exit;
                 $commision  =0;
                foreach($slab as $row){
                    if($amount>=$row['start_range'] && $amount<=$row['end_range']){
                        if($row['c_flat'])
                     $commision  = $row['g_commission'];
                     else
                     $commision=(float)$amount* $row['g_commission']*0.01;
                     break;
                    }
                }
                return $commision;
}
  public function submitBeneficiaryForm() {

    if ($_POST) {
      $data = $this->security->xss_clean($_POST);


      if ($this->session->userdata('latitude')) {
        $location = $this->session->userdata('latitude').'|'.$this->session->userdata('longitude');
      }
           
        
         
      if ($data) {
        $net_total=$data['transaction_amount']+$data['surcharge'];
        $saveForm = [
          'title' => $data['title'],
          'first_name' => $data['first_name'],
          'last_name' => $data['last_name'],
          'beneficiary_name' => $data['beneficiary_name'],
          'beneficiary_mobile' => $data['beneficiary_mobile'],
          'beneficiary_account_number' => $data['beneficiary_account_number'],
          'beneficiary_ifsc' => $data['beneficiary_ifsc'],
          'beneficiary_bank_name' => $data['beneficiary_bank_name'],
          'created' => current_datetime(),
        ];

        $this->data['submitTransection'] = [
          'beneficiaryAccountNumber' => $data['beneficiary_account_number'],
          'beneficiaryIfscCode' => $data['beneficiary_ifsc'],
          'transactionAmount' => $data['transaction_amount'],
          'customerMobile' => $data['beneficiary_mobile'],
          'customerName' => $data['beneficiary_name'],
          'referenceNo' => self::referenceNo(),
          'remark' => $data['dmt_remark']
        ];
        
    
        $saveTransection = [
            'transection_id' => self::utan($data['beneficiary_mobile']),
            'transection_type' => $this->tnxType,
            'member_id' => $this->session->userdata('member_id'),
            'transection_amount' =>  $data['transaction_amount'],
            'transection_bank_code' => $data['beneficiary_ifsc'],
            'transection_bank_ifsc' => $data['beneficiary_ifsc'],
            'reference_number' =>   $this->data['submitTransection']['referenceNo'],
            'transection_mobile' => $data['beneficiary_mobile'],
            'api_requist' => 'dmtTx',
            'location' => $location,
            'service_id' => $this->data['serid']
          ];

          if ($beneficiary_primary = $this->common_model->insert($saveForm, 'beneficiary_list')) {

            if ($transaction_primary = $this->common_model->insert($saveTransection, 'submit_transection')) {


              $response = self::transection_service();
                pre($response);exit;
                if (isJson($response)) {
                  $result = json_decode($response);
                
                //get Commission
                $user_commision = self::get_commission($this->session->userdata('user_roles'),$this->data['serid'],$net_total);
                 
                 $parent=  $this->users_model->get_parent($this->session->userdata('user_id'));
                 $role=  $this->users_model->get_parent_role($parent);
         
                $parent_commision = self::get_commission($role,$this->data['serid'],$net_total);
             
                //get wallet amount
                    $admin_wallet= $this->common_model->get_user_wallet($parent);
                    $user_wallet= $this->common_model->get_user_wallet($this->session->userdata('user_id'));
                $bal1=array("balance" =>  $parent_commision+$admin_wallet );
                $bal2=array("balance" =>  $user_commision+$user_wallet );
                
                $this->common_model->update($bal1, 'member_id', $parent, 'wallet');
                $this->common_model->update($bal2 , 'member_id', $this->session->userdata('user_id'), 'wallet');
                
                  
                echo $user_commision."\n";
                echo $parent_commision;
                
                //update user Balance Wallet
                 $user_wallet= $this->common_model->get_user_wallet($this->session->userdata('user_id'));
                 $user_wallet=$user_wallet - (float)$net_total; 
               $admin_wallet= $this->common_model->get_user_wallet($parent);
                $admin_wallet=$admin_wallet+$data['surcharge'];
                $this->common_model->update(array('balance' =>$user_wallet ), 'member_id',$this->session->userdata('user_id')
                , 'wallet');    
                $this->common_model->update(array('balance' =>$admin_wallet ), 'member_id', $parent, 'wallet');
           
                
               // wallet transaction
                $logme = [
                'member_to' =>  $this->session->userdata('user_id'),
                'member_from' =>  $this->users_model->get_parent($this->session->userdata('user_id')),
                'amount' =>  $data['transaction_amount'],
                'surcharge' =>$data['surcharge'],
                'refrence' =>  $this->data['submitTransection']['referenceNo'],
                'commission' =>  $user_commision,
                'service_id' => $this->data['serid'],
                'status' => 'success',
                'stock_type'=> $this->tnxType,
               'status' => 'success',
                'date'=> date('Y-m-d'),
      ];
     $id= $this->common_model->insert($logme, 'wallet_transaction');
                
                
                  if ($result->respCode == 0) {
                      
                    $result->status = 1;
                  }else{
                    $result->status = 0;
                  }
                  $action = [
                    'transection_status' =>  $result->status,
                    'transection_msg' => $result->respMsg,
                    'transection_respcode' => $result->respCode,
                    'transection_response' => $response,
                  ];
                  // Update RESPONSE in transection
                  ;
                   
                  if ($this->common_model->update($action, 'primary_id', $transaction_primary, 'submit_transection')) {
                    $transaction = $this->transaction_model->get_transactionById($transaction_primary);
                    $transaction->error = '0';
                    $transaction->msg = 'Action Success';
                    $transaction->beneficiary = (object)$saveForm;
                    $this->data['transaction'] = $transaction;
                    echo $this->load->view('transection-view', $this->data, true);
                  } else {
                    echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
                  }
              }else{
                  echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
              }
            }else{
              echo json_encode(['error' => 1, 'msg' => 'Transection Submit Failed']);
            }

          }else{
            echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
          }
      }
    }
  }


  public function transection_service() {
      $this->client = new Client();

      $url = _SERVICE_API_ . 'dmttransaction';
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

  public function stan() {
    return mt_rand(99999, 999999);
  }

  public function referenceNo() {

    return round(microtime(true)) . self::stan();

  }

  public function utan( $node ) {

    return $node . '00' . round(microtime(true));

  }



  public function tHistory() {
    $this->data['breadcrumbs'] = [array('url' => base_url('aeps'), 'name' => 'DMT'), array('url' => base_url('history'), 'name' => 'Transection History')];
    $this->data['param'] = $this->paremlink('/');
    $this->data['main_content'] = $this->load->view('list', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }

  public function get_history() {
  		$uri = $this->security->xss_clean($_GET);
  		// pre($uri);exit;

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
          $query .= ' AND(member_id LIKE "%' . $_GET["search"]["value"] . '%" ';
          $query .= ' OR u.member_id LIKE "%' . $_GET["search"]["value"] . '%" ';
          $query .= ' OR phone LIKE "%' . $_GET["search"]["value"] . '%" ';
          $query .= ' OR user.email LIKE "%' . $_GET["search"]["value"] . '%" ';
          $query .= ' OR user.user_status LIKE "%' . $_GET["search"]["value"] . '%" ';
          $query .= ' OR user.kyc_status LIKE "%' . $_GET["search"]["value"] . '%" )';
        }
        if(isset($_GET["searchByCat"]) ) {
           $query .= " AND ".$_GET["searchByCat"]." = '". $_GET["searchValue"]."'  ";
        }
        if(isset($_GET["date_from"]) ) {
           $query .= " AND created >= '". $_GET["date_from"]."'  ";
        }
        if(isset($_GET["date_to"]) ) {
           $query .= " AND created <= '". $_GET["date_to"]."'  ";
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
// pre($sql);exit;
  			$result = $sql->result_array();

        $i = 0;

        foreach ($result as $row) {

          $i = $i + 1;
          
          $sub_array = array();

          $sub_array[] = $i;
          
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

         


        }







  			$output["draw"] = intval($_GET["draw"]);

        $output["recordsFiltered" ] =$recordsFiltered;

  			$output["recordsTotal"] =$recordsFiltered;

  			$output["data"] = $data;



  			echo json_encode($output);

  		}

  	}

 public function surcharge() {
    $this->data['param'] = $this->paremlink('/');
     $this->data['charge'] =$this->commission_model->get_surcharge(2);
     //pre($this->data['charge']);exit;
    $this->data['main_content'] = $this->load->view('subcharge/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('subcharge/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
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
    
    if (!empty($uri)) {
      $query = '';

      $output = array();


      

      $data = array();

    if (isAdmin($this->session->userdata('user_roles'))) {
            
          $query .= "SELECT * from service_commission  where role_id = '$role_id' AND service_id=2 ";

          $recordsFiltered = $this->users_model->row_count($query);

        }else{
                
          $query .= "SELECT * from service_commission where role_id = '$role_id' AND service_id=2 ";

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
       $data1['start_range']=$data['start'];
        $data1['end_range']=$data['end'];
        $data1['role_id']=$data['role_id'];
       $data1['g_commission']=$data['commision'];
       $data1['service_id']=$this->data['serid'];
       $data1['max_commission']=$data['max'];
       $data1['c_flat	']=isset($data['flat'])?1:0;
        $data1['created	']=date('Y-m-d hh:mm:ss');
    if($this->common_model->insert($data1,'service_commission')){;
    
      $this->session->set_flashdata(
              array(
                'status' => 1,
                'msg' => " Commission Added Successfully"
              )
            );
    
    redirect('dmt/commission', 'refresh');
    }
    }
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
            redirect('dmt/surcharge', 'refresh');
        }
        
    }
  }
  public function addcommission(){
       $this->data['param'] = $this->paremlink('/');
    $this->data['main_content'] = $this->load->view('commission/add', $this->data, true);
    $this->data['is_script'] = $this->load->view('commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
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
    // pre($form);exit;
 
        $logme['start_range'] = $form['start'];
        $logme['end_range'] = $form['end'];
        $logme['g_commission'] = $form['commision'];
        $field = $form['service_commission_id']; 
     
        $logme['max_commission'] =$form['max'];
        $logme['c_flat'] = $form['flat'];
        $logme['role_id'] = $form['role_id'];
        $logme['service_id'] = $this->data['serid'];
      


      if ($this->common_model->update($logme, "service_commission_id", $field , 'service_commission')) {
       $this->session->set_flashdata(
              array(
                'status' => 1,
                'msg' => " Updated Successfully"
              )
            );
            redirect('dmt/commission', 'refresh');
    }
  }
    

}
