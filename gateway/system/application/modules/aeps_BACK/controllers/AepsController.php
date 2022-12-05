<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AepsController extends Vite {


  public $data = array();

  public $client;


  public function __construct() {
      parent::__construct();
      $this->data['active'] = 'AEPS';
      $this->data['breadcrumbs'] = [array('url' => base_url('dashboard'), 'name' => 'Dashboard')];
  }

  public function index() {

    $this->data['param'] = $this->paremlink('add');
    $this->data['main_content'] = $this->load->view('index', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);

  }

  public function add() {
    $this->data['param'] = $this->paremlink('/');
    $this->data['main_content'] = $this->load->view('add', $this->data, true);
    $this->data['is_script'] = $this->load->view('script', $this->data, true);
    $this->load->view('layout/index', $this->data);
  }



  public function aepsTransectionForm() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);

      if (isset($data['bankCode'])) {

        $this->data['bankCode'] = $data['bankCode'];

        echo $this->load->view('add', $this->data, true);

      }else{
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }

  public function submitTransection() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);

      if (isset($data['transectionCode'])) {

        $this->data['transectionCode'] = $data['transectionCode'];

        echo $this->load->view('transection-summary', $this->data, true);

      }else{
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }

  public function aepsBiometricForm() {

    if ($_POST) {

      $data = $this->security->xss_clean($_POST);

      if (isset($data['bioMetric'])) {

        $this->data['bioMetric'] = $data['bioMetric'];

        $this->data['devices'] = []; // here fatch bioMetric devices list from database

        echo $this->load->view('add-biometric', $this->data, true);

      }else{
        echo json_encode(['error' => 1, 'msg' => 'requeste not allowed']);
      }

    }

  }


}
