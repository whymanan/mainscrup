<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;
require APPPATH.'/libraries/REST_Controller.php';

class Rechargebillpayment extends REST_Controller {
  
  public $client;

    function __construct() {
        parent::__construct();
        $this->load->helper('api');
        $this->load->model('common_model');
        $this->load->model('users_model');
        $this->load->model('commission_model');
        $this->tnxType = 'Recharge';
        }
     //wallet balance
     public function wallet_post()
     {
         $data=$this->security->xss_clean($_POST);
         if(isset($data['member_id']))
         { 
            if($this->common_model->member_id($data['member_id'])==1)
            {
              $user=$this->common_model->select_option($data['member_id'],'member_id','user');
              echo Json_encode(['status'=>1,'response'=>1,'wallet_balance'=>$this->common_model->wallet_balance($user[0]['user_id'])]);
            }
            else
            {
                echo Json_encode(['status'=>0,'response'=>1,'massage'=>'Access denie']);
            }
         }
         else
         {
             echo Json_encode(['status'=>0,'response'=>2,'massage'=>'Please enter the memberId']);
         }
     }
      //leger
     public function leger_post()
     {
         $data=$this->security->xss_clean($_POST);
         if(isset($data['member_id']))
         {
            if($this->common_model->member_id($data['member_id'])==1)
            {
              $user=$this->common_model->select_option($data['member_id'],'member_id','user');
              echo Json_encode(['status'=>1,'response'=>1,'leger'=>$this->common_model->select_option($user[0]['user_id'],'member_to','wallet_transaction')]);
            }
            else
            {
                echo Json_encode(['status'=>0,'response'=>1,'massage'=>'Access denie']);
            }
         }
         else
         {
             echo Json_encode(['status'=>0,'response'=>2,'massage'=>'Please enter the memberId']);
         }
     }
     //all transition
     public function transition_post()
     {
         $data=$this->security->xss_clean($_POST);
         if(isset($data['member_id']) && isset($data['service_id']) && isset($data['api_requist']))
         {
            if($this->common_model->member_id($data['member_id'])==1)
            {
              if($data['member_id']=='POCA001')
              {
                  $query="SELECT * FROM submit_transection WHERE service_id = {$data['service_id']} and api_requist='{$data['api_requist']}' ORDER BY `submit_transection`.`primary_id` DESC";
              }
              else
              {
                $query="SELECT * FROM submit_transection WHERE service_id = {$data['service_id']} and api_requist='{$data['api_requist']}' AND member_id = '{$data['member_id']}' ORDER BY `submit_transection`.`primary_id` DESC";  
              }
              $sql = $this->db->query($query);
            //   print_r($this->db->last_query());
            //   exit();
              $transition = $sql->result_array();
              if($sql->num_rows()>0){
               echo Json_encode(['status'=>1,'response'=>1,'all_transition'=>$transition]);
              }
              else
              {
                 echo Json_encode(['status'=>0,'response'=>0,'all_transition'=>'not found']); 
              }
            }
            else
            {
                echo Json_encode(['status'=>0,'response'=>1,'massage'=>'Access denie']);
            }
         }
         else
         {
             echo Json_encode(['status'=>0,'response'=>2,'massage'=>'All field is Mandatory']);
         }
     }
     //recharge commisition distribute
     public function commition_distribute($id, $service, $transection, $operator)
     {

       if($operator==12 || $operator==14 || $operator==27 || $operator==8 || $operator==10)
       {
         $sting="DTH Recharge";
       }
       else
       {
         $sting="Mobile Recharge";
       }
      $parentsList = self::checkparent($id);
      $i = 0;
      $j = 0;
      $allwallet = [];
      foreach ($parentsList as $key => $value) {
               $commision = $this->commission_model->get_commision_by_role_recharge($value['role_id'], $service, $transection, $operator);
               if (!empty($commision)) {
                             $userWallet = $this->common_model->get_user_wallet_balance($value['user_id']);

                             if ($userWallet != 'none') {

                                    if ($commision[$i]['c_flat'] == 1) {

                                               $amountc = $commision[$i]['g_commission'];

                                               $updateBalance = $userWallet->balance + $commision[$i]['g_commission'];    // add commission
                                               $updateWallet = [
                                                                 'balance' => $updateBalance,
                                                               ];
                                             }else {

                                             $amountc = $transection *  $commision[$i]['g_commission'] / 100;

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
                                            // $this->set_notification($message);
                                            $logme = [
                                              'wallet_id' => $userWallet->wallet_id,
                                              'member_to' =>  $value['user_id'],
                                              'member_from' =>  $value['parent'],
                                              'amount' =>  $transection,
                                              //   'surcharge' => $data['surcharge'],
                                              'refrence' =>  'Recharge'.self::stan2(),
                                              'transection_id'=>$this->data['saveTransection']['transection_id'],
                                              'commission' =>  $amountc,
                                              'service_id' => $service,
                                              'stock_type' => $this->tnxType,
                                              'status' => 'success',
                                              'balance' =>  $userWallet->balance,
                                              'closebalance' => $updateBalance,
                                              'type' => 'credit',
                                              'mode' => $sting,
                                              'bank' => $sting,
                                              'narration' => $sting.' Commision',
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
                                    // $this->set_notification($message);
                                     }
                  } else {
                        $message = [
                          'msg' => 'Commission Not Found',
                          'user_id' => $value['user_id']
                        ];
                        // $this->set_notification($message);
                      }
    }
      return Json_encode($allwallet);
     }
    //  bill commisition distribute
     public function commition_distribute_biil($id, $service, $transection, $operaor)
     {
    
    $parentsList = self::checkparent($id);
    $i = 0;
    foreach ($parentsList as $key => $value) {
      $commision = $this->commission_model->get_commision_by_role_bill($value['role_id'], $service, $transection, $operaor);
     
      if (!empty($commision)) {
        $userWallet = $this->common_model->get_user_wallet_balance($value['user_id']);
        
        if ($userWallet != 'none') {

          if ($commision[$i]['c_flat'] == 1) {

            $amountc = $commision[$i]['g_commission'];

            $updateBalance = $userWallet->balance + $commision[$i]['g_commission'];    // add commission
            $updateWallet = [
              'balance' => $updateBalance,
            ];
          } else {

            $amountc = $transection *  $commision[$i]['g_commission'] / 100;

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
            
            $logme = [
              'wallet_id' => $userWallet->wallet_id,
              'member_to' =>  $value['user_id'],
              'member_from' =>  $value['parent'],
              'amount' =>  $transection,
              //   'surcharge' => $data['surcharge'],
              'refrence' =>  'Bill'.self::stan2(),
              'transection_id'=>$this->data['saveTransection']['transection_id'],
              'commission' =>  $amountc,
              'service_id' => $service,
              'stock_type' => $this->data['saveTransection']['api_requist'],
              'status' => 'success',
              'balance' =>  $userWallet->balance,
              'closebalance' => $updateBalance,
              'type' => 'credit',
              'mode' => 'Bill Pay',
              'bank' =>  'Bill Pay',
              'narration' => 'Bill Pay Commision',
              'date' => date('Y-m-d'),
            ];
           
             $this->common_model->insert($logme, 'wallet_transaction');
          }
        } else {
          $message = [
            'msg' => 'User Wallet not Found',
            'user_id' => $value['user_id']
          ];
         
        }
      } else {
        $message = [
          'msg' => 'Commission Not Found',
          'user_id' => $value['user_id']
        ];
       
      }
    }
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
                   self::checkparent($data->parent, $parents, $level + 1);
                  }
      return $parents;
     }
     //rechargepay
     public function recharge_post()
     {
         $data=$this->security->xss_clean($_POST);
         if(isset($data['member_id']) && isset($data['mobile']) && isset($data['latitude']) && isset($data['longitude']) && isset($data['amount']) && isset($data['operator']) && isset($data['type']))
         {
            if($this->common_model->member_id($data['member_id'])==1)
            {
              $location = $data['latitude'] . '|' . $data['longitude'];
              $user=$this->common_model->select_option($data['member_id'],'member_id','user');
              $data['bal']=$this->common_model->wallet_balance($user[0]['user_id']);
              $logelectric = $this->common_model->get_user_wallet_balance($user[0]['user_id']);
              if($data['bal'] > 0 && $data['bal'] > $data['amount'])
              {
                  $transection_id = self::utan($data['mobile']);
                  $this->data['submitTransection'] = [
                                                       'operator' => $data['operator'],
                                                       'canumber' => $data['mobile'],
                                                       'amount' => $data['amount'],
                                                       'referenceid' =>'Recharge'.self::stan2(),
                                                       'location' => $location,
                                                       'transection_id' => $transection_id
                                                     ];
                 $response = self::transection_service('POCA001',$data['member_id']);
                 $response1 = json_decode($response);
                 if(isset($response1->status) && isset($response1->response_code)){
                 if($response1->status == true && $response1->response_code == 1 || $response1->response_code == 0 || $response1->response_code == 2){//success
                      $this->data['saveTransection'] = [
                                                        'transection_id' => $transection_id,
                                                        'transection_type' => 'recharge',
                                                        'member_id' => $data['member_id'],
                                                        'transection_amount' =>  $data['amount'],
                                                        'service_id' => 13,
                                                        'transection_msg' => $response1->message,
                                                        'reference_number' => $response1->refid,
                                                        'transection_mobile' => $data['mobile'],
                                                        'api_requist' => $data['type'],
                                                        'location' => $location,
                                                        "transection_status" => $response1->status,
                                                        "transection_response" => $response,
                                                         'created'=> date("Y-m-d h:i:sa")
                                                       ];
                     $tran_id = $this->common_model->insert($this->data['saveTransection'], 'submit_transection');
                     $user=$this->common_model->select_option($data['member_id'],'member_id','user');
                     $userWallet = $this->common_model->wallet_balance($user[0]['user_id']);
                     if ($userWallet != 'none') {
                                                 $updateBalance = $userWallet - $data['amount'];    //Deduct balance
                                                 $updateWallet = [
                                                                  'balance' => $updateBalance,
                                                                 ];
                     if ($this->common_model->update($updateWallet, 'member_id', $user[0]['user_id'], 'wallet')) { //update deducted balance
                                                    $message = [
                                                                'msg' => 'Your wallet balance debited Rs. ' . $data['amount'] . ' available balance is ' . $updateBalance,
                                                                'user_id' => $data['member_id']
                                                               ];
                     }
                      $logme = [
                                        'wallet_id' => $logelectric->wallet_id,
                                        'member_to' =>  $user[0]['user_id'],
                                        'member_from' =>  1,
                                        'amount' =>  $data['amount'],
                                                                  //   'surcharge' => $data['surcharge'],
                                        'refrence' =>  $this->data['submitTransection']['referenceid'],
                                        'transection_id'=>$this->data['saveTransection']['transection_id'],
                                        // 'commission' =>  $amountc,
                                        'service_id' => 13,
                                        'stock_type' => $this->data['saveTransection']['api_requist'],
                                        'status' => 'success',
                                        'balance' =>  $userWallet,
                                        'closebalance' => $updateBalance,
                                        'type' => 'debit',
                                        'mode' => 'Recharge',
                                        'bank' =>  'Recharge',
                                        'narration' => 'Recharge',
                                        'date' => date("Y-m-d h:i:sa"),
                                    ];
                        $this->common_model->insert($logme, 'wallet_transaction');
                   }
                      $wallet_store=self::commition_distribute($user[0]['user_id'], 13, $data['amount'], $data['operator']); 
                      $status_pendding = array(
                                               'member_id' => $data['member_id'],
                                                'ref_id' => $this->data['submitTransection']['referenceid'],
                                                'service_id' => 13,
                                                'api_type' => 'mobile_recharge',
                                                'amount' => $data['amount'],
                                                'cnumber' => $data['mobile'],
                                                'wallet_transition_id'=>$wallet_store,
                                                'submit_trantision_id' => $tran_id
                                              );
                 }
                 elseif ($response1->status == 56 && $response1->response_code == 56) {
                  if($response1->data->status==2 && $response1->data->msg=='SUCCESS' && $response1->data->errorcode==200)//success
                  {
                    $this->data['saveTransection'] = [
                                                        'transection_id' => $transection_id,
                                                        'transection_type' => 'recharge',
                                                        'member_id' => $data['member_id'],
                                                        'transection_amount' =>  $data['amount'],
                                                        'service_id' => 13,
                                                        'transection_msg' => $response1->data->msg,
                                                        'reference_number' => $response1->data->agentid,
                                                        'transection_mobile' => $data['mobile'],
                                                        'api_requist' => $data['type'],
                                                        'location' => $location,
                                                        "transection_status" => $response1->data->status,
                                                        "transection_response" => $response,
                                                        'created'=> date("Y-m-d h:i:sa")
                                                       ];
                     $tran_id = $this->common_model->insert($this->data['saveTransection'], 'submit_transection');
                     $user=$this->common_model->select_option($data['member_id'],'member_id','user');
                     $userWallet = $this->common_model->wallet_balance($user[0]['user_id']);
                     if ($userWallet != 'none') {
                                                 $updateBalance = $userWallet - $data['amount'];    //Deduct balance
                                                 $updateWallet = [
                                                                  'balance' => $updateBalance,
                                                                 ];
                     if ($this->common_model->update($updateWallet, 'member_id', $user[0]['user_id'], 'wallet')) { //update deducted balance
                                                    $message = [
                                                                'msg' => 'Your wallet balance debited Rs. ' . $data['amount'] . ' available balance is ' . $updateBalance,
                                                                'user_id' => $data['member_id']
                                                               ];
                     }
                     $logme = [
                                        'wallet_id' => $logelectric->wallet_id,
                                        'member_to' =>  $user[0]['user_id'],
                                        'member_from' =>  1,
                                        'amount' =>  $data['amount'],
                                                                  //   'surcharge' => $data['surcharge'],
                                        'refrence' =>  $this->data['submitTransection']['referenceid'],
                                        'transection_id'=>$this->data['saveTransection']['transection_id'],
                                        // 'commission' =>  $amountc,
                                        'service_id' => 13,
                                        'stock_type' => $this->data['saveTransection']['api_requist'],
                                        'status' => 'success',
                                        'balance' =>  $userWallet,
                                        'closebalance' => $updateBalance,
                                        'type' => 'debit',
                                        'mode' => 'Recharge',
                                        'bank' =>  'Recharge',
                                        'narration' => 'Recharge',
                                        'date' => date("Y-m-d h:i:sa"),
                                    ];
                        $this->common_model->insert($logme, 'wallet_transaction');
                   }
                      $wallet_store=self::commition_distribute($user[0]['user_id'], 13, $data['amount'], $data['operator']); 
                    }
                  elseif($response1->data->status==1)//pending
                  {
                      $this->data['saveTransection'] = [
                                                       'transection_id' => $transection_id,
                                                        'transection_type' => 'recharge',
                                                        'member_id' => $data['member_id'],
                                                        'transection_amount' =>  $data['amount'],
                                                        'service_id' => 13,
                                                        'transection_msg' => $response1->data->msg,
                                                        'reference_number' => $response1->data->agentid,
                                                        'transection_mobile' => $data['mobile'],
                                                        'api_requist' => $data['type'],
                                                        'location' => $location,
                                                        "transection_status" => $response1->data->status,
                                                        "transection_response" => $response,
                                                         'created'=> date("Y-m-d h:i:sa")
                                                       ];
                      $tran_id = $this->common_model->insert($this->data['saveTransection'], 'submit_transection');
                      $user=$this->common_model->select_option($data['member_id'],'member_id','user');
                      $userWallet = $this->common_model->wallet_balance($user[0]['user_id']);
                      if ($userWallet != 'none') {
                                                 $updateBalance = $userWallet - $data['amount'];    //Deduct balance
                                                 $updateWallet = [
                                                                  'balance' => $updateBalance,
                                                                 ];
                      if ($this->common_model->update($updateWallet, 'member_id', $user[0]['user_id'], 'wallet')) { //update deducted balance
                                                    $message = [
                                                                'msg' => 'Your wallet balance debited Rs. ' . $data['amount'] . ' available balance is ' . $updateBalance,
                                                                'user_id' => $data['member_id']
                                                               ];
                     }
                     $logme = [
                                        'wallet_id' => $logelectric->wallet_id,
                                        'member_to' =>  $user[0]['user_id'],
                                        'member_from' =>  1,
                                        'amount' =>  $data['amount'],
                                                                  //   'surcharge' => $data['surcharge'],
                                        'refrence' =>  $this->data['submitTransection']['referenceid'],
                                        'transection_id'=>$this->data['saveTransection']['transection_id'],
                                        // 'commission' =>  $amountc,
                                        'service_id' => 13,
                                        'stock_type' => $this->data['saveTransection']['api_requist'],
                                        'status' => 'success',
                                        'balance' =>  $userWallet,
                                        'closebalance' => $updateBalance,
                                        'type' => 'debit',
                                        'mode' => 'Recharge',
                                        'bank' =>  'Recharge',
                                        'narration' => 'Recharge',
                                        'date' => date("Y-m-d h:i:sa"),
                                    ];
                        $this->common_model->insert($logme, 'wallet_transaction');
                   }
                      $wallet_store=self::commition_distribute($user[0]['user_id'], 13, $data['amount'], $data['operator']); 
                 }
                }
                 }
                 echo $response;
              }
              else
              {
                  echo Json_encode(['status'=>6,'response'=>6,'massage'=>'Insufficient Balance']);
              }
            }
            else
            {
                echo Json_encode(['status'=>4,'response'=>4,'massage'=>'Access denie']);
            }
         }
         else
         {
             echo Json_encode(['status'=>5,'response'=>5,'massage'=>'All field is Mandatory']);
         }
     }
     //recharge api hitter
     public function transection_service($amemberid, $rmemberid)
     {
      $this->client = new Client();
      #guzzle
      try {
        $response = $this->client->request('POST', "https://vitefintech.com/viteapi/home/dorecharge2/" . $amemberid . "/" . $rmemberid, [
  
          'form_params' => $this->data['submitTransection'],
        ]);
  
  
        return  $response->getBody()->getContents();
      } catch (GuzzleHttp\Exception\BadResponseException $e) {
      #guzzle repose for future use
      $response = $e->getResponse();
      $responseBodyAsString = $response->getBody()->getContents();
      print_r($responseBodyAsString);
    }
  }
     
      //dthpay
      //dthpay
     public function dth_post()
     {
         $data=$this->security->xss_clean($_POST);
         if(isset($data['member_id']) && isset($data['mobile']) && isset($data['latitude']) && isset($data['longitude']) && isset($data['amount']) && isset($data['operator']) && isset($data['type']))
         {
            if($this->common_model->member_id($data['member_id'])==1)
            {
              $location = $data['latitude'] . '|' . $data['longitude'];
              $user=$this->common_model->select_option($data['member_id'],'member_id','user');
              $data['bal']=$this->common_model->wallet_balance($user[0]['user_id']);
              $logelectric = $this->common_model->get_user_wallet_balance($user[0]['user_id']);
              if($data['bal'] > 0 && $data['bal'] > $data['amount'])
              {
                  $transection_id = self::utan($data['mobile']);
                  $this->data['submitTransection'] = [
                                                      'operator' => $data['operator'],
                                                      'canumber' => $data['mobile'],
                                                      'amount' => $data['amount'],
                                                      'referenceid' =>'DTH'.self::stan2(),
                                                      'location' => $location,
                                                      'transection_id' => $transection_id,
                                                      'api_key'=>'POCA001',
                                                      'submerchantid'=>$data['member_id'],
                                                      'latitude'=>$data['latitude'],
                                                      'longitude'=>$data['longitude'],
                                                      'type'=>$data['type']
                   ];
                 $response = self::transection_dth();
                 $response1 = json_decode($response);
                if(isset($response1->status) && isset($response1->response_code)){
                 if($response1->status == true && $response1->response_code == 1 || $response1->response_code == 0 || $response1->response_code == 2){//success
                      $data['saveTransection'] = [
                                                        'transection_id' => $transection_id,
                                                        'transection_type' => 'recharge',
                                                        'member_id' => $data['member_id'],
                                                        'transection_amount' =>  $data['amount'],
                                                        'service_id' => 9,
                                                        'transection_msg' => $response1->message,
                                                        'reference_number' => $response1->refid,
                                                        'transection_mobile' => $data['mobile'],
                                                        'api_requist' => $data['type'],
                                                        'location' => $location,
                                                        "transection_status" => $response1->status,
                                                        "transection_response" => $response,
                                                        'created'=> date("Y-m-d h:i:sa")
                                                       ];
                     $tran_id = $this->common_model->insert($data['saveTransection'], 'submit_transection');
                     $user=$this->common_model->select_option($data['member_id'],'member_id','user');
                     $userWallet = $this->common_model->wallet_balance($user[0]['user_id']);
                     if ($userWallet != 'none') {
                                                 $updateBalance = $userWallet - $data['amount'];    //Deduct balance
                                                 $updateWallet = [
                                                                  'balance' => $updateBalance,
                                                                 ];
                     if ($this->common_model->update($updateWallet, 'member_id', $user[0]['user_id'], 'wallet')) { //update deducted balance
                                                    $message = [
                                                                'msg' => 'Your wallet balance debited Rs. ' . $data['amount'] . ' available balance is ' . $updateBalance,
                                                                'user_id' => $data['member_id']
                                                               ];
                     }
                      $logme = [
                                        'wallet_id' => $logelectric->wallet_id,
                                        'member_to' =>  $user[0]['user_id'],
                                        'member_from' =>  1,
                                        'amount' =>  $data['amount'],
                                                                  //   'surcharge' => $data['surcharge'],
                                        'refrence' =>  $this->data['submitTransection']['referenceid'],
                                        'transection_id'=>$this->data['saveTransection']['transection_id'],
                                        // 'commission' =>  $amountc,
                                        'service_id' => 9,
                                        'stock_type' => $this->data['saveTransection']['api_requist'],
                                        'status' => 'success',
                                        'balance' =>  $userWallet,
                                        'closebalance' => $updateBalance,
                                        'type' => 'debit',
                                        'mode' => 'Recharge',
                                        'bank' =>  'Recharge',
                                        'narration' => 'Recharge',
                                        'date' => date("Y-m-d h:i:sa"),
                                    ];
                        $this->common_model->insert($logme, 'wallet_transaction');
                   }
                      $wallet_store=self::commition_distribute($user[0]['user_id'],9, $data['amount'], $data['operator']); 
                      $status_pendding = array(
                                               'member_id' => $data['member_id'],
                                                'ref_id' => $this->data['submitTransection']['referenceid'],
                                                'service_id' => 9,
                                                'api_type' => 'mobile_recharge',
                                                'amount' => $data['amount'],
                                                'cnumber' => $data['mobile'],
                                                'wallet_transition_id'=>$wallet_store,
                                                'submit_trantision_id' => $tran_id
                                              );
                 }
                 elseif ($response1->status == 56 && $response1->response_code == 56) {
                   if($response1->data->status==2 && $response1->data->msg=='SUCCESS' && $response1->data->errorcode==200)//success
                   {
                        $data['saveTransection'] = [
                                  'transection_id' => $transection_id,
                                   'transection_type' => 'recharge',
                                   'member_id' => $data['member_id'],
                                  'transection_amount' =>  $data['amount'],
                                  'service_id' => 9,
                                  'transection_msg' => $response1->data->msg,
                                  'reference_number' => $response1->data->agentid,
                                  'transection_mobile' => $data['mobile'],
                                  'api_requist' => $data['type'],
                                  'location' => $location,
                                  "transection_status" => $response1->data->status,
                                  "transection_response" => $response,
                                  'created'=> date("Y-m-d h:i:sa")
                               ];
                    $tran_id = $this->common_model->insert($data['saveTransection'], 'submit_transection');
                    $user=$this->common_model->select_option($data['member_id'],'member_id','user');
                    $userWallet = $this->common_model->wallet_balance($user[0]['user_id']);
                    if ($userWallet != 'none') {
                                                 $updateBalance = $userWallet - $data['amount'];    //Deduct balance
                                                 $updateWallet = [
                                                                  'balance' => $updateBalance,
                                                                 ];
                     if ($this->common_model->update($updateWallet, 'member_id', $user[0]['user_id'], 'wallet')) { //update deducted balance
                                                    $message = [
                                                                'msg' => 'Your wallet balance debited Rs. ' . $data['amount'] . ' available balance is ' . $updateBalance,
                                                                'user_id' => $data['member_id']
                                                               ];
                     }
                      $logme = [
                                        'wallet_id' => $logelectric->wallet_id,
                                        'member_to' =>  $user[0]['user_id'],
                                        'member_from' =>  1,
                                        'amount' =>  $data['amount'],
                                                                  //   'surcharge' => $data['surcharge'],
                                        'refrence' =>  $this->data['submitTransection']['referenceid'],
                                        'transection_id'=>$this->data['saveTransection']['transection_id'],
                                        // 'commission' =>  $amountc,
                                        'service_id' => 9,
                                        'stock_type' => $this->data['saveTransection']['api_requist'],
                                        'status' => 'success',
                                        'balance' =>  $userWallet,
                                        'closebalance' => $updateBalance,
                                        'type' => 'debit',
                                        'mode' => 'Recharge',
                                        'bank' =>  'Recharge',
                                        'narration' => 'Recharge',
                                        'date' => date("Y-m-d h:i:sa"),
                                    ];
                        $this->common_model->insert($logme, 'wallet_transaction');
                   }
                    $wallet_store=self::commition_distribute($user[0]['user_id'],9, $data['amount'], $data['operator']);
                   }
                   elseif($response1->data->status==1)//pending
                   {
                     $data['saveTransection'] = [
                                'transection_id' => $transection_id,
                                'transection_type' => 'recharge',
                                'member_id' => $data['member_id'],
                                'transection_amount' =>  $data['amount'],
                                 'service_id' => 9,
                              'transection_msg' => $response1->data->msg,
                                'reference_number' => $response1->data->agentid,
                                'transection_mobile' => $data['mobile'],
                                'api_requist' => $data['type'],
                                'location' => $location,
                                "transection_status" => $response1->data->status,
                                "transection_response" => $response,
                                'created'=> date("Y-m-d h:i:sa"),
                         ];
                     $tran_id = $this->common_model->insert($this->data['saveTransection'],'submit_transection');
                     if ($userWallet != 'none') {
                                                 $updateBalance = $userWallet - $data['amount'];    //Deduct balance
                                                 $updateWallet = [
                                                                  'balance' => $updateBalance,
                                                                 ];
                     if ($this->common_model->update($updateWallet, 'member_id', $user[0]['user_id'], 'wallet')) { //update deducted balance
                                                    $message = [
                                                                'msg' => 'Your wallet balance debited Rs. ' . $data['amount'] . ' available balance is ' . $updateBalance,
                                                                'user_id' => $data['member_id']
                                                               ];
                     }
                      $logme = [
                                        'wallet_id' => $logelectric->wallet_id,
                                        'member_to' =>  $user[0]['user_id'],
                                        'member_from' =>  1,
                                        'amount' =>  $data['amount'],
                                                                  //   'surcharge' => $data['surcharge'],
                                        'refrence' =>  $this->data['submitTransection']['referenceid'],
                                        'transection_id'=>$this->data['saveTransection']['transection_id'],
                                        // 'commission' =>  $amountc,
                                        'service_id' => 9,
                                        'stock_type' => $this->data['saveTransection']['api_requist'],
                                        'status' => 'success',
                                        'balance' =>  $userWallet,
                                        'closebalance' => $updateBalance,
                                        'type' => 'debit',
                                        'mode' => 'Recharge',
                                        'bank' =>  'Recharge',
                                        'narration' => 'Recharge',
                                        'date' => date("Y-m-d h:i:sa"),
                                    ];
                        $this->common_model->insert($logme, 'wallet_transaction');
                   }
                    $wallet_store=self::commition_distribute($user[0]['user_id'],9, $data['amount'], $data['operator']);
                     $status_pendding = array(
                             'member_id' => $data['member_id'],
                             'ref_id' => $this->data['submitTransection']['referenceid'],
                             'service_id' => 9,
                             'api_type' => 'mobile_recharge(M)',
                             'amount' => $data['amount'],
                             'cnumber' => $data['mobile'],
                             'wallet_transition_id'=>$wallet_store,
                             'submit_trantision_id' => $tran_id
                            );
                  $this->common_model->insert($status_pendding, 'status_api');
                    }
                 }             
                 echo $response;
              }
              }
              else
              {
                  echo Json_encode(['status'=>6,'response'=>6,'massage'=>'Insufficient Balance']);
              }
            }
            else
            {
                echo Json_encode(['status'=>4,'response'=>4,'massage'=>'Access denie']);
            }
         }
         else
         {
             echo Json_encode(['status'=>5,'response'=>5,'massage'=>'All field is Mandatory']);
         }
     }
      //dthpay api hitting
     public function transection_dth()
     {
       $this->client = new Client();
       #guzzle
        try {
           $response = $this->client->request('POST', "https://vitefintech.com/viteapi/home/dthpay/", [
           'form_params' => $this->data['submitTransection'],
           ]);
       return  $response->getBody()->getContents();
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
         $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
      }
     }
     
     //billpay
     public function bill_post()
     {
         $data=$this->security->xss_clean($_POST);
         if(isset($data['member_id']) && isset($data['account']) && isset($data['latitude']) && isset($data['longitude']) && isset($data['amount']) && isset($data['operator']) && isset($data['type']) && isset($data['duedate']) && isset($data['username']) && isset($data['service']))
         {
            if($this->common_model->member_id($data['member_id'])==1)
            {
              $location = $data['latitude'] . '|' . $data['longitude'];
              $user=$this->common_model->select_option($data['member_id'],'member_id','user');
              $data['bal']=$this->common_model->wallet_balance($user[0]['user_id']);
              $logelectric = $this->common_model->get_user_wallet_balance($user[0]['user_id']);
              if($data['bal'] > 0 && $data['bal'] > $data['amount'])
              {
                  $transection_id = self::utan($data['account']);
                  $this->data['submitTransection'] = [
                                                      'operator' => $data['operator'],
                                                      'canumber' => $data['account'],
                                                      'amount' => $data['amount'],
                                                      'referenceid' =>'Bill_'.self::stan2(),
                                                      'location' => $location,
                                                      'transection_id' => $transection_id,
                                                      'api_key'=>'POCA001',
                                                      'submerchantid'=>$data['member_id'],
                                                      'latitude'=>$data['latitude'],
                                                      'longitude'=>$data['longitude'],
                                                      'type'=>$data['type'],
                                                      'billdate' => date("d/M/Y"),
                                                      'duedate' => $data['duedate'],
                                                      'name' => $data['username'],
                                                     ];
                  
                   $this->data['saveTransection'] = [
                                                      'transection_id' => $transection_id,
                                                      'transection_type' => 'bill_payment',
                                                      'member_id' => $data['member_id'],
                                                      'transection_amount' => $data['amount'],
                                                      'service_id' => $data['service'],
                                                      'reference_number' => $this->data['submitTransection']['referenceid'],
                                                      'transection_mobile' => $data['account'],
                                                      'api_requist' => $data['type'],
                                                      'location' => $location,
                                                     ];
                $transition_id=$this->common_model->insert($this->data['saveTransection'], 'submit_transection');
                $response =self::transection_service2();
                if (isJson($response)) {
                      $response1 = json_decode($response);
                      if(isset($response1->status) && isset($response1->response_code)){
                        $action = [
                                       'transection_status' =>  $response1->status,
                                       'transection_msg' => $response1->message,
                                       'transection_respcode' => $response1->response_code,
                                       'transection_response' => $response,
                                  ];
                        $this->common_model->update($action, 'primary_id', $transition_id, 'submit_transection');
                        if($response1->status==true && $response1->response_code==1){//success      
                              $user=$this->common_model->select_option($data['member_id'],'member_id','user');
                              $userWallet = $this->common_model->wallet_balance($user[0]['user_id']);
                              if ($userWallet != 'none') {
                                                 $updateBalance = $userWallet - $data['amount'];    //Deduct balance
                                                 $updateWallet = [
                                                                  'balance' => $updateBalance,
                                                                 ];
                                    if ($this->common_model->update($updateWallet, 'member_id', $user[0]['user_id'], 'wallet')) { //update deducted balance
                                                    $message = [
                                                                'msg' => 'Your wallet balance debited Rs. ' . $data['amount'] . ' available balance is ' . $updateBalance,
                                                                'user_id' => $data['member_id']
                                                               ];
                                        }
                                    
                              }
                              $logme = [
                                        'wallet_id' => $logelectric->wallet_id,
                                        'member_to' =>  $user[0]['user_id'],
                                        'member_from' =>  1,
                                        'amount' =>  $data['amount'],
                                                                  //   'surcharge' => $data['surcharge'],
                                        'refrence' =>  $this->data['submitTransection']['referenceid'],
                                        'transection_id'=>$this->data['saveTransection']['transection_id'],
                                        // 'commission' =>  $amountc,
                                        'service_id' => $data['service'],
                                        'stock_type' => $this->data['saveTransection']['api_requist'],
                                        'status' => 'success',
                                        'balance' =>  $userWallet,
                                        'closebalance' => $updateBalance,
                                        'type' => 'debit',
                                        'mode' => 'Bill Pay',
                                        'bank' =>  'Bill Pay',
                                        'narration' => 'Bill',
                                        'date' => date("Y-m-d h:i:sa"),
                                    ];
                                $this->common_model->insert($logme, 'wallet_transaction');
                              $wallet_sid = self::commition_distribute_biil($user[0]['user_id'], $data['service'], $data['amount'],$data['operator']); 
                        }
                        elseif($response1->status==true && $response1->response_code==0){//pending        
                              $user=$this->common_model->select_option($data['member_id'],'member_id','user');
                              $userWallet = $this->common_model->wallet_balance($user[0]['user_id']);
                              if ($userWallet != 'none') {
                                                $updateBalance = $userWallet - $data['amount'];    //Deduct balance
                                                $updateWallet = [
                                                                 'balance' => $updateBalance,
                                                                ];
                                      if ($this->common_model->update($updateWallet, 'member_id', $user[0]['user_id'], 'wallet')) { //update deducted balance
                                                   $message = [
                                                               'msg' => 'Your wallet balance debited Rs. ' . $data['amount'] . ' available balance is ' . $updateBalance,
                                                               'user_id' => $data['member_id']
                                                              ];
                                       }
                                       $logme = [
                                        'wallet_id' => $logelectric->wallet_id,
                                        'member_to' =>  $user[0]['user_id'],
                                        'member_from' =>  1,
                                        'amount' =>  $data['amount'],
                                                                  //   'surcharge' => $data['surcharge'],
                                        'refrence' =>  $this->data['submitTransection']['referenceid'],
                                        'transection_id'=>$this->data['saveTransection']['transection_id'],
                                        // 'commission' =>  $amountc,
                                        'service_id' => $data['service'],
                                        'stock_type' => $this->data['saveTransection']['api_requist'],
                                        'status' => 'success',
                                        'balance' =>  $userWallet,
                                        'closebalance' => $updateBalance,
                                        'type' => 'debit',
                                        'mode' => 'Bill Pay',
                                        'bank' =>  'Bill Pay',
                                        'narration' => 'Bill',
                                        'date' => date("Y-m-d h:i:sa"),
                                    ];
                                   $this->common_model->insert($logme, 'wallet_transaction');
                              }
                              $status_pendding = array(
                              
                                                   'member_id' => $user[0]['user_id'],
                                                   'ref_id' => $this->data['submitTransection']['referenceid'],
                                                   'service_id' => $data['service'],
                                                   'api_type' => $data['type'],
                                                   'amount' => $data['amount'],
                                                   'cnumber' => $data['account'],
                                                   'submit_trantision_id' =>$transition_id
                              
                                                  );
                                                  
                              $this->common_model->insert($status_pendding, 'status_api');
                        }
                      }
                 echo $response;
                }
              }
              else
              {
                  echo Json_encode(['status'=>6,'response'=>6,'massage'=>'Insufficient Balance']);
              }
            }
            else
            {
                echo Json_encode(['status'=>4,'response'=>4,'massage'=>'Access denie']);
            }
         }
         else
         {
             echo Json_encode(['status'=>5,'response'=>5,'massage'=>'All field is Mandatory']);
         }
     }

     //billpay hitting api
     public function transection_service2()
     {
    $this->client = new Client();

    //   print_r($this->data['submitTransection']);
    //   exit();
    #guzzle
    try {
      $response = $this->client->request('POST', "https://vitefintech.com/viteapi/home/billpay/",[

        'decode_content' => false,
        'form_params' => $this->data['submitTransection'],
      ]);
      return $response->getBody()->getContents();
    } catch (GuzzleHttp\Exception\BadResponseException $e) {
      #guzzle repose for future use
      $response = $e->getResponse();
      $responseBodyAsString = $response->getBody()->getContents();
      print_r($responseBodyAsString);
    }
  }

     public function utan($node)
     {
      return $node . '00' . round(microtime(true));
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
    }
     
}