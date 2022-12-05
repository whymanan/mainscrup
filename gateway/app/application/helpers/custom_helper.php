<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	//-- check logged user
	if (!function_exists('check_login_user')) {
	    function check_login_user() {
	        $ci = get_instance();
	        if ($ci->session->userdata('is_login') != TRUE) {
	            $ci->session->sess_destroy();
	            redirect(base_url('auth'));
	        }
	    }
	}


	if (!function_exists('pre')) {
	    function pre($data) {
				if (is_array($data)) {
					return '<pre>'.$data.'</pre>';
				}else{
					echo "Variable is not array";
				}
	    }
	}
	if (!function_exists('json_output')) {
    	function json_output($statusHeader, $response) {
            $ci = &get_instance();
            $ci->output->set_content_type('application/json');
            $ci->output->set_status_header($statusHeader);
            $ci->output->set_output(json_encode($response));
        }
	}

	if (!function_exists('get_client_ip')) {
		function get_client_ip() {
				$ipaddress = '';
				if (getenv('HTTP_CLIENT_IP'))
						$ipaddress = getenv('HTTP_CLIENT_IP');
				else if(getenv('HTTP_X_FORWARDED_FOR'))
						$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
				else if(getenv('HTTP_X_FORWARDED'))
						$ipaddress = getenv('HTTP_X_FORWARDED');
				else if(getenv('HTTP_FORWARDED_FOR'))
						$ipaddress = getenv('HTTP_FORWARDED_FOR');
				else if(getenv('HTTP_FORWARDED'))
					 $ipaddress = getenv('HTTP_FORWARDED');
				else if(getenv('REMOTE_ADDR'))
						$ipaddress = getenv('REMOTE_ADDR');
				else
						$ipaddress = 'UNKNOWN';
				return $ipaddress;
			}
	}

	if (!function_exists('get_increment')) {
		    function get_increment(&$inc) {
					return $inc = $inc + 1 ;
		    }
		}


	if(!function_exists('check_power')){
	    function check_power($type){
	        $ci = get_instance();

	        $ci->load->model('common_model');
	        $option = $ci->common_model->check_power($type);

	        return $option;
	    }
    }
if(!function_exists('objectToArray')){
		function objectToArray($d) {
				if (is_object($d)) {
						// Gets the properties of the given object
						// with get_object_vars function
						$d = get_object_vars($d);
				}

				if (is_array($d)) {
						/*
						* Return array converted to object
						* Using __FUNCTION__ (Magic constant)
						* for recursive call
						*/
						return array_map(__FUNCTION__, $d);
				}
				else {
						// Return array
						return $d;
				}
		}
	}


