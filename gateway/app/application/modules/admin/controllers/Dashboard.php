<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Vite {

  public $data = array();
	public function __construct() {
      parent::__construct();
       $this->load->model('common_model');
      $this->data['active'] = 'dashboard';
      $this->data['breadcrumbs'] = [array('url' => base_url('dashboard'), 'name' => 'Dashboard')];
      $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));

  }

  public function index() {
    $this->data['status_amount']=$this->transaction_analysis();
    if($this->session->userdata("user_roles")==94    ){
      $this->data['main_content'] = $this->load->view('layout/home', $this->data, true);

    }else if($this->session->userdata("user_roles")==95 || $this->session->userdata("user_roles")==97){

      $this->data['main_content'] = $this->load->view('layout/home_distributor', $this->data, true);

    }elseif($this->session->userdata("user_roles")==100){
      $this->data['main_content'] = $this->load->view('layout/home', $this->data, true);
    }else{
	$this->data['services']=$this->common_model->select("services");
      $this->data['main_content'] = $this->load->view('layout/home_retailer', $this->data, true);

    }
    $data['is_script'] = $this->load->view('menu/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
    public function transaction_analysis(){
        // pre($this->session->userdata());
        // exit();
            $date = date('Y-m-d');
            $prev_date = date("Y-m-d", strtotime("yesterday"));
            $member_id =$this->session->userdata('member_id');
            $res = $this->common_model->earning_by_date($member_id, $date);
            $prev_res = $this->common_model->earning_by_date($member_id, $prev_date);
            $amount = 0;
            foreach($res->result() as $row){
                $amount += $row->transection_amount;
            }

            $prev_amount = 0;
            foreach($prev_res->result() as $prev_row){
                $prev_amount += $prev_row->transection_amount;
            }

            if($amount > $prev_amount){
                // you can put your own calculation here
                $current_status = 'up';
            }
            if($amount < $prev_amount){
                // you can put your own calculation here
                $current_status = 'down';
            }
             if($amount==$prev_amount){
                // you can put your own calculation here
                $current_status = 'straight';
            }
                if($prev_amount!=0)
                {
                    $percent=($amount/$prev_amount)*100;
                }
                else
                {
                    $percent=0;
                }
            
            $data = [
                'today_amount' => $amount,
                'prev_amount' => $prev_amount,
                'current_status' => $current_status,
                'percent'=>$percent
            ];
            return $data;
            // echo json_encode($data);
        }
  public function coming()
  {
    $this->data['main_content'] = $this->load->view('coming', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }

}
