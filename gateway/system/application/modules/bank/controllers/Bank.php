<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
  }

  public function index() {
      $this->data['bank'] = $this->users_model->get_bank($this->session->userdata('user_id'));
    $this->data['main_content'] = $this->load->view('index', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
 public function add()

  {

  
    $user_id=$this->session->userdata('user_id');

    $data = $this->security->xss_clean($_POST);

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

                redirect($_SERVER['HTTP_REFERER']);
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

}