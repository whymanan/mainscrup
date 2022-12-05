<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gstfiling extends Vite {


  public $data = array();

  public function __construct() {
      parent::__construct();
    $this->load->model('common_model');
    $this->load->model('menu_model');
    $this->load->model('users_model');
    $this->load->model('commission_model');
    $this->load->helper('string');
     $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));

    
  }

  public function index() {
    $this->data['param'] = $this->paremlink('add');
    $this->data['main_content'] = $this->load->view('index', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }


    
  public function submit() {
    
    $data = $this->security->xss_clean($_POST);
    // $data=array();
    $data=array();
    $member_id=$_POST['member_id'];
    $reference_id =$_POST['reference_id'];
    $accepted_date =$_POST['accepted_date'];
    $type= $_POST['type'];



    if(!empty(isset($_FILES["image_file"]["name"]))){



          // Set preference

          $config['upload_path'] = 'uploads/gstfile';

          $config['allowed_types'] = 'jpg|jpeg|png|gif';

          $config['max_size'] = '500'; // max_size in kb

          $config['overwrite'] = TRUE;

          $config['file_name'] = $_POST['type']."_".  $member_id;

        // echo  $config['upload_path'];
        // exit();

          //Load upload library

          $this->load->library('upload',$config);



      if (!$this->upload->do_upload('image_file')) {

        $error =  $this->upload->display_errors();

        echo json_encode(array('msg' => $error, 'success' => false));

      } else {

        $data = $this->upload->data();

        $insert['filepath'] = $data['file_name'];

        $insert['reference_id'] = $reference_id;

        $insert['member_id'] = $member_id;

        $insert['accepted_date'] = $accepted_date;

        $insert['type'] = $type;



        $this->db->insert('gstfile', $insert);

        $getId = $this->db->insert_id();

        
        $arr = array('msg' => 'Image has not uploaded successfully', 'success' => false);



        if ($getId) {

          $arr = array('msg' => 'Image has been uploaded successfully', 'success' => true);

        }

        echo json_encode($arr);



      }

        }


    
  }

  public function add() {
    // $this->data['breadcrumbs'] = [array('url' => base_url('ca'), 'name' => 'Ca'), array('url' => base_url('add'), 'name' => 'Send file')];
    // $this->data['param'] = $this->paremlink('/');
    $this->data['bank'] = $this->users_model->get_parent_bank($this->session->userdata('user_id'));
  
    $this->data['main_content'] = $this->load->view('add', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
     //list of admin start
            
             public function retailer_list(){
              // $this->data['breadcrumbs'] = [array('url' => base_url('ca'), 'name' => 'Ca'), array('url' => base_url('retailer_list'), 'name' => 'List Of Retailers')];
              // $this->data['param'] = $this->paremlink('/');
                 $this->data['member_id'] = $this->security->xss_clean($_GET);

            $this->data['main_content'] = $this->load->view('home', $this->data, true);
            $this->data['is_script'] = $this->load->view('script', $this->data, true);
            $this->load->view('layout/index', $this->data);
                
            } 
           public function get_admin_list()
              {
            
                $uri = $this->security->xss_clean($_GET);
               
        
                if (!empty($uri)) {
                
            
                  $output = array();
            
            
                 
            
                  $data = array();
                  $sql = $this->db->query("SELECT g.*, u.phone ,u.user_id FROM gstfile g,user u WHERE g.member_id =u.member_id AND u.role_id='98'");
                  $filtered_rows = $sql->num_rows();
                  $result = $sql->result_array();
                 
            
                  $i = 1;
                  foreach ($result as $row) {
                    $sub_array = array();
                    
                    $sub_array[] = $row['member_id'];
                    
                     $sub_array[] = $row['reference_id'];
                    $sub_array[] = $row['accepted_date'];
                   
                    $sub_array[] = $row['type'];
                    $sub_array[] = $row['status'];
                    $sub_array[] = $row['remark'];
                    
                    
          // $sub_array[] = '<a href="' . base_url('ca/retailer_details?q=') . $row['user_id'] . '"> <button type="button" class="btn btn-sm btn-success"  data-placement="bottom" title="Details Menu Information">Details</button></a>';
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
            
            //list of admin ends
      // public function submit(){
      //   echo 'yes'
      // }

            

            public function submitfile()
            {
            
              $data = $this->security->xss_clean($_POST);
              
          
              if ($data) {
                $config['upload_path'] = 'uploads/gstfile';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $temp = explode(".", $_FILES["userfiles"]["name"]);
            $newfilename = round(microtime(true)) . '.' . end($temp);
            $config['file_name'] = $newfilename;
            
            //Load upload library and initialize configuration
            $this->load->library('upload',$config);
            $this->upload->initialize($config);
            $this->upload->do_upload('userfiles');
            $uploadData = $this->upload->data();
            $filePath = "uploads/gstfile/".$uploadData['file_name'];
            
                $logme = [
                 
                  'reference_id' => $data["reference_id"],
                  'member_id'=> $data["member_id"],
                  'accepted_date' =>  $data["accepted_date"],
                  
                  'type' => $data["type"],
                   'filepath'=> $filePath,
                ];
               // pre($logme);exit;
               $id= $this->common_model->insert($logme, 'gstfile');
                
                 
                
                redirect('gstfiling/add', 'refresh');
              }
            }

//  function start


  
            
public function gstform(){
             
  // $this->data['member_id'] = $this->security->xss_clean($_GET);
  // $this->data['breadcrumbs'] = [array('url' => base_url('gstfiling'), 'name' => 'gstform'), array('url' => base_url('gstform'), 'name' => 'Gst Form')];
  // $this->data['param'] = $this->paremlink('/');
$this->data['main_content'] = $this->load->view('gstform', $this->data, true);
$this->data['is_script'] = $this->load->view('script', $this->data, true);
$this->load->view('layout/index', $this->data);
 
} 

public function addForm() {
  // $this->load->view('form');
  if ($_POST) {
    echo $this->load->view('form');
  }

  // if ($_POST) {

  //   $data = $this->security->xss_clean($_POST);

  //   if (isset($data['aepsCommissionForm'])) {

  

  //     echo $this->load->view('form');

  //   } else {
  //     echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
  //   }

  // }

}


    
public function reg() {
    
  // $data = $this->security->xss_clean($_POST);
  

  if ($_POST) 
  {
    $files = $_FILES;
    // photo
        $count = count($_FILES['userfile']['name']);
        for($i=0; $i<$count; $i++)
          {
    
            $_FILES['userfile']['name']= time().$files['userfile']['name'][$i];
          $_FILES['userfile']['type']= $files['userfile']['type'][$i];
          $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
          $_FILES['userfile']['error']= $files['userfile']['error'][$i];
          $_FILES['userfile']['size']= $files['userfile']['size'][$i];
          $config['upload_path'] = 'uploads/gstfile/';
          // $config['allowed_types'] = 'jpg|png|jpeg';
          $config['max_size'] = '2000000';
          $config['remove_spaces'] = true;
          $config['overwrite'] = false;
          $config['max_width'] = '';
          $config['max_height'] = '';
          $this->load->library('upload', $config);
          $this->upload->initialize($config);
          $this->upload->do_upload();
         
          $images[] = $_FILES['userfile']['name'];
          }
          $fileName = implode(',',$images);
          $filename1 = explode(',',$fileName);
      //  phto
          // adharfront
          $count2 = count($_FILES['adhar_front']['name']);
          for($i=0; $i<$count2; $i++)
            {
      
              $_FILES['adhar_front']['name']= time().$files['adhar_front']['name'][$i];
            $_FILES['adhar_front']['type']= $files['adhar_front']['type'][$i];
            $_FILES['adhar_front']['tmp_name']= $files['adhar_front']['tmp_name'][$i];
            $_FILES['adhar_front']['error']= $files['adhar_front']['error'][$i];
            $_FILES['adhar_front']['size']= $files['adhar_front']['size'][$i];
            $config['upload_path'] = 'uploads/gstfile/';
            // $config['allowed_types'] = 'jpg|png|jpeg';
            $config['max_size'] = '2000000';
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;
            $config['max_width'] = '';
            $config['max_height'] = '';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $this->upload->do_upload();
           
            $images2[] = $_FILES['adhar_front']['name'];
            }
            $fileName2 = implode(',',$images2);
            $filename2 = explode(',',$fileName2);

            // print_r($filename2); exit();
          // adharfornt
          $count3 = count($_FILES['adhar_back']['name']);
          for($i=0; $i<$count3; $i++)
            {
      
              $_FILES['adhar_back']['name']= time().$files['adhar_back']['name'][$i];
            $_FILES['adhar_back']['type']= $files['adhar_back']['type'][$i];
            $_FILES['adhar_back']['tmp_name']= $files['adhar_back']['tmp_name'][$i];
            $_FILES['adhar_back']['error']= $files['adhar_back']['error'][$i];
            $_FILES['adhar_back']['size']= $files['adhar_back']['size'][$i];
            $config['upload_path'] = 'uploads/gstfile/';
            // $config['allowed_types'] = 'jpg|png|jpeg';
            $config['max_size'] = '2000000';
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;
            $config['max_width'] = '';
            $config['max_height'] = '';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $this->upload->do_upload();
           
            $images3[] = $_FILES['adhar_back']['name'];
            }
            $fileName3 = implode(',',$images3);
            $filename3 = explode(',',$fileName3);
          // adharback
          // pan
          $count4 = count($_FILES['pan_file']['name']);
          for($i=0; $i<$count4; $i++)
            {
      
              $_FILES['pan_file']['name']= time().$files['pan_file']['name'][$i];
            $_FILES['pan_file']['type']= $files['pan_file']['type'][$i];
            $_FILES['pan_file']['tmp_name']= $files['pan_file']['tmp_name'][$i];
            $_FILES['pan_file']['error']= $files['pan_file']['error'][$i];
            $_FILES['pan_file']['size']= $files['pan_file']['size'][$i];
            $config['upload_path'] = 'uploads/gstfile/';
            // $config['allowed_types'] = 'jpg|png|jpeg';
            $config['max_size'] = '2000000';
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;
            $config['max_width'] = '';
            $config['max_height'] = '';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $this->upload->do_upload();
           
            $images4[] = $_FILES['pan_file']['name'];
            }
            $fileName4 = implode(',',$images4);
            $filename4 = explode(',',$fileName4);
          // panend
    $count5 = count($_FILES['statement']['name']);
          for($i=0; $i<$count4; $i++)
            {
      
              $_FILES['statement']['name']= time().$files['statement']['name'][$i];
            $_FILES['statement']['type']= $files['statement']['type'][$i];
            $_FILES['statement']['tmp_name']= $files['statement']['tmp_name'][$i];
            $_FILES['statement']['error']= $files['statement']['error'][$i];
            $_FILES['statement']['size']= $files['statement']['size'][$i];
            $config['upload_path'] = 'uploads/gstfile/';
            // $config['allowed_types'] = 'jpg|png|jpeg';
            $config['max_size'] = '2000000';
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;
            $config['max_width'] = '';
            $config['max_height'] = '';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $this->upload->do_upload();
           
            $images5[] = $_FILES['statement']['name'];
            }
            $fileName5 = implode(',',$images5);
            $filename5 = explode(',',$fileName5);
          // pan
    
    // statementend
      $name=$this->input->post('name'); 
      $m_name=$this->input->post('m_name');
      $dob=$this->input->post('dob');
      $email=$this->input->post('email');
      $mobile=$this->input->post('mobile');
      $pan_no=$this->input->post('pan_no');
      $adhar_no=$this->input->post('adhar_no');
      $designation=$this->input->post('designation');
      $address=$this->input->post('address');
      $din=$this->input->post('din');
      $this->db->select('reg_id');
        $this->db->order_by('reg_id',"desc");
        $this->db->limit(1);
        $data = $this->db->get("gst_registration")->result();
        if (!empty($data))
        {
          foreach ($data as $row):
            $reg_id = $row->reg_id +1;
          endforeach;
        }else{
      $reg_id ='1';
        }
      $data = array();
      for ($i = 0; $i < count($this->input->post('name')); $i++)
      {
        
          $data[$i] = array(
              'name' => $name[$i],
              'm_name' => $m_name[$i],
              'dob' => $dob[$i],
              'email' => $email[$i],
              'mobile' => $mobile[$i],
              'pan_no' => $pan_no[$i],
              'adhar_no' => $adhar_no[$i],
              'designation' => $designation[$i],
              'address' => $address[$i],
              'reg_id' => $reg_id,
              'photo' =>$filename1[$i],
              'adhar_front' =>$filename2[$i],
              'adhar_back' =>$filename3[$i],
              'pan_file' =>$filename4[$i],
              'statement'=>$filename5[$i],
              'din' =>$din[$i]
            
              


          );
      }
      
    //   print_r($data); exit();
      
      $regdata = [
     
        'status' => 'pending'
      ];   
                 
    
      $this->common_model->create($data);
      $this->common_model->insert($regdata, 'gst_registration');
  
        echo '100';
   
  }


  
}


public function docs() {
  $data = $this->security->xss_clean($_POST);
              
          
  if ($data) {
// $config['upload_path'] = 'uploads/gstfile';
// // $config['allowed_types'] = 'jpg|jpeg|png';
// $temp = explode(".", $_FILES["auth_sign_file"]["name"]);
// $newfilename = round(microtime(true)) . '.' . end($temp);
// $config['file_name'] = $newfilename;
// $this->load->library('upload',$config);
// $this->upload->initialize($config);
// $this->upload->do_upload('auth_sign_file');
// $uploadData = $this->upload->data();
// $filePath = "uploads/gstfile/".$uploadData['file_name'];

// moafile
// $config1['upload_path'] = 'uploads/gstfile';
// // $config1['allowed_types'] = 'jpg|jpeg|png';
// $temp1 = explode(".", $_FILES["moa_file"]["name"]);
// $newfilename1 = round(microtime(true)) . '.' . end($temp1);
// $config1['file_name'] = $newfilename1;
// $this->load->library('upload',$config1);
// $this->upload->initialize($config1);
// $this->upload->do_upload('moa_file');
// $uploadData1 = $this->upload->data();
// $filePath1 = "uploads/gstfile/".$uploadData1['file_name'];

// aoafile
// $config2['upload_path'] = 'uploads/gstfile';
// // $config2['allowed_types'] = 'jpg|jpeg|png';
// $temp2 = explode(".", $_FILES["aoa_file"]["name"]);
// $newfilename2 = round(microtime(true)) . '.' . end($temp2);
// $confi2['file_name'] = $newfilename2;
// $this->load->library('upload',$config2);
// $this->upload->initialize($config2);
// $this->upload->do_upload('aoa_file');
// $uploadData2 = $this->upload->data();
// $filePath2 = "uploads/gstfile/".$uploadData2['file_name'];
// electricy bill
$config3['upload_path'] = 'uploads/gstfile';
// $config3['allowed_types'] = 'jpg|jpeg|png';
$temp3 = explode(".", $_FILES["electricity_bill"]["name"]);
$newfilename3 = round(microtime(true)) . '.' . end($temp3);
$confi3['file_name'] = $newfilename3;
$this->load->library('upload',$config3);
$this->upload->initialize($config3);
$this->upload->do_upload('electricity_bill');
$uploadData3 = $this->upload->data();
$filePath3 = "uploads/gstfile/".$uploadData3['file_name'];
// rent agreement
$config4['upload_path'] = 'uploads/gstfile';
// $config4['allowed_types'] = 'jpg|jpeg|png';
$temp4 = explode(".", $_FILES["rent_agreement"]["name"]);
$newfilename4 = round(microtime(true)) . '.' . end($temp4);
$confi4['file_name'] = $newfilename4;
$this->load->library('upload',$config4);
$this->upload->initialize($config4);
$this->upload->do_upload('rent_agreement');
$uploadData4 = $this->upload->data();
$filePath4 = "uploads/gstfile/".$uploadData4['file_name'];
// coi
$config5['upload_path'] = 'uploads/gstfile';
// $config4['allowed_types'] = 'jpg|jpeg|png';
$temp5 = explode(".", $_FILES["coi"]["name"]);
$newfilename5 = round(microtime(true)) . '.' . end($temp5);
$confi5['file_name'] = $newfilename5;
$this->load->library('upload',$config5);
$this->upload->initialize($config5);
$this->upload->do_upload('coi');
$uploadData5 = $this->upload->data();
$filePath5 = "uploads/gstfile/".$uploadData5['file_name'];
// fetchlastid
$this->db->select('reg_id');
$this->db->order_by('reg_id',"desc");
$this->db->limit(1);
$data = $this->db->get("gst_registration")->result();
if (!empty($data))
{
  foreach ($data as $row):
    $reg_id = $row->reg_id;
  endforeach;
}else{
$reg_id ='1';
}
// echo $reg_id; exit();
$state=$this->input->post('state'); 
$district=$this->input->post('district');
$business_adress=$this->input->post('business_adress');

$nob=$this->input->post('nob');
$nop=$this->input->post('nop'); 
$firm_name=$this->input->post('firm_name'); 
$com_type=$this->input->post('com_type'); 
    $logme = [
     
      'state' => $state,
      'district'=> $district,
      'business_adress' =>  $business_adress, 
      'nob' => $nob,
    //   'auth_sign_file'=> $filePath,
    //   'moa_file'=> $filePath1,
    //   'aoa_file'=> $filePath2,
       'electricity_bill'=> $filePath3,
       'rent_agreement'=> $filePath4,
       'coi_file'=> $filePath5,
       'created_by' => $this->session->userdata('member_id'),
       'firm_name'=> $firm_name,
       'nop' => $nop,
       'com_type' =>$com_type

    ];
    // print_r($this->common_model->update($logme, "reg_id",  $reg_id , 'gst_registration'));exit;
  $this->common_model->update($logme, "reg_id",  $reg_id , 'gst_registration');

    
     
    
    redirect('gstfiling/gstform', 'refresh');
  }
}


            
public function gstreturn(){
$this->data['main_content'] = $this->load->view('gstreturn', $this->data, true);
$this->data['is_script'] = $this->load->view('script', $this->data, true);
$this->load->view('layout/index', $this->data);
 
} 

public function saleReturn(){
  $member_id = $this->session->userdata('member_id');
  $files = $_FILES;
     // photo
         $count = count($_FILES['userfile']['name']);
         for($i=0; $i<$count; $i++)
           {
     
             $_FILES['userfile']['name']= time().$files['userfile']['name'][$i];
           $_FILES['userfile']['type']= $files['userfile']['type'][$i];
           $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
           $_FILES['userfile']['error']= $files['userfile']['error'][$i];
           $_FILES['userfile']['size']= $files['userfile']['size'][$i];
           $config['upload_path'] = 'uploads/gstfile/';
           // $config['allowed_types'] = 'jpg|png|jpeg';
           $config['max_size'] = '2000000';
           $config['remove_spaces'] = true;
           $config['overwrite'] = false;
           $config['max_width'] = '';
           $config['max_height'] = '';
           $this->load->library('upload', $config);
           $this->upload->initialize($config);
           $this->upload->do_upload();
          
           $images[] = $_FILES['userfile']['name'];
           }
           $fileName = implode(',',$images);
           $filename1 = explode(',',$fileName);
 
              $type="Sales Return"; 
                 
               $data = array();
       for ($i = 0; $i < count($filename1); $i++)
       {
         
           $data[$i] = array(
               'type' => $type,
               'filepath' => $filename1[$i],
                'created_by' => $member_id
               
                         
           );
       }
         
        //  print_r($data); exit();
         // $query =$this->db->insert('gstreturn', $regdata);
        $query = $this->common_model->insert_Returnfile($data);
        if ($query) {
       
          redirect('gstfiling/gstreturn', 'refresh');
 
        }
 
 
 }

 public function purchaseReturn(){
 $member_id = $this->session->userdata('member_id');

  $files = $_FILES;
     // photo
         $count = count($_FILES['userfile']['name']);
         for($i=0; $i<$count; $i++)
           {
     
             $_FILES['userfile']['name']= time().$files['userfile']['name'][$i];
           $_FILES['userfile']['type']= $files['userfile']['type'][$i];
           $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
           $_FILES['userfile']['error']= $files['userfile']['error'][$i];
           $_FILES['userfile']['size']= $files['userfile']['size'][$i];
           $config['upload_path'] = 'uploads/gstfile/';
           // $config['allowed_types'] = 'jpg|png|jpeg';
           $config['max_size'] = '2000000';
           $config['remove_spaces'] = true;
           $config['overwrite'] = false;
           $config['max_width'] = '';
           $config['max_height'] = '';
           $this->load->library('upload', $config);
           $this->upload->initialize($config);
           $this->upload->do_upload();
          
           $images[] = $_FILES['userfile']['name'];
           }
           $fileName = implode(',',$images);
           $filename1 = explode(',',$fileName);
 
              $type="Purchase Return"; 
                
               $data = array();
       for ($i = 0; $i < count($filename1); $i++)
       {
         
           $data[$i] = array(
               'type' => $type,
               'filepath' => $filename1[$i],
                'created_by' => $member_id 
               
                         
           );
       }
         
         
        $query = $this->common_model->insert_Returnfile($data);
        if ($query) {
       
          redirect('gstfiling/gstreturn', 'refresh');
 
        }
 
 
 }
//  
public function gstreg_list(){
  // $this->data['breadcrumbs'] = [array('url' => base_url('superadmin'), 'name' => 'Superadmin'), array('url' => base_url('admin_list'), 'name' => 'List Of Admins')];
  // $this->data['param'] = $this->paremlink('/');
     $this->data['member_id'] = $this->security->xss_clean($_GET);

$this->data['main_content'] = $this->load->view('reglist', $this->data, true);
$this->data['is_script'] = $this->load->view('script', $this->data, true);
$this->load->view('layout/index', $this->data);
    
} 

public function get_gstreg_list()
{

  $uri = $this->security->xss_clean($_GET);
 

  if (!empty($uri)) {
  

    $output = array();


   

    $data = array();
    $sql = $this->db->query("select * from gst_registration");
    $filtered_rows = $sql->num_rows();
    $result = $sql->result_array();
   

    $i = 1;
    foreach ($result as $row) {
      $sub_array = array();
      
      $sub_array[] = $row['firm_name'];
      
       $sub_array[] = $row['state'];
      $sub_array[] = $row['district'];
     
      $sub_array[] = $row['com_type'];
   
      
$sub_array[] = '<a href="' . base_url('gstfiling/editRegdata?q=') . $row['reg_id'] . '"> <button type="button" class="btn btn-sm btn-success"  data-placement="bottom" title="Details Menu Information">Edit</button></a>';
$sub_array[] = '<a href="' . base_url('gstfiling/dir_list?q=') . $row['reg_id'] . '"> <button type="button" class="btn btn-sm btn-success"  data-placement="bottom" title="director">Directors</button></a>';
// $sub_array[] = '<a href="' . base_url('gstfiling/pdfdetails?q=') . $row['reg_id'] . '&d='. $row['created_by'] .'"> <button type="button" class="btn btn-sm btn-success"  data-placement="bottom" title="director">Directors</button></a>';
$sub_array[] = '<a href="' . base_url('gstfiling/reciept?q=') . $row['reg_id'] . '&d='. $row['created_by'] .'"> <button type="button" class="btn btn-sm btn-success"  data-placement="bottom" title="director">Directors</button></a>';

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

 
// endgstreg list

public function pdfdetails()
	{
    
    $uri = $this->security->xss_clean($_GET);
    if (isset($uri['q']) && !empty($uri['q'])) {
        $uid = $uri['q'];
        $uid1 = $uri['d'];
        $this->load->library('pdf');
   			$data['fetch_invoice_details']= $this->common_model->fetch_invoice_details($uid);
   			$data['fetch_u_details']= $this->common_model->fetch_u_details($this->session->userdata('user_id'));
			// $customer_id = $this->uri->segment(3);
			$html_content = '<h3 align="center" style="color:green;">Reciept</h3>';

 $html_content=$this->load->view('invoice',$data, true);

// echo $html_content;
     
			$this->pdf->loadHtml($html_content);
			$this->pdf->render();
			$this->pdf->stream("".$uid.".pdf", array("Attachment"=>0));
		}
	}

public function reciept()
	{
    
     $uri = $this->security->xss_clean($_GET);
    if (isset($uri['q']) && !empty($uri['q'])) {
        $uid = $uri['q'];
        $uid1 = $uri['d'];
        $this->load->library('pdf');
        	$fk_user_id='1';
   			$data['fetch_user_details_byid']= $this->common_model->fetch_user_details_byid($fk_user_id);
   	$id='4';
   			$data['fetch_aeps_bill_byid']= $this->common_model->fetch_aeps_bill_byid($id);
			// $customer_id = $this->uri->segment(3);
			$html_content = '<h3 align="center" style="color:green;">Reciept</h3>';

 $html_content=$this->load->view('bill',$data, true);

// echo $html_content; exit();
          

			$this->pdf->loadHtml($html_content);
			$this->pdf->render();
			$this->pdf->stream("".$uid.".pdf", array("Attachment" => false));
			exit();
		}
	}
// 
}
