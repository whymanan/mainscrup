<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;
require APPPATH.'/libraries/REST_Controller.php';

class Bank extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('users_model');
        $this->load->model('commission_model');
    }
    public function generated_axis_account_post(){
        $data = $this->security->xss_clean($this->input->post());
        if(!empty($data['member_id']) && !empty($data['submerchant_id']))
        {
             if($this->common_model->member_id($data['member_id'])==1)
             {
                 if($this->common_model->member_id($data['submerchant_id'])==1)
                 {
                     $user=$this->common_model->select_option($data['submerchant_id'],'member_id','user');
                     $user_details=$this->common_model->select_option($user[0]['user_id'],'fk_user_id','user_detail');
                     $full_name=$user_details[0]['first_name']." ".$user_details[0]['last_name'];
                     $url = 'https://vitefintech.com/viteapi/api/account-opening';
                     $ch = curl_init();
                     curl_setopt($ch, CURLOPT_URL, $url);
                     curl_setopt($ch, CURLOPT_POST, 1);
                     curl_setopt(
                      $ch,
                    CURLOPT_POSTFIELDS,
                     http_build_query(
                    array(
                    'member_id' => $data['member_id'], // for member id, please contact to vitefintech.com.
                    'type' => 1, // 1 for saving account and 2 for current account
                    'retailer_id' => $data['submerchant_id'],
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
                        'member_id' => $data['submerchant_id'],
                        'user_name' => $full_name,
                        'user_roles' => $user[0]['role_id'],
                         'bank_url' => $output->data,
                           'type' => 1,
                        'created_at' => date('Y-m-d H:i:s')
                       ];
                     if($this->db->insert('bank_account_link_histroy',$array_data))
                     {
                         echo $server_output;
                     }
                 }
                 else
                 {
                     echo json_encode(['status'=>0,'response_code'=>11,'message'=>'Access denie for Submerchant']); 
                 }
             }
             else
             {
                echo json_encode(['status'=>0,'response_code'=>12,'message'=>'Access denie for Member']);
             }
        }
        else
        {
            echo Json_encode(['status'=>5,'response'=>5,'massage'=>'All field is Mandatory']);
        }
    }
}
?>