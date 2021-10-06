<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mst_role extends CI_Controller
{
    var $active_menu = array(
        'active' => 'menu_master',
        'sub_active' => 'submenu_master_role',
    );

    function __construct()
    {
        parent::__construct();
        $this->load->model('M_mst_role', 'M_mst_role');
        $this->load->model('mst_role/M_mst_role_akses', 'M_mst_role_akses');

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
        $content['page_title'] = 'Master Role';
        $content['content_title'] = 'Master Role';
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
        $list = $this->M_mst_role->get_datatables();
        //        echo "<pre>"; print_r($list); die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;

            $row = array();
            $row[] = $no;
            $row[] = $field->name;
            $row[] = $field->description;
            $row[] = $field->is_active;

            $str = '';
            $str .= '<button style="z-index: unset;margin: 2px;" class="btn-floating btn-small orange" data-id="' . $field->id . '" onclick=getForm("view",' . $field->id . ');><i class="material-icons">search</i></button>';
            $str .= '<button style="z-index: unset;margin: 2px;" class="btn-floating btn-small green" data-id="' . $field->id . '" onclick=getForm("edit",' . $field->id . ');><i class="material-icons">edit</i></button>';

            $row[] = $str;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_mst_role->count_all(),
            "recordsFiltered" => $this->M_mst_role->count_filtered(),
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

            $return_array = $this->M_mst_role->getDataView($param);
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

            $return_array = $this->M_mst_role->getDataEdit($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getData_role_akses()
    {
        $return_array = array();
        try {
            $param = array();
            $param['id'] = $this->input->post('id');

            $return_array = $this->M_mst_role_akses->getData_role_akses($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    // ================= GET DATA PURPOSES END ====================//

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
            $name = $this->input->post('name');
            $description = $this->input->post('description');
            $is_active = $this->input->post('is_active');

            $submit_type = $this->input->post('submit_type');

            $param_akses = array();
            $param_akses['id'] = $this->input->post('id');
            $data_akses = $this->M_mst_role_akses->getData_role_akses($param_akses);


            //=========================================================================================================

            $param['role'] = array();
            $param['role']['id'] = !is_null($id) ? $id : NULL;
            $param['role']['name'] = !is_null($name) ? $name : NULL;
            $param['role']['description'] = !is_null($description) ? $description : NULL;
            $param['role']['is_active'] = !is_null($is_active) ? $is_active : NULL;
            $param['role']['created_by'] = !is_null($id_user) ? $id_user : NULL;


            //=========================================================================================================

            $param['def_role_akses_map'] = array();
            $index_pb = 0;
            foreach ($data_akses['data']['def_akses_header'] as $k_aks_h => $v_aks_h) {
                $cb_akses_h = $this->input->post('cb_akses_h-' . $v_aks_h->id);
                $is_active_h = 0;
                if ($cb_akses_h == 'on') {
                    $is_active_h = 1;
                }

                $param['def_role_akses_map'][$index_pb]['id_role'] = !is_null($id) ? $id : NULL;
                $param['def_role_akses_map'][$index_pb]['id_akses_header'] = !is_null($v_aks_h->id) ? $v_aks_h->id : NULL;
                $param['def_role_akses_map'][$index_pb]['id_akses_detail'] = 0;
                $param['def_role_akses_map'][$index_pb]['is_active'] = $is_active_h;
                $param['def_role_akses_map'][$index_pb]['created_by'] = $id_user;

                foreach ($data_akses['data']['def_akses_detail'] as $k_aks_d => $v_aks_d) {
                    $cb_akses_d = $this->input->post('cb_akses_d-' . $v_aks_h->id . '-' . $v_aks_d->id);
                    $is_active_d = 0;
                    if ($cb_akses_d == 'on') {
                        $is_active_d = 1;
                    }

                    $param['def_role_akses_map'][$index_pb]['id_role'] = !is_null($id) ? $id : NULL;
                    $param['def_role_akses_map'][$index_pb]['id_akses_header'] = !is_null($v_aks_h->id) ? $v_aks_h->id : NULL;
                    $param['def_role_akses_map'][$index_pb]['id_akses_detail'] = !is_null($v_aks_d->id) ? $v_aks_d->id : NULL;
                    $param['def_role_akses_map'][$index_pb]['is_active'] = $is_active_d;
                    $param['def_role_akses_map'][$index_pb]['created_by'] = $id_user;
                    $index_pb++;
                }
                $index_pb++;
            }

            //=========================================================================================================


            if ($submit_type == 'add') {
                $this->M_mst_role->submit_add($param);
            } elseif ($submit_type == 'edit') {
                $param['role']['is_open'] = 0;
                $param['role']['open_by'] = 0;
                $param['role']['updated_by'] = !is_null($id_user) ? $id_user : NULL;
                $this->M_mst_role->submit_edit($param);
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
