<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends Vite {

  public $data = array();
	public function __construct() {
      parent::__construct();
    $this->load->model('common_model');
    $this->load->model('menu_model');
    $this->load->model('users_model');
    $this->load->model('commission_model');
    $this->data['active'] = 'menu';
    $this->data['breadcrumbs'] = [array('url' => base_url('menu'), 'name' => 'Menu')];

  }

  public function index() {
    $this->data['param'] = $this->paremlink('add');
    $this->data['main_content'] = $this->load->view('menu/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('menu/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }


  public function submit()  {

    $data = $this->security->xss_clean($_POST);
    if($data) {
      if($data['parent']==0){
         $logme['menu_name']= lcfirst(preg_replace('/\s+/', '', $data['name']));
        $logme['data_menu'] = $data['name'];
        $logme['path'] = $data['path'];
        $logme['select_service'] = $data['select_service'];

      }else{
        $logme['sub_menu_name'] = lcfirst(preg_replace('/\s+/', '', $data['name']));
        $logme['parent_id'] = $data['parent'];
        $logme['data_sub_menu'] = $data['name'];
        $logme['path'] = $data['path'];
        $logme['select_service'] = $data['select_service'];
 
      }


      if($this->common_model->insert( $logme, 'menu_permission' )) {


            $this->session->set_flashdata(
              array(
                'status' => 1,
                'msg' => "Menu Added Successfully"
              )
            );
            redirect('menu', 'refresh');
          }
        }

  }
  public function update()
  {
    // $data=array();
    $data = $this->security->xss_clean($_POST);
    // foreach ($form['form'] as $row) {
     
    //     $arr = array(
    //       $row['name'] => $row['value']
    //     );
    //   array_push($data, $arr);
     
    // }
    // pre($data);exit;
    if ($data) {
      if ($data['parent'] == 0) {
        $logme['menu_name'] = lcfirst(preg_replace('/\s+/', '', $data['name']));
        $logme['data_menu'] = $data['name'];
        $logme['path'] = $data['path'];
      } else {
        $logme['sub_menu_name'] = lcfirst(preg_replace('/\s+/', '', $data['name']));
        $logme['parent_id'] = $data['parent'];
        $logme['data_sub_menu'] = $data['name'];
        $logme['path'] = $data['path'];
      }

    
      if ($this->common_model->update($logme, "menu_permission_id", $data['menu_id'], 'menu_permission')) {
         $this->session->set_flashdata(
              array(
                'status' => 1,
                'msg' => "Menu Updated"
              )
            );
            redirect('menu', 'refresh');
      }else{
         $this->session->set_flashdata(
              array(
                'msg' => "Something Wrong"
              )
            );
            redirect('menu', 'refresh');
      }
    }
  }
  public function delete($id)
  {
    if ($this->db->where("menu_permission_id", $id)->delete('menu_permission')) {
      echo 1;
    } else {
      echo 0;
    }
  }

  public function set_email($email) {
      return $this->common_model->insert( $email, 'emails' );
  }

   public function get_squadlist() {

    $uri = $this->security->xss_clean($_GET);
    if (!empty($uri)) {
      $query = '';

      $output = array();


      $list = $uri['list'];

      $data = array();

      switch ($list) {
        case 'all':
          // code...
          $query .= "SELECT mp.menu_permission_id as id,mp.data_menu AS NAME,mp.path, mp.data_sub_menu AS sub_menu, mp1.data_menu as parent, mp.select_service as select_service FROM `menu_permission` AS mp
          LEFT JOIN menu_permission AS mp1 ON mp1.menu_permission_id = mp.parent_id ";

          break;

        default:
          $query .= "SELECT mp.menu_permission_id as id,mp.data_menu AS NAME,mp.path, mp.data_sub_menu AS sub_menu, mp1.data_menu as parent,  mp1.select_service as select_service FROM `menu_permission` AS mp LEFT JOIN menu_permission AS mp1 ON mp1.menu_permission_id = mp.parent_id ";

          break;
      }


      if(!empty($_GET["search"]["value"]))
      {
        $query .= 'OR mp.data_menu LIKE "%'.$_GET["search"]["value"].'%" ';
        $query .= 'OR mp.data_sub_menu LIKE "%'.$_GET["search"]["value"].'%" ';

      }

      if(!empty($_GET["order"]))
      {
        $query .= 'ORDER BY '.$_GET['order']['0']['column'].' '.$_GET['order']['0']['dir'].' ';
      }
      $sql = $this->db->query($query);
      $filtered_rows = $sql->num_rows();
      if($_GET["length"] != -1)
      {
        $query .= 'LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];
      }
      $sql = $this->db->query($query);
      $result = $sql->result_array();

      foreach($result as $row)
      {
        $sub_array=array();
        $sub_array[] = '<button type="button" class="btn btn-sm btn-info"  data-placement="bottom" onclick="Edit(' . $row['id'] . ')" title="EDIT Menu Information"><i class="fa fa-pencil-alt"></i></button>
           <button type="button" class="btn btn-sm btn-primary"  data-placement="bottom" onclick="Delete('. $row['id'] .')" title="Delete Menu Information"><i class="fa fa-trash-alt"></i></button>';
        if($row['sub_menu']=="") {
          $sub_array[] = $row['NAME'];
        }
        else{
          $sub_array[] = $row['sub_menu'];
        }
        $sub_array[] = $row['path'];
        $sub_array[] = $row['parent'];
        $sub_array[] = $this->commission_model->get_service_name($row['select_service']);
        $data[] = $sub_array;
      }

      $output["draw"] = intval($_GET["draw"]);
      $output["recordsTotal"] = $this->common_model->rowCount('menu_permission');
      $output["recordsFiltered"] = $filtered_rows;
      $output["data"] = $data;
      echo json_encode($output);
    }
  }


  

  public function edit_menu($id)
  {
    $menu= $this->common_model->select_option($id, 'menu_permission_id', 'menu_permission');
    echo json_encode($menu[0]);
  }
  
   public function addupdate() {

        echo $this->load->view('menu/edit', $this->data, true);

  }
  
  public function add() {

        echo $this->load->view('menu/add', $this->data, true);

  }
  
  
  
}