if(!function_exists('randomPassword')){

	function randomPassword()
  {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
      $n = rand(0, $alphaLength);
      $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
  }
}

	//-- current date time function
	if(!function_exists('current_datetime')){
	    function current_datetime(){
	        $dt = new DateTime('now', new DateTimezone('Asia/Kolkata'));
	        $date_time = $dt->format('Y-m-d H:i:s');
	        return $date_time;
	    }
	}

	//-- show current date & time with custom format
	if(!function_exists('my_date_show_time')){
	    function my_date_show_time($date){
	        if($date != ''){
	            $date2 = date_create($date);
	            $date_new = date_format($date2,"d M Y h:i A");
	            return $date_new;
	        }else{
	            return '';
	        }
	    }
	}

	//-- show current date & time with custom format
	if(!function_exists('time_diff')){
	    function time_diff($date){
				$start_date = new DateTime($date);
				$since_start = $start_date->diff(new DateTime(current_datetime()));
				if ($since_start->y) {
					return $since_start->y. " Year";
				}elseif ($since_start->m) {
					return $since_start->m. " Month";
				}elseif ($since_start->d) {
					return $since_start->d. " Day";
				}elseif ($since_start->h) {
					return $since_start->h. " Hour";
				}elseif ($since_start->i) {
					return $since_start->i. " Minute";
				}else{
					return;
				}
	    }
	}



	if(!function_exists('my_date_show_time')){
	    function my_date_show($date){
	        if($date != ''){
	            $date2 = date_create($date);
	            $date_new = date_format($date2,"M Y ");
	            return $date_new;
	        }else{
	            return '';
	        }
	    }
	}

	//-- show current date with custom format
	if(!function_exists('my_date_show')){
	    function my_date_show($date){

	        if($date != ''){
	            $date2 = date_create($date);
	            $date_new = date_format($date2,"d M Y");
	            return $date_new;
	        }else{
	            return '';
	        }
	    }
	}

	//-- show current date with custom format
	if(!function_exists('my_month_show')){
	    function my_month_show(){
					$dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
				  $date_time = $dt->format('Y-m-d');
	        if($date_time != ''){
	            $date2 = date_create($date_time);
	            $date_new = date_format($date2,"M d");
	            return $date_new;
	        }else{
	            return '';
	        }
	    }
	}

	if(!function_exists('getUniqidId')) {
	    function getUniqidId($slug=""){
				return uniqid($slug);
	    }
	}

	if(!function_exists('getCustomId')) {
	    function getCustomId($max, $slug=""){
				$text = $slug . ($max + 1);
				return $text;
	    }
	}

	if(!function_exists('get_footer')) {
	    function get_footer($path = ''){
				$ci = get_instance();
				$var = $ci->load->view($path);
				return $var;
	    }
	}

	if(!function_exists('get_sidebar')) {
	    function get_sidebar($path = ''){
				$ci = get_instance();
				$var = $ci->load->view($path);
				return $var;
	    }
	}

	if(!function_exists('get_section')) {
		function get_section($path = ''){
			$ci = get_instance();
			$var = $ci->load->view($path);
			return $var;
		}
}

	if(!function_exists('get_related')) {
	    function get_related($type = '', $node = '', $path = '') {
				$ci = get_instance();
				$data['type'] = $type;
				$data['node'] = $node;
				$var = $ci->load->view($path, $data);
				return $var;
	    }
	}

	if(!function_exists('get_player')) {
	    function get_player($node) {
				$ci = get_instance();
				$data['node'] = $node;
				$var = $ci->load->view('web/layout/embedded-player', $data);
				return $var;
	    }
	}

	if(!function_exists('isJson')) {
		function isJson($string) {
			json_decode($string);
			return (json_last_error() == JSON_ERROR_NONE);
		}
	}

	if(!function_exists('get_web_page')) {
		function get_web_page( $url )
    {
        $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

        $options = array(

            CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
            CURLOPT_POST           =>false,        //set to GET
            CURLOPT_USERAGENT      => $user_agent, //set user agent
            CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $header;
    }
	}

	if(!function_exists('array_flatten')) {
 	 function array_flatten($array) {
 				if (!is_array($array)) {
 		    	return FALSE;
 			  }
 			  $result = array();
 			  foreach ($array as $value) {
 			    if (is_array($value)) {
 			      $result = array_merge($result, array_flatten($value));
 			    }
 			    else {
 			      $result[] = $value;
 			    }
 			  }
 			  return $result;
 		}
 }

 if(!function_exists('callAPI')) {

		 function callAPI($method, $url, $data, $headers = false){
		    $curl = curl_init();
		    switch ($method){
		       case "POST":
		          curl_setopt($curl, CURLOPT_POST, 1);
		          if ($data)
		             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		          break;
		       case "PUT":
		          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		          if ($data)
		             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		          break;
		       default:
		          if ($data)
		             $url = sprintf("%s?%s", $url, http_build_query($data));
		    }
		    // OPTIONS:
		    curl_setopt($curl, CURLOPT_URL, $url);
				if(!$headers){
	       curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	          'Content-Type: application/json',
	       ));
			   }else{
			       curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			          'Content-Type: application/json',
			          $headers
			       ));
			   }
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		    // EXECUTE:
		    $result = curl_exec($curl);
		    if(!$result){die("Connection Failure");}
		    curl_close($curl);
		    return $result;
		 }
	}
	
	
	
			if(!function_exists('validate_phone_number')) {


		function validate_phone_number($phone) {
			 // Allow +, - and . in phone number
			 $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
			 // Remove "-" from number
			 $phone_to_check = str_replace("-", "", $filtered_phone_number);

			 // Check the lenght of number
			 // This can be customized if you want phone number from a specific country
			 if (strlen($phone_to_check) < 10 || strlen($phone_to_check) > 10) {
					return false;
			 } else {
				 return true;
			 }
	 }
	}
