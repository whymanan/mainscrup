<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use GuzzleHttp\Client;
class Cronjob extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('users_model');
        $this->load->model('commission_model');
    }
     // dont touch this function without my permission 

  public function commition_distribute($id,$service, $transection,$operator,$transition_id)
  {
    $operator_id=array('Airtel'=>'11','BSNL'=>'13','JIO'=>'18','MTNL DELHI'=>'33','MTNL MUMBAI'=>'34','IDEA'=>'4','Vodafone'=>'22');
    $parentsList = self::checkparent($id);
    $i = 0;
    $j = 0;
    $allwallet = [];
    foreach ($parentsList as $key => $value) {
      $commision = $this->commission_model->get_commision_by_role_recharge($value['role_id'], $service, $transection, $operator_id[$operator]);
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
            // $this->set_notification($message);
            $logme = [
              'wallet_id' => $userWallet->wallet_id,
              'member_to' =>  $value['user_id'],
              'member_from' =>  $value['parent'],
              'amount' =>  $transection,
              //   'surcharge' => $data['surcharge'],
              'refrence' => $transition_id,
              'commission' =>  $amountc,
              'service_id' => $service,
              'stock_type' => 'Recharge',
              'status' => 'success',
              'balance' =>  $userWallet->balance,
              'closebalance' => $updateBalance,
              'type' => 'credit',
              'mode' => 'Mobile Recharge',
              'bank' => 'Mobile Recharge',
              'narration' => 'Mobile Recharge Commision',
              'date' => date('Y-m-d'),
            ];
            $allwallet[$j] = $this->common_model->insert($logme, 'wallet_transaction');
            $j++;
          }
        }
      } 
    }
    return $allwallet;
  }
  //bill comission
  public function commition_distribute_biil($id, $service, $transection, $operaor,$transection_id,$api_requist)
  {
    $this->db->select('operator_id')
                             ->from('billpayment')
                             ->where('operator_name',$operaor);
       $query = $this->db->get();
       $operator1=$query->result_array();
       $operaor=$operator1[0]['operator_id'];

    $parentsList = self::checkparent($id);
    $i = 0;
    foreach ($parentsList as $key => $value) {
      $commision = $this->commission_model->get_commision_by_role_bill($value['role_id'], $service, $transection, $operaor);
      if (!empty($commision)) {
        $userWallet = $this->common_model->get_user_wallet_balance($value['user_id']);

        if ($userWallet != 'none') {

          if ($commision[$i]['c_flat'] == 1) {

            $amountc = $commision[$i]['g_commission'];
     

            $updateBalance = $userWallet->balance + $amountc;    // add commission
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
              'refrence' =>  $transection_id,
              'commission' =>  $amountc,
              'service_id' => $service,
              'stock_type' =>$api_requist,
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

  //parent
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
    
    
    
    //for recharge
        public function status_checker()
      {
          foreach($this->common_model->select('status_api') as $value)
          {
            $wallet_status = self::transection_service_status($value['ref_id']);
            $user=$this->common_model->select_option($value['member_id'],'member_id','user');
            $wallet_status2=json_decode($wallet_status);
            if($wallet_status2->response_code==1 && $wallet_status2->status==1 && $wallet_status2->data->status==0 && $wallet_status2->data->refunded==1)
            {
            $userWallet = $this->common_model->wallet_balance($user[0]['user_id']);
            if($wallet_status2->data->canumber==$value['cnumber'] && $wallet_status2->data->amount==$value['amount'])
            {
             $net_wallet=$userWallet+$wallet_status2->data->amount;
             $this->common_model->update(array('balance'=>$net_wallet), 'member_id', $user[0]['user_id'], 'wallet');
             $transion = array('transection_msg' => 'failure','transection_response'=>$wallet_status);
             $this->common_model->update($transion,'primary_id ', $value['submit_trantision_id'], 'submit_transection');
             $this->db->where('id',$value['id'])->delete('status_api');
            }
            }
            elseif($wallet_status2->response_code==1 && $wallet_status2->status==1 && $wallet_status2->data->status==1)
            {
             if($wallet_status2->data->canumber==$value['cnumber'] && $wallet_status2->data->amount==$value['amount']){
             $trans_array=$this->common_model->select_option($value['submit_trantision_id'],'primary_id','submit_transection');
             $transion = array('transection_msg' =>trim($trans_array[0]['transection_msg'],'(Pendding)'),'transection_response'=>$wallet_status);
             self::commition_distribute_biil($user[0]['user_id'],13,$wallet_status2->data->amount,$wallet_status2->data->operatorname,$trans_array[0]['transection_id']);
             $this->common_model->update($transion,'primary_id ', $value['submit_trantision_id'], 'submit_transection');
             $this->db->where('id',$value['id'])->delete('status_api');
             }
            }
           }
      }
       public function transection_service_status($refid)
       {
          $this->client = new Client();
          #guzzle
          try {
            $response = $this->client->request('POST', "https://emopay.co.in/vite/home/status2/" .$refid, [

              // 'form_params' => $this->data['submitTransection'],
            ]);


            return $response->getBody()->getContents();
          } catch (GuzzleHttp\Exception\BadResponseException $e) {
            #guzzle repose for future use
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            print_r($responseBodyAsString);
          }
        }
        //for billpay
            public function statusbill_checker()
      {
          foreach($this->common_model->select('status_api') as $value)
          {
            $wallet_status = self::transection_servicebill_status($value['ref_id']);
            $user=$this->common_model->select_option($value['member_id'],'member_id','user');
            $wallet_status2=json_decode($wallet_status);
            if($wallet_status2->status==1 && $wallet_status2->data->status==0 && $wallet_status2->data->refunded==1)
            {
             $userWallet = $this->common_model->wallet_balance($user[0]['user_id']);
             if($wallet_status2->data->canumber==$value['cnumber'] && $wallet_status2->data->amount==$value['amount'])
              {
                $net_wallet=$userWallet+$wallet_status2->data->amount;
                $this->common_model->update(array('balance'=>$net_wallet), 'member_id', $user[0]['user_id'], 'wallet');
                $transion = array('transection_msg' => 'failure','transection_response'=>$wallet_status);
                $this->common_model->update($transion,'primary_id ', $value['submit_trantision_id'], 'submit_transection');
                $this->db->where('id',$value['id'])->delete('status_api');
              }
           }
            else if($wallet_status2->response_code==1 && $wallet_status2->status==1 && $wallet_status2->data->status==1 && $wallet_status2->data->refunded==0)
            {
                $this->common_model->update(array('transection_msg'=>$wallet_status2->data->operatorname.' Amount '.$wallet_status2->data->amount.' is successfull','transection_respcode'=>$wallet_status2->response_code,'transection_response'=>$wallet_status), 'primary_id',$value['submit_trantision_id'], 'submit_transection');
                $trans_array=$this->common_model->select_option($value['submit_trantision_id'],'primary_id','submit_transection');
                $transion = array('transection_msg' =>trim($trans_array[0]['transection_msg'],'(Pending)'),'transection_response'=>$wallet_status);
                self::commition_distribute_biil($user[0]['user_id'],$value['service_id'],$wallet_status2->data->amount,$wallet_status2->data->operatorname,$trans_array[0]['transection_id'],$trans_array[0]['api_requist']);
                $this->db->where('id',$value['id'])->delete('status_api');
            }
           }
      }
       public function transection_servicebill_status($refid)
       {
          $this->client = new Client();
          #guzzle
          try {
            $response = $this->client->request('POST', "https://emopay.co.in/vite/home/billstatus1/" .$refid, [

              // 'form_params' => $this->data['submitTransection'],
            ]);


            return $response->getBody()->getContents();
          } catch (GuzzleHttp\Exception\BadResponseException $e) {
            #guzzle repose for future use
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            print_r($responseBodyAsString);
          }
        }
}
