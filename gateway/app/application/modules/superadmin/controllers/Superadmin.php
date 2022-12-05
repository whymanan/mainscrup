<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Superadmin extends Vite {


  public $data = array();

  public function __construct() {
      parent::__construct();
    $this->load->model('common_model');
    $this->load->model('menu_model');
    $this->load->model('users_model');
    $this->load->model('commission_model');
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


             
            
            public function addCommission(){
             
              $this->data['member_id'] = $this->security->xss_clean($_GET);

         $this->data['main_content'] = $this->load->view('addCommission', $this->data, true);
         $this->data['is_script'] = $this->load->view('script', $this->data, true);
         $this->load->view('layout/index', $this->data);
             
         } 

         public function view_commision(){
          $this->data['commision'] = $this->common_model->select('service_commission');

          $this->data['param'] = $this->paremlink('add');
          $this->data['main_content'] = $this->load->view('view_commision', $this->data, true);
          $this->data['is_script'] = $this->load->view('script', $this->data, true);
  
          $this->load->view('layout/index', $this->data);
         }

         public function editCommission() {



          $uri = $this->security->xss_clean($_GET);
       
    
       
          if (isset($uri['q']) && !empty($uri['q'])) {
       
       
       
            $this->data['param'] = $this->paremlink('/');
       
            $this->data['main'] = $this->common_model->select_option($uri['q'],'	service_commission_id ','service_commission');
       
            $this->data['main_content'] = $this->load->view('editCommission', $this->data, true);
       
            $this->data['is_script'] = $this->load->view('script', $this->data, true);
       
            $this->load->view('layout/index', $this->data);
       
       
       
          }else{
       
       
       
            echo json_encode(['error' => 'true', 'msg' => 'Request Not allowed']);
       
       
       
          }
       
       }
         
    

           public function admindetails(){
            $this->data['param'] = $this->paremlink('/');
            $uri = $this->security->xss_clean($_GET);
            if (isset($uri['q']) && !empty($uri['q'])) {
                $uid = $uri['q'];
                if (!$this->common_model->exists('user', ['user_id' => $uid])) {
                    exit('User dosn\'t Exist');
                }
            }
            $this->data['user'] = $this->common_model->select_user_option($uri['q']);
    
            $this->data['bank'] = $this->users_model->get_user_bank($uri['q']);
            $docs = $this->common_model->select_user_doc($uri['q']);
    
            if ($docs) {
                foreach ($docs as $key => $value) {
                    $this->data['docs'][$value['type']] = $value;
                }
            }
        //pre($docs);exit;
            $this->data['main_content'] = $this->load->view('adminDetails', $this->data, true);
            $this->data['is_script'] = $this->load->view('script', $this->data, true);
            
            $this->load->view('layout/index', $this->data);
         
     } 

     //list of admin start
            
             public function admin_list(){
             
                 $this->data['member_id'] = $this->security->xss_clean($_GET);

            $this->data['main_content'] = $this->load->view('admin_list', $this->data, true);
            $this->data['is_script'] = $this->load->view('script', $this->data, true);
            $this->load->view('layout/index', $this->data);
                
            } 
           public function get_admin_list()
              {
            
                $uri = $this->security->xss_clean($_GET);
               
        
                if (!empty($uri)) {
                
            
                  $output = array();
            
            
                 
            
                  $data = array();
                  $sql = $this->db->query("select * from user where role_id='94'");
                  $filtered_rows = $sql->num_rows();
                  $result = $sql->result_array();
                 
            
                  $i = 1;
                  foreach ($result as $row) {
                    $sub_array = array();
                    
                    $sub_array[] = $row['member_id'];
                    
                     $sub_array[] = $row['email'];
                    $sub_array[] = $row['phone'];
                   
                    $sub_array[] = $row['user_type'];
                    $sub_array[] = $row['user_status'];
                    $sub_array[] = $row['kyc_status'];
                    $sub_array[] = $row['updated_at'];
                    
          $sub_array[] = '<a href="' . base_url('superadmin/admindetails?q=') . $row['user_id'] . '"> <button type="button" class="btn btn-sm btn-success"  data-placement="bottom" title="Details Menu Information">Details</button></a>';
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
            
            //list of admin ends


            // summry start
            public function summary(){
              $this->data['active'] = 'wallet';
              $this->data['breadcrumbs'] = [array('url' => base_url('superadmin/summary'), 'name' => 'Wallet Summary')];
             
             $this->data['main_content'] = $this->load->view('summary', $this->data, true);
             $this->data['is_script'] = $this->load->view('script', $this->data, true);
             $this->load->view('layout/index', $this->data);
                 
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
                  
                    $sub_array[] = '<a href="' . base_url('superadmin/superadmin/details?q=') . $row['member_to'] . '"> <button type="button" class="btn btn-sm btn-success"  data-placement="bottom" title="Approve">Detail</button></a>';
                   
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

// summary ends

// wallate summary details starts
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
// wallate summary details ends

  // hisory function start
           public function thistory() {
            $this->data['breadcrumbs'] = [array('url' => base_url('aeps'), 'name' => 'AePS'), array('url' => base_url('thistory'), 'name' => 'Transection History')];
            $this->data['param'] = $this->paremlink('/');
            $this->data['main_content'] = $this->load->view('thistory', $this->data, true);
            $this->data['is_script'] = $this->load->view('script', $this->data, true);
            $this->load->view('layout/index', $this->data);
          }

          

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

// hostory functon ends


// commission function start

          public function addCommissionForm() {

            if ($_POST) {
        
              $data = $this->security->xss_clean($_POST);
        
              if (isset($data['aepsCommissionForm'])) {
        
                $baseRole = $data['aepsCommissionForm'];
        
                $service = 1;
        
                echo $this->load->view('commission_form', $this->data, true);
        
              } else {
                echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
              }
        
            }
        
          }

          public function insert(){
      
            if ($_POST) {
           
               $data = $this->security->xss_clean($_POST);
               $data1['start_range']=$data['start'];
                $data1['end_range']=$data['end'];
                $data1['role_id']=$data['role_id'];
               $data1['g_commission']=$data['commision'];
               $data1['service_id']=$data['service_id'];
               $data1['max_commission']=$data['max'];
               $data1['c_flat	']=isset($data['flat'])?1:0;
                $data1['created	']=date('Y-m-d hh:mm:ss');

              
            $this->common_model->insert($data1,'service_commission');
            redirect('superadmin/commission_form', 'refresh');
                
            }
          }

          // commission function start

          public function delete($id)
          {
            if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
               echo 1;
            } else {
              echo 0;
            }
          }
          public function editComm($id)
  {
    $menu= $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
    echo json_encode($menu[0]);
  }

          public function addupdate() {

            if ($_POST) {
        
              $data = $this->security->xss_clean($_POST);
        
              if (isset($data['addupdate'])) {
        
                $baseRole = $data['addupdate'];
        
                $service = 1;
        
                $commissionList = $this->commission_model->get_list($service, $baseRole);
        
        
                echo $this->load->view('editCommission', $this->data, true);
        
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
            redirect('superadmin/commission_form', 'refresh');
    }
  }

  // commission edit ends
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
        

// 

// wallate function start


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
public function submitWallet()
{

  $data = $this->security->xss_clean($_POST);
  
  if ($data) {
    $wallet_id= $this->common_model->get_wallet_id($this->session->userdata('user_id'));
  
   $parent = $this->users_model->get_parent($this->session->userdata('user_id'));

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
      'balance_type' => $data["balance_type"],
      'date'=> date('Y-m-d'),
    ];
   // pre($logme);exit;
   $id= $this->common_model->insert($logme, 'wallet_transaction');
    
     
    
    redirect('superadmin/add', 'refresh');
  }
}
// wallate functons ends

}
