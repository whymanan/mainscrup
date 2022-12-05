<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Commission extends Vite
{


    public $data = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('commission_model');
        $this->load->model('menu_model');
        $this->load->model('users_model');
        $this->data['active'] = 'Commision';
        $this->data['breadcrumbs'] = [array('url' => base_url('commission'), 'name' => 'Commision')];
         $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));
    }

    public function index()
    {
        $this->data['param'] = $this->paremlink('/');
    $this->data['main_content'] = $this->load->view('index.php');
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
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
}
