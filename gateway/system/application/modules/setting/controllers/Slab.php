<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Slab extends Vite
{


    public $data = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('menu_model');
        $this->data['active'] = 'Slab';
        $this->data['breadcrumbs'] = [array('url' => base_url('slab'), 'name' => 'Slab')];
    }

    public function index()
    {

        $this->data['main_content'] = $this->load->view('slab', $this->data, true);
        $this->data['is_script'] = $this->load->view('script', $this->data, true);

        $this->load->view('layout/index', $this->data);
    }
    public function delete($id)
    {
        if ($this->db->where("slab_id", $id)->delete('slab')) {
            echo 1;
        } else {
            echo 0;
        }
    }
    public function submit()
    {

        $data = $this->security->xss_clean($_POST);

        if ($data) {
                $logme['slab'] = $data['start'];
                $logme['end'] = $data['end'];
                $logme['created'] = current_datetime();
          


            if ($this->common_model->insert($logme, 'slab')) {


                $this->session->set_flashdata(
                    array(
                        'status' => 1,
                        'msg' => "Slab Added Successfully"
                    )
                );
                redirect('slab', 'refresh');
            }
        }
    }

    public function get_slablist()
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
                    $query .= "Select * from slab ";

                    break;

                default:
                    $query .= "Select * from slab ";

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
            

                $sub_array[] = '<button type="button" class="btn btn-sm btn-info"  data-placement="bottom" onclick="Edit(' . $row['slab_id'] . ')" title="EDIT Menu Information"><i class="fa fa-pencil-alt"></i></button>
           <button type="button" class="btn btn-sm btn-primary"  data-placement="bottom" onclick="Delete(' . $row['slab_id'] . ')" title="Delete Menu Information"><i class="fa fa-trash-alt"></i></button>';
                $sub_array[] = $row['start'];
                $sub_array[] = $row['end'];
                
                $sub_array[] = $row['created'];


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
        $slab = $this->common_model->select_option($id, 'slab_id', 'slab');
        echo json_encode($slab[0]);
    }

    public function update()
    {
        $data = array();
        $form = $this->security->xss_clean($_POST);
                //pre($form);exit;

        foreach ($form['form'] as $row) {

            $arr = array(
                $row['name'] => $row['value']
            );
            array_push($data, $arr);
        }
        //pre($data);exit;
        if ($data) {
            $logme['start'] = $data[0]['start'];
            $logme['end'] = $data[1]['end'];
            $logme['updated'] = current_datetime();
          
         

            if ($this->common_model->update($logme, "slab_id", $data[3]['slab_id'], 'slab')) {
                echo 1;
            } else {
                echo 2;
            }
        }
    }
}
