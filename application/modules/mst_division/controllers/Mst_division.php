<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mst_division extends CI_Controller
{
    var $active_menu = array(
        'active' => 'menu_master',
        'sub_active' => 'submenu_master_division',
    );
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_mst_division', 'M_mst_division');
        $this->load->model('mst_department/M_mst_department', 'M_mst_department');

        if (!$this->check_session->check()) {
            redirect(base_url('auth/do_logout'));
            exit();
        }
    }

    // ================= CONTENT PURPOSES ====================//

    public function index()
    {
        $content = array();

        //default content
        $content['headmenu'] = $this->load->view('template/headmenu', NULL, TRUE);
        $content['footer'] = $this->load->view('template/footer', NULL, TRUE);
        $content['lib_js'] = $this->load->view('template/init_js', $this->active_menu, TRUE);

        //content
        $content['page_title'] = 'Master Division';
        $content['content_title'] = 'Master Division';
        if (!$this->check_session->check_module($this->active_menu)) {
            $content['maincontent'] = $this->load->view('template/v_forbidden', NULL, TRUE);
        } else {
            $content['maincontent'] = $this->load->view('v_index', NULL, TRUE);
            $content['maincontent_script'] = $this->load->view('v_script', NULL, TRUE);
        }

        $this->load->view('template/v_base', $content);
    }

    public function getForm()
    {
        $param = array();
        $param['id'] = $this->input->post('id');
        $param['mode'] = $this->input->post('mode');

        $return_array = array();
        $return_array["res"] = 1;
        $return_array["message"] = 'success';
        try {
            switch ($param['mode']) {
                case 'add':
                    $return_array['content'] = $this->load->view('v_add', $param, TRUE);
                    break;
                case 'edit':
                    $return_array['content'] = $this->load->view('v_edit', $param, TRUE);
                    break;
                case 'view':
                    $return_array['content'] = $this->load->view('v_view', $param, TRUE);
                    break;
                default:
                    $return_array['content'] = $this->load->view('v_view', $param, TRUE);
            }
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    // ================= CONTENT PURPOSES ====================//

    // ================= GET DATATABLES ====================//

    public function get_main_table()
    {
        $list = $this->M_mst_division->get_datatables();
        //        echo "<pre>"; print_r($list); die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;

            $row = array();
            $row[] = $no;
            $row[] = $field->code;
            $row[] = $field->name;
            $row[] = $field->description;
            $row[] = $field->department;
            $row[] = $field->is_active;

            $str = '';
            $str .= '<button style="z-index: unset;margin: 2px;" class="btn-floating btn-small orange" data-id="' . $field->id . '" onclick=getForm("view",' . $field->id . ');><i class="material-icons">search</i></button>';
            $str .= '<button style="z-index: unset;margin: 2px;" class="btn-floating btn-small green" data-id="' . $field->id . '" onclick=getForm("edit",' . $field->id . ');><i class="material-icons">edit</i></button>';

            $row[] = $str;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_mst_division->count_all(),
            "recordsFiltered" => $this->M_mst_division->count_filtered(),
            "data" => $data
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    // ================= GET DATATABLES ====================//

    // ================= GET DATA PURPOSES ====================//

    public function getDataView()
    {
        $return_array = array();
        try {
            $param = array();
            $param['id'] = $this->input->post('id');

            $return_array = $this->M_mst_division->getDataView($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getDataEdit()
    {
        $return_array = array();
        try {
            $param = array();
            $param['id'] = $this->input->post('id');

            $return_array = $this->M_mst_division->getDataEdit($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getDataSelect_department()
    {
        $return_array = array();
        try {
            $param = array();
            $param['is_active'] = 1;

            $return_array = $this->M_mst_department->getData_department($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    // ================= GET DATA PURPOSES ====================//

    // ================= CUD PURPOSES START ====================//

    public function submit()
    {
        $return_array = array();
        try {

            $param = array();


            $now = new DateTime(NULL, new DateTimeZone('Asia/Jakarta'));
            $current_datetime = $now->format('Y-m-d H:i:s');
            $current_date = $now->format('Y-m-d');
            $current_year = $now->format('Y');
            $current_year_2digit = $now->format('y');
            $current_month = $now->format('m');
            $id_user = $this->session->userdata('id_user');

            $id = $this->input->post('id');
            $code = $this->input->post('code');
            $name = $this->input->post('name');
            $description = $this->input->post('description');
            $is_active = $this->input->post('is_active');
            $id_department = $this->input->post('id_department');
            $submit_type = $this->input->post('submit_type');


            //=========================================================================================================

            $param['division'] = array();
            $param['division']['id'] = !is_null($id) ? $id : NULL;
            $param['division']['code'] = !is_null($code) ? strtoupper($code) : NULL;
            $param['division']['name'] = !is_null($name) ? $name : NULL;
            $param['division']['description'] = !is_null($description) ? $description : NULL;
            $param['division']['is_active'] = !is_null($is_active) ? $is_active : NULL;
            $param['division']['id_department'] = !is_null($id_department) ? $id_department : NULL;

            //=========================================================================================================


            if ($submit_type == 'add') {
                $this->M_mst_division->submit_add($param);
            } elseif ($submit_type == 'edit') {
                $this->M_mst_division->submit_edit($param);
            } else {
                throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : INVALID Parameter ');
            }

            $return_array["res"] = 1;
            $return_array["message"] = 'success';
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    // ================= CUD PURPOSES END ====================//
}
