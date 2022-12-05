<?php
class Common_model extends CI_Model {
public function __construct()
        {
                $this->load->database();
        }
    //-- insert function
	public function insert($data,$table){
        $this->db->insert($table,$data);
        return $this->db->insert_id();
    }
     // analysis
    public function earning_by_date($member_id,$date){
        $query = $this->db->where(['transection_status'=> 1,'member_id'=> $member_id,'service_id'=>1])
                             ->or_where('service_id',2)
                            ->like('created',$date)
                            ->get('submit_transection');
        return $query;
    }
    
    public function gst_price($service='')
  {
    $this->db->select('price');
    $this->db->from('gst_price');
    $this->db->where('service_id',$service);
    $this->db->order_by('id','DESC');
    $query = $this->db->get();
    $query = $query->result_array();
    return $query;
  }

  public function select_gst_data($gst_id)
  {
    $this->db->select('*');
    $this->db->from('gst_registration');
    $this->db->where_in('gst_id',$gst_id);
    $this->db->order_by('gst_id', 'DESC');
    $query = $this->db->get();
    //pre( $this->db->last_query());exit;
    $query = $query->result_array();
    return $query;
  }
  public function select_gstr_data($gst_id)
  {
    $this->db->select('*');
    $this->db->from('gst_return');
    $this->db->where_in('id',$gst_id);
    $this->db->order_by('id', 'DESC');
    $query = $this->db->get();
    //pre( $this->db->last_query());exit;
    $query = $query->result_array();
    return $query;
  }
  
    public function priority($roleid)
    {
        $this->db->select();
        $this->db->from('roles');
        $this->db->where('roles_id',$roleid);
        $query = $this->db->get();
        $query = $query->result_array();
        return $query;
    }
    
