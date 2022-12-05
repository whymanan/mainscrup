<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class UserController extends Vite {





  public $data = array();



  public function __construct() {

      parent::__construct();

    $this->load->model('common_model');

    $this->load->model('menu_model');

    $this->load->model('users_model');

    $this->load->model('role_model');

    $this->data['active'] = 'users';

    $this->data['breadcrumbs'] = [array('url' => base_url('users'), 'name' => 'Users')];

  }



  public function index() {



    $this->data['param'] = $this->paremlink('add');

    $this->data['main_content'] = $this->load->view('index', $this->data, true);

    $this->data['is_script'] = $this->load->view('script', $this->data, true);



    $this->load->view('layout/index', $this->data);

  }



  public function profile()  {

    $this->data['user'] = $this->common_model->select_user_option($this->session->userdata('user_id'));

    $this->data['bank'] = $this->users_model->get_user_bank($this->session->userdata('user_id'));

    $docs = $this->common_model->select_user_doc($this->session->userdata('user_id'));
    
    $this->data['image'] = $this->common_model->photo('documents', $this->session->userdata('user_id') );


    //pre($this->data['bank']);exit;

    if(!empty($docs)){

      $data1 = self::get_userdocs_by_type($docs);



      if(in_array("photo", $data1)){

        $this->data['photo']=1;

      }  if (in_array("adhar_front", $data1) && in_array("adhar_back", $data1)) {

        $this->data['adhar'] = 1;

      } if (in_array("pan", $data1)){

        $this->data['pan'] = 1;

      } if (in_array("passbook", $data1)) {

        $this->data['pass'] = 1;

      }

    }else{

      $this->data['pan'] = 0;

      $this->data['adhar'] = 0;

      $this->data['photo'] = 0;

      $this->data['pass'] = 0;

    }



    // pre($this->data);

    // exit;



    $this->data['main_content'] = $this->load->view('profile', $this->data, true);

    $this->data['is_script'] = $this->load->view('script', $this->data, true);



    $this->load->view('layout/index', $this->data);

  }

  function get_userdocs_by_type($doc){

     $data=array();

    foreach($doc as  $row){

     $data[]= $row['type'];



    }

    return $data;

  }

  public function kyc()

  {
      $uri = $this->security->xss_clean($_GET);
     
      if(!isset($uri['q'])){
    $this->data['user_id']=$this->session->userdata('user_id');
      }
    else{
    $this->data['user_id']=$uri['q'];
    }
    $data['docs'] = $this->common_model->select_user_doc($this->data['user_id']);

    //  pre($data['docs']);exit;

    $this->data['bank'] = $this->common_model->exists('user_bank_details',array("fk_user_id" => $this->data['user_id']));

  //pre( $this->data['bank'] );exit;

    $this->data['main_content'] = $this->load->view('kyc', $this->data, true);

    $this->data['is_script'] = $this->load->view('script', $this->data, true);



    $this->load->view('layout/index', $this->data);

  }



  public function file_upload()

  {

    $data=array();
    if($_POST['user_id']!='')
    $user_id=$_POST['user_id'];
    else
    $user_id=$this->session->userdata('user_id');
   // $countfiles = count($_FILES);



    if(!empty(isset($_FILES["image_file"]["name"]))){



          // Set preference

          $config['upload_path'] = 'uploads/'.$_POST['type'];

          $config['allowed_types'] = 'jpg|jpeg|png|gif';

          $config['max_size'] = '500'; // max_size in kb

          $config['overwrite'] = TRUE;

          $config['file_name'] = $_POST['type']."_".  $user_id;

        //echo  $config['upload_path'];

          //Load upload library

          $this->load->library('upload',$config);



      if (!$this->upload->do_upload('image_file')) {

        $error =  $this->upload->display_errors();

        echo json_encode(array('msg' => $error, 'success' => false));

      } else {

        $data = $this->upload->data();

        $insert['name'] = $data['file_name'];

        $insert['root'] =  $user_id;

        $insert['status'] = 1;

        $insert['type'] = $_POST['type'];

        $insert['details'] = json_encode($_FILES["image_file"]);

        $insert['created'] = current_datetime();



        $this->db->insert('documents', $insert);

        $getId = $this->db->insert_id();



        $arr = array('msg' => 'Image has not uploaded successfully', 'success' => false);



        if ($getId) {

          $arr = array('msg' => 'Image has been uploaded successfully', 'success' => true);

        }

        echo json_encode($arr);

      }

        }









  }



  public function add_bank()

  {

  if($_POST['user_id']!='')
    $user_id=$_POST['user_id'];
    else
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
      $userdata = [

          'aadhar' => $data["adharcard"],

          'pan' =>  strtoupper($data["pancard"]),

          'organisation' => $data["organization_name"],

          'gstno' => $data["gst_no"],
        'address' => $data["address"],

          'state' => $data["states"],
          'city' => $data["city"],
            'area' => $data["area"],
          'pincode' => $data["pincode"],

          'updated_at' => current_datetime(),

          'updated_by' => $user_id,

        ];

        if ($this->common_model->update($userdata,"fk_user_id", $user_id, 'user_detail')) {
        $this->common_model->update(array("kyc_status" => "pending"),"user_id", $user_id, 'user');
            
           if(! $this->common_model->exists('user_bank_details',array("fk_user_id" => $user_id))) 
            $id = $this->common_model->insert($logme, 'user_bank_details');
            else
            $id = $this->common_model->update($logme,"fk_user_id", $user_id, 'user_bank_details');

      if ($id) {

       echo 1;
$message = [
                'msg' => 'Your bank Details added Successfully ',
                'user_id' => $user_id
              ];
              $this->set_notification($message);


      } else {

        echo 0;

      }


        } else {

          echo 0;

        }

   


    }

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



    if ($data) {

    $count=$this->common_model->get_last_id('user',$data['user_role']);
    if($count)
    $cc=$count;
    else
    $cc=1000;
      $uid = getCustomId($cc, $this->role_model->get_sort_name_id($data['user_role']));

      $logme = [

        'member_id' => $uid,

        'email' => $data["email"],

        'parent' => $data["vendor"],
        'counter' => $cc+1,

        'phone' => $data["phone_no"],

        'role_id' => $data["user_role"],

        'password' => password_hash($data["phone_no"], PASSWORD_DEFAULT),

        'created_by' => $this->session->userdata('user_id'),

        'user_status' => 'pending',

        'created_at' => current_datetime()

      ];

      $id = $this->common_model->insert($logme, 'user');

      if ($id) { 

        $userdata = [

          'fk_user_id' => $id,

          'first_name' => $data["firstname"],

          'last_name' => $data["lastname"],

          'home_address' => $data["home_address"],

          'home_state' => $data["home_states"],

          'home_city' => $data["home_city"],

          'home_pincode' => $data["home_pincode"],
          'home_area' => $data["home_area"],

          'created_at' => current_datetime(),

          'created_by' => $this->session->userdata('user_id'),

        ];

        if ($this->common_model->insert($userdata, 'user_detail')) {

          $this->session->set_flashdata(array('error' => 0, 'msg' => 'Registered Successfully'));

        } else {

          $this->session->set_flashdata(array('error' => 1, 'msg' => 'Action Failed'));

        }

      } else {

        $this->session->set_flashdata(array('error' => 1, 'msg' => 'Action Failed'));

      }

      redirect('users', 'refresh');

    }

  }









  public function get_squadlist() {



  		$uri = $this->security->xss_clean($_GET);
  		//pre($uri);exit;

  		if (isset($uri['key']) && !empty($uri['key'])) {

  			$query = '';



  			$output = array();



  			$duid = $uri['key'];



  			$list = $uri['list'];



  			$data = array();





        if (isAdmin($this->session->userdata('user_roles'))) {

          $query .= "SELECT user.*, u.member_id as parent1,roles.role from user LEFT JOIN user as u on u.user_id=user.parent

          Join roles on roles.roles_id=user.role_id WHERE 1 ";

          $recordsFiltered = $this->users_model->row_count($query);

        }else{

          $query .= "SELECT user.*, u.member_id as parent1,roles.role from user LEFT JOIN user as u on u.user_id=user.parent

          Join roles on roles.roles_id=user.role_id WHERE user.parent = '{$duid}'";

          $recordsFiltered = $this->users_model->row_count($query);

        }



  			switch ($list) {



          case 'all':



          break;



  				case 'new':

  					$query .= " AND user.kyc_status = 'new' ";

  					break;



  				case 'pending':

  					$query .= " AND user.kyc_status = 'pending' ";

  					break;



  				case 'verify':

  					$query .= " AND user.kyc_status = 'verify' ";



  					break;



  				default:

            echo json_encode(['error' => 1, 'msg' => 'request not allowed']);

            break;


 
  			}
	
            if(isset($_GET["searchtype"]) && !empty($_GET["searchtype"])){
               $query .= " AND user.".$_GET["searchByCat"]." = ". $_GET["searchValue"]." "; 
               	
            }

  			if(!empty($_GET["order"]))

  			{

  				$query .= 'ORDER BY '.$_GET['order']['0']['column'].' '.$_GET['order']['0']['dir'].' ';

  			}

  			else

  			{

  				$query .= 'ORDER BY user.created_at DESC ';

  			}



  			if($_GET["length"] != -1)

  			{

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

            $kyc = '<i class="fa fa-circle text-success font-12" data-toggle="tooltip" data-placement="top" title="" data-original-title="Active"></i>';

          } elseif ($row['kyc_status'] == 'pending') {

            $kyc = '<i class="fa fa-circle text-warning font-12" data-toggle="tooltip" data-placement="top" title="" data-original-title="Pending"></i>';

          } else {

            $kyc = '<i class="fa fa-circle text-danger font-12" data-toggle="tooltip" data-placement="top" title="" data-original-title="Not uploaded"></i>';

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

          $sub_array[] = $row['member_id'];

          $sub_array[] = $row['parent1'];

          $sub_array[] = $row['email'];

          $sub_array[] = $row['phone'];

          $sub_array[] = $row['role'];

          $sub_array[] = $status ;

          $sub_array[] =  $kyc;

          $sub_array[] = $row['created_at'];





          $data[] = $sub_array;

          $i++;

        }







  			$output["draw"] = intval($_GET["draw"]);

        $output["recordsFiltered" ] =$recordsFiltered;	

  			$output["recordsTotal"] =$recordsFiltered;

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

      // pre($this->data['details']);

      // exit;

      $this->data['main_content'] = $this->load->view('edit', $this->data, true);

      $this->data['is_script'] = $this->load->view('script', $this->data, true);

      $this->load->view('layout/index', $this->data);

    }

  }



  public function update()   {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);

      //pre($_POST);exit;

      $logme = [





        'email' => $data["email"],

        'parent' => $data["vendor"],

        'phone' => $data["phone_no"],



        'role_id' => $data["user_role"],

        'updated_by' => $this->session->userdata('user_id'),

        'user_status' => 'pending',

        'updated_at' => current_datetime()

      ];



      $this->common_model->update($logme, 'user_id', $_POST['user_id'], 'user');



      $userdata = [



        'first_name' => $data["firstname"],

        'last_name' => $data["lastname"],

        
'home_address' => $data["home_address"],

          'home_state' => $data["home_states"],

          'home_city' => $data["home_city"],

          'home_pincode' => $data["home_pincode"],
          'home_area' => $data["home_area"],
        

        'updated_at' => current_datetime(),

        'updated_by' => $this->session->userdata('user_id'),

      ];



      $this->common_model->update($userdata, 'user_detail_id', $_POST['user_detail_id'], 'user_detail');

    }



   redirect('users', 'refresh');

  }



  public function live_count() {

    $uri = $this->security->xss_clean($_GET);

    if (isset($uri['key']) && !empty($uri['key'])) {

      $data = array();

      $duid = $uri['key'];

      if (isAdmin($this->session->userdata('user_roles'))) {

        $result = $this->users_model->totel_count();

      }else{

        $result = $this->users_model->totel_count($duid);

      }

      foreach ($result as $value) {

        $data[$value->kyc_status] = number_format($value->totel, 0);

      }

      echo json_encode($data);

    }



  }



}
