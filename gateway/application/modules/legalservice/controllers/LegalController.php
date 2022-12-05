<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;

class LegalController extends Vite {


  public $data = array();

  public $client;


  public function __construct() {
      parent::__construct();
      $this->data['active'] = 'Legal Service';
      $this->data['serid'] = '51';
      $this->tnxType = 'lgsTx' ;
      $this->load->model('common_model');
      $this->load->model('users_model');
      $this->load->model('commission_model');
      $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));
  }
//commission ditribute
// dont touch this function without my permission 
public function commition_distribute($id,$service,$transection,$service_type)
{
    $parentsList = self::checkparent($id);
    // pre($parentsList);exit;
    $i = 0;
    $j = 0;
    $allwallet = [];
    foreach ($parentsList as $key => $value) {
      $commision = $this->commission_model->get_commision_by_role($value['role_id'],$service,$transection);
      if (!empty($commision)) {
        $userWallet = $this->common_model->get_user_wallet_balance($value['user_id']);  
        if ($userWallet != 'none') {
            pre($userWallet);
          if ($commision->c_flat== 1) {

            $amountc = $commision->g_commission;
            // print_r($userWallet->balance);
            // echo "</br>";
            $updateBalance = $userWallet->balance + $commision->g_commission;    // add commission
            // exit();
            $updateWallet = [
              'balance' => $updateBalance,
            ];
          
          } else {

            $amountc = $transection *  $commision->g_commission / 100;

            $updateBalance = $userWallet->balance + $amountc;    // add commission
            $updateWallet = [
              'balance' => $updateBalance,
            ];
          }
          if ($this->common_model->update($updateWallet, 'member_id', $value['user_id'], 'wallet')) {
            $message = [
              'msg' => 'Your wallet balance credited ' . $amountc . ' available balance is ' . $updateBalance,
              'user_id' => $value['user_id']
            ];
            $this->set_notification($message);
            $logme = [
              'wallet_id' =>$userWallet->wallet_id,
              'member_to' =>  $value['user_id'],
              'member_from' =>  $value['parent'],
              'amount' =>  $transection,
              //   'surcharge' => $data['surcharge'],
              'refrence' =>  'legal'.self::stan2(),
              'commission' =>$amountc,
              'service_id' =>$service,
              'stock_type' =>$service_type,
              'status' => 'success',
              'balance' =>  $userWallet->balance,
              'closebalance' => $updateBalance,
              'type' => 'credit',
              'mode' => $service_type,
              'bank' => $service_type,
              'narration' => $service_type.' Commision',
              'date' => date('Y-m-d'),
            ];
            $allwallet[$j] = $this->common_model->insert($logme, 'wallet_transaction');
            $j++;
          }
        } else {
          $message = [
            'msg' => 'User Wallet not Found',
            'user_id' => $value['user_id']
          ];
          $this->set_notification($message);
        }
      } else {
        $message = [
          'msg' => 'Commission Not Found',
          'user_id' => $value['user_id']
        ];
        $this->set_notification($message);
      }
    }
    return Json_encode($allwallet);
}

public function checkparent($id, &$parents = array(), $level = 1)
  {
    $data = $this->users_model->get_parent_recharge($id);
    // if($data->parent != 1){
    if (isset($data)) {
      $parents[$level]['user_id'] = $data->user_id;
      $parents[$level]['member_id'] = $data->member_id;
      $parents[$level]['parent'] = $data->parent;
      $parents[$level]['role_id'] = $data->role_id;
      // echo $data['parent'];

      self::checkparent($data->parent, $parents, $level + 1);
    }
    // }      
    return $parents;
  }
