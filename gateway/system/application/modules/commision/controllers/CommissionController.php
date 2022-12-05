<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CommissionController extends Vite
{


    public $data = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('commision_model');
        $this->load->model('menu_model');
        $this->load->model('users_model');
        $this->data['active'] = 'Commision';
        $this->data['breadcrumbs'] = [array('url' => base_url('commission'), 'name' => 'Commision')];
    }

    public function index() {

        $this->data['commision'] = $this->common_model->select('service_commission_main');

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

            $service = [
                'name' => $data["name"],
                'role' => $data["user_role"],
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => current_datetime()
            ];
            $id = $this->common_model->insert($service, 'service_commission_main');
            if ($id) {
               for($i = 1; $i <= count($data['rate']); $i++){
                   for($j=1; $j<= count($data['rate'][$i]); $j++){
                        $userdata = [
                            'fk_service_main_id' => $id,
                            'role_id' => $data['user_role'],
                            'service_id' => $i,
                            'slab_id' => $j,

                            'created' => current_datetime(),
                            'rate' => $data['rate'][$i][$j],
                        ];
                        $this->common_model->insert($userdata, 'service_commission');
                   }
               }

                $this->session->set_flashdata(array('error' => 0, 'msg' => 'Added Successfully'));
            } else {
                $this->session->set_flashdata(array('error' => 1, 'msg' => 'Action Failed'));
            }
            redirect('commission', 'refresh');
        }
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

    public function edit() {
        $this->data['param'] = $this->paremlink('/');
        $uri = $this->security->xss_clean($_GET);
        if (isset($uri['q']) && !empty($uri['q'])) {
            $uid = $uri['q'];
            if (!$this->common_model->exists('user', ['user_id' => $uid])) {
                exit('User dosn\'t Exist');
            }
            $this->data['user'] = $this->users_model->find($uid);
            $this->data['details'] = $this->users_model->find_details($uid);
            $this->data['main_content'] = $this->load->view('edit', $this->data, true);
            $this->data['is_script'] = $this->load->view('script', $this->data, true);
            $this->load->view('layout/index', $this->data);
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
