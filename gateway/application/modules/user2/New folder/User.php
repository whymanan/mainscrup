<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends Vite
{

    public $data = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        
        $this->load->model('users_model');
        $this->data['active'] = 'user';
        $this->data['breadcrumbs'] = [array('url' => base_url('user'), 'name' => 'User')];
    }

    public function index()
    {
        $this->data['param'] = $this->paremlink('add');
        //$this->data['dist'] = $this->users_model->get_by_id(2);
        $this->data['main_content'] = $this->load->view('user/index', $this->data, true);
        $this->data['is_script'] = $this->load->view('user/script', $this->data, true);
        $this->load->view('layout/index', $this->data);
    }

    public function add()
    {
        array_push($this->data['breadcrumbs'], array('url' => base_url('add'), 'name' => 'Add New'));
        $this->data['param'] = $this->paremlink('/');
       
        $this->data['main_content'] = $this->load->view('user/add', $this->data, true);
        $this->data['is_script'] = $this->load->view('user/script', $this->data, true);
        $this->load->view('layout/index', $this->data);
    }

    public function submit()
    {

        $data = $this->security->xss_clean($_POST);
        if ($this->common_model->exists('logme', ['phone' => $data['phone_no']])) { //$data['phone_no']
            exit('User already Exist');
        }
        if ($data) {
            $pass = randomPassword();
            $uid = getCustomId($this->common_model->get_last_id('logme'), "EMO");
            $logme = [
                'logid' => $uid,
                'name' => $data["firstname"] . " " . $data["lastname"],
                'email' => $data["email"],
                'parent' => $data["vendor"],
                'phone' => $data["phone_no"],
                'city' => $data["city"],
                'role_id' => 2,
                'password' => password_hash($data["vendor"], PASSWORD_DEFAULT),
                'created' => $this->session->userdata('userID'),
                'status' => 'pending',
                'joindate' => current_datetime()
            ];

            if ($this->common_model->insert($logme, 'logme')) {
                $userdata = [
                    'user_id' => $uid,
                    'first_name' => $data["firstname"],
                    'last_name' => $data["lastname"],
                    'aadhar' => $data["adharcard"],
                    'pan' => $data["pancard"],
                    'k_origination' => $data["organization_name"],
                    'gstno' => $data["gst_no"],
                    'address' => $data["address"],
                    'state' => $data["states"],
                    'city' => $data["city"],
                    'pincode' => $data["pincode"],
                    'created' => current_datetime(),
                ];
                if ($this->common_model->insert($userdata, 'user_detail')) {
                    $email = [
                        'sendfrom' => 'info@emopay.com',
                        'sendto' => $data['email'],
                        'subject' => 'Hello World',
                        'template' => json_encode(
                            array(
                                "uid" => $uid,
                                "phone" => $logme['phone'],
                                "password" => $pass,
                                "joindate" => $logme['joindate']
                            )
                        ),
                    ];
                    if ($this->set_email($email)) {
                        $this->session->set_flashdata(
                            array(
                                'error' => 0,
                                'msg' => "user joining successfull, please check your email."
                            )
                        );
                    } else {
                        $this->session->set_flashdata(array('error' => 2, 'msg' => 'user Information Save, Email Sending Failed :('));
                    }
                }
            } else {
                $this->session->set_flashdata(array('error' => 1, 'msg' => 'Action Failed'));
            }
            redirect('user', 'refresh');
        }
    }


    public function set_email($email)
    {
        return $this->common_model->insert($email, 'emails');
    }

    public function get_squadlist()
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
                    $query .= "SELECT * FROM logme WHERE role_id = '2' ";
                    $output["recordsFiltered"] =    $this->users_model->userCount();
                    break;

                case 'pending':
                    // code...
                    $query .= "SELECT * FROM logme WHERE role_id = '2' AND kyc != 'varify' ";
                    $output["recordsFiltered"] =    $this->users_model->userCount();
                    break;

                case 'varify':
                    // code...
                    $query .= "SELECT * FROM logme WHERE  role_id = '2' AND kyc = 'varify' ";
                    $output["recordsFiltered"] =    $this->users_model->userCount();
                    break;

                default:
                    $query .= "SELECT * FROM logme WHERE  role_id = '2' ";
                    $output["recordsFiltered"] =    $this->users_model->userCount();
                    break;
            }


            if (!empty($_GET["search"]["value"])) {
                $query .= 'OR logid LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= 'OR name LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= 'OR city LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= 'OR email LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= 'OR phone LIKE "%' . $_GET["search"]["value"] . '%" ';
                $query .= 'OR joindate LIKE "%' . $_GET["search"]["value"] . '%" ';
            }

            if (!empty($_GET["order"])) {
                $query .= 'ORDER BY ' . $_GET['order']['0']['column'] . ' ' . $_GET['order']['0']['dir'] . ' ';
            } else {
                $query .= 'ORDER BY created DESC ';
            }

            if ($_GET["length"] != -1) {
                $query .= 'LIMIT ' . $_GET['start'] . ', ' . $_GET['length'];
            }
            $sql = $this->db->query($query);
            $result = $sql->result_array();
            $filtered_rows = $sql->num_rows();
            foreach ($result as $row) {
                $status = '';
                $kyc = '';
                if ($row['kyc'] == 'varify') {
                    // code...
                    $kyc = '<i class="fa fa-circle text-success font-12" data-toggle="tooltip" data-placement="top" title="" data-original-title="Active"></i>';
                } else {
                    $kyc = '<i class="fa fa-circle text-warning font-12" data-toggle="tooltip" data-placement="top" title="" data-original-title="Pending"></i>';
                }
                if ($row['status'] == 'active') {
                    $status = '<span class="badge badge-success">Active</span>';
                } elseif ($row['status'] == 'pending') {
                    $status = '<span class="badge badge-warning">Pending</span>';
                } else {
                    $status = '<span class="badge badge-danger">Deactive</span>';
                }

                $sub_array = array();
                $sub_array[] = '<a href="' . base_url('user/edit?q=') . $row['logid'] . '"> <button type="button" class="btn btn-sm btn-link"  data-placement="bottom" title="Edit user Information"><i class="fa fa-pencil-alt"></i></button></a>
          <button type="button" class="btn btn-sm btn-link"  data-placement="bottom" title="status Pendding"><i class="fa fa-eye" aria-hidden="true"></i></button>';
                $sub_array[] = $row['name'];
                $sub_array[] = $row['phone'];
                $sub_array[] = $this->users_model->nameById($row['parent']);
                $sub_array[] = $row['city'];
                $sub_array[] = $status;
                $sub_array[] = $kyc;
                $sub_array[] = my_date_show($row['joindate']);
                $data[] = $sub_array;
            }

            $output["draw"] = intval($_GET["draw"]);
            $output["recordsTotal"] = $filtered_rows;
            $output["data"] = $data;

            echo json_encode($output);
        }
    }


    public function edit()
    {
        $this->data['param'] = $this->paremlink('/');
        $uri = $this->security->xss_clean($_GET);
        if (isset($uri['q']) && !empty($uri['q'])) {
            $uid = $uri['q'];
            if (!$this->common_model->exists('logme', ['logid' => $uid])) {
                exit('User dosn\'t Exist');
            }
            $this->data['user'] = $this->users_model->find($uid);
            $this->data['details'] = $this->users_model->find_details($uid);
            $this->data['main_content'] = $this->load->view('user/edit', $this->data, true);
            $this->data['is_script'] = $this->load->view('user/script', $this->data, true);
            $this->load->view('layout/index', $this->data);
        }
    }

    public function update()
    {
        if ($_POST) {


            $data1['phone'] = $_POST['mobile'];
            $data1['email'] = $_POST['email'];
            $data1['name'] = $_POST['firstname'] . " " . $_POST['lastname'];

            $data1['city'] = $_POST['city'];
            $data1['parent'] = $_POST['vendor'];

            $this->common_model->update($data1, 'id', $_POST['user_id'], 'logme');

            $data1 = array();

            $data1['pincode'] = $_POST['postalcode'];

            $data1['address'] = $_POST['address'];
            $data1['last_name'] = $_POST['lastname'];
            $data1['state'] = $_POST['state'];
            $data1['city'] = $_POST['city'];
            $data1['first_name'] = $_POST['firstname'];
            $data1['aadhar'] = $_POST['adhar'];
            $data1['pan'] = $_POST['pan'];

            $this->common_model->update($data1, 'id', $_POST['user_detail_id'], 'user_detail');
        }

        $this->data['main_content'] = $this->load->view('user/add', $this->data, true);
        $this->load->view('layout/index', $this->data);
    }


}