//commission distributor

  //gst registation
  public function index() {
    $this->data['param'] = $this->paremlink('add'); 
    $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));
    $this->data['main_content'] = $this->load->view('index', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  //gst return
  public function gst_return() {
    $this->data['param'] = $this->paremlink('add'); 
    $this->data['main_content'] = $this->load->view('gst_return', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  
  //itrbussiness
  public function itrbussiness() {
    $this->data['param'] = $this->paremlink('add'); 
    $this->data['main_content'] = $this->load->view('itrbussiness', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  
  //itrsalary
  public function itrsalary() {
    $this->data['param'] = $this->paremlink('add'); 
    $this->data['main_content'] = $this->load->view('itrsalary', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
   //msme
  public function msme() {
    $this->data['param'] = $this->paremlink('add'); 
    $this->data['main_content'] = $this->load->view('msme', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }

  public function gst_reg()
  {
    if($data = $this->security->xss_clean($_POST)){
      $set_price=$this->common_model->gst_price($data['service_id']);
      if(isset($set_price) && !empty($set_price))
       {
        $amount = $set_price[0]['price'];
       }
       else
       {
        $this->session->set_flashdata(
          array(
            'status' => 1,
            'msg' => "Price is not set"
          )
        );
        redirect('legal/gst', 'refresh');
        exit();
       }
     if($this->data['bal']>0 && $this->data['bal']>$amount){

           $config['upload_path'] = 'uploads/gst/documents/';//.$_POST['type'];
           $config['allowed_types'] = 'jpg|jpeg|png|gif';
           $config['max_size'] = '500'; // max_size in kb
           $config['overwrite'] = false;

           $this->load->library('upload',$config);

           if(!empty(isset($_FILES["certificate_of_incorporation"]["name"]))){

               $this->upload->do_upload('certificate_of_incorporation');
               $certificate = $this->upload->data();
               $this->upload->do_upload('electricity_bill');
               $electricity = $this->upload->data();
               $this->upload->do_upload('rent_agreement');
               $rent = $this->upload->data();
               
               $certificate_of_incorporation=$certificate['file_name']; 
                         
           }else{

               $this->upload->do_upload('electricity_bill');
               $electricity = $this->upload->data();
               $this->upload->do_upload('rent_agreement');
               $rent = $this->upload->data();
               $certificate_of_incorporation = '';
           }


           $gst_registration = [
                 'firm_name' => $data['firm_name'] ,
                 'member_id' => $this->session->userdata('member_id') ,
                 'referance_number' => self::stan() ,
                 'nature_of_properties' => $data['nature_property'] ,
                 'state' => $data['state'] ,
                 'district' => $data['district'] ,
                 'business_address' => $data['business_adress'] ,
                 'nature_of_business' => $data['nob'] ,
                 'company_type' => $data['type'] ,
                 'certificate_of_incorporation' => $certificate_of_incorporation ,
                 'electricity_bill' => $electricity['file_name'],
                 'rent_agreement' => $rent['file_name'] ,
                 'notation' => "GST Request Has Send",
                 'serviceid'=>$data['service_id'],
                 'service_type'=>$data['service_type'],
                 'created' => date("Y-m-d") ,

           ] ;
           if($gstid= $this->common_model->insert($gst_registration, 'gst_registration')){
             $i = 0 ;
             if(isset($data['name']) && !empty($data['name'])){              
                 foreach ($data['name'] as $row=>$name){
                   
                     $save = [

                               'gst_registration_id' => $gstid ,
                               'name'  => $data['name'][$i],
                               'email'  => $data['email'][$i] ,
                               'dob'  => $data['dob'][$i] ,
                               'mobile_number'  => $data['mobile'][$i] ,
                               'father_name'  => $data['father_name'][$i] ,
                               'adhar_number'  => $data['adhar_no'][$i] ,
                               'pan_number'  => $data['pan_no'][$i] ,
                               'address'  => $data['address'][$i] ,
                               'created'  => date("Y-m-d") ,

                             ];
                             

                         $director_id[$i]= $this->common_model->insert($save, 'directors');
                         $i++;
                 }
               }
           }

           $images = array();
           $files = $_FILES;
           $cpt = count($_FILES['director_photo']['name']);
           for($i=0; $i<$cpt; $i++){           

               $_FILES['director_photo']['name']= $files['director_photo']['name'][$i];
               $_FILES['director_photo']['type']= $files['director_photo']['type'][$i];
               $_FILES['director_photo']['tmp_name']= $files['director_photo']['tmp_name'][$i];
               $_FILES['director_photo']['error']= $files['director_photo']['error'][$i];
               $_FILES['director_photo']['size']= $files['director_photo']['size'][$i]; 

               $this->upload->initialize($this->set_upload_options('photo'));
               $this->upload->do_upload('director_photo');
               $images[] = $this->upload->data();
               $dataimage = [
                             'director_id' => $director_id[$i],
                             'type' => "photo" ,
                             'image_name' => $images[$i]['file_name'],
                             'created' => date('Y-m-d')
                           ];
               $this->common_model->insert($dataimage, 'directors_doc');            
               
               $_FILES['adhar_front']['name']= $files['adhar_front']['name'][$i];
               $_FILES['adhar_front']['type']= $files['adhar_front']['type'][$i];
               $_FILES['adhar_front']['tmp_name']= $files['adhar_front']['tmp_name'][$i];
               $_FILES['adhar_front']['error']= $files['adhar_front']['error'][$i];
               $_FILES['adhar_front']['size']= $files['adhar_front']['size'][$i];

               $this->upload->initialize($this->set_upload_options('adhar_front'));
               $this->upload->do_upload('adhar_front');
               $images[] = $this->upload->data();
               $dataimage = [
                             'director_id' => $director_id[$i],
                             'type' => "adhar_front" ,
                             'image_name' => $images[$i]['file_name'],
                             'created' => date('Y-m-d')
                           ];
               $this->common_model->insert($dataimage, 'directors_doc'); 

               $_FILES['adhar_back']['name']= $files['adhar_back']['name'][$i];
               $_FILES['adhar_back']['type']= $files['adhar_back']['type'][$i];
               $_FILES['adhar_back']['tmp_name']= $files['adhar_back']['tmp_name'][$i];
               $_FILES['adhar_back']['error']= $files['adhar_back']['error'][$i];
               $_FILES['adhar_back']['size']= $files['adhar_back']['size'][$i];

               $this->upload->initialize($this->set_upload_options('adhar_back'));
               $this->upload->do_upload('adhar_back');
               $images[] = $this->upload->data();
               $dataimage = [
                             'director_id' => $director_id[$i],
                             'type' => "adhar_back" ,
                             'image_name' => $images[$i]['file_name'],
                             'created' => date('Y-m-d')
                           ];
               $this->common_model->insert($dataimage, 'directors_doc'); 

               $_FILES['pan_file']['name']= $files['pan_file']['name'][$i];
               $_FILES['pan_file']['type']= $files['pan_file']['type'][$i];
               $_FILES['pan_file']['tmp_name']= $files['pan_file']['tmp_name'][$i];
               $_FILES['pan_file']['error']= $files['pan_file']['error'][$i];
               $_FILES['pan_file']['size']= $files['pan_file']['size'][$i];

               $this->upload->initialize($this->set_upload_options('pan'));
               $this->upload->do_upload('pan_file');
               $images[] = $this->upload->data();
               $dataimage = [
                           'director_id' => $director_id[$i],
                           'type' => "pan" ,
                           'image_name' => $images[$i]['file_name'],
                           'created' => date('Y-m-d')
                         ];
               $this->common_model->insert($dataimage, 'directors_doc'); 

               $_FILES['bank_statement']['name']= $files['bank_statement']['name'][$i];
               $_FILES['bank_statement']['type']= $files['bank_statement']['type'][$i];
               $_FILES['bank_statement']['tmp_name']= $files['bank_statement']['tmp_name'][$i];
               $_FILES['bank_statement']['error']= $files['bank_statement']['error'][$i];
               $_FILES['bank_statement']['size']= $files['bank_statement']['size'][$i];

               $this->upload->initialize($this->set_upload_options('bank'));
               $this->upload->do_upload('bank_statement');
               $images[] = $this->upload->data();
               $dataimage = [
                             'director_id' => $director_id[$i],
                             'type' => "bank" ,
                             'image_name' => $images[$i]['file_name'],
                             'created' => date('Y-m-d')
                           ];
               $this->common_model->insert($dataimage, 'directors_doc'); 

           }

          self::pay_amount($amount,$gstid,$data['service_id'],$data['service_type']);;
          self::commition_distribute($this->session->userdata('user_id'),$data['service_id'],$amount,$data['service_type']); 
             $this->session->set_flashdata(
               array(
                 'status' => 1,
                 'msg' => " Gst Registration Successfully Submit"
               )
             );
             redirect('legal/gst', 'refresh');
     }else{

       $this->session->set_flashdata(
         array(
           'error' => 2,
           'msg' => "Wallet balance Low Recharge Your Wallet"
         )
       );
       redirect('legalservice/LegalController', 'refresh');
 
     }

   }else{

     $this->session->set_flashdata(
       array(
         'status' => 0,
         'msg' => "Something Went Wrong"
       )
     );
     redirect('legalservice/LegalController', 'refresh');
   }
  }

  //gst company registration
  public function cmp_reg()
  {
    if($data = $this->security->xss_clean($_POST)){
      $amount=str_replace(array('₹',' '),'',$data['amount']);
     if(isset($amount) && !empty($amount))
     {
      $amount = $amount;
     }
     else
     {
      $this->session->set_flashdata(
        array(
          'status' => 1,
          'msg' => "Price is not set"
        )
      );
      redirect('legal/gst/company_registation', 'refresh');
      exit();
     }
     if($this->data['bal']>0 && $this->data['bal']>$amount){

           $config['upload_path'] = 'uploads/gst/documents/';//.$_POST['type'];
           $config['allowed_types'] = 'jpg|jpeg|png|gif';
           $config['max_size'] = '500'; // max_size in kb
           $config['overwrite'] = false;

           $this->load->library('upload',$config);
           $this->upload->do_upload('electrict_bill');
           
           $electricity = $this->upload->data();
           $cmp_registration = [
                 'member_id' => $this->session->userdata('member_id') ,
                 'referance_number' => self::stan(),
                 'serviceid'=>$data['service_id'],
                 'service_type'=>$data['service_type'],
                 'business_address'=>$data['Address_company'],
                 'state' => $data['state'],
                 'electricity_bill' => $electricity['file_name'],
                 'notation' => "Company Registration Request Has Send",
                 'created' => date("Y-m-d") ,

           ] ;
           if($gstid= $this->common_model->insert($cmp_registration, 'gst_registration')){
             $i = 0 ;
             if(isset($data['name']) && !empty($data['name'])){              
                 foreach ($data['name'] as $row=>$name){
                   
                     $save = [

                               'gst_registration_id' => $gstid ,
                               'name'  => $data['name'][$i],
                               'email'  => $data['email'][$i] ,
                               'dob'  => $data['dob'][$i] ,
                               'mobile_number'  => $data['mobile'][$i] ,
                               'father_name'  => $data['father_name'][$i] ,
                               'adhar_number'  => $data['adhar_no'][$i] ,
                               'pan_number'  => $data['pan_no'][$i] ,
                               'address'  => $data['address'][$i] ,
                               'created'  => date("Y-m-d") ,

                             ];
                             

                         $director_id[$i]= $this->common_model->insert($save, 'directors');
                         $i++;
                 }
               }
           }

           $images = array();
           $files = $_FILES;
           $cpt = count($_FILES['director_photo']['name']);
           for($i=0;$i<$cpt; $i++){           

               $_FILES['director_photo']['name']= $files['director_photo']['name'][$i];
               $_FILES['director_photo']['type']= $files['director_photo']['type'][$i];
               $_FILES['director_photo']['tmp_name']= $files['director_photo']['tmp_name'][$i];
               $_FILES['director_photo']['error']= $files['director_photo']['error'][$i];
               $_FILES['director_photo']['size']= $files['director_photo']['size'][$i]; 

               $this->upload->initialize($this->set_upload_options('photo'));
               $this->upload->do_upload('director_photo');
               $images[] = $this->upload->data();
               $dataimage = [
                             'director_id' => $director_id[$i],
                             'type' => "photo" ,
                             'image_name' => $images[$i]['file_name'],
                             'created' => date('Y-m-d')
                           ];
               $this->common_model->insert($dataimage, 'directors_doc');            
               
               $_FILES['adhar_front']['name']= $files['adhar_front']['name'][$i];
               $_FILES['adhar_front']['type']= $files['adhar_front']['type'][$i];
               $_FILES['adhar_front']['tmp_name']= $files['adhar_front']['tmp_name'][$i];
               $_FILES['adhar_front']['error']= $files['adhar_front']['error'][$i];
               $_FILES['adhar_front']['size']= $files['adhar_front']['size'][$i];

               $this->upload->initialize($this->set_upload_options('adhar_front'));
               $this->upload->do_upload('adhar_front');
               $images[] = $this->upload->data();
               $dataimage = [
                             'director_id' => $director_id[$i],
                             'type' => "adhar_front" ,
                             'image_name' => $images[$i]['file_name'],
                             'created' => date('Y-m-d')
                           ];
               $this->common_model->insert($dataimage, 'directors_doc'); 

               $_FILES['adhar_back']['name']= $files['adhar_back']['name'][$i];
               $_FILES['adhar_back']['type']= $files['adhar_back']['type'][$i];
               $_FILES['adhar_back']['tmp_name']= $files['adhar_back']['tmp_name'][$i];
               $_FILES['adhar_back']['error']= $files['adhar_back']['error'][$i];
               $_FILES['adhar_back']['size']= $files['adhar_back']['size'][$i];

               $this->upload->initialize($this->set_upload_options('adhar_back'));
               $this->upload->do_upload('adhar_back');
               $images[] = $this->upload->data();
               $dataimage = [
                             'director_id' => $director_id[$i],
                             'type' => "adhar_back" ,
                             'image_name' => $images[$i]['file_name'],
                             'created' => date('Y-m-d')
                           ];
               $this->common_model->insert($dataimage, 'directors_doc'); 

               $_FILES['pan_file']['name']= $files['pan_file']['name'][$i];
               $_FILES['pan_file']['type']= $files['pan_file']['type'][$i];
               $_FILES['pan_file']['tmp_name']= $files['pan_file']['tmp_name'][$i];
               $_FILES['pan_file']['error']= $files['pan_file']['error'][$i];
               $_FILES['pan_file']['size']= $files['pan_file']['size'][$i];

               $this->upload->initialize($this->set_upload_options('pan'));
               $this->upload->do_upload('pan_file');
               $images[] = $this->upload->data();
               $dataimage = [
                           'director_id' => $director_id[$i],
                           'type' => "pan" ,
                           'image_name' => $images[$i]['file_name'],
                           'created' => date('Y-m-d')
                         ];
               $this->common_model->insert($dataimage, 'directors_doc'); 

               $_FILES['bank_statement']['name']= $files['bank_statement']['name'][$i];
               $_FILES['bank_statement']['type']= $files['bank_statement']['type'][$i];
               $_FILES['bank_statement']['tmp_name']= $files['bank_statement']['tmp_name'][$i];
               $_FILES['bank_statement']['error']= $files['bank_statement']['error'][$i];
               $_FILES['bank_statement']['size']= $files['bank_statement']['size'][$i];

               $this->upload->initialize($this->set_upload_options('bank'));
               $this->upload->do_upload('bank_statement');
               $images[] = $this->upload->data();
               $dataimage = [
                             'director_id' => $director_id[$i],
                             'type' => "bank" ,
                             'image_name' => $images[$i]['file_name'],
                             'created' => date('Y-m-d')
                           ];
               $this->common_model->insert($dataimage, 'directors_doc'); 
               $_FILES['Voter_License']['name']= $files['Voter_License']['name'][$i];
               $_FILES['Voter_License']['type']= $files['Voter_License']['type'][$i];
               $_FILES['Voter_License']['tmp_name']= $files['Voter_License']['tmp_name'][$i];
               $_FILES['Voter_License']['error']= $files['Voter_License']['error'][$i];
               $_FILES['Voter_License']['size']= $files['Voter_License']['size'][$i];
               $this->upload->initialize($this->set_upload_options('voter'));
               $this->upload->do_upload('Voter_License');
               $images[] = $this->upload->data();
               $dataimage = [
                             'director_id' => $director_id[$i],
                             'type' => "voter" ,
                             'image_name' => $images[$i]['file_name'],
                             'created' => date('Y-m-d')
                           ];
               $this->common_model->insert($dataimage, 'directors_doc'); 

           }

           self::pay_amount($amount,$gstid,$data['service_id'],$data['service_type']);;
           self::commition_distribute($this->session->userdata('user_id'),$data['service_id'],$amount,$data['service_type']); 
             $this->session->set_flashdata(
               array(
                 'status' => 1,
                 'msg' => " Company Registration Successfully Submit"
               )
             );
             redirect('legal/gst/company_registation', 'refresh');
     }else{

       $this->session->set_flashdata(
         array(
           'error' => 2,
           'msg' => "Wallet balance Low Recharge Your Wallet"
         )
       );
       redirect('legal/gst/company_registation', 'refresh');
 
     }

   }else{

     $this->session->set_flashdata(
       array(
         'status' => 0,
         'msg' => "Something Went Wrong"
       )
     );
     redirect('legal/gst/company_registation', 'refresh');
   }
  }

  //gst Return
  public function gst_return_submit()
  {
    $data = $this->security->xss_clean($_POST);
    $set_price=$this->common_model->gst_price($data['service_id']);
    if(isset($set_price) && !empty($set_price))
     {
      $amount = $set_price[0]['price'];
     }
     else
     {
      $this->session->set_flashdata(
        array(
          'status' => 1,
          'msg' => "Price is not set"
        )
      );
      redirect('legal/gst/return', 'refresh');
      exit();
     }
    if($this->data['bal']>0 &&$this->data['bal']>$amount){
      $config['upload_path'] = 'uploads/gst/gst_return_doc/';//.$_POST['type'];
      $config['allowed_types'] = 'jpg|jpeg|png|gif';
      $config['max_size'] = '500'; // max_size in kb
      $config['overwrite'] = false;
      $this->load->library('upload',$config);
      if(isset($data['password']) && isset($data['name']))
      {
         $gst_return = [
             'gst_no'=>$data['gst_number'],
             'name'=>$data['name'],
             'member_id'=>$this->session->userdata('member_id'),
             'status'=>'Pending',
             'password'=>$data['password'],
             'mobile'=>$data['mobile_number'],
             'referance_number	'=>'gtr'.self::stan() ,
             'notation' => "GST Return  Request Has Send",
             'created' => date("Y-m-d h-i-s") ,
            ];  
      }
      else
      {
           $gst_return = [
             'gst_no'=>$data['gst_number'],
             'name'=>'',
             'password'=>'',
              'member_id'=>$this->session->userdata('member_id'),
             'status'=>'Pending',
             'mobile'=>$data['mobile_number'],
             'referance_number	'=>'gtr'.self::stan() ,
             'notation' => "GST Return  Request Has Send",
             'created' => date("Y-m-d h-i-s") ,
            ]; 
      }
      if($gstid= $this->common_model->insert($gst_return, 'gst_return')){
        $images = array();
        $files = $_FILES;
        $cpt = count($_FILES['purchase']['name']);
        $cpts = count($_FILES['sale']['name']);
        if($cpt==$cpts)
        {
            for($i=0;$i<$cpt;$i++)
            {
                 $_FILES['purchase']['name']= $files['purchase']['name'][$i];
                 $_FILES['purchase']['type']= $files['purchase']['type'][$i];
                 $_FILES['purchase']['tmp_name']= $files['purchase']['tmp_name'][$i];
                 $_FILES['purchase']['error']= $files['purchase']['error'][$i];
                 $_FILES['purchase']['size']= $files['purchase']['size'][$i]; 
                 $this->upload->initialize($this->set_upload_options('gst_return_doc'));
                 $this->upload->do_upload('purchase');
                 $images1[] = $this->upload->data();
                 $_FILES['sale']['name']= $files['sale']['name'][$i];
                 $_FILES['sale']['type']= $files['sale']['type'][$i];
                 $_FILES['sale']['tmp_name']= $files['sale']['tmp_name'][$i];
                 $_FILES['sale']['error']= $files['sale']['error'][$i];
                 $_FILES['sale']['size']= $files['sale']['size'][$i]; 
                 $this->upload->initialize($this->set_upload_options('gst_return_doc'));
                 $this->upload->do_upload('sale');
                 $images2[] = $this->upload->data();
                 $dataimage = [
                        'gst_return_id' => $gstid,
                        'Purchase' => $images1[$i]['file_name'] ,
                        'Sale' => $images2[$i]['file_name'],
                        'created' => date("Y-m-d h-i-s")
                      ];
                 $this->common_model->insert($dataimage, 'gst_return_doc');
            }
        }
      }
      self::pay_amount($amount,$gstid,$data['service_id'],$data['service_type']);
      self::commition_distribute($this->session->userdata('user_id'),$data['service_id'],$amount,$data['service_type']); 
        $this->session->set_flashdata(
          array(
            'status' => 1,
            'msg' => " Gst Return Successfully Submit"
          )
        );
        redirect('legal/gst/return', 'refresh');
    }else{
     $this->session->set_flashdata(
       array(
       'error' => 2,
       'msg' => "Wallet balance Low Recharge Your Wallet"
      )
     );
      redirect('legal/gst/return', 'refresh');
    }
  }

  //itrbussiness
  public function itr_bussiness()
  {
    if($data = $this->security->xss_clean($_POST)){
      $set_price=$this->common_model->gst_price($data['service_id']);
      if(isset($set_price) && !empty($set_price))
       {
        $amount = $set_price[0]['price'];
       }
       else
       {
        $this->session->set_flashdata(
          array(
            'status' => 1,
            'msg' => "Price is not set"
          )
        );
        redirect('legal/gst/itr/bussiness', 'refresh');
        exit();
       }
     if($this->data['bal']>0 && $this->data['bal']>$amount){

           $config['upload_path'] = 'uploads/gst/documents/';//.$_POST['type'];
           $config['allowed_types'] = 'jpg|jpeg|png|gif';
           $config['max_size'] = '500'; // max_size in kb
           $config['overwrite'] = false;

           $this->load->library('upload',$config);
           $this->upload->do_upload('pan');
           $pan = $this->upload->data();
           $this->upload->do_upload('Adharf');
           $adharf = $this->upload->data();
           $this->upload->do_upload('Adharb');
           $adharb = $this->upload->data();
           $this->upload->do_upload('photo');
           $photo = $this->upload->data();
           $this->upload->do_upload('bussiness');
           $bussiness = $this->upload->data();
           $cmp_registration = [
                 'member_id' => $this->session->userdata('member_id') ,
                 'referance_number' => self::stan(),
                 'serviceid'=>$data['service_id'],
                 'service_type'=>$data['service_type'],
                 'business_details' => $data['bussinessdetails'] ,
                 'annualincome' => $data['annualincome'],
                 'email'=>$data['email'],
                 'mobile'=>$data['mobile'],
                 'pancard'=>$pan['file_name'],
                 'adharcardb'=>$adharb['file_name'],
                 'adharcardf'=>$adharf['file_name'],
                 'photo'=>$photo['file_name'],
                 'business_details_file'=>$bussiness['file_name'],
                 'notation' => "Itr Bussiness Request Has Send",
                 'created' => date("Y-m-d"),
           ] ;
           $gstid=$this->common_model->insert($cmp_registration, 'gst_registration');
           self::pay_amount($amount,$gstid,$data['service_id'],$data['service_type']);
           self::commition_distribute($this->session->userdata('user_id'),$data['service_id'],$amount,$data['service_type']); 

             $this->session->set_flashdata(
               array(
                 'status' => 1,
                 'msg' => "Successfully Submit"
               )
             );
             redirect('legal/gst/itr/bussiness', 'refresh');
     }else{

       $this->session->set_flashdata(
         array(
           'error' => 2,
           'msg' => "Wallet balance Low Recharge Your Wallet"
         )
       );
       redirect('legal/gst/itr/bussiness', 'refresh');
 
     }

   }else{

     $this->session->set_flashdata(
       array(
         'status' => 0,
         'msg' => "Something Went Wrong"
       )
     );
     redirect('legal/gst/itr/bussiness', 'refresh');
   }
  }
  //itrsalary
public function itr_salary()
{
    if($data = $this->security->xss_clean($_POST)){
      $set_price=$this->common_model->gst_price($data['service_id']);
      if(isset($set_price) && !empty($set_price))
       {
        $amount = $set_price[0]['price'];
       }
       else
       {
        $this->session->set_flashdata(
          array(
            'status' => 1,
            'msg' => "Price is not set"
          )
        );
        redirect('legal/gst/itr/salary', 'refresh');
        exit();
       }
     if($this->data['bal']>0 && $this->data['bal']>$amount){
           $config['upload_path'] = 'uploads/gst/documents/';//.$_POST['type'];
           $config['allowed_types'] = 'jpg|jpeg|png|gif';
           $config['max_size'] = '500'; // max_size in kb
           $config['overwrite'] = false;
           $this->load->library('upload',$config);
           $this->upload->do_upload('salary');
           $salary = $this->upload->data();
           $this->upload->do_upload('pan');
           $pan = $this->upload->data();
           $this->upload->do_upload('Adharf');
           $Adharf = $this->upload->data();
           $this->upload->do_upload('Adharb');
           $Adharb = $this->upload->data();
           $this->upload->do_upload('photo');
           $photo = $this->upload->data();
           $cmp_registration = [
                 'member_id' => $this->session->userdata('member_id') ,
                 'referance_number' => self::stan(),
                 'serviceid'=>$data['service_id'],
                 'service_type'=>$data['service_type'],
                 'email'=>$data['email'],
                 'mobile'=>$data['mobile'],
                 'annualincome'=>$data['annualincome'],
                 'salary'=>$salary['file_name'],
                 'pancard'=>$pan['file_name'],
                 'adharcardf'=>$Adharf['file_name'],
                 'adharcardb'=>$Adharb['file_name'],
                 'photo'=>$photo['file_name'],
                 'notation' => "ITR Salary Request Has Send",
                 'created' => date("Y-m-d"),
           ] ;
           $gstid= $this->common_model->insert($cmp_registration, 'gst_registration');
           self::pay_amount($amount,$gstid,$data['service_id'],$data['service_type']);
           self::commition_distribute($this->session->userdata('user_id'),$data['service_id'],$amount,$data['service_type']); 
             $this->session->set_flashdata(
               array(
                 'status' => 1,
                 'msg' => "Successfully Submit"
               )
             );
             redirect('legal/gst/itr/salary', 'refresh');
     }else{

       $this->session->set_flashdata(
         array(
           'error' => 2,
           'msg' => "Wallet balance Low Recharge Your Wallet"
         )
       );
       redirect('legal/gst/itr/salary', 'refresh');
 
     }

   }else{

     $this->session->set_flashdata(
       array(
         'status' => 0,
         'msg' => "Something Went Wrong"
       )
     );
     redirect('legal/gst/itr/salary', 'refresh');
   };
}
//msme
public function msme_regi()
{
      if($data = $this->security->xss_clean($_POST)){
        $set_price=$this->common_model->gst_price($data['service_id']);
      if(isset($set_price) && !empty($set_price))
       {
        $amount = $set_price[0]['price'];
       }
       else
       {
        $this->session->set_flashdata(
          array(
            'status' => 1,
            'msg' => "Price is not set"
          )
        );
        redirect('legal/gst/msme/registration', 'refresh');
        exit();
       }
       if($this->data['bal']>0 && $this->data['bal']>$amount){
             $config['upload_path'] = 'uploads/gst/documents/';//.$_POST['type'];
             $config['allowed_types'] = 'jpg|jpeg|png|gif';
             $config['max_size'] = '500'; // max_size in kb
             $config['overwrite'] = false;
             $this->load->library('upload',$config);
             $this->upload->do_upload('bussinessdetails');
             $bussinessdetails = $this->upload->data();
             $this->upload->do_upload('pan');
             $pan = $this->upload->data();
             $this->upload->do_upload('Adharf');
             $Adharf = $this->upload->data();
             $this->upload->do_upload('Adharb');
             $Adharb = $this->upload->data();
             $this->upload->do_upload('photo');
             $photo = $this->upload->data();
             $this->upload->do_upload('bankstatement');
             $bankstatement = $this->upload->data();
             $cmp_registration = [
                   'member_id' => $this->session->userdata('member_id') ,
                   'referance_number' => self::stan(),
                   'serviceid'=>$data['service_id'],
                   'service_type'=>$data['service_type'],
                   'email'=>$data['email'],
                    'business_details'=>$data['bussinessdetails'],
                   'mobile'=>$data['mobile'],
                   'pancard'=>$pan['file_name'],
                   'adharcardf'=>$Adharf['file_name'],
                   'adharcardb'=>$Adharb['file_name'],
                   'photo'=>$photo['file_name'],
                   'business_details_file'=> $bankstatement['file_name'],
                   'notation' => "MSME Request Has Send",
                   'created' => date("Y-m-d"),
             ] ;
             $gstid= $this->common_model->insert($cmp_registration, 'gst_registration');
             self::pay_amount($amount,$gstid,$data['service_id'],$data['service_type']);
             self::commition_distribute($this->session->userdata('user_id'),$data['service_id'],$amount,$data['service_type']); 
               $this->session->set_flashdata(
                 array(
                   'status' => 1,
                   'msg' => "Successfully Submit"
                 )
               );
               redirect('legal/gst/msme/registration', 'refresh');
       }else{
  
         $this->session->set_flashdata(
           array(
             'error' => 2,
             'msg' => "Wallet balance Low Recharge Your Wallet"
           )
         );
         redirect('legal/gst/msme/registration', 'refresh');
   
       }
  
     }else{
  
       $this->session->set_flashdata(
         array(
           'status' => 0,
           'msg' => "Something Went Wrong"
         )
       );
       redirect('legal/gst/msme/registration', 'refresh');
     };
}

  private function stan( ) {
     
    date_default_timezone_set("Asia/Calcutta");
    $today = date("H");
    $year = date("Y"); 
    $year =  $year;
    $year = substr( $year, -1);   
    $daycount =  date("z")+1;
    $ref = $year . $daycount. $today. mt_rand(100000, 999999);
    return $ref;
  }


  public function addForm() {
    
    if ($data = $this->security->xss_clean($_POST)) {

      $this->data['type'] = $data['company_type'];

      echo $this->load->view('gstform', $this->data, true);

    }else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
  
  }

  private function set_upload_options($type){   
    $config = array();
    $config['upload_path'] = 'uploads/gst/'.$type."/";
    $config['allowed_types'] = 'jpeg|jpg|png';
    $config['max_size']      = '500';
    $config['overwrite']     = false;

    return $config;
  }

  
  private function pay_amount($amount,$rrn,$serviceid,$servicetype){

      $userWallet = $this->common_model->get_user_wallet_balance($this->session->userdata('user_id'));

        if ($userWallet != 'none') {
                      
            $updateBalance = $userWallet->balance - $amount;    //Deduct balance
            
            $updateWallet = [
                                'balance' => $updateBalance,
                            ];

                            $logme = [
                              'wallet_id' => $userWallet->wallet_id,
                              'member_to' =>  $this->session->userdata('user_id'),
                              'amount' =>  $amount,
                              'surcharge' => 0,
                              'refrence' =>substr($servicetype,3).self::stan2(),
                              'service_id' => $serviceid,
                              'stock_type'=> $servicetype,
                              'status' => 'success',
                              'balance' =>  $userWallet->balance,
                              'closebalance' => $updateBalance,
                              'type' => 'debit',
                              'mode' => 'Legal',
                              'bank' =>  'Legal',
                              'narration' => 'Legal Amount',
                              'date'=> date('Y-m-d'),
                            ];
        

            if($this->common_model->insert($logme, 'wallet_transaction')) { //update deducted balance
                                        
                $message = [
                              'msg' => 'Your wallet balance debited Rs. ' . $amount. ' available balance is ' . $updateBalance,
                              'user_id' => $this->session->userdata('user_id')
                            ];

                  $this->set_notification($message);

                  return $this->common_model->update($updateWallet, 'member_id',$this->session->userdata('user_id'), 'wallet') ;
            }
                                
        } 

  }

  public function list(){
    
    $this->data['main_content'] = $this->load->view('list', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);

  }

public function list_return(){
    
    $this->data['main_content'] = $this->load->view('list_return', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
}

public function list_itr_bussiness(){
    
  $this->data['main_content'] = $this->load->view('itr_bussiness_list', $this->data, true);
  $this->data['is_script'] = $this->load->view('script', $this->data, true);
  $this->load->view('layout/index', $this->data);
}

public function list_itr_salary(){
    
  $this->data['main_content'] = $this->load->view('itr_salary_list', $this->data, true);
  $this->data['is_script'] = $this->load->view('script', $this->data, true);
  $this->load->view('layout/index', $this->data);
}

public function list_msme(){
    
  $this->data['main_content'] = $this->load->view('msme_list', $this->data, true);
  $this->data['is_script'] = $this->load->view('script', $this->data, true);
  $this->load->view('layout/index', $this->data);
}

public function list_company(){
  $this->data['main_content'] = $this->load->view('company_list', $this->data, true);
  $this->data['is_script'] = $this->load->view('script', $this->data, true);
  $this->load->view('layout/index', $this->data);
}
 
  //gst gst registration
  public function get_gstlist() {
    
    $uri = $this->security->xss_clean($_GET);
    
    if (!empty($uri)) {

      $query = '';
      $output = array();
      $list = $uri['list'];
      $data = array();
      
      if (isAdmin($this->session->userdata('user_roles'))) {

        $query .= "SELECT * FROM gst_registration where serviceid=".$_GET['service']." ";

        $recordsFiltered = $this->users_model->row_count($query);

      }else{

        $query .= "SELECT * FROM gst_registration where serviceid=".$_GET['service']." AND member_id = '{$this->session->userdata('member_id')}'";

        $recordsFiltered = $this->users_model->row_count($query);

      }

      if(isset($_GET["searchtype"]) && !empty($_GET["searchtype"])){
        $query .= " AND gst_id .".$_GET["searchByCat"]." = ". $_GET["searchValue"]." ";         
      }
      if(!empty($_GET["order"])){
        $query .= 'ORDER BY '.$_GET['order']['0']['column'].' '.$_GET['order']['0']['dir'].' ';
      }else{
        $query .= 'ORDER BY gst_registration.created DESC ';
      }
      if($_GET["length"] != -1){
        $query .= 'LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];
      }

      $sql = $this->db->query($query);
      $result = $sql->result_array();
      $i = 1;

      foreach ($result as $row) {

        $sub_array = array();

        if ($row['status'] == 'Success') {

          $status = '<span class="badge badge-success">Success</span>';

        } elseif ($row['status'] == 'Reject') {

          $status = '<span class="badge badge-danger">Reject</span>';

        }elseif ($row['status'] == 'Accept') {

          $status = '<span class="badge badge-primary">Accept</span>';

        }else{

          $status = '<span class="badge badge-warning">Pending</span>';

        } 
        if($row['status'] == 'Accept')
        {
          $button='<a href="' . base_url('legal/gst/view?q=') . $row['gst_id'] . '"> <button type="button" class="btn btn-sm btn-secondary"  data-placement="bottom" title="View GST Form Information"><i class="fa fa-pencil-alt"></i></button></a>';
        }
        else
        {
          $button='<div class="checkboxpre"><input class="from-controle checksub" type="checkbox" name="check" value="'.$row['gst_id'].'"></input><a href="' . base_url('legal/gst/view?q=') . $row['gst_id'] . '"> <button type="button" class="btn btn-sm btn-secondary"  data-placement="bottom" title="View GST Form Information"><i class="fa fa-pencil-alt"></i></button></a>';
        }

        $sub_array[] = $button;
        $sub_array[] = $row['referance_number'];
        $sub_array[] = $row['member_id'];
        $sub_array[] = $row['firm_name'];
        $sub_array[] = $row['company_type'];
        $sub_array[] = $row['nature_of_properties'];
        $sub_array[] = $row['district'];
        $sub_array[] = $row['state'];
        $sub_array[] = $row['nature_of_business'];
        $sub_array[] = $row['business_address'];
        $sub_array[] = $status;
        $sub_array[] = $row['notation'];
        $sub_array[] = $row['created'];

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
  //gst return 
  public function get_gstlist_return() {
    
    $uri = $this->security->xss_clean($_GET);
    
    if (!empty($uri)) {

      $query = '';
      $output = array();
      $list = $uri['list'];
      $data = array();
      
      if (isAdmin($this->session->userdata('user_roles'))) {

        $query .= "SELECT * FROM ".$_GET['service']." ";

        $recordsFiltered = $this->users_model->row_count($query);

      }else{

        $query .= "SELECT * FROM ".$_GET['service']." WHERE member_id = '{$this->session->userdata('member_id')}'";

        $recordsFiltered = $this->users_model->row_count($query);

      }

      if(isset($_GET["searchtype"]) && !empty($_GET["searchtype"])){
        $query .= " AND gst_id .".$_GET["searchByCat"]." = ". $_GET["searchValue"]." ";         
      }
      if(!empty($_GET["order"])){
        $query .= 'ORDER BY '.$_GET['order']['0']['column'].' '.$_GET['order']['0']['dir'].' ';
      }else{
        $query .= 'ORDER BY gst_return .created DESC ';
      }
      if($_GET["length"] != -1){
        $query .= 'LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];
      }

      $sql = $this->db->query($query);
      $result = $sql->result_array();
      $i = 1;

      foreach ($result as $row) {

        $sub_array = array();

        if ($row['status'] == 'Success') {

          $status = '<span class="badge badge-success">Success</span>';

        } elseif ($row['status'] == 'Reject') {

          $status = '<span class="badge badge-danger">Reject</span>';

        }elseif ($row['status'] == 'Accept') {

          $status = '<span class="badge badge-primary">Accept</span>';

        }else{

          $status = '<span class="badge badge-warning">Pending</span>';

        } 
        if($row['status'] == 'Accept')
        {
          $button='<a href="' . base_url('legal/gst/view_return?q=') . $row['id'] . '"> <button type="button" class="btn btn-sm btn-secondary"  data-placement="bottom" title="View GST Form Information"><i class="fa fa-pencil-alt"></i></button></a>';
        }
        else
        {
          $button='<div class="checkboxpre"><input class="from-controle checksub" type="checkbox" name="check" value="'.$row['id'].'"></input><a href="' . base_url('legal/gst/view_return?q=') . $row['id'] . '"> <button type="button" class="btn btn-sm btn-secondary"  data-placement="bottom" title="View GST Form Information"><i class="fa fa-pencil-alt"></i></button></a>';
        }

        $sub_array[] = $button;
        $sub_array[] = $row['gst_no'];
        $sub_array[] = $row['member_id'];
        $sub_array[] = $row['name'];
        $sub_array[] = $row['password'];
        $sub_array[] = $row['mobile'];
        $sub_array[] = $row['referance_number'];
        $sub_array[] = $status;
        $sub_array[] = $row['notation'];
        $sub_array[] = $row['created'];
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

  //itr bussiness
  public function itr_bussiness_list() {
    
    $uri = $this->security->xss_clean($_GET);
    
    if (!empty($uri)) {

      $query = '';
      $output = array();
      $list = $uri['list'];
      $data = array();
      
      if (isAdmin($this->session->userdata('user_roles'))) {

        $query .="SELECT * FROM gst_registration where serviceid=".$_GET['service']." ";

        $recordsFiltered = $this->users_model->row_count($query);

      }else{

        $query .= "SELECT * FROM gst_registration where serviceid=".$_GET['service']." AND member_id = '{$this->session->userdata('member_id')}'";

        $recordsFiltered = $this->users_model->row_count($query);

      }

      if(isset($_GET["searchtype"]) && !empty($_GET["searchtype"])){
        $query .= " AND gst_id .".$_GET["searchByCat"]." = ". $_GET["searchValue"]." ";         
      }
      if(!empty($_GET["order"])){
        $query .= 'ORDER BY '.$_GET['order']['0']['column'].' '.$_GET['order']['0']['dir'].' ';
      }else{
        $query .= 'ORDER BY gst_registration.created DESC ';
      }
      if($_GET["length"] != -1){
        $query .= 'LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];
      }

      $sql = $this->db->query($query);
      $result = $sql->result_array();
      $i = 1;

      foreach ($result as $row) {

        $sub_array = array();

        if ($row['status'] == 'Success') {

          $status = '<span class="badge badge-success">Success</span>';

        } elseif ($row['status'] == 'Reject') {

          $status = '<span class="badge badge-danger">Reject</span>';

        }elseif ($row['status'] == 'Accept') {

          $status = '<span class="badge badge-primary">Accept</span>';

        }else{

          $status = '<span class="badge badge-warning">Pending</span>';

        } 
        if($row['status'] == 'Accept')
        {
          $button='<a href="' . base_url('legal/gst/itr_bussiness_view?q=') . $row['gst_id'] . '"> <button type="button" class="btn btn-sm btn-secondary"  data-placement="bottom" title="View GST Form Information"><i class="fa fa-pencil-alt"></i></button></a>';
        }
        else
        {
          $button='<div class="checkboxpre"><input class="from-controle checksub" type="checkbox" name="check" value="'.$row['gst_id'].'"></input><a href="' . base_url('legal/gst/itr_bussiness_view?q=') . $row['gst_id'] . '"> <button type="button" class="btn btn-sm btn-secondary"  data-placement="bottom" title="View GST Form Information"><i class="fa fa-pencil-alt"></i></button></a>';
        }

        $sub_array[] = $button;
        $sub_array[] = $row['referance_number'];
        $sub_array[] = $row['member_id'];
        $sub_array[] = $row['email'];
        $sub_array[] = $row['mobile'];
        $sub_array[] = $row['nature_of_properties'];
        $sub_array[] = $status;
        $sub_array[] = $row['notation'];
        $sub_array[] = $row['created'];
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
  //itr salary
  public function itr_salary_list() {
    
    $uri = $this->security->xss_clean($_GET);
    
    if (!empty($uri)) {

      $query = '';
      $output = array();
      $list = $uri['list'];
      $data = array();
      
      if (isAdmin($this->session->userdata('user_roles'))) {

        $query .= "SELECT * FROM gst_registration where serviceid=".$_GET['service']." ";

        $recordsFiltered = $this->users_model->row_count($query);

      }else{

        $query .= "SELECT * FROM gst_registration where serviceid=".$_GET['service']." AND member_id = '{$this->session->userdata('member_id')}'";

        $recordsFiltered = $this->users_model->row_count($query);

      }

      if(isset($_GET["searchtype"]) && !empty($_GET["searchtype"])){
        $query .= " AND gst_id .".$_GET["searchByCat"]." = ". $_GET["searchValue"]." ";         
      }
      if(!empty($_GET["order"])){
        $query .= 'ORDER BY '.$_GET['order']['0']['column'].' '.$_GET['order']['0']['dir'].' ';
      }else{
        $query .= 'ORDER BY gst_registration.created DESC ';
      }
      if($_GET["length"] != -1){
        $query .= 'LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];
      }

      $sql = $this->db->query($query);
      $result = $sql->result_array();
      $i = 1;

      foreach ($result as $row) {

        $sub_array = array();

        if ($row['status'] == 'Success') {

          $status = '<span class="badge badge-success">Success</span>';

        } elseif ($row['status'] == 'Reject') {

          $status = '<span class="badge badge-danger">Reject</span>';

        }elseif ($row['status'] == 'Accept') {

          $status = '<span class="badge badge-primary">Accept</span>';

        }else{

          $status = '<span class="badge badge-warning">Pending</span>';

        } 
        if($row['status'] == 'Accept')
        {
          $button='<a href="' . base_url('legal/gst/itr_salary_view?q=') . $row['gst_id'] . '"> <button type="button" class="btn btn-sm btn-secondary"  data-placement="bottom" title="View GST Form Information"><i class="fa fa-pencil-alt"></i></button></a>';
        }
        else
        {
          $button='<div class="checkboxpre"><input class="from-controle checksub" type="checkbox" name="check" value="'.$row['gst_id'].'"></input><a href="' . base_url('legal/gst/itr_salary_view?q=') . $row['gst_id'] . '"> <button type="button" class="btn btn-sm btn-secondary"  data-placement="bottom" title="View GST Form Information"><i class="fa fa-pencil-alt"></i></button></a>';
        }

        $sub_array[] = $button;
        $sub_array[] = $row['referance_number'];
        $sub_array[] = $row['member_id'];
        $sub_array[] = $row['email'];
        $sub_array[] = $row['mobile'];
        $sub_array[] = $row['nature_of_properties'];
        $sub_array[] = $status;
        $sub_array[] = $row['notation'];
        $sub_array[] = $row['created'];
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
  //msme list
  public function msme_list() {
    
    $uri = $this->security->xss_clean($_GET);
    
    if (!empty($uri)) {

      $query = '';
      $output = array();
      $list = $uri['list'];
      $data = array();
      
      if (isAdmin($this->session->userdata('user_roles'))) {

        $query .="SELECT * FROM gst_registration where serviceid=".$_GET['service']." ";

        $recordsFiltered = $this->users_model->row_count($query);

      }else{

        $query .= "SELECT * FROM gst_registration where serviceid=".$_GET['service']." AND member_id = '{$this->session->userdata('member_id')}'";

        $recordsFiltered = $this->users_model->row_count($query);

      }

      if(isset($_GET["searchtype"]) && !empty($_GET["searchtype"])){
        $query .= " AND gst_id .".$_GET["searchByCat"]." = ". $_GET["searchValue"]." ";         
      }
      if(!empty($_GET["order"])){
        $query .= 'ORDER BY '.$_GET['order']['0']['column'].' '.$_GET['order']['0']['dir'].' ';
      }else{
        $query .= 'ORDER BY gst_registration.created DESC ';
      }
      if($_GET["length"] != -1){
        $query .= 'LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];
      }

      $sql = $this->db->query($query);
      $result = $sql->result_array();
      $i = 1;

      foreach ($result as $row) {

        $sub_array = array();

        if ($row['status'] == 'Success') {

          $status = '<span class="badge badge-success">Success</span>';

        } elseif ($row['status'] == 'Reject') {

          $status = '<span class="badge badge-danger">Reject</span>';

        }elseif ($row['status'] == 'Accept') {

          $status = '<span class="badge badge-primary">Accept</span>';

        }else{

          $status = '<span class="badge badge-warning">Pending</span>';

        } 
        if($row['status'] == 'Accept')
        {
          $button='<a href="' . base_url('legal/gst/msme_view?q=') . $row['gst_id'] . '"> <button type="button" class="btn btn-sm btn-secondary"  data-placement="bottom" title="View GST Form Information"><i class="fa fa-pencil-alt"></i></button></a>';
        }
        else
        {
          $button='<div class="checkboxpre"><input class="from-controle checksub" type="checkbox" name="check" value="'.$row['gst_id'].'"></input><a href="' . base_url('legal/gst/msme_view?q=') . $row['gst_id'] . '"> <button type="button" class="btn btn-sm btn-secondary"  data-placement="bottom" title="View GST Form Information"><i class="fa fa-pencil-alt"></i></button></a>';
        }

        $sub_array[] = $button;
        $sub_array[] = $row['referance_number'];
        $sub_array[] = $row['member_id'];
        $sub_array[] = $row['email'];
        $sub_array[] = $row['mobile'];
        $sub_array[] = $row['nature_of_properties'];
        $sub_array[] = $status;
        $sub_array[] = $row['notation'];
        $sub_array[] = $row['created'];
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
  //company registration
  public function company_list() {
    
    $uri = $this->security->xss_clean($_GET);
    
    if (!empty($uri)) {

      $query = '';
      $output = array();
      $list = $uri['list'];
      $data = array();
      
      if (isAdmin($this->session->userdata('user_roles'))) {

        $query .= "SELECT * FROM gst_registration where serviceid=".$_GET['service']." ";

        $recordsFiltered = $this->users_model->row_count($query);

      }else{

        $query .= "SELECT * FROM gst_registration where serviceid=".$_GET['service']." AND member_id = '{$this->session->userdata('member_id')}'";

        $recordsFiltered = $this->users_model->row_count($query);

      }

      if(isset($_GET["searchtype"]) && !empty($_GET["searchtype"])){
        $query .= " AND gst_id .".$_GET["searchByCat"]." = ". $_GET["searchValue"]." ";         
      }
      if(!empty($_GET["order"])){
        $query .= 'ORDER BY '.$_GET['order']['0']['column'].' '.$_GET['order']['0']['dir'].' ';
      }else{
        $query .= 'ORDER BY gst_registration.created DESC ';
      }
      if($_GET["length"] != -1){
        $query .= 'LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];
      }

      $sql = $this->db->query($query);
      $result = $sql->result_array();
      $i = 1;

      foreach ($result as $row) {

        $sub_array = array();

        if ($row['status'] == 'Success') {

          $status = '<span class="badge badge-success">Success</span>';

        } elseif ($row['status'] == 'Reject') {

          $status = '<span class="badge badge-danger">Reject</span>';

        }elseif ($row['status'] == 'Accept') {

          $status = '<span class="badge badge-primary">Accept</span>';

        }else{

          $status = '<span class="badge badge-warning">Pending</span>';

        } 
        if($row['status'] == 'Accept')
        {
          $button='<a href="' . base_url('legal/gst/company_view?q=') . $row['gst_id'] . '"> <button type="button" class="btn btn-sm btn-secondary"  data-placement="bottom" title="View GST Form Information"><i class="fa fa-pencil-alt"></i></button></a>';
        }
        else
        {
          $button='<div class="checkboxpre"><input class="from-controle checksub" type="checkbox" name="check" value="'.$row['gst_id'].'"></input><a href="' . base_url('legal/gst/company_view?q=') . $row['gst_id'] . '"> <button type="button" class="btn btn-sm btn-secondary"  data-placement="bottom" title="View GST Form Information"><i class="fa fa-pencil-alt"></i></button></a>';
        }

        $sub_array[] = $button;
        $sub_array[] = $row['referance_number'];
        $sub_array[] = $row['member_id'];
        $sub_array[] = $row['business_address'];
        $sub_array[] = $row['state'];
        $sub_array[] = $row['nature_of_business'];
        $sub_array[] = $status;
        $sub_array[] = $row['notation'];
        $sub_array[] = $row['created'];

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

  public function view(){

    $uri = $this->security->xss_clean($_GET);

    if (isset($uri['q']) && !empty($uri['q'])) {

      // pre($uri);exit;
      $uid = $uri['q'];

      if (isAdmin($this->session->userdata('user_roles'))) {

        $query = "SELECT * FROM `gst_registration` where gst_id = '{$uri['q']}' ";

        $recordsFiltered = $this->users_model->row_count($query);
        $sql = $this->db->query($query);
        $this->data['gst_details'] = $sql->row();

        if(!$this->data['gst_details']){

          exit('Not allow to access Exist');
        
        }

      }else{

        exit('Not allow to access Exist');

      }

        $query = "SELECT * FROM `directors` where gst_registration_id = '{$this->data['gst_details']->gst_id}' ";

        $recordsFiltered = $this->users_model->row_count($query);
        $sql = $this->db->query($query);
         
        $this->data['director']= $sql->result_array();
        $i = 0 ;
        foreach($this->data['director'] as $image){

          $query = "SELECT * FROM `directors_doc` where director_id = '{$image['director_id']}' ";

          $recordsFiltered = $this->users_model->row_count($query);
          $sql = $this->db->query($query);
          $this->data['director_doc'][$i] = $sql->result_array();
          $i++;
          
        }
        
      //   pre($this->data['director_doc']);
      // pre($this->data['director']);

      // exit;

      $this->data['main_content'] = $this->load->view('views', $this->data, true);

      $this->data['is_script'] = $this->load->view('script', $this->data, true);

      $this->load->view('layout/index', $this->data);

    }

  }

  public function view_comapmy(){

    $uri = $this->security->xss_clean($_GET);

    if (isset($uri['q']) && !empty($uri['q'])) {

      // pre($uri);exit;
      $uid = $uri['q'];

      if (isAdmin($this->session->userdata('user_roles'))) {

        $query = "SELECT * FROM `gst_registration` where gst_id = '{$uri['q']}' ";

        $recordsFiltered = $this->users_model->row_count($query);
        $sql = $this->db->query($query);
        $this->data['gst_details'] = $sql->row();

        if(!$this->data['gst_details']){

          exit('Not allow to access Exist');
        
        }

      }else{

        exit('Not allow to access Exist');

      }

        $query = "SELECT * FROM `directors` where gst_registration_id = '{$this->data['gst_details']->gst_id}' ";

        $recordsFiltered = $this->users_model->row_count($query);
        $sql = $this->db->query($query);
         
        $this->data['director']= $sql->result_array();
        $i = 0 ;
        foreach($this->data['director'] as $image){

          $query = "SELECT * FROM `directors_doc` where director_id = '{$image['director_id']}' ";

          $recordsFiltered = $this->users_model->row_count($query);
          $sql = $this->db->query($query);
          $this->data['director_doc'][$i] = $sql->result_array();
          $i++;
          
        }
        
      //   pre($this->data['director_doc']);
      // pre($this->data['director']);

      // exit;

      $this->data['main_content'] = $this->load->view('company_view', $this->data, true);

      $this->data['is_script'] = $this->load->view('script', $this->data, true);

      $this->load->view('layout/index', $this->data);

    }

  }
  
  public function view_return(){

    $uri = $this->security->xss_clean($_GET);

    if (isset($uri['q']) && !empty($uri['q'])) {

      // pre($uri);exit;
      $uid = $uri['q'];

      if (isAdmin($this->session->userdata('user_roles'))) {

        $query = "SELECT * FROM `gst_return` where id = '{$uri['q']}' ";

        $recordsFiltered = $this->users_model->row_count($query);
        $sql = $this->db->query($query);
        $this->data['gst_details'] = $sql->row();

        if(!$this->data['gst_details']){

          exit('Not allow to access Exist');
        
        }

      }else{

        exit('Not allow to access Exist');

      }

        $query = "SELECT * FROM `gst_return_doc` where gst_return_id = '{$this->data['gst_details']->id}' ";

        $recordsFiltered = $this->users_model->row_count($query);
        $sql = $this->db->query($query);
         
        $this->data['image']= $sql->result_array();
        
      $this->data['main_content'] = $this->load->view('view_return', $this->data, true);

      $this->data['is_script'] = $this->load->view('script', $this->data, true);

      $this->load->view('layout/index', $this->data);

    }

  }

  public function itr_salary_view(){

    $uri = $this->security->xss_clean($_GET);
     
    if (isset($uri['q']) && !empty($uri['q'])) {

      // pre($uri);exit;
      $uid = $uri['q'];

      if (isAdmin($this->session->userdata('user_roles'))) {

        $query = "SELECT * FROM `gst_registration` where gst_id = '{$uri['q']}' ";

        $recordsFiltered = $this->users_model->row_count($query);
        $sql = $this->db->query($query);
        $this->data['gst_details'] = $sql->row();

        if(!$this->data['gst_details']){

          exit('Not allow to access Exist');
        
        }

      }else{

        exit('Not allow to access Exist');

      }  
      $this->data['main_content'] = $this->load->view('itr_salary_view', $this->data, true);

      $this->data['is_script'] = $this->load->view('script', $this->data, true);

      $this->load->view('layout/index', $this->data);

    }

  }

  public function itr_bussiness_view(){

    $uri = $this->security->xss_clean($_GET);
     
    if (isset($uri['q']) && !empty($uri['q'])) {

      // pre($uri);exit;
      $uid = $uri['q'];

      if (isAdmin($this->session->userdata('user_roles'))) {

        $query = "SELECT * FROM `gst_registration` where gst_id = '{$uri['q']}' ";

        $recordsFiltered = $this->users_model->row_count($query);
        $sql = $this->db->query($query);
        $this->data['gst_details'] = $sql->row();

        if(!$this->data['gst_details']){

          exit('Not allow to access Exist');
        
        }

      }else{

        exit('Not allow to access Exist');

      }  
      $this->data['main_content'] = $this->load->view('itr_bussiness_view', $this->data, true);

      $this->data['is_script'] = $this->load->view('script', $this->data, true);

      $this->load->view('layout/index', $this->data);

    }

  }

  public function msme_view(){

    $uri = $this->security->xss_clean($_GET);
     
    if (isset($uri['q']) && !empty($uri['q'])) {

      // pre($uri);exit;
      $uid = $uri['q'];

      if (isAdmin($this->session->userdata('user_roles'))) {

        $query = "SELECT * FROM `gst_registration` where gst_id = '{$uri['q']}' ";

        $recordsFiltered = $this->users_model->row_count($query);
        $sql = $this->db->query($query);
        $this->data['gst_details'] = $sql->row();

        if(!$this->data['gst_details']){

          exit('Not allow to access Exist');
        
        }

      }else{

        exit('Not allow to access Exist');

      }  
      $this->data['main_content'] = $this->load->view('msme_view', $this->data, true);

      $this->data['is_script'] = $this->load->view('script', $this->data, true);

      $this->load->view('layout/index', $this->data);

    }

  }


  public function gst_reject()
  {
    $uri = $this->security->xss_clean($_POST);
     $data=[
            'notation'=>$uri['gst_massage']."(By admin)",
            'status'=>'Reject'
     ];
    if($this->common_model->update($data,'gst_id',$uri['gst_id'],'gst_registration'))
    {
      $this->session->set_flashdata(
        array(
          'status' => 1,
          'msg' => "GST Reject"
        )
      );
      redirect('legal/gst/list', 'refresh');
    }
    else
    {
      $this->session->set_flashdata(
        array(
          'status' => 1,
          'msg' => "GST Not Reject"
        )
      );
      redirect('legal/gst/list', 'refresh');
    }
  }
  
  public function gst_return_reject()
  {
    $uri = $this->security->xss_clean($_POST);
     $data=[
            'notation'=>$uri['gst_massage']."(By admin)",
            'status'=>'Reject'
     ];
    if($this->common_model->update($data,'id',$uri['gst_id'],'gst_return'))
    {
      $this->session->set_flashdata(
        array(
          'status' => 1,
          'msg' => "GST Reject"
        )
      );
      redirect('legal/gst/list', 'refresh');
    }
    else
    {
      $this->session->set_flashdata(
        array(
          'status' => 1,
          'msg' => "GST Not Reject"
        )
      );
      redirect('legal/gst/list', 'refresh');
    }
  }

  
  
  public function payment_save(){
                        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ){
                          if($_POST){
                             $data = $this->security->xss_clean($_POST);
                             $location = $this->session->userdata('latitude') . '|' . $this->session->userdata('longitude');
                            if($data['status']=='captured')
                            {          
                              $logme = [
                                'transection_id' =>$data['refrence'],
                                'transection_type'=>'GST Register',
                                'member_id'=>$this->session->userdata('member_id'),
                                'service_id'=>51,
                                'transection_amount'=>$data['amount'],
                                'reference_number'=>$data['gst_refrence_id'],
                                'transection_status'=>1,
                                'transection_msg'=>"GST Sent",
                                'api_requist'=>'GstGw',
                                'transection_response'=>json_encode($data['response']),
                                'location'=>$location,
                                'created'=> date("Y-m-d h:i:sa"),
                              ];
                              $id= $this->common_model->insert($logme, 'submit_transection');
                              if($id)
                              {
                                $update=[
                                  'notation'=>"Accept (By admin)",
                                  'status'=>'Accept'
                                 ];
                                if($this->common_model->update($update,'gst_id',$data['gst_id'],'gst_registration'))
                                {
                                    $response=self::details($data['gst_id']);
                                    $response_gst_api=json_decode($response);
                                    if($response_gst_api->status==TRUE && $response_gst_api->response==1)
                                    {
                                       $update=[
                                        'responce_api'=>$response,
                                        'notation'=>"Successfully send To CA (By admin)",
                                        'status'=>'Accept'
                                       ];
                                       $this->common_model->update($update,'gst_id',$data['gst_id'],'gst_registration');
                                       echo $response;
                                    }
                                    else
                                    {
                                      $update=[
                                        'responce_api'=>$response,
                                        'notation'=>"Successfully not send To CA (By admin)",
                                        'status'=>'Accept'
                                       ];
                                       $this->common_model->update($update,'gst_id',$data['gst_id'],'gst_registration');
                                       echo $response;
                                    }
                                }
                              }
                              else
                              {
                                 echo json_encode(['status'=>true,'response'=>127,'message'=>'Payment Successfull but not request not send to CA']);
                              }
                              echo json_encode(['status'=>true,'response'=>127,'message'=>'Payment Successfull but not request not send to CA']);
                            }
                            else
                            {
                              $logme = [
                                'transection_id' =>$data['refrence'],
                                'transection_type'=>'GST Register',
                                'member_id'=>$this->session->userdata('member_id'),
                                'service_id'=>51,
                                'transection_amount'=>$data['amount'],
                                'reference_number'=>$data['gst_refrence_id'],
                                'transection_status'=>0,
                                'transection_msg'=>"GST not sent",
                                'api_requist'=>'GstGw',
                                'transection_response'=>$data['response'],
                                'location'=>$location,
                                'created'=> date("Y-m-d h:i:sa"),
                              ];
                              $id= $this->common_model->insert($logme, 'submit_transection');
                              echo 1;
                            }
                          }
                        
                        }
                        else{ 
                             echo "This Method Is Not Allow ." ;
                            }
  }

  public function details()
  {
    $gst_refrense_id=[];
    $gst_id_incre=0;
    foreach($_POST['array_gst'] as $value)
    {
      if($_POST['service']!='gst_return')
      {
        $data=$this->common_model->select_option($value,'gst_id','gst_registration');
      }
      else
      {
        $data=$this->common_model->select_option($value,'id','gst_return');
      }
      $service_type=$data[0]['referance_number'];
      $gst_refrense_id[$gst_id_incre]=$data[0]['referance_number'];
      $gst_id_incre++;
    }
    $this->payment_data=[
                           'email'=>$_POST['email'],
                           'amount'=>$_POST['price'],
                           'phone'=>$this->session->userdata('phone'),
                           'api_key'=>'POCA001',
                           'service'=>$_POST['service'],
                           'return_url'=>base_url('legalservice/LegalController/return_url'),
                           'gst_id'=>$_POST['array_gst'],
                           'gst_refrence_id'=>$gst_refrense_id,
                        ];
      $responce=self::payment();
  }
  
    //payment gateway
  public function payment(){
    $this->client = new Client();
    $headers=array('Access-Control-Allow-Origin'=>'https://vitefintech.com/viteapi/vitepayment/');
         #guzzle
         try {
          $response = $this->client->request('POST', "https://vitefintech.com/viteapi/vitepayment/", [

            'form_params' => $this->payment_data,
            "headers" => $headers,
          ]);
         $response=$response->getBody()->getContents();
         print($response);
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
        }
  }

  public function return_url()
  {
    if(isset($_GET['result']) || !empty($_GET['result']))
    {
    $data=json_decode($_GET['result']);
    if($data->status=='PAID')
    {
      $submit=[
               'order_id'=>$data->order_id,
               'amount'=>$data->amount,
               'remark'=>$data->remark,
               'email'=>$data->email,
               'phone'=>$data->phone,
               'service'=>$data->service,
               'status'=>$data->status,
               'transition_id'=>$data->transition_id,
               'transition_mgs'=>$data->transition_mgs,
               'response'=>$data->response
              ];

      $pay_id=$this->common_model->insert($submit,'payment_gateway');
      $gst_id=json_decode($data->gst_id);
      $gst_refence_number=json_decode($data->gst_refence_number);
      for($i=0;$i<count($gst_id);$i++)
      {
        $data1=[
               'gst_id'=>$gst_id[$i],
               'gst_refence_number'=>$gst_refence_number[$i],
               'payment_id'=>$pay_id,
               'service'=>$data->service,
               'order_id'=>$data->order_id,
              ];
        $this->common_model->insert($data1,'gst_details_pay');
      }
      $gst_id=json_decode($data->gst_id);
      $gst_success='';
      $get_s=[];
      if($data1['service']=='cmp_registation')
      {
          for($l=0;$l<count($gst_id);$l++)
          {
           $data_gst=$this->common_model->select_option($gst_id[$l],'gst_id','gst_registration');
           $data_director=$this->common_model->select_option($gst_id[$l],'gst_registration_id','directors');
           $data_director_doc=[];
           $i=0;
           foreach($data_director as $value)
           {
            $data_director_doc[$i]=$this->common_model->select_option($value['director_id'],'director_id','directors_doc');
            $i++;
           }
           $gst_details=[
                         "Electricity_Bill"=>base_url('uploads/gst/documents/').$data_gst[0]['electricity_bill'],//file
                         "referance_number"=>$data_gst[0]['referance_number'],
                         "business_adress"=>$data_gst[0]['business_address'],
                         "type_s"=>$data1['service'],
                         "state"=>$data_gst[0]['state'],
                         "api_key"=>"POCA001",
                        ];
           $data_director_details=[];
           $main_director_details=[];
           $data_director_doc_temo=[];
           $data_director_doc_temo_wh=[];
           $i=0;
           foreach($data_director as $value)
           {
            $data_director_details[$i]=[
                                        'name['.$i.']'=>$value['name'],
                                        'phone['.$i.']'=>$value['mobile_number'],
                                        'email['.$i.']'=>$value['email'],
                                        'father_name['.$i.']'=>$value['father_name'],
                                        'dob['.$i.']'=>$value['dob'],
                                        'adhar_no['.$i.']'=>$value['adhar_number'],
                                        'pan_no['.$i.']'=>$value['pan_number'],
                                        'address['.$i.']'=>$value['address'],
                                        'photo['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][0]['type']."/".$data_director_doc[$i][0]['image_name'],
                                        'adhar_font['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][1]['type']."/".$data_director_doc[$i][1]['image_name'],
                                        'adhar_back['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][2]['type']."/".$data_director_doc[$i][2]['image_name'],
                                        'pan['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][3]['type']."/".$data_director_doc[$i][3]['image_name'],
                                        'back_statement['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][4]['type']."/".$data_director_doc[$i][4]['image_name'],
                                        'voter/lience_id['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][5]['type']."/".$data_director_doc[$i][4]['image_name'],
                                     ];
            $data_director_doc_temo[$i]=[
              'photo['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][0]['type']."/".$data_director_doc[$i][0]['image_name'],
              'adhar_font['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][1]['type']."/".$data_director_doc[$i][1]['image_name'],
              'adhar_back['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][2]['type']."/".$data_director_doc[$i][2]['image_name'],
              'pan['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][3]['type']."/".$data_director_doc[$i][3]['image_name'],
              'back_statement['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][4]['type']."/".$data_director_doc[$i][4]['image_name']
            ];
            $data_director_doc_temo_wh+=$data_director_doc_temo[$i];
            $main_director_details+=$data_director_details[$i];
            $i++;
           }
           $whole_data=$gst_details+$main_director_details;//array of whole data
           $whole_data_key=array_keys($whole_data);
           $data_director_doc_temo_key=array_keys($data_director_doc_temo_wh);
           $multi_data_submit=[];
           $i=0;
           //   pre($whole_data);
           //   exit();
           //   foreach($whole_data_key as $value)
           //   {
    //      if($value=='Certificate_Of_Incorporation' || $value=='Electricity_Bill' || $value=='Rent_Agreement' || in_array($value,$data_director_doc_temo_key)==1)
    //         {
    //          $multi_data_submit[$i]=[
    //                               'name'=>$value,
    //                             //   'contents'=>fopen($whole_data[$value],'r')
    //                             'contents'=>$whole_data[$value]
    //         ];
    //       }
    //       else
    //       {
    //          $multi_data_submit[$i]=[
    //           'name'=>$value,
    //           'contents'=>$whole_data[$value]
    //           ];
    //       }
    //      $i++;
    //   }
          $this->client = new Client();
          #guzzle
          try 
          {
            $response = $this->client->request('POST', "https://vitefintech.com/viteapi/GST2/cpm", [
 
            'form_params' => $whole_data,
            ]);
            $response=$response->getBody()->getContents();
            $response1=json_decode($response);
            if($response1->status=='true' && $response1->response==1)
            {
             $update=[
              'responce_api'=>$response,
             'notation'=>"Successfully send To CA (By admin)",
             'status'=>'Accept'
            ];
            }
            else
            {
                  $update=[
                 'responce_api'=>$response,
                 'notation'=>"Successfully not send To CA (By admin)",
                 'status'=>'Accept'
                 ];
            }
            $this->common_model->update($update,'gst_id',$gst_id[$l],'gst_registration');
            $gst_success=$gst_success.','.$data_gst[0]['referance_number'];
            $get_s[$l]=$gst_id[$l];
          } 
          catch (GuzzleHttp\Exception\BadResponseException $e)
          {
            #guzzle repose for future use
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            print_r($responseBodyAsString);
          }
        }
      }
      else if($data1['service']=='gst_return')
      {
          for($l=0;$l<count($gst_id);$l++)
          {
           $data_gst=$this->common_model->select_option($gst_id[$l],'id','gst_return');
           $data_director=$this->common_model->select_option($gst_id[$l],'gst_return_id','gst_return_doc');
           $data_director_doc=[];
           if(isset($data_gst[0]['name']))
           {
               $name=$data_gst[0]['name'];
           }
           else
           {
               $name='';
           }
           if(isset($data_gst[0]['password']))
           {
               $password=$data_gst[0]['password'];
           }
           else
           {
               $password='';
           }
           $gst_details=[
                         "gst_no"=>$data_gst[0]['gst_no'],
                         "referance_number"=>$data_gst[0]['referance_number'],
                         "mobile"=>$data_gst[0]['mobile'],
                         "type_s"=>$data1['service'],
                         "password"=>$password,
                         "name"=>$name,
                         "api_key"=>"POCA001",
                        ];
           $data_director_details=[];
           $main_director_details=[];
           $data_director_doc_temo=[];
           $data_director_doc_temo_wh=[];
           $i=0;
           foreach($data_director as $value)
           {
            $data_director_details[$i]=[
                                        'purchase['.$i.']'=>base_url('uploads/gst/gst_return_doc')."/".$value['Purchase'],
                                        'sale['.$i.']'=>base_url('uploads/gst/gst_return_doc')."/".$value['Sale'],
                                       ];
            $main_director_details+=$data_director_details[$i];
            $i++;
           }
           $whole_data=$gst_details+$main_director_details;//array of whole data
           $whole_data_key=array_keys($whole_data);
           $data_director_doc_temo_key=array_keys($data_director_doc_temo_wh);
           $multi_data_submit=[];
           $i=0;
           //   pre($whole_data);
           //   exit();
           //   foreach($whole_data_key as $value)
           //   {
    //      if($value=='Certificate_Of_Incorporation' || $value=='Electricity_Bill' || $value=='Rent_Agreement' || in_array($value,$data_director_doc_temo_key)==1)
    //         {
    //          $multi_data_submit[$i]=[
    //                               'name'=>$value,
    //                             //   'contents'=>fopen($whole_data[$value],'r')
    //                             'contents'=>$whole_data[$value]
    //         ];
    //       }
    //       else
    //       {
    //          $multi_data_submit[$i]=[
    //           'name'=>$value,
    //           'contents'=>$whole_data[$value]
    //           ];
    //       }
    //      $i++;
    //   }
          $this->client = new Client();
          #guzzle
          try 
          {
            $response = $this->client->request('POST', "https://vitefintech.com/viteapi/GST2/gstr", [
 
            'form_params' => $whole_data,
            ]);
            $response=$response->getBody()->getContents();
            $response1=json_decode($response);
            if($response1->status=='true' && $response1->response==1)
            {
             $update=[
               'responce_api'=>$response,
               'notation'=>"Successfully send To CA (By admin)",
               'status'=>'Accept'
             ];
            }
            else
            {
                $update=[
               'responce_api'=>$response,
               'notation'=>"Successfully not send To CA (By admin)",
               'status'=>'Accept'
               ];
            }
              $this->common_model->update($update,'id',$gst_id[$l],'gst_return');
              $gst_success=$gst_success.','.$data_gst[0]['referance_number'];
              $get_s[$l]=$gst_id[$l];
          } 
          catch (GuzzleHttp\Exception\BadResponseException $e)
          {
            #guzzle repose for future use
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            print_r($responseBodyAsString);
          }
        }
      }
      else if($data1['service']=='gst_registation')
      {
          for($l=0;$l<count($gst_id);$l++)
          {
           $data_gst=$this->common_model->select_option($gst_id[$l],'gst_id','gst_registration');
           $data_director=$this->common_model->select_option($gst_id[$l],'gst_registration_id','directors');
           $data_director_doc=[];
           $i=0;
           foreach($data_director as $value)
           {
            $data_director_doc[$i]=$this->common_model->select_option($value['director_id'],'director_id','directors_doc');
            $i++;
           }
           if(!empty($data_gst[0]['certificate_of_incorporation']))
           {
         $Certificate_Of_Incorporation=base_url('uploads/gst/documents/').$data_gst[0]['certificate_of_incorporation'];
       }
           else
           {
         $Certificate_Of_Incorporation='';
       }
           $gst_details=[
                         "Certificate_Of_Incorporation"=>$Certificate_Of_Incorporation,//file
                         "Electricity_Bill"=>base_url('uploads/gst/documents/').$data_gst[0]['electricity_bill'],//file
                         "Rent_Agreement"=>base_url('uploads/gst/documents/').$data_gst[0]['rent_agreement'],//file
                         "firm_name"=>$data_gst[0]['firm_name'],//file
                         "nature_property"=>$data_gst[0]['nature_of_properties'],
                         "referance_number"=>$data_gst[0]['referance_number'],
                         "district"=>$data_gst[0]['district'],
                         "business_adress"=>$data_gst[0]['business_address'],
                         "nob"=>$data_gst[0]['nature_of_business'],
                         "type"=>$data_gst[0]['company_type'],
                         "type_s"=>'GST Registation',
                         "state"=>$data_gst[0]['state'],
                         "api_key"=>"POCA001",
                        ];
           $data_director_details=[];
           $main_director_details=[];
           $data_director_doc_temo=[];
           $data_director_doc_temo_wh=[];
           $i=0;
           foreach($data_director as $value)
           {
         $data_director_details[$i]=[
                                      'name['.$i.']'=>$value['name'],
                                      'phone['.$i.']'=>$value['mobile_number'],
                                      'email['.$i.']'=>$value['email'],
                                      'father_name['.$i.']'=>$value['father_name'],
                                      'dob['.$i.']'=>$value['dob'],
                                      'adhar_no['.$i.']'=>$value['adhar_number'],
                                      'pan_no['.$i.']'=>$value['pan_number'],
                                      'address['.$i.']'=>$value['address'],
                                      'photo['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][0]['type']."/".$data_director_doc[$i][0]['image_name'],
                                      'adhar_font['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][1]['type']."/".$data_director_doc[$i][1]['image_name'],
                                      'adhar_back['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][2]['type']."/".$data_director_doc[$i][2]['image_name'],
                                      'pan['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][3]['type']."/".$data_director_doc[$i][3]['image_name'],
                                      'back_statement['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][4]['type']."/".$data_director_doc[$i][4]['image_name']
         ];
         $data_director_doc_temo[$i]=[
           'photo['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][0]['type']."/".$data_director_doc[$i][0]['image_name'],
           'adhar_font['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][1]['type']."/".$data_director_doc[$i][1]['image_name'],
           'adhar_back['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][2]['type']."/".$data_director_doc[$i][2]['image_name'],
           'pan['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][3]['type']."/".$data_director_doc[$i][3]['image_name'],
           'back_statement['.$i.']'=>base_url('uploads/gst/').$data_director_doc[$i][4]['type']."/".$data_director_doc[$i][4]['image_name']
         ];
         $data_director_doc_temo_wh+=$data_director_doc_temo[$i];
         $main_director_details+=$data_director_details[$i];
         $i++;
       }
           $whole_data=$gst_details+$main_director_details;//array of whole data
           $whole_data_key=array_keys($whole_data);
           $data_director_doc_temo_key=array_keys($data_director_doc_temo_wh);
           $multi_data_submit=[];
           $i=0;
           //   pre($whole_data);
           //   exit();
           //   foreach($whole_data_key as $value)
           //   {
    //      if($value=='Certificate_Of_Incorporation' || $value=='Electricity_Bill' || $value=='Rent_Agreement' || in_array($value,$data_director_doc_temo_key)==1)
    //         {
    //          $multi_data_submit[$i]=[
    //                               'name'=>$value,
    //                             //   'contents'=>fopen($whole_data[$value],'r')
    //                             'contents'=>$whole_data[$value]
    //         ];
    //       }
    //       else
    //       {
    //          $multi_data_submit[$i]=[
    //           'name'=>$value,
    //           'contents'=>$whole_data[$value]
    //           ];
    //       }
    //      $i++;
    //   }
          $this->client = new Client();
          #guzzle
          try 
          {
            $response = $this->client->request('POST', "https://vitefintech.com/viteapi/GST2/temp", [
 
            'form_params' => $whole_data,
            ]);
            $response=$response->getBody()->getContents();
            $response1=json_decode($response);
            if($response1->status=='true' && $response1->response==1)
            {
             $update=[
              'responce_api'=>$response,
              'notation'=>"Successfully send To CA (By admin)",
              'status'=>'Accept'
             ];
            }
            else
            {
                 $update=[
              'responce_api'=>$response,
              'notation'=>"Successfully not send To CA (By admin)",
              'status'=>'Accept'
             ];
            }
              $this->common_model->update($update,'gst_id',$gst_id[$l],'gst_registration');
              $gst_success=$gst_success.','.$data_gst[0]['referance_number'];
              $get_s[$l]=$gst_id[$l];
          } 
          catch (GuzzleHttp\Exception\BadResponseException $e)
          {
            #guzzle repose for future use
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            print_r($responseBodyAsString);
          }
        }
      }
      else if($data1['service']=='itr_bussiness')
      {
          for($l=0;$l<count($gst_id);$l++)
          {
           $data_gst=$this->common_model->select_option($gst_id[$l],'gst_id','gst_registration');
           if(isset($data_gst[0]['business_details']) && !empty($data_gst[0]['business_details']))
           {
               $bussiness_details=$data_gst[0]['business_details'];
               $bussiness_image='';
           }
           else
           {
               $bussiness_image=base_url('uploads/gst/documents/').$data_gst[0]['business_details_file'];
               $bussiness_details='';
           }
           $gst_details=[
                         "email"=>$data_gst[0]['email'],
                         "referance_number"=>$data_gst[0]['referance_number'],
                         "mobile"=>$data_gst[0]['mobile'],
                         "type_s"=>$data1['service'],
                         "anual_income"=>$data_gst[0]['annualincome'],
                         "business_details"=>$bussiness_details,
                         "business_details_file"=>$bussiness_image,
                         "pancard"=>base_url('uploads/gst/documents/').$data_gst[0]['pancard'],
                         "adharcardf"=>base_url('uploads/gst/documents/').$data_gst[0]['adharcardf'],
                         "adharcardb"=>base_url('uploads/gst/documents/').$data_gst[0]['adharcardb'],
                         "photo"=>base_url('uploads/gst/documents/').$data_gst[0]['photo'],
                         "api_key"=>"POCA001",
                        ];
          $this->client = new Client();
          #guzzle
          try 
          {
            $response = $this->client->request('POST', "https://vitefintech.com/viteapi/GST2/itr_bussiness", [
 
            'form_params' => $gst_details,
            ]);
            $response=$response->getBody()->getContents();
            $response1=json_decode($response);
            if($response1->status=='true' && $response1->response==1)
            {
             $update=[
             'responce_api'=>$response,
              'notation'=>"Successfully send To CA (By admin)",
              'status'=>'Accept'
              ];
           }
           else
           {
               $update=[
             'responce_api'=>$response,
              'notation'=>"Successfully not send To CA (By admin)",
              'status'=>'Accept'
              ];  
           }
            $this->common_model->update($update,'gst_id',$gst_id[$l],'gst_registration');
             $gst_success=$gst_success.','.$data_gst[0]['referance_number'];
             $get_s[$l]=$gst_id[$l];
          } 
          catch (GuzzleHttp\Exception\BadResponseException $e)
          {
            #guzzle repose for future use
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            print_r($responseBodyAsString);
          }
        }
      }
      else if($data1['service']=='itr_salary')
      {
           for($l=0;$l<count($gst_id);$l++)
          {
           $data_gst=$this->common_model->select_option($gst_id[$l],'gst_id','gst_registration');
           $gst_details=[
                         "email"=>$data_gst[0]['email'],
                         "referance_number"=>$data_gst[0]['referance_number'],
                         "mobile"=>$data_gst[0]['mobile'],
                         "type_s"=>$data1['service'],
                         "anual_income"=>$data_gst[0]['annualincome'],
                         "salary"=>base_url('uploads/gst/documents/').$data_gst[0]['salary'],
                         "pancard"=>base_url('uploads/gst/documents/').$data_gst[0]['pancard'],
                         "adharcardf"=>base_url('uploads/gst/documents/').$data_gst[0]['adharcardf'],
                         "adharcardb"=>base_url('uploads/gst/documents/').$data_gst[0]['adharcardb'],
                         "photo"=>base_url('uploads/gst/documents/').$data_gst[0]['photo'],
                         "api_key"=>"POCA001",
                        ];
          $this->client = new Client();
          #guzzle
          try 
          {
            $response = $this->client->request('POST', "https://vitefintech.com/viteapi/GST2/itr_salary", [
 
            'form_params' => $gst_details,
            ]);
            $response=$response->getBody()->getContents();
            $response1=json_decode($response);
            if($response1->status=='true' && $response1->response==1)
            {
             $update=[
             'responce_api'=>$response,
              'notation'=>"Successfully send To CA (By admin)",
              'status'=>'Accept'
              ];
           }
           else
           {
               $update=[
             'responce_api'=>$response,
              'notation'=>"Successfully not send To CA (By admin)",
              'status'=>'Accept'
              ];  
           }
            $this->common_model->update($update,'gst_id',$gst_id[$l],'gst_registration');
             $gst_success=$gst_success.','.$data_gst[0]['referance_number'];
             $get_s[$l]=$gst_id[$l];
          } 
          catch (GuzzleHttp\Exception\BadResponseException $e)
          {
            #guzzle repose for future use
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            print_r($responseBodyAsString);
          }
        }
      }
      else if($data1['service']=='msme')
      {
          for($l=0;$l<count($gst_id);$l++)
          {
           $data_gst=$this->common_model->select_option($gst_id[$l],'gst_id','gst_registration');
           
           if(isset($data_gst[0]['business_details']) && !empty($data_gst[0]['business_details']))
           {
               $bussiness_details=$data_gst[0]['business_details'];
               $bussiness_image='';
           }
           else
           {
               $bussiness_image=base_url('uploads/gst/documents/').$data_gst[0]['business_details_file'];
               $bussiness_details='';
           }
           $gst_details=[
                         "email"=>$data_gst[0]['email'],
                         "referance_number"=>$data_gst[0]['referance_number'],
                         "mobile"=>$data_gst[0]['mobile'],
                         "type_s"=>$data1['service'],
                         "anual_income"=>$data_gst[0]['annualincome'],
                         "business_details"=>$bussiness_details,
                         "business_details_file"=>$bussiness_image,
                         "pancard"=>base_url('uploads/gst/documents/').$data_gst[0]['pancard'],
                         "adharcardf"=>base_url('uploads/gst/documents/').$data_gst[0]['adharcardf'],
                         "adharcardb"=>base_url('uploads/gst/documents/').$data_gst[0]['adharcardb'],
                         "photo"=>base_url('uploads/gst/documents/').$data_gst[0]['photo'],
                         "bank_statement"=>base_url('uploads/gst/documents/').$data_gst[0]['bank_statement'],
                         "api_key"=>"POCA001",
                        ];
          $this->client = new Client();
          #guzzle
          try 
          {
            $response = $this->client->request('POST', "https://vitefintech.com/viteapi/GST2/msme", [
 
            'form_params' => $gst_details,
            ]);
            $response=$response->getBody()->getContents();
            $response1=json_decode($response);
            if($response1->status=='true' && $response1->response==1)
            {
             $update=[
             'responce_api'=>$response,
              'notation'=>"Successfully send To CA (By admin)",
              'status'=>'Accept'
              ];
           }
           else
           {
               $update=[
             'responce_api'=>$response,
              'notation'=>"Successfully not send To CA (By admin)",
              'status'=>'Accept'
              ];  
           }
            $this->common_model->update($update,'gst_id',$gst_id[$l],'gst_registration');
             $gst_success=$gst_success.','.$data_gst[0]['referance_number'];
             $get_s[$l]=$gst_id[$l];
          } 
          catch (GuzzleHttp\Exception\BadResponseException $e)
          {
            #guzzle repose for future use
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            print_r($responseBodyAsString);
          }
        }
      }
      if(count($get_s)>0)
      {
        $this->session->set_flashdata(
         array(
           'status' => 1,
           'msg' => "Successfully"
         )
        );
        redirect('dashboard', 'refresh');
       }
      else
      {
        $this->session->set_flashdata(
          array(
            'status' => 1,
            'msg' => "Some issue is generate"
          )
         );
        redirect('dashboard', 'refresh');
       }
    }
    else
    {
      $this->session->set_flashdata(
        array(
          'status' => 0,
          'msg' =>$data->status
        )
       );
      redirect('dashboard', 'refresh');
     }
    }
    else
    {
      redirect('dashboard', 'refresh');
    }
  }

  //   gst commission
 public function gst_price()
 {
   $this->data['main_content'] = $this->load->view('gst_price_set/index', $this->data, true);
   $this->data['is_script'] = $this->load->view('gst_price_set/script', $this->data, true);
   $this->load->view('layout/index', $this->data);
 }
//price set start
 public function gst_priceForm()
 {

   if ($_POST) {

     $data = $this->security->xss_clean($_POST);


     if (isset($data['aepsCommissionForm'])) {

       $baseRole = $data['aepsCommissionForm'];

       $service = 42;

       $this->data['role_id'] = $baseRole;
       //  $commissionList = $this->common_model->get_list($service, $baseRole);


       echo $this->load->view('gst_price_set/add', $this->data, true);
     } else {
       echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
     }
   }
 }

 public function gst_priceinsert()
 {
   if ($_POST) {
     $data = $this->security->xss_clean($_POST);
     $data1['price'] = $data['amount'];
     $data1['service_id'] = $data['service'];
     $data1['role'] = $data['role_id'];
     $data1['created'] = date('Y-m-d hh:mm:ss');
     if ($this->common_model->insert($data1, 'gst_price')) {
       $this->session->set_flashdata(
         array(
           'status' => 1,
           'msg' => " Insert Successfully"
         )
       );
       redirect('legal/gst/price', 'refresh');
     }
   }
 }
 public function gst_get_list()
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

       $query .= "SELECT * from gst_price  where `role` = '$role_id'";

       $recordsFiltered = $this->users_model->row_count($query);
     } else {

       $query .= "SELECT * from gst_price where `role` = '$role_id'";

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


       $sub_array[] = '<button type="button" class="btn btn-sm btn-info"  data-placement="bottom" onclick="Edit(' . $row['id'] . ')" title="Edit Commission Information"><i class="fa fa-pencil-alt"></i></button>
          <button type="button" class="btn btn-sm btn-primary"  data-placement="bottom" onclick="Delete(' . $row['id'] . ')" title="Delete Commission Information"><i class="fa fa-trash-alt"></i></button>';
       $sub_array[] = $this->common_model->select_option($row['service_id'],'id','services')[0]['name'];
       $sub_array[] = $row['price'];
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
 public function gst_priceaddupdate()
 {

   if ($_POST) {

     $data = $this->security->xss_clean($_POST);
     if (isset($data['addupdate'])) {

       $baseRole = $data['addupdate'];
       $commissionList['gst'] = $this->commission_model->get_list1($data['id'], $baseRole);
       echo $this->load->view('gst_price_set/edit', $commissionList, true);
     } else {
       echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
     }
   }
 }
 public function gst_priceedit($id)
 {
   $menu = $this->common_model->select_option($id, 'id', 'gst_price');
   echo json_encode($menu[0]);
 }
 public function gst_priceupdate()
 {
   $data = array();
   $form = $this->security->xss_clean($_POST);
   $logme['id'] = $form['id'];
   $logme['service_id'] = $form['service'];
   $logme['price'] = $form['amount'];
   $logme['role'] = $form['role_id'];
   if ($this->common_model->update($logme,"id",$logme['id'],'gst_price')) {
     $this->session->set_flashdata(
       array(
         'status' => 1,
         'msg' => " Updated Successfully"
       )
     );
     redirect('legal/gst/price', 'refresh');
   }
 }

 public function gst_pricedelete($id)
 {
    if ($this->db->where("id", $id)->delete('gst_price')) {
      echo 1;
    } else {
      echo 0;
    }
 }
//price set end

public function payment_list()
{
 
 $data=$this->security->xss_clean($_GET);
 $gst_id_list=$data['data'];
 if($data['service']!='gst_return')
 {
  $all_gst_data=$this->common_model->select_gst_data($gst_id_list);
  $this->data['gst_data']=$all_gst_data;
  echo $this->data['main_content'] = $this->load->view('paylist',$this->data,true);
 }
 else
 {
  $all_gst_data=$this->common_model->select_gstr_data($gst_id_list);
 $this->data['gst_data']=$all_gst_data;
   echo $this->data['main_content'] = $this->load->view('paylist1',$this->data,true);  
 }
}

 //company registation start
   //company registation ui
 public function company_registation()
 {
    $this->data['param'] = $this->paremlink('add'); 
    $this->data['state']=$this->common_model->select('company_regisation_price');
    $this->data['main_content'] = $this->load->view('company_register', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);  
 }
 public function company_registration_price()
 {
  $data=$_GET;
  $data=$this->common_model->select_option($data['state'],'state','company_regisation_price');
  echo $data[count($data)-1]['price'];
 }
 //ui end
 public function company_registation_price_list()
 {
       $uri = $this->security->xss_clean($_GET);
   //   pre($uri);
   //   exit();

   if (!empty($uri)) {
     $query = '';

     $output = array();




     $data = array();

     if (isAdmin($this->session->userdata('user_roles'))) {

       $query .= "SELECT * from company_regisation_price  where 1 ";

       $recordsFiltered = $this->users_model->row_count($query);
     } else {

       $query .= "SELECT * from company_regisation_price where 1 ";

       $recordsFiltered = $this->users_model->row_count($query);
     }

     if (!empty($_GET["search"]["value"])) {
       $query .= 'AND state LIKE "%' . $_GET["search"]["value"] . '%" ';
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


       $sub_array[] = '<button type="button" class="btn btn-sm btn-info"  data-placement="bottom" onclick="Edit(' . $row['id'] . ')" title="Edit Commission Information"><i class="fa fa-pencil-alt"></i></button>
          <button type="button" class="btn btn-sm btn-primary"  data-placement="bottom" onclick="Delete(' . $row['id'] . ')" title="Delete Commission Information"><i class="fa fa-trash-alt"></i></button>';
       $sub_array[] = $row['state'];
       $sub_array[] = $row['price'];
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

 public function company_price()
 {
    $this->data['param'] = $this->paremlink('add'); 
    $api_key='POCA001';
    $data=self::company_price_api($api_key);
    $data=json_decode($data);
    if($data->status==true && $data->response==1)
    {
        $this->data['price']=$data->data;
    }
    else
    {
        $this->data['price']=$data['message'];
    }
    $this->data['main_content'] = $this->load->view('company_registation_price/add', $this->data, true);
    $this->data['is_script'] = $this->load->view('company_registation_price/script', $this->data, true);
    $this->load->view('layout/index', $this->data); 
 }
 //delete
 public function company_price_delete()
 {
     $data=$this->security->xss_clean($this->input->get());
     if($this->common_model->delete(['id'=>$data['id']],'company_regisation_price'))
     {
         echo 1;
     }
     else
     {
         echo 0;
     }
 }
 public function company_price_show()
 {
      $data=$this->security->xss_clean($_GET);
      $api_key='POCA001';
      $data=self::company_price_api($api_key,$data['data']);
      $data=json_decode($data);
      echo $data->data[0]->price;
 }
 public function company_price_submit()
 {
      $data=$this->security->xss_clean($this->input->post());
      
	  $storage=[
	            'price'=>$data['price'],
                'state'=>$data['state'],
                'created'=>date("Y-m-d h:i:sa")
                ];
      if($this->common_model->insert($storage,'company_regisation_price'))
      {
           $this->session->set_flashdata(
					array(
						'status' => 1,
					'msg' => "Add Successfully"
				  )
				);
	           redirect(base_url('legal/gst/company_price'), 'location');
      }
      else
      {
           $this->session->set_flashdata(
					array(
						'status' => 1,
					'msg' => "Issue is Created"
				  )
				);
	           redirect(base_url('legal/gst/company_price'), 'location');
      }
 }

 //api price company registation
 private function company_price_api($api_key,$state='')
 {
     $this->client = new Client();
     $headers=array('Access-Control-Allow-Origin'=>'https://vitefintech.com/viteapi/Company_price/?api_key='.$api_key.'&state='.$state);
         #guzzle
         try {
          $response = $this->client->request('GET', "https://vitefintech.com/viteapi/Company_price/?api_key=".$api_key.'&state='.$state, [
            "headers" => $headers,
          ]);
         $response=$response->getBody()->getContents();
         return $response;
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
        }
 }
 //company registation end
//comisssion start
 public function commission()
 {
   $this->data['main_content'] = $this->load->view('legal_commission/index', $this->data, true);
   $this->data['is_script'] = $this->load->view('legal_commission/script', $this->data, true);
   $this->load->view('layout/index', $this->data);
 }
 
 public function CommissionForm()
 {

   if ($_POST) {

     $data = $this->security->xss_clean($_POST);


     if (isset($data['aepsCommissionForm'])) {

       $baseRole = $data['aepsCommissionForm'];

       $service = 13;

       $this->data['role_id'] = $baseRole;
       //  $commissionList = $this->common_model->get_list($service, $baseRole);


       echo $this->load->view('legal_commission/add', $this->data, true);
     } else {
       echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
     }
   }
 }
 
 public function commissioninsert()
 {

   if ($_POST) {

     $data = $this->security->xss_clean($_POST);
     $data1['start_range'] = $data['start'];
     $data1['end_range'] = $data['end'];
     $data1['role_id'] = $data['role_id'];
     $data1['g_commission'] = $data['commision'];
     $data1['service_id'] = $data['service'];
     $data1['max_commission'] = $data['max'];
     $data1['c_flat	'] = isset($data['flat']) ? 1 : 0;
     $data1['created	'] = date('Y-m-d hh:mm:ss');
     if ($this->common_model->insert($data1, 'service_commission')) {
       $this->session->set_flashdata(
         array(
           'status' => 1,
           'msg' => " Insert Successfully"
         )
       );
       redirect('legal/gst/comission', 'refresh');
     }
   }
 }
 
 public function legal_get_list()
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

       $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id IN(53,54,55,57,58,59)";
       $recordsFiltered = $this->users_model->row_count($query);
     } else {

       $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id IN(53,54,55,57,58,59)";

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

       $sub_array[] = $this->common_model->select_option($row['service_id'],'id','services')[0]['name'];
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
 
 public function commissiondelete($id)
 {
   if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
     echo 1;
   } else {
     echo 0;
   }
 }
 
 public function commissionaddupdate()
 {

   if ($_POST) {
     $data = $this->security->xss_clean($_POST);

     if (isset($data['addupdate'])) {

       $baseRole = $data['addupdate'];
       $commissionList = $this->commission_model->get_list($data['service'], $baseRole);
       echo $this->load->view('legal_commission/edit', $this->data, true);
     } else {
       echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
     }
   }
 }
 
 public function commissionedit($id)
 {
   $menu = $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
   $data=[
          'service_commission_id'=>$menu[0]['service_commission_id'],
          'role_id'=>$menu[0]['role_id'],
          'service'=> $this->common_model->select_option($menu[0]['service_id'],'id','services')[0]['name'],
          'operator'=>$menu[0]['operator'],
          'service_id'=>$menu[0]['service_id'],
          'start_range'=>$menu[0]['start_range'],
          'end_range'=>$menu[0]['end_range'],
          'g_commission'=>$menu[0]['g_commission'],
          'max_commission'=>$menu[0]['max_commission'],
          'c_flat'=>$menu[0]['c_flat'],
          'created'=>$menu[0]['created'],
          'updated'=>$menu[0]['updated']
   ];
   echo json_encode($data);
 }
 
 public function commissionupdate()
 {
   $data = array();
   $form = $this->security->xss_clean($_POST);

   $logme['start_range'] = $form['start'];
   $logme['service_id'] = $form['service'];
   $logme['end_range'] = $form['end'];
   $logme['g_commission'] = $form['commision'];
   $field = $form['service_commission_id'];

   $logme['max_commission'] = $form['max'];
   $logme['c_flat'] = isset($form['flat']) ? 1 : 0;
   $logme['role_id'] = $form['role_id'];
   $logme['service_id'] = $form['service_id'];



   if ($this->common_model->update($logme, "service_commission_id", $field, 'service_commission')) {
     $this->session->set_flashdata(
       array(
         'status' => 1,
         'msg' => " Updated Successfully"
       )
     );
     redirect('legalservice/LegalController//commission', 'refresh');
   }
 }

 //comission end
public function stan2()
{    
  date_default_timezone_set("Asia/Calcutta");
  $today = date("H");
  $year = date("Y"); 
  $year =  $year;
  $year = substr( $year, -1);   
  $daycount =  date("z")+1;
  $ref = $year . $daycount. $today. mt_rand(100000, 999999);
  return $ref;
 // return mt_rand(99999999999, 999999999999);
}
}