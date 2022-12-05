<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 
 */
    if ( ! function_exists('remove_unknown_fields')){
        function remove_unknown_fields($row_data, $expected_fields) {
            $new_data = array();
            foreach ($row_data as $field_name => $field_value) {
                if($field_value !='' && in_array($field_name, array_values($expected_fields))){
                    $new_data[$field_name] = $field_value;
                }
        }
        return $new_data; 
    }
    
    function json_output($statusHeader, $response) {
        $ci = &get_instance();
        $ci->output->set_content_type('application/json');
        $ci->output->set_status_header($statusHeader);
        $ci->output->set_output(json_encode($response));
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
	
	if(!function_exists('current_datetime')){
	    function current_datetime(){
	        $dt = new DateTime('now', new DateTimezone('Asia/Kolkata'));
	        $date_time = $dt->format('Y-m-d H:i:s');
	        return $date_time;
	    }
	}
	
		if(!function_exists('isJson')) {
		function isJson($string) {
			json_decode($string);
			return (json_last_error() == JSON_ERROR_NONE);
		}
	}
	
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
	 
	function user_ckeck($api_key) {
			 
			$ci =& get_instance();
            $ci->load->database();
                 
            $query = $ci->db->get_where('user',array('member_id'=>$api_key));

            if($query->num_rows() == 1){
                $result = $query->row();
                $result = [
                       "Status" => 200 ,
                       "Msg" => "Access Grant"
                       ];
                return json_encode($result);
            }else{
                
                $result = [
                       "Status" => 403 ,
                       "Msg" => "Forbidden! Contact To ViteFintech Pvt Ltd. ",
                       
                       ];
                  
                  echo json_encode($result);exit;
            }
			 
	}
	
	if (!function_exists('find_Secret')) {
	    
	    function find_Secret($partnerId , $Secret_key) {
	        
			$ci =& get_instance();
            $ci->load->database();
            
            $ci->db->from('key_details');
            $ci->db->where('member_id' , $partnerId);
            $query = $ci->db->get(); 
            if($query->num_rows()){
                
                $result = $query->row();
                if($result->secret_id == $Secret_key ){
                    
                    return $query->row();
                    
                }else{
                    
                    $result = [
                    
                       "Status" => false ,
                       "statusCode" => 180,
                       "Msg" => "Ivalid deatils",
                         
                       ];
                  
                    echo json_encode($result,http_response_code(403));exit;
                    
                }
                
                
            }else{
                
                $result = [
                    
                       "Status" => false ,
                       "statusCode" => 170,
                       "Msg" => "Ivalid deatils",
                         
                       ];
                  
                    echo json_encode($result,http_response_code(403));exit;
                
            }
	    }
	}

}
