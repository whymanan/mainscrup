<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require FCPATH.'vendor/autoload.php';
   
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
   
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use GuzzleHttp\Client;

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
    $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));

  }



  public function index() {



    $this->data['param'] = $this->paremlink('add');

    $this->data['main_content'] = $this->load->view('index', $this->data, true);

    $this->data['is_script'] = $this->load->view('script', $this->data, true);



    $this->load->view('layout/index', $this->data);

  }
  
   public function manual_notification() {



    $this->data['param'] = $this->paremlink('add');

    $this->data['main_content'] = $this->load->view('notification', $this->data, true);

    $this->data['is_script'] = $this->load->view('script', $this->data, true);



    $this->load->view('layout/index', $this->data);

  }
  
   public function notify() {


     $data = $this->security->xss_clean($_POST);
     if(isset($data))
     {
         $data=[
               'create' => current_datetime(),
               'role'=>$data['user_role'],
               'msg'=>$data['notification'],
               ];
               $id = $this->common_model->insert($data, 'manual_notify');
        $this->session->set_flashdata(array('error' => 0, 'msg' => 'Send Successfully'));
     }
     else
     {
         $this->session->set_flashdata(array('error' => 1, 'msg' => 'Action Failed'));
     }
     redirect('user/notify', 'refresh');
  }



  public function profile()  {

    $this->data['user'] = $this->common_model->select_user_option($this->session->userdata('user_id'));

    $this->data['bank'] = $this->users_model->get_user_bank($this->session->userdata('user_id'));

    $docs = $this->common_model->select_user_doc($this->session->userdata('user_id'));
    
    $this->data['image'] = $this->common_model->photo('documents', $this->session->userdata('user_id'));


    // pre($this->data['image']);
    // exit();

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

//   pre( $this->data['bank'] );exit;

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

            // pre($insert);exit;

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

        'phone' => $data["phone_no1"],

        'role_id' => $data["user_role"],

        'password' => password_hash($data["phone_no1"], PASSWORD_DEFAULT),

        'created_by' => $this->session->userdata('user_id'),

        'user_status' => 'pending',

        'created_at' => current_datetime()

      ];
      
    //   print_r($logme);
     
      $id = $this->common_model->insert($logme, 'user');

      if ($id) { 
       if(!empty($data["receive_amount"]))
       {
           $receive=$data["receive_amount"];
       }
       else
       {
           $receive="";
       }
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
          'deal_amount'=>$data["deal_amount"],
          'receive_amount'=>$receive,

          'created_by' => $this->session->userdata('user_id'),

        ];

        if ($this->common_model->insert($userdata, 'user_detail')) {
          self::welcome($logme['member_id'],$logme['phone']);
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
  		// pre($uri);exit;

  		if (isset($uri['key']) && !empty($uri['key'])) {

  			$query = '';



  			$output = array();



  			$duid = $uri['key'];



  			$list = $uri['list'];



  			$data = array();





        if (isAdmin($this->session->userdata('user_roles'))) {
        // by akash
        //   $query .= "SELECT user.*, u.member_id as parent1,roles.role from user LEFT JOIN user as u on u.user_id=user.parent

        //   Join roles on roles.roles_id=user.role_id WHERE 1 ";
         //by deepak   
          $query.="SELECT user.*,user_detail.*, u.member_id as parent1,roles.role,`wallet`.`balance` from user LEFT JOIN user as u on u.user_id=user.parent Join roles on roles.roles_id=user.role_id LEFT join `wallet` ON `wallet`.`member_id`=`user`.`user_id` JOIN user_detail ON user.user_id = user_detail.fk_user_id WHERE user.delete_user = 0 ";
          $recordsFiltered = $this->users_model->row_count($query);

        }else{

          $query .= "SELECT user.*, u.member_id as parent1,roles.role from user LEFT JOIN user as u on u.user_id=user.parent

          Join roles on roles.roles_id=user.role_id join `wallet` ON `wallet`.`member_id`=`user`.`user_id` JOIN user_detail ON user.user_id = user_detail.fk_user_id WHERE user.parent = '{$duid}' && user.delete_user==0 ";

          $recordsFiltered = $this->users_model->row_count($query);

        }
         if(!empty($uri['role']))
        {
          $role=$this->role_model->get_role_by_id($uri['role']);
          $query .=" AND `roles`.`role`='".$role->role."' ";
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
             if (!empty($_GET["search"]["value"])) {
               $query .= 'AND (user.member_id LIKE "%' . $_GET["search"]["value"] . '%" ';
               $query .= 'OR user.phone LIKE "%' . $_GET["search"]["value"] . '%" ';
               $query .= 'OR user_detail .home_state LIKE "%' . $_GET["search"]["value"] . '%" ';
               $query .= 'OR user_detail .home_city LIKE "%' . $_GET["search"]["value"] . '%" ';
               $query .= 'OR user_detail .home_area LIKE "%' . $_GET["search"]["value"] . '%" ';
               $query .= 'OR user_detail .home_pincode LIKE "%' . $_GET["search"]["value"] . '%" ';
               $query .= ' ) ';
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
  		// 	print_r($this->db->last_query());
  		// 	exit();

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
             
          $sub_array[] = '<button type="button" class="btn btn-sm btn-primary"  data-placement="bottom" onclick="Password(' . $row['user_id'] . ')" ><i class="fa fa-cog fa-spin fa-fw"></i></button>';     

          $sub_array[] = $row['member_id'];

          $sub_array[] = $row['parent1'];
          if (isAdmin($this->session->userdata('user_roles')))
          {
              $sub_array[] = $row['balance'];
          }

          $sub_array[] = $row['email'];
          

          $sub_array[] = $row['phone'];

          $sub_array[] = $row['role'];

          $sub_array[] = $status ;

          $sub_array[] =  $kyc;
          $sub_array[] = $row['deal_amount'];
          $sub_array[] = $row['receive_amount'];

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

        'phone' => $data["phone_no1"],



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
          'receive_amount' => $data["receive_amount"],
        

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
  
// <!--Start Reset Password-->
      function resetpass(){
         
            $uri = $this->security->xss_clean($_POST);
            
            $password = password_hash($uri['password'], PASSWORD_DEFAULT);
            
            if($this->common_model->resetpass($uri['user_id'],$password)){
                
                $result = [
                            
                            "status" => true,
                            "msg"    => "Successfully"
                            
                          ];
                
                echo json_encode($result);
                
            }else{
                
                 $result = [
                            
                            "status" => false,
                            "msg"    => "Faild"
                            
                          ];
                
                echo json_encode($result);
                
            }
          
        }
        
    public function checkpass($password){

      if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
      {
            $user = $this->session->userdata('user_id');
    
            $log =	$this->common_model->check_user($user);
            if (password_verify($password, $log->password)) {
              echo 0;
            }else{
              echo 1;
            }

      }else{
        echo "Your Request Not Valid";
      }

    }    
// <!--End Reset Password-->
 public function export()
    {
      	$uri = $this->security->xss_clean($_GET);
  		//pre($uri);exit;
  		ini_set('memory_limit', '44M');
        $fileName = 'employee.xlsx';

  		if (isset($uri['key']) && !empty($uri['key'])) {

  			$query = '';



  			$output = array();



  			$duid = $uri['key'];


  			$data = array();

        if (isAdmin($this->session->userdata('user_roles'))) {
        // by akash
        //   $query .= "SELECT user.*, u.member_id as parent1,roles.role from user LEFT JOIN user as u on u.user_id=user.parent

        //   Join roles on roles.roles_id=user.role_id WHERE 1 ";
         //by deepak   
          $query.="SELECT user.*, u.member_id as parent1,roles.role,`wallet`.`balance` from user LEFT JOIN user as u on u.user_id=user.parent

           Join roles on roles.roles_id=user.role_id LEFT join `wallet` ON `wallet`.`member_id`=`user`.`user_id` WHERE 1 && user.delete_user!=1 ";
      

        }else{

          $query .= "SELECT user.*, u.member_id as parent1,roles.role from user LEFT JOIN user as u on u.user_id=user.parent

          Join roles on roles.roles_id=user.role_id WHERE user.parent = '{$duid}' && user.delete_user!=1 ";

          

        }

	     $sql = $this->db->query($query);

  			$result = $sql->result_array();
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'S.No');
            $sheet->setCellValue('B1', 'MEMBER ID');
            $sheet->setCellValue('C1', 'PARENT');
           $sheet->setCellValue('D1', 'BALNACE');
            $sheet->setCellValue('E1', 'EMAIL');
            $sheet->setCellValue('F1', 'PHONE');
            $sheet->setCellValue('G1', 'USER TYPE');
            $sheet->setCellValue('H1' , 'STATUS');
            $sheet->setCellValue('I1' , 'KYC');
            $sheet->setCellValue('J1' , 'CREATED AT');
            $rows = 2;
            $i=1;
            foreach ($result as $val){
                $sheet->setCellValue('A'. $rows, $i);
                $sheet->setCellValue('B'. $rows,$val['member_id']);
                $sheet->setCellValue('C' . $rows, $val['parent1']);
                if(isAdmin($this->session->userdata('user_roles')))
                {
                 $sheet->setCellValue('D' . $rows,$val['balance']);
                }
                else
                {
                     $sheet->setCellValue('D' . $rows," ");
                }
               
                $sheet->setCellValue('E' . $rows,$val['email']);
                $sheet->setCellValue('F' . $rows,$val['phone']);
                $sheet->setCellValue('G' . $rows,$val['role']);
             
                $sheet->setCellValue('H' . $rows, $val['user_status']);
                $sheet->setCellValue('I' . $rows, $val['kyc_status']);
                $sheet->setCellValue('J' . $rows, $val['created_at']);
                $rows++;
                $i++;
            }
            $writer = new Xlsx($spreadsheet);
            // $writer->save("php://output");
           $writer->save("uploads/".$fileName);
            header("Content-Type: application/vnd.ms-excel");
            echo base_url()."/uploads/".$fileName;

  		}
  	}

 // <!--wallet show-->
    public function wallet_show()
    {
       $data=$this->security->xss_clean($_GET);
       $sql="SELECT sum(`balance`) AS balance FROM `wallet` ";
       if(!empty($data['value']))
       {
         $role=$this->role_model->get_role_by_id($data['value'])->role;
         $sql.="Where `role_id`=".$data['value']; 
         
       }
       else
       {
           $role='All Member';
       }
       $query = $this->db->query($sql);
       $balance=$query->result_array();
       $data=['role'=>$role,'balance'=>$balance[0]['balance']];
       echo json_encode($data);
    }
    
    //user delete
    public function delete_user($id)
    {
        $data=$this->common_model->select_option($id,'parent','user');
        if($this->common_model->update(['delete_user'=>1],'user_id',$id,'user')){
            foreach($data as $value)
            {
            $this->common_model->update(['parent'=>1],'parent',$value['parent'],'user'); //make parent to amdin
            }
            echo "true";
        }
        else
        {
            echo "false";
        }
    }
    
      //api sms
  private function welcome($userid,$phone)
  {
        $text="Welcome to At Moon pe Your User id is: ".$userid." and Password is: ".$phone." You can access the portal using this link:".base_url('/auth');
        $this->client = new Client();
        try {
        $response = $this->client->request('GET', "http://sms.vitefintech.com/api/sendmsg.php?user=&pass=&sender=&phone=".$phone."&text=".$text."&priority=ndnd&stype=normal");

      $result = $response->getBody()->getContents();
      return $result;
         } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
     }
     }
     
  public function send_otp()
  {
         $data = $this->session->userdata('phone');
         $otp=self::otp();
         if($this->common_model->update(['otp'=>$otp],'user_id',$this->session->userdata('user_id'),'user'))
         {
              $text="Dear user OTP to reset your At Moon Pe password is: ".$otp." Do not share your OTP with others.";
              $this->client = new Client();
              try {
                 $response = $this->client->request('GET', "http://sms.vitefintech.com/api/sendmsg.php?user=&pass=&sender=&phone=".$data."&text=".$text."&priority=ndnd&stype=normal");

                  $result = $response->getBody()->getContents();
                  echo "true";
              } catch (GuzzleHttp\Exception\BadResponseException $e) {
                  #guzzle repose for future use
                  $response = $e->getResponse();
                  $responseBodyAsString = $response->getBody()->getContents();
                  print_r($responseBodyAsString);
               }
         }
   }
     
     //otp generator
  private function otp()
  {
        $min = 2000;  // minimum
        $max = 9990;  // maximum
        $otp=random_int(1000,mt_rand($min, $max));
        return $otp;
     }
    //otp verify
   public function otp_verify()
   {
        $uri = $this->security->xss_clean($_POST);
        $user_details=$this->common_model->select_option($this->session->userdata('user_id'),'user_id','user');
        if($user_details && !empty($uri['otp']))
        {
          if($user_details[0]['otp']==$uri['otp'])
          {
            $this->common_model->update(['otp'=>''],'user_id',$this->session->userdata('user_id'),'user');
            echo "true";
          }
           else
        {
            echo "false";
        }
        }
        else
        {
            echo "false";
        }
     }
    

}
