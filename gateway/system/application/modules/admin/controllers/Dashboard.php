<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Vite {

  public $data = array();
	public function __construct() {
      parent::__construct();
       $this->load->model('common_model');
      $this->data['active'] = 'dashboard';
      $this->data['breadcrumbs'] = [array('url' => base_url('dashboard'), 'name' => 'Dashboard')];

  }

  public function index() {
    
    if($this->session->userdata("user_roles")==94    ){
      $this->data['main_content'] = $this->load->view('layout/home', $this->data, true);

    }else if($this->session->userdata("user_roles")==95 || $this->session->userdata("user_roles")==97){

      $this->data['main_content'] = $this->load->view('layout/home_distributor', $this->data, true);

    }
    else{
	$this->data['services']=$this->common_model->select("services");
      $this->data['main_content'] = $this->load->view('layout/home_retailer', $this->data, true);

    }
    $data['is_script'] = $this->load->view('menu/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  public function coming()
  {
    $this->data['main_content'] = $this->load->view('coming', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }

}
