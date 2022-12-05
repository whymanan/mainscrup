<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends Vite {


  public $data = array();

  public function __construct() {
      parent::__construct();
    $this->load->model('common_model');
    $this->load->model('menu_model');
    $this->load->model('setting_model');
      $this->data['active'] = 'Settings';
      $this->data['breadcrumbs'] = [array('url' => base_url('setting'), 'name' => 'Settings')];
      $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));
  }

  public function index() {
    
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
  public function submit()
  {

    $data = $this->security->xss_clean($_POST);
    if ($this->common_model->exists('user', ['phone' => $data['phone_no']])) { //$data['phone_no']
      exit('User already Exist');
    }
    if ($data) {
     
      $uid = getCustomId($this->common_model->get_last_id('user'), $data["phone_no"]);
      $logme = [
        'customer_id' => $uid,
        
        'email' => $data["email"],
        'parent' => $data["vendor"],
        'phone' => $data["phone_no"],
       
        'role_id' => $data["user_role"],
        'password' => password_hash($data["phone_no"], PASSWORD_DEFAULT),
        'created_by' => $this->session->userdata('userID'),
        'user_status' => 'pending',
        'created_at' => current_datetime()
      ];
     $id= $this->common_model->insert($logme, 'user');
      if ($id) {
        $userdata = [
          'fk_user_id' => $id,
          'first_name' => $data["firstname"],
          'last_name' => $data["lastname"],
          'aadhar' => $data["adharcard"],
          'pan' => $data["pancard"],
          'organisation' => $data["organization_name"],
          'gstno' => $data["gst_no"],
          'address' => $data["address"],
          'state' => $data["states"],
          'city' => $data["city"],
          'pincode' => $data["pincode"],
          'created_at' => current_datetime(),
          'created_by' => $_SESSION['userID'],
        ];
        if ($this->common_model->insert($userdata, 'user_detail')) {
          $this->session->set_flashdata(array('error' => 0, 'msg' => 'Registered Successfully'));
        }else{
          $this->session->set_flashdata(array('error' => 1, 'msg' => 'Action Failed'));
        }
      } else {
        $this->session->set_flashdata(array('error' => 1, 'msg' => 'Action Failed'));
      }
      redirect('users', 'refresh');
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
          $query .= "SELECT user.*,u.customer_id as parent1,roles.role from user join user as u on u.user_id=user.parent 
          Join roles on roles.roles_id=user.role_id ";

          break;

        default:
          $query .= "SELECT user.*,u.customer_id as parent1 from user join user as u on u.user_id=user.parent Join roles on roles.roles_id=user.role_id ";

          break;
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
      $result = $sql->result_array();

      $i = 1;
      foreach ($result as $row) {
        $sub_array = array();
        $status = '';
        $kyc = '';
        if ($row['kyc_status'] == 'verify') {
          // code...
          $kyc = '<i class="fa fa-circle text-success font-12" data-toggle="tooltip" data-placement="top" title="" data-original-title="Active"></i>';
        } elseif ($row['kyc_status'] == 'pending') {
          $kyc = '<i class="fa fa-circle text-warning font-12" data-toggle="tooltip" data-placement="top" title="" data-original-title="Pending"></i>';

        }
        else {
          $kyc = '<i class="fa fa-circle text-success font-12" data-toggle="tooltip" data-placement="top" title="" data-original-title="Pending"></i>';
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
        $sub_array[] = $row['customer_id'];
        $sub_array[] = $row['parent1'];
        $sub_array[] = $row['email'];
        $sub_array[] = $row['phone'];
        $sub_array[] = $row['role'];
        $sub_array[] =  $kyc;
        $sub_array[] = $status;
        $sub_array[] = $row['created_at'];

       
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

  public function update()
  {
    if ($_POST) {
      $data = $this->security->xss_clean($_POST);

      $logme = [
       

        'email' => $data["email"],
        'parent' => $data["vendor"],
        'phone' => $data["phone_no"],

        'role_id' => $data["user_role"],
        'updated_by' => $this->session->userdata('userID'),
        'user_status' => 'pending',
        'updated_at' => current_datetime()
      ];

      $this->common_model->update($logme, 'id', $_POST['user_id'], 'user');

    

      $userdata = [
       
        'first_name' => $data["firstname"],
        'last_name' => $data["lastname"],
        'aadhar' => $data["adharcard"],
        'pan' => $data["pancard"],
        'organisation' => $data["organization_name"],
        'gstno' => $data["gst_no"],
        'address' => $data["address"],
        'state' => $data["states"],
        'city' => $data["city"],
        'pincode' => $data["pincode"],
        'updated_at' => current_datetime(),
        'updated_by' => $_SESSION['userID'],
      ];

      $this->common_model->update($userdata, 'id', $_POST['user_detail_id'], 'user_detail');
    }

    $this->data['main_content'] = $this->load->view('user/add', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }

}
