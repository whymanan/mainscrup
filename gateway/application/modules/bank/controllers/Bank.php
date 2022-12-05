<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use GuzzleHttp\Client;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Bank extends Vite {

  public $data = array();

  public function __construct() {
      parent::__construct();
    $this->load->model('common_model');
    $this->load->model('menu_model');
    $this->load->model('users_model');
    
      $this->data['active'] = 'Bank Detail';
      $this->data['breadcrumbs'] = [array('url' => base_url('Bank Detail'), 'name' => 'Bank Detail')];
           $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));
  }

  public function index() {
      $this->data['bank'] = $this->users_model->get_bank($this->session->userdata('user_id'));
    $this->data['main_content'] = $this->load->view('index', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
 public function add($data)
  {
    $user_id=$this->session->userdata('user_id');
    // $data = $this->security->xss_clean($_POST);
  // pre($data);exit;

    if ($data) {



      $logme = [

        'account_holder_name' => $data["name"],

        'account_no' => $data["account_no"],

        'bank_name' => $data["bank_name"],

        'phone_no' => $data["phone"],

        'ifsc_code' => $data["ifsc"],

        'fk_user_id' => $user_id,

        'created_at' => current_datetime()

      ];
      
     
            
           if(! $this->common_model->exists('user_bank_details',array("fk_user_id" => $user_id))) 
            $id = $this->common_model->insert($logme, 'user_bank_details');
            else
            $id = $this->common_model->update($logme,"fk_user_id", $user_id, 'user_bank_details');

      if ($id) {

      
            $message = [
                'msg' => 'Your bank Details added Successfully ',
                'user_id' => $user_id
              ];
              $this->set_notification($message);

                // redirect($_SERVER['HTTP_REFERER']);
                return "true";
      } 

    }

  }
  public function get_squadlist()
  {

    $uri = $this->security->xss_clean($_GET);
    if (!empty($uri)) {
      $query = '';

      $output = array();


      $list = $uri['list'];

      $data = array();

      switch ($list) {
        case 'all':
          // code...
          $query .= "SELECT * FROM `user_bank_details`  where fk_user_id =".$this->session->userdata('user_id')."  ";

          break;

        default:
          $query .= "SELECT * FROM `user_bank_details` where fk_user_id =".$this->session->userdata('user_id')." ";

          break;
      }


      if (!empty($_GET["search"]["value"])) {
        $query .= 'OR  LIKE "%' . $_GET["search"]["value"] . '%" ';
        $query .= 'OR mp.data_sub_menu LIKE "%' . $_GET["search"]["value"] . '%" ';
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
        $sub_array[] = $i;
        $sub_array[] = ' <a href="' . base_url('role/edit?q=') . $row['roles_id'] . '"> <button type="button" class="btn btn-sm btn-link"  data-placement="bottom" title="Edit Role Information"><i class="fa fa-pencil-alt"></i></button></a>
           <button type="button" class="btn btn-sm btn-primary" onclick="Delete(' . $row['roles_id'] . ')"  data-placement="bottom" title="Edit Role Information"><i class="fa fa-trash-alt"></i></button></a>';


        $sub_array[] = $row['account_holder_name'];
        $sub_array[] = $row['account_no'];
        $sub_array[] = $row['bank_name'];
        $sub_array[] = $row['ifsc_code'];
         $sub_array[] = $row['phone_no'];
        $data[] = $sub_array;
        $i++;
      }

      $output["draw"] = intval($_GET["draw"]);
      $output["recordsTotal"] = $i - 1;
      $output["recordsFiltered"] = $filtered_rows;
      $output["data"] = $data;

      echo json_encode($output);
    }
  }
  
 public function varify($userid)
 {
  $this->db->select('*')->from('user_bank_details')->where('fk_user_id',$userid);
  $query = $this->db->get();
  $bank_details= $query->result_array();
  $this->db->select('*')->from('wallet')->where('member_id',$userid);
  $query = $this->db->get();
  $wallet= $query->result_array();
  if($wallet[0]['balance']>3.36){
    $this->data['varify']=[
             'accountNumber'=>$bank_details[0]['account_no'],
             'ifsc'=>$bank_details[0]['ifsc_code'],
             'bname'=>$bank_details[0]['account_holder_name'],
             'purpose'=>'Verification',
             'api_key'=>'POCA001'
            ];
$response=self::varifyreturn();
$response1=json_decode($response);
 if(isset($response1->status) && $response1->status==0 && !isset($response1->statusCode)){
    $this->db->select('*')->from('wallet')->where('member_id',$userid);
    $query = $this->db->get();
    $wallet= $query->result_array();
    $updateBalance=$wallet[0]['balance']-3.36;
    $updateWallet = [
    'balance' => $updateBalance,
    ];
      if($this->common_model->update($updateWallet, 'member_id', $this->session->userdata('user_id'), 'wallet')){
     if($response1->data->status=='Success'){
          $this->common_model->update(['varification'=>1,'account_holder_name'=>$response1->data->beneficiaryName], 'fk_user_id', $this->session->userdata('user_id'), 'user_bank_details');
          $response='Varify';
      }
      elseif($response1->data->status=='Failure' && isset($response1->data->error))
      {
          $response=$response1->data->error;
      }
    $log=[
         'wallet_id' => $wallet[0]['wallet_id'],
         'member_to' => $this->session->userdata('user_id'),
         'stock_type' => 'Main Bal',
         'status' => 'success',
         'balance' =>  $wallet[0]['balance'],
          'closebalance' => $updateBalance,
          'type' => 'debit',
          'mode' => 'Account Varification',
          'bank' =>  $bank_details[0]['bank_name'],
          'narration' => 'Account Varification',
          'trans_type'=>'deduct',
          'amount'=>3.36,
          'date' => date('Y-m-d'),
         ];
         $this->common_model->insert($log, 'wallet_transaction');
         echo $response;
  }
  }
   else{
      echo $response1->msg;
      }
}
  else
  {
  echo  "Insufficience Balance";
  }  
}
 private function varifyreturn()
 {
      $this->client = new Client();

    //   print_r($this->data['varify']);
    //   exit();
    #guzzle
    try {
      $response = $this->client->request('POST', "https://vitefintech.com/viteapi/payu/accountVerify",[

        'decode_content' => false,
        'form_params' => $this->data['varify'],
      ]);
      return $response->getBody()->getContents();
    } catch (GuzzleHttp\Exception\BadResponseException $e) {
      #guzzle repose for future use
      $response = $e->getResponse();
      $responseBodyAsString = $response->getBody()->getContents();
      print_r($responseBodyAsString);
    }
 }
 
 public function send_otp()
 {
         $otp=self::otp();
         if($this->common_model->update(['otp'=>$otp],'user_id',$this->session->userdata('user_id'),'user'))
         {
              $text="Dear user OTP to add move to bank account in your At Moon Pe panel is: ".$otp." Do not share your OTP with others.";
              $this->client = new Client();
              try {
                 $response = $this->client->request('GET', "http://sms.vitefintech.com/api/sendmsg.php?user=&pass=&sender=&phone=".$this->session->userdata('phone')."&text=".$text."&priority=ndnd&stype=normal");

                  $result = $response->getBody()->getContents();
                  if(isset($result))
                  {
                      echo $this->session->userdata('phone');
                  }
                  else
                  {
                      echo "false";
                  }
              } catch (GuzzleHttp\Exception\BadResponseException $e) {
                  #guzzle repose for future use
                  $response = $e->getResponse();
                  $responseBodyAsString = $response->getBody()->getContents();
                  print_r($responseBodyAsString);
               }
         }
   }
   
  //otp generator
  private function otp()
  {
        $min = 2000;  // minimum
        $max = 9990;  // maximum
        $otp=random_int(1000,mt_rand($min, $max));
        return $otp;
     }
  //otp verify
  public function otp_verify()
  {
        $uri = $this->security->xss_clean($_POST);
        $user_details=$this->common_model->select_option($this->session->userdata('user_id'),'user_id','user');
        if($user_details)
        {
        //   if($user_details[0]['otp']==$uri['otp'])
        //   {
            // $this->common_model->update(['otp'=>''],'user_id',$this->session->userdata('user_id'),'user');
            $data=[
                   'name'=>$uri['name'],
                   'account_no'=>$uri['account_no'],
                   'ifsc'=>$uri['ifsc'],
                   'phone'=>$uri['phone'],
                   'bank_name'=>$uri['bank_name']
                  ];
            echo self::add($data);
        //   }
        //   else
        // {
        //     echo "false";
        // }
        }
        else
        {
            echo "false";
        }
     }

}