
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use GuzzleHttp\Client;

class RechargeController extends Vite {

  public $data = array();
  public $client;
  public function __construct() {
      parent::__construct();
    $this->load->model('common_model');
    $this->load->model('menu_model');
          $this->load->model('commission_model');
 //$this->load->model('wallet_model');
    $this->load->model('users_model');
          $this->data['serid'] = '4';

     $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));
      if($_SESSION['kyc_status']!='verify'){
        redirect('users/kyc', 'refresh');
       
        
    }else{
      $this->data['active'] = 'recharge';
      $this->data['breadcrumbs'] = [array('url' => base_url('recharge'), 'name' => 'recharge')];
    }
  }

  public function index() {
    $this->data['main_content'] = $this->load->view('index', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
 public function mobile() {
   
    $this->data['main_content'] = $this->load->view('mobile', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
 public function get_mobile(){
     
     $data = $this->security->xss_clean($_POST);
      $this->client = new Client();
        $this->data['submitTransection'] = [
          'apikey' => '1435743661',
          'username' => 'G271762461',
          'format' => 'json',
          'mobile' => $data['mobile'],
         
        ];
   // print_r( $this->data['submitTransection']);exit;
      #guzzle
      try {
          $response = $this->client->request('GET', "https://mobilerechargenow.com/api/mobileinfo.php", [
            
            'query' => $this->data['submitTransection'],
          ]);

           $result =$response->getBody()->getContents() ;
           
        //   $result=json_decode($result);
           print_r($result);
        

      } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
      }
 } 

public function fetch_plan(){
     $this->client = new Client();
      $data = $this->security->xss_clean($_POST);
     // print_r($data);exit;
      try {
          $response = $this->client->request('GET', "https://mobilerechargenow.com/recharge-plan.php", [
            
            'query' => [
          'apikey' => '1435743661',
          'username' => 'G271762461',
          'format' => 'json',
          'circle' => $data['circle'],
         'operator' => $data['operator'],
         'type' => $data['type'],
        ],
          ]);

           $result['data'] =$response->getBody()->getContents() ;
           
           $result['data']=json_decode($result['data']);
        //echo "<pre>";   print_r($result);exit;
           echo $this->load->view('plans',$result , true);

      } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
      }
}



public function fetch_dth_plan(){
     $this->client = new Client();
      $data = $this->security->xss_clean($_POST);
     // print_r($data);exit;
      try {
          $response = $this->client->request('GET', "https://mobilerechargenow.com/dth-plan.php", [
            
            'query' => [
          'apikey' => '1435743661',
          'username' => 'G271762461',
         "operator" => $data['operator']
        ],
          ]);

           $result['data'] =$response->getBody()->getContents() ;
           
           $result['data']=json_decode($result['data']);
        //echo "<pre>";   print_r($result);exit;
           echo $this->load->view('dth_plan',$result , true);

      } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
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

  public function commition_distribute($id,  $transection) {


    $parentsList = self::checkparent($id);

      foreach ($parentsList as $key => $value) {
        $commision = $this->commission_model->get_commision_by_role($value['role_id'], $this->data['serid'], $transection);
        if (!empty($commision)) {
          $userWallet = $this->common_model->wallet_balance($value['user_id']);
          if ($userWallet != 'none') {
              
            $updateBalance = $userWallet + $commision->g_commission;    // add commission
            $updateWallet = [
              'balance' => $updateBalance,
            ];
            if($this->common_model->update($updateWallet, 'member_id', $value['user_id'], 'wallet')) {
              $message = [
                'msg' => 'Your wallet balance credited ' . $commision->g_commission . ' available balance is ' . $updateBalance,
                'user_id' => $value['user_id']
              ];
              $this->set_notification($message);
               $logme = [
                  'member_to' =>  $value['user_id'],
                  'member_from' => $value['parent'] ,
                  'amount' =>  $updateBalance,
                 // 'surcharge' => $data['surcharge'],
                  'refrence' =>  $this->data['saveTransection']['transection_id'],      //refrence for submit transaction
                  'commission' =>  $commision->g_commission,
                  'service_id' => $this->data['serid'],
                  'status' => 'success',
                 // 'stock_type'=> "recharge",
                  'status' => 'success',
                  'date'=> date('Y-m-d'),
                ];
               $id= $this->common_model->insert($logme, 'wallet_transaction');
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

  public function checkparent($id, &$parents = array(), $level = 1) {
      $data = $this->users_model->get_parent($id);
      if (isset($data)) {
        $parents[$level]['user_id'] = $data->user_id;
        $parents[$level]['member_id'] = $data->member_id;
        $parents[$level]['parent'] = $data->parent;
        $parents[$level]['role_id'] = $data->role_id;
        // echo $data['parent'];
        self::checkparent($data->parent, $parents, $level+1);
      }
      return $parents;
  }      
    
 public function mobile_submit() {

    if ($_POST) {

        $data = $this->security->xss_clean($_POST);

        if ($data) {

            if ($this->session->userdata('latitude')) {
            $location = $this->session->userdata('latitude').'|'.$this->session->userdata('longitude');
            }
        if( $this->data['bal']>0 && $this->data['bal']>$data['amount']){
             //pre($data);exit;
            $this->data['submitTransection']= [
            'apikey' => '1435743661',
            'username' => 'G271762461',
            'format' => 'json',
            'circle' => $data['circle'],
            'operator' => $data['operator'],
            'type' => $data['type'],
            'no' => $data['mobile'],
            'amount' => $data['amount'],
            'user' => self::stan(),
            ];
    
            //pre( $this->data['submitTransection']);exit;
            $response = self::transection_service();
            $response1 = json_decode($response);
            // pre( $response1);exit;
             $this->data['saveTransection'] = [
            'transection_id' => self::utan($data['mobile']),
            'transection_type' => 'recharge',
            'member_id' => $this->session->userdata('member_id'),
            'transection_amount' => $response1->amount,
            'service_id' => 4,
            'transection_msg' =>$response1->resText,
            'reference_number' => $response1->orderId,
            'transection_mobile' => $response1->mobile,
            'api_requist' => $data['type'],
            'location' => $location,
            "transection_status"=>$response1->status="FAILED" ? 0:1,
            "transection_response" =>$response,
            ];
            //pre( $this->data['saveTransection']);exit;
                $this->common_model->insert( $this->data['saveTransection'], 'submit_transection');
                    //echo $response1->status;exit;
                        
                    // $this->session->set_flashdata(
                    // array(
                    // 'status' => 1,
                    // 'msg' => $response1->resText
                    // ));
                    
                     $userWallet = $this->common_model->wallet_balance($this->session->userdata('user_id'));
          if ($userWallet != 'none') {
             $updateBalance = $userWallet - $response1->amount;    //Deduct balance
            $updateWallet = [
              'balance' => $updateBalance,
            ];
            if($this->common_model->update($updateWallet, 'member_id',$this->session->userdata('user_id'), 'wallet')) { //update deducted balance
              $message = [
                'msg' => 'Your wallet balance debited Rs. ' . $response1->amount. ' available balance is ' . $updateBalance,
                'user_id' => $this->session->userdata('user_id')
              ];
              $this->set_notification($message);
            }
          }
                   self::commition_distribute($this->session->userdata('user_id'),$response1->amount);  
                    redirect('recharge/mobile', 'refresh');
             
           }
            else{
                $this->session->set_flashdata(
                array(
                'status' => 0,
                'msg' => "Insufficient Balance"
                ));
            }


        }else{
                $this->session->set_flashdata(
                array(
                'status' => 0,
                'msg' => "Insufficient Balance"
                ));
            }

         }
    }
   
   
    public function stan( ) {
    return mt_rand(99999999999, 999999999999);
  }
  
   public function utan( $node ) {

    return $node . '00' . round(microtime(true));

  }
  
   public function bill_submit() {

    if ($_POST) {

        $data = $this->security->xss_clean($_POST);

        if ($data) {

            if ($this->session->userdata('latitude')) {
            $location = $this->session->userdata('latitude').'|'.$this->session->userdata('longitude');
            }
        if( $this->data['bal']>0 && $this->data['bal']>$data['amount']){
             //pre($data);exit;
            $this->data['submitTransection']= [
            'apikey' => '1435743661',
            'username' => 'G271762461',
            'format' => 'json',
            
            'operator' => $data['operator'],
            'type' => $data['type'],
            'no' => $data['account'],
            'amount' => $data['amount'],
            'txnid' => self::stan(),
            ];
    
            //pre( $this->data['submitTransection']);exit;
            $response = self::transection_service2();
            $response1 = json_decode($response);
            // pre( $response1);exit;
             $this->data['saveTransection'] = [
            'transection_id' => self::utan($data['mobile']),
            'transection_type' => 'bill_payment',
            'member_id' => $this->session->userdata('member_id'),
            'transection_amount' => $response1->amount,
            'service_id' => 8,
            'transection_msg' =>$response1->resText,
            'reference_number' => $response1->orderId,
            'transection_mobile' => $response1->mobile,
            'api_requist' => $data['type'],
            'location' => $location,
            "transection_status"=>$response1->status="FAILED" ? 0:1,
            "transection_response" =>$response,
            ];
            //pre( $this->data['saveTransection']);exit;
                $this->common_model->insert( $this->data['saveTransection'], 'submit_transection');
                    //echo $response1->status;exit;
                        
                    // $this->session->set_flashdata(
                    // array(
                    // 'status' => 1,
                    // 'msg' => $response1->resText
                    // ));
                    
                     $userWallet = $this->common_model->wallet_balance($this->session->userdata('user_id'));
          if ($userWallet != 'none') {
             $updateBalance = $userWallet - $response1->amount;    //Deduct balance
            $updateWallet = [
              'balance' => $updateBalance,
            ];
            if($this->common_model->update($updateWallet, 'member_id',$this->session->userdata('user_id'), 'wallet')) { //update deducted balance
              $message = [
                'msg' => 'Your wallet balance debited Rs. ' . $response1->amount. ' available balance is ' . $updateBalance,
                'user_id' => $this->session->userdata('user_id')
              ];
              $this->set_notification($message);
            }
          }
                   self::commition_distribute($this->session->userdata('user_id'),$response1->amount);  
                    redirect('recharge/electricity', 'refresh');
             
           }
            else{
                $this->session->set_flashdata(
                array(
                'status' => 0,
                'msg' => "Insufficient wallet Balance"
                ));
                redirect('recharge/electricity', 'refresh');
            }


        }else{
                $this->session->set_flashdata(
                array(
                'status' => 0,
                'msg' => "Invalid Request"
                ));
                redirect('recharge/electricity', 'refresh');
            }

         }
    }
public function transection_service2() {
      $this->client = new Client();

      
      #guzzle
      try {
          $response = $this->client->request('POST', "https://mobilerechargenow.com/billpay/payment.php", [
           
            'decode_content' => false,
            'query' => $this->data['submitTransection'],
          ]);

          return $response->getBody()->getContents();

      } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
      }
  }

public function get_Bill_history() {
  		$uri = $this->security->xss_clean($_GET);
  		// pre($uri);exit;

  		if (isset($uri['key']) && !empty($uri['key'])) {

  			$query = '';


  			$output = array();

           
  			$duid = $uri['key'];


  			$list = $uri['list'];


  			$data = array();


       


        if (isAdmin($this->session->userdata('user_roles'))) {

          $query .= "SELECT * FROM submit_transection WHERE service_id = 8 and api_requist='{$uri['type']}'";
          
          $recordsFiltered = $this->users_model->row_count($query);

        } else {

          $query .= "SELECT * FROM submit_transection WHERE service_id = 8 and api_requist='{$uri['type']}' AND member_id = '{$duid}' ";


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

      

        foreach ($result as $row) {

          
          
          $sub_array = array();

         
          $sub_array[] = $row['transection_id'];

          $sub_array[] = $row['transection_msg'];
            $sub_array[] = $row['transection_mobile'];
          $sub_array[] = $row['transection_amount'];
       
   

          $sub_array[] = $row['transection_status'];

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

public function get_history() {
  		$uri = $this->security->xss_clean($_GET);
  		// pre($uri);exit;

  		if (isset($uri['key']) && !empty($uri['key'])) {

  			$query = '';


  			$output = array();

           
  			$duid = $uri['key'];


  			$list = $uri['list'];


  			$data = array();


       


        if (isAdmin($this->session->userdata('user_roles'))) {

          $query .= "SELECT * FROM submit_transection WHERE service_id = 4 and api_requist='{$uri['type']}'";
          
          $recordsFiltered = $this->users_model->row_count($query);

        } else {

          $query .= "SELECT * FROM submit_transection WHERE service_id = 4 and api_requist='{$uri['type']}' AND member_id = '{$duid}' ";


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

      

        foreach ($result as $row) {

          
          
          $sub_array = array();

         
          $sub_array[] = $row['transection_id'];

          $sub_array[] = $row['transection_msg'];
            $sub_array[] = $row['transection_mobile'];
          $sub_array[] = $row['transection_amount'];
       
   

          $sub_array[] = $row['transection_status'];

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

   public function transection_service() {
      $this->client = new Client();

      
      #guzzle
      try {
          $response = $this->client->request('POST', "https://mobilerechargenow.com/recharge.php", [
           
            'decode_content' => false,
            'query' => $this->data['submitTransection'],
          ]);

          return $response->getBody()->getContents();

      } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
      }
  }

public function fetch_bill(){
     $this->client = new Client();
      $data = $this->security->xss_clean($_POST);
     // print_r($data);exit;
      try {
          $response = $this->client->request('GET', "https://mobilerechargenow.com/api/bill-fetch.php", [
            
            'query' => [
          'apikey' => '1435743661',
          'username' => 'G271762461',
          'format' => 'json',
          'no' => $data['account'],
         'operator' => $data['operator'],
         'txnid' => self::stan(),
        ],
          ]);

           $result['data'] =$response->getBody()->getContents() ;
           
          echo  $result['data'];
        

      } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
      }
}  
public function dth() {
    $this->data['main_content'] = $this->load->view('dth', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function datacard() {
    $this->data['main_content'] = $this->load->view('datacard', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function landline() {
    $this->data['main_content'] = $this->load->view('landline', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  
  public function electricity() {
    $this->data['main_content'] = $this->load->view('electricity', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function gas() {
    $this->data['main_content'] = $this->load->view('gas', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function water() {
    $this->data['main_content'] = $this->load->view('water', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function insurance() {
    $this->data['main_content'] = $this->load->view('insurance', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
    
   

 

}
