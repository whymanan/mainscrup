<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use GuzzleHttp\Client;

class RechargeController extends Vite
{

  public $data = array();
  public $client;
  public function __construct()
  {
    parent::__construct();
    $this->tnxType = 'Recharge';
    $this->load->model('common_model');
    $this->load->model('menu_model');
    $this->load->model('commission_model');
    $this->load->model('users_model');
    $this->load->library('user_agent');
    $this->data['serid'] = '4';

    $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));
    //   if($_SESSION['kyc_status']!='verify'){
    //     redirect('users/kyc', 'refresh');


    // }else{
    $this->data['active'] = 'recharge';
    $this->data['breadcrumbs'] = [array('url' => base_url('recharge'), 'name' => 'recharge')];
    // }
  }

  public function index()
  {
    $this->data['main_content'] = $this->load->view('index', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  // print
  public function print($id)
  {

    $query = "SELECT * FROM submit_transection WHERE primary_id = '{$id}'";

    $sql = $this->db->query($query);

    $this->data['result'] = $sql->row();
    //  echo json_encode($result);
    echo $this->load->view('print', $this->data, true);
  }
  public function mobile()
  {
    $this->data['param'] = $this->paremlink('/');
    $this->data['member_list']=self::memberselect();
    $this->data['main_content'] = $this->load->view('mobile', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }

  public function mobile_history()
  {

    $this->data['main_content'] = $this->load->view('mobile_history', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }

  public function history()
  {

    $this->data['main_content'] = $this->load->view('all', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }

  //   mobile 
  public function mobile_commission()
  {
    $this->data['main_content'] = $this->load->view('mobile_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('mobile_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
    //   retailer 
  public function retailer_show_commission()
  {
    $this->data['services']=$this->common_model->select('services');
    $this->data['main_content'] = $this->load->view('broadband_commission/retailer_show', $this->data, true);
    $this->data['is_script'] = $this->load->view('broadband_commission/scriptr', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
    //   retailer 
  public function show_get_list()
  {
    $uri = $this->security->xss_clean($_GET);
    $service = $uri['id'];

    if (!empty($uri)) {
      $query = '';

      $output = array();




      $data = array();

      if (isAdmin($this->session->userdata('user_roles'))) {
        if($service==7)
        {
            $query .= "SELECT * from service_charge where service_id ='$service' ";
        }
        else
        {
            $query .= "SELECT * from service_commission  where role_id = 98  AND service_id ='$service' ";
        }
        $recordsFiltered = $this->users_model->row_count($query);
      } else {
         if($service==7)
        {
            $query .= "SELECT * from service_charge where service_id ='$service' ";
        }
        else
        {
        $query .= "SELECT * from service_commission where role_id = 98  AND service_id = '$service' ";
        }

        $recordsFiltered = $this->users_model->row_count($query);
      }

      if (!empty($_GET["search"]["value"])) {
        $query .= 'AND start_range LIKE "%' . $_GET["search"]["value"] . '%" ';
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


        $sub_array[] =$i; 
         if($service==7)
        {
        $sub_array[] = "AEPS";
        $sub_array[] = $row['start_range'];
        $sub_array[] = $row['end_range'];
        $sub_array[] = $row['charge'];
        $sub_array[] = "";
        $sub_array[] = $row['c_flate'];
        }
        else
        {
        $sub_array[] = $row['operator'];
        $sub_array[] = $row['start_range'];
        $sub_array[] = $row['end_range'];
        $sub_array[] = $row['g_commission'];
        $sub_array[] = $row['max_commission'];
        $sub_array[] = $row['c_flat'];
        }
       



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
  
  public function mobile_CommissionForm()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);


      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 13;

        $this->data['role_id'] = $baseRole;
        //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('mobile_commission/add', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  
  public function m_commissioninsert()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      $data1['start_range'] = $data['start'];
      $data1['operator'] = $data['operator'];
      $data1['end_range'] = $data['end'];
      $data1['role_id'] = $data['role_id'];
      $data1['g_commission'] = $data['commision'];
      $data1['service_id'] = $data['service_id'];
      $data1['max_commission'] = $data['max'];
      $data1['c_flat	'] = isset($data['flat']) ? 1 : 0;
      $data1['created	'] = date('Y-m-d hh:mm:ss');



      if ($this->common_model->insert($data1, 'service_commission')) {
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

        $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id = 13 ";

        $recordsFiltered = $this->users_model->row_count($query);
      } else {

        $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = 13 ";

        $recordsFiltered = $this->users_model->row_count($query);
      }

      if (!empty($_GET["search"]["value"])) {
        $query .= 'AND start_range LIKE "%' . $_GET["search"]["value"] . '%" ';
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
  
  public function m_commissionaddupdate()
  {

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
    $menu = $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
    echo json_encode($menu[0]);
  }
  
  public function m_commissionupdate()
  {
    $data = array();
    $form = $this->security->xss_clean($_POST);

    $logme['start_range'] = $form['start'];
    $logme['operator'] = $form['operator'];
    $logme['end_range'] = $form['end'];
    $logme['g_commission'] = $form['commision'];
    $field = $form['service_commission_id'];

    $logme['max_commission'] = $form['max'];
    $logme['c_flat'] = isset($form['flat']) ? 1 : 0;
    $logme['role_id'] = $form['role_id'];
    $logme['service_id'] = $form['service_id'];



    if ($this->common_model->update($logme, "service_commission_id", $field, 'service_commission')) {
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
  public function dth_commission()
  {
    $this->data['main_content'] = $this->load->view('dth_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('dth_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  
  public function dth_CommissionForm()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);


      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 9;

        $this->data['role_id'] = $baseRole;
        //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('dth_commission/add', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  
  public function dth_commissioninsert()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      $data1['start_range'] = $data['start'];
      $data1['end_range'] = $data['end'];
      $data1['role_id'] = $data['role_id'];
      $data1['g_commission'] = $data['commision'];
      $data1['service_id'] = $data['service_id'];
      $data1['operator'] = $data['operator'];
      $data1['max_commission'] = $data['max'];
      $data1['c_flat	'] = isset($data['flat']) ? 1 : 0;
      $data1['created	'] = date('Y-m-d hh:mm:ss');



      if ($this->common_model->insert($data1, 'service_commission')) {
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

        $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id = 9 ";

        $recordsFiltered = $this->users_model->row_count($query);
      } else {

        $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = 9 ";

        $recordsFiltered = $this->users_model->row_count($query);
      }

      if (!empty($_GET["search"]["value"])) {
        $query .= 'AND start_range LIKE "%' . $_GET["search"]["value"] . '%" ';
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
  
  public function dth_commissiondelete($id)
  {
    if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
      echo 1;
    } else {
      echo 0;
    }
  }
  
  public function dth_commissionaddupdate()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 9;

        $commissionList = $this->commission_model->get_list($service, $baseRole);

        echo $this->load->view('dth_commission/edit', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  
  public function dth_commissionedit($id)
  {
    $menu = $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
    echo json_encode($menu[0]);
  }
  
  public function dth_commissionupdate()
  {
    $data = array();
    $form = $this->security->xss_clean($_POST);

    $logme['start_range'] = $form['start'];
    $logme['operator'] = $form['operator'];
    $logme['end_range'] = $form['end'];
    $logme['g_commission'] = $form['commision'];
    $field = $form['service_commission_id'];

    $logme['max_commission'] = $form['max'];
    $logme['c_flat'] = isset($form['flat']) ? 1 : 0;
    $logme['role_id'] = $form['role_id'];
    $logme['service_id'] = $form['service_id'];



    if ($this->common_model->update($logme, "service_commission_id", $field, 'service_commission')) {
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
  public function datacard_commission()
  {
    $this->data['main_content'] = $this->load->view('datacard_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('datacard_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  
  public function datacard_CommissionForm()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);


      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 14;

        $this->data['role_id'] = $baseRole;
        //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('datacard_commission/add', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  
  public function datacard_commissioninsert()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      $data1['start_range'] = $data['start'];
      $data1['operator'] = $data['operator'];
      $data1['end_range'] = $data['end'];
      $data1['role_id'] = $data['role_id'];
      $data1['g_commission'] = $data['commision'];
      $data1['service_id'] = $data['service_id'];
      $data1['max_commission'] = $data['max'];
      $data1['c_flat	'] = isset($data['flat']) ? 1 : 0;
      $data1['created	'] = date('Y-m-d hh:mm:ss');



      if ($this->common_model->insert($data1, 'service_commission')) {
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

        $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id = 14 ";

        $recordsFiltered = $this->users_model->row_count($query);
      } else {

        $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = 14 ";

        $recordsFiltered = $this->users_model->row_count($query);
      }

      if (!empty($_GET["search"]["value"])) {
        $query .= 'AND start_range LIKE "%' . $_GET["search"]["value"] . '%" ';
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
  
  public function datacard_commissionaddupdate()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 14;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('datacard_commission/edit', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  
  public function datacard_commissionedit($id)
  {
    $menu = $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
    echo json_encode($menu[0]);
  }
  
  public function datacard_commissionupdate()
  {
    $data = array();
    $form = $this->security->xss_clean($_POST);

    $logme['start_range'] = $form['start'];
    $logme['operator'] = $form['operator'];
    $logme['end_range'] = $form['end'];
    $logme['g_commission'] = $form['commision'];
    $field = $form['service_commission_id'];

    $logme['max_commission'] = $form['max'];
    $logme['c_flat'] = isset($form['flat']) ? 1 : 0;
    $logme['role_id'] = $form['role_id'];
    $logme['service_id'] = $form['service_id'];



    if ($this->common_model->update($logme, "service_commission_id", $field, 'service_commission')) {
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
  public function landline_commission()
  {
    $this->data['main_content'] = $this->load->view('landline_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('landline_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  
  public function landline_CommissionForm()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);


      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 16;

        $this->data['role_id'] = $baseRole;
        //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('landline_commission/add', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  
  public function landline_commissioninsert()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      $data1['start_range'] = $data['start'];
      $data1['operator'] = $data['operator'];
      $data1['end_range'] = $data['end'];
      $data1['role_id'] = $data['role_id'];
      $data1['g_commission'] = $data['commision'];
      $data1['service_id'] = $data['service_id'];
      $data1['max_commission'] = $data['max'];
      $data1['c_flat	'] = isset($data['flat']) ? 1 : 0;
      $data1['created	'] = date('Y-m-d hh:mm:ss');



      if ($this->common_model->insert($data1, 'service_commission')) {
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

        $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id = 16 ";

        $recordsFiltered = $this->users_model->row_count($query);
      } else {

        $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = 16 ";

        $recordsFiltered = $this->users_model->row_count($query);
      }

      if (!empty($_GET["search"]["value"])) {
        $query .= 'AND start_range LIKE "%' . $_GET["search"]["value"] . '%" ';
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
  
  public function landline_commissionaddupdate()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 16;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('landline_commission/edit', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  
  public function landline_commissionedit($id)
  {
    $menu = $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
    echo json_encode($menu[0]);
  }
  
  public function landline_commissionupdate()
  {
    $data = array();
    $form = $this->security->xss_clean($_POST);

    $logme['start_range'] = $form['start'];
    $logme['operator'] = $form['operator'];
    $logme['end_range'] = $form['end'];
    $logme['g_commission'] = $form['commision'];
    $field = $form['service_commission_id'];

    $logme['max_commission'] = $form['max'];
    $logme['c_flat'] = isset($form['flat']) ? 1 : 0;
    $logme['role_id'] = $form['role_id'];
    $logme['service_id'] = $form['service_id'];



    if ($this->common_model->update($logme, "service_commission_id", $field, 'service_commission')) {
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
  public function electricity_commission()
  {
    $this->data['main_content'] = $this->load->view('electricity_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('electricity_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  
  public function electricity_CommissionForm()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);


      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 18;

        $this->data['role_id'] = $baseRole;
        //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('electricity_commission/add', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  
  public function electricity_commissioninsert()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      $data1['start_range'] = $data['start'];
      $data1['operator'] = $data['operator'];
      $data1['end_range'] = $data['end'];
      $data1['role_id'] = $data['role_id'];
      $data1['mode'] = $data['mode'];
      $data1['g_commission'] = $data['commision'];
      $data1['service_id'] = $data['service_id'];
      $data1['max_commission'] = $data['max'];
      $data1['c_flat	'] = isset($data['flat']) ? 1 : 0;
      $data1['created	'] = date('Y-m-d hh:mm:ss');



      if ($this->common_model->insert($data1, 'service_commission')) {
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

        $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = 18 ";

        $recordsFiltered = $this->users_model->row_count($query);
      } else {

        $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = 18 ";

        $recordsFiltered = $this->users_model->row_count($query);
      }

      if (!empty($_GET["search"]["value"])) {
        $query .= 'AND start_range LIKE "%' . $_GET["search"]["value"] . '%" ';
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
          $sub_array[] = $row['mode'];
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
  
  public function electricity_commissionaddupdate()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 18;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('electricity_commission/edit', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  
  public function electricity_commissionedit($id)
  {
    $menu = $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
    echo json_encode($menu[0]);
  }

  public function electricity_commissionupdate()
  {
    $data = array();
    $form = $this->security->xss_clean($_POST);
    $logme['start_range'] = $form['start'];
    $logme['end_range'] = $form['end'];
    $logme['g_commission'] = $form['commision'];
    $field = $form['service_commission_id'];
    $logme['mode'] = $form['mode'];
    $logme['max_commission'] = $form['max'];
    $logme['c_flat'] = isset($form['flat']) ? 1 : 0;
    $logme['role_id'] = $form['role_id'];
    $logme['service_id'] = $form['service_id'];



    if ($this->common_model->update($logme, "service_commission_id", $field, 'service_commission')) {
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
  public function gas_commission()
  {
    $this->data['main_content'] = $this->load->view('gas_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('gas_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  
  public function gas_CommissionForm()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);


      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 22;

        $this->data['role_id'] = $baseRole;
        //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('gas_commission/add', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  
  public function gas_commissioninsert()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      $data1['start_range'] = $data['start'];
      $data1['operator'] = $data['operator'];
      $data1['end_range'] = $data['end'];
      $data1['role_id'] = $data['role_id'];
      $data1['g_commission'] = $data['commision'];
      $data1['service_id'] = $data['service_id'];
      $data1['max_commission'] = $data['max'];
      $data1['c_flat	'] = isset($data['flat']) ? 1 : 0;
      $data1['created	'] = date('Y-m-d hh:mm:ss');



      if ($this->common_model->insert($data1, 'service_commission')) {
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

        $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id = 22 ";

        $recordsFiltered = $this->users_model->row_count($query);
      } else {

        $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = 22 ";

        $recordsFiltered = $this->users_model->row_count($query);
      }

      if (!empty($_GET["search"]["value"])) {
        $query .= 'AND start_range LIKE "%' . $_GET["search"]["value"] . '%" ';
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
  
  public function gas_commissionaddupdate()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 22;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('gas_commission/edit', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  
  public function gas_commissionedit($id)
  {
    $menu = $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
    echo json_encode($menu[0]);
  }
  
  public function gas_commissionupdate()
  {
    $data = array();
    $form = $this->security->xss_clean($_POST);

    $logme['start_range'] = $form['start'];
    $logme['operator'] = $form['operator'];
    $logme['end_range'] = $form['end'];
    $logme['g_commission'] = $form['commision'];
    $field = $form['service_commission_id'];

    $logme['max_commission'] = $form['max'];
    $logme['c_flat'] = isset($form['flat']) ? 1 : 0;
    $logme['role_id'] = $form['role_id'];
    $logme['service_id'] = $form['service_id'];



    if ($this->common_model->update($logme, "service_commission_id", $field, 'service_commission')) {
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
  public function water_commission()
  {
    $this->data['main_content'] = $this->load->view('water_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('water_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  
  public function water_CommissionForm()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);


      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 19;

        $this->data['role_id'] = $baseRole;
        //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('water_commission/add', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  
  public function water_commissioninsert()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      $data1['start_range'] = $data['start'];
      $data1['operator'] = $data['operator'];
      $data1['end_range'] = $data['end'];
      $data1['role_id'] = $data['role_id'];
      $data1['g_commission'] = $data['commision'];
      $data1['service_id'] = $data['service_id'];
      $data1['max_commission'] = $data['max'];
      $data1['c_flat	'] = isset($data['flat']) ? 1 : 0;
      $data1['created	'] = date('Y-m-d hh:mm:ss');



      if ($this->common_model->insert($data1, 'service_commission')) {
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

        $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id =19 ";

        $recordsFiltered = $this->users_model->row_count($query);
      } else {

        $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = 19 ";

        $recordsFiltered = $this->users_model->row_count($query);
      }

      if (!empty($_GET["search"]["value"])) {
        $query .= 'AND start_range LIKE "%' . $_GET["search"]["value"] . '%" ';
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
  
  public function water_commissionaddupdate()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 19;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('water_commission/edit', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  
  public function water_commissionedit($id)
  {
    $menu = $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
    echo json_encode($menu[0]);
  }
  
  public function water_commissionupdate()
  {
    $data = array();
    $form = $this->security->xss_clean($_POST);

    $logme['start_range'] = $form['start'];
    $logme['operator'] = $form['operator'];
    $logme['end_range'] = $form['end'];
    $logme['g_commission'] = $form['commision'];
    $field = $form['service_commission_id'];

    $logme['max_commission'] = $form['max'];
    $logme['c_flat'] = isset($form['flat']) ? 1 : 0;
    $logme['role_id'] = $form['role_id'];
    $logme['service_id'] = $form['service_id'];



    if ($this->common_model->update($logme, "service_commission_id", $field, 'service_commission')) {
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
  public function insurance_commission()
  {
    $this->data['main_content'] = $this->load->view('insurance_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('insurance_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function insurance_CommissionForm()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);


      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 20;

        $this->data['role_id'] = $baseRole;
        //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('insurance_commission/add', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  public function insurance_commissioninsert()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      $data1['start_range'] = $data['start'];
      $data1['operator'] = $data['operator'];
      $data1['end_range'] = $data['end'];
      $data1['role_id'] = $data['role_id'];
      $data1['g_commission'] = $data['commision'];
      $data1['service_id'] = $data['service_id'];
      $data1['max_commission'] = $data['max'];
      $data1['c_flat	'] = isset($data['flat']) ? 1 : 0;
      $data1['created	'] = date('Y-m-d hh:mm:ss');



      if ($this->common_model->insert($data1, 'service_commission')) {
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

        $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id = 20 ";

        $recordsFiltered = $this->users_model->row_count($query);
      } else {

        $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id =20 ";

        $recordsFiltered = $this->users_model->row_count($query);
      }

      if (!empty($_GET["search"]["value"])) {
        $query .= 'AND start_range LIKE "%' . $_GET["search"]["value"] . '%" ';
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
  public function insurance_commissionaddupdate()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 20;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('insurance_commission/edit', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  public function insurance_commissionedit($id)
  {
    $menu = $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
    echo json_encode($menu[0]);
  }
  public function insurance_commissionupdate()
  {
    $data = array();
    $form = $this->security->xss_clean($_POST);

    $logme['start_range'] = $form['start'];
    $logme['operator'] = $form['operator'];
    $logme['end_range'] = $form['end'];
    $logme['g_commission'] = $form['commision'];
    $field = $form['service_commission_id'];

    $logme['max_commission'] = $form['max'];
    $logme['c_flat'] = isset($form['flat']) ? 1 : 0;
    $logme['role_id'] = $form['role_id'];
    $logme['service_id'] = $form['service_id'];



    if ($this->common_model->update($logme, "service_commission_id", $field, 'service_commission')) {
      $this->session->set_flashdata(
        array(
          'status' => 1,
          'msg' => " Updated Successfully"
        )
      );
      redirect('recharge/insurance/commission', 'refresh');
    }
  }
  //   emi commission
  public function emi_commission()
  {
    $this->data['main_content'] = $this->load->view('emi_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('emi_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function emi_CommissionForm()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);


      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 41;

        $this->data['role_id'] = $baseRole;
        //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('emi_commission/add', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  public function emi_commissioninsert()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      $data1['start_range'] = $data['start'];
      $data1['operator'] = $data['operator'];
      $data1['end_range'] = $data['end'];
      $data1['role_id'] = $data['role_id'];
      $data1['g_commission'] = $data['commision'];
      $data1['service_id'] = $data['service_id'];
      $data1['max_commission'] = $data['max'];
      $data1['c_flat	'] = isset($data['flat']) ? 1 : 0;
      $data1['created	'] = date('Y-m-d hh:mm:ss');



      if ($this->common_model->insert($data1, 'service_commission')) {
        $this->session->set_flashdata(
          array(
            'status' => 1,
            'msg' => " Insert Successfully"
          )
        );
        redirect('recharge/emi/commission', 'refresh');
      }
    }
  }
  public function emi_get_list()
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

        $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id =41 ";

        $recordsFiltered = $this->users_model->row_count($query);
      } else {

        $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id =41 ";

        $recordsFiltered = $this->users_model->row_count($query);
      }

      if (!empty($_GET["search"]["value"])) {
        $query .= 'AND start_range LIKE "%' . $_GET["search"]["value"] . '%" ';
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
  public function emi_commissiondelete($id)
  {
    if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
      echo 1;
    } else {
      echo 0;
    }
  }
  public function emi_commissionaddupdate()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 41;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('emi_commission/edit', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  public function emi_commissionedit($id)
  {
    $menu = $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
    echo json_encode($menu[0]);
  }
  public function emi_commissionupdate()
  {
    $data = array();
    $form = $this->security->xss_clean($_POST);

    $logme['start_range'] = $form['start'];
    $logme['operator'] = $form['operator'];
    $logme['end_range'] = $form['end'];
    $logme['g_commission'] = $form['commision'];
    $field = $form['service_commission_id'];

    $logme['max_commission'] = $form['max'];
    $logme['c_flat'] = isset($form['flat']) ? 1 : 0;
    $logme['role_id'] = $form['role_id'];
    $logme['service_id'] = $form['service_id'];



    if ($this->common_model->update($logme, "service_commission_id", $field, 'service_commission')) {
      $this->session->set_flashdata(
        array(
          'status' => 1,
          'msg' => " Updated Successfully"
        )
      );
      redirect('recharge/emi/commission', 'refresh');
    }
  }
//   broadband commission
 public function broadband_commission()
  {
    $this->data['main_content'] = $this->load->view('broadband_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('broadband_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function broadband_CommissionForm()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);


      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 42;

        $this->data['role_id'] = $baseRole;
        //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('broadband_commission/add', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  public function broadband_commissioninsert()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      $data1['start_range'] = $data['start'];
      $data1['operator'] = $data['operator'];
      $data1['end_range'] = $data['end'];
      $data1['role_id'] = $data['role_id'];
      $data1['g_commission'] = $data['commision'];
      $data1['service_id'] = $data['service_id'];
      $data1['max_commission'] = $data['max'];
      $data1['c_flat	'] = isset($data['flat']) ? 1 : 0;
      $data1['created	'] = date('Y-m-d hh:mm:ss');



      if ($this->common_model->insert($data1, 'service_commission')) {
        $this->session->set_flashdata(
          array(
            'status' => 1,
            'msg' => " Insert Successfully"
          )
        );
        redirect('recharge/broadband/commission', 'refresh');
      }
    }
  }
  public function broadband_get_list()
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

        $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id =42 ";

        $recordsFiltered = $this->users_model->row_count($query);
      } else {

        $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id =42 ";

        $recordsFiltered = $this->users_model->row_count($query);
      }

      if (!empty($_GET["search"]["value"])) {
        $query .= 'AND start_range LIKE "%' . $_GET["search"]["value"] . '%" ';
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
  public function broadband_commissiondelete($id)
  {
    if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
      echo 1;
    } else {
      echo 0;
    }
  }
  public function broadband_commissionaddupdate()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 42;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('broadband_commission/edit', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  public function broadband_commissionedit($id)
  {
    $menu = $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
    echo json_encode($menu[0]);
  }
  public function broadband_commissionupdate()
  {
    $data = array();
    $form = $this->security->xss_clean($_POST);

    $logme['start_range'] = $form['start'];
    $logme['operator'] = $form['operator'];
    $logme['end_range'] = $form['end'];
    $logme['g_commission'] = $form['commision'];
    $field = $form['service_commission_id'];

    $logme['max_commission'] = $form['max'];
    $logme['c_flat'] = isset($form['flat']) ? 1 : 0;
    $logme['role_id'] = $form['role_id'];
    $logme['service_id'] = $form['service_id'];



    if ($this->common_model->update($logme, "service_commission_id", $field, 'service_commission')) {
      $this->session->set_flashdata(
        array(
          'status' => 1,
          'msg' => " Updated Successfully"
        )
      );
      redirect('recharge/broadband/commission', 'refresh');
    }
  }
  //cable commission
  public function cable_commission()
  {
    $this->data['main_content'] = $this->load->view('cable_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('cable_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function cable_CommissionForm()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);


      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 43;

        $this->data['role_id'] = $baseRole;
        //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('cable_commission/add', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  public function cable_commissioninsert()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      $data1['start_range'] = $data['start'];
      $data1['operator'] = $data['operator'];
      $data1['end_range'] = $data['end'];
      $data1['role_id'] = $data['role_id'];
      $data1['g_commission'] = $data['commision'];
      $data1['service_id'] = $data['service_id'];
      $data1['max_commission'] = $data['max'];
      $data1['c_flat	'] = isset($data['flat']) ? 1 : 0;
      $data1['created	'] = date('Y-m-d hh:mm:ss');



      if ($this->common_model->insert($data1, 'service_commission')) {
        $this->session->set_flashdata(
          array(
            'status' => 1,
            'msg' => " Insert Successfully"
          )
        );
        redirect('recharge/cable/commission', 'refresh');
      }
    }
  }
  public function cable_get_list()
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

        $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id =43 ";

        $recordsFiltered = $this->users_model->row_count($query);
      } else {

        $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id =43 ";

        $recordsFiltered = $this->users_model->row_count($query);
      }

      if (!empty($_GET["search"]["value"])) {
        $query .= 'AND start_range LIKE "%' . $_GET["search"]["value"] . '%" ';
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
  public function cable_commissiondelete($id)
  {
    if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
      echo 1;
    } else {
      echo 0;
    }
  }
  public function cable_commissionaddupdate()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 43;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('cable_commission/edit', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  public function cable_commissionedit($id)
  {
    $menu = $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
    echo json_encode($menu[0]);
  }
  public function cable_commissionupdate()
  {
    $data = array();
    $form = $this->security->xss_clean($_POST);

    $logme['start_range'] = $form['start'];
    $logme['operator'] = $form['operator'];
    $logme['end_range'] = $form['end'];
    $logme['g_commission'] = $form['commision'];
    $field = $form['service_commission_id'];

    $logme['max_commission'] = $form['max'];
    $logme['c_flat'] = isset($form['flat']) ? 1 : 0;
    $logme['role_id'] = $form['role_id'];
    $logme['service_id'] = $form['service_id'];



    if ($this->common_model->update($logme, "service_commission_id", $field, 'service_commission')) {
      $this->session->set_flashdata(
        array(
          'status' => 1,
          'msg' => " Updated Successfully"
        )
      );
      redirect('recharge/cable/commission', 'refresh');
    }
  }
  //challan commission
   public function challan_commission()
  {
    $this->data['main_content'] = $this->load->view('challan_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('challan_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function challan_CommissionForm()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);


      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 44;

        $this->data['role_id'] = $baseRole;
        //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('challan_commission/add', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  public function challan_commissioninsert()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      $data1['start_range'] = $data['start'];
      $data1['operator'] = $data['operator'];
      $data1['end_range'] = $data['end'];
      $data1['role_id'] = $data['role_id'];
      $data1['g_commission'] = $data['commision'];
      $data1['service_id'] = $data['service_id'];
      $data1['max_commission'] = $data['max'];
      $data1['c_flat	'] = isset($data['flat']) ? 1 : 0;
      $data1['created	'] = date('Y-m-d hh:mm:ss');



      if ($this->common_model->insert($data1, 'service_commission')) {
        $this->session->set_flashdata(
          array(
            'status' => 1,
            'msg' => " Insert Successfully"
          )
        );
        redirect('recharge/challan/commission', 'refresh');
      }
    }
  }
  public function challan_get_list()
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

        $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id =44 ";

        $recordsFiltered = $this->users_model->row_count($query);
      } else {

        $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id =44 ";

        $recordsFiltered = $this->users_model->row_count($query);
      }

      if (!empty($_GET["search"]["value"])) {
        $query .= 'AND start_range LIKE "%' . $_GET["search"]["value"] . '%" ';
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
  public function challan_commissiondelete($id)
  {
    if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
      echo 1;
    } else {
      echo 0;
    }
  }
  public function challan_commissionaddupdate()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 44;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('challan_commission/edit', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  public function challan_commissionedit($id)
  {
    $menu = $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
    echo json_encode($menu[0]);
  }
  public function challan_commissionupdate()
  {
    $data = array();
    $form = $this->security->xss_clean($_POST);

    $logme['start_range'] = $form['start'];
    $logme['operator'] = $form['operator'];
    $logme['end_range'] = $form['end'];
    $logme['g_commission'] = $form['commision'];
    $field = $form['service_commission_id'];

    $logme['max_commission'] = $form['max'];
    $logme['c_flat'] = isset($form['flat']) ? 1 : 0;
    $logme['role_id'] = $form['role_id'];
    $logme['service_id'] = $form['service_id'];



    if ($this->common_model->update($logme, "service_commission_id", $field, 'service_commission')) {
      $this->session->set_flashdata(
        array(
          'status' => 1,
          'msg' => " Updated Successfully"
        )
      );
      redirect('recharge/challan/commission', 'refresh');
    }
  }
//   municipality commision
   public function municipality_commission()
  {
    $this->data['main_content'] = $this->load->view('municipality_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('municipality_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function municipality_CommissionForm()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);


      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 46;

        $this->data['role_id'] = $baseRole;
        //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('municipality_commission/add', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  public function municipality_commissioninsert()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      $data1['start_range'] = $data['start'];
      $data1['operator'] = $data['operator'];
      $data1['end_range'] = $data['end'];
      $data1['role_id'] = $data['role_id'];
      $data1['g_commission'] = $data['commision'];
      $data1['service_id'] = $data['service_id'];
      $data1['max_commission'] = $data['max'];
      $data1['c_flat	'] = isset($data['flat']) ? 1 : 0;
      $data1['created	'] = date('Y-m-d hh:mm:ss');



      if ($this->common_model->insert($data1, 'service_commission')) {
        $this->session->set_flashdata(
          array(
            'status' => 1,
            'msg' => " Insert Successfully"
          )
        );
        redirect('recharge/Municipality/commission', 'refresh');
      }
    }
  }
  public function municipality_get_list()
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

        $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id =46 ";

        $recordsFiltered = $this->users_model->row_count($query);
      } else {

        $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id =46 ";

        $recordsFiltered = $this->users_model->row_count($query);
      }

      if (!empty($_GET["search"]["value"])) {
        $query .= 'AND start_range LIKE "%' . $_GET["search"]["value"] . '%" ';
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
  public function municipality_commissiondelete($id)
  {
    if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
      echo 1;
    } else {
      echo 0;
    }
  }
  public function municipality_commissionaddupdate()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 46;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('municipality_commission/edit', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  public function municipality_commissionedit($id)
  {
    $menu = $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
    echo json_encode($menu[0]);
  }
  public function municipality_commissionupdate()
  {
    $data = array();
    $form = $this->security->xss_clean($_POST);

    $logme['start_range'] = $form['start'];
    $logme['operator'] = $form['operator'];
    $logme['end_range'] = $form['end'];
    $logme['g_commission'] = $form['commision'];
    $field = $form['service_commission_id'];

    $logme['max_commission'] = $form['max'];
    $logme['c_flat'] = isset($form['flat']) ? 1 : 0;
    $logme['role_id'] = $form['role_id'];
    $logme['service_id'] = $form['service_id'];



    if ($this->common_model->update($logme, "service_commission_id", $field, 'service_commission')) {
      $this->session->set_flashdata(
        array(
          'status' => 1,
          'msg' => " Updated Successfully"
        )
      );
      redirect('recharge/Municipality/commission', 'refresh');
    }
  }
  //municipaltax commission
   public function municipaltax_commission()
  {
    $this->data['main_content'] = $this->load->view('municipaltax_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('municipaltax_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function municipaltax_CommissionForm()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);


      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 45;

        $this->data['role_id'] = $baseRole;
        //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('municipaltax_commission/add', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  public function municipaltax_commissioninsert()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      $data1['start_range'] = $data['start'];
      $data1['operator'] = $data['operator'];
      $data1['end_range'] = $data['end'];
      $data1['role_id'] = $data['role_id'];
      $data1['g_commission'] = $data['commision'];
      $data1['service_id'] = $data['service_id'];
      $data1['max_commission'] = $data['max'];
      $data1['c_flat	'] = isset($data['flat']) ? 1 : 0;
      $data1['created	'] = date('Y-m-d hh:mm:ss');



      if ($this->common_model->insert($data1, 'service_commission')) {
        $this->session->set_flashdata(
          array(
            'status' => 1,
            'msg' => " Insert Successfully"
          )
        );
        redirect('recharge/municipaltax/commission', 'refresh');
      }
    }
  }
  public function municipaltax_get_list()
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

        $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id =45 ";

        $recordsFiltered = $this->users_model->row_count($query);
      } else {

        $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id =45 ";

        $recordsFiltered = $this->users_model->row_count($query);
      }

      if (!empty($_GET["search"]["value"])) {
        $query .= 'AND start_range LIKE "%' . $_GET["search"]["value"] . '%" ';
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
  public function municipaltax_commissiondelete($id)
  {
    if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
      echo 1;
    } else {
      echo 0;
    }
  }
  public function municipaltax_commissionaddupdate()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 45;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('municipaltax_commission/edit', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  public function municipaltax_commissionedit($id)
  {
    $menu = $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
    echo json_encode($menu[0]);
  }
  public function municipaltax_commissionupdate()
  {
    $data = array();
    $form = $this->security->xss_clean($_POST);

    $logme['start_range'] = $form['start'];
    $logme['operator'] = $form['operator'];
    $logme['end_range'] = $form['end'];
    $logme['g_commission'] = $form['commision'];
    $field = $form['service_commission_id'];

    $logme['max_commission'] = $form['max'];
    $logme['c_flat'] = isset($form['flat']) ? 1 : 0;
    $logme['role_id'] = $form['role_id'];
    $logme['service_id'] = $form['service_id'];



    if ($this->common_model->update($logme, "service_commission_id", $field, 'service_commission')) {
      $this->session->set_flashdata(
        array(
          'status' => 1,
          'msg' => " Updated Successfully"
        )
      );
      redirect('recharge/municipaltax/commission', 'refresh');
    }
  }
  //   loan commission
  public function loan_commission()
  {
    $this->data['main_content'] = $this->load->view('loan_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('loan_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function loan_CommissionForm()
  {

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
  public function loan_commissioninsert()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      $data1['start_range'] = $data['start'];
      $data1['operator'] = $data['operator'];
      $data1['end_range'] = $data['end'];
      $data1['role_id'] = $data['role_id'];
      $data1['g_commission'] = $data['commision'];
      $data1['service_id'] = $data['service_id'];
      $data1['max_commission'] = $data['max'];
      $data1['c_flat	'] = isset($data['flat']) ? 1 : 0;
      $data1['created	'] = date('Y-m-d hh:mm:ss');



      if ($this->common_model->insert($data1, 'service_commission')) {
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
      } else {

        $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = 26 ";

        $recordsFiltered = $this->users_model->row_count($query);
      }

      if (!empty($_GET["search"]["value"])) {
        $query .= 'AND start_range LIKE "%' . $_GET["search"]["value"] . '%" ';
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
  public function loan_commissionaddupdate()
  {

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
    $menu = $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
    echo json_encode($menu[0]);
  }
  public function loan_commissionupdate()
  {
    $data = array();
    $form = $this->security->xss_clean($_POST);

    $logme['start_range'] = $form['start'];
    $logme['operator'] = $form['operator'];
    $logme['end_range'] = $form['end'];
    $logme['g_commission'] = $form['commision'];
    $field = $form['service_commission_id'];

    $logme['max_commission'] = $form['max'];
    $logme['c_flat'] = isset($form['flat']) ? 1 : 0;
    $logme['role_id'] = $form['role_id'];
    $logme['service_id'] = $form['service_id'];



    if ($this->common_model->update($logme, "service_commission_id", $field, 'service_commission')) {
      $this->session->set_flashdata(
        array(
          'status' => 1,
          'msg' => " Updated Successfully"
        )
      );
      redirect('recharge/loan/commission', 'refresh');
    }
  }
  //mobile postpaid
   public function mobilep_commission()
  {
    $this->data['main_content'] = $this->load->view('mobilep_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('mobilep_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function mobilep_CommissionForm()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);


      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 49;

        $this->data['role_id'] = $baseRole;
        //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('mobilep_commission/add', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  public function mobilep_commissioninsert()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      $data1['start_range'] = $data['start'];
      $data1['operator'] = $data['operator'];
      $data1['end_range'] = $data['end'];
      $data1['role_id'] = $data['role_id'];
      $data1['g_commission'] = $data['commision'];
      $data1['service_id'] = $data['service_id'];
      $data1['max_commission'] = $data['max'];
      $data1['c_flat	'] = isset($data['flat']) ? 1 : 0;
      $data1['created	'] = date('Y-m-d hh:mm:ss');



      if ($this->common_model->insert($data1, 'service_commission')) {
        $this->session->set_flashdata(
          array(
            'status' => 1,
            'msg' => " Insert Successfully"
          )
        );
        redirect('recharge/Recharge_Postpaid/commission', 'refresh');
      }
    }
  }
  public function mobilep_get_list()
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

        $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id = 49 ";

        $recordsFiltered = $this->users_model->row_count($query);
      } else {

        $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = 49 ";

        $recordsFiltered = $this->users_model->row_count($query);
      }

      if (!empty($_GET["search"]["value"])) {
        $query .= 'AND start_range LIKE "%' . $_GET["search"]["value"] . '%" ';
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
  public function mobilep_commissiondelete($id)
  {
    if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
      echo 1;
    } else {
      echo 0;
    }
  }
  public function mobilep_commissionaddupdate()
  {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 49;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('mobilep_commission/edit', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  public function mobilep_commissionedit($id)
  {
    $menu = $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
    echo json_encode($menu[0]);
  }
  public function mobilep_commissionupdate()
  {
    $data = array();
    $form = $this->security->xss_clean($_POST);

    $logme['start_range'] = $form['start'];
    $logme['operator'] = $form['operator'];
    $logme['end_range'] = $form['end'];
    $logme['g_commission'] = $form['commision'];
    $field = $form['service_commission_id'];

    $logme['max_commission'] = $form['max'];
    $logme['c_flat'] = isset($form['flat']) ? 1 : 0;
    $logme['role_id'] = $form['role_id'];
    $logme['service_id'] = $form['service_id'];



    if ($this->common_model->update($logme, "service_commission_id", $field, 'service_commission')) {
      $this->session->set_flashdata(
        array(
          'status' => 1,
          'msg' => " Updated Successfully"
        )
      );
      redirect('recharge/Recharge_Postpaid/commission', 'refresh');
    }
  }
  public function get_mobile()
  {

    $data = $this->security->xss_clean($_POST);
    $this->client = new Client();
    $this->data['submitTransection'] = [
      //   'apikey' => '1486404268',
      //   'username' => 'G482962389',
      //   'format' => 'json',
      'mobile' => $data['mobile'],
      'api_key'=>'POCA001',

    ];
    // print_r( $this->data['submitTransection']);exit;
    #guzzle
    try {
      $response = $this->client->request('POST', "https://vitefintech.com/viteapi/home/getmobile", [

        'form_params' => $this->data['submitTransection'],
      ]);

      $result = $response->getBody()->getContents();

      //   $result=json_decode($result);
      print_r($result);
    } catch (GuzzleHttp\Exception\BadResponseException $e) {
      #guzzle repose for future use
      $response = $e->getResponse();
      $responseBodyAsString = $response->getBody()->getContents();
      print_r($responseBodyAsString);
    }
  }

  public function fetch_plan()
  {
    $this->client = new Client();
    $data = $this->security->xss_clean($_POST);
    $this->data['submitTransection'] = [
      'circle' => $_POST['circle'],
      'operator' => $_POST['operator'],
      'api_key'=>'POCA001',
    ];
    //   print_r( $this->data['submitTransection']);exit;
    try {
      $response = $this->client->request('POST', "https://vitefintech.com/viteapi/home/plan", [

        'form_params' => $this->data['submitTransection'],
      ]);

      $result = $response->getBody()->getContents();
      //   echo $result['data'];
      //  $result=json_decode($result);
      print_r($result);
      //   echo $this->load->view('plans',$result , true);

    } catch (GuzzleHttp\Exception\BadResponseException $e) {
      #guzzle repose for future use
      $response = $e->getResponse();
      $responseBodyAsString = $response->getBody()->getContents();
      print_r($responseBodyAsString);
    }
  }
  
   public function dthinfo()
  {
    $this->client = new Client();
    $data = $this->security->xss_clean($_POST);
    $this->data['submitTransection'] = [
      'canumber' => $_POST['canumber'],
      'operator' => $_POST['operator'],
      'api_key'=>'POCA001',
    ];
    //   print_r( $this->data['submitTransection']);exit;
    try {
      $response = $this->client->request('POST', "https://vitefintech.com/viteapi/home/getdthinfo", [

        'form_params' => $this->data['submitTransection'],
      ]);

      $result = $response->getBody()->getContents();
      //   echo $result['data'];
      //  $result=json_decode($result);
      print_r($result);
      //   echo $this->load->view('plans',$result , true);

    } catch (GuzzleHttp\Exception\BadResponseException $e) {
      #guzzle repose for future use
      $response = $e->getResponse();
      $responseBodyAsString = $response->getBody()->getContents();
      print_r($responseBodyAsString);
    }
  }



  public function fetch_dth_plan()
  {
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

      $result['data'] = $response->getBody()->getContents();

      $result['data'] = json_decode($result['data']);
      //echo "<pre>";   print_r($result);exit;
      echo $this->load->view('dth_plan', $result, true);
    } catch (GuzzleHttp\Exception\BadResponseException $e) {
      #guzzle repose for future use
      $response = $e->getResponse();
      $responseBodyAsString = $response->getBody()->getContents();
      print_r($responseBodyAsString);
    }
  }

  public function get_commission($role, $service, $amount)
  {
    $slab =  $this->common_model->get_commision_by_role($role, $service);
    // pre($slab);exit;
    $commision  = 0;
    foreach ($slab as $row) {
      if ($amount >= $row['start_range'] && $amount <= $row['end_range']) {
        if ($row['c_flat'])
          $commision  = $row['g_commission'];
        else
          $commision = (float)$amount * $row['g_commission'] * 0.01;
        break;
      }
    }
    return $commision;
  }


   // dont touch this function without my permission 

  public function commition_distribute($id, $service, $transection, $operator)
  {

    if($operator==12 || $operator==14 || $operator==27 || $operator==8 || $operator==10)
    {
        $sting="DTH Recharge";
    }
    else
    {
        $sting="Mobile Recharge";
    }
    $parentsList = self::checkparent($id);
    $i = 0;
    $j = 0;
    $allwallet = [];
    foreach ($parentsList as $key => $value) {
      $commision = $this->commission_model->get_commision_by_role_recharge($value['role_id'], $service, $transection, $operator);
      if (!empty($commision)) {
        $userWallet = $this->common_model->get_user_wallet_balance($value['user_id']);

        if ($userWallet != 'none') {

          if ($commision[$i]['c_flat'] == 1) {

            $amountc = $commision[$i]['g_commission'];

            $updateBalance = $userWallet->balance + $commision[$i]['g_commission'];    // add commission
            $updateWallet = [
              'balance' => $updateBalance,
            ];
          } else {

            $amountc = $transection *  $commision[$i]['g_commission'] / 100;

            $updateBalance = $userWallet->balance + $amountc;    // add commission
            $updateWallet = [
              'balance' => $updateBalance,
            ];
          }
          if ($this->common_model->update($updateWallet, 'member_id', $value['user_id'], 'wallet')) {
            $message = [
              'msg' => 'Your wallet balance credited ' . $amountc . ' available balance is ' . $updateBalance,
              'user_id' => $value['user_id']
            ];
            $this->set_notification($message);
            $logme = [
              'wallet_id' => $userWallet->wallet_id,
              'member_to' =>  $value['user_id'],
              'member_from' =>  $value['parent'],
              'amount' =>  $transection,
              //   'surcharge' => $data['surcharge'],
             'refrence' =>  'Recharge'.self::stan2(),
             'transection_id'=>$this->data['saveTransection']['transection_id'],
              'commission' =>  $amountc,
              'service_id' => $service,
              'stock_type' => $this->tnxType,
              'status' => 'success',
              'balance' =>  $userWallet->balance,
              'closebalance' => $updateBalance,
              'type' => 'credit',
              'mode' => $sting,
              'bank' => $sting,
              'narration' => $sting.' Commision',
              'date' => date('Y-m-d'),
            ];
            $allwallet[$j] = $this->common_model->insert($logme, 'wallet_transaction');
            $j++;
          }
        } else {
          $message = [
            'msg' => 'User Wallet not Found',
            'user_id' => $value['user_id']
          ];
          $this->set_notification($message);
        }
      } else {
        $message = [
          'msg' => 'Commission Not Found',
          'user_id' => $value['user_id']
        ];
        $this->set_notification($message);
      }
    }
    return Json_encode($allwallet);
  }

  
   public function commition_distribute_biil($mode,$id, $service, $transection, $operaor)
  {
    $parentsList = self::checkparent($id);
    $i = 0;
    foreach ($parentsList as $key => $value) {
      $commision = $this->commission_model->get_commision_by_role_bill($mode,$value['role_id'], $service, $transection, $operaor);
      if (!empty($commision)) {
        $userWallet = $this->common_model->get_user_wallet_balance($value['user_id']);

        if ($userWallet != 'none') {

          if ($commision[$i]['c_flat'] == 1) {

            $amountc = $commision[$i]['g_commission'];

            $updateBalance = $userWallet->balance + $commision[$i]['g_commission'];    // add commission
            $updateWallet = [
              'balance' => $updateBalance,
            ];
          } else {

            $amountc = $transection *  $commision[$i]['g_commission'] / 100;

            $updateBalance = $userWallet->balance + $amountc;    // add commission
            $updateWallet = [
              'balance' => $updateBalance,
            ];
          }
          if ($this->common_model->update($updateWallet, 'member_id', $value['user_id'], 'wallet')) {
            $message = [
              'msg' => 'Your wallet balance credited ' . $amountc . ' available balance is ' . $updateBalance,
              'user_id' => $value['user_id']
            ];
            $this->set_notification($message);
            $logme = [
              'wallet_id' => $userWallet->wallet_id,
              'member_to' =>  $value['user_id'],
              'member_from' =>  $value['parent'],
              'amount' =>  $transection,
              //   'surcharge' => $data['surcharge'],
              'refrence' =>  'Bill'.self::stan2(),
              'transection_id'=>$this->data['saveTransection']['transection_id'],
              'commission' =>  $amountc,
              'service_id' => $service,
              'stock_type' => $this->data['saveTransection']['api_requist'],
              'status' => 'success',
              'balance' =>  $userWallet->balance,
              'closebalance' => $updateBalance,
              'type' => 'credit',
              'mode' => 'Bill Pay',
              'bank' =>  'Bill Pay',
              'narration' => 'Bill Pay Commision',
              'date' => date('Y-m-d'),
            ];
             $this->common_model->insert($logme, 'wallet_transaction');
          }
        } else {
          $message = [
            'msg' => 'User Wallet not Found',
            'user_id' => $value['user_id']
          ];
          $this->set_notification($message);
        }
      } else {
        $message = [
          'msg' => 'Commission Not Found',
          'user_id' => $value['user_id']
        ];
        $this->set_notification($message);
      }
    }
  }
  
  
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
  
  public function checkparent($id, &$parents = array(), $level = 1)
  {
    $data = $this->users_model->get_parent_recharge($id);
    // if($data->parent != 1){
    if (isset($data)) {
      $parents[$level]['user_id'] = $data->user_id;
      $parents[$level]['member_id'] = $data->member_id;
      $parents[$level]['parent'] = $data->parent;
      $parents[$level]['role_id'] = $data->role_id;
      // echo $data['parent'];

      self::checkparent($data->parent, $parents, $level + 1);
    }
    // }      
    return $parents;
  }


//   public function mobile_submit()
//   {
//     if ($_POST) {
//       $data = $this->security->xss_clean($_POST);
//       if ($data) {
//         $transection_id = self::utan($data['mobile']);
//         if ($this->session->userdata('latitude')) {
//           $location = $this->session->userdata('latitude') . '|' . $this->session->userdata('longitude');
//         }
//         if ($this->data['bal'] > 0 && $this->data['bal'] > $data['amount']) {
//           $this->data['submitTransection'] = [
//             'operator' => $data['operator'],
//             'canumber' => $data['mobile'],
//             'amount' => $data['amount'],
//             'referenceid' =>'Recharge'.self::stan(),
//             'location' => $location,
//             'transection_id' => $transection_id
//              ];
//           $response = self::transection_service('POCA001', $this->session->userdata('member_id'));
//           if(isJson($response)){
//           $response1 = json_decode($response);
//           if (isset($response1->statusCode)) {
//             $statuscode = $response1->statusCode;
//           } else {
//             $statuscode = 0;
//           }
//           // api response condition
//           if ($response1->status == 2 && $statuscode == 2) {//admin wallet
//             $this->session->set_flashdata(
//               array(
//                 'status' => 2,
//                 'msg' => $response1->msg,
//               )
//             );
//             redirect($this->agent->referrer(), 'refresh');
//           } 
//           elseif ($response1->status == true && $response1->response_code == 1 || $response1->response_code == 0 || $response1->response_code == 2) {//success
//             $this->data['saveTransection'] = [
//               'transection_id' => $transection_id,
//               'transection_type' => 'recharge',
//               'member_id' => $this->session->userdata('member_id'),
//               'transection_amount' =>  $data['amount'],
//               'service_id' => 13,
//               'transection_msg' => $response1->message,
//               'reference_number' => $response1->refid,
//               'transection_mobile' => $data['mobile'],
//               'api_requist' => $data['type'],
//               'location' => $location,
//               "transection_status" => $response1->status,
//               "transection_response" => $response,
//             ];
//             $tran_id = $this->common_model->insert($this->data['saveTransection'], 'submit_transection');
//             $userWallet = $this->common_model->wallet_balance($this->session->userdata('user_id'));
//             if ($userWallet != 'none') {
//               $updateBalance = $userWallet - $data['amount'];    //Deduct balance
//               $updateWallet = [
//                 'balance' => $updateBalance,
//               ];
//               if ($this->common_model->update($updateWallet, 'member_id', $this->session->userdata('user_id'), 'wallet')) { //update deducted balance
//                 $message = [
//                   'msg' => 'Your wallet balance debited Rs. ' . $data['amount'] . ' available balance is ' . $updateBalance,
//                   'user_id' => $this->session->userdata('user_id')
//                 ];
//                 $this->set_notification($message);
//               }
//             }
//           $wallet_store=self::commition_distribute($this->session->userdata('user_id'), 13, $data['amount'], $data['operator']); 
//           $status_pendding = array(
//             'member_id' => $this->session->userdata('member_id'),
//             'ref_id' => $this->data['submitTransection']['referenceid'],
//             'service_id' => 13,
//             'api_type' => 'mobile_recharge',
//             'amount' => $data['amount'],
//             'cnumber' => $data['mobile'],
//             'wallet_transition_id'=>$wallet_store,
//             'submit_trantision_id' => $tran_id
//           );
//           $this->common_model->insert($status_pendding, 'status_api');
//           $this->session->set_flashdata(
//               array(
//                 'status' => 0,
//                 'msg' => $response1->message,
//                 'id' => 13,
//               )
//             );
//             redirect('recharge/mobile', 'refresh');
//           } 
//           elseif ($response1->status == true && $response1->response_code == 16) {//api wallet
//             $this->session->set_flashdata(
//               array(
//                 'status' => 0,
//                 'msg' => "!Recharge Failure,Please Connent to Admin"
//               )
//             );
//             $message = [
//               'msg' => 'Insufficient amount in your rechage wallet Please do Rechage',
//               'user_id' => 'POCA001'
//             ];
//             $this->set_notification($message);
//             redirect('recharge/mobile', 'refresh');
//           }
//           elseif ($response1->status == true && $response1->response_code == 18) {//Duplicate transition
//             $this->session->set_flashdata(
//               array(
//                 'status' => 0,
//                 'msg' => "!Recharge Failure,Some issue has been created"
//               )
//             );
//             redirect('recharge/mobile', 'refresh');
//           }
//         // api response condition end
//         } 
//         }else {//user wallet
//           $this->session->set_flashdata(
//             array(
//               'status' => 0,
//               'msg' => "Insufficient Balance"
//             )
//           );
//           redirect('recharge/mobile', 'refresh');
//         }
//       } 
//       else {
//         $this->session->set_flashdata(
//           array(
//             'status' => 0,
//             'msg' => "Some issue created try again"
//           )
//         );
//          redirect('recharge/mobile', 'refresh');
//       }
//     }
//   }
   public function mobile_submit(){
     
    if ($_POST) {
        
      $data = $this->security->xss_clean($_POST);
      
      $operator1=array('11'=>'Airtel','13'=>'BSNL','18'=>'Jio','22'=>'Vodafone','4'=>'Vodafone','33'=>'MTNL DELHI','34'=>'MTNL MUMBAI','12'=>'Airtel Digital TV','14'=>'Dish TV','27'=>'Sun Direct','8'=>'Tata Sky','10'=>'Videocon D2H');
      
        if ($data) {
          
            $transection_id = self::utan($data['mobile']);
            
            if ($this->session->userdata('latitude')) {
              $location = $this->session->userdata('latitude') . '|' . $this->session->userdata('longitude');
            }
        
            if ($this->data['bal'] > 0 && $this->data['bal'] > $data['amount']) {
                  
                    $this->data['submitTransection'] = [
                      
                        'operator' => $data['operator'],
                        'canumber' => $data['mobile'],
                        'amount' => $data['amount'],
                        'referenceid' =>'Recharge_'.self::stan(),
                        'location' => $location,
                        'transection_id' => $transection_id
                     
                    ];
                 
                $response = self::transection_service('POCA001', $this->session->userdata('member_id'));
                
                if(isJson($response)){
                   $response1 = json_decode($response);
                   
                   if (isset($response1->statusCode)) {
                    $statuscode = $response1->statusCode;
                   }else {
                    $statuscode = 0;
                  }
                  // api response condition
                     if ($response1->status == 2 && $statuscode == 2) {//admin wallet
                        $this->session->set_flashdata(
                          array(
                            'status' => 2,
                            'msg' => $response1->msg,
                          )
                        );
                        redirect($this->agent->referrer(), 'refresh');
                 
                    }elseif ($response1->status == true && $response1->response_code == 1 || $response1->response_code == 3 || $response1->response_code == 2 || $response1->response_code == 4) {//success
                        
                        $this->data['saveTransection'] = [
                          'transection_id' => $transection_id,
                          'transection_type' => 'recharge',
                          'member_id' => $this->session->userdata('member_id'),
                          'transection_amount' =>  $data['amount'],
                          'service_id' => 13,
                          'transection_msg' => $response1->message,
                          'reference_number' => $response1->refid,
                          'transection_mobile' => $data['mobile'],
                          'api_requist' => $data['type'],
                          'location' => $location,
                          "transection_status" => $response1->status,
                          "transection_response" => $response,
                          "operator"=>$operator1[$data['operator']],
                          'created'=> date("Y-m-d h:i:sa")
                        ];
                        $tran_id = $this->common_model->insert($this->data['saveTransection'], 'submit_transection');
                        $userWallet = $this->common_model->wallet_balance($this->session->userdata('user_id'));
                        
                        if ($userWallet != 'none') {
                          $updateBalance = $userWallet - $data['amount'];    //Deduct balance
                          $updateWallet = [
                            'balance' => $updateBalance,
                          ];
                            if ($this->common_model->update($updateWallet, 'member_id', $this->session->userdata('user_id'), 'wallet')) { //update deducted balance
                                $message = [
                                  'msg' => 'Your wallet balance debited Rs. ' . $data['amount'] . ' available balance is ' . $updateBalance,
                                  'user_id' => $this->session->userdata('user_id')
                                
                                ];
                                
                                $logelectric = $this->common_model->get_user_wallet_balance($this->session->userdata('user_id'));
                                $logme = [
                                        'wallet_id' => $logelectric->wallet_id,
                                        'member_to' =>  $this->session->userdata('user_id'),
                                        'member_from' =>  1,
                                        'amount' =>  $data['amount'],
                                                                  //   'surcharge' => $data['surcharge'],
                                        'refrence' =>  $this->data['submitTransection']['referenceid'],
                                        'transection_id'=>$this->data['saveTransection']['transection_id'],
                                        // 'commission' =>  $amountc,
                                        'service_id' => 13,
                                        'stock_type' => $this->data['saveTransection']['api_requist'],
                                        'status' => 'success',
                                        'balance' =>  $userWallet,
                                        'closebalance' => $updateBalance,
                                        'type' => 'debit',
                                        'mode' => 'Recharge',
                                        'bank' =>  'Recharge',
                                        'narration' => 'Recharge',
                                        'date' => date("Y-m-d h:i:sa"),
                                    ];
                                                        
                                        $this->common_model->insert($logme, 'wallet_transaction'); 
                                        $this->set_notification($message);
                            }
                        }
                            $wallet_store=self::commition_distribute($this->session->userdata('user_id'), 13, $data['amount'], $data['operator']); 
                            $status_pendding = array(
                                'member_id' => $this->session->userdata('member_id'),
                                'ref_id' => $this->data['submitTransection']['referenceid'],
                                'service_id' => 13,
                                'api_type' => 'mobile_recharge',
                                'amount' => $data['amount'],
                                'cnumber' => $data['mobile'],
                                'wallet_transition_id'=>$wallet_store,
                                'submit_trantision_id' => $tran_id
                            );
                        $this->common_model->insert($status_pendding, 'status_api');
                        $this->session->set_flashdata(
                          array(
                            'status' => 0,
                            'msg' => $response1->message,
                            'id' => 13,
                          )
                        );
                        redirect('recharge/mobile', 'refresh');
                    
                    }elseif ($response1->status == 56 && $response1->response_code == 56) {
                      
                        if($response1->data->status==2 && $response1->data->msg=='SUCCESS' && $response1->data->errorcode==200){//success
                      
                            $this->data['saveTransection'] = [
                                 'transection_id' => $transection_id,
                                 'transection_type' => 'recharge',
                                 'member_id' => $this->session->userdata('member_id'),
                                 'transection_amount' =>  $data['amount'],
                                 'service_id' => 13,
                                 'transection_msg' => $response1->data->msg,
                                 'reference_number' => $response1->data->agentid,
                                 'transection_mobile' => $data['mobile'],
                                 'api_requist' => $data['type'],
                                 'location' => $location,
                                 "transection_status" => $response1->data->status,
                                 "transection_response" => $response,
                                  "operator"=>$operator1[$data['operator']],
                                 'created'=> date("Y-m-d h:i:sa")
                            ];
                            
                            $tran_id = $this->common_model->insert($this->data['saveTransection'], 'submit_transection');
                            $userWallet = $this->common_model->wallet_balance($this->session->userdata('user_id'));
                    
                            if ($userWallet != 'none') {
                              $updateBalance = $userWallet - $data['amount'];    //Deduct balance
                              $updateWallet = [
                                'balance' => $updateBalance,
                              ];
                                if ($this->common_model->update($updateWallet, 'member_id', $this->session->userdata('user_id'), 'wallet')) { //update deducted balance
                                        $message = [
                                          'msg' => 'Your wallet balance debited Rs. ' . $data['amount'] . ' available balance is ' . $updateBalance,
                                          'user_id' => $this->session->userdata('user_id')
                                        ];
                                            
                                    $logelectric = $this->common_model->get_user_wallet_balance($this->session->userdata('user_id'));
                
                                    $logme = [
                                                'wallet_id' => $logelectric->wallet_id,
                                                'member_to' =>  $this->session->userdata('user_id'),
                                                'member_from' =>  1,
                                                'amount' =>  $data['amount'],
                                                                          //   'surcharge' => $data['surcharge'],
                                                'refrence' =>  $this->data['submitTransection']['referenceid'],
                                                'transection_id'=>$this->data['saveTransection']['transection_id'],
                                                // 'commission' =>  $amountc,
                                                'service_id' => 13,
                                                'stock_type' => $this->data['saveTransection']['api_requist'],
                                                'status' => 'success',
                                                'balance' =>  $userWallet,
                                                'closebalance' => $updateBalance,
                                                'type' => 'debit',
                                                'mode' => 'Recharge',
                                                'bank' =>  'Recharge',
                                                'narration' => 'Recharge',
                                                'date' => date("Y-m-d h:i:sa"),
                                            ];
                                                                
                                            $this->common_model->insert($logme, 'wallet_transaction');
                                            
                                    $this->set_notification($message);
                                }
                            }
                            
                            $wallet_store=self::commition_distribute($this->session->userdata('user_id'), 13, $data['amount'], $data['operator']); 
                            $this->session->set_flashdata(
                              array(
                                'status' => 0,
                                'msg' => $response1->data->msg,
                                'id' => 13,
                              )
                            );
                            
                            redirect('recharge/mobile', 'refresh');
                     
                        }elseif($response1->data->status==1){//pending
                        
                                $this->data['saveTransection'] = [
                                     'transection_id' => $transection_id,
                                     'transection_type' => 'recharge',
                                     'member_id' => $this->session->userdata('member_id'),
                                     'transection_amount' =>  $data['amount'],
                                     'service_id' => 13,
                                     'transection_msg' => $response1->data->msg,
                                     'reference_number' => $response1->data->agentid,
                                     'transection_mobile' => $data['mobile'],
                                     'api_requist' => $data['type'],
                                     'location' => $location,
                                     "transection_status" => $response1->data->status,
                                     "transection_response" => $response,
                                     "operator"=>$operator1[$data['operator']],
                                     'created'=> date("Y-m-d h:i:sa")
                                ];
                                
                                $tran_id = $this->common_model->insert($this->data['saveTransection'], 'submit_transection');
                                $userWallet = $this->common_model->wallet_balance($this->session->userdata('user_id'));
                                
                                if ($userWallet != 'none') {
                                  $updateBalance = $userWallet - $data['amount'];    //Deduct balance
                                  $updateWallet = [
                                    'balance' => $updateBalance,
                                  ];
                                  if ($this->common_model->update($updateWallet, 'member_id', $this->session->userdata('user_id'), 'wallet')) { //update deducted balance
                                    $message = [
                                      'msg' => 'Your wallet balance debited Rs. ' . $data['amount'] . ' available balance is ' . $updateBalance,
                                      'user_id' => $this->session->userdata('user_id')
                                    ];
                                    
                                            
                                            $logelectric = $this->common_model->get_user_wallet_balance($this->session->userdata('user_id'));
                
                                            $logme = [
                                                'wallet_id' => $logelectric->wallet_id,
                                                'member_to' =>  $this->session->userdata('user_id'),
                                                'member_from' =>  1,
                                                'amount' =>  $data['amount'],
                                                                          //   'surcharge' => $data['surcharge'],
                                                'refrence' => $this->data['submitTransection']['referenceid'],
                                                'transection_id'=>$this->data['saveTransection']['transection_id'],
                                                // 'commission' =>  $amountc,
                                                'service_id' => 13,
                                                'stock_type' => $this->data['saveTransection']['api_requist'],
                                                'status' => 'success',
                                                'balance' =>  $userWallet,
                                                'closebalance' => $updateBalance,
                                                'type' => 'debit',
                                                'mode' => 'Recharge',
                                                'bank' =>  'Recharge',
                                                'narration' => 'Recharge',
                                                'date' => date("Y-m-d h:i:sa"),
                                            ];
                                                                
                                            $this->common_model->insert($logme, 'wallet_transaction');
                                    
                                            $this->set_notification($message);
                                    }
                                }
                                
                                $wallet_store=self::commition_distribute($this->session->userdata('user_id'), 13, $data['amount'], $data['operator']); 
                   
                                $status_pendding = array(
                                    'member_id' => $this->session->userdata('member_id'),
                                    'ref_id' => $this->data['submitTransection']['referenceid'],
                                    'service_id' => 13,
                                    'api_type' => 'mobile_recharge(M)',
                                    'amount' => $data['amount'],
                                    'cnumber' => $data['mobile'],
                                    'wallet_transition_id'=>$wallet_store,
                                    'submit_trantision_id' => $tran_id
                                );
                                
                                $this->common_model->insert($status_pendding, 'status_api');
                                $this->session->set_flashdata(
                                  array(
                                    'status' => 0,
                                    'msg' => $response1->data->msg,
                                    'id' => 13,
                                  )
                                );
                                redirect('recharge/mobile', 'refresh');
                        }else{
                                $this->session->set_flashdata(
                                      array(
                                        'status' => 0,
                                       'msg' => $response1->data->msg,
                                        'id' => 13,
                                    )
                                );
                                redirect('recharge/mobile', 'refresh'); 
                        }
                        
                    }elseif ($response1->status == true && $response1->response_code == 16) {//api wallet
                    
                        $this->session->set_flashdata(
                          array(
                            'status' => 0,
                            'msg' => "!Recharge Failure,Please Connent to Admin"
                          )
                        );
                        $message = [
                          'msg' => 'Insufficient amount in your rechage wallet Please do Rechage',
                          'user_id' => 'POCA001'
                        ];
                        $this->set_notification($message);
                        redirect('recharge/mobile', 'refresh');
                        
                    }elseif ($response1->status == true && $response1->response_code == 18) {//Duplicate transition
                    
                        $this->session->set_flashdata(
                          array(
                            'status' => 0,
                            'msg' => "!Recharge Failure,Some issue has been created"
                          )
                        );
                        redirect('recharge/mobile', 'refresh');
                    }
                   // api response condition end
                }else{
                  $this->session->set_flashdata(
                    array(
                      'status' => 110,
                      'msg' => "Invaild Response"
                    )
                  );
                  redirect('recharge/mobile', 'refresh');
                }
            
            }else{//user wallet
              $this->session->set_flashdata(
                array(
                  'status' => 0,
                  'msg' => "Insufficient Balance"
                )
              );
              redirect('recharge/mobile', 'refresh');
            }
        
        }else{
            
            $this->session->set_flashdata(
              array(
                'status' => 0,
                'msg' => "Some issue created try again"
              )
            );
             redirect('recharge/mobile', 'refresh');
        }
    }else{
            
            $this->session->set_flashdata(
              array(
                'status' => 120,
                'msg' => "Request Not allow"
              )
            );
             redirect('recharge/mobile', 'refresh');
        }
    
  }
  
 //dth 
  public function dth_submit()
  {
    if ($_POST) {
      $data = $this->security->xss_clean($_POST);
      $operator1=array('11'=>'Airtel','13'=>'BSNL','18'=>'Jio','22'=>'Vodafone','4'=>'Vodafone','33'=>'MTNL DELHI','34'=>'MTNL MUMBAI','12'=>'Airtel Digital TV','14'=>'Dish TV','27'=>'Sun Direct','8'=>'Tata Sky','10'=>'Videocon D2H');
      if ($data) {
        $transection_id = self::utan($data['mobile']);
        if ($this->session->userdata('latitude')) {
          $location = $this->session->userdata('latitude') . '|' . $this->session->userdata('longitude');
        }
        $logelectric = $this->common_model->get_user_wallet_balance($this->session->userdata('user_id'));
        if ($this->data['bal'] > 0 && $this->data['bal'] > $data['amount']) {
          $this->data['submitTransection'] = [
            'operator' => $data['operator'],
            'canumber' => $data['mobile'],
            'amount' => $data['amount'],
            'referenceid' =>'DTH'.self::stan(),
            'location' => $location,
            'transection_id' => $transection_id,
            'api_key'=>'POCA001',
            'submerchantid'=>$this->session->userdata('member_id'),
            'latitude'=>$this->session->userdata('latitude'),
            'longitude'=>$this->session->userdata('longitude'),
            'type'=>$data['type']
             ];
          $response = self::transection_dth();
          $response1 = json_decode($response);
          if (isset($response1->statusCode)) {
            $statuscode = $response1->statusCode;
          } else {
            $statuscode = 0;
          }
          // api response condition
          if ($response1->status == 2 && $statuscode == 2) {//admin wallet
            $this->session->set_flashdata(
              array(
                'status' => 2,
                'msg' => $response1->msg,
              )
            );
            redirect($this->agent->referrer(), 'refresh');
          } 
          elseif ($response1->status == true && $response1->response_code == 1 || $response1->response_code == 0 || $response1->response_code == 2) {//success
            $this->data['saveTransection'] = [
              'transection_id' => $transection_id,
              'transection_type' => 'recharge',
              'member_id' => $this->session->userdata('member_id'),
              'transection_amount' =>  $data['amount'],
              'service_id' => 9,
              'transection_msg' => $response1->message,
              'reference_number' => $response1->refid,
              'transection_mobile' => $data['mobile'],
              'api_requist' => $data['type'],
              'location' => $location,
              "transection_status" => $response1->status,
              "transection_response" => $response,
               "operator"=>$operator1[$data['operator']],
              'created'=> date("Y-m-d h:i:sa")
            ];
            $tran_id = $this->common_model->insert($this->data['saveTransection'], 'submit_transection');
            $userWallet = $this->common_model->wallet_balance($this->session->userdata('user_id'));
            if ($userWallet != 'none') {
              $updateBalance = $userWallet - $data['amount'];    //Deduct balance
              $updateWallet = [
                'balance' => $updateBalance,
              ];
              if ($this->common_model->update($updateWallet, 'member_id', $this->session->userdata('user_id'), 'wallet')) { //update deducted balance
                $message = [
                  'msg' => 'Your wallet balance debited Rs. ' . $data['amount'] . ' available balance is ' . $updateBalance,
                  'user_id' => $this->session->userdata('user_id')
                ];
                $this->set_notification($message);
              }
               $logme = [
                                        'wallet_id' => $logelectric->wallet_id,
                                        'member_to' =>  $this->session->userdata('user_id'),
                                        'member_from' =>  1,
                                        'amount' =>  $data['amount'],
                                                                  //   'surcharge' => $data['surcharge'],
                                        'refrence' =>  $this->data['submitTransection']['referenceid'],
                                        'transection_id'=>$this->data['saveTransection']['transection_id'],
                                        // 'commission' =>  $amountc,
                                        'service_id' => 9,
                                        'stock_type' => $this->data['saveTransection']['api_requist'],
                                        'status' => 'success',
                                        'balance' =>  $userWallet,
                                        'closebalance' => $updateBalance,
                                        'type' => 'debit',
                                        'mode' => 'Recharge',
                                        'bank' =>  'Recharge',
                                        'narration' => 'Recharge',
                                        'date' => date("Y-m-d h:i:sa"),
                                    ];
                        $this->common_model->insert($logme, 'wallet_transaction');
                        $this->set_notification($message);
            }
           $wallet_store=self::commition_distribute($this->session->userdata('user_id'),9, $data['amount'], $data['operator']);
           $status_pendding = array(
              'member_id' => $this->session->userdata('member_id'),
              'ref_id' => $this->data['submitTransection']['referenceid'],
              'service_id' => 9,
              'api_type' => 'mobile_recharge',
              'amount' => $data['amount'],
              'cnumber' => $data['mobile'],
              'wallet_transition_id'=>$wallet_store,
              'submit_trantision_id' => $tran_id
            );
            $this->common_model->insert($status_pendding, 'status_api');
            $this->session->set_flashdata(
              array(
                'status' => 0,
                'msg' => $response1->message,
                'id' => 9,
              )
            );
            redirect('recharge/dth', 'refresh');
          } 
          elseif ($response1->status == 56 && $response1->response_code == 56) {
              if($response1->data->status==2 && $response1->data->msg=='SUCCESS' && $response1->data->errorcode==200)//success
              {
                  $this->data['saveTransection'] = [
                        'transection_id' => $transection_id,
                        'transection_type' => 'recharge',
                        'member_id' => $this->session->userdata('member_id'),
                        'transection_amount' =>  $data['amount'],
                        'service_id' => 9,
                        'transection_msg' => $response1->data->msg,
                        'reference_number' => $response1->data->agentid,
                        'transection_mobile' => $data['mobile'],
                        'api_requist' => $data['type'],
                        'location' => $location,
                        "transection_status" => $response1->data->status,
                        "transection_response" => $response,
                         "operator"=>$operator1[$data['operator']],
                        'created'=> date("Y-m-d h:i:sa")
                      ];
            $tran_id = $this->common_model->insert($this->data['saveTransection'], 'submit_transection');
            $userWallet = $this->common_model->wallet_balance($this->session->userdata('user_id'));
             if ($userWallet != 'none') {
              $updateBalance = $userWallet - $data['amount'];    //Deduct balance
              $updateWallet = [
                'balance' => $updateBalance,
              ];
              if ($this->common_model->update($updateWallet, 'member_id', $this->session->userdata('user_id'), 'wallet')) { //update deducted balance
                $message = [
                  'msg' => 'Your wallet balance debited Rs. ' . $data['amount'] . ' available balance is ' . $updateBalance,
                  'user_id' => $this->session->userdata('user_id')
                ];
                $this->set_notification($message);
              }
              $logme = [
                                        'wallet_id' => $logelectric->wallet_id,
                                        'member_to' => $this->session->userdata('user_id'),
                                        'member_from' =>  1,
                                        'amount' =>  $data['amount'],
                                                                  //   'surcharge' => $data['surcharge'],
                                        'refrence' =>  $this->data['submitTransection']['referenceid'],
                                        'transection_id'=>$this->data['saveTransection']['transection_id'],
                                        // 'commission' =>  $amountc,
                                        'service_id' => 9,
                                        'stock_type' => $this->data['saveTransection']['api_requist'],
                                        'status' => 'success',
                                        'balance' =>  $userWallet,
                                        'closebalance' => $updateBalance,
                                        'type' => 'debit',
                                        'mode' => 'Recharge',
                                        'bank' =>  'Recharge',
                                        'narration' => 'Recharge',
                                        'date' => date("Y-m-d h:i:sa"),
                                    ];
                        $this->common_model->insert($logme, 'wallet_transaction');
                        $this->set_notification($message);
            }
           $wallet_store=self::commition_distribute($this->session->userdata('user_id'),9, $data['amount'], $data['operator']);
           $this->session->set_flashdata(
              array(
                'status' => 0,
                'msg' => $response1->data->msg,
                'id' => 9,
              )
            );
            redirect('recharge/dth', 'refresh');
             }
              elseif($response1->data->status==1)//pending
              {
                  $this->data['saveTransection'] = [
                      'transection_id' => $transection_id,
                      'transection_type' => 'recharge',
                      'member_id' => $this->session->userdata('member_id'),
                      'transection_amount' =>  $data['amount'],
                      'service_id' => 9,
                     'transection_msg' => $response1->data->msg,
                        'reference_number' => $response1->data->agentid,
                      'transection_mobile' => $data['mobile'],
                      'api_requist' => $data['type'],
                      'location' => $location,
                      "transection_status" => $response1->data->status,
                      "transection_response" => $response,
                      "operator"=>$operator1[$data['operator']],
                      'created'=> date("Y-m-d h:i:sa")
            ];
            $tran_id = $this->common_model->insert($this->data['saveTransection'], 'submit_transection');
            $userWallet = $this->common_model->wallet_balance($this->session->userdata('user_id'));
            if ($userWallet != 'none') {
              $updateBalance = $userWallet - $data['amount'];    //Deduct balance
              $updateWallet = [
                'balance' => $updateBalance,
              ];
              if ($this->common_model->update($updateWallet, 'member_id', $this->session->userdata('user_id'), 'wallet')) { //update deducted balance
                $message = [
                  'msg' => 'Your wallet balance debited Rs. ' . $data['amount'] . ' available balance is ' . $updateBalance,
                  'user_id' => $this->session->userdata('user_id')
                ];
                $this->set_notification($message);
              }
              $logme = [
                                        'wallet_id' => $logelectric->wallet_id,
                                        'member_to' => $this->session->userdata('user_id'),
                                        'member_from' =>  1,
                                        'amount' =>  $data['amount'],
                                                                  //   'surcharge' => $data['surcharge'],
                                        'refrence' =>  $this->data['submitTransection']['referenceid'],
                                        'transection_id'=>$this->data['saveTransection']['transection_id'],
                                        // 'commission' =>  $amountc,
                                        'service_id' => 9,
                                        'stock_type' => $this->data['saveTransection']['api_requist'],
                                        'status' => 'success',
                                        'balance' =>  $userWallet,
                                        'closebalance' => $updateBalance,
                                        'type' => 'debit',
                                        'mode' => 'Recharge',
                                        'bank' =>  'Recharge',
                                        'narration' => 'Recharge',
                                        'date' => date("Y-m-d h:i:sa"),
                                    ];
                        $this->common_model->insert($logme, 'wallet_transaction');
                        $this->set_notification($message);
            }
           $wallet_store=self::commition_distribute($this->session->userdata('user_id'),9, $data['amount'], $data['operator']);
           $status_pendding = array(
            'member_id' => $this->session->userdata('member_id'),
            'ref_id' => $this->data['submitTransection']['referenceid'],
            'service_id' => 9,
            'api_type' => 'mobile_recharge(M)',
            'amount' => $data['amount'],
            'cnumber' => $data['mobile'],
            'wallet_transition_id'=>$wallet_store,
            'submit_trantision_id' => $tran_id
          );
          $this->common_model->insert($status_pendding, 'status_api');
           $this->session->set_flashdata(
              array(
                'status' => 0,
                'msg' => $response1->data->msg,
                'id' => 9,
              )
            );
            redirect('recharge/dth', 'refresh');
             }
              else
              {
                 $this->session->set_flashdata(
                  array(
                    'status' => 0,
                   'msg' => $response1->data->msg,
                    'id' => 9,
                )
              );
             redirect('recharge/dth', 'refresh'); 
              }
          }
          elseif ($response1->status == true && $response1->response_code == 16) {//api wallet
            $this->session->set_flashdata(
              array(
                'status' => 0,
                'msg' => "!Recharge Failure,Please Connent to Admin"
              )
            );
            $message = [
              'msg' => 'Insufficient amount in your rechage wallet Please do Rechage',
              'user_id' => 'POCA001'
            ];
            $this->set_notification($message);
            redirect('recharge/dth', 'refresh');
          }
          elseif ($response1->status == true && $response1->response_code == 18) {//Duplicate transition
            $this->session->set_flashdata(
              array(
                'status' => 0,
                'msg' => "!Recharge Failure,Some issue has been created"
              )
            );
            redirect('recharge/dth', 'refresh');
          }
        // api response condition end
        } else {//user wallet
          $this->session->set_flashdata(
            array(
              'status' => 0,
              'msg' => "Insufficient Balance"
            )
          );
          redirect('recharge/dth', 'refresh');
        }
      } 
      else {
        $this->session->set_flashdata(
          array(
            'status' => 0,
            'msg' => "Some issue created try again"
          )
        );
         redirect('recharge/dth', 'refresh');
      }
    }
  }

  public function transection_dth()
  {
    $this->client = new Client();
    #guzzle
    try {
      $response = $this->client->request('POST', "https://vitefintech.com/viteapi/home/dthpay/", [

        'form_params' => $this->data['submitTransection'],
      ]);


      return  $response->getBody()->getContents();
    } catch (GuzzleHttp\Exception\BadResponseException $e) {
      #guzzle repose for future use
      $response = $e->getResponse();
      $responseBodyAsString = $response->getBody()->getContents();
      print_r($responseBodyAsString);
      }
  }
  
  public function stan()
  {
    return mt_rand(99999,99999).time();
  }
   
  public function utan($node)
  {

    return $node . '00' . round(microtime(true));
  }
  
//new
    public function bill_submit(){
        $this->load->library('user_agent');
        if ($_POST) {
          $data = $this->security->xss_clean($_POST);

           $this->db->select('operator_name')
                             ->from('billpayment')
                             ->where('operator_id',$data['operator']);
                            
       $query = $this->db->get();
       $operator1=$query->result_array();
          if ($data) {
              if(isset($data['mode12']) && !empty($data['mode12']))  //data set  or null n ho tab
              {
                 $mode= $data['mode12'];
              }
              else
              {
                  $mode= 'online';
              }
            if ($this->session->userdata('latitude')) {
              $location = $this->session->userdata('latitude') . '|' . $this->session->userdata('longitude');
            }
            $logelectric = $this->common_model->get_user_wallet_balance($this->session->userdata('user_id'));
            if ($this->data['bal'] > 0 && $this->data['bal'] > $data['amount']) {
                
                    $transection_id = self::utan($data['account']);
                    $this->data['submitTransection'] = [
                                                    'operator' => $data['operator'],
                                                    'canumber' => $data['account'],
                                                    'amount' => $data['amount'],
                                                    'referenceid' => "Bill_".self::stan(),
                                                    'latitude' => $this->session->userdata('latitude'),
                                                    'longitude' => $this->session->userdata('longitude'),
                                                    'billdate' => date("d/M/Y"),
                                                    'duedate' => $data['duedate'],
                                                    'name' => $data['username'],
                                                    'location' => $location,
                                                    'transection_id' => $transection_id,
                                                    'type' => $data['type'],
                                                    'api_key'=>'POCA001',
                                                    'submerchantid'=>$this->session->userdata('member_id'),
                                                    // 'mode'=>'online'
                                                    'mode'=>'$mode'
                                                ];
              
                    $this->data['saveTransection'] = [
                                                    'transection_id' => $transection_id,
                                                    'transection_type' => 'bill_payment',
                                                    'member_id' => $this->session->userdata('member_id'),
                                                    'transection_amount' => $data['amount'],
                                                    'service_id' => $data['service'],
                                                    'reference_number' => $this->data['submitTransection']['referenceid'],
                                                    'transection_mobile' => $data['account'],
                                                    'api_requist' => $data['type'],
                                                    'location' => $location,
                                                    'operator'=>$operator1[0]['operator_name'],
                                                     'created'=> date("Y-m-d h:i:sa")
                                                ];
                    $transition_id=$this->common_model->insert($this->data['saveTransection'], 'submit_transection');
                    $response = self::transection_service2();
                if (isJson($response)) {
                  
                        $result = json_decode($response); 
                        $action = [
                                    'transection_status' =>  $result->status,
                                    'transection_msg' => $result->message,
                                    'transection_respcode' => $result->response_code,
                                    'transection_response' => $response,
                                    ];
                        $this->common_model->update($action, 'primary_id', $transition_id, 'submit_transection');
              
                    if(isset($result->status) && isset($result->statusCode)){
                    
                        if($result->status==2 && $result->statusCode=2){
                                $this->session->set_flashdata(
                                                                array(
                                                                  'status' => 2,
                                                                  'msg' => $result->msg,
                                                                )
                                                            );
                                redirect($this->agent->referrer(), 'refresh');
                }
                    }
                    
                    if(isset($result->status) && isset($result->response_code)){
                        
                        $userWallet = $this->common_model->wallet_balance($this->session->userdata('user_id'));//wallet
                        
                        if($result->status==true && $result->response_code==1){ //succeess
                            if ($userWallet != 'none'){
                                
                                    $updateBalance = $userWallet - $data['amount'];    //Deduct balance
                                    $updateWallet = [
                                                    'balance' => $updateBalance,
                                                    ];
                                                    
                                    if ($this->common_model->update($updateWallet, 'member_id', $this->session->userdata('user_id'), 'wallet')) { //update deducted balance
                                            $message = [
                                                           'msg' => 'Your wallet balance debited Rs. ' . $data['amount'] . ' available balance is ' . $updateBalance,
                                                           'user_id' => $this->session->userdata('user_id')
                                                        ];
                                             $this->set_notification($message);
                                    }
                                     $logme = [
                                        'wallet_id' => $logelectric->wallet_id,
                                        'member_to' =>  $this->session->userdata('user_id'),
                                        'member_from' =>  1,
                                        'amount' =>  $data['amount'],
                                                                  //   'surcharge' => $data['surcharge'],
                                        'refrence' =>  $this->data['submitTransection']['referenceid'],
                                        'transection_id'=>$this->data['saveTransection']['transection_id'],
                                        // 'commission' =>  $amountc,
                                        'service_id' => $data['service'],
                                        'stock_type' => $this->data['saveTransection']['api_requist'],
                                        'status' => 'success',
                                        'balance' =>  $userWallet,
                                        'closebalance' => $updateBalance,
                                        'type' => 'debit',
                                         'mode' => 'Bill Pay',
                                        'bank' =>  'Bill Pay',
                                        'narration' => 'Bill',
                                        'date' => date("Y-m-d h:i:sa"),
                                    ];
                                  $this->common_model->insert($logme, 'wallet_transaction');
                        $this->set_notification($message);
                            }
                            
                            $wallet_sid = self::commition_distribute_biil($mode,$this->session->userdata('user_id'),$data['service'], $data['amount'],$data['operator']);
                                         
                                $this->session->set_flashdata(
                                                                array(
                                                                        'status' => 0,
                                                                        'msg' => $result->message,
                                                                    )
                                                            );
                                redirect($this->agent->referrer(), 'refresh');
                                        
                        }elseif($result->status==true && $result->response_code==0){//pending
                        
                            $this->common_model->update(array('transection_msg' => $result->message),'primary_id', $transition_id, 'submit_transection');
                            
                            if ($userWallet != 'none') {
                                
                                    $updateBalance = $userWallet - $data['amount'];    //Deduct balance
                                    
                                        $updateWallet = [
                                                            'balance' => $updateBalance,
                                                        ];
                                                        
                                    if ($this->common_model->update($updateWallet, 'member_id', $this->session->userdata('user_id'), 'wallet')) { //update deducted balance
                                    
                                            $message = [
                                                          'msg' => 'Your wallet balance debited Rs. ' . $data['amount'] . ' available balance is ' . $updateBalance,
                                                          'user_id' => $this->session->userdata('user_id')
                                                        ];
                                                        
                                            $this->set_notification($message);
                                            $logme = [
                                              'wallet_id' => $logelectric->wallet_id,
                                              'member_to' =>  $this->session->userdata('user_id'),
                                              'member_from' =>  1,
                                              'amount' =>  $data['amount'],
                                                                  //   'surcharge' => $data['surcharge'],
                                               'refrence' =>  $this->data['submitTransection']['referenceid'],
                                              'transection_id'=>$this->data['saveTransection']['transection_id'],
                                        // 'commission' =>  $amountc,
                                              'service_id' => $data['service'],
                                               'stock_type' => $this->data['saveTransection']['api_requist'],
                                              'status' => 'success',
                                              'balance' =>  $userWallet,
                                              'closebalance' => $updateBalance,
                                              'type' => 'debit',
                                               'mode' => 'Bill Pay',
                                               'bank' =>  'Bill Pay',
                                               'narration' => 'Bill',
                                               'date' => date("Y-m-d h:i:sa"),
                                    ];
                                             $this->common_model->insert($logme, 'wallet_transaction');
                                    }
                            }
                            
                            $status_pendding = array(
                                
                                                     'member_id' => $this->session->userdata('member_id'),
                                                     'ref_id' => $this->data['submitTransection']['referenceid'],
                                                     'service_id' => $data['service'],
                                                     'api_type' => $data['type'],
                                                     'mode'=>$mode,
                                                     'amount' => $data['amount'],
                                                     'cnumber' => $data['account'],
                                                     'submit_trantision_id' =>$transition_id
                                
                                                    );
                                                    
                                $this->common_model->insert($status_pendding, 'status_api');
                                $this->session->set_flashdata(
                                                                array(
                                                                        'status' => 0,
                                                                        'msg' => $result->message,
                                                                    )
                                                            );
                                redirect($this->agent->referrer(), 'refresh');
                        
                        }else{
                                
                                $this->session->set_flashdata(
                                                                array(
                                                                        'status' => 0,
                                                                        'msg' => $result->message,
                                                                    )
                                                            );
                                                            
                                redirect($this->agent->referrer(), 'refresh');
                        }
                
                    }
                }
            }else{
                  $this->session->set_flashdata(
                           array(
                             'status' => 0,
                             'msg' => "Insufficient wallet Balance"
                              )
                            );
                        redirect($this->agent->referrer(), 'refresh');
            }
          }else{
                $this->session->set_flashdata(
                                              array(
                                                'status' => 0,
                                                'msg' => "Invalid Request"
                                              )
                                            );
                redirect($this->agent->referrer(), 'refresh');
       }
        }
    }
  
  
    public function transection_service2()
    {
    $this->client = new Client();

    //   print_r($this->data['submitTransection']);
    //   exit();
    #guzzle
    try {
      $response = $this->client->request('POST', "https://vitefintech.com/viteapi/home/billpay/",[

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

  public function get_Bill_history()
  {
    $uri = $this->security->xss_clean($_GET);
    // pre($uri);exit;

    if (isset($uri['key']) && !empty($uri['key'])) {

      $query = '';


      $output = array();


      $duid = $uri['key'];


      $list = $uri['list'];



      $data = array();





      if (isAdmin($this->session->userdata('user_roles'))) {

        $query .= "SELECT * FROM submit_transection WHERE service_id = {$uri['service_id']} and api_requist='{$uri['type']}'";

        $recordsFiltered = $this->users_model->row_count($query);
      } else {

        $query .= "SELECT * FROM submit_transection WHERE service_id = {$uri['service_id']} and api_requist='{$uri['type']}' AND member_id = '{$duid}' ";


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
      if (isset($_GET["searchByCat"])) {
        $query .= " AND " . $_GET["searchByCat"] . " = '" . $_GET["searchValue"] . "'  ";
      }
      if (isset($_GET["date_from"])) {
        $query .= " AND created >= '" . $_GET["date_from"] . "'  ";
      }
      if (isset($_GET["date_to"])) {
        $query .= " AND created <= '" . $_GET["date_to"] . "'  ";
      }
      if (!empty($_GET["order"])) {
        $query .= 'ORDER BY ' . $_GET['order']['0']['column'] . ' ' . $_GET['order']['0']['dir'] . ' ';
      } else {
        $query .= 'ORDER BY created DESC ';
      }
      if ($_GET["length"] != -1) {
        $query .= 'LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];
      }

      $sql = $this->db->query($query);
      // pre($sql);exit;
      $result = $sql->result_array();



      foreach ($result as $row) {



        $sub_array = array();

        $sub_array[] = "<button onclick='Print(" . $row['primary_id'] . ")'><i class='fa fa-print'></i></button>";
         $sub_array[] = "<button id='but_". $row['primary_id'] ."' onclick='ref(" . $row['primary_id'] . ")'><i class='fa-solid fa-arrow-rotate-right' id='ref_". $row['primary_id'] ."'></i></button>";
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

      $output["recordsFiltered"] = $recordsFiltered;

      $output["recordsTotal"] = $recordsFiltered;

      $output["data"] = $data;



      echo json_encode($output);
    }
  }
   //new
    public function electricity_submit(){
        $this->load->library('user_agent');
        if ($_POST) {
          $data = $this->security->xss_clean($_POST);
          if ($data) {
            if ($this->session->userdata('latitude')) {
              $location = $this->session->userdata('latitude') . '|' . $this->session->userdata('longitude');
            }
            if ($this->data['bal'] > 0 && $this->data['bal'] > $data['amount']) {
                
                    $transection_id = self::utan($data['account']);
                    $this->data['submitTransection'] = [
                                                    'operator' => $data['operator'],
                                                    'canumber' => $data['account'],
                                                    'amount' => $data['amount'],
                                                    'referenceid' => "Bill_".self::stan(),
                                                    'latitude' => $this->session->userdata('latitude'),
                                                    'longitude' => $this->session->userdata('longitude'),
                                                    'billdate' => date("d/M/Y"),
                                                    'duedate' => $data['duedate'],
                                                    'name' => $data['username'],
                                                    'location' => $location,
                                                    'transection_id' => $transection_id,
                                                    'type' => $data['type'],
                                                    'api_key'=>'POCA001',
                                                    'fetchbill_id'=>$data['fetchbill'],
                                                    'submerchantid'=>$this->session->userdata('member_id')
                                                ];
              
                    $this->data['saveTransection'] = [
                                                    'transection_id' => $transection_id,
                                                    'transection_type' => 'bill_payment',
                                                    'member_id' => $this->session->userdata('member_id'),
                                                    'transection_amount' => $data['amount'],
                                                    'service_id' => $data['service'],
                                                    'reference_number' => $this->data['submitTransection']['referenceid'],
                                                    'transection_mobile' => $data['account'],
                                                    'api_requist' => $data['type'],
                                                    'location' => $location,
                                                    'created'=> date("Y-m-d h:i:sa")
                                                ];
                    $transition_id=$this->common_model->insert($this->data['saveTransection'], 'submit_transection');
                    $response = self::electricity_service2();
                    $action=['transection_response' => $response];
                    $this->common_model->update($action, 'primary_id', $transition_id, 'submit_transection');
                if(isJson($response)) {
                        $result = json_decode($response); 
                        $action = [
                                    'transection_status' =>  $result->status,
                                    'transection_msg' => $result->msg,
                                    'transection_respcode' => $result->errorcode,
                                    ];
                        $this->common_model->update($action, 'primary_id', $transition_id, 'submit_transection');

                    if(isset($result->status) && isset($result->statusCode)){
                    
                        if($result->status==2 && $result->statusCode==2){
                                $this->session->set_flashdata(
                                                                array(
                                                                  'status' => 2,
                                                                  'msg' => $result->msg,
                                                                )
                                                            );
                                redirect($this->agent->referrer(), 'refresh');
                }
                    }
                    
                    if(isset($result->status)){
                        
                        $userWallet = $this->common_model->wallet_balance($this->session->userdata('user_id'));//wallet
                        
                        if($result->status==2 && $result->msg=='SUCCESS'){ //succeess
                            if ($userWallet != 'none'){
                                
                                    $updateBalance = $userWallet - $data['amount'];    //Deduct balance
                                    $updateWallet = [
                                                    'balance' => $updateBalance,
                                                    ];
                                                    
                                    if ($this->common_model->update($updateWallet, 'member_id', $this->session->userdata('user_id'), 'wallet')) { //update deducted balance
                                            $message = [
                                                           'msg' => 'Your wallet balance debited Rs. ' . $data['amount'] . ' available balance is ' . $updateBalance,
                                                           'user_id' => $this->session->userdata('user_id')
                                                        ];
                                             $this->set_notification($message);
                                    }
                            }
                            
                            $wallet_sid = self::commition_distribute_biil($this->session->userdata('user_id'), $data['service'], $data['amount'],$data['operator']);
                                         
                                $this->session->set_flashdata(
                                                                array(
                                                                        'status' => 0,
                                                                        'msg' => $result->msg,
                                                                    )
                                                            );
                                redirect($this->agent->referrer(), 'refresh');
                                        
                        }elseif($result->status==1){//pending
                        
                            $this->common_model->update(array('transection_msg' => $result->msg),'primary_id', $transition_id, 'submit_transection');
                            
                            if ($userWallet != 'none') {
                                
                                    $updateBalance = $userWallet - $data['amount'];    //Deduct balance
                                    
                                        $updateWallet = [
                                                            'balance' => $updateBalance,
                                                        ];
                                                        
                                    if ($this->common_model->update($updateWallet, 'member_id', $this->session->userdata('user_id'), 'wallet')) { //update deducted balance
                                    
                                            $message = [
                                                          'msg' => 'Your wallet balance debited Rs. ' . $data['amount'] . ' available balance is ' . $updateBalance,
                                                          'user_id' => $this->session->userdata('user_id')
                                                        ];
                                                        
                                            $this->set_notification($message);
                                    }
                            }
                            
                            $status_pendding = array(
                                
                                                     'member_id' => $this->session->userdata('member_id'),
                                                     'ref_id' => $this->data['submitTransection']['referenceid'],
                                                     'service_id' => $data['service'],
                                                     'api_type' => $data['type'],
                                                     'amount' => $data['amount'],
                                                     'cnumber' => $data['account'],
                                                     'submit_trantision_id' =>$transition_id
                                
                                                    );
                                                    
                                $this->common_model->insert($status_pendding, 'status_api');
                                $this->session->set_flashdata(
                                                                array(
                                                                        'status' => 0,
                                                                        'msg' => $result->msg,
                                                                    )
                                                            );
                                redirect($this->agent->referrer(), 'refresh');
                        
                        }else{
                                
                                $this->session->set_flashdata(
                                                                array(
                                                                        'status' => 0,
                                                                        'msg' => $result->msg,
                                                                    )
                                                            );
                                                            
                                redirect($this->agent->referrer(), 'refresh');
                        }
                
                    }
                }
            }else{
                  $this->session->set_flashdata(
                           array(
                             'status' => 0,
                             'msg' => "Insufficient wallet Balance"
                              )
                            );
                        redirect($this->agent->referrer(), 'refresh');
            }
          }else{
                $this->session->set_flashdata(
                                              array(
                                                'status' => 0,
                                                'msg' => "Invalid Request"
                                              )
                                            );
                redirect($this->agent->referrer(), 'refresh');
       }
        }
    }
    //mitra
    public function electricity_service2()
    {
    $this->client = new Client();

    //   print_r($this->data['submitTransection']);
    //   exit();
    #guzzle
    try {
      $response = $this->client->request('POST', "https://vitefintech.com/viteapi/home/electricitypay/",[

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

  public function get_history()
  {
    $uri = $this->security->xss_clean($_GET);
    // pre($uri);exit;

    if (isset($uri['key']) && !empty($uri['key'])) {

      $query = '';


      $output = array();


      $duid = $uri['key'];


      $list = $uri['list'];


      $data = array();





      if (isAdmin($this->session->userdata('user_roles'))) {

        $query .= "SELECT * FROM submit_transection WHERE service_id ='{$uri['serviceid']}' and api_requist='{$uri['type']}'";

        $recordsFiltered = $this->users_model->row_count($query);
      } else {

        $query .= "SELECT * FROM submit_transection WHERE service_id ='{$uri['serviceid']}' and api_requist='{$uri['type']}' AND member_id = '{$duid}' ";


        $recordsFiltered = $this->users_model->row_count($query);
      }
      if(!empty($uri['member']))
        {
           $query .=" AND member_id='".$uri['member']."' ";
        }
         if(!empty($uri['from']) && !empty($uri['to']))
        {
           $query.=" AND (`created` BETWEEN '".$uri['from']."' AND '".$uri['to']."') "; 
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
      $query.=" ORDER BY `submit_transection`.`primary_id` DESC ";
      if ($_GET["length"] != -1) {
        $query .= ' LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];
      }
      
      

      $sql = $this->db->query($query);
     
    //   pre($sql);exit;
      $result = $sql->result_array();
      foreach ($result as $row) {



        $sub_array = array();
        $sub_array[] = "<button onclick='Print(" . $row['primary_id'] . ")'><i class='fa fa-print'></i></button>";
         $sub_array[] = "<button id='but_". $row['primary_id'] ."' onclick='ref(" . $row['primary_id'] . ")'><i class='fa-solid fa-arrow-rotate-right' id='ref_". $row['primary_id'] ."'></i></button>";
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

      $output["recordsFiltered"] = $recordsFiltered;

      $output["recordsTotal"] = $recordsFiltered;

      $output["data"] = $data;



      echo json_encode($output);
    }
  }

  public function transection_service($amemberid, $rmemberid)
  {
    $this->client = new Client();
    #guzzle
    try {
      $response = $this->client->request('POST', "https://vitefintech.com/viteapi/home/dorecharge2/" . $amemberid . "/" . $rmemberid, [

        'form_params' => $this->data['submitTransection'],
      ]);


      return  $response->getBody()->getContents();
    } catch (GuzzleHttp\Exception\BadResponseException $e) {
      #guzzle repose for future use
      $response = $e->getResponse();
      $responseBodyAsString = $response->getBody()->getContents();
      print_r($responseBodyAsString);
    }
  }
  public function transection_service_status($refid)
  {
    $this->client = new Client();
    #guzzle
    try {
      $response = $this->client->request('POST', "https://vitefintech.com/viteapi/home/status2/" .$refid, [

        // 'form_params' => $this->data['submitTransection'],
      ]);


      return $response->getBody()->getContents();
    } catch (GuzzleHttp\Exception\BadResponseException $e) {
      #guzzle repose for future use
      $response = $e->getResponse();
      $responseBodyAsString = $response->getBody()->getContents();
      print_r($responseBodyAsString);
    }
  }

  public function fetch_bill()
  {
    $this->client = new Client();
    if ($_POST) {
      try {
        $data = $this->security->xss_clean($_POST);
        $response = $this->client->request('POST', "https://vitefintech.com/viteapi/home/billfetch", [
          'form_params' => array(
            'operator' => $data['operator'],
            'canumber' => $data['account'],
            'mode'=>$data['mode'],
            'api_key'=>'POCA001',
          ),
        ]);
        $result['data'] = $response->getBody()->getContents();

        echo  $result['data'];
      } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
      }
    }
  }
  public function dth()
  {
       $this->data['param'] = $this->paremlink('/');
    $this->data['member_list']=self::memberselect();
    $this->data['main_content'] = $this->load->view('dth', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function fastag()
  {
    $this->data['main_content'] = $this->load->view('fastag', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function datacard()
  {
       $this->data['param'] = $this->paremlink('/');
    $this->data['member_list']=self::memberselect();
    $this->data['main_content'] = $this->load->view('datacard', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function landline()
  {
       $this->data['param'] = $this->paremlink('/');
    $this->data['member_list']=self::memberselect();
    $this->data['main_content'] = $this->load->view('landline', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }

  public function electricity()
  {
       $this->data['param'] = $this->paremlink('/');
    $this->data['member_list']=self::memberselect();
    $this->data['main_content'] = $this->load->view('electricity', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function gas()
  {
       $this->data['param'] = $this->paremlink('/');
    $this->data['member_list']=self::memberselect();
    $this->data['main_content'] = $this->load->view('gas', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function hospital()
  {
       $this->data['param'] = $this->paremlink('/');
    $this->data['member_list']=self::memberselect();
    $this->data['main_content'] = $this->load->view('hospital', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function water()
  {
       $this->data['param'] = $this->paremlink('/');
    $this->data['member_list']=self::memberselect();
    $this->data['main_content'] = $this->load->view('water', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function insurance()
  {
       $this->data['param'] = $this->paremlink('/');
    $this->data['member_list']=self::memberselect();
    $this->data['main_content'] = $this->load->view('insurance', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }

  public function loan()
  {
       $this->data['param'] = $this->paremlink('/');
    $this->data['member_list']=self::memberselect();
    $this->data['main_content'] = $this->load->view('loan', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function emi()
  {
     $this->data['param'] = $this->paremlink('/');
    $this->data['member_list']=self::memberselect();
    $this->data['main_content'] = $this->load->view('emi', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function broadband()
  {
       $this->data['param'] = $this->paremlink('/');
    $this->data['member_list']=self::memberselect();
    $this->data['main_content'] = $this->load->view('broadband', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function cable()
  {
       $this->data['param'] = $this->paremlink('/');
    $this->data['member_list']=self::memberselect();
    $this->data['main_content'] = $this->load->view('cable', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function challan()
  {
       $this->data['param'] = $this->paremlink('/');
    $this->data['member_list']=self::memberselect();
    $this->data['main_content'] = $this->load->view('challan', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function municipaltax()
  {
       $this->data['param'] = $this->paremlink('/');
    $this->data['member_list']=self::memberselect();
    $this->data['main_content'] = $this->load->view('municipaltax', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function municipality()
  {
       $this->data['param'] = $this->paremlink('/');
    $this->data['member_list']=self::memberselect();
    $this->data['main_content'] = $this->load->view('municipality', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }

  public function get_history_all()
  {
    $uri = $this->security->xss_clean($_GET);
    // pre($uri);exit;

    if (isset($uri['key']) && !empty($uri['key'])) {

      $query = '';


      $output = array();


      $duid = $uri['key'];


      $list = $uri['list'];


      $data = array();





      if (isAdmin($this->session->userdata('user_roles'))) {

        $query .= "SELECT * FROM submit_transection WHERE service_id = 18 OR service_id = 22 ";

        $recordsFiltered = $this->users_model->row_count($query);
      } else {

        $query .= "SELECT * FROM submit_transection WHERE service_id = 18 OR service_id = 22 AND member_id = '{$duid}' ";


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
      if (isset($_GET["searchByCat"])) {
        $query .= " AND " . $_GET["searchByCat"] . " = '" . $_GET["searchValue"] . "'  ";
      }
      if (isset($_GET["date_from"])) {
        $query .= " AND created >= '" . $_GET["date_from"] . "'  ";
      }
      if (isset($_GET["date_to"])) {
        $query .= " AND created <= '" . $_GET["date_to"] . "'  ";
      }
      if (!empty($_GET["order"])) {
        $query .= 'ORDER BY ' . $_GET['order']['0']['column'] . ' ' . $_GET['order']['0']['dir'] . ' ';
      } else {
        $query .= 'ORDER BY created DESC ';
      }
      if ($_GET["length"] != -1) {
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

      $output["recordsFiltered"] = $recordsFiltered;

      $output["recordsTotal"] = $recordsFiltered;

      $output["data"] = $data;



      echo json_encode($output);
    }
  }
  public function refresh() { 
     $uri = $this->security->xss_clean($_GET); 
     $flag=0;
     $transition=$this->common_model->select_option($uri['id'],'primary_id','submit_transection'); 
     $status=$this->common_model->select_option($transition[0]['reference_number'],'ref_id','status_api');
     if(isset($transition) && !empty($transition) && isset($status) && !empty($status))
     {
      $response1=self::transection_service_status($transition[0]['reference_number']);
    //   print_r($response1);
    //   exit();
      if(!isJson($response1))
      {    $status=$this->common_model->select_option($transition[0]['reference_number'],'ref_id','status_api');
            $xml = simplexml_load_string($response1);
            $json = json_encode($xml);
            $result=json_decode($json);
            if($result->STATUS==2 && $result->MSG=='SUCCESS')
            {
                        $transition=$this->common_model->select_option($transition[0]['reference_number'],'reference_number','submit_transection');
                        
                        if(count($status)>0)
                        {
                            if($result->ACCOUNT==(int)$transition[0]['transection_mobile'] && $result->AMOUNT==(float)$transition[0]['transection_amount'])
                            {
                                 if($this->db->where('id',$status[0]['id'])->delete('status_api'))
                                 {
                                     $this->common_model->update(array('transection_msg'=>"SUCCESS",'transection_response'=>$response),'primary_id',$transition[0]['primary_id'],'submit_transection');
                                 }
                            }
                        }
                        echo true;
            }
            else if($result->STATUS==3 && $result->MSG=='FAILED')
            {
                $transition=$this->common_model->select_option($transition[0]['reference_number'],'reference_number','submit_transection');
                if($result->ACCOUNT==(int)$transition[0]['transection_mobile'] && $result->AMOUNT==(float)$transition[0]['transection_amount'])
                {
                  $leger=$this->common_model->select_option($transition[0]['transection_id'],'transection_id','wallet_transaction');
                if($this->db->where('ref_id',$transition[0]['reference_number'])->delete('status_api')){
                foreach($leger as $value)
                {
                  $user=$this->common_model->select_user_option($value['member_to']);
                  $wallet_balance=$this->common_model->get_user_wallet_balance($value['member_to']);  
                  if($status[0]['member_id']==$user[0]['member_id'])
                  {
                     if($flag==0 && $value['type']!='credit')
                     {
                     $updateBalance = $wallet_balance->balance+$transition[0]['transection_amount']-$value['commission'];    //Deduct balance
                     $flag++;
                     }
                     else
                     {
                         $updateBalance = $wallet_balance->balance-$value['commission'];
                     }
                  }
                  else
                  {
                      $updateBalance = $wallet_balance->balance-$leger[0]['commission'];    //Deduct balance
                      $value['type']='debit';
                  }
                  $updateWallet = [
                         'balance' => $updateBalance,
                      ]; 
                if($this->common_model->update($updateWallet,'member_id',$value['member_to'], 'wallet'))
                {
                  $logme = [
                        'wallet_id' => $value['wallet_id'],
                        'member_to' =>  $value['member_to'],
                        'member_from' =>  $value['member_from'],
                        'amount' =>  $value['amount'],
                          //   'surcharge' => $data['surcharge'],
                        'refrence' =>  'Refund'.$value['refrence'],
                        'transection_id'=>$value['transection_id'],
                        'commission' =>  $value['commission'],
                        'service_id' => $value['service_id'],
                        'stock_type' => $value['stock_type'],
                        'status' => 'failed',
                        'balance' => $wallet_balance->balance,
                        'closebalance' => $updateBalance,
                        'type' => $value['type'],
                        'mode' => $value['mode'],
                        'bank' => $value['bank'],
                        'narration' => 'Refund Transition failure',
                        'date' => date('Y-m-d'),
                      ];
                  $this->common_model->insert($logme, 'wallet_transaction');   
                }
              }
                $data=[
                      'transection_msg'=>'failure',
                      'transection_response'=>$response1
                      ];
                $this->common_model->update($data,'primary_id',$uri['id'],'submit_transection');
                
              } 
                }
                echo true;
            }
      }
      else
      {
         if($response->response_code==1 && $response->status==1 && $response->data->status==0)
        {  
          if($response->data->canumber==(int)$transition[0]['transection_mobile'] && $response->data->amount==(float)$transition[0]['transection_amount'])
          {   
              $leger=$this->common_model->select_option($transition[0]['transection_id'],'transection_id','wallet_transaction');
              if($this->db->where('ref_id',$transition[0]['reference_number'])->delete('status_api')){
                foreach($leger as $value)
                {
                  $user=$this->common_model->select_user_option($value['member_to']);
                  $wallet_balance=$this->common_model->get_user_wallet_balance($value['member_to']);  
                  if($status[0]['member_id']==$user[0]['member_id'])
                  {
                     if($flag==0 && $value['type']!='credit')
                     {
                     $updateBalance = $wallet_balance->balance+$transition[0]['transection_amount']-$value['commission'];    //Deduct balance
                     $flag++;
                     }
                     else
                     {
                         $updateBalance = $wallet_balance->balance-$value['commission'];
                     }
                  }
                  else
                  {
                      $updateBalance = $wallet_balance->balance-$value['commission'];    //Deduct balance
                      $value['type']='debit';
                  }
                  $updateWallet = [
                         'balance' => $updateBalance,
                      ]; 
                if($this->common_model->update($updateWallet,'member_id',$value['member_to'], 'wallet'))
                {
                  $logme = [
                        'wallet_id' => $value['wallet_id'],
                        'member_to' =>  $value['member_to'],
                        'member_from' =>  $value['member_from'],
                        'amount' =>  $value['amount'],
                          //   'surcharge' => $data['surcharge'],
                        'refrence' =>  'Refund'.$value['refrence'],
                        'transection_id'=>$value['transection_id'],
                        'commission' =>  $value['commission'],
                        'service_id' => $value['service_id'],
                        'stock_type' => $value['stock_type'],
                        'status' => 'failed',
                        'balance' => $wallet_balance->balance,
                        'closebalance' => $updateBalance,
                        'type' => $value['type'],
                        'mode' => $value['mode'],
                        'bank' => $value['bank'],
                        'narration' => 'Refund Transition failure',
                        'date' => date('Y-m-d'),
                      ];
                  $this->common_model->insert($logme, 'wallet_transaction');   
                }
              }
                $data=[
                      'transection_msg'=>'failure',
                      'transection_response'=>$response1
                      ];
                $this->common_model->update($data,'primary_id',$uri['id'],'submit_transection');
                echo true;
              }
          }
      }
      else
      {
          echo false;
      }
      }
     }
     else
     {
         echo false;
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
  		 
    // pre($uri);exit;

    if (isset($uri['key']) && !empty($uri['key'])) {

      $query = '';


      $output = array();


      $duid = $uri['key'];


      $list = $uri['list'];


      $data = array();





      if (isAdmin($this->session->userdata('user_roles'))) {

        $query .= "SELECT * FROM submit_transection WHERE service_id ='{$uri['serviceid']}' and api_requist='{$uri['type']}'";

        $recordsFiltered = $this->users_model->row_count($query);
      } else {

        $query .= "SELECT * FROM submit_transection WHERE service_id ='{$uri['serviceid']}' and api_requist='{$uri['type']}' AND member_id = '{$duid}' ";


        $recordsFiltered = $this->users_model->row_count($query);
      }
      if(!empty($uri['member']))
        {
           $query .=" AND member_id='".$uri['member']."' ";
        }
         if(!empty($uri['from']) && !empty($uri['to']))
        {
           $query.=" AND (`created` BETWEEN '".$uri['from']."' AND '".$uri['to']."') "; 
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
     

      $sql = $this->db->query($query);
      // pre($sql);exit;
      $result = $sql->result_array();
  		
  			$spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'S.No');
            $sheet->setCellValue('B1', 'MEMBER ID');
            $sheet->setCellValue('C1', 'Transition ID');
            $sheet->setCellValue('D1', 'Transition Msg');
            $sheet->setCellValue('E1', 'Transition Mobile');
            $sheet->setCellValue('F1', 'Transition Amount');
            $sheet->setCellValue('G1', 'Status');
            $sheet->setCellValue('H1','Created');
             $sheet->setCellValue('I1','Refrence Id');
            $rows = 2;
            $i=1;
            foreach ($result as $val){
               $sheet->setCellValue('A'. $rows, $i);
               $sheet->setCellValue('B'. $rows,$val['member_id']);
               $sheet->setCellValue('C' . $rows, $val['transection_id']);
               $sheet->setCellValue('D' . $rows,$val['transection_msg']);
               $sheet->setCellValue('E' . $rows,$val['transection_mobile']);
               $sheet->setCellValue('F' . $rows,$val['transection_amount']);
               $sheet->setCellValue('G' . $rows, $val['transection_status']);
                $sheet->setCellValue('H' . $rows, $val['created']);
                $sheet->setCellValue('I' . $rows, $val['reference_number']);
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
 public function refresh_bill()
  {
    //   print_r("hello");
     $uri = $this->security->xss_clean($_GET);
     $transition=$this->common_model->select_option($uri['id'],'primary_id','submit_transection'); 
     $user=$this->common_model->select_option($transition[0]['member_id'],'member_id','user');
     $status=$this->common_model->select_option($transition[0]['reference_number'],'ref_id','status_api');
     if(isset($transition) && !empty($transition) && isset($status) && !empty($status))
     {
       if(count($status)>0)
       {
       $response=self::transection_service_status_bill($transition[0]['reference_number']);
       $response1=json_decode($response);
    //   print_r($response);
       if($transition[0]['transection_amount']==$response1->data->amount && $transition[0]['transection_mobile']==$response1->data->canumber)
       {
        if($response1->response_code==1 && $response1->status==1 && $response1->data->status==1)//success
        {
                if($this->db->where('id',$status[0]['id'])->delete('status_api'))
                {
                  $this->common_model->update(array('transection_msg'=>$response1->data->operatorname.' Amount '.$response1->data->amount.' is successfull','transection_respcode'=>$response1->response_code,'transection_response'=>$response), 'primary_id',$transition[0]['primary_id'],'submit_transection');
                  self::commition_distribute_biil_status($status[0]['mode'],$user[0]['user_id'],$transition[0]['service_id'],$response1->data->amount,$response1->data->operatorname,$transition[0]['transection_id'],$transition[0]['api_requist']);   
                  
                }
        }  
        elseif($response1->response_code==5 && $response1->status==1 && $response1->data->status==0)//failure
        {   
            if($response1->data->refunded==1)
            {
               
            if($this->db->where('id',$status[0]['id'])->delete('status_api'))
                {
                  $wallet_balance=$this->common_model->get_user_wallet_balance($user[0]['user_id']);
                  $update_balance=$wallet_balance->balance+$response1->data->amount;
                   $updateWallet = [
                         'balance' => $update_balance,
                       ]; 
                  if($this->common_model->update($updateWallet,'member_id',$user[0]['user_id'], 'wallet'))
                  {
                    $logme = [
                        'wallet_id' => $wallet_balance->wallet_id,
                        'member_to' =>  $user[0]['user_id'],
                        'member_from' =>  '',
                        'amount' =>$response1->data->amount,
                          //   'surcharge' => $data['surcharge'],
                        'refrence' =>  'Refund'.$transition[0]['reference_number'],
                        'transection_id'=>$transition[0]['transection_id'],
                        // 'commission' =>  $leger[0]['commission'],
                        'service_id' => $transition[0]['service_id'],
                        'stock_type' => $transition[0]['api_requist'],
                        'status' => 'reject',
                        'balance' => $wallet_balance->balance,
                        'closebalance' => $update_balance,
                        'type' => 'credit',
                        'mode' => $transition[0]['api_requist'],
                        'bank' => $transition[0]['api_requist'],
                        'narration' => 'Refund Transition failure',
                        'date' => date('Y-m-d'),
                      ];
                    $this->common_model->insert($logme, 'wallet_transaction');   
                    $this->common_model->update(array('transection_msg'=>'Failure','transection_respcode'=>$response1->response_code,'transection_response'=>$response), 'primary_id',$transition[0]['primary_id'],'submit_transection');
                  }
                 
                } 
            }
        }
       }
     }
    }
     echo true;
 }
  public function transection_service_status_bill($refid)
  {
    $this->client = new Client();
    #guzzle
    try {
      $response = $this->client->request('POST', "https://vitefintech.com/viteapi/Home/billstatus/".$refid, [

        // 'form_params' => $this->data['submitTransection'],
      ]);


      return $response->getBody()->getContents();
    } catch (GuzzleHttp\Exception\BadResponseException $e) {
      #guzzle repose for future use
      $response = $e->getResponse();
      $responseBodyAsString = $response->getBody()->getContents();
      print_r($responseBodyAsString);
    }
  }
    //bill comission
  public function commition_distribute_biil_status($mode,$id, $service, $transection, $operaor,$transection_id,$api_requist)
  {
    $this->db->select('operator_id')
                             ->from('billpayment')
                             ->where('operator_name',$operaor);
       $query = $this->db->get();
       $operator1=$query->result_array();
       $operaor=$operator1[0]['operator_id'];
    $parentsList = self::checkparent($id);
    $i = 0;
    foreach ($parentsList as $key => $value) {
      $commision = $this->commission_model->get_commision_by_role_bill($mode,$value['role_id'], $service, $transection, $operaor);
     
      if (!empty($commision)) {
        $userWallet = $this->common_model->get_user_wallet_balance($value['user_id']);

        if ($userWallet != 'none') {

          if ($commision[$i]['c_flat'] == 1) {

            $amountc = $commision[$i]['g_commission'];
     

            $updateBalance = $userWallet->balance + $amountc;    // add commission
            $updateWallet = [
              'balance' => $updateBalance,
            ];
          } else {

            $amountc = $transection *  $commision[$i]['g_commission'] / 100;
          
            $updateBalance = $userWallet->balance + $amountc;    // add commission
            $updateWallet = [
              'balance' => $updateBalance,
            ];
          }
          if ($this->common_model->update($updateWallet, 'member_id', $value['user_id'], 'wallet')) {
            $message = [
              'msg' => 'Your wallet balance credited ' . $amountc . ' available balance is ' . $updateBalance,
              'user_id' => $value['user_id']
            ];
            $logme = [
              'wallet_id' => $userWallet->wallet_id,
              'member_to' =>  $value['user_id'],
              'member_from' =>  $value['parent'],
              'amount' =>  $transection,
              //   'surcharge' => $data['surcharge'],
              'refrence' => 'Refund'.self::stan2(),
              'transection_id'=> $transection_id,
              'commission' =>  $amountc,
              'service_id' => $service,
              'stock_type' =>$api_requist,
              'status' => 'success',
              'balance' =>  $userWallet->balance,
              'closebalance' => $updateBalance,
              'type' => 'credit',
              'mode' => 'Bill Pay',
              'bank' =>  'Bill Pay',
              'narration' => 'Bill Pay Failure',
              'date' => date('Y-m-d'),
            ];
            $this->common_model->insert($logme, 'wallet_transaction');
          }
        } else {
          $message = [
            'msg' => 'User Wallet not Found',
            'user_id' => $value['user_id']
          ];
          
        }
      } else {
        $message = [
          'msg' => 'Commission Not Found',
          'user_id' => $value['user_id']
        ];
       
      }
    }
  }
}
