<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use GuzzleHttp\Client;

class Authentication extends CI_Controller {

    public $client;
    public function __construct(){
        parent::__construct();
        $this->client = new Client();
        $this->load->model('common_model');
        $this->load->model('users_model');
        $this->load->library(['auth', 'session']);
        $this->load->library('form_validation');
    }

    public function index() {
     if (check())
      {   
          redirect(base_url('dashboard'), 'refresh');
      }
      else
      {
        $data['main_content'] = $this->load->view('login', '', true);
        $this->load->view('index',$data);
      }
    }
    
    public function login() {
      $data = $this->security->xss_clean($_POST);
      if($data) {


        $url = _SERVICE_API_.'login';
        #guzzle
        try {

          $response = $this->client->request('POST', $url, [
          'form_params' => [
              'member_id' => $data['member_id'],
              'latitude' => $data['latitude'],
              'longitude' => $data['longitude'],
              'password' => $data['password']
            ],
          ]
        );

          $user = $response->getBody()->getContents();

          if( $user ) {
             // pre($user);exit;
            $user = json_decode($user);
            $user_name = $this->users_model->get_name($user->user_id);
            $set_user = array(
              'expires_in' => $user->expires_in,
              'kyc_status' => $user->kyc_status,
              'user_id' => $user->user_id,
              
              'member_id' => $user->member_id,
              'user_name' => $user_name,
              'menu_permission' => $this->users_model->get_menu_config($user->role_id),
              'phone' => $user->phone,
              'user_roles' => $user->role_id,
              'latitude' => $user->latitude,
              'longitude' => $user->longitude,
              'token' => $user->token,
              'token_type' => $user->token_type,
              'user_id' => $user->user_id,
              'user_status' => $user->user_status,
              'user_type' => $user->user_type,
              'loginStatus' => true
            );
            $this->session->set_userdata($set_user);
            $result = [
              'uname' => $user_name,
              'status' => $response->getStatusCode(),
              'phrase' => $response->getReasonPhrase(),
              'loginStatus' => 1
            ];
            echo json_encode($result);
          }

        } catch (GuzzleHttp\Exception\BadResponseException $e) {
          #guzzle repose for future use
          $response = $e->getResponse();
          $responseBodyAsString = $response->getBody()->getContents();
          echo json_encode($responseBodyAsString);
        }
      } else {
    }
  }


  public function get_data()
  {
    $id=  $this->security->xss_clean($_POST['id']);
    $data=$this->common_model->user_check($id);
    $flag=0;
    if(!$data){
      echo 0;
    } else{
        
        	$to = $data->email;
    		$subject = "Your OTP ";
    		$message = random_int(100000, 999999);
    		$from  = "info@vitefintech.com";
    		$headers = "From : $from";
    		$send_mobile=self::send_otp_mobile($data->phone,$message);
    		if(mail($to , $subject , $message, $headers)){
    		  $flag=1;
    		} 
    		elseif($send_mobile=="true")
    		{
    		  $flag=1;  
    		}
    		if($flag==1)
    		{
    		    $user_id =  $this->common_model->find_member('user','member_id',$id);
    		    $data = [
    		        
    		        'user_id' => $user_id->user_id,
    		        'phone' => $data->phone,
    		        'email' => $data->email
    		       
    		        ];
    		    $otp = [
    		        
        		        'otp' => $message,
        		        'user_id' => $user_id->user_id,
    		        
    		            ];
    		    
    		    $this->common_model->insert($otp,'otp_details');  
    		}
    		
                echo json_encode($data);
                
    }
  }
  
   private function send_otp_mobile($mobile,$otp)
   {
      $text="Dear user OTP to reset your At Moon Pe password is ".$otp." Do not share your OTP with others.";
              $this->client = new Client();
              try {
                 $response = $this->client->request('GET',"http://sms.vitefintech.com/api/sendmsg.php?user=ATMoon&pass=At@moon123&sender=MOONPE&phone=".$mobile."&text=".$text."&priority=ndnd&stype=normal");

                  $result = $response->getBody()->getContents();
                  return "true";
              } catch (GuzzleHttp\Exception\BadResponseException $e) {
                  #guzzle repose for future use
                  $response = $e->getResponse();
                  $responseBodyAsString = $response->getBody()->getContents();
                  print_r($responseBodyAsString);
               }
      }
      