     function select_limit_value2($table, $limit){
        $this->db->select();
        $this->db->from($table);
        $this->db->order_by('id','DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        $query = $query->result_array();
        return $query;
    }
    
     function create_transaction($data) {
      $this->db->insert_batch('wallet_transaction', $data);
  }
  
    function get_user_wallet_balance($id){
        $this->db->select('balance,wallet_id');
        $this->db->from('wallet');
        $this->db->where('member_id',$id);
        $query = $this->db->get();
        return $query->row();
      
    }
    
     function bank_get($id){
        $this->db->select('');
        $this->db->from('user_bank_details');
        $this->db->where('fk_user_id',$id);
        $query = $this->db->get();
        return $query->row();
      
    }
  
  function find_member($table,$where,$id){
        $this->db->select();
        $this->db->from($table);
        $this->db->where($where,$id);
        $this->db->limit(1);
            $query = $this->db->get();
            if ($query->num_rows() == 1) {
              return $query->row();
            } else {
              return false;
            }
    }
    
    
    function photo($table , $id){
        $this->db->select();
        $this->db->from($table);
        $this->db->where('root',$id);
        $query = $this->db->get();
        return $query->result_array();
    }    

function check_wallet($id){
        $this->db->select();
        $this->db->from('wallet ');
        $this->db->where('member_id',$id);
        $query = $this->db->get();
       // pre( $this->db->last_query());exit;
        if ($query->num_rows() >= 1) {
             return 1;
           } else {
             return 0;
           }
    }
    
 function get_commision_by_role($role,$service){
        $this->db->select();
        $this->db->from('service_commission ');
        $this->db->where('service_id',$service);
         $this->db->where('role_id',$role);
        $query = $this->db->get();
        //pre( $this->db->last_query());exit;
        return $query->result_array();
    }
    
function get_user_wallet($id){
        $this->db->select('balance');
        $this->db->from('wallet');
        $this->db->where('member_id',$id);
        $query = $this->db->get();
        pre( $this->db->last_query());exit;
        if ($query->num_rows() >= 1) {
             return $query->row('balance');
           } else {
             return 0;
           }
    }
function get_admin_wallet(){
        $this->db->select('admin');
        $this->db->from('awt');
        
        $query = $this->db->get();
       // pre( $this->db->last_query());exit;
        if ($query->num_rows() >= 1) {
             return $query->row('admin');
           } else {
             return 0;
           }
    }
    public function Login_check($data){
        $condition = "email =" . "'" . $data['email'] . "' AND " . "password =" . "'" . $data['password'] . "'AND role='".$data['role']."'" ;
            $this->db->select('*');
            $this->db->from('user');
            $this->db->where($condition);
            $this->db->limit(1);
            $query = $this->db->get();

            if ($query->num_rows() == 1) {
            return $query->result_array();
            } else {
            return false;
            }
        }
      public function get_service_commission($id){
           $this->db->select('*');
            $this->db->from('service_commission');
            $this->db->where('service_commission_id',$id);
            $this->db->limit(1);
            $query = $this->db->get();

            if ($query->num_rows() == 1) {
            return $query->result_array();
            } else {
            return false;
            }
      }
      
      public function beneficiary_exist($mobile) {
           $result = $this->db->get_where('beneficiary_list', array('beneficiary_mobile' => $mobile));
           //pre($result);exit;
           if ($result->num_rows() >= 1) {
             return $result->row();
           } else {
             return false;
           }
        }
      
    public function Login_check_mobile($data){
        $condition = "phone =" . "'" . $data['mobile']."'AND role='s'" ;
            $this->db->select('*');
            $this->db->from('user');
            $this->db->where($condition);
            $this->db->limit(1);
            $query = $this->db->get();

            if ($query->num_rows() == 1) {
            return $query->result_array();
            } else {
            return false;
            }
        }
         public function check_user($id){

            $this->db->select('*');
            $this->db->from('user');
            $this->db->where('user_id',$id);
            $this->db->limit(1);
            $query = $this->db->get();
        //    echo $this->db->last_query();exit;
            if ($query->num_rows() == 1) {
              return $query->row();
            } else {
              return false;
            }
        }
         public function exists($table, $data){
           $result = $this->db->get_where($table, $data);
        //   pre($result);exit;
           if ($result->num_rows() >= 1) {
             return $result->result();
           } else {
             return false;
           }
        }
  public function submenu_exists($menu, $parent)
  {
    $this->db->select('*');
    $this->db->from('menu_permission');
    $this->db->where('parent_id', $parent);
    $this->db->where('data_sub_menu', $menu);
    $result = $this->db->get();
    // echo $this->db->last_query();
    // exit;
    if ($result->num_rows() == 1) {
      return $result->row();
    } else {
      return false;
    }
  }
        public function get_otp($data){

            $this->db->select('*');
            $this->db->from('message');
            $this->db->where('key',$data['mobile']);
            $this->db->limit(1);
            $query = $this->db->get();

            if ($query->num_rows() == 1) {
            return $query->result_array();
            } else {
            return false;
            }
        }
public function check_otp($data){

            $this->db->select('*');
            $this->db->from('otp_details');
            $this->db->where('user_id',$data['user_id']);
            $this->db->where('otp',$data['code']);
            $this->db->limit(1);
            $query = $this->db->get();
          // echo $this->db->last_query();exit;
            if ($query->num_rows() == 1) {
            return true;
            }
        }
    //-- edit function
    function edit_option($action, $id, $table){
        $this->db->where('id',$id);
        $this->db->update($table,$action);
        return;
    }

    //-- update function
    function update($action,$field, $id, $table){
        return $this->db->where($field,$id)->update($table,$action);
    }

    //-- delete function
    function delete($data,$table){  return $this->db->delete($table, $data);  }

    function select_value($id,$table){
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where(array('id' => $id));
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        $query = $query->result_array();
        return $query;
    }

    //-- user role delete function
      function delete_user_role($id,$table){
        $this->db->delete($table, array('user_id' => $id));
        return;
    }
  function select_user(){
        $this->db->select();
        $this->db->from('user l');
        $this->db->order_by('user_id','ASC');
        $this->db->join('user_details u','l.user_id = u.user_id','INNER');

        $query = $this->db->get();
        $query = $query->result_array();
        return $query;
    }
function select_user_option($id){
        $this->db->select("*");
        $this->db->from('user l');
        //$this->db->order_by('user_id','ASC');
        $this->db->join('user_detail u', 'l.user_id = u.fk_user_id','left');
        $this->db->where('user_id', $id);
        $query = $this->db->get();
       // pre( $this->db->last_query());exit;
        $query = $query->result_array();
        return $query;
    }
  function select_user_doc($id)
  {
    $this->db->select("*");
    $this->db->from('documents');
    //$this->db->order_by('user_id','ASC');
    $this->db->where('root', $id);
    $query = $this->db->get();
    // pre( $this->db->last_query());exit;
    $query = $query->result_array();
    return $query;
  }
    //-- select function
    function select($table){
        $this->db->select();
        $this->db->from($table);
        $query = $this->db->get();
        $query = $query->result_array();
        return $query;
    }
    function select_limit_value($table, $limit){
        $this->db->select();
        $this->db->from($table);
        $this->db->order_by('joindate','DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        $query = $query->result_array();
        return $query;
    }
    // function select_attribute($table){
    //     $this->db->select('distinct(attribute),value');
    //     //$this->db->select('value');
    //     $this->db->from($table);
    //     $this->db->order_by('id','ASC');
    //     $query = $this->db->get();
    //     $query = $query->result_array();
    //     return $query;
    // }
function getMaxUserId(){
        $this->db->select('max(user_id) as id');
        $this->db->from('user');

        $query = $this->db->get();
        return $query->row('id');

    }
    //-- select by id
    function select_option($id,$field,$table){
        $this->db->select();
        $this->db->from($table);
        $this->db->where($field, $id);
        $query = $this->db->get();
        //pre( $this->db->last_query());exit;
        $query = $query->result_array();
        return $query;
    }
      function select_option1($id,$field,$table){
        $this->db->select();
        $this->db->from($table);
        $this->db->where($field, $id);
         $this->db->where('created>','2022-11-28');
        $query = $this->db->get();
        
        $query = $query->result_array();
        return $query;
    }
function check_kyc_status($id){
        $this->db->select("kyc_status");
        $this->db->from("user");
        $this->db->where("user_id", $id);
        $query = $this->db->get();
        
        $query = $query->row('kyc_status');
        return $query;
    }

    // File Upload

function get_wallet_by_id($id,$field){
        $this->db->select('wallet_transaction.*,user.member_id');
        $this->db->from('wallet_transaction');
        $this->db->where($field, $id);
        $this->db->join('user', 'user.user_id=wallet_transaction.member_to');
        $query = $this->db->get();
        //pre( $this->db->last_query());exit;
        $query = $query->result_array();
        return $query;
    }



  public function upload_image($max_size) {

          //-- set upload path
          $config['upload_path']  = UPLOAD_FILE;
          $config['allowed_types']= 'gif|jpg|png|jpeg';
          $config['max_size']     = '920000';
          $config['max_width']    = '92000';
          $config['max_height']   = '92000';

          $this->load->library('upload', $config);
          if ($this->upload->do_upload("file")) {

              $data = $this->upload->data();

              //-- set upload path
              $source             = UPLOAD_FILE ."/" . $data['file_name'] ;
              $destination_medium = UPLOAD_FILE . "/medium/" ;
              $main_img = $data['file_name'];

              // Permission Configuration
              chmod($source, 0777) ;
              /* Resizing Processing */
              // Configuration Of Image Manipulation :: Static
              $this->load->library('image_lib') ;
              $img['image_library'] = 'GD2';
              $img['create_thumb']  = TRUE;
              $img['maintain_ratio']= TRUE;

              /// Limit Width Resize
              $limit_medium   = $max_size ;
              $limit_thumb    = 200;

              // Size Image Limit was using (LIMIT TOP)
              $limit_use  = $data['image_width'] > $data['image_height'] ? $data['image_width'] : $data['image_height'] ;

              // Percentase Resize
              if ($limit_use > $limit_medium || $limit_use > $limit_thumb) {
                  $percent_medium = $limit_medium/$limit_use ;
                  $percent_thumb  = $limit_thumb/$limit_use ;
              }

              ////// Making MEDIUM /////////////
              $img['width']   = $limit_use > $limit_medium ?  $data['image_width'] * $percent_medium : $data['image_width'] ;
              $img['height']  = $limit_use > $limit_medium ?  $data['image_height'] * $percent_medium : $data['image_height'] ;

              // Configuration Of Image Manipulation :: Dynamic
              $img['thumb_marker'] = '_medium-'.floor($img['width']).'x'.floor($img['height']) ;
              $img['quality']      = '100%' ;
              $img['source_image'] = $source ;
              $img['new_image']    = $destination_medium ;

              $mid = $data['raw_name']. $img['thumb_marker'].$data['file_ext'];
              // Do Resizing
              $this->image_lib->initialize($img);
              $this->image_lib->resize();
              $this->image_lib->clear() ;

              //-- set upload path
              $images = UPLOAD_FILE . "/medium/" . $mid;
              unlink($source);

              return array(
                  'status' => 1,
                  'path' => $images,
              );
          }
          else {
            return array(
                'status' => 0,
                'error' => $this->upload->display_errors(),
            );
          }

  }




    public function get_cities_by_name($name=''){
      $this->db->select('name as id, name AS text');
      $this->db->from('cities');
      $this->db->where('name LIKE', $name.'%');
      $result = $this->db->get();
      return $result->result();
    }
    public function get_cities() {
      $this->db->select('name');
      $this->db->from('cities');
      $result = $this->db->get();
      return $result->result_array();
    }
    public function get_states() {
      $this->db->select('name');
      $this->db->from('states');
      $result = $this->db->get();
      return $result->result_array();
    }
    public function get_states_by_name($name=''){
      $this->db->select('name as id, name AS text');
      $this->db->from('states');
    $this->db->where('country_id', 101);
      $this->db->where('name LIKE', $name.'%');
      $result = $this->db->get();
      return $result->result();
    }
  public function get_service($name='')
  {
    $this->db->select('id , name AS text');
    $this->db->from('services');
    if($name!=''){
      $this->db->where('name LIKE', $name . '%');

    }
    $result = $this->db->get();
    return $result->result();
  }
    public function get_vendor_by_name($name,$role_id){
      $this->db->select('user_id as id, CONCAT(user_detail.first_name,last_name, " (", member_id, ") ") AS text');
      $this->db->from('user');
      $this->db->where('user.delete_user',0);
      if($role_id != ''){
      $this->db->where('user.role_id', $role_id );
      }
      else
      {
        $this->db->where('user.role_id!=',98);  
      }
      if($name){
      $this->db->where('user_detail.first_name LIKE', $name . '%');
      $this->db->or_where('user_detail.last_name LIKE', $name . '%');
      $this->db->or_where('phone LIKE', $name . '%');
       $this->db->or_where('user.member_id LIKE', $name . '%');
      }
    // $this->db->not_like('user.member_id', 'RT'); 
    
    
    $this->db->join("user_detail", "user_detail.fk_user_id=user.user_id","left");
    //   print_r($this->db->last_query());
    //   exit();
      $result = $this->db->get();
      return $result->result();
    // return $this->db->last_query();
    
    }
    
  public function get_vendor_by_name_by_prority($name,$role_id,$prority){
      $this->db->select('user_id as id, CONCAT(user_detail.first_name,last_name, " (", member_id, ") ") AS text');
      $this->db->from('user');
      $this->db->where('user.delete_user',0);
     
     
        $this->db->where('user.role_id!=',98);  
     
      if($name){
      $this->db->where('user_detail.first_name LIKE', $name . '%');
      $this->db->or_where('user_detail.last_name LIKE', $name . '%');
      $this->db->or_where('phone LIKE', $name . '%');
       $this->db->or_where('user.member_id LIKE', $name . '%');
      }
    // $this->db->not_like('user.member_id', 'RT'); 
     $this->db->where('roles.priority<',$prority); 
    
    $this->db->join("user_detail", "user_detail.fk_user_id=user.user_id","left");
    $this->db->join("roles", "roles.roles_id=user.role_id","left");
    //   print_r($this->db->last_query());
    //   exit();
      $result = $this->db->get();
      return $result->result();
    // return $this->db->last_query();
    
    }
    
  public function get_vendor_by_name_by_prority1($name,$userid,$role_id,$prority){
      $this->db->select('user_id as id, CONCAT(user_detail.first_name,last_name, " (", member_id, ") ") AS text');
      $this->db->from('user');
      $this->db->where('user.delete_user',0);
      $this->db->where('user.parent',$userid);
      if($name){
      $this->db->where('user_detail.first_name LIKE', $name . '%');
      $this->db->or_where('user_detail.last_name LIKE', $name . '%');
      $this->db->or_where('phone LIKE', $name . '%');
       $this->db->or_where('user.member_id LIKE', $name . '%');
      }
    $this->db->where('roles.priority>',$prority); 
    $this->db->join("user_detail", "user_detail.fk_user_id=user.user_id","left");
    $this->db->join("roles", "roles.roles_id=user.role_id","left");
      $result = $this->db->get();
      return $result->result();
    // return $this->db->last_query();
    
    }
  
  public function get_parent_menu($name="")
  {
    $this->db->select('menu_permission_id as id,data_menu as text');
    $this->db->from('menu_permission');
    // $this->db->where('parent_id', "");
    $this->db->where('data_menu LIKE', $name . '%');

    $result = $this->db->get();
    return $result->result();
  }
    public function rowCount($table){
      return $this->db->count_all($table);
    }

    public function returnKyc($id){
      return $this->db->select('kyc')->get_where('user', ['user_id' => $id])->row()->kyc;
    }

    public function get_last_id($table,$role) {
        $this->db->select('max(counter) as id');
        $this->db->from($table);
        $this->db->where('role_id', $role);
        $result = $this->db->get();
       if ($result->num_rows() == 1) {
           return $result->row()->id;
            } else {
            return false;
            }
        
    }
   public function get_wallet_id($id){
      $this->db->select('wallet_id');
        $this->db->from('wallet');
       $this->db->where('member_id', $id);
            $query = $this->db->get();

            if ($query->num_rows() >= 1) {
            return $query->row('wallet_id');
            } else {
            return false;
            }
     
     
 }   
    
 
  public function get_wallet_bal(){
      $this->db->select('*');
        $this->db->from('awt');
       
            $query = $this->db->get();

            if ($query->num_rows() == 1) {
            return $query->row();
            } else {
            return false;
            }
     
     
 }
     public function wallet_balance($id){
 $this->db->from('wallet');
            $this->db->where("member_id",$id);
            $this->db->limit(1);
            $query = $this->db->get();

            if ($query->num_rows() == 1) {
            return $query->row('balance');
            } else {
            return false;
            }
     
     
 }
 
  public function wallet_balance_remain($id){
      $this->db->select('sum(amount) as amount');
            $this->db->from('wallet_transaction');
            $this->db->where("member_from",$id);
            $this->db->where("status",'new');
            
            $query = $this->db->get();
            //pre( $this->db->last_query());exit;
            if ($query->num_rows() >= 1) {
            return $query->row();
            } else {
            return false;
            }
     
     
 }
  public function wallet_balance_total($id){
      $this->db->select('`role_id`,sum(`balance`) as bal');
            $this->db->from('wallet');
            $this->db->where("role_id",$id);
            $this->db->group_by('role_id');
            $this->db->limit(1);
            
            $query = $this->db->get();
//pre( $this->db->last_query());exit;
            if ($query->num_rows() == 1) {
            return $query->result_array();
            } else {
            return false;
            }
     
     
 }
 
  public function getBeneficiaryMobile($bid) {
      $result = $this->db->select('beneficiary_account_number,beneficiary_ifsc,beneficiary_mobile,ba_primary')->get_where('beneficiary_list', array('ba_primary' => $bid));
      if ($result->num_rows()) {
        return $result->row();
      } else {
        return false;
      }
    }
    
     public function getBeneficiaryDetails($bid) {
      $result = $this->db->select('*')->get_where('beneficiary_list', array('ba_primary' => $bid));
      if ($result->num_rows()) {
        return $result->row();
      } else {
        return false;
      }
    }
    
    
// <!--Start Reset Password-->

        public function user_check($id){

            $this->db->select('phone,email');
            $this->db->from('user');
            $this->db->where('member_id',$id);
            $this->db->limit(1);
            $query = $this->db->get();
            if ($query->num_rows() == 1) {
              return $query->row();
            } else {
              return false;
            }
        }

    public function resetpass($id,$password){
        
        $this->db->set('password', $password); 
        $this->db->where('user_id', $id);   
        $this->db->update('user'); 
        return $this->db->affected_rows(); 
        
    }
    
    function get_charge_by_move_bank($service, $range) {
          $this->db->select('charge');
          $this->db->from('service_charge');
          $this->db->where('service_id',$service);
          $this->db->where('start_range <=',$range);
          $this->db->where('end_range >=',$range);
          $query = $this->db->get();
          return $query->row('charge');
       }


// <!--End Reset Password--> 

    function sub_charge($range,$id){

          $this->db->select('charge, c_flate');
          $this->db->from('service_charge');
          $this->db->where('service_id',$id);
          $this->db->where('start_range <=',$range);
          $this->db->where('end_range >=',$range);
          $query = $this->db->get();
          return $query->row();		
      
      
    }
    
     public function wallet_balance_total_admin($id){
      $this->db->select('sum(`balance`) as bal');
            $this->db->from('wallet');
            $query = $this->db->get();
            if ($query->num_rows() == 1) {
            return $query->result_array();
            } else {
            return false;
            }
     
     
    }
    
    
    public function pan_amount($data){
        
        $this->db->select();
        $this->db->from('pan_coupon_amount');
        $query = $this->db->get();
        if ($query->num_rows() == 2) {
            $result = [
                    "row" => $query->num_rows() ,
                    "data" => $query->result_array()
                ]  ;              
             return $result;
        } else {
            $this->db->insert('pan_coupon_amount',$data);
            return $this->db->insert_id();
        }
    }
    
    public function pan_amount_get(){
        
        $this->db->select();
        $this->db->from('pan_coupon_amount');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function service_get($name){
        
        $this->db->select('id , name, created');
        $this->db->from('services');
        $this->db->where('name',$name);
        $query = $this->db->get();
        return $query->row();
    }
    
    public function panagent_check($submerchant){
        
        $this->db->select();
        $this->db->from('pan_agent');
        $this->db->where('member_id',$submerchant);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
             return $query->row();
        } else {
             return false;
        }
    }

    public function key_details(){
        
        $this->db->select();
        $this->db->from('key_details');
        $query = $this->db->get();
        return $query->row();
    }


    
}
