<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Axis_bank extends Vite
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('menu_model');
        $this->load->model('users_model');
        $this->load->model('commission_model');

        $this->data['active'] = 'Axis Bank Account';
        $this->data['breadcrumbs'] = [array('url' => base_url('bank/axis-bank-account'), 'name' => 'Axis Bank Account')];
        $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));

    }
    public function index()
    {
         if($this->session->userdata('user_roles') != '98'){
            return redirect('/dashboard');
        }
        $this->data['bank'] = $this->users_model->get_bank($this->session->userdata('user_id'));
        $this->data['main_content'] = $this->load->view('account_open', $this->data, true);
        $this->data['is_script'] = $this->load->view('script', $this->data, true);
        $this->load->view('layout/index', $this->data);
    }

    public function generated_axis_account(){
         if($this->session->userdata('user_roles') != '98'){
            return redirect('/dashboard');
        }
        $url = 'https://vitefintech.com/viteapi/api/account-opening';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            http_build_query(
                array(
                    'member_id' => 'POCA001', // for member id, please contact to vitefintech.com.
                    'type' => 1, // 1 for saving account and 2 for current account
                    'retailer_id' => $this->session->userdata('member_id'),
                )
            )
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);
        // your modified code
       
        $output = json_decode($server_output);
        
        $this->data['output'] = $output;

        $array_data = [
            'member_id' => $this->session->userdata('member_id'),
            'user_name' => $this->session->userdata('user_name'),
            'user_roles' => $this->session->userdata('user_roles'),
            'bank_url' => $output->data,
            'type' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('bank_account_link_histroy',$array_data);
        $this->data['main_content'] = $this->load->view('account_opening_link', $this->data, true);
        $this->data['is_script'] = $this->load->view('script', $this->data, true);
        $this->load->view('layout/index', $this->data);
    }

    public function fetch_bank_url_history()
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
                        $query .= "SELECT * FROM `bank_account_link_histroy`  where member_id = '".$this->session->userdata('member_id') ."'";

                        break;

                    default:
                        $query .= "SELECT * FROM `bank_account_link_histroy` where member_id = '".$this->session->userdata('member_id') ."'";

                        break;
                }


                if (!empty($_GET["search"]["value"])) {
                    $query .= 'OR  LIKE "%' . $_GET["search"]["value"] . '%" ';
                    $query .= 'OR mp.data_sub_menu LIKE "%' . $_GET["search"]["value"] . '%" ';
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
                    $sub_array[] = $row['user_name'];

                    if($row['user_roles'] == 98){
                        $sub_array[] = 'Retailer';
                    }
                    $sub_array[] = $row['bank_url'];
                    if($row['type'] == 1){
                        $sub_array[] = 'Saving Account';
                    } else if($row['type'] == 2){
                        $sub_array[] = 'Current Account';
                    }
                    if($row['status'] == '0'){
                        $sub_array[] = '<span class="badge badge-danger">Inactive</span>';
                    } else if($row['status'] == '1'){
                        $sub_array[] = '<span class="badge badge-success">Activective</span>';
                    }
                    
                    $sub_array[] = $row['created_at'];
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
    
    public function bank_commission()
    {
       if($this->session->userdata('user_roles') == '98'){
            return redirect('/dashboard');
        }
    $this->data['main_content'] = $this->load->view('bank_commission/index', $this->data, true);
    $this->data['is_script'] = $this->load->view('bank_commission/script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }
  
    public function bank_CommissionForm()
    {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);


      if (isset($data['aepsCommissionForm'])) {

        $baseRole = $data['aepsCommissionForm'];

        $service = 50;

        $this->data['role_id'] = $baseRole;
        //  $commissionList = $this->common_model->get_list($service, $baseRole);


        echo $this->load->view('bank_commission/add', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  
    public function b_commissioninsert()
    {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      $data1['start_range'] = 0;
      $data1['operator'] = $data['operator'];
      $data1['end_range'] = 0;
      $data1['role_id'] = $data['role_id'];
      $data1['g_commission'] = $data['commision'];
      $data1['service_id'] = $data['service_id'];
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
        redirect('bank/commission', 'refresh');
      }
    }
  }
  
    public function bank_get_list()
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

        $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id = 50 ";

        $recordsFiltered = $this->users_model->row_count($query);
      } else {

        $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = 50 ";

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

        $sub_array[] = $row['operator'];
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
  
    public function b_commissiondelete($id)
    {
    if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
      echo 1;
    } else {
      echo 0;
    }
  }
  
    public function b_commissionaddupdate()
    {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);
      if (isset($data['addupdate'])) {

        $baseRole = $data['addupdate'];

        $service = 18;

        $commissionList = $this->commission_model->get_list($service, $baseRole);


        echo $this->load->view('bank_commission/edit', $this->data, true);
      } else {
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }
    }
  }
  
    public function b_commissionedit($id)
    {
    $menu = $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
    echo json_encode($menu[0]);
  }
  
    public function b_commissionupdate()
    {
    $data = array();
    $form = $this->security->xss_clean($_POST);

    $logme['start_range'] = $form['start'];
    $logme['operator'] = $form['operator'];
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
      redirect('bank/commission', 'refresh');
    }
  }
  
    public function share_commission_account()
    {
       if($this->session->userdata('user_roles') == '98'){
            return redirect('/dashboard');
        }
        $this->data['main_content'] = $this->load->view('distribute_commission', $this->data, true);
        $this->data['is_script'] = $this->load->view('script', $this->data, true);
        $this->load->view('layout/index', $this->data);
  }
 
    public function distribute_commission(){
  
    if ($_POST) {
      $data = $this->security->xss_clean($_POST);
      if(isset($data['vendor']) && !empty($data['vendor']) && isset($data['quantity']) && !empty($data['quantity']) && isset($data['created']) && !empty($data['created'])){
       $user=$this->common_model->select_option($data['vendor'],'member_id','user');
       if(count($user)>0)
       {
        if($user[0]['role_id']==98)
        {
         if(self::commition_distribute($user[0]['user_id'],50,0,"OPENING_ACCOUNT",$data['created'],$data['quantity']))
         {
            $this->session->set_flashdata(
          array(
                  'status' => 0,
                  'msg' =>"Commission distribute successfully",
          )); 
        }
         else
         {
          $this->session->set_flashdata(
          array(
                  'status' => 0,
                  'msg' =>"Some issue has been crated",
          ));   
         }
        }
        else
        {
            $this->session->set_flashdata(
          array(
                  'status' => 0,
                  'msg' =>"Not fount this Retailer Id",
          )); 
        }
       }
       else
       {
        $this->session->set_flashdata(
          array(
                  'status' => 0,
                  'msg' =>"Not fount this member Id",
          )); 
       }
    }
      redirect('bank/share_commission', 'refresh');        
   }
}
    
    //commission distribute
    public function commition_distribute($id, $service, $transection, $operator,$created,$quantity)
    {
    $parentsList = self::checkparent($id);
    $i = 0;
    $j = 0;
    $allwallet = [];
    for($j=0;$j<$quantity;$j++)
    {
    foreach ($parentsList as $key => $value) {
      $commision = $this->commission_model->get_commision_by_role_account($value['role_id'], $service, $transection, $operator);
      if (!empty($commision)) {
        $userWallet = $this->common_model->get_user_wallet_balance($value['user_id']);

        if ($userWallet != 'none') {
            $amountc = $commision[$i]['g_commission'];

            $updateBalance = $userWallet->balance + $commision[$i]['g_commission'];    // add commission
            $updateWallet = [
              'balance' => $updateBalance,
            ];
          if ($this->common_model->update($updateWallet, 'member_id', $value['user_id'], 'wallet')) {
            $message = [
              'msg' => 'Your wallet balance credited ' . $amountc . ' available balance is ' . $updateBalance,
              'user_id' => $value['user_id']
            ];
            $this->set_notification($message);
            $logme = [
              'wallet_id' => $userWallet->wallet_id,
              'member_to' =>  $value['user_id'],
              'member_from' =>  $value['parent'],
            //   'amount' =>  $transection,
              //   'surcharge' => $data['surcharge'],
            //  'refrence' =>  'Recharge'.self::stan2(),
            //  'transection_id'=>$this->data['saveTransection']['transection_id'],
              'commission' =>  $amountc,
              'service_id' => $service,
              'stock_type' => "ACTOP",
              'status' => 'success',
              'balance' =>  $userWallet->balance,
              'closebalance' => $updateBalance,
              'type' => 'credit',
              'mode' => "Account Opening",
              'bank' => "Account Opening",
              'narration' => "Account Opening Created on ".$created,
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
    }
    return Json_encode($allwallet);
  }
    //parent checker
    public function checkparent($id, &$parents = array(), $level = 1)
    {
    $data = $this->users_model->get_parent_recharge($id);
    if (isset($data)) {
      $parents[$level]['user_id'] = $data->user_id;
      $parents[$level]['member_id'] = $data->member_id;
      $parents[$level]['parent'] = $data->parent;
      $parents[$level]['role_id'] = $data->role_id;
      // echo $data['parent'];

      self::checkparent($data->parent, $parents, $level + 1);
    }
    return $parents;
  }
  
    public function stan2( ) {
     
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