    public function resetView() {
     echo $this->load->view('forget_pass');
    }
    
   public function remove_otp($id)
   {
     $this->common_model->delete(['user_id'=>$id],'otp_details'); 
   }
   
    public function resend_otp()
    {
      $id=  $this->security->xss_clean($_POST['id']);
      $data=$this->common_model->select_option($id,'user_id','user');
       $flag=0;
    if(!$data){
      echo 0;
    } else{
        
        	$to = $data[0]['email'];
    		$subject = "Your OTP ";
    		$message = random_int(100000, 999999);
    		$from  = "info@vitefintech.com";
    		$headers = "From : $from";
    		$send_mobile=self::send_otp_mobile($data[0]['phone'],$message);
    		if(mail($to , $subject , $message, $headers)){
    		  $flag=1;
    		} 
    		elseif($send_mobile=="true")
    		{
    		  $flag=1;  
    		}
    		if($flag==1)
    		{
    		    
    		    $otp = [
    		        
        		        'otp' => $message,
        		        'user_id' => $id,
    		        
    		            ];
    		    
    		    $this->common_model->insert($otp,'otp_details');  
    		}
    		
                echo json_encode($data);
                
    }
      
  }
    
  public function verify_otp()
  {
    $id =  $this->security->xss_clean($_POST);
    if (!$this->common_model->check_otp($id)) {
      echo 0;
    } else {
        $data = [
            
            'user_id' => $id['user_id'],
            'value' => 1
            
            ];
            
      echo json_encode($data);
    
        
    }
  }
    public function resetPass() {
      $pass = self::randomPassword();
      $data =  array('email' => $_POST['email']);
      $data = self::send_email($data['email'],  $pass);

    }
    function logout(){
        $this->session->sess_destroy();
        redirect(base_url() . 'authentication', 'refresh');
    }
     public function send_email($email = '', $password = '') {
         $data=array();
        $data['email']=$email;

        $data['password']=$password;
        //  echo print_r($data['value']);
        //  echo print_r($email);exit;
        //   $email = $email;
           $subject = 'Password Reset!!';

          $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'info@rectorsol.com', // change it to yours
            'smtp_pass' => 'shash#13', // change it to yours
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE
            );
          $this->email->set_header('MIME-Version', '1.0; charset=utf-8');
          $this->load->library('email', $config);
          $this->email->set_newline("\r\n");
          $this->email->from('info@rectorsol.com'); // change it to yours
          $this->email->to($email);// change it to yours
          $this->email->subject($subject);
          $this->email->set_mailtype('html');
         // $msg=$this->load->view('join/email');
          $this->email->message($this->load->view('email',$data,TRUE));
          $this->email->send();
    }
    function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    
     function reset_pass(){
         
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
        
     //api sms
   public function sms($phone=0)
   {
        if($phone==0)
        {
          $phone1=$user_details=$this->common_model->select_option(1,'user_id','user')[0]['phone'];
        }
        else
        {
          $phone1=$phone;  
        }
        $otp=self::otp();
        $text="Dear user OTP to login to your At Moon Pe account is ".$otp." Do not share your OTP with others.";
        $this->client = new Client();
        try {
        $response = $this->client->request('GET', "http://sms.vitefintech.com/api/sendmsg.php?user=ATMoon&pass=At@moon123&sender=MOONPE&phone=".$phone1."&text=".$text."&priority=ndnd&stype=normal", [

      ]);

      $result = $response->getBody()->getContents();
      if($phone==0)
      {
        $this->common_model->update(['otp'=>$otp],'user_id',1,'user'); 
        echo "true";
      }
      else
      {
      return $otp;
      }
         } catch (GuzzleHttp\Exception\BadResponseException $e) {
        #guzzle repose for future use
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        print_r($responseBodyAsString);
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
        $user_details=$this->common_model->select_option(1,'user_id','user');
        if($user_details && !empty($uri['otp']))
        {
          if($user_details[0]['otp']==$uri['otp'])
          {
            $this->common_model->update(['otp'=>''],'user_id',1,'user');
            $this->session->set_userdata('loginStatus', true);
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
