<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Role extends Vite
{

  public $data = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->model('common_model');
    $this->load->model('role_model');
    $this->load->model('users_model');
    $this->data['active'] = 'setting';
    $this->data['breadcrumbs'] = [array('url' => base_url('role'), 'name' => 'Setting')];
     $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));
  }

  public function index()
  {
    $this->data['param'] = $this->paremlink('add');
    $this->data['main_content'] = $this->load->view('role/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('role/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }

  public function delete($id)
  {
    if ($this->db->where("roles_id", $id)->delete('roles')) {
      echo 1;
    } else {
      echo 0;
    }
  }

  public function submit()
  {

    $data = $this->security->xss_clean($_POST);

    if ($data) {

     $role['role'] =  $data['name'];
    //   $role['created_at'] = date('Y-m-d h:m:s');
      $cname=explode(" ",$data['name']);
      $sort_name="";
      forEach($cname as $value)
      {
          $sort_name.=$value[0];
      }
      $role['role_status'] = 'ACTIVE';
      $role['created_by'] = 1;
      $role['sort_name']=strtoupper($sort_name);
     $this->db->select_max('priority');
     $que= $this->db->get('roles');
     $role['priority']=$que->result_array()[0]['priority']+1;





      if ($this->common_model->insert($role, 'roles')) {


        $this->session->set_flashdata(
          array(
            'status' => 1,
            'msg' => "Role Added Successfully"
          )
        );
        redirect('role', 'refresh');
      }
    }
  }
  public function edit()
  {
    $Id = $this->security->xss_clean($_GET['q']);
    $this->data['role'] = $this->role_model->get_role_by_id($Id);
    $this->data['roles'] = $this->setting_model->get_menu();
    $this->data['main_content'] = $this->load->view('role/edit', $this->data, true);
    $this->data['is_script'] = $this->load->view('role/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function get_tabs()
  {
    $data1 = $this->security->xss_clean($_POST);
    $this->data['menu'] = $this->setting_model->get_sub_menu($data1['id']);
    $role = $this->setting_model->get_role_permission($data1['role']);
    if (!empty($role)) {
      foreach ($role as $row) {
        $this->data['role'][] = $row['fk_menu_id'];
      }
    } else {
      $this->data['role'][0] = "null";
    }

    //pre($this->data['role']);exit;
    $this->data['color'] = array("success", "danger", "primary", "warning", "dark", "info");
    $this->data['name'] = $data1['name'];
    $this->data['roles_id'] = $data1['id'];
    echo $this->load->view('role/tabs', $this->data, true);
  }
  public function update_role_permission()
  {
    if ($_POST) {
     // pre($_POST);exit;
      //$check=$this->role_model->get_sub_menu($_POST['role'], $_POST['id']);
      if ($_POST['type'] == "insert") {


        $data1['fk_role_id'] = $_POST['role'];
        $data1['fk_menu_id'] = $_POST['id'];
        $data1['created_at'] = date('Y-m-d h:m:s');
        $data1['created_by'] = 1;

        if ($this->common_model->insert($data1, 'role_permission')) {
          echo 1;
        } else {
          echo 0;
        }
      } else {
        $data1['fk_menu_id'] = $_POST['id'];
        if ($this->common_model->delete($data1, 'role_permission')) {
          echo 1;
        } else {
          echo 0;
        }
      }
    }
  }
  public function update_role_permission2()
  {
    if ($_POST) {
      pre($_POST);
      $stat1 = 0;
      $stat2 = 0;
      $check = $this->setting_model->get_sub_menu($_POST['id']);
       pre($check);
       
      if ($_POST['type'] == "insert") {

        if (!empty($check)) {
          $i = 0;
          $count = count($check);
          //pre(count($check));exit;
          foreach ($check as $row) {
            $data1['fk_role_id'] = $_POST['role'];
            $data1['fk_menu_id'] = $row['menu_permission_id'];
            $data1['created_at'] = date('Y-m-d h:m:s');
            $data1['created_by'] = $this->session->userdata('user_id');

            if ($this->common_model->insert($data1, 'role_permission')) {
              $i++;
            }
          }
           echo "I= ".$i;
           echo " count= " . $count;
          if ($i == $count) {
            $stat1 = 1;
          } else {
            $stat1 = 0;
          }
        }
        $data1['fk_role_id'] = $_POST['role'];
        $data1['fk_menu_id'] = $_POST['id'];
        $data1['created_at'] = date('Y-m-d h:m:s');
        $data1['created_by'] = $this->session->userdata('user_id');

        if ($this->common_model->insert($data1, 'role_permission')) {
          $stat2 = 1;
        } else {
          $stat2 = 0;
        }
      } else {
        if (!empty($check)) {
          $i = 0;
          $count = count($check);
          // pre(count($check));exit;
          foreach ($check as $row) {
           
            $data1['fk_menu_id'] = $row['menu_permission_id'];
            

            if ($this->common_model->delete($data1, 'role_permission')) {
              $i++;
            }
          }
           echo " I= " . $i;
           echo " count= " . $count;
          if ($i == $count) {
            $stat1 = 1;
          } else {
            $stat1 = 0;
          }
        }
        $data1['fk_menu_id'] = $_POST['id'];
        if ($this->common_model->delete($data1, 'role_permission')) {
          $stat2 = 1;
        } else {
          $stat2 = 0;
        }
      }
       echo "stat1 = " . $stat1;
       echo " stat2= " . $stat2;exit;
      if($stat2==$stat1){
        echo 1;
      }else{
        echo 0;
      }
    }
  }
  public function set_email($email)
  {
    return $this->common_model->insert($email, 'emails');
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
          $query .= "SELECT * FROM `roles`  ";

          break;

        default:
          $query .= "SELECT * FROM `roles` ";

          break;
      }


     if (!empty($_GET["search"]["value"])) {
        $query .= 'WHERE role LIKE "%' . $_GET["search"]["value"] . '%" ';
        // $query .= 'OR mp.data_sub_menu LIKE "%' . $_GET["search"]["value"] . '%" ';
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


        $sub_array[] = $row['role'];
        $sub_array[] = $row['role_status'];
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




  public function update()
  {
    if ($_POST) {
      //pre($_POST);exit;

      $data1['role'] = $_POST['name'];
      $role['updated_at'] = date('Y-m-d h:m:s');
      $role['updated_by'] = 1;

      if ($this->common_model->update($data1, 'roles_id', $_POST['roleid'], 'roles')) {
        echo 1;
      }
    }
  }
}
