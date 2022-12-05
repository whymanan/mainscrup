<?php
defined('BASEPATH') or exit('No direct script access allowed');

use GuzzleHttp\Client;

class Pancard extends vite{
    public $data = array();
    public $client;

    public function __construct()
    {
        parent::__construct();
        $this->tnxType = 'panTx' ;
        $this->load->model('common_model');
        $this->load->model('commission_model');
        $this->load->model('users_model');
        $this->data['serid'] = $this->common_model->service_get('Pancard')->id;
        $this->data['active'] = 'pancard';
        $this->data['bal'] = $this->common_model->wallet_balance($this->session->userdata('user_id'));
        $this->api_key = $this->common_model->key_details()->member_id ;  
        $this->ClientId = $this->common_model->key_details()->client_id ;
        $this->Secret = $this->common_model->key_details()->secret_id ;

    }

    public function registration(){
        
        $this->data['main_content'] = $this->load->view('registration', $this->data, true);
        $this->data['is_script'] = $this->load->view('script', $this->data, true);
        $this->load->view('layout/index', $this->data);
    }

    public function register_agent(){

      if($data = $this->security->xss_clean($_POST)){
            $query = $this->common_model->panagent_check($data['agent_id']);
                
            if(!$query){  
                
                $save =  [
        
                          'full_name' => $data['name'] ,
                          'member_id' => $data['agent_id'] ,
                          'mobileNo' => $data['mobileNo'] ,
        
                          'email' => $data['email'] ,
                          'company' => $data['company'] ,
                          'pincode' => $data['pincode'] ,
                          
                          'address' => $data['address'] ,
                          'state' => $data['state'] ,
                          'aadharNO' => $data['aadharNO'] , 
                          
                          'panNo' => $data['panNo'] ,
                          'created' =>  date('Y-m-d H:i:s') 
        
        
                        ] ;
    
                if($panid = $this->common_model->insert($save , 'pan_agent'))  {
                
                    $response = self::panRegister($data);
                    $result = json_decode($response);
                    
                    if(isset($result->status)){
                        if($result->status == "SUCCESS"){
                                        
                            $action =  [
                                                    
                                        'msg' => $result->data->message ,
                                        'status' => $result->data->status ,
                                        'response' => $response
                                            
                                                    ];
                              $this->common_model->update($action, 'pan_id', $panid, 'pan_agent');
                               
            
                                $this->session->set_flashdata(
                                  array(
                                    'status' => 1,
                                    'msg' => $result->status
                                  )
                                );
                              redirect('pancard/AgentRegistration', 'refresh');
                                        
                        }else{
                                        
                              $this->session->set_flashdata(
                                  array(
                                    'status' => 1,
                                    'msg' => " Server Not Reponse "
                                  )
                                );
                            //   redirect('pancard/pancard/registration', 'refresh');
                                        
                        }
                    }else{
                        $this->session->set_flashdata(
                        array(
                          'status' => 1,
                          'msg' => " Something Went Wrong"
                        )
                      );
                        redirect('pancard/AgentRegistration', 'refresh');
                    }    
        
                }else{
        
                  $this->session->set_flashdata(
                    array(
                      'status' => 1,
                      'msg' => " Something Went Wrong"
                    )
                  );
                  redirect('pancard/AgentRegistration', 'refresh');
        
                }   
                
            }else{
                    
                    $this->session->set_flashdata(
                      array(
                        'status' => 1,
                        'msg' => "You Are Already Register"
                      )
                    );
                  redirect('pancard/AgentRegistration', 'refresh');
                    
            }       

      }else{

        $this->session->set_flashdata(
          array(
            'status' => 1,
            'msg' => "Something Went Wrong"
          )
        );
      redirect('pancard/AgentRegistration', 'refresh');

      }

    }
    
