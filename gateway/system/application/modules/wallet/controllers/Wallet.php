<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet extends Vite {


  public $data = array();

  public function __construct() {
      parent::__construct();
    $this->load->model('common_model');
    $this->load->model('menu_model');
    $this->load->model('users_model');
        $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));

    if($_SESSION['kyc_status']!='verify'){
        redirect('users/kyc', 'refresh');
       
        
    }else{
      
             $this->data['active'] = 'wallet';
      $this->data['breadcrumbs'] = [array('url' => base_url('wallet'), 'name' => 'Wallet Balance')];
       
    }
  }

  public function index() {
     
     //echo $data;exit;
       if( $this->common_model->check_wallet( $this->session->userdata('user_id'))){
    $this->data['param'] = $this->paremlink('add');
    $this->data['total'] = $this->common_model->wallet_balance_total($this->session->userdata('user_roles'));
    $this->data['remain'] = $this->common_model->wallet_balance_remain($this->session->userdata('user_id'));
    //pre($this->data['remain']);exit;
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
        $insert['created'] = current_datetime();

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
    $this->data['bank'] = $this->users_model->get_parent_bank($this->session->userdata('user_id'));
           // pre($this->data['bank']);exit;
    $this->data['wallet'] =$this->common_model->wallet_balance($_SESSION['member_id']);
    //print_r($this->data['wallet']);exit;
    $this->data['main_content'] = $this->load->view('add', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function submit()
  {

    $data = $this->security->xss_clean($_POST);
    
    if ($data) {
      $wallet_id= $this->common_model->get_wallet_id($this->session->userdata('user_id'));
     // pre($this->session->userdata());exit;
     
      $logme = [
          'wallet_id'=>$wallet_id, 
        'member_to' =>  $this->session->userdata('user_id'),
        'member_from' =>  $this->users_model->get_parent($this->session->userdata('user_id')),
        'amount' => $data["amount"],
         'type'=> "credit",
        'narration'=> $data["remark"],
        'refrence' =>  $data["reference"],
        
        'mode' => $data["mode"],
         'stock_type'=> $data["stock_type"],
        'bank'=> $data["bank"],
        'date'=> date('Y-m-d'),
      ];
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
        
         $query .= "SELECT wallet_transaction.*,u1.member_id as member1,u2.member_id as member2 from wallet_transaction
         left join user u1 on u1.user_id=wallet_transaction.member_to
         left join user u2 on u2.user_id=wallet_transaction.member_from where wallet_transaction.trans_type='deduct'  ";
     

       //echo $query;
      if (!empty($_GET["search"]["value"])) {
        $query .= 'OR user.member_id LIKE "%' . $_GET["search"]["value"] . '%" ';
        $query .= 'OR u.parent1 LIKE  "%' . $_GET["search"]["value"] . '%" ';
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
      //  pre($this->session->userdata());
      $i = 1;
      foreach ($result as $row) {
        $sub_array = array();
       if($this->session->userdata('user_roles')==94 || $this->session->userdata('user_roles')==95 || $this->session->userdata('user_roles')==97){
           
                $sub_array[] = '<a href="' . base_url('wallet/approve?q=') . $row['wallet_transaction_id'] . '"> <button type="button" class="btn btn-sm btn-success"  data-placement="bottom" title="Approve">Approve</button></a>
                 <button type="button"class="btn btn-sm btn-primary"  data-placement="bottom" onclick="Deny(' . $row['wallet_transaction_id'] . ')" title="Deny">Deny</button>';
           
       } else{
             $sub_array[] =$i;
       }  
      
        
         $sub_array[] = $row['member1'];   
        
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
 
 public function get_widthdrawal_list()
  {

    $uri = $this->security->xss_clean($_GET);
    if (!empty($uri)) {
      $query = '';

      $output = array();


     

      $data = array();
        $parent=0;$to=0;
         $query .= "SELECT wallet_transaction.*,u1.member_id as member1,u2.member_id as member2 from wallet_transaction
          join user u1 on u1.user_id=wallet_transaction.member_to
          join user u2 on u2.user_id=wallet_transaction.member_from  where wallet_transaction.trans_type='deduct' and  wallet_transaction.member_to ='".$_SESSION['user_id']."'";
     


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
         $sub_array[] = $row['member1'];   
        }else{
        
        $sub_array[] = $row['member2'];
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
 
 public function live_count()
    {
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
    $data=        $this->common_model->get_wallet_by_id($uri['q'],'wallet_transaction_id');
   //pre($data);exit;
    $admin_wallet= $this->common_model->get_user_wallet($data[0]['member_to']);
    $user_wallet= $this->common_model->get_user_wallet($data[0]['member_from']);
    // echo $user_wallet."/";
    // echo $admin_wallet;
    // exit; 
    if($user_wallet>0){
    if($data[0]['type']=='credit' ){
    $user_wallet=$user_wallet - (int)$data[0]['amount']; 
    $admin_wallet=  $admin_wallet +( int)$data[0]['amount'];
    }else{
      $user_wallet=$user_wallet+ (int)$data[0]['amount'];
      $admin_wallet=  $admin_wallet -(int)$data[0]['amount'];
    }
    }else{
       $arr = array('msg' => 'Insufficient Balance', 'success' => false); 
    echo json_encode($arr);exit;
    }
//   echo $user_wallet."/";
//     echo $admin_wallet;
//     exit;
      $this->common_model->update(array('balance' =>$admin_wallet ), 'member_id',$data[0]['member_to'] , 'wallet');    
      $this->common_model->update(array('balance' =>$user_wallet ), 'member_id',$data[0]['member_from'] , 'wallet');    
          $this->common_model->update(array('status' => 'accept'), 'wallet_transaction_id', $uri['q'], 'wallet_transaction');

          redirect('wallet', 'refresh');

  }
public function deny()
  {
    $this->data['param'] = $this->paremlink('/');
    $uri = $this->security->xss_clean($_GET);
   
          $this->common_model->update(array('status' => 'reject'), 'wallet_transaction_id', $uri['q'], 'wallet_transaction');

          redirect('wallet', 'refresh');

  }


 
//   ak
  public function deduct_bal(){
           if ($_POST) {
      $data = $this->security->xss_clean($_POST);
  //pre($_POST);exit;
      if($data["amount"]<$data['balance']){
       $logme = [
        'member_to' => $data["vendor"] ,
        'member_from' =>  1,
        'amount' => $data["amount"],
         'type'=> "debit",
          'refrence' => "Null",
        'trans_type' => "deduct",
        'mode' => "Wallet Transfer",
         'stock_type'=> "Main Bal",
        'bank'=> "Null",
        'narration'=> $data["narration"],
    
        'date'=> date('Y-m-d'),
      ];
      //pre($logme);exit;
     $id= $this->common_model->insert($logme, 'wallet_transaction');
     $user_wallet=$data['balance'] - (float)$data['amount']; 
    $this->common_model->update(array('balance' =>$user_wallet ), 'member_id',$this->session->userdata('user_id'), 'wallet');    

      }else{
         $this->data['main_content'] = $this->load->view('message', $this->data, true);
           // $this->data['is_script'] = $this->load->view('script', $this->data, true);
            $this->load->view('layout/index', $this->data);
      }
     
    }
    
      redirect('wallet/deduct', 'refresh');
             
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
            
             public function all_request(){
            $this->data['param'] = $this->paremlink('add');
            $this->data['main_content'] = $this->load->view('all_request', $this->data, true);
            $this->data['is_script'] = $this->load->view('script', $this->data, true);
            $this->load->view('layout/index', $this->data);
                
            }
             public function request(){
            $this->data['param'] = $this->paremlink('add');
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
                  
                   $query .= " ";
                 
            
            
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
                   
                    $sub_array[] = $i;
                    $sub_array[] = $row['member_to'];
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
                  
                    $sub_array[] = '<a href="' . base_url('wallet/detail?q=') . $row['wallet_transaction_id'] . '"> <button type="button" class="btn btn-sm btn-success"  data-placement="bottom" title="Approve">Detail</button></a>';
                   
                //     $sub_array[] = $row['date'];
            
                   
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
                   $query .= "SELECT *,SUM(IF(type = 'debit', amount, 0)) as total_debit, SUM(IF(type = 'credit', amount, 0)) as total_credit FROM wallet_transaction LEFT JOIN user_detail ON user_detail.user_detail_id = wallet_transaction.member_to LEFT JOIN user ON user.user_id = wallet_transaction.member_to LEFT JOIN wallet ON wallet.member_id = wallet_transaction.member_to GROUP BY wallet_transaction.member_to  ";
                 
            
            
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
                    $sub_array[] = $i;
                    $sub_array[] = $row['member_to'];
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
            
                  echo json_encode($output);
                }
              } 



// 

}
