<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
   
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Wallet extends Vite {


  public $data = array();

  public function __construct() {
      parent::__construct();
      $this->data['serid'] = 40;
      $this->tnxType = "Main Bal";
    $this->load->model('common_model');
    $this->load->model('menu_model');
    $this->load->model('users_model');
    $this->ClientId = 'doj5SPOCA001lgu' ;
    $this->Secret = 'XsE0Mh2RNkiSbanac632ef26e7c9d3' ;
    $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));

    if($_SESSION['kyc_status']!='verify'){
        redirect('users/kyc', 'refresh');
       
        
    }else{
      
             $this->data['active'] = 'wallet';
      $this->data['breadcrumbs'] = [array('url' => base_url('wallet'), 'name' => 'Wallet Balance')];
       
    }
  }

  public function index() {
     
    //  echo $this->session->userdata('user_roles');exit;
       if( $this->common_model->check_wallet( $this->session->userdata('user_id'))){
            $this->data['param'] = $this->paremlink('add');
            $this->data['total'] = $this->common_model->wallet_balance_total($this->session->userdata('user_roles'));
            if($this->session->userdata('user_roles') == 94){
              $this->data['total'] = $this->common_model->wallet_balance_total_admin($this->session->userdata('user_roles'));
            }
            $this->data['remain'] = $this->common_model->wallet_balance_remain($this->session->userdata('user_id'));
            // pre($this->data['total']);exit;
            $this->data['main_content'] = $this->load->view('index', $this->data, true);
       }else{
             $this->data['param'] = $this->paremlink('add');
            $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('member_id'));
            $this->data['main_content'] = $this->load->view('start', $this->data, true);
       }
       
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
   
    $this->load->view('layout/index', $this->data);
  }

   public function get_balance() {
     
     $data = $this->security->xss_clean($_GET);
     $this->data['bal'] = $this->common_model->wallet_balance($data['search']);
     echo $this->data['bal'];
  }





  public function profile()
  {//
   
    $this->data['user'] = $this->common_model->select_user_option($_SESSION['customer_id']);

    $docs = $this->common_model->select_user_doc($_SESSION['customer_id']);
    $this->data['pan'] = 0;
    $this->data['adhar'] = 0;
    $this->data['photo'] = 0;
    if(!empty($docs)){
      $data1 = self::get_userdocs_by_type($docs);
      if(in_array("photo", $data1)){
        $this->data['photo']=1;
      } else if (in_array("adhar", $data1)) {
        $this->data['adhar'] = 1;
      }else if (in_array("pan", $data1)){
        $this->data['pan'] = 1;
      }else{
        $this->data['pan'] = 0;
        $this->data['adhar'] = 0;
        $this->data['photo'] = 0;
      }
    }else{
      $this->data['pan'] = 0;
      $this->data['adhar'] = 0;
      $this->data['photo'] = 0;
    }
    
    // pre($this->data);
    // exit;
     
    $this->data['main_content'] = $this->load->view('profile', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);

    $this->load->view('layout/index', $this->data);
  }
  function get_userdocs_by_type($doc){
     $data=array();
    foreach($doc as  $row){
     $data[]= $row['type'];
      
    }
    return $data;
  }
  public function kyc()
  {
    $this->data['main_content'] = $this->load->view('kyc', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);

    $this->load->view('layout/index', $this->data);
  }
  public function file_upload()
  {
    $data=array();
    //pre($_POST);exit;
   // $countfiles = count($_FILES);
 
    if(!empty(isset($_FILES["image_file"]["name"]))){
 
          // Set preference
          $config['upload_path'] = 'uploads/'.$_POST['type']; 
          $config['allowed_types'] = 'jpg|jpeg|png|gif';
          $config['max_size'] = '500'; // max_size in kb
      $config['overwrite'] = TRUE;
          $config['file_name'] = $_POST['type']."_". $_SESSION['customer_id'];
        //echo  $config['upload_path'];
          //Load upload library
          $this->load->library('upload',$config);

      if (!$this->upload->do_upload('image_file')) {
        $error =  $this->upload->display_errors();
        echo json_encode(array('msg' => $error, 'success' => false));
      } else {
        $data = $this->upload->data();
        $insert['name'] = $data['file_name'];
        $insert['root'] = $_SESSION['customer_id'];
        $insert['status'] = 1;
        $insert['type'] = $_POST['type'];
        $insert['details'] = json_encode($_FILES["image_file"]);
        $insert['created'] = date("Y-m-d h:i:sa");

        $this->db->insert('documents', $insert);
        $getId = $this->db->insert_id();

        $arr = array('msg' => 'Image has not uploaded successfully', 'success' => false);

        if ($getId) {
          $arr = array('msg' => 'Image has been uploaded successfully', 'success' => true);
        }
        echo json_encode($arr);
      }  
        }
      
      
   

  }


  public function add() {
    $this->data['param'] = $this->paremlink('/');
    $this->data['wallet'] =$this->common_model->wallet_balance($_SESSION['member_id']);
    $this->data['accessKey'] = $this->accesskey();
    $this->data['main_content'] = $this->load->view('add', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function submit()
  {

    $data = $this->security->xss_clean($_POST);
    
    if ($data) {
      $wallet_id= $this->common_model->get_wallet_id($this->session->userdata('user_id'));
      //pre($this->session->userdata());exit;
     $parent = $this->users_model->get_parent_wallet($this->session->userdata('user_id'));
    // pre($parent);exit;
      $logme = [
          'wallet_id'=>$wallet_id, 
        'member_to' =>  $this->session->userdata('user_id'),
        'member_from' => $parent->parent ,
        'amount' => $data["amount"],
         'type'=> "credit",
        'narration'=> $data["remark"],
        'refrence' =>  $data["reference"],
        
        'mode' => $data["mode"],
         'stock_type'=> $data["stock_type"],
        'bank'=> $data["bank"],
        'date'=> date("Y-m-d h:i:sa"),
      ];
     // pre($logme);exit;
     $id= $this->common_model->insert($logme, 'wallet_transaction');
      
       
      
      redirect('wallet', 'refresh');
    }
  }

  public function get_requests()
  {

    $uri = $this->security->xss_clean($_GET);
    if (!empty($uri)) {
      $query = '';
      $output = array();
      $data = array();
        if (isAdmin($this->session->userdata('user_roles'))) {
             $query .= "SELECT wallet_transaction.*,u1.member_id as member1,u2.member_id as member2 from wallet_transaction
             left join user u1 on u1.user_id=wallet_transaction.member_to
             left join user u2 on u2.user_id=wallet_transaction.member_from where wallet_transaction.trans_type='add' ";
        }
        elseif($this->session->userdata('user_id')){
            //  $query .="SELECT * FROM `user` INNER JOIN `wallet` ON `user`.`user_id` = `wallet`.`member_id` INNER JOIN `wallet_transaction` ON `wallet`.`wallet_id` = `wallet_transaction`.`wallet_id` WHERE user.user_id = 13  ";
            
            $query .= "SELECT wallet_transaction.*,u1.member_id as member1,u2.member_id as member2 from wallet_transaction
             left join user u1 on u1.user_id=wallet_transaction.member_to
             left join user u2 on u2.user_id=wallet_transaction.member_from where wallet_transaction.trans_type='add' AND u1.user_id=".$this->session->userdata('user_id')." ";
            
            //  $query .= "SELECT wallet_transaction.*,u1.member_id as member1,u2.member_id as member2 from wallet_transaction
            //  left join user u1 on u1.user_id=wallet_transaction.member_to
            //  left join user u2 on u2.user_id=wallet_transaction.member_from where wallet_transaction.trans_type='add' ";
            
        }
          if(!empty($uri['member']))
        {
           $query .=" AND u1.member_id='".$uri['member']."' ";
        }
         if(!empty($uri['from']) && !empty($uri['to']))
        {
           $query.=" AND (CAST(`date` as date) BETWEEN '".$uri['from']."' AND '".$uri['to']."') "; 
        }
        if(!empty($uri['default_a']) && !empty($uri['default_v']))
        {
            $query .=" AND ".$uri['default_a']."='".$uri['default_v']."' ";
        }
        if(!empty($uri['status']))
        {  
               $query .=" AND type='".$uri['status']."' ";  
        }

      if (!empty($_GET["search"]["value"])) {
          if (isAdmin($this->session->userdata('user_roles'))) {
                $query .= 'OR u1.member_id LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= 'OR u1.parent LIKE  "%' . $_GET["search"]["value"] . '%" ';
                $query .= 'OR u1.phone LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= 'OR u1.email LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= 'OR u1.user_status LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= 'OR u1.kyc_status LIKE "%' . $_GET["search"]["value"] . '%" ';
            
          }  else{
              
                $query .= 'AND wallet_transaction.amount LIKE "%' . $_GET["search"]["value"] . '%" ';
               
          }
      }
      

      if (!empty($_GET["order"])) {
        $query .= 'ORDER BY ' . $_GET['order']['0']['column'] . ' ' . $_GET['order']['0']['dir'] . ' ';
      }
      $query .= 'ORDER BY `wallet_transaction`.`wallet_transaction_id` DESC  ';
      $sql = $this->db->query($query);
      $filtered_rows = $sql->num_rows();
      if ($_GET["length"] != -1) {
        $query .= 'LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];
      }
                    
      $sql = $this->db->query($query);
      $result = $sql->result_array();
      //  pre($this->session->userdata());
      $i = 1;
      foreach ($result as $row) {
        $sub_array = array();
       if($this->session->userdata('user_roles')==94 || $this->session->userdata('user_roles')==95 || $this->session->userdata('user_roles')==97){
           if($row['status'] == 'new') {
                $sub_array[] = '<a href="' . base_url('wallet/approve?q=') . $row['wallet_transaction_id'] . '"> <button type="button" class="btn btn-sm btn-success"  data-placement="bottom" title="Approve">Approve</button></a>
                 <button type="button"class="btn btn-sm btn-primary"  data-placement="bottom" onclick="Deny(' . $row['wallet_transaction_id'] . ')" title="Deny">Deny</button>';
           } else{
                $sub_array[] = "";
           }
       } else{
             $sub_array[] =$i;
       }  
      
        
         $sub_array[] = $row['member1'];   
        
        $sub_array[] = $row['amount'];
        
        if ($row['status'] == 'accept') {

            $status = '<span class="badge badge-success">accept</span>';

          } elseif ($row['status'] == 'reject') {

            $status = '<span class="badge badge-danger">reject</span>';

          }elseif ($row['status'] == 'credit by admin') {

            $status = '<span class="badge badge-success">credit by admin</span>';

          }
           elseif ($row['status'] == 'deduct by admin') {
              $status = '<span class="badge badge-success">deduct by admin</span>';
          }elseif($row['status'] == 'failed'){
              
              $status = '<span class="badge badge-success">Failed</span>';
          
          }else {

            $status = '<span class="badge badge-warning">New</span>';

          }
         $sub_array[] = $status;
        $sub_array[] = $row['mode'];
        $sub_array[] = $row['refrence'];
       
        $sub_array[] = $row['stock_type'];
        $sub_array[] = $row['balance'];
        $sub_array[] = $row['closebalance'];
        
        $sub_array[] = $row['commission'];
        $sub_array[] = $row['surcharge'];
        $sub_array[] = $row['bank'];
        
       $sub_array[] = $row['narration'];
        $sub_array[] = $row['type'];
        $sub_array[] = $row['updated'];

       
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
 
    public function get_widthdrawal_list(){

        $uri = $this->security->xss_clean($_GET);
        if (!empty($uri)) {
          $query = '';
    
          $output = array();
    
    
         
    
          $data = array();
            $parent=0;$to=0;
            
            if( isAdmin($this->session->userdata('user_roles')) ){
                $query .= "SELECT wallet_transaction.*,u1.member_id as member1,u2.member_id as member2 from wallet_transaction
                left  join user u1 on u1.user_id=wallet_transaction.member_to
                left  join user u2 on u2.user_id=wallet_transaction.member_from  where wallet_transaction.trans_type='widthdraw' OR wallet_transaction.trans_type='distwidthdraw' ORDER BY `wallet_transaction`.`wallet_transaction_id` DESC  ";
            }else{
                
                $query .= "SELECT wallet_transaction.*,u1.member_id as member1,u2.member_id as member2 from wallet_transaction
                left  join user u1 on u1.user_id=wallet_transaction.member_to
                left  join user u2 on u2.user_id=wallet_transaction.member_from  where wallet_transaction.trans_type='widthdraw' and  wallet_transaction.member_to ='".$_SESSION['user_id']."' OR wallet_transaction.type = 'distwidthdraw' ORDER BY `wallet_transaction`.`wallet_transaction_id` DESC  ";
                
            }
        
    
          if (!empty($_GET["search"]["value"])) {
            $query .= 'OR user.customer_id LIKE "%' . $_GET["search"]["value"] . '%" ';
            $query .= 'OR u.parent1 "%' . $_GET["search"]["value"] . '%" ';
            $query .= 'OR user.phone LIKE "%' . $_GET["search"]["value"] . '%" ';
            $query .= 'OR user.email LIKE "%' . $_GET["search"]["value"] . '%" ';
            $query .= 'OR user.user_status LIKE "%' . $_GET["search"]["value"] . '%" ';
            $query .= 'OR user.kyc_status LIKE "%' . $_GET["search"]["value"] . '%" ';
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
        //   pre($query);exit;
          $result = $sql->result_array();
    
          $i = 1;
          foreach ($result as $row) {
            $sub_array = array();
            if( isAdmin($this->session->userdata('user_roles')) ){
                if( $row['status']=='accept' || $row['status']=='success' ){
                    $sub_array[] = '<span class="badge badge-success">Payout Success</span>';
                    
                }elseif( $row['status']=='reject'){
                    
                    $sub_array[] = '<span class="badge badge-danger">reject</span>';
                    
                }else{
                    $sub_array[] = '<button type="button" class="btn btn-sm btn-success"  data-placement="bottom" onclick="Approve(' . $row['wallet_transaction_id'] . ')"  title="Approve">Approve</button>
                     <button type="button"class="btn btn-sm btn-primary"  data-placement="bottom" onclick="Deny(' . $row['wallet_transaction_id'] . ')" title="Deny">Deny</button>';
                }
               
            }else{
               
               $sub_array[] = $i; 
                
            }
            
            if($to==0){
             $sub_array[] = $row['member1'];   
            }else{
            
            $sub_array[] = $row['member2'];
            }
            $sub_array[] = $row['amount'];
            $sub_array[] = $row['surcharge'];
            if ($row['status'] == 'accept') {
    
                $status = '<span class="badge badge-success">accept</span>';
    
              } elseif ($row['status'] == 'reject') {
    
                $status = '<span class="badge badge-danger">reject</span>';
    
              } else {
    
                $status = '<span class="badge badge-warning">New</span>';
    
              }
             $sub_array[] = $status;
            $sub_array[] = $row['mode'];
            $sub_array[] = $row['refrence'];
           
            $sub_array[] = $row['stock_type'];
            $sub_array[] = $row['bank'];
           $sub_array[] = $row['narration'];
            $sub_array[] = $row['type'];
           
            $sub_array[] = $row['date'];
    
           
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
    
    // public function payout(){
      
    //     $uri = $this->security->xss_clean($_GET);
    //     $wallet  = $this->common_model->get_wallet_by_id($uri['id'],'wallet_transaction_id');
        
    //     $bank_details  = $this->common_model->bank_get($wallet[0]['member_to']);
        
    //     pre($bank_details);
      
      
    //     $this->data['submitTransection'] = [
            
    //                      "bankAccount" => $bank_details->account_no,
    //                      "ifsc" => $bank_details->ifsc_code,
    //                      "amount" => $wallet[0]['amount'],
    //                      "name" => $bank_details->account_holder_name,
    //                      "transferId" => "Widthdrawal".self::stan(),
    //                      "phone" => $bank_details->phone_no,
    //                      "email" => "test@gmail.com",
    //                      "client_id" => "CF187604CBD8R0UJ5FIO1RSL5H80" ,
    //                      "secret" => "17261e2812f1a8c17d62a6577eea8b6b01e4a902" ,
    //                     //  "client_id" => "CF235962CBD8BTTG8KNVTMU4VJ10" ,
    //                     //  "secret" => "920047944e040f57e4e5b9f716bfb35c771a463f" ,
    //                  ];
        
    //     $transaction = self::payout_service();
    //     $transaction = json_decode($transaction);
    //     pre(self::payout_service());
    //     exit();
    //     if($transaction->status == "SUCCESS"){
            
    //             if($this->common_model->update(array('status' => 'accept'), 'wallet_transaction_id', $id, 'wallet_transaction')){
                
    //                 // Success Part
                    
    //             //   echo 1;
    //             return 1;
                    
                    
    //             }
           
            
    //     }else{
            
    //         // faild Part
            
    //         // echo 0;
    //         return 0;
    //     }
      
    
      
    // }
    
    // public function payout(){
      
    //     $uri = $this->security->xss_clean($_GET);
      
    //     $wallet  = $this->common_model->get_wallet_by_id($uri['id'],'wallet_transaction_id');
        
    //     $bank_details  = $this->common_model->bank_get($wallet[0]['member_to']);
        
    //      $this->data['submitTransection'] = [
                         
    //                  ];
       
    //     $transaction = self::payout_service();
       
    //     $transaction = json_decode($transaction);
    //     if($transaction->status == "PENDING" || $transaction->status == "SUCCESS" ){
            
    //             if($this->common_model->update(array('status' => 'accept'), 'wallet_transaction_id', $uri['id'], 'wallet_transaction')){
                
    //                 // Success Part
                    
    //               echo 1;
                    
                    
    //             }
           
            
    //         }else{
                
    //             // faild Part
                
    //             echo 0;
    //         }
      
    
      
    // }
    
    public function payout(){
      
        $uri = $this->security->xss_clean($_GET);
      
        $wallet  = $this->common_model->get_wallet_by_id($uri['id'],'wallet_transaction_id');
        
        $bank_details  = $this->common_model->bank_get($wallet[0]['member_to']);
        
        // pre($bank_details);exit;
      
      
        $this->data['submitTransection'] = [
            
                'partnerId' => 'POCA001',
                'amount' => $wallet[0]['amount'],
                'transactioonId' => "Widthdrawal".self::stan(),
                'account' => $bank_details->account_no,
                'ifsc' => $bank_details->ifsc_code,
                'name' => $bank_details->account_holder_name,
                'phone' => $bank_details->phone_no,
                'email' => "vvnmsindia@gmail.com",
                
        ];
        
        $response = self::payout_service();
        
        $transaction = json_decode($response);
            
        if($transaction->status == 'PENDING' || $transaction->status == 'SUCCESS'){
            
            if($this->common_model->update(array('status' => 'accept'), 'wallet_transaction_id', $uri['id'], 'wallet_transaction')){
                echo 1;
                
            }
            
        }else{
            echo $response;
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
    
    private function encode($key){
        
        $plaintext = json_encode($this->data['submitTransection']);
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
        $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
        
        return $ciphertext;
        
    }
    
    private function payout_service() {
        
        $this->client = new Client();
        
            $url = "https://vitefintech.com/viteapi/payout/transaction";
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
    
    public function get_move_charge() {
     
     $wallet_sevice_id = 40;
     $data = $this->security->xss_clean($_GET);
     $this->data['bal'] = $this->common_model->get_charge_by_move_bank($wallet_sevice_id,$data['search']);
     echo $this->data['bal'];
  
   }
    
   
    
   
    
    public function get_bank_list(){

        $uri = $this->security->xss_clean($_GET);
        if (!empty($uri)) {
          $query = '';
    
          $output = array();
    
    
         
    
          $data = array();
            $parent=0;$to=0;
            
            if( isAdmin($this->session->userdata('user_roles')) ){
                $query .= "SELECT * FROM `user_bank_details` WHERE `fk_user_id` = {$this->session->userdata('user_id')} ";
            }else{
                
                $query .= "SELECT * FROM `user_bank_details` WHERE `fk_user_id` = {$this->session->userdata('user_id')} ";
                
            }
        
    
          if (!empty($_GET["search"]["value"])) {
            $query .= 'OR user_bank_details.account_holder_name LIKE "%' . $_GET["search"]["value"] . '%" ';
            $query .= 'OR user_bank_details.account_no LIKE "%' . $_GET["search"]["value"] . '%" ';
            $query .= 'OR user_bank_details.bank_name LIKE "%' . $_GET["search"]["value"] . '%" ';
            $query .= 'OR user_bank_details.ifsc_code LIKE "%' . $_GET["search"]["value"] . '%" ';
            $query .= 'OR user_bank_details.phone_no LIKE "%' . $_GET["search"]["value"] . '%" ';
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
            
            $sub_array[] = '<a href="#"> <button type="button" class="btn btn-sm btn-success"  onclick="sent_otp()" data-placement="bottom" data-toggle="modal" data-target="#modal-add-beneficiary" title="Move_To_Bank">Move To Bank</button></a>';
                
                
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
 
    public function live_count(){
        $uri = $this->security->xss_clean($_GET);

        if (isset($uri['key']) && !empty($uri['key'])) {
            $data = array();

            $duid = $uri['key'];

            if (isAdmin($this->session->userdata('user_roles'))) {
                $result = $this->users_model->totel_count();
            } else {
                $result = $this->users_model->totel_count($duid);
            }

            foreach ($result as $value) {
                $data[$value->kyc_status] = number_format($value->totel, 0);
            }

            echo json_encode($data);
        }
    }
    
  public function edit()
  {
    $this->data['param'] = $this->paremlink('/');
    $uri = $this->security->xss_clean($_GET);
    if (isset($uri['q']) && !empty($uri['q'])) {
      $uid = $uri['q'];
      if (!$this->common_model->exists('user', ['user_id' => $uid])) {
        exit('User dosn\'t Exist');
      }
      $this->data['user'] = $this->users_model->find($uid);
      $this->data['details'] = $this->users_model->find_details($uid);
      $this->data['main_content'] = $this->load->view('edit', $this->data, true);
      $this->data['is_script'] = $this->load->view('script', $this->data, true);
      $this->load->view('layout/index', $this->data);
    }
  }

 public function approve()
  {
    $this->data['param'] = $this->paremlink('/');
    $uri = $this->security->xss_clean($_GET);
    $data= $this->common_model->get_wallet_by_id($uri['q'],'wallet_transaction_id');



    $admin_wallet= $this->common_model->get_user_wallet($data[0]['member_to']);
   
 
   
    $user_wallet= $this->common_model->get_user_wallet($data[0]['member_from']);
    
    // pre($admin_wallet);exit;
        if (isAdmin($this->session->userdata('user_roles'))) {
      
      
                if($data[0]['type']=='credit' ){
                       
                  $data1  = $this->common_model->find_member('user','member_id',$data[0]['member_id']);
                  $wallet  = $this->common_model->find_member('wallet','member_id',$data1[0]['user_id']);
                  $wallet1  = $this->common_model->find_member('wallet','member_id',1);
                  
                  $amount =  $data[0]['amount'] + $wallet[0]['balance'];
                  $open =    $wallet[0]['balance'];
                  $close =    $amount;           
                  
                    $admin_wallet=  $wallet1[0]['balance'] - $data[0]['amount'];
                    
                    
                      $this->common_model->update(array('balance' =>$admin_wallet ), 'member_id',1 , 'wallet');
                      
                      $this->common_model->update(array('balance' =>$amount), 'member_id',$data[0]['member_to'] , 'wallet');    
                      
                      $this->common_model->update(array('status' => 'accept','balance' => $open , 'closebalance' => $close  ), 'wallet_transaction_id', $uri['q'], 'wallet_transaction');
                
                         $this->session->set_flashdata(
                                             array('error' => 0,
                                             'msg' => "Balance Addes Successfully"
                                          )
                                        );
                                        redirect('wallet/request', 'refresh');
                    
                    // pre($admin_wallet);exit;
                  }else{
                  $user_wallet=$user_wallet+ (int)$data[0]['amount'];
                  $admin_wallet=  $admin_wallet -(int)$data[0]['amount'];
                }
      
      
      
        }else{$this->session->set_flashdata(
                                     array('error' => 1,
                                     'msg' => "Insufficient Balance"
                                  )
                                );
                                redirect('wallet/request', 'refresh');
        }

    if($user_wallet>0 && $user_wallet>$data[0]['amount']){
        echo "h";
    if($data[0]['type']=='credit' ){
        echo"h1";
    $user_wallet=$user_wallet - (int)$data[0]['amount']; 
    $admin_wallet=  $admin_wallet +( int)$data[0]['amount'];
    
          
          $data1  = $this->common_model->find_member('user','member_id',$data[0]['member_id']);
          $wallet  = $this->common_model->find_member('wallet','member_id',$data1[0]['user_id']);
          $wallet1  = $this->common_model->find_member('wallet','member_id',$this->session->userdata('user_id'));
          $amount =  $data[0]['amount'] + $wallet[0]['balance'];
                        
            $admin_wallet=  $wallet1[0]['balance'] - $data[0]['amount'];
            
            
             
              $this->common_model->update(array('balance' =>$amount), 'member_id',$data[0]['member_to'] , 'wallet');    
              $this->common_model->update(array('balance' =>$admin_wallet ), 'member_id',$data[0]['member_from'] , 'wallet');
              $this->common_model->update(array('status' => 'accept'), 'wallet_transaction_id', $uri['q'], 'wallet_transaction');
        
                 $this->session->set_flashdata(
                                     array('error' => 0,
                                     'msg' => "Balance Addes Successfully"
                                  )
                                );
                                redirect('wallet/request', 'refresh');
    
        //
    }else{
      $user_wallet=$user_wallet+ (int)$data[0]['amount'];
      $admin_wallet=  $admin_wallet -(int)$data[0]['amount'];
    }
    }else{
    //   $arr = array('msg' => 'Insufficient Balance', 'success' => false); 
       
                     $this->session->set_flashdata(
                                     array('error' => 1,
                                     'msg' => "Insufficient Balance"
                                  )
                                );
                                redirect('wallet/request', 'refresh');

    }

  }
// payout Deny
    public function deny($id){
    
        $query = "SELECT surcharge,amount,member_to FROM wallet_transaction WHERE wallet_transaction_id = '{$id}' ";
        
        $sql = $this->db->query($query);
        
        $result = $sql->row();
        
        $query = "SELECT * FROM wallet WHERE member_id = '{$result->member_to}' ";
        
        $sql = $this->db->query($query);
        
        $wallet = $sql->row();
        
        
        if($this->common_model->update(array('status' => 'reject'), 'wallet_transaction_id', $id, 'wallet_transaction')){
            
            if($wallet){
                
                $amount =  $wallet->balance + $result->amount + $result->surcharge;
                    if(!$this->common_model->update(array('balance' =>$amount ), 'member_id',$result->member_to , 'wallet')){
                         
                    }else{
                            $message = [
                                'msg' => 'Your Widthdraw  rejected. Balance is ' .  $result->amount  .' credit',
                                'user_id' => $result->member_to
                              ];
                              $this->set_notification($message);
                        }   
                    
                
            }else{
                $message = [
                                'msg' => 'Faild',
                                'user_id' => $result->member_to
                              ];
                              $this->set_notification($message);
            }
            
             echo 1;
             
        }else {
            
          echo 0;
          
        }

    }
  
//   payout deny end


 
//   ak
 public function deduct_bal(){
  
    if ($_POST) {
      $data = $this->security->xss_clean($_POST);
      if(isset($data['vendor']) && !empty($data['vendor']) && isset($data['amount']) && !empty($data['amount'])){
       if(count($this->common_model->select_option($data['vendor'],'member_id','user'))>0)
       {
        if($this->common_model->select_option($data['vendor'],'member_id','user')[0]['user_id']!=1)
        {
         $wallet=$this->common_model->get_user_wallet_balance($this->common_model->select_option($data['vendor'],'member_id','user')[0]['user_id']);
         if($wallet->balance>=$data['amount'])
         {
           $updatebalance=$wallet->balance-(float)$data['amount'];
           if($this->common_model->update(array('balance' =>$updatebalance), 'member_id',$this->common_model->select_option($data['vendor'],'member_id','user')[0]['user_id'], 'wallet'))
           {
              $logme = [
                 'wallet_id'=> $wallet->wallet_id,
                 'member_to' => $this->common_model->select_option($data['vendor'],'member_id','user')[0]['user_id'] ,
                 'member_from' =>  1,
                 'amount' => $data["amount"],
                 'type'=> "debit",
                 'refrence' => "deduct".self::stan2(),
                 'trans_type' => "add",
                 'mode' => "Wallet Transfer",
                 'stock_type'=> "Main Bal",
                 'bank'=> "Null",
                 'status'=>'deduct by admin',
                 "balance"=>$wallet->balance,
                 "closebalance"=>$updatebalance,
                 'narration'=> $data["narration"]."('Deduct by admin')",
                 'date'=> date('Y-m-d'),
            ];
            //pre($logme);exit;
             $id= $this->common_model->insert($logme, 'wallet_transaction');
             $this->session->set_flashdata(
              array(
                      'status' => 0,
                      'msg' =>"Successfully Deduct form ".$data['vendor'],
                  )
          );
           }
         }
         else
         {
          $this->session->set_flashdata(
            array(
                    'status' => 0,
                    'msg' =>"Insuffience Balance",
            )); 
         }
        }
        else
        {
          $this->session->set_flashdata(
            array(
                    'status' => 0,
                    'msg' =>"Don't deduct balance from this account",
            )); 
        }
       }
       else
       {
        $this->session->set_flashdata(
          array(
                  'status' => 0,
                  'msg' =>"Not fount this member Id",
          )); 
       }
    }
      redirect('wallet/deduct', 'refresh');        
   }
}
 
    public function widthdraw_bal(){
            if ($_POST) {
                $data = $this->security->xss_clean($_POST);
                if($data["amount"]<$data['balance']){
                    $userWallet = $this->common_model->bank_get($data["vendor"]);
                    $user_wallet=$data['balance'] - (float)$data['amount'] - (float)$data['charge']; 
                    
                       $logme = [
                           
                        'member_to' => $data["vendor"] ,
                        'member_from' =>  1,
                        'amount' => $data["amount"],
                        'type'=> "debit",
                        'refrence' => "Widthdraw_".self::stan(),
                        'trans_type' => "widthdraw",
                        'mode' => "Wallet Transfer",
                        'stock_type'=> "Main Bal",
                        'surcharge' => (float)$data['charge'],
                        'balance' => $data['balance'],
                        'closebalance' => $user_wallet,
                        'bank'=> $userWallet->bank_name,
                        'account_no' => $userWallet->account_no,
                        'narration'=> "Move To Bank Request",
                        'date'=> date("Y-m-d h:i:sa"),
                      
                      ];
                      
                        $id= $this->common_model->insert($logme, 'wallet_transaction');
                        
                        $this->common_model->update(array('balance' =>$user_wallet ), 'member_id',$this->session->userdata('user_id'), 'wallet');    
        
                }else{
                    $this->data['main_content'] = $this->load->view('message', $this->data, true);
                    $this->load->view('layout/index', $this->data);
                }
            }
    
      redirect('wallet/widthdraw', 'refresh');
             
    }
    
    public function stan( ) {
     
        return mt_rand(100000, 999999);
        
    }
 
 
  public function add_wallet(){
      
        $this->data['user'] = $this->common_model->select_user_option($_SESSION['user_id']);

    $docs = $this->common_model->select_user_doc($_SESSION['user_id']);
    $this->data['pan'] = 0;
    $this->data['adhar'] = 0;
    $this->data['photo'] = 0;
    if(!empty($docs)){
      $data1 = self::get_userdocs_by_type($docs);
      if(in_array("photo", $data1)){
        $this->data['photo']=1;
      } else if (in_array("adhar", $data1)) {
        $this->data['adhar'] = 1;
      }else if (in_array("pan", $data1)){
        $this->data['pan'] = 1;
      }else{
        $this->data['pan'] = 0;
        $this->data['adhar'] = 0;
        $this->data['photo'] = 0;
      }
    }else{
      $this->data['pan'] = 0;
      $this->data['adhar'] = 0;
      $this->data['photo'] = 0;
    }
            $this->data['bank'] = $this->users_model->get_user_bank($this->session->userdata('user_id'));
            //pre($this->data['user']);exit;
            $this->data['main_content'] = $this->load->view('profile', $this->data, true);
            $this->data['is_script'] = $this->load->view('script', $this->data, true);
            $this->load->view('layout/index', $this->data);
      

                
 }
 
  public function start_wallet(){
      
      if($this->common_model->check_wallet( $this->session->userdata('user_id')) ){
        $arr = array('msg' => 'Wallet already Created', 'success' => false);
      }else{ 
          $this->common_model->insert(array('member_id' =>$this->session->userdata('user_id'),'role_id' => $this->session->userdata('user_roles') ), 'wallet');
         
           $arr = array('msg' => 'Wallet Created successfully', 'success' => true);
           
      }  
       echo json_encode($arr);
            }
            
              public function verify_wallet(){
      
             $this->data['main_content'] = $this->load->view('kyc', $this->data, true);
            $this->data['is_script'] = $this->load->view('script', $this->data, true);
            $this->load->view('layout/index', $this->data);
            }
            
            public function deduct(){
            $this->data['param'] = $this->paremlink('add');
            $this->data['main_content'] = $this->load->view('deduct', $this->data, true);
            $this->data['is_script'] = $this->load->view('script', $this->data, true);
            $this->load->view('layout/index', $this->data);
                
            }
            
            
             public function get_deduct_list()
              {
            
                $uri = $this->security->xss_clean($_GET);
                if (!empty($uri)) {
                  $query = '';
            
                  $output = array();
            
            
                 
            
                  $data = array();
                    $parent=0;$to=0;
                   $query .= "SELECT wallet_transaction.*,u1.member_id as member1,u2.member_id as member2 from wallet_transaction
         left join user u1 on u1.user_id=wallet_transaction.member_to
         left join user u2 on u2.user_id=wallet_transaction.member_from where wallet_transaction.trans_type='deduct' ";
                 
            
            
                  if (!empty($_GET["search"]["value"])) {
                    $query .= 'OR user.customer_id LIKE "%' . $_GET["search"]["value"] . '%" ';
                    $query .= 'OR u.parent1 "%' . $_GET["search"]["value"] . '%" ';
                    $query .= 'OR user.phone LIKE "%' . $_GET["search"]["value"] . '%" ';
                    $query .= 'OR user.email LIKE "%' . $_GET["search"]["value"] . '%" ';
                    $query .= 'OR user.user_status LIKE "%' . $_GET["search"]["value"] . '%" ';
                    $query .= 'OR user.kyc_status LIKE "%' . $_GET["search"]["value"] . '%" ';
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
                    
                    if($parent==1 ){
            if( $row['status']=='accept'){
                $sub_array[] = '
           <button type="button"class="btn btn-sm btn-primary"  data-placement="bottom" onclick="Deny(' . $row['wallet_transaction_id'] . ')" title="Deny">Deny</button>'; 
            }else{
                $sub_array[] = '<a href="' . base_url('wallet/approve?q=') . $row['wallet_transaction_id'] . '"> <button type="button" class="btn btn-sm btn-success"  data-placement="bottom" title="Approve">Approve</button></a>
                 <button type="button"class="btn btn-sm btn-primary"  data-placement="bottom" onclick="Deny(' . $row['wallet_transaction_id'] . ')" title="Deny">Deny</button>';
            }
           
        }
        if($to==1){
         $sub_array[] = $row['member2'];   
        }else{
        
        $sub_array[] = $row['member1'];
        }
        $sub_array[] = $row['amount'];
        if ($row['status'] == 'accept') {

            $status = '<span class="badge badge-success">accept</span>';

          } elseif ($row['status'] == 'reject') {

            $status = '<span class="badge badge-danger">reject</span>';

          } else {

            $status = '<span class="badge badge-warning">New</span>';

          }
         $sub_array[] = $status;
        $sub_array[] = $row['mode'];
        $sub_array[] = $row['refrence'];
       
        $sub_array[] = $row['stock_type'];
        $sub_array[] = $row['bank'];
       $sub_array[] = $row['narration'];
        $sub_array[] = $row['type'];
       
        $sub_array[] = $row['date'];

       
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

    public function credit(){
        $this->data['param'] = $this->paremlink('add');
        $this->data['main_content'] = $this->load->view('credit', $this->data, true);
        $this->data['is_script'] = $this->load->view('script', $this->data, true);
        $this->load->view('layout/index', $this->data);
                
    }
            
    // function credit_balance(){
    //     $data = $this->security->xss_clean($_POST);
    //     $data1  = $this->common_model->find_member('user','member_id',$data['member_id']);
    //     $wallet  = $this->common_model->find_member('wallet','member_id',$data1->user_id);
    //     // pre($wallet);exit;
           
    //     if($wallet){
    //         $amount =  $wallet->balance + $data['amount'];
    //             if(!$this->common_model->update(array('balance' =>$amount ), 'member_id',$data1->user_id , 'wallet')){
                            
    //                     $this->session->set_flashdata(
    //                                                     array('error' => 1,
    //                                                     'msg' => " Balance Added Failded"
    //                                                     )
    //                                                 );
    //                     redirect('wallet/credit', 'refresh');
                        
    //             }else {
                           
    //                     $data2 = [
                                            
    //                                 'wallet_id' => $wallet->wallet_id,
    //                                 'member_to' => $this->session->userdata('user_id') ,
    //                                 'member_from' =>$data1->user_id,
    //                                 'refrence' => $data['narration'],
    //                                 'mode' => 'SAME BANK FUND TRANSFER',
    //                                 'bank' => "icicii 123467719",
    //                                 'amount' => $data['amount'],
    //                                 'balance' => $wallet->balance,
    //                                 'closebalance' => $amount,
    //                                 'type' => "credit",
    //                                 'trans_type' => "add",
    //                                 'narration' => $data['narration'],
    //                                 'date' => date("Y-m-d h:i:sa"),
    //                                 'status' => "credit by admin",
    //                                 'stock_type' => "Main Bal"
                                    
    //                              ];
                                    
    //                             $a = $this->common_model->insert($data2,'wallet_transaction');
                     
    //                             $this->session->set_flashdata(
    //                                                              array('error' => 0,
    //                                                                 'msg' => " Balance Added Successfully"
    //                                                               )
    //                                                         );
    //                             redirect('wallet/credit', 'refresh');
                        
    //                   }
                
                    
    //     } else  {
    //                 $this->session->set_flashdata(
    //                                                 array('error' => 1,
    //                                                 'msg' => " Wallet Not Activate"
    //                                                     )
    //                                             );
    //                 redirect('wallet/credit', 'refresh');
    //             }
                
    // }
    function credit_balance(){
        $data = $this->security->xss_clean($_POST);
        $data1  = $this->common_model->find_member('user','member_id',$data['member_id']);
        $wallet  = $this->common_model->find_member('wallet','member_id',$data1->user_id);
        // pre($wallet);exit;
           
        if($wallet){
            $amount =  $wallet->balance + $data['amount'];
                if(!$this->common_model->update(array('balance' =>$amount ), 'member_id',$data1->user_id , 'wallet')){
                            
                        $this->session->set_flashdata(
                                                        array('error' => 1,
                                                        'msg' => " Balance Added Failded"
                                                        )
                                                    );
                        redirect('wallet/credit', 'refresh');
                        
                }else {
                           
                        $data2 = [
                                            
                                    'wallet_id' => $wallet->wallet_id,
                                    'member_to' => $wallet->member_id ,
                                    'member_from' => 1,
                                    'refrence' => $data['narration'],
                                    'mode' => 'SAME BANK FUND TRANSFER',
                                    'bank' => "icicii 123467719",
                                    'amount' => $data['amount'],
                                    'balance' => $wallet->balance,
                                    'closebalance' => $amount,
                                    'type' => "credit",
                                    'trans_type' => "add",
                                    'narration' => $data['narration'],
                                    'date' => date("Y-m-d h:i:sa"),
                                    'status' => "credit by admin",
                                    'stock_type' => "Main Bal"
                                    
                                 ];
                                    
                                $a = $this->common_model->insert($data2,'wallet_transaction');
                     
                                $this->session->set_flashdata(
                                                                 array('error' => 0,
                                                                    'msg' => " Balance Added Successfully"
                                                                  )
                                                            );
                                redirect('wallet/credit', 'refresh');
                        
                       }
                
                    
        } else  {
                    $this->session->set_flashdata(
                                                    array('error' => 1,
                                                    'msg' => " Wallet Not Activate"
                                                        )
                                                );
                    redirect('wallet/credit', 'refresh');
                }
                
    }
    
    public function all_request(){
        $this->data['param'] = $this->paremlink('add');
        $this->data['main_content'] = $this->load->view('all_request', $this->data, true);
        $this->data['is_script'] = $this->load->view('script', $this->data, true);
        $this->load->view('layout/index', $this->data);
    }
            
     public function memberselect()
    {
      $query="Select `member_id` FROM `user` where `role_id`!=94 && `delete_user`!=1";
      $sql = $this->db->query($query);
      $result = $sql->result_array();
      return $result;
    }
  
    public function request(){
       $this->data['param'] = $this->paremlink('add');
        $this->data['member_list']=self::memberselect();
        $this->data['main_content'] = $this->load->view('request', $this->data, true);
        $this->data['is_script'] = $this->load->view('script', $this->data, true);
        $this->load->view('layout/index', $this->data);
        }
        
    public function summary(){
        $this->data['active'] = 'wallet';
        $this->data['breadcrumbs'] = [array('url' => base_url('wallet/summary'), 'name' => 'Wallet Summary')];
        $this->data['main_content'] = $this->load->view('summary', $this->data, true);
        $this->data['is_script'] = $this->load->view('script', $this->data, true);
        $this->load->view('layout/index', $this->data);
    }
            
    public function widthdraw(){
        $this->data['param'] = $this->paremlink('add');
        $query = "SELECT user.user_id ,user.member_id , user.role_id, user.parent AS parent_user_id , user_detail.first_name , user_detail.middle_name , user_detail.last_name , user_detail.phone_no FROM `user` JOIN user_detail ON user.parent = user_detail.fk_user_id WHERE user.user_id = {$this->session->userdata('user_id')} AND kyc_status = 'verify' ";
        $sql = $this->db->query($query);
        $result = $sql->row();
        
        $this->data['parent_id'] = $result->parent_user_id;
        $this->data['main_content'] = $this->load->view('widthdraw', $this->data, true);
        $this->data['is_script'] = $this->load->view('script', $this->data, true);
        $this->load->view('layout/index', $this->data);
                
    }
            
    public function details(){
             
        $this->data['member_id'] = $this->security->xss_clean($_GET);
        $this->data['main_content'] = $this->load->view('details', $this->data, true);
        $this->data['is_script'] = $this->load->view('script', $this->data, true);
        $this->load->view('layout/index', $this->data);
                
    }
    
    public function get_details_list()
        {
            
                $uri = $this->security->xss_clean($_GET);
                if (!empty($uri)) {
                  $query = '';
            
                  $output = array();
            
            
                 
            
                  $data = array();
                  
                   $query .= "select * from wallet_transaction where member_to = {$uri['id']} ";
                 
            
            
                  if (!empty($_GET["search"]["value"])) {
                    $query .= 'OR refrence LIKE "%' . $_GET["search"]["value"] . '%" ';
                    $query .= 'OR amount Like "%' . $_GET["search"]["value"] . '%" ';
                    $query .= 'OR narration LIKE "%' . $_GET["search"]["value"] . '%" ';
                    $query .= 'OR balance LIKE "%' . $_GET["search"]["value"] . '%" ';
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
                   
                    $sub_array[] = $row['updated'];
                    
                     $sub_array[] = $row['refrence'];
                    $sub_array[] = $row['narration'];
                   
                    $sub_array[] = $row['type'];
                    $sub_array[] = $row['amount'];
                  
                  ;
            
                   
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
            
    public function get_summary_list()
        {
            
                $uri = $this->security->xss_clean($_GET);
                
                if (!empty($uri)) {
                  $query = '';
            
                  $output = array();
            
            
                 
            
                  $data = array();
                    $parent=0;$to=0;
                    if(isAdmin($this->session->userdata("user_roles"))){
                   $query .= "SELECT user.phone,user.member_id,user_detail.first_name,user_detail.last_name,user.role_id,wallet.balance,wallet_transaction.member_to,SUM(IF(type = 'debit', amount, 0)) as total_debit, SUM(IF(type = 'credit', amount+commission, 0)) as total_credit FROM wallet_transaction LEFT JOIN user_detail ON user_detail.user_detail_id = wallet_transaction.member_to LEFT JOIN user ON user.user_id = wallet_transaction.member_to LEFT JOIN wallet ON wallet.member_id = wallet_transaction.member_to ";
                 
            
            
                  if (!empty($_GET["search"]["value"])) {
                    $query .= 'where user.customer_id LIKE "%'.$_GET["search"]["value"].'%" ';
                    $query .= 'OR user.parent Like "%'. $_GET["search"]["value"].'%" ';
                    $query .= 'OR user.phone LIKE "%' . $_GET["search"]["value"] . '%" ';
                    $query .= 'OR user.email LIKE "%' . $_GET["search"]["value"] . '%" ';
                    $query .= 'OR user.user_status LIKE "%' . $_GET["search"]["value"] . '%" ';
                    $query .= 'OR user.kyc_status LIKE "%' . $_GET["search"]["value"] . '%" ';
                  }
                  $query.=" GROUP BY wallet_transaction.member_to ";
            
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
                // pre($result);exit;
                  $i = 1;
                  foreach ($result as $row) {
                    $sub_array = array();
                   
                    $sub_array[] = $i;
                    $sub_array[] = $row['member_id'];
                    $sub_array[] = $row['first_name'].$row['last_name'];
                   
                    $sub_array[] = $row['phone'];
                    
                    if($row['role_id'] == 94){
                    $sub_array[] = 'Admin';
                    }
                     elseif ($row['role_id'] == 95){
                    $sub_array[] = 'Distributor';
                    }
                     elseif ($row['role_id'] == 97){
                    $sub_array[] = 'Master Distributor';
                    }
                     elseif ($row['role_id'] == 100){
                    $sub_array[] = 'Super Admin';
                    }
                     elseif ($row['role_id'] == 105){
                    $sub_array[] = 'Employee';
                    }else{
                         $sub_array[] = 'Retailer';
                    }
                    
                    $sub_array[] = $row['total_debit'];
                    $sub_array[] = $row['total_credit'];
                    $sub_array[] = $row['balance'];
                  
                    $sub_array[] = '<a href="' . base_url('wallet/wallet/details?q=') . $row['member_to'] . '"> <button type="button" class="btn btn-sm btn-success"  data-placement="bottom" title="Approve">Detail</button></a>';
                   
                //     $sub_array[] = $row['date'];
            
                   
                    $data[] = $sub_array;
                    $i++;
                  }
            
                  $output["draw"] = intval($_GET["draw"]);
                  $output["recordsTotal"] = $i - 1;
                  $output["recordsFiltered"] = $filtered_rows;
                  $output["data"] = $data;
                    }
                    else
                    {
                         $output["draw"] ='';
                  $output["recordsTotal"] ='';
                  $output["recordsFiltered"] ='';
                  $output["data"] ='';
                    }
                  echo json_encode($output);
                   
                }
              } 
              
    public function get_all_history(){
        
        $uri = $this->security->xss_clean($_GET);
        if (!empty($uri)) {
                  $query = '';
                  $output = array();
                  $data = array();
                        if (isAdmin($this->session->userdata('user_roles'))) {
                            
                            $query .= "SELECT wallet_transaction.*,u1.member_id as member1,u2.member_id as member2 from wallet_transaction
                            left join user u1 on u1.user_id=wallet_transaction.member_to
                            left join user u2 on u2.user_id=wallet_transaction.member_from where wallet_transaction.trans_type='add' ";
                        }
                        
                        elseif($this->session->userdata('user_id')){
                          
                            $query .= "SELECT wallet_transaction.*,u1.member_id as member1,u2.member_id as member2 from wallet_transaction
                            left join user u1 on u1.user_id=wallet_transaction.member_to
                            left join user u2 on u2.user_id=wallet_transaction.member_from where u1.user_id = ".$this->session->userdata('user_id')." OR u1.parent = ".$this->session->userdata('user_id')." ";
                            
                            
                        }
    
                    if (!empty($_GET["search"]["value"])) {
                            if (isAdmin($this->session->userdata('user_roles'))) {
                                    $query .= 'OR u1.member_id LIKE "%' . $_GET["search"]["value"] . '%" ';
                                    $query .= 'OR u1.parent LIKE  "%' . $_GET["search"]["value"] . '%" ';
                                    $query .= 'OR u1.phone LIKE "%' . $_GET["search"]["value"] . '%" ';
                                    $query .= 'OR u1.email LIKE "%' . $_GET["search"]["value"] . '%" ';
                                    $query .= 'OR u1.user_status LIKE "%' . $_GET["search"]["value"] . '%" ';
                                    $query .= 'OR u1.kyc_status LIKE "%' . $_GET["search"]["value"] . '%" ';
                
                            }else{
                                  
                                    $query .= 'AND wallet_transaction.amount LIKE "%' . $_GET["search"]["value"] . '%" ';
                                   
                                }
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
          
                $i = 0;
                 foreach ($result as $row) {
                    $sub_array = array();  
                  
                    $sub_array[] = $row['member1'];   
                    
                    $sub_array[] = $row['amount'];
                    if ($row['status'] == 'accept') {
            
                        $status = '<span class="badge badge-success">accept</span>';
            
                      } elseif ($row['status'] == 'reject') {
            
                        $status = '<span class="badge badge-danger">reject</span>';
            
                      }elseif ($row['status'] == 'credit by admin') {
            
                        $status = '<span class="badge badge-success">credit by admin</span>';
            
                      }else {
            
                        $status = '<span class="badge badge-warning">New</span>';
            
                      }
                    $sub_array[] = $status;
                    $sub_array[] = $row['mode'];
                    $sub_array[] = $row['refrence'];
                   
                    $sub_array[] = $row['stock_type'];
                    $sub_array[] = $row['bank'];
                    $sub_array[] = $row['narration'];
                    
                    $sub_array[] = $row['type'];
                    $sub_array[] = $row['updated'];
            
                   
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
    
    // payment gateway 

  public function payment(){
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
      if($_POST){
        $data = $this->security->xss_clean($_POST);
        $this->client = new Client();

        $access_key = "b6e70c40-72d9-11ec-b941-e9d4b8eaecc5";
        $secret_key = "e5224d52ff0c6fcd4bdcf27d7d96405bf5664d0b" ;
        $url = "https://icp-api.bankopen.co/api/payment_token";
        // test url https://sandbox-icp-api.bankopen.co
        // live url https://icp-api.bankopen.co
        $header = [
          'Authorization' => 'Bearer ' . $access_key.':'.$secret_key,        
          'Accept' => 'application/json',
        ];

        $data =  [

          'amount' => $data['amount'],
          'currency' => 'INR', 
          'name' => $this->session->userdata('user_name') , 
          'email_id' => $data['email'],
          'contact_number' => $this->session->userdata('phone'), 
          'mtx' => date("Ymd-his"),//unix number
          'udf' => 'string'

        ];
        
        // pre($data);exit;
        // $url = 'https://sandbox-icp-api.bankopen.co/api/payment_token';
        #guzzle
        try {
            $response = $this->client->request('POST', $url, [
            'headers' => $header,
            'form_params' => $data,
          ]);
        
        
          echo $response->getBody()->getContents();
        
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
          #guzzle repose for future use
          $response = $e->getResponse();
          $responseBodyAsString = $response->getBody()->getContents();
          echo $responseBodyAsString ;
      
        }
      }
    }


  }

  private function accesskey(){

    $access_key = "b6e70c40-72d9-11ec-b941-e9d4b8eaecc5"; //api_key
    return $access_key;

  }
  
  public function payment_save(){
      
      if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
          
            if($_POST){
                
                $data = $this->security->xss_clean($_POST);
                
                
                $userWallet = $this->common_model->get_user_wallet_balance($this->session->userdata('user_id'));
                
                if ($userWallet != 'none') {
                    
                    if($data['status'] == 'captured'){
                        
                        $updateBalance = $userWallet->balance + $data['amount'];
                        
                        $updateWallet = [
                                            'balance' => $updateBalance,
                                        ];
             
                         if($this->common_model->update($updateWallet, 'member_id', $this->session->userdata('user_id'), 'wallet')) {

                                        
                              $logme = [
                        
                              'wallet_id' => $userWallet->wallet_id,
                              'member_to' =>  $this->session->userdata('user_id'),
                              'member_from' =>  $this->session->userdata('user_id'),
                              'amount' =>  $data['amount'],
                              'refrence' =>  "GATEWAY_".$data['refrence'],
                              'balance' => $userWallet->balance,
                              'closebalance' => $updateBalance,
                              'service_id' => $this->data['serid'],
                              'status' => 'success',
                              'stock_type'=> $this->tnxType,
                              'status' => 'success',
                              'type' => 'credit',
                              'mode' => 'Add Balance',
                              'bank' => 'Wallet',
                              'narration' => 'Balance Credit',
                              'date'=> date("Y-m-d h:i:sa"),
                            
                            ];
                            
                            $id= $this->common_model->insert($logme, 'wallet_transaction');
                            
                            echo 0;
                         }
                        
                    }else{
                        
                        $logme = [
                        
                              'wallet_id' => $userWallet->wallet_id,
                              'member_to' =>  $this->session->userdata('user_id'),
                              'member_from' =>  $this->session->userdata('user_id'),
                              'amount' =>  $data['amount'],
                              'refrence' =>  "GATEWAY_".$data['refrence'],
                              'balance' => $userWallet->balance,
                              'closebalance' => $userWallet->balance,
                              'service_id' => $this->data['serid'],
                              'status' => 'failed',
                              'stock_type'=> $this->tnxType,
                              'mode' => 'Add Balance',
                              'bank' => 'Wallet',
                              'narration' => 'Balance Not Credit',
                              'date'=> date("Y-m-d h:i:sa"),
                            
                            ];
                            
                            $id= $this->common_model->insert($logme, 'wallet_transaction');
                            echo 1;
                        
                    }
                    
                }
                
                
               
                
            }
            
        }else{
          
          echo "This Method Is Not Allow ." ;
          
        }
      
  }

    // END PAYMENT GATEWAY
    public function stan2( ) {
     
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
  
 public function export()
{
   		$uri = $this->security->xss_clean($_GET);
   		
   	  	ini_set('memory_limit', '44M');
         $fileName = 'employee.xlsx';
  		 $uri = $this->security->xss_clean($_GET);
    if (!empty($uri)) {
      $query = '';
      $output = array();
      $data = array();
        if (isAdmin($this->session->userdata('user_roles'))) {
             $query .= "SELECT wallet_transaction.*,u1.member_id as member1,u2.member_id as member2 from wallet_transaction
             left join user u1 on u1.user_id=wallet_transaction.member_to
             left join user u2 on u2.user_id=wallet_transaction.member_from where wallet_transaction.trans_type='add' ";
        }
        elseif($this->session->userdata('user_id')){
            //  $query .="SELECT * FROM `user` INNER JOIN `wallet` ON `user`.`user_id` = `wallet`.`member_id` INNER JOIN `wallet_transaction` ON `wallet`.`wallet_id` = `wallet_transaction`.`wallet_id` WHERE user.user_id = 13  ";
            
            $query .= "SELECT wallet_transaction.*,u1.member_id as member1,u2.member_id as member2 from wallet_transaction
             left join user u1 on u1.user_id=wallet_transaction.member_to
             left join user u2 on u2.user_id=wallet_transaction.member_from where wallet_transaction.trans_type='add' AND u1.user_id=".$this->session->userdata('user_id')." ";
            
            //  $query .= "SELECT wallet_transaction.*,u1.member_id as member1,u2.member_id as member2 from wallet_transaction
            //  left join user u1 on u1.user_id=wallet_transaction.member_to
            //  left join user u2 on u2.user_id=wallet_transaction.member_from where wallet_transaction.trans_type='add' ";
            
        }
          if(!empty($uri['member']))
        {
           $query .=" AND u1.member_id='".$uri['member']."' ";
        }
         if(!empty($uri['from']) && !empty($uri['to']))
        {
           $query.=" AND (CAST(`date` as date) BETWEEN '".$uri['from']."' AND '".$uri['to']."') "; 
        }
        if(!empty($uri['default_a']) && !empty($uri['default_v']))
        {
            $query .=" AND ".$uri['default_a']."='".$uri['default_v']."' ";
        }
        if(!empty($uri['status']))
        {  
               $query .=" AND type='".$uri['status']."' ";  
        }

      if (!empty($_GET["search"]["value"])) {
          if (isAdmin($this->session->userdata('user_roles'))) {
                $query .= 'OR u1.member_id LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= 'OR u1.parent LIKE  "%' . $_GET["search"]["value"] . '%" ';
                $query .= 'OR u1.phone LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= 'OR u1.email LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= 'OR u1.user_status LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= 'OR u1.kyc_status LIKE "%' . $_GET["search"]["value"] . '%" ';
            
          }  else{
              
                $query .= 'AND wallet_transaction.amount LIKE "%' . $_GET["search"]["value"] . '%" ';
               
          }
      }
      

      if (!empty($_GET["order"])) {
        $query .= 'ORDER BY ' . $_GET['order']['0']['column'] . ' ' . $_GET['order']['0']['dir'] . ' ';
      }
      $query .= 'ORDER BY `wallet_transaction`.`wallet_transaction_id` DESC  ';
      $sql = $this->db->query($query);
      $sql = $this->db->query($query);
      $result = $sql->result_array();
  		
  			$spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'S.No');
            $sheet->setCellValue('B1', 'MEMBER ID');
            $sheet->setCellValue('C1', 'AMOUNT');
            $sheet->setCellValue('D1', 'Status');
            $sheet->setCellValue('E1', 'MODE');
            $sheet->setCellValue('F1', 'REFRENCE ID');
            $sheet->setCellValue('G1', 'ASTOCK TYPE');
            $sheet->setCellValue('H1', 'BALANCE');
            $sheet->setCellValue('I1','CLOSE BALANCE');
            $sheet->setCellValue('J1','COMMISSION');
            $sheet->setCellValue('K1','SUBCHARGE');
            $sheet->setCellValue('L1','BANK');
            $sheet->setCellValue('M1','NARRATION');
            $sheet->setCellValue('N1','TYPE');
            $sheet->setCellValue('O1','UPDATED');
            $rows = 2;
            $i=1;
            foreach ($result as $val){
               $sheet->setCellValue('A'. $rows, $i);
               $sheet->setCellValue('B'. $rows,$val['member1']);
               $sheet->setCellValue('C' . $rows, $val['amount']);
               $sheet->setCellValue('D' . $rows,$val['status']);
               $sheet->setCellValue('E' . $rows,$val['mode']);
               $sheet->setCellValue('F' . $rows,$val['refrence']);
               $sheet->setCellValue('G' . $rows,$val['stock_type']);
    
               $sheet->setCellValue('H' . $rows,$val['balance']);
               $sheet->setCellValue('I' . $rows, $val['closebalance']);
               $sheet->setCellValue('J' . $rows, $val['commission']);
               $sheet->setCellValue('K' . $rows, $val['surcharge']);
               $sheet->setCellValue('L' . $rows, $val['bank']);
               $sheet->setCellValue('M' . $rows, $val['narration']);
               $sheet->setCellValue('N' . $rows, $val['type']);
               $sheet->setCellValue('O' . $rows, $val['updated']);
                          
              $rows++;
              $i++;
            }
       $writer = new Xlsx($spreadsheet);
//   $writer->save("php://output");
       $writer->save("uploads/".$fileName);
      header("Content-Type: application/vnd.ms-excel");
     echo base_url()."/uploads/".$fileName;
  }
    
    // 04/08/2022
    
    


}

    
    
    public function get_parent_list(){

        $uri = $this->security->xss_clean($_GET);
        
        if (!empty($uri)) {
            $query = '';
    
            $output = array();
          
            $data = array();
            $parent=0;$to=0;
            
            if( isAdmin($this->session->userdata('user_roles')) ){
                
                $query .= "SELECT user.user_id ,user.member_id , user.role_id, user.parent AS parent_user_id , user_detail.first_name , user_detail.middle_name , user_detail.last_name , user_detail.phone_no FROM `user` JOIN user_detail ON user.parent = user_detail.fk_user_id WHERE user.user_id = {$this->session->userdata('user_id')} AND kyc_status = 'verify' ";
            
            }else{
               
                $query .= "SELECT user.user_id ,user.member_id , user.role_id, user.parent AS parent_user_id , user_detail.first_name , user_detail.middle_name , user_detail.last_name , user_detail.phone_no FROM `user` JOIN user_detail ON user.parent = user_detail.fk_user_id WHERE user.user_id = {$this->session->userdata('user_id')} AND kyc_status = 'verify' ";
              
            }
        
    
            if (!empty($_GET["search"]["value"])) {
                $query .= 'OR user_bank_details.account_holder_name LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= 'OR user_bank_details.account_no LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= 'OR user_bank_details.bank_name LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= 'OR user_bank_details.ifsc_code LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= 'OR user_bank_details.phone_no LIKE "%' . $_GET["search"]["value"] . '%" ';
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
            // pre($result);exit;
            $i = 1;
            
            foreach ($result as $row) {
                $sub_array = array();
                
                // $sub_array[] = $row['parent_user_id'];
                
                $sub_array[] = '<a href="#"> <button type="button" class="btn btn-sm btn-success"  data-placement="bottom" data-toggle="modal" data-target="#distributor-model">Move To Distributor Wallet</button></a>';
                    
                    
                $sub_array[] = $row['first_name']." ".$row['middle_name']." ".$row['last_name'];
                $sub_array[] = $row['member_id'];
               
                $sub_array[] = $row['member_id'];
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
    
    public function distributor_movve(){
        
        if ($_POST) {
                $data = $this->security->xss_clean($_POST);
                
                
                // pre($data);exit;
                
                if($data["amount"]<$data['balance']){
                    $userWallet = $this->common_model->bank_get($data["vendor"]);
                    $user_wallet=$data['balance'] - (float)$data['amount'] - (float)$data['distributor_charge']; 
                    
                        $logme = [
                           
                            'member_to' => $data["vendor"] ,
                            'member_from' =>  1,
                            'amount' => $data["amount"],
                            'type'=> "debit",
                            'refrence' => "Widthdraw_d".self::stan(),
                            'trans_type' => "distwidthdraw",
                            'mode' => "Wallet Transfer",
                            'stock_type'=> "Main Bal",
                            'surcharge' => (float)$data['distributor_charge'],
                            'balance' => $data['balance'],
                            'closebalance' => $user_wallet,
                            'bank'=> $userWallet->bank_name,
                            'account_no' => $userWallet->account_no,
                            'narration'=> "Move To Distributor Wallet",
                            'status' => 'success',
                            'date'=> date("Y-m-d h:i:sa"),
                      
                        ];
                      
                        if($id= $this->common_model->insert($logme, 'wallet_transaction')){
                        
                            $this->common_model->update(array('balance' =>$user_wallet ), 'member_id',$this->session->userdata('user_id'), 'wallet');  
                            
                            $dis_balance = $this->common_model->wallet_balance($data['parent_id']);
                            
                            $dis_user_wallet = $dis_balance + (float)$data['amount']; 
                    
                            $dis_logme = [
                               
                                'member_to' => $data['parent_id'] ,
                                'member_from' =>  1,
                                'amount' => $data["amount"],
                                'type'=> "credit",
                                'refrence' => "CreRet_".self::stan(),
                                'trans_type' => "add",
                                'mode' => "Wallet Transfer",
                                'stock_type'=> "Main Bal",
                                // 'surcharge' => (float)$data['charge'],
                                'balance' => $dis_balance,
                                'closebalance' => $dis_user_wallet,
                                'bank'=> "Wallet Transafer",
                                'account_no' => $this->session->userdata('member_id'),
                                'narration'=> "Credit From Retailer",
                                'status' => 'success',
                                'date'=> date("Y-m-d h:i:sa"),
                          
                            ];
                            
                            $id= $this->common_model->insert($dis_logme, 'wallet_transaction');
                            $this->common_model->update(array('balance' =>$dis_user_wallet ), 'member_id',$data['parent_id'], 'wallet');  
                        
                        }
                }else{
                    $this->data['main_content'] = $this->load->view('message', $this->data, true);
                    $this->load->view('layout/index', $this->data);
                }
            }
    
      redirect('wallet/widthdraw', 'refresh');
      
    }

     public function send_otp()
     {
         $data = $this->security->xss_clean($_POST);
         $otp=self::otp();
         if($this->common_model->update(['otp'=>$otp],'user_id',$this->session->userdata('user_id'),'user'))
         {
              $text="Dear User OTP for your request to do settlement from your At Moon Pe Wallet to Bank Account is: ".$otp;
              $this->client = new Client();
              try {
                 $response = $this->client->request('GET', "http://sms.vitefintech.com/api/sendmsg.php?user=&pass=&sender=&phone=".$data['phone']."&text=".$text."&priority=ndnd&stype=normal");

                  $result = $response->getBody()->getContents();
                  echo "true";
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
        if($user_details && !empty($uri['otp']))
        {
          if($user_details[0]['otp']==$uri['otp'])
          {
            $this->common_model->update(['otp'=>''],'user_id',$this->session->userdata('user_id'),'user');
            echo "true";
          }
           else
        {
            echo "false";
        }
        }
        else
        {
            echo "false";
        }
     }
   
   //balance tranfer to wallet to wallet
   public function balance_tranfer()
   {
        $this->data['param'] = $this->paremlink('add');
        $this->data['main_content'] = $this->load->view('balance_tranfer', $this->data, true);
        $this->data['is_script'] = $this->load->view('script', $this->data, true);
        $this->load->view('layout/index', $this->data);
   }
   
   public function balance_tranfer_sumbmit()
   { 
        $data=$this->security->xss_clean($_POST);
       if(isset($data))
       {
        if(isset($data['vendor']) && isset($data['amount']) && isset($data['narration']) && !empty($data['vendor']))
        {
           //sender
            $sender_wallet=$this->data['bal'];
            $updateBalance = $sender_wallet-$data['amount'];    // add commission
            $updateWallet = [
              'balance' => $updateBalance,
            ];
            $user_reciever=$this->common_model->select_option($data['vendor'],'user_id','user');
            $userWallet= $this->common_model->get_user_wallet_balance($this->session->userdata('user_id'));
            if($this->common_model->update($updateWallet, 'member_id',$this->session->userdata('user_id'),'wallet'))
            {
                 $logme = [
                  'wallet_id' => $userWallet->wallet_id,
                  'member_to' =>$this->session->userdata('user_id'),
                  'member_from' =>$data['vendor'],
                  'amount' => $data['amount'],
              //   'surcharge' => $data['surcharge'],
                  'refrence' =>  'fund_transfer'.self::stan2(),
                  'stock_type' =>'fund',
                  'status' => 'success',
                    'bank'=>'fund transfer',
                  'balance' =>  $sender_wallet,
                  'closebalance' => $updateBalance,
                  'type' => 'debit',
                  'mode' => 'fund_transfer',
                  'narration' =>$data['narration'].' '.$user_reciever[0]['member_id'],
                  'date' => date('Y-m-d'),
            ];
             $this->common_model->insert($logme, 'wallet_transaction');
            // reciever
            $userWallet1= $this->common_model->get_user_wallet_balance($data['vendor']);
            $updateBalance1 = $userWallet1->balance+$data['amount'];    // add commission
            $updateWallet1 = [
              'balance' => $updateBalance1,
            ];
             if($this->common_model->update($updateWallet1,'member_id',$data['vendor'],'wallet'))
            {
                 $logme = [
                  'wallet_id' => $userWallet1->wallet_id,
                  'member_to' =>$data['vendor'],
                  'member_from' =>$this->session->userdata('user_id'),
                  'amount' => $data['amount'],
              //   'surcharge' => $data['surcharge'],
                  'refrence' =>  'fund_transfer'.self::stan2(),
                  'stock_type' =>'fund',
                  'status' => 'success',
                  'bank'=>'fund transfer',
                  'balance' =>  $userWallet1->balance,
                  'closebalance' => $updateBalance1,
                  'type' => 'credit',
                  'mode' => 'fund_transfer',
                  'narration' =>$data['narration'].' '.$this->session->userdata('member_id'),
                  'date' => date('Y-m-d'),
            ];
             $this->common_model->insert($logme, 'wallet_transaction');
            }
             $this->session->set_flashdata(
                          array(
                            'status' => 1,
                            'msg' => "Fund has been send",
                          )
                        );
           }

        }
        else
        {
             $this->session->set_flashdata(
                          array(
                            'status' => 1,
                            'msg' => "Fund has been not send",
                          )
                        );
        }
       }
       else
       {
           $this->session->set_flashdata(
                          array(
                            'status' => 1,
                            'msg' => "Fund has been not send",
                          )
                        );
       }
       redirect('balance', 'refresh');
   }
   
   public function fund_request_v()
   {
    $this->data['bank'] = $this->users_model->get_user_bank($this->session->userdata('user_id'));
    //pre($this->data['user']);exit;
    $this->data['main_content'] = $this->load->view('fund_request', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
   }
   public function fund_request()
   {
    $data=$this->security->xss_clean($_POST);
    if(isset($_FILES))
    {
      $config['upload_path'] = 'uploads/fund_request/';//.$_POST['type'];
      $config['allowed_types'] = 'jpg|jpeg|png';
      $config['max_size'] = '500'; // max_size in kb
      $config['overwrite'] = false;
      $config['encrypt_name'] = TRUE;

      $this->load->library('upload',$config);
      if(!empty(isset($_FILES["screenshot"]["name"]))){
        if($this->upload->do_upload('screenshot'))
        {
          $screenshot = $this->upload->data();
        }
        else
        {
          $this->upload->display_errors();
        }

      }

    }
    if(isset($data))
    {
      if(isset($screenshot['file_name']))
      {
        $screenshot=$screenshot['file_name'];
      }
      else
      {
        $screenshot='';
      }
      $data1=[
              'member_id'=>$this->session->userdata('member_id'),
              'user_id'=>$this->session->userdata('user_id'),
              'referance_id'=>'fund'.self::stan2(),
              'amount'=>$data['amount'],
              'image'=>$screenshot,
              'utr'=>$data['utr'],
              'remark'=>$data['narration'],
              'created'=>date("Y-m-d h:i:sa"),                  
      ];
      if($this->common_model->insert($data1,'fund_request'))
      {
        $this->session->set_flashdata(
          array(
            'status' => 0,
            'msg' => "Requested send",
         
          )
        );
        redirect('wallet/fund_request', 'refresh');
      }
      else
      {
        $this->session->set_flashdata(
          array(
            'status' => 0,
            'msg' => "Requested not send",
          
          )
        );
        redirect('wallet/fund_request', 'refresh');
      }
    }

   }

   public function get_fund( ){
    $uri = $this->security->xss_clean($_GET);
        
    if (!empty($uri)) {
        $query = '';

        $output = array();
      
        $data = array();
        $parent=0;$to=0;
        
        if( isAdmin($this->session->userdata('user_roles')) ){
            
            $query .= "SELECT * from fund_request ";
        
        }else{
           
            $query .= "SELECT * from fund_request where `member_id`=".$this->session->userdata('member_id')." ";
          
        }
    

        if (!empty($_GET["search"]["value"])) {
            $query .= 'OR user_bank_details.account_holder_name LIKE "%' . $_GET["search"]["value"] . '%" ';
            $query .= 'OR user_bank_details.account_no LIKE "%' . $_GET["search"]["value"] . '%" ';
            $query .= 'OR user_bank_details.bank_name LIKE "%' . $_GET["search"]["value"] . '%" ';
            $query .= 'OR user_bank_details.ifsc_code LIKE "%' . $_GET["search"]["value"] . '%" ';
            $query .= 'OR user_bank_details.phone_no LIKE "%' . $_GET["search"]["value"] . '%" ';
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

            if($row["status"]=="new")
            {
              $status="info";
            }
            elseif($row["status"]=="accept")
            { 
              $status="success"; 
            }
            else
            { 
              $status="danger"; 
            }
            // $sub_array[] = $row['parent_user_id'];
            
            // $sub_array[] = '<a href="#"> <button type="button" class="btn btn-sm btn-success"  data-placement="bottom" data-toggle="modal" data-target="#distributor-model">Move To Distributor Wallet</button></a>';
            if(isAdmin($this->session->userdata('user_roles')))
            {
              if($row['status']=='new')
              {
                $sub_array[] = '<a href="javascript:void(0)" onclick="approve('.$row['id'].')"> <button type="button" class="btn btn-sm btn-success"  data-placement="bottom">Appove</button></a><a href="javascript:void(0)" onclick="deny('.$row['id'].')"> <button type="button" class="btn btn-sm btn-success"  data-placement="bottom">Deny</button></a>';
              }
              else
              {
                $sub_array[]='<span class="badge badge-'.$status.'">'.strtoupper($row['status']).'</span>';
              }
            } 
            else
            {
              $sub_array[]='<span class="badge badge-'.$status.'">'.strtoupper($row['status']).'</span>';
            }   
            $sub_array[] = '<button type="button" onclick="modal(`'.$row['image'].'`)" class="btn bg-primary btn-sm" style="color:white" data-bs-toggle="modal" data-bs-target="#exampleModal">View</button>';   
            $sub_array[] = $row['member_id'];
            $sub_array[] = $row['amount'];
            $sub_array[] = $row['utr'];
            $sub_array[] = $row['referance_id'];
            $sub_array[] = $row['remark'];
            $sub_array[] = $row['created'];
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

   public function deny_fund()
   {
    if(isAdmin($this->session->userdata('user_roles'))){ 
      $uri = $this->security->xss_clean($_GET);
      $this->common_model->update(array('status'=>'deny'),'id',$uri['id'],'fund_request');
    }
   }
  public function approve_fund()
  {
    if(isAdmin($this->session->userdata('user_roles'))){
      $uri = $this->security->xss_clean($_GET);
      $transection_id = self::utan(123456790);
      $fund_date=$this->common_model->select_value($uri['id'],'fund_request');
      $userWallet = $this->common_model->get_user_wallet_balance($fund_date[0]['user_id']);
      if ($userWallet != 'none') {
         if($this->common_model->update(array('status'=>'accept'),'id',$uri['id'],'fund_request'))
         {
        $updateBalance = $userWallet->balance+$fund_date[0]['amount'];
        $updateWallet = [
            'balance' => $updateBalance,
         ];
         if($this->common_model->update($updateWallet,'member_id', $fund_date[0]['user_id'],'wallet'))
         {
          $message = [
           'msg' => 'Your wallet balance credited ' . $fund_date[0]['amount'] . ' available balance is ' . $updateBalance,
           'user_id' => $fund_date[0]['user_id']
           ];
           $this->set_notification($message);
           $logme = [
            'wallet_id' => $userWallet->wallet_id,
            'member_to' => $fund_date[0]['user_id'],
            'member_from' =>1,
            'amount' =>  $fund_date[0]['amount'],
            'refrence' =>  $fund_date[0]['referance_id'],
            'transection_id'=>$transection_id,
            'stock_type' =>'FREST',
            'status' => 'success',
            'balance' =>  $userWallet->balance,
            'closebalance' => $updateBalance,
            'type' => 'credit',
            'mode' => 'Fund Request',
            'bank' =>  'Fund Request',
            'narration' => 'Fund Request Offline',
            'date' => date('Y-m-d'),
            ];
           $this->common_model->insert($logme, 'wallet_transaction');
         }
         }
      }
  }  
  }

public function utan($node)
{

  return $node . '00' . round(microtime(true));
}

}
