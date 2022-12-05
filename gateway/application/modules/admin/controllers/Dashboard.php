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
	$this->data['slider']=$this->common_model->select_limit_value2('slider',3);
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
            // echo "<pre>";
            // print_r($res);
            // exit();
            foreach($res->result() as $row){
                $amount += (float)$row->transection_amount;
            }

            $prev_amount = 0;
            foreach($prev_res->result() as $prev_row){
                $prev_amount += (float)$prev_row->transection_amount;
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
public function slider()
  {
    if($this->session->userdata("user_roles")==94    ){
    $this->data['slider']=$this->common_model->select_limit_value2('slider',3);
    $this->data['main_content'] = $this->load->view('slider', $this->data, true);
    $this->load->view('layout/index', $this->data);
    }
     else
  {
    redirect('/dashboard','refresh');  
  }
  }
  public function editslider($id)
  {
    if($this->session->userdata("user_roles")==94    ){
    $this->data['editslider']=$this->common_model->select_value($id,'slider');
    // print_r($this->data['editslider']);
    // exit();
     $this->data['slider']=$this->common_model->select_limit_value2('slider',3);
    $this->data['main_content'] = $this->load->view('editslider', $this->data, true);
    $this->load->view('layout/index', $this->data);
    }
     else
  {
    redirect('/dashboard','refresh');  
  }
  }
  
  public function edit($id)
  {
      if($this->session->userdata("user_roles")==94    ){
      if(!empty($_FILES['file']['name'])){
   
			// Define new $_FILES array - $_FILES['file']
			$_FILES['file']['name'] = $_FILES['file']['name'];
			$_FILES['file']['type'] = $_FILES['file']['type'];
			$_FILES['file']['tmp_name'] = $_FILES['file']['tmp_name'];
			$_FILES['file']['error'] = $_FILES['file']['error'];
			$_FILES['file']['size'] = $_FILES['file']['size'];
  
			// Set preference
			$config['upload_path'] = 'assets/img/slide/'; 
			$config['allowed_types'] = 'jpg|jpeg|png';
			$config['max_size'] = '5000'; // max_size in kb
			$config['file_name'] = $_FILES['file']['name'];
   
			//Load upload library
			$this->load->library('upload',$config); 
   
			// File upload
      if (!$this->upload->do_upload('file'))
      {
         $error = array('error' => $this->upload->display_errors());
         $this->session->set_flashdata(
          array('status' => 0,
          'msg' =>$error['error'])
         );
         redirect('/slider','refresh');
      }
      else
      {
         $data = $this->upload->data();
         $this->db->where('id',$id);
      $this->db->update('slider',['slider'=>$data['file_name']]);
         redirect('/slider','refresh');   
      }
    }
      }
       else
  {
    redirect('/dashboard','refresh');  
  }
  }
  public function delete($id)
  {
      if($this->session->userdata("user_roles")==94    ){
      $this->db->delete('slider',['id'=>$id]);
       redirect('/slider','refresh');
      }
       else
  {
    redirect('/dashboard','refresh');  
  }
  }
  public function slideradd()
  {
      $data = $this->security->xss_clean($_POST);
    if($this->session->userdata("user_roles")==94    ){
    if(!empty($_FILES['file']['name'])){
   
			// Define new $_FILES array - $_FILES['file']
			$_FILES['file']['name'] = $_FILES['file']['name'];
			$_FILES['file']['type'] = $_FILES['file']['type'];
			$_FILES['file']['tmp_name'] = $_FILES['file']['tmp_name'];
			$_FILES['file']['error'] = $_FILES['file']['error'];
			$_FILES['file']['size'] = $_FILES['file']['size'];
  
			// Set preference
			$config['upload_path'] = 'assets/img/slide/'; 
			$config['allowed_types'] = 'jpg|jpeg|png';
			$config['max_size'] = '5000'; // max_size in kb
			$config['file_name'] = $_FILES['file']['name'];
   
			//Load upload library
			$this->load->library('upload',$config); 
   
			// File upload
      if (!$this->upload->do_upload('file'))
      {
         $error = array('error' => $this->upload->display_errors());
         $this->session->set_flashdata(
          array('status' => 0,
          'msg' =>$error['error'])
         );
         redirect('/slider','refresh');
      }
      else
      {
         $data = $this->upload->data();
         $this->common_model->insert(['slider'=>$data['file_name']],'slider');
         redirect('/slider','refresh');   
      }
    }
  }
   else
  {
    redirect('/dashboard','refresh');  
  }
  }
 
}
