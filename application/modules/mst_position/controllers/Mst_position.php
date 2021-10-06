<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mst_position extends CI_Controller
{
    var $active_menu = array(
        'active' => 'menu_master',
        'sub_active' => 'submenu_master_position',
    );

    function __construct()
    {
        parent::__construct();
        $this->load->model('M_mst_position', 'M_mst_position');
        $this->load->model('mst_department/M_mst_department', 'M_mst_department');
        $this->load->model('mst_division/M_mst_division', 'M_mst_division');

        if (!$this->check_session->check()) {
            redirect(base_url('auth/do_logout'));
            exit();
        }
    }

    // ================= CONTENT PURPOSES START ====================//

    public function index()
    {
        $content = array();

        //default content
        $content['headmenu'] = $this->load->view('template/headmenu', NULL, TRUE);
        $content['footer'] = $this->load->view('template/footer', NULL, TRUE);
        $content['lib_js'] = $this->load->view('template/init_js', $this->active_menu, TRUE);

        //content
        $content['page_title'] = 'Master Position';
        $content['content_title'] = 'Master Position';
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

    // ================= CONTENT PURPOSES END ====================//

    // ================= GET DATATABLES START ====================//

    public function get_main_table()
    {
        $list = $this->M_mst_position->get_datatables();
        //        echo "<pre>"; print_r($list); die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;

            $row = array();
            $row[] = $no;
            $row[] = $field->code;
            $row[] = $field->name;
            $row[] = $field->parent;
            $row[] = $field->department;
            $row[] = $field->division;
            $row[] = $field->is_active;

            $str = '';
            $str .= '<button style="z-index: unset;margin: 2px;" class="btn-floating btn-small orange" data-id="' . $field->id . '" onclick=getForm("view",' . $field->id . ');><i class="material-icons">search</i></button>';
            $str .= '<button style="z-index: unset;margin: 2px;" class="btn-floating btn-small green" data-id="' . $field->id . '" onclick=getForm("edit",' . $field->id . ');><i class="material-icons">edit</i></button>';

            $row[] = $str;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_mst_position->count_all(),
            "recordsFiltered" => $this->M_mst_position->count_filtered(),
            "data" => $data
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    // ================= GET DATATABLES END ====================//

    // ================= GET DATA PURPOSES START ====================//

    public function getDataView()
    {
        $return_array = array();
        try {
            $param = array();
            $param['id'] = $this->input->post('id');

            $return_array = $this->M_mst_position->getDataView($param);
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

            $return_array = $this->M_mst_position->getDataEdit($param);
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

    public function getDataSelect_division()
    {
        $return_array = array();
        try {
            $param = array();
            $param['is_active'] = 1;
            $param['id_department'] = $this->input->post('id_department');

            $return_array = $this->M_mst_division->getData_division($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getDataSelect_parent_position()
    {
        $return_array = array();
        try {
            $param = array();
            $param['is_active'] = 1;
            $param['id_department'] = $this->input->post('id_department');
            $param['id_division'] = $this->input->post('id_division');

            $return_array = $this->M_mst_position->getData_position($param);
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
            $id_parent = $this->input->post('id_parent');
            $code = $this->input->post('code');
            $name = $this->input->post('name');
            $description = $this->input->post('description');
            $is_active = $this->input->post('is_active');
            $level = 0;
            $id_department = $this->input->post('id_department');
            $has_department = !is_null($id_department) or $id_department > 0 ? 1 : 0;
            $id_division = $this->input->post('id_division');
            $has_division = !is_null($id_division) or $id_division > 0 ? 1 : 0;

            $submit_type = $this->input->post('submit_type');


            //=========================================================================================================

            $param['position'] = array();
            $param['position']['id'] = !is_null($id) ? $id : NULL;
            $param['position']['id_parent'] = !is_null($id_parent) ? $id_parent : NULL;
            $param['position']['code'] = !is_null($code) ? strtoupper($code) : NULL;
            $param['position']['name'] = !is_null($name) ? $name : NULL;
            $param['position']['description'] = !is_null($description) ? $description : NULL;
            $param['position']['is_active'] = !is_null($is_active) ? $is_active : NULL;
            $param['position']['level'] = !is_null($level) ? $level : NULL;
            $param['position']['has_department'] = !is_null($has_department) ? $has_department : NULL;
            $param['position']['id_department'] = !is_null($id_department) ? $id_department : NULL;
            $param['position']['has_division'] = !is_null($has_division) ? $has_division : NULL;
            $param['position']['id_division'] = !is_null($id_division) ? $id_division : NULL;

            //=========================================================================================================


            if ($submit_type == 'add') {
                $this->M_mst_position->submit_add($param);
            } elseif ($submit_type == 'edit') {
                $this->M_mst_position->submit_edit($param);
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
