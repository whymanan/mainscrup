<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Commission extends Vite
{


    public $data = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('commission_model');
        $this->load->model('menu_model');
        $this->load->model('users_model');
        $this->data['active'] = 'Commision';
        $this->data['breadcrumbs'] = [array('url' => base_url('commission'), 'name' => 'Commision')];
    }

    public function index()
    {
        $this->data['commision']=$this->common_model->select('service_commission_main');

        $this->data['param'] = $this->paremlink('add');
        $this->data['main_content'] = $this->load->view('index', $this->data, true);
        $this->data['is_script'] = $this->load->view('script', $this->data, true);

        $this->load->view('layout/index', $this->data);
    }

    


    public function add()
    {
        $this->data['param'] = $this->paremlink('/');
        $this->data['main_content'] = $this->load->view('add', $this->data, true);
        $this->data['is_script'] = $this->load->view('script', $this->data, true);
        $this->load->view('layout/index', $this->data);
    }
    public function submit()
    {

        $data = $this->security->xss_clean($_POST);
       
        if ($data) {
            if($data['flat']=='on')
                $flat  =1;
                else
                $flat =0;

             $service = [
                            'start_range' =>$data['start'],
                            'end_range' => $data['end'],
                            'service_id' =>  $data['service_id'],
                            'role_id' =>  $data['role_id'],
                              'g_commission' =>  $data['rate'],
                             'c_flat' => $flat,
                            'created' => current_datetime(),
                            'max_commission' => $data['max'],
                            
                        ];
                        
            $id = $this->common_model->insert($service, 'service_commission');
            
            redirect('commission', 'refresh');
        }
    }
    
      public function delete($id){
       return  $this->db->delete('service_commission', array('service_commission_id' => $id));
      } 
    
      public function get_list()
    {

        $uri = $this->security->xss_clean($_GET);
        if (!empty($uri)) {
            $query = '';

            $output = array();


            $list = $uri['list'];

            $data = array();

            switch ($list) {
                case 'all':
                    // code...
                    $query .= "Select service_commission.*, roles.role from service_commission join roles on service_commission.role_id= roles.roles_id  ";

                    break;

                default:
                    $query .= "Select service_commission.*, roles.role from service_commission join roles on service_commission.role_id= roles.roles_id  ";

                    break;
            }


            if (!empty($_GET["search"]["value"])) {
                $query .= 'OR start LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= 'OR end LIKE "%' . $_GET["search"]["value"] . '%" ';

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
            

                $sub_array[] = '<button type="button" class="btn btn-sm btn-info"  data-placement="bottom" onclick="Edit(' . $row['service_commission_id'] . ')" title="EDIT  Information"><i class="fa fa-pencil-alt"></i></button>
           <button type="button" class="btn btn-sm btn-primary"  data-placement="bottom" onclick="Delete('. $row['service_commission_id'] .')" title="Delete Information"><i class="fa fa-trash-alt"></i></button>';
                $sub_array[] = $row['role'];
               
                
                $sub_array[] = $row['start_range'];
                $sub_array[] = $row['end_range'];
                 $sub_array[] = $row['start_range'];
                $sub_array[] = $row['g_commission'];
                $sub_array[] = $row['c_flat'];
                $data[] = $sub_array;
                $i++;
            }

            $output["draw"] = intval($_GET["draw"]);
            $output["recordsTotal"] = $i - 1;
            $output["recordsFiltered"] = $filtered_rows;
            $output["data"] = $data;

            echo json_encode($output);
        }
    }
    
    public function edit($id)
    {
        $data['slab'] = $this->common_model->get_service_commission($id);
        if($data['slab']!="")
       echo json_encode($data['slab']);
        else 
        echo 1;
       
    }
    
    public function get_slab()
    {
        $data['slab'] = $this->common_model->select('slab');
        $data['service'] = $this->common_model->select('services');


        echo $this->load->view('commision/slab', $data, true);
    }

    public function view() {



   $uri = $this->security->xss_clean($_GET);



   if (isset($uri['q']) && !empty($uri['q'])) {



     $this->data['param'] = $this->paremlink('/');



     $this->data['slab'] = $this->common_model->select('slab');

     $this->data['service'] = $this->common_model->select('services');

     $this->data['main'] = $this->common_model->select_option($uri['q'],'service_commission_main_id','service_commission_main');

     if (!empty($this->data['main'])) {

       $this->data['role'] = $this->data['main'][0]['role'];

     }

     $this->data['main_content'] = $this->load->view('edit', $this->data, true);

     $this->data['is_script'] = $this->load->view('script', $this->data, true);

     $this->load->view('layout/index', $this->data);



   }else{



     echo json_encode(['error' => 'true', 'msg' => 'Request Not allowed']);



   }

}



    public function update()
    {
        if ($_POST) {
            $data = $this->security->xss_clean($_POST);
            //pre($_POST);exit;
            $logme = [


                'email' => $data["email"],
                'parent' => $data["vendor"],
                'phone' => $data["phone_no"],

                'role_id' => $data["user_role"],
                'updated_by' => $this->session->userdata('userID'),
                'user_status' => 'pending',
                'updated_at' => current_datetime()
            ];

            $this->common_model->update($logme, 'user_id', $_POST['user_id'], 'user');

            $userdata = [

                'first_name' => $data["firstname"],
                'last_name' => $data["lastname"],
                'aadhar' => $data["adharcard"],
                'pan' => $data["pancard"],
                'organisation' => $data["organization_name"],
                'gstno' => $data["gst_no"],
                'address' => $data["address"],
                'state' => $data["states"],
                'city' => $data["city"],
                'pincode' => $data["pincode"],
                'updated_at' => current_datetime(),
                'updated_by' => $this->session->userdata('userID'),
            ];

            $this->common_model->update($userdata, 'user_detail_id', $_POST['user_detail_id'], 'user_detail');
        }

        $this->data['main_content'] = $this->load->view('users', $this->data, true);
        $this->load->view('layout/index', $this->data);
    }

    public function live_count()
    {
        $uri = $this->security->xss_clean($_GET);
        if (isset($uri['key']) && !empty($uri['key'])) {
            $data = array();
            $duid = $uri['key'];
            if (isAdmin($this->session->userdata('user_roles'))) {
                $result = $this->users_model->totel_count();
            } else {
                $result = $this->users_model->totel_count($duid);
            }
            foreach ($result as $value) {
                $data[$value->kyc_status] = number_format($value->totel, 0);
            }
            echo json_encode($data);
        }
    }
}
