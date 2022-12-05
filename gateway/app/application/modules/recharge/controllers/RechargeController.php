
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use GuzzleHttp\Client;

class RechargeController extends Vite {

  public $data = array();
  public $client;
  public function __construct() {
      parent::__construct();
            $this->tnxType = 'Recharge';
            $this->load->model('common_model');
            $this->load->model('menu_model');
            $this->load->model('commission_model');
            $this->load->model('users_model');
            $this->data['serid'] = '4';

     $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));
    //   if($_SESSION['kyc_status']!='verify'){
    //     redirect('users/kyc', 'refresh');
       
        
    // }else{
      $this->data['active'] = 'recharge';
      $this->data['breadcrumbs'] = [array('url' => base_url('recharge'), 'name' => 'recharge')];
    // }
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
//   mobile commission
  public function mobile_commission() {
    $this->data['main_content'] = $this->load->view('mobile_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('mobile_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
   public function mobile_CommissionForm() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
     

      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 18;

        $this->data['role_id'] = $baseRole;               
      //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('mobile_commission/add', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
   public function m_commissioninsert(){
      
    if ($_POST) {
   
       $data = $this->security->xss_clean($_POST);
       $data1['start_range']=$data['start'];
       $data1['operator']=$data['operator'];
       $data1['end_range']=$data['end'];
       $data1['role_id']=$data['role_id'];
       $data1['g_commission']=$data['commision'];
       $data1['service_id']=$data['service_id'];
       $data1['max_commission']=$data['max'];
       $data1['c_flat	']=isset($data['flat'])?1:0;
       $data1['created	']=date('Y-m-d hh:mm:ss');
        
 

      if ($this->common_model->insert($data1,'service_commission')) {
       $this->session->set_flashdata(
              array(
                'status' => 1,
                'msg' => " Insert Successfully"
              )
            );
            redirect('recharge/mobile/commission', 'refresh');
        }
    }
        
  }
  public function mobile_get_list()
  {

    $uri = $this->security->xss_clean($_GET);
//   pre($uri);
//   exit();
    $role_id = $uri['id'];
    
    if (!empty($uri)) {
      $query = '';

      $output = array();


      

      $data = array();

    if (isAdmin($this->session->userdata('user_roles'))) {
            
          $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id = 18 ";

          $recordsFiltered = $this->users_model->row_count($query);

        }else{
                
          $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = 18 ";

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
    //   pre($sql);exit;
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
       
       $sub_array[] = $row['operator'];
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
     public function m_commissiondelete($id)
  {
    if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
       echo 1;
    } else {
      echo 0;
    }
  }
    public function m_commissionaddupdate() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 18;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('mobile_commission/edit', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
   public function m_commissionedit($id)
      {
        $menu= $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
        echo json_encode($menu[0]);
      }
        public function m_commissionupdate()
  {
    $data=array();
    $form = $this->security->xss_clean($_POST);
  
        $logme['start_range'] = $form['start'];
        $logme['operator'] = $form['operator'];
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
            redirect('recharge/mobile/commission', 'refresh');
    }
  }
//   dth commission
 public function dth_commission() {
    $this->data['main_content'] = $this->load->view('dth_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('dth_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
   public function dth_CommissionForm() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
     

      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 19;

        $this->data['role_id'] = $baseRole;               
      //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('dth_commission/add', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
   public function dth_commissioninsert(){
      
    if ($_POST) {
   
       $data = $this->security->xss_clean($_POST);
       $data1['start_range']=$data['start'];
       $data1['end_range']=$data['end'];
       $data1['role_id']=$data['role_id'];
       $data1['g_commission']=$data['commision'];
       $data1['service_id']=$data['service_id'];
       $data1['operator']=$data['operator'];
       $data1['max_commission']=$data['max'];
       $data1['c_flat	']=isset($data['flat'])?1:0;
       $data1['created	']=date('Y-m-d hh:mm:ss');
        
 

      if ($this->common_model->insert($data1,'service_commission')) {
       $this->session->set_flashdata(
              array(
                'status' => 1,
                'msg' => " Insert Successfully"
              )
            );
            redirect('recharge/dth/commission', 'refresh');
        }
    }
        
  }
  public function dth_get_list()
  {

    $uri = $this->security->xss_clean($_GET);
//   pre($uri);
//   exit();
    $role_id = $uri['id'];
    
    if (!empty($uri)) {
      $query = '';

      $output = array();


      

      $data = array();

    if (isAdmin($this->session->userdata('user_roles'))) {
            
          $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id = 19 ";

          $recordsFiltered = $this->users_model->row_count($query);

        }else{
                
          $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = 19 ";

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
    //   pre($sql);exit;
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
       $sub_array[]=$row['operator'];
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
     public function dth_commissiondelete($id)
  {
    if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
       echo 1;
    } else {
      echo 0;
    }
  }
    public function dth_commissionaddupdate() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 19;

        $commissionList = $this->commission_model->get_list($service, $baseRole);

        echo $this->load->view('dth_commission/edit', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
   public function dth_commissionedit($id)
      {
        $menu= $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
        echo json_encode($menu[0]);
      }
        public function dth_commissionupdate()
  {
    $data=array();
    $form = $this->security->xss_clean($_POST);
  
        $logme['start_range'] = $form['start'];
        $logme['operator'] = $form['operator'];
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
            redirect('recharge/dth/commission', 'refresh');
    }
  }
//   datacard commission
 public function datacard_commission() {
    $this->data['main_content'] = $this->load->view('datacard_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('datacard_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
   public function datacard_CommissionForm() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
     

      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 20;

        $this->data['role_id'] = $baseRole;               
      //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('datacard_commission/add', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
   public function datacard_commissioninsert(){
      
    if ($_POST) {
   
       $data = $this->security->xss_clean($_POST);
       $data1['start_range']=$data['start'];
       $data1['operator']=$data['operator'];
       $data1['end_range']=$data['end'];
       $data1['role_id']=$data['role_id'];
       $data1['g_commission']=$data['commision'];
       $data1['service_id']=$data['service_id'];
       $data1['max_commission']=$data['max'];
       $data1['c_flat	']=isset($data['flat'])?1:0;
       $data1['created	']=date('Y-m-d hh:mm:ss');
        
 

      if ($this->common_model->insert($data1,'service_commission')) {
       $this->session->set_flashdata(
              array(
                'status' => 1,
                'msg' => " Insert Successfully"
              )
            );
            redirect('recharge/datacard/commission', 'refresh');
        }
    }
        
  }
  public function datacard_get_list()
  {

    $uri = $this->security->xss_clean($_GET);
//   pre($uri);
//   exit();
    $role_id = $uri['id'];
    
    if (!empty($uri)) {
      $query = '';

      $output = array();


      

      $data = array();

    if (isAdmin($this->session->userdata('user_roles'))) {
            
          $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id = 20 ";

          $recordsFiltered = $this->users_model->row_count($query);

        }else{
                
          $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = 20 ";

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
    //   pre($sql);exit;
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
       
        $sub_array[] = $row['operator'];
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
     public function datacard_commissiondelete($id)
  {
    if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
       echo 1;
    } else {
      echo 0;
    }
  }
    public function datacard_commissionaddupdate() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 20;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('datacard_commission/edit', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
   public function datacard_commissionedit($id)
      {
        $menu= $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
        echo json_encode($menu[0]);
      }
        public function datacard_commissionupdate()
  {
    $data=array();
    $form = $this->security->xss_clean($_POST);
  
        $logme['start_range'] = $form['start'];
         $logme['operator'] = $form['operator'];
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
            redirect('recharge/datacard/commission', 'refresh');
    }
  }
//   landline commission
 public function landline_commission() {
    $this->data['main_content'] = $this->load->view('landline_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('landline_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
   public function landline_CommissionForm() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
     

      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 21;

        $this->data['role_id'] = $baseRole;               
      //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('landline_commission/add', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
   public function landline_commissioninsert(){
      
    if ($_POST) {
   
       $data = $this->security->xss_clean($_POST);
       $data1['start_range']=$data['start'];
       $data1['operator']=$data['operator'];
       $data1['end_range']=$data['end'];
       $data1['role_id']=$data['role_id'];
       $data1['g_commission']=$data['commision'];
       $data1['service_id']=$data['service_id'];
       $data1['max_commission']=$data['max'];
       $data1['c_flat	']=isset($data['flat'])?1:0;
       $data1['created	']=date('Y-m-d hh:mm:ss');
        
 

      if ($this->common_model->insert($data1,'service_commission')) {
       $this->session->set_flashdata(
              array(
                'status' => 1,
                'msg' => " Insert Successfully"
              )
            );
            redirect('recharge/landline/commission', 'refresh');
        }
    }
        
  }
  public function landline_get_list()
  {

    $uri = $this->security->xss_clean($_GET);
//   pre($uri);
//   exit();
    $role_id = $uri['id'];
    
    if (!empty($uri)) {
      $query = '';

      $output = array();


      

      $data = array();

    if (isAdmin($this->session->userdata('user_roles'))) {
            
          $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id = 21 ";

          $recordsFiltered = $this->users_model->row_count($query);

        }else{
                
          $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = 21 ";

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
    //   pre($sql);exit;
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
        $sub_array[] = $row['operator'];
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
     public function landline_commissiondelete($id)
  {
    if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
       echo 1;
    } else {
      echo 0;
    }
  }
    public function landline_commissionaddupdate() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 21;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('landline_commission/edit', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
   public function landline_commissionedit($id)
      {
        $menu= $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
        echo json_encode($menu[0]);
      }
        public function landline_commissionupdate()
  {
    $data=array();
    $form = $this->security->xss_clean($_POST);
  
        $logme['start_range'] = $form['start'];
        $logme['operator'] = $form['operator'];
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
            redirect('recharge/landline/commission', 'refresh');
    }
  }
//  electricity commission
 public function electricity_commission() {
    $this->data['main_content'] = $this->load->view('electricity_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('electricity_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
   public function electricity_CommissionForm() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
     

      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 22;

        $this->data['role_id'] = $baseRole;               
      //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('electricity_commission/add', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
   public function electricity_commissioninsert(){
      
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
        
 

      if ($this->common_model->insert($data1,'service_commission')) {
       $this->session->set_flashdata(
              array(
                'status' => 1,
                'msg' => " Insert Successfully"
              )
            );
            redirect('recharge/electricity/commission', 'refresh');
        }
    }
        
  }
  public function electricity_get_list()
  {

    $uri = $this->security->xss_clean($_GET);
//   pre($uri);
//   exit();
    $role_id = $uri['id'];
    
    if (!empty($uri)) {
      $query = '';

      $output = array();


      

      $data = array();

    if (isAdmin($this->session->userdata('user_roles'))) {
            
          $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = 22 ";

          $recordsFiltered = $this->users_model->row_count($query);

        }else{
                
          $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = 22 ";

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
    //   pre($sql);exit;
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
     public function electricity_commissiondelete($id)
  {
    if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
       echo 1;
    } else {
      echo 0;
    }
  }
    public function electricity_commissionaddupdate() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 22;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('electricity_commission/edit', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
   public function electricity_commissionedit($id)
      {
        $menu= $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
        echo json_encode($menu[0]);
      }
        
    public function electricity_commissionupdate(){
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
            redirect('recharge/electricity/commission', 'refresh');
        }
    }
    
// gas commission
 public function gas_commission() {
    $this->data['main_content'] = $this->load->view('gas_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('gas_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
   public function gas_CommissionForm() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
     

      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 23;

        $this->data['role_id'] = $baseRole;               
      //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('gas_commission/add', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
   public function gas_commissioninsert(){
      
    if ($_POST) {
   
       $data = $this->security->xss_clean($_POST);
       $data1['start_range']=$data['start'];
       $data1['operator']=$data['operator'];
       $data1['end_range']=$data['end'];
       $data1['role_id']=$data['role_id'];
       $data1['g_commission']=$data['commision'];
       $data1['service_id']=$data['service_id'];
       $data1['max_commission']=$data['max'];
       $data1['c_flat	']=isset($data['flat'])?1:0;
       $data1['created	']=date('Y-m-d hh:mm:ss');
        
 

      if ($this->common_model->insert($data1,'service_commission')) {
       $this->session->set_flashdata(
              array(
                'status' => 1,
                'msg' => " Insert Successfully"
              )
            );
            redirect('recharge/gas/commission', 'refresh');
        }
    }
        
  }
  public function gas_get_list()
  {

    $uri = $this->security->xss_clean($_GET);
//   pre($uri);
//   exit();
    $role_id = $uri['id'];
    
    if (!empty($uri)) {
      $query = '';

      $output = array();


      

      $data = array();

    if (isAdmin($this->session->userdata('user_roles'))) {
            
          $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id = 23 ";

          $recordsFiltered = $this->users_model->row_count($query);

        }else{
                
          $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = 23 ";

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
    //   pre($sql);exit;
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
        $sub_array[] = $row['operator'];
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
     public function gas_commissiondelete($id)
  {
    if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
       echo 1;
    } else {
      echo 0;
    }
  }
    public function gas_commissionaddupdate() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 23;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('gas_commission/edit', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
   public function gas_commissionedit($id)
      {
        $menu= $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
        echo json_encode($menu[0]);
      }
        public function gas_commissionupdate()
  {
    $data=array();
    $form = $this->security->xss_clean($_POST);
  
        $logme['start_range'] = $form['start'];
         $logme['operator'] = $form['operator'];
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
            redirect('recharge/gas/commission', 'refresh');
    }
  }
//  water commission
 public function water_commission() {
    $this->data['main_content'] = $this->load->view('water_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('water_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
   public function water_CommissionForm() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
     

      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 24;

        $this->data['role_id'] = $baseRole;               
      //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('water_commission/add', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
   public function water_commissioninsert(){
      
    if ($_POST) {
   
       $data = $this->security->xss_clean($_POST);
       $data1['start_range']=$data['start'];
        $data1['operator']=$data['operator'];
       $data1['end_range']=$data['end'];
       $data1['role_id']=$data['role_id'];
       $data1['g_commission']=$data['commision'];
       $data1['service_id']=$data['service_id'];
       $data1['max_commission']=$data['max'];
       $data1['c_flat	']=isset($data['flat'])?1:0;
       $data1['created	']=date('Y-m-d hh:mm:ss');
        
 

      if ($this->common_model->insert($data1,'service_commission')) {
       $this->session->set_flashdata(
              array(
                'status' => 1,
                'msg' => " Insert Successfully"
              )
            );
            redirect('recharge/water/commission', 'refresh');
        }
    }
        
  }
  public function water_get_list()
  {

    $uri = $this->security->xss_clean($_GET);
//   pre($uri);
//   exit();
    $role_id = $uri['id'];
    
    if (!empty($uri)) {
      $query = '';

      $output = array();


      

      $data = array();

    if (isAdmin($this->session->userdata('user_roles'))) {
            
          $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id = 24 ";

          $recordsFiltered = $this->users_model->row_count($query);

        }else{
                
          $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = 24 ";

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
    //   pre($sql);exit;
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
       $sub_array[] = $row['operator'];
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
     public function water_commissiondelete($id)
  {
    if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
       echo 1;
    } else {
      echo 0;
    }
  }
    public function water_commissionaddupdate() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 24;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('water_commission/edit', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
   public function water_commissionedit($id)
      {
        $menu= $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
        echo json_encode($menu[0]);
      }
        public function water_commissionupdate()
  {
    $data=array();
    $form = $this->security->xss_clean($_POST);
  
        $logme['start_range'] = $form['start'];
        $logme['operator'] = $form['operator'];
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
            redirect('recharge/water/commission', 'refresh');
    }
  }
//  insurance commission
 public function insurance_commission() {
    $this->data['main_content'] = $this->load->view('insurance_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('insurance_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
   public function insurance_CommissionForm() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
     

      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 25;

        $this->data['role_id'] = $baseRole;               
      //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('insurance_commission/add', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
   public function insurance_commissioninsert(){
      
    if ($_POST) {
   
       $data = $this->security->xss_clean($_POST);
       $data1['start_range']=$data['start'];
        $data1['operator']=$data['operator'];
       $data1['end_range']=$data['end'];
       $data1['role_id']=$data['role_id'];
       $data1['g_commission']=$data['commision'];
       $data1['service_id']=$data['service_id'];
       $data1['max_commission']=$data['max'];
       $data1['c_flat	']=isset($data['flat'])?1:0;
       $data1['created	']=date('Y-m-d hh:mm:ss');
        
 

      if ($this->common_model->insert($data1,'service_commission')) {
       $this->session->set_flashdata(
              array(
                'status' => 1,
                'msg' => " Insert Successfully"
              )
            );
            redirect('recharge/insurance/commission', 'refresh');
        }
    }
        
  }
  public function insurance_get_list()
  {

    $uri = $this->security->xss_clean($_GET);
//   pre($uri);
//   exit();
    $role_id = $uri['id'];
    
    if (!empty($uri)) {
      $query = '';

      $output = array();


      

      $data = array();

    if (isAdmin($this->session->userdata('user_roles'))) {
            
          $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id = 25 ";

          $recordsFiltered = $this->users_model->row_count($query);

        }else{
                
          $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id =25 ";

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
    //   pre($sql);exit;
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
       $sub_array[] = $row['operator'];
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
     public function insurance_commissiondelete($id)
  {
    if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
       echo 1;
    } else {
      echo 0;
    }
  }
    public function insurance_commissionaddupdate() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 25;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('insurance_commission/edit', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
   public function insurance_commissionedit($id)
      {
        $menu= $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
        echo json_encode($menu[0]);
      }
        public function insurance_commissionupdate()
  {
    $data=array();
    $form = $this->security->xss_clean($_POST);
  
        $logme['start_range'] = $form['start'];
        $logme['operator'] = $form['operator'];
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
            redirect('recharge/insurance/commission', 'refresh');
    }
  }
//   loan commission
 public function loan_commission() {
    $this->data['main_content'] = $this->load->view('loan_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('loan_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
   public function loan_CommissionForm() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
     

      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 26;

        $this->data['role_id'] = $baseRole;               
      //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('loan_commission/add', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
   public function loan_commissioninsert(){
      
    if ($_POST) {
   
       $data = $this->security->xss_clean($_POST);
       $data1['start_range']=$data['start'];
       $data1['operator']=$data['operator'];
       $data1['end_range']=$data['end'];
       $data1['role_id']=$data['role_id'];
       $data1['g_commission']=$data['commision'];
       $data1['service_id']=$data['service_id'];
       $data1['max_commission']=$data['max'];
       $data1['c_flat	']=isset($data['flat'])?1:0;
       $data1['created	']=date('Y-m-d hh:mm:ss');
        
 

      if ($this->common_model->insert($data1,'service_commission')) {
       $this->session->set_flashdata(
              array(
                'status' => 1,
                'msg' => " Insert Successfully"
              )
            );
            redirect('recharge/loan/commission', 'refresh');
        }
    }
        
  }
  public function loan_get_list()
  {

    $uri = $this->security->xss_clean($_GET);
//   pre($uri);
//   exit();
    $role_id = $uri['id'];
    
    if (!empty($uri)) {
      $query = '';

      $output = array();


      

      $data = array();

    if (isAdmin($this->session->userdata('user_roles'))) {
            
          $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id = 26 ";

          $recordsFiltered = $this->users_model->row_count($query);

        }else{
                
          $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = 26 ";

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
    //   pre($sql);exit;
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
       $sub_array[] = $row['operator'];
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
     public function loan_commissiondelete($id)
  {
    if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
       echo 1;
    } else {
      echo 0;
    }
  }
    public function loan_commissionaddupdate() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 26;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('loan_commission/edit', $this->data, true);

      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }
   public function loan_commissionedit($id)
      {
        $menu= $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
        echo json_encode($menu[0]);
      }
        public function loan_commissionupdate()
  {
    $data=array();
    $form = $this->security->xss_clean($_POST);
  
        $logme['start_range'] = $form['start'];
        $logme['operator'] = $form['operator'];
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
            redirect('recharge/loan/commission', 'refresh');
    }
  }
 public function get_mobile(){
     
     $data = $this->security->xss_clean($_POST);
      $this->client = new Client();
        $this->data['submitTransection'] = [
          'apikey' => '1486404268',
          'username' => 'G482962389',
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
           'apikey' => '1486404268',
          'username' => 'G482962389',
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
           'apikey' => '1486404268',
           'username' => 'G482962389',
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


// dont touch this function without my permission 

  public function commition_distribute($id, $service, $transection,$operator) {


    $parentsList = self::checkparent($id);
    $i = 0;
      foreach ($parentsList as $key => $value) {
        $commision = $this->commission_model->get_commision_by_role_recharge($value['role_id'], $service, $transection,$operator);
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
                              'refrence' =>  $this->data['saveTransection']['transection_id'],
                              'commission' =>  $commision[$i]['g_commission'],
                              'service_id' => $service,
                              'stock_type'=> $this->tnxType,
                              'status' => 'success',
                              'balance' =>  $userWallet->balance,
                              'closebalance' => $updateBalance,
                             'type' => 'credit',
                             'mode' => 'Mobile Recharge',
                             'bank' => 'Mobile Recharge',
                             'narration' => 'Mobile Recharge Commision',
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
      }
    }
    
    
    public function commition_distribute_biil($id, $service, $transection) {


    $parentsList = self::checkparent($id);
    
        $i = 0;
      foreach ($parentsList as $key => $value) {
        $commision = $this->commission_model->get_commision_by_role_bill($value['role_id'], $service, $transection);
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
                              'refrence' =>  $this->data['saveTransection']['transection_id'],
                              'commission' =>  $commision[$i]['g_commission'],
                              'service_id' => $service,
                              'stock_type'=> $this->tnxType,
                              'status' => 'success',
                              'balance' =>  $userWallet->balance,
                              'closebalance' => $updateBalance,
                             'type' => 'credit',
                             'mode' => 'Bill Pay',
                             'bank' =>  'Bill Pay',
                             'narration' => 'Bill Pay Commision',
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
    
 public function mobile_submit() {

    if ($_POST) {

        $data = $this->security->xss_clean($_POST);

        if ($data) {

            if ($this->session->userdata('latitude')) {
                    $location = $this->session->userdata('latitude').'|'.$this->session->userdata('longitude');
            }
            
            if( $this->data['bal']>0 && $this->data['bal']>$data['amount']){
             
                $this->data['submitTransection']= [
                                                    'apikey' => '1486404268',
                                                    'username' => 'G482962389',
                                                    'format' => 'json',
                                                    'circle' => $data['circle'],
                                                    'operator' => $data['operator'],
                                                    'type' => $data['type'],
                                                    'no' => $data['mobile'],
                                                    'amount' => $data['amount'],
                                                    'user' => self::stan(),
                                                ];
        
                $response = self::transection_service();
                $response1 = json_decode($response);
                
                $this->data['saveTransection'] =   [
                                                        'transection_id' => self::utan($data['mobile']),
                                                        'transection_type' => 'Recharge',
                                                        'member_id' => $this->session->userdata('member_id'),
                                                        'transection_amount' => $response1->amount,
                                                        'service_id' => 18,
                                                        'transection_msg' =>$response1->resText,
                                                        'reference_number' => $response1->orderId,
                                                        'transection_mobile' => $response1->mobile,
                                                        'api_requist' => $data['type'],
                                                        'location' => $location,
                                                        "transection_status"=>$response1->status,
                                                        "transection_response" =>$response,
                                                    ];
                                                    
                        $this->common_model->insert( $this->data['saveTransection'], 'submit_transection');
                      
                        $userWallet = $this->common_model->wallet_balance($this->session->userdata('user_id'));
                                  
                        if($response1->status = "SUCCESS"){
                            
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
                            
                            
                            $service = 18;
                            self::commition_distribute($this->session->userdata('user_id'),$service,$response1->amount,$data['operator']);  
                            $this->session->set_flashdata(
                                                                 array('error' => 0,
                                                                    'msg' => " Recharge Successfully"
                                                                  )
                                                            );
                            redirect('recharge/mobile', 'refresh');
                        } else{
                             $this->session->set_flashdata(
                                                            array(
                                                                    'error' => 1,
                                                                    'msg' => "Recharge Faild"
                                                                ));
                                                            redirect('recharge/mobile', 'refresh');
                            
                            
                        }
                 
               }
                else{
                    $this->session->set_flashdata(
                                                    array(
                                                            'error' => 1,
                                                            'msg' => "Insufficient Balance"
                                                        ));
                                                    redirect('recharge/mobile', 'refresh');
                }


        }else{
                $this->session->set_flashdata(
                                                array(
                                                        'error' => 1,
                                                        'msg' => "Field All"
                                                    ));
                                                redirect('recharge/mobile', 'refresh');
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
    
                    $response = self::transection_service2();
                    $response1 = json_decode($response);
            
                     $this->data['saveTransection'] = [
                                                        'transection_id' => self::utan($data['account']),
                                                        'transection_type' => 'bill_payment',
                                                        'member_id' => $this->session->userdata('member_id'),
                                                        'transection_amount' => $response1->amount,
                                                        'service_id' => $data['service'],
                                                        'transection_msg' =>$response1->resText,
                                                        'reference_number' => $response1->orderId,
                                                        'transection_mobile' => $response1->mobile,
                                                        'api_requist' => $data['type'],
                                                        'location' => $location,
                                                        "transection_status"=>$response1->status,
                                                        "transection_response" =>$response,
                                                    ];
           
                        $this->common_model->insert( $this->data['saveTransection'], 'submit_transection');
                      
                        $userWallet = $this->common_model->wallet_balance($this->session->userdata('user_id'));
                                  
                        if($response1->status == "SUCCESS"){
                            
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
                            
                            
                            $service = 22;
                            self::commition_distribute_biil($this->session->userdata('user_id'),$service,$response1->amount);  
                            $this->session->set_flashdata(
                                                                array('error' => 0,
                                                                    'msg' => " Bill Successfully"
                                                                 )
                                                            );
                            redirect('recharge/electricity', 'refresh');
                        } else{
                              $this->session->set_flashdata(
                                                    array(
                                                            'error' => 1,
                                                            'msg' => "Bill Faild"
                                                        ));
                                                    redirect('recharge/electricity', 'refresh');
                            
                            
                        }
                 
               }else{
                    $this->session->set_flashdata(
                                                    array(
                                                            'error' => 1,
                                                            'msg' => "Insufficient Balance"
                                                        ));
                                                    redirect('recharge/electricity', 'refresh');
                    }


        }else{
                $this->session->set_flashdata(
                                                array(
                                                        'error' => 1,
                                                        'msg' => "Field All"
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

          $query .= "SELECT * FROM submit_transection WHERE service_id = 22 and api_requist='{$uri['type']}'";
          
          $recordsFiltered = $this->users_model->row_count($query);

        } else {

          $query .= "SELECT * FROM submit_transection WHERE service_id = 22 and api_requist='{$uri['type']}' AND member_id = '{$duid}' ";


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

          $query .= "SELECT * FROM submit_transection WHERE service_id = 18 and api_requist='{$uri['type']}'";
          
          $recordsFiltered = $this->users_model->row_count($query);

        } else {

          $query .= "SELECT * FROM submit_transection WHERE service_id = 18 and api_requist='{$uri['type']}' AND member_id = '{$duid}' ";


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

         $sub_array[] = $row['member_id'];
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
          'apikey' => '1486404268',
          'username' => 'G482962389',
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
  public function fastag() {
    $this->data['main_content'] = $this->load->view('fastag', $this->data, true);
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
    
    public function loan() {
    $this->data['main_content'] = $this->load->view('loan', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
    

 

}
