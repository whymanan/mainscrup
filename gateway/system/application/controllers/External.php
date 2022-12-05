<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class External extends CI_Controller {

  	public function __construct()
  	{
  		parent::__construct();
      $this->load->model('common_model');
      $this->load->model('role_model');
  	}

    public function auto_cities() {

        $cities = array_flatten($this->common_model->get_cities());

        $phrase = "";

        if (isset($_GET['phrase'])) {
        	$phrase = $_GET['phrase'];
        }

        $dataType = "json";

        if (isset($_GET['dataType'])) {
        	$dataType = $_GET['dataType'];
        }

        $found_cities = array();

        foreach ($cities as $key => $city) {

        	if ($phrase == "" || stristr($city, $phrase) != false) {
        		array_push($found_cities, $city);
        	}
        }


        switch ($dataType) {

        	case "json":

        		$json = '[';

        		foreach ($found_cities as $key => $city) {
        			$json .= '{"name": "' . $city . '"}';

        			if ($city !== end($found_cities)) {
        				$json .= ',';
        			}
        		}

        		$json .= ']';


        		header('Content-Type: application/json');
        		echo $json;

        		break;

        	case "xml":
        		$xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        		$xml .= '<data>';

        		foreach ($found_cities as $key => $city) {
        			$xml .= '<country>' . $city . '</country>';
        		}

        		$xml .= '</data>';


        		header('Content-Type: text/xml');
        		echo $xml;
        		break;

        	default:
        		break;

        }
    }

    public function auto_states() {

        $states = array_flatten($this->common_model->get_states());

        $phrase = "";

        if (isset($_GET['phrase'])) {
        	$phrase = $_GET['phrase'];
        }

        $dataType = "json";

        if (isset($_GET['dataType'])) {
        	$dataType = $_GET['dataType'];
        }

        $found_states = array();

        foreach ($states as $key => $state) {

        	if ($phrase == "" || stristr($state, $phrase) != false) {
        		array_push($found_states, $state);
        	}
        }


        switch ($dataType) {

        	case "json":

        		$json = '[';

        		foreach ($found_states as $key => $state) {
        			$json .= '{"name": "' . $state . '"}';

        			if ($state !== end($found_states)) {
        				$json .= ',';
        			}
        		}

        		$json .= ']';


        		header('Content-Type: application/json');
        		echo $json;

        		break;

        	case "xml":
        		$xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        		$xml .= '<data>';

        		foreach ($found_states as $key => $state) {
        			$xml .= '<country>' . $state . '</country>';
        		}

        		$xml .= '</data>';


        		header('Content-Type: text/xml');
        		echo $xml;
        		break;

        	default:
        		break;

        }
    }


    public function datayuge() {
      if ($_GET) {
        $result = callAPI('GET', IFSC_API . $_GET['search'], false, IFSC_API_KEY);
        $response = json_decode($result, true);
        if (isset($response['errors'])) {
          // code...
          echo json_encode($response['errors']);
        }else{
          echo json_encode($response);
        }
      }
    }


  	public function get_cities(){
  		if ($_GET) {
  			$subject=$this->security->xss_clean($_GET);
  			 if(isset($subject['search'])){
          	$data = $this->common_model->get_cities_by_name($subject['search']);
        }else{
          	$data = $this->common_model->get_cities_by_name();
        }
  			echo json_encode($data);
  		}
  	}
  	public function get_states(){
  		if ($_GET) {
  			$subject=$this->security->xss_clean($_GET);
  					 if(isset($subject['search'])){
          	$data = $this->common_model->get_states_by_name($subject['search']);
        }else{
          	$data = $this->common_model->get_states_by_name();
        }
  			
  			echo json_encode($data);
  		}
  	}

    public function get_vendor() {
      if ($_GET) {
        $menu=$this->security->xss_clean($_GET);
        if(isset($menu['search']) && isset($menu['role'])){
            if($menu['type']=='add')
          $data = $this->common_model->get_vendor_by_name($menu['search'], $menu['role']);
          else
           $data = $this->common_model->get_vendor_by_name($menu['search'], 0);
        }else {
           if(isset($menu['search'])) 
          $data = $this->common_model->get_vendor_by_name($menu['search'],0);
          else
         $data = $this->common_model->get_vendor_by_name("",0);   
        
        }
        echo json_encode($data);
      }
    }
	public function get_service()
	{
		if ($_GET) {
			$subject = $this->security->xss_clean($_GET);
			if (isset($menu['search'])) {
				$data = $this->common_model->get_service($subject['search']);
			} else {
				$data = $this->common_model->get_service();
			}
			echo json_encode($data);
		}
	}



    public function get_menu() {
    if ($_GET) {
    $menu = $this->security->xss_clean($_GET);
    if(isset($menu['search'])){
    	$data = $this->common_model->get_parent_menu($menu['search']);
    }else{
    	$data = $this->common_model->get_parent_menu();
    }

    echo json_encode($data);
    }

    }

    public function get_vendor_exist() {
    if ($_GET) {
      $subject = $this->security->xss_clean($_GET);
      $data = $this->common_model->exists('logme', ['phone' => $subject['search']]);
      if ($data) {
        $responce = [
          'error' => 1,
          'msg' => 'phone number already exist'
        ];
      }else{
        $responce = [
          'error' => 0,
          'msg' => 'phone number not exist'
        ];
      }
      echo json_encode($responce);
    }
    }

    public function get_role() {
      if ($_GET) {
	  $menu = $this->security->xss_clean($_GET);
			$role = $this->role_model->get_role_priority($this->session->userdata('user_roles'));
			//pre($this->session->userdata('user_roles'));exit;
      if(isset($menu['search'])){

      	$data = $this->role_model->get_role($menu['search'], $role->priority);
      }else{
      	$data = $this->role_model->get_role("", $role->priority);
      }

      echo json_encode($data);
      }
    }
    public function get_role_exist() {
      if ($_GET) {
        $subject = $this->security->xss_clean($_GET);
        $data = $this->common_model->exists('roles', ['role' => $subject['search']]);
        if ($data) {
        	$responce = [
        		'error' => 1,
        		'msg' => 'Role already exist'
        	];
        } else {
        	$responce = [
        		'error' => 0,
        		'msg' => 'role not exist'
        	];
        }
        echo json_encode($responce);
      }
    }

    public function get_menu_exist() {
    if ($_GET) {
    $subject = $this->security->xss_clean($_GET);
    $data = $this->common_model->exists('menu_permission', ['menu_name' => $subject['search']]);
    if ($data) {
    	$responce = [
    		'error' => 1,
    		'msg' => 'Menu already exist'
    	];
    } else {
    	$responce = [
    		'error' => 0,
    		'msg' => 'Menu not exist'
    	];
    }
    echo json_encode($responce);
    }
    }

    public function get_submenu_exist() {
		if ($_POST) {
			$data1 = $this->security->xss_clean($_POST);
			$data = $this->common_model->submenu_exists($data1['menu'], $data1['parent'] );
			if ($data) {
				$responce = [
					'error' => 1,
					'msg' => 'Menu already exist'
				];
			} else {
				$responce = [
					'error' => 0,
					'msg' => 'Menu not exist'
				];
			}
			echo json_encode($responce);
		}
	}
	public function smssend()
	{
		// kvstore API url
		$url = 'https://www.vitefintech.com/public/api/textsms';
		// Collection object
		$data = [
			'phone' => 7668498112,
			'msgtext' => "hello"
		];
		// Initializes a new cURL session
		$curl = curl_init($url);
		// Set the CURLOPT_RETURNTRANSFER option to true
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		// Set the CURLOPT_POST option to true for POST request
		curl_setopt($curl, CURLOPT_POST, true);
		// Set the request data as JSON using json_encode function
		curl_setopt($curl, CURLOPT_POSTFIELDS,  json_encode($data));
		// Set custom headers for RapidAPI Auth and Content-Type header
		// curl_setopt($curl, CURLOPT_HTTPHEADER, [
		// 	'X-RapidAPI-Host: kvstore.p.rapidapi.com',
		// 	'X-RapidAPI-Key: 7xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
		// 	'Content-Type: application/json'
		// ]);
		// Execute cURL request with all previous settings
		$response = curl_exec($curl);
		// Close cURL session
		curl_close($curl);
		echo $response . PHP_EOL;
	}
}
