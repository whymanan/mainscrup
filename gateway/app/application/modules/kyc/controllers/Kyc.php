<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Kyc extends Vite
{
    public $data = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('menu_model');
        $this->load->model('users_model');
        $this->data['active'] = 'kyc';
        $this->data['breadcrumbs'] = [array('url' => base_url('kyc'), 'name' => 'Kyc')];
        $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));
        
        $this->load->helper('url');
         $this->load->library('zip');
    }

    public function index()
    {
        $this->data['main_content'] = $this->load->view('index', $this->data, true);
        $this->data['is_script'] = $this->load->view('script', $this->data, true);
        $this->load->view('layout/index', $this->data);
    }
 public function get_info()
    {
        $uri = $this->security->xss_clean($_POST);
        $this->data['user'] = $this->common_model->select_user_option($uri['vendor']);
        $this->data['main_content'] = $this->load->view('details', $this->data, true);
       echo $this->data['main_content'];
    }
public function update_info()
    {
        $uri = $this->security->xss_clean($_POST);
         if ($uri)
    		{
    		    $id=$uri['id'];
    			$data=array(
    				'mid'=>$uri['mid'],
    				'tid'=>$uri['tid'],
    			
    			);
    			//print_r($data);exit;
    			$this->common_model->update($data,"user_id",$id,'user');
				
    			redirect(base_url("kyc"));

    		}
    }
    public function edit()
    { //
        $this->data['param'] = $this->paremlink('/');
        $uri = $this->security->xss_clean($_GET);
        if (isset($uri['q']) && !empty($uri['q'])) {
            $uid = $uri['q'];
            if (!$this->common_model->exists('user', ['user_id' => $uid])) {
                exit('User dosn\'t Exist');
            }
        }
        $this->data['user'] = $this->common_model->select_user_option($uri['q']);

        $this->data['bank'] = $this->users_model->get_user_bank($uri['q']);
        $docs = $this->common_model->select_user_doc($uri['q']);

        if ($docs) {
            foreach ($docs as $key => $value) {
                $this->data['docs'][$value['type']] = $value;
            }
        }
    //pre($docs);exit;
        $this->data['main_content'] = $this->load->view('profile', $this->data, true);
        $this->data['is_script'] = $this->load->view('script', $this->data, true);
        
        $this->load->view('layout/index', $this->data);
    }
    public function get_userdocs_by_type($doc)
    {
        $data=array();
        foreach ($doc as  $row) {
            $data[]= $row['type'];
        }
        return $data;
    }

    public function get_users()
    {
        $uri = $this->security->xss_clean($_GET);
        if (!empty($uri)) {
            $query = '';

            $output = array();


            $list = $uri['list'];

            $data = array();

            if (isAdmin($this->session->userdata('user_roles'))) {
                $query .= "SELECT user.*,user_detail.first_name,user_detail.last_name, u.member_id as parent1,roles.role from user LEFT JOIN user as u on u.user_id=user.parent
            Join user_detail on user_detail.fk_user_id=user.user_id
          Join roles on roles.roles_id=user.role_id WHERE 1 ";

                $recordsFiltered = $this->users_model->row_count($query);
            } else {
                $query .= "SELECT user.*,user_detail.first_name,user_detail.last_name, u.member_id as parent1,roles.role from user LEFT JOIN user as u on u.user_id=user.parent
            Join user_detail on user_detail.fk_user_id=user.user_id

          Join roles on roles.roles_id=user.role_id WHERE user.parent = '{$duid}'";

                $recordsFiltered = $this->users_model->row_count($query);
            }

            switch ($list) {



          case 'new':
        $query .= " AND user.kyc_status = 'new' ";


          break;



                case 'reject':

                    $query .= " AND user.kyc_status = 'reject' ";

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
            //echo $query;exit;
            if (!empty($_GET["search"]["value"])) {
                $query .= ' AND( user.member_id LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= ' OR u.member_id LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= ' OR user.phone LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= ' OR user.email LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= ' OR user.user_status LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= ' OR user.kyc_status LIKE "%' . $_GET["search"]["value"] . '%" )';
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
                $status = '';
                $kyc = '';
                if ($row['kyc_status'] == 'verify') {
                    // code...
                    $kyc = '<i class="fa fa-circle text-success font-12" data-toggle="tooltip" data-placement="top" title="" data-original-title="Active"></i>';
                } elseif ($row['kyc_status'] == 'reject') {
                    $kyc = '<i class="fa fa-circle text-warning font-12" data-toggle="tooltip" data-placement="top" title="" data-original-title="Rejected"></i>';
                } else {
                    $kyc = '<i class="fa fa-circle text-warning font-12" data-toggle="tooltip" data-placement="top" title="" data-original-title="Pending"></i>';
                }
                if ($row['user_status'] == 'active') {
                    $status = '<span class="badge badge-success">Active</span>';
                } elseif ($row['user_status'] == 'pending') {
                    $status = '<span class="badge badge-warning">Pending</span>';
                } else {
                    $status = '<span class="badge badge-danger">Deactive</span>';
                }

                    if ($row['kyc_status'] == 'verify') {
                          $sub_array[] = '<a href="' . base_url('kyc/edit?q=') . $row['user_id'] . '"> <button type="button" class="btn btn-sm btn-success"  data-placement="bottom" title="Edit Menu Information">Verify</button></a>
                          <a href="' . base_url('users/kyc?q=') . $row['user_id'] . '"> <button type="button" class="btn btn-sm btn-primary"  data-placement="bottom" title="Upload Kyc">Update KYC</button></a>   
                          <a href="' . base_url('kyc/export?q=') . $row['member_id'] . '"> <button type="button" class="btn btn-sm btn-danger"  data-placement="bottom" title="Edit Menu Information">Export</button></a> ';
                        }

                else{
                         $sub_array[] = '<a href="' . base_url('kyc/edit?q=') . $row['user_id'] . '"> <button type="button" class="btn btn-sm btn-success"  data-placement="bottom" title="Edit Menu Information">Verify</button></a>
                         <a href="' . base_url('users/kyc?q=') . $row['user_id'] . '"> <button type="button" class="btn btn-sm btn-primary"  data-placement="bottom" title="Upload Kyc">Update KYC</button></a>';   
                     }
                $sub_array[] = $row['member_id'];
                $sub_array[] = $row['parent1'];
                $sub_array[] = $row['first_name']."  ".$row['last_name'];
                $sub_array[] = $row['phone'];
                $sub_array[] = $row['role'];
                $sub_array[] = $status;
                $sub_array[] = $kyc;
                $sub_array[] = $row['created_at'];


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

    public function change_status($status, $id)
    {
        $data=array();
        if ($this->users_model->change_status($status, $id)) {
            if ($_POST && $status== "reject") {
                $reason= $_POST['reason'];
                $stat = "REJECTED";
            } else {
                $stat = "ACCEPTED";
                $reason="";
            }
            $data['created_at']=current_datetime();
            $data['created_by'] =$_SESSION['user_id'];
            $data['kyc_status']= $stat;
            $data['reason'] =  $reason;
            $data['fk_user_id'] = $id;
            // pre($data);exit;
            $Id= $this->common_model->insert($data, "kyc");
            if ($Id) {
                $this->session->set_flashdata(array('error' => 0, 'msg' => 'KYC '.$stat));
            } else {
                $this->session->set_flashdata(array('error' => 1, 'msg' => 'kyc '.$stat));
            }
        } else {
            $this->session->set_flashdata(array('error' => 1, 'msg' => 'Something went wrong '));
        }



        redirect('kyc', 'refresh');
    }

    public function live_count()
    {
        $uri = $this->security->xss_clean($_GET);

        if (isset($uri['key']) && !empty($uri['key'])) {
            $data = array();

            $duid = $uri['key'];

            if (isAdmin($this->session->userdata('user_roles'))) {
                $result = $this->users_model->totel_count();
            } else {
                $result = $this->users_model->totel_count($duid);
            }

            foreach ($result as $value) {
                $data[$value->kyc_status] = number_format($value->totel, 0);
            }

            echo json_encode($data);
        }
    }

    

    public function add()
    {
        
         $this->data['main_content'] = $this->load->view('add', $this->data, true);
        $this->data['is_script'] = $this->load->view('script', $this->data, true);
        $this->load->view('layout/index', $this->data);

    }
    
     public function bank()
    {
        
         $this->data['main_content'] = $this->load->view('kyc', $this->data, true);
        $this->data['is_script'] = $this->load->view('script', $this->data, true);
        $this->load->view('layout/index', $this->data);

    }
    
     public function get_user_detail()
    {
        $uri = $this->security->xss_clean($_POST);
        if($this->common_model->check_kyc_status($uri['id'])=='pending'  ){
         
          echo 1;
        }else{
           $data['bank'] = $this->common_model->select_user_option($uri['id']);
        $data['main_content'] = $this->load->view('user/kyc', $this->data, true);
         echo $data['main_content'];   
        }
       
    }
  
  public function createxls() {
 $fileName = 'employee.xlsx';
 $usersData = $this->users_model->exportList();
 // pre($usersData);exit;
 $spreadsheet = new Spreadsheet();
 $sheet = $spreadsheet->getActiveSheet();
 $sheet->setCellValue('A1', 'S.No');
 $sheet->setCellValue('B1', 'Barcode');
 $sheet->setCellValue('C1', 'Member Id');
 $sheet->setCellValue('D1', 'TID');
 $sheet->setCellValue('E1', 'ME Name');
 $sheet->setCellValue('F1', 'DBA Name');
 $sheet->setCellValue('G1', 'Business Type');
 $sheet->setCellValue('H1' , 'Contact Person Name');
 $sheet->setCellValue('I1' , 'Contact Person No.');
 $sheet->setCellValue('J1', 'kyc_status');

 $sheet->setCellValue('K1' , 'organisation');
 $sheet->setCellValue('L1' , 'Deployment address');
 $sheet->setCellValue('M1' , 'city');
 $sheet->setCellValue('N1' , 'state');
 $sheet->setCellValue('O1' , 'Pincode');
 $sheet->setCellValue('P1' , 'home_address');
 $sheet->setCellValue('Q1' , 'home_state');
 $sheet->setCellValue('R1' , 'home_city');
 $sheet->setCellValue('S1' , 'home_pincode');
 $sheet->setCellValue('T1' ,'area');
 $sheet->setCellValue('U1' , 'home_area');
 $sheet->setCellValue('V1' , 'account_holder_name');
 $sheet->setCellValue('W1' , 'Bank account no');
 $sheet->setCellValue('X1' , 'Bank Name');
 $sheet->setCellValue('Y1' , 'Bank Branch Name');
 $sheet->setCellValue('Z1' , 'IFSC code');
 
 
 $sheet->setCellValue('AA1' , 'Agreegator PAN');
 $sheet->setCellValue('AB1' , 'Sub merchant PAN NO.');
 $sheet->setCellValue('AC1' , 'agreegator  GST no.');
 $sheet->setCellValue('AD1' , 'Agreegator GST Address');
 $sheet->setCellValue('AE1' , 'Agreegator GST Pincode');
 $sheet->setCellValue('AF1' , 'No of terminal');
 $sheet->setCellValue('AG1' , 'Model');
 $sheet->setCellValue('AH1', 'Business Category');
 $sheet->setCellValue('AI1', 'Business Entity');
 $sheet->setCellValue('AJ1' , 'MCC');
 $sheet->setCellValue('AK1' , 'Domestic (Debit card) ATS (0 - 2000)');
 $sheet->setCellValue('AL1' , 'Domestic (Debit card) ATS:(> Rs.2000)');
 $sheet->setCellValue('AM1' , 'Non premium MDR');
 $sheet->setCellValue('AN1' , 'Premium MDR');
 $sheet->setCellValue('AO1' , 'International MDR');
 $sheet->setCellValue('AP1' , 'REQUIRED PAYMENT MODE');

 $rows = 2;
 foreach ($usersData as $val){
 $sheet->setCellValue('A'. $rows, $rows);
 $sheet->setCellValue('B'. $rows, '');
 $sheet->setCellValue('C' . $rows, $val['member_id']);
 $sheet->setCellValue('D' . $rows, "");
 $sheet->setCellValue('E' . $rows, "");
 $sheet->setCellValue('F' . $rows, "");
 $sheet->setCellValue('G' . $rows, "");

 $sheet->setCellValue('H' . $rows, $val['first_name']." ".$val['last_name']);
 $sheet->setCellValue('I' . $rows, $val['phone']);
 $sheet->setCellValue('J' . $rows, $val['kyc_status']);

 $sheet->setCellValue('K' . $rows, $val['organisation']);
 $sheet->setCellValue('L' . $rows, $val['address']);
 $sheet->setCellValue('M' . $rows, $val['city']);
 $sheet->setCellValue('N' . $rows, $val['state']);
 $sheet->setCellValue('O' . $rows, $val['pincode']);
 $sheet->setCellValue('P' . $rows, $val['home_address']);
 $sheet->setCellValue('Q' . $rows, $val['home_state']);
 $sheet->setCellValue('R' . $rows, $val['home_city']);
 $sheet->setCellValue('S' . $rows, $val['home_pincode']);
 $sheet->setCellValue('T' . $rows, $val['area']);
 $sheet->setCellValue('U' . $rows, $val['home_area']);
 $sheet->setCellValue('V' . $rows, $val['account_holder_name']);
 $sheet->setCellValue('W' . $rows, $val['account_no']);
 $sheet->setCellValue('X' . $rows, $val['bank_name']);
 $sheet->setCellValue('Y' . $rows,'');
 $sheet->setCellValue('Z' . $rows, $val['ifsc_code']);
 $sheet->setCellValue('AA' . $rows, $val['pan']);
 $rows++;
 }
 $writer = new Xlsx($spreadsheet);
 $writer->save("uploads/".$fileName);
 header("Content-Type: application/vnd.ms-excel");
 redirect(base_url()."/uploads/".$fileName);
 }
 
 public function exls($user_id) {
 $fileName = 'users.xlsx';
 $usersData = $this->users_model->kycdata($user_id);
//  pre($usersData);exit;
 $spreadsheet = new Spreadsheet();
 $sheet = $spreadsheet->getActiveSheet();
 $sheet->setCellValue('A1', 'S.No');
 $sheet->setCellValue('B1', 'Barcode');
 $sheet->setCellValue('C1', 'Member Id');
 $sheet->setCellValue('D1', 'TID');
 $sheet->setCellValue('E1', 'ME Name');
 $sheet->setCellValue('F1', 'DBA Name');
 $sheet->setCellValue('G1', 'Business Type');
 $sheet->setCellValue('H1' , 'Contact Person Name');
 $sheet->setCellValue('I1' , 'Contact Person No.');
 $sheet->setCellValue('J1', 'kyc_status');

 $sheet->setCellValue('K1' , 'organisation');
 $sheet->setCellValue('L1' , 'Deployment address');
 $sheet->setCellValue('M1' , 'city');
 $sheet->setCellValue('N1' , 'state');
 $sheet->setCellValue('O1' , 'Pincode');
 $sheet->setCellValue('P1' , 'home_address');
 $sheet->setCellValue('Q1' , 'home_state');
 $sheet->setCellValue('R1' , 'home_city');
 $sheet->setCellValue('S1' , 'home_pincode');
 $sheet->setCellValue('T1' ,'area');
 $sheet->setCellValue('U1' , 'home_area');
 $sheet->setCellValue('V1' , 'account_holder_name');
 $sheet->setCellValue('W1' , 'Bank account no');
 $sheet->setCellValue('X1' , 'Bank Name');
 $sheet->setCellValue('Y1' , 'Bank Branch Name');
 $sheet->setCellValue('Z1' , 'IFSC code');
 
 
 $sheet->setCellValue('AA1' , 'Agreegator PAN');
 $sheet->setCellValue('AB1' , 'Sub merchant PAN NO.');
 $sheet->setCellValue('AC1' , 'agreegator  GST no.');
 $sheet->setCellValue('AD1' , 'Agreegator GST Address');
 $sheet->setCellValue('AE1' , 'Agreegator GST Pincode');
 $sheet->setCellValue('AF1' , 'No of terminal');
 $sheet->setCellValue('AG1' , 'Model');
 $sheet->setCellValue('AH1', 'Business Category');
 $sheet->setCellValue('AI1', 'Business Entity');
 $sheet->setCellValue('AJ1' , 'MCC');
 $sheet->setCellValue('AK1' , 'Domestic (Debit card) ATS (0 - 2000)');
 $sheet->setCellValue('AL1' , 'Domestic (Debit card) ATS:(> Rs.2000)');
 $sheet->setCellValue('AM1' , 'Non premium MDR');
 $sheet->setCellValue('AN1' , 'Premium MDR');
 $sheet->setCellValue('AO1' , 'International MDR');
 $sheet->setCellValue('AP1' , 'REQUIRED PAYMENT MODE');

 $rows = 2;
 foreach ($usersData as $val){
 $sheet->setCellValue('A'. $rows, $rows);
 $sheet->setCellValue('B'. $rows, '');
 $sheet->setCellValue('C' . $rows, $val['member_id']);
 $sheet->setCellValue('D' . $rows, "");
 $sheet->setCellValue('E' . $rows, "");
 $sheet->setCellValue('F' . $rows, "");
 $sheet->setCellValue('G' . $rows, "");

 $sheet->setCellValue('H' . $rows, $val['first_name']." ".$val['last_name']);
 $sheet->setCellValue('I' . $rows, $val['phone']);
 $sheet->setCellValue('J' . $rows, $val['kyc_status']);

 $sheet->setCellValue('K' . $rows, $val['organisation']);
 $sheet->setCellValue('L' . $rows, $val['address']);
 $sheet->setCellValue('M' . $rows, $val['city']);
 $sheet->setCellValue('N' . $rows, $val['state']);
 $sheet->setCellValue('O' . $rows, $val['pincode']);
 $sheet->setCellValue('P' . $rows, $val['home_address']);
 $sheet->setCellValue('Q' . $rows, $val['home_state']);
 $sheet->setCellValue('R' . $rows, $val['home_city']);
 $sheet->setCellValue('S' . $rows, $val['home_pincode']);
 $sheet->setCellValue('T' . $rows, $val['area']);
 $sheet->setCellValue('U' . $rows, $val['home_area']);
 $sheet->setCellValue('V' . $rows, $val['account_holder_name']);
 $sheet->setCellValue('W' . $rows, $val['account_no']);
 $sheet->setCellValue('X' . $rows, $val['bank_name']);
 $sheet->setCellValue('Y' . $rows,'');
 $sheet->setCellValue('Z' . $rows, $val['ifsc_code']);
 $rows++;
 }
 $writer = new Xlsx($spreadsheet);
 $writer->save("uploads/".$fileName);
 header("Content-Type: application/vnd.ms-excel");
 //redirect(base_url()."/uploads/".$fileName);
 }
 
 function export(){
      $this->data['param'] = $this->paremlink('/');
      $uri = $this->security->xss_clean($_GET);
    
      if (isset($uri['q']) && !empty($uri['q'])) {
      $uid = $uri['q'];
      if (!$this->common_model->exists('user', ['member_id' => $uid])) {
        exit('User dosn\'t Exist');
      }
    }
      $data = $this->users_model->exportsingle($uri['q']);
      $user_id = $data[0]['user_id'] ;

      $images = $this->users_model->images($user_id); 
      $filepath1 = FCPATH.'/uploads/adhar_front/'.$images[1]['name'].'';
      $filepath3 = FCPATH.'/uploads/adhar_back/'.$images[2]['name'].'';
      $filepath4 = FCPATH.'/uploads/pan/'.$images[3]['name'].'';
      $filepath5 = FCPATH.'/uploads/passbook/'.$images[4]['name'].'';
      $filepath6 = FCPATH.'/uploads/photo/'.$images[0]['name'].'';
      
    // $filepath2 = $this->zip->read_file( );
      
   //   $filepath2 = FCPATH.'/uploads/users.xlsx';




    
    
       $filepath2 =    $this->zip->add_data($this->exls($user_id)); 
         
       
       $this->zip->read_file($filepath1);
       $this->zip->read_file($filepath2);
       $this->zip->read_file($filepath3);
       $this->zip->read_file($filepath4);
       $this->zip->read_file($filepath5);
       $this->zip->read_file($filepath6);
       // Download
       $filename = ''.$data[0]['member_id'].'.zip';
       
       $this->zip->download($filename);
 
        $filename = ''.$data[0]['member_id'].'.zip';
       $path = 'uploads';
       $this->zip->read_dir($path);
       $this->zip->archive(FCPATH.'/archivefiles/'.$filename);
       $this->zip->download($filename);
    
   
    $this->load->view('index');

    }

}