    private function panRegister($data){
        
        $token = json_decode(self::token());
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://vitefintech.com/viteapi/api/pan/agent',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('api_key' => $this->api_key,'name' => $data['name'],'agent_id' => $data['agent_id'],'mobile' => $data['mobileNo'],'email' => $data['email'],'shop' => $data['company'],'address' => $data['address'],'state' => $data['state'],'pincode' => $data['pincode'],'aadharNO' => $data['aadharNO'],'panNo' => $data['panNo'] ),
          CURLOPT_HTTPHEADER => array(
            'token:'.$token->token,
            'Secret-key:'.$this->Secret,
            'Client-Secret:'.$this->Secret
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;


    }

    private function token(){
        
      $this->client = new Client();
       
           $url = "https://vitefintech.com/viteapi/auth/token";
           
           $send = [
               
               'partnerId' => $this->api_key,
               'Client-Secret' => $this->Secret
               
               ];
           
         #guzzle
           try {
               $response = $this->client->request('POST', $url, [
               'form_params' => $send
             ]);
           
             return $response->getBody()->getContents();
           
           } catch (GuzzleHttp\Exception\BadResponseException $e) {
             #guzzle repose for future use
             $response = $e->getResponse();
             $responseBodyAsString = $response->getBody()->getContents();
             echo $responseBodyAsString ;
         
           }
       
    }
    
    public function get_amount() {
        
        $data = $this->security->xss_clean($_GET);
            
            if($data['type'] == "ecoupon"){
                $type =  2 ;
            }else{
                $type = 1 ;
            }
            
            $query = "select pan_coupon_amount from pan_coupon_amount where pan_coupon_type = '{$type}' " ;
            $sql = $this->db->query($query);
            $this->data['bal'] = $sql->row();
            echo $this->data['bal']->pan_coupon_amount;
            
  
    }

    public function coupon_request(){
      $this->data['coupon_amount'] = $this->common_model->select('pan_coupon_amount');
      $this->data['main_content'] = $this->load->view('coupon_request', $this->data, true);
      $this->data['is_script'] = $this->load->view('script', $this->data, true);
      $this->load->view('layout/index', $this->data);
    }

    public function coupon_buy(){

      if($data = $this->security->xss_clean($_POST)){

        // e-coupon = 1
        // p-coupon = 2

          if($data['type'] == "ecoupon" || $data['type'] == "pcoupon" ) {

                if($data['type'] == "ecoupon") {

                    $query = "select * from pan_coupon_amount where pan_coupon_type = '2' " ;
                    $sql = $this->db->query($query);
                    $result = $sql->row();
                    $send = [
                      
                      'type' => 2,
                      'qty' => $data['coupon_qty'],
                      
                    ];
                    $amount =  $data['coupon_qty'] * $result->pan_coupon_amount   ; 

                }else{

                    $query = "select * from pan_coupon_amount where pan_coupon_type = '1' " ;
                    $sql = $this->db->query($query);
                    $result = $sql->row();
                    
                    $send = [

                      'type' => 1,
                      'qty' => $data['coupon_qty'],

                    ];

                    $amount =  $data['coupon_qty'] * $result->pan_coupon_amount ;

                }

                if( $this->data['bal']>0 && $this->data['bal']>$amount){
                          $response = self::coupon_serivice($send);
                          $result = json_decode($response);
                          
                        if(isset($result->data)){
                            $save = [
                                            "pan_order_id" => $result->data->order_id,
                                            "submember_id" => $result->data->vle_id,
                                            "pan_agent_name" => $result->data->vle_name,
                                            "pan_coupon_type" => $result->data->type,
                                            "pan_coupon_qty" => $result->data->qty,
                                            "pan_coupon_rate" => $result->data->rate,
                                            "pan_coupon_amount" => $result->data->amount,
                                            "pan_coupon_old_bal" => $result->data->old_bal,
                                            "pan_coupon_new_bal" => $result->data->new_bal,
                                            "message" => $result->data->message,
                                            "status" => $result->data->status,
                                            "response"   => $response ,
                                            "created" => $result->data->date,
                                        ];
                                    
                            $this->common_model->insert($save , 'pan_coupon') ;

                            if ($result->status == 'SUCCESS') {
                                
                              $userWallet = $this->common_model->get_user_wallet_balance($this->session->userdata('user_id'));

                              if($userWallet != 'none') {
                                  $updateBalance = $userWallet->balance - $amount;    //Deduct balance
                                  $updateWallet = [
                                                      'balance' => $updateBalance,
                                                  ];
                                  if($this->common_model->update($updateWallet, 'member_id',$this->session->userdata('user_id'), 'wallet')) { //update deducted balance
                                    $message = [
                                      'msg' => 'Your wallet balance debited Rs. ' . $amount. ' available balance is ' . $updateBalance,
                                      'user_id' => $this->session->userdata('user_id')
                                    ];
                                      $this->set_notification($message);
                                      $logme = [
                                            'wallet_id' => $userWallet->wallet_id,
                                            'member_to' =>  $this->session->userdata('user_id'),
                                            'amount' =>  $data['amount'],
                                            'transection_id' => $save['pan_order_id'] ,
                                            'refrence' =>  "Pan_".Self::stan(),
                                            'service_id' => $this->data['serid'],
                                            'stock_type'=> $this->tnxType,
                                            'status' => 'success',
                                            'balance' =>  $userWallet->balance,
                                            'closebalance' => $updateBalance,
                                            'type' => 'debit',
                                            'mode' => 'Pancard',
                                            'bank' =>  'Pancard',
                                            'narration' => 'Pancard Charge',
                                            'date'=> date('Y-m-d'),
                                          ];
                      
                      
                                      $this->common_model->insert($logme, 'wallet_transaction');
                                  }
                              } 
                              
                              
                                self::commition_distribute($this->session->userdata('user_id'), $this->data['serid'],$amount ,$result->data->order_id);
                              
                                $this->session->set_flashdata(
                                  array(
                                    'status' => 1,
                                    'msg' => "Transaction Success"
                                  )
                                );
                                redirect('pancard/BuyCoupon', 'refresh');
                            
                            } else {
                              
                              $this->session->set_flashdata(
                                array(
                                  'status' => 1,
                                  'msg' => "Transaction Faild"
                                )
                              );
                            redirect('pancard/BuyCoupon', 'refresh');
                          } 
                          
                        } else {
                              
                              $this->session->set_flashdata(
                                array(
                                  'status' => 1,
                                  'msg' => "Server Not Reponse"
                                )
                              );
                            redirect('pancard/BuyCoupon', 'refresh');
                        }   
                }else{

                    $this->session->set_flashdata(
                      array(
                        'status' => 1,
                        'msg' => "Insufficient Balance"
                      )
                    );
                  redirect('pancard/BuyCoupon', 'refresh');

                } 

          }else{

              $this->session->set_flashdata(
                array(
                  'status' => 1,
                  'msg' => "Coupon Type Not Valid"
                )
              );
            redirect('pancard/BuyCoupon', 'refresh');

          }

      }else{

          $this->session->set_flashdata(
            array(
              'status' => 1,
              'msg' => "Something Went Wrong"
            )
          );
        redirect('pancard/BuyCoupon', 'refresh');

      }

    }

    private function coupon_serivice($data){

      $token = json_decode(self::token());
      $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://vitefintech.com/viteapi/api/pan/coupon',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('api_key' => $this->api_key,'agent_id' => $this->session->userdata('member_id'),'type' => $data['type'],'qty' => $data['qty'] ),
          CURLOPT_HTTPHEADER => array(
                'token:'.$token->token,
                'Secret-key:'.$this->Secret,
                'Client-Secret:'.$this->Secret
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

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

    private function commition_distribute($id, $service,$transection , $order_id) {
        
      $parentsList = self::checkparent($id);
      
      $i = 0;
      foreach ($parentsList as $key => $value) {

          if($key != 1){
              $commision = $this->commission_model->get_commision_by_role($value['role_id'], $service,$transection);

              if (!empty($commision)) {
                      $userWallet = $this->common_model->get_user_wallet_balance($value['user_id']);
                      
                if ($userWallet != 'none') {
                    
                  if($commision->c_flat == 0){
                      
                      $amountc = $commision->g_commission;
                      
                      $updateBalance = $userWallet->balance + $commision->g_commission;    // add commission
                      $updateWallet = [
                        'balance' => $updateBalance,
                      ];
                  }else{
                      
                      $amountc = $transection *  $commision->g_commission / 100;
                      
                       $updateBalance = $userWallet->balance + $amountc;    // add commission
                       $updateWallet = [
                        'balance' => $updateBalance,
                      ];
                      
                  }
                  if($this->common_model->update($updateWallet, 'member_id', $value['user_id'], 'wallet')) {
                    $message = [
                      'msg' => 'Your wallet balance credited ' . $amountc . ' available balance is ' . $updateBalance,
                      'user_id' => $value['user_id']
                    ];
                    $this->set_notification($message);
                       $logme = [
                                    'wallet_id' => $userWallet->wallet_id,
                                    'member_to' =>  $value['user_id'],
                                    'member_from' =>  $value['parent'],
                                    'amount' =>  $transection,
                                    'refrence' =>  "Pan_".Self::stan(),
                                    'transection_id' => $order_id ,
                                    'commission' =>  $amountc,
                                    'service_id' => $service,
                                    'stock_type'=> $this->tnxType,
                                    'status' => 'success',
                                    'balance' =>  $userWallet->balance,
                                    'closebalance' => $updateBalance,
                                   'type' => 'credit',
                                   'mode' => 'PanCard',
                                   'bank' =>  'PanCard',
                                   'narration' => 'PanCard Commision',
                                   'date'=> date('Y-m-d'),
                                  ];
                      
                      
                    $this->common_model->insert($logme, 'wallet_transaction');
                  }
      
                }else{
                  $message = [
                    'msg' => 'User Wallet not Found',
                    'user_id' => $value['user_id']
                  ];
                  $this->set_notification($message);
                }
              }else{
                $message = [
                  'msg' => 'Commission Not Found',
                  'user_id' => $value['user_id']
                ];
                $this->set_notification($message);
              }
          }else{
              
          }
        
      }
    }   
    
    private function checkparent($id, &$parents = array(), $level = 1) {
        $data = $this->users_model->get_parent_aeps($id);
 
            if (isset($data)) {
                $parents[$level]['user_id'] = $data->user_id;
                $parents[$level]['member_id'] = $data->member_id;
                $parents[$level]['parent'] = $data->parent;
                $parents[$level]['role_id'] = $data->role_id;
           
                $ak = self::checkparent($data->parent, $parents, $level+1);
           
            }
      return $parents;
      
    }
    
    // Commisssion Section 
    
    public function commission() {
        
        $this->data['param'] = $this->paremlink('/');
        $this->data['main_content'] = $this->load->view('commission/index', $this->data, true);
        $this->data['is_script'] = $this->load->view('commission/script', $this->data, true);
        $this->load->view('layout/index', $this->data);
    
    }
    
    public function addCommissionForm() {

        if ($_POST) {
    
            $data = $this->security->xss_clean($_POST);
    
          if (isset($data['CommissionForm'])) {
    
            $baseRole = $data['CommissionForm'];
    
            $this->data['role_id'] = $baseRole;               
    
    
            echo $this->load->view('commission/add', $this->data, true);
    
          } else {
            echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
          }
    
        }

    }  
    
    public function Cinsert(){
      
        if ($_POST) {
       
           $data = $this->security->xss_clean($_POST);
           $data1['start_range'] = $data['start'];
           $data1['end_range'] = $data['end'];
           $data1['role_id'] = $data['role_id'];
           $data1['g_commission'] = $data['commision'];
           $data1['service_id'] = $this->data['serid'];
           $data1['c_flat'] = isset($data['flat'])?1:0;
           $data1['created'] = date("Y-m-d h:i:sa");
           
            if ($this->common_model->insert($data1,'service_commission')) {
                $this->session->set_flashdata(
                  array(
                    'status' => 1,
                    'msg' => " Insert Successfully"
                  )
                );
                redirect('pancard/commission', 'refresh');
            }
        }else{
            
            $this->session->set_flashdata(
                  array(
                    'status' => 1,
                    'msg' => " Insert Successfully"
                  )
                );
            redirect('pancard/commission', 'refresh');
            
        }
        
    }
    
    public function delete($id){
        
        if ($this->db->where("service_commission_id", $id)->delete('service_commission')) {
            echo 1;
        }else{
            echo 0;
        }
    }
    
    public function edit($id){
        
        $menu= $this->common_model->select_option($id, 'service_commission_id', 'service_commission');
        echo json_encode($menu[0]);
      
    }
    
    public function addupdate() {

        if ($_POST) {
    
          $data = $this->security->xss_clean($_POST);
    
          if (isset($data['addupdate'])) {
    
            $baseRole = $data['addupdate'];
    
            $service = $this->data['serid'];
    
            $commissionList = $this->commission_model->get_list($service, $baseRole);
    
    
            echo $this->load->view('commission/edit', $this->data, true);
    
          }else{
            echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
          }
        }
    }
    
    public function update(){
        
        $data = $this->security->xss_clean($_POST);
        if($data){
            $logme['start_range'] = $data['start'];
            $logme['end_range'] = $data['end'];
            $logme['g_commission'] = $data['commision'];
            $field = $this->data['serid']; 
         
            $logme['c_flat'] = isset($data['flat'])?1:0;
            $logme['role_id'] = $data['role_id'];
            $logme['service_id'] = $this->data['serid'];
            
            if ($this->common_model->update($logme, "service_commission_id", $field , 'service_commission')) {
                $this->session->set_flashdata(
                  array(
                    'status' => 1,
                    'msg' => " Updated Successfully"
                  )
                );
                redirect('pancard/commission', 'refresh');
            }
        }else{
            
            $this->session->set_flashdata(
                  array(
                    'status' => 1,
                    'msg' => " Updated Successfully"
                  )
                );
                redirect('pancard/commission', 'refresh');
            
        }
    }
    
    public function get_list(){

        $uri = $this->security->xss_clean($_GET);
    
        $role_id = $uri['id'];
    
        if (!empty($uri)) {
          $query = '';
    
          $output = array();
          
          $data = array();
    
        if (isAdmin($this->session->userdata('user_roles'))) {
                
              $query .= "SELECT * from service_commission  where role_id = '$role_id'  AND service_id = {$this->data['serid']} ";
    
              $recordsFiltered = $this->users_model->row_count($query);
    
            }else{
                    
              $query .= "SELECT * from service_commission where role_id = '$role_id'  AND service_id = {$this->data['serid']} ";
    
              $recordsFiltered = $this->users_model->row_count($query);
    
            }
    
          if (!empty($_GET["search"]["value"])) {
            $query .= 'AND start_range LIKE "%' . $_GET["search"]["value"] . '%" ' ;
            // $query .= 'OR end_range "%' . $_GET["search"]["value"] . '%" ';
            
            
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
           
    
          $sub_array[] = '<button type="button" class="btn btn-sm btn-info"  data-placement="bottom" onclick="Edit(' . $row['service_commission_id'] . ')" title="Edit Commission Information"><i class="fa fa-pencil-alt"></i></button>
               <button type="button" class="btn btn-sm btn-primary"  data-placement="bottom" onclick="Delete(' . $row['service_commission_id'] . ')" title="Delete Commission Information"><i class="fa fa-trash-alt"></i></button>';
           
            $sub_array[] = $row['start_range'];
            $sub_array[] = $row['end_range'];
            $sub_array[] = $row['g_commission'];
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
        
    // End Commission Section
    
    public function AmountSet(){
        $save = array();
        if($data = $this->security->xss_clean($_POST)){ 
            
            if($data['type'] == "pcoupon"){
                
                $type = '1' ;
                
            }elseif($data['type'] == "ecoupon"){
                
                $type = '2' ;
                
            }else{
                $this->session->set_flashdata(
                  array(
                    'status' => 1,
                    'msg' => "Coupon Type Invalid"
                  )
                );
                redirect('pancard/AmountSet', 'refresh');
            }
            
            $save = [
                    
                    "pan_coupon_type" =>  $type,
                    "pan_coupon_amount" =>  $data['coupon_amount'],
                    "created"  =>  date('Y-m-d H:i:s') 
                
                ] ;
            $id = $this->common_model->pan_amount($save);
            
            if(isset($id['row']) && $id['row'] == 2){
                $this->session->set_flashdata(
                  array(
                    'status' => 1,
                    'msg' => "Maximum Limit Exits"
                  )
                );
                redirect('pancard/AmountSet', 'refresh');
            }else{
                $this->session->set_flashdata(
                  array(
                    'status' => 1,
                    'msg' => "Coupon Price Set"
                  )
                );
                redirect('pancard/AmountSet', 'refresh');
            }
           
        }else{
            $this->data['data'] = $this->common_model->pan_amount_get();
            $this->data['main_content'] = $this->load->view('amount', $this->data, true);
            $this->data['is_script'] = $this->load->view('script', $this->data, true);
            $this->load->view('layout/index', $this->data);
            
        }
        
    }
    
    public function deleteAmount($id){
        
        if ($this->db->where("id", $id)->delete('pan_coupon_amount')) {
            echo 1;
        }else{
            echo 0;
        }
    }
    
    
}
