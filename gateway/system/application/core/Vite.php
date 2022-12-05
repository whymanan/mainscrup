<?php (defined('BASEPATH')) OR exit('No direct script access allowed');



class Vite extends CI_Controller {



  public function __construct() {
    parent::__construct();
    if (!check()) redirect(base_url('auth'), 'refresh');
    $this->load->model('setting_model');
    $this->load->model('common_model');
    $paremlink = (object)array('link', 'label');
    self::profile_image();
  }


  public function app_config() {
    return $this->setting_model->settings();
  }

   public function profile_image() {
    $profile=$this->setting_model->get_image($this->session->userdata('user_id'));
   //echo $profile;exit;
    if($profile){
      $this->data['profile']=base_url('uploads/photo/').$profile;
    }else{
      $this->data['profile']=base_url(ASSETS) ."/img/theme/avtar.png";
    }
  
  }
  

  public function paremlink($method) {
    $link = new v_method($method);
    $link->link = $link->get_link();
    $link->label = $link->get_label();
    return  $link;
  }
  
    public function set_notification($message) {
      if (isset($message['msg'])) {
        // Get IP address
        if( ($remote_addr = $_SERVER['REMOTE_ADDR']) == '') {
          $remote_addr = "REMOTE_ADDR_UNKNOWN";
        }else{
          $remote_addr = $_SERVER['REMOTE_ADDR'];
        }

        // Get requested script
        if( ($request_uri = $_SERVER['REQUEST_URI']) == '') {
          $request_uri = "REQUEST_URI_UNKNOWN";
        }else{
          $request_uri = $_SERVER['REQUEST_URI'];
        }

        if (isset($message['user_id'])) {
          $member_id = $message['user_id'];
        }else{
          $member_id = $this->session->userdata('user_id');
        }

        $data = [
          'mamber_id' => $member_id,
          'notification_title' => $message['msg'],
          'remote_addr' => $remote_addr,
          'request_uri' => $request_uri,
          'message' => json_encode($message)
        ];

        $result = $this->common_model->insert($data, 'notification_callback');

        if($result) {
          return TRUE;
        } else {
          return FALSE;
        }
      }

    }

}


/**
 * Paremlink class
 */
class v_method {

  public $link;
  public $label;

  public $parem = [
    'add' => 'Add',
    'edit' => 'Edit',
    'Delete' => 'Delete',
    'update' => 'Update',
    '/' => 'List',
    'view' => 'View',
  ];

  public function __construct($method) {
    if (array_key_exists($method, $this->parem)) {
      $this->link = $method;
      $this->label = $this->parem[$method];
    }else{
      log_message('error', "Method {$method} Not Exist.") or exit("Method {$method} Not Exist.");
    }
  }

  public function get_link() {
    return $this->link;
  }

  public function get_label() {
    return $this->label;
  }

}
