<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_management extends CI_Controller
{
    var $active_menu = array(
        'active' => 'menu_user',
        'sub_active' => 'submenu_user_management',
    );

    function __construct()
    {
        parent::__construct();
        $this->load->model('mst_division/M_mst_division', 'M_mst_division');
        $this->load->model('mst_department/M_mst_department', 'M_mmst_department');
        $this->load->model('mst_position/M_mst_position', 'M_mst_position');
        $this->load->model('mst_location/M_mst_location', 'M_mst_location');
        $this->load->model('mst_role/M_mst_role', 'M_mst_role');
        $this->load->model('mst_role/M_mst_role_akses', 'M_mst_role_akses');

        $this->load->model('user_management/M_user_akses', 'M_user_akses');
        $this->load->model('M_user', 'M_user');

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
        $content['page_title'] = 'User Management';
        $content['content_title'] = 'User Management';
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

        $now = new DateTime(NULL, new DateTimeZone('Asia/Jakarta'));
        $current_datetime = $now->format('Y-m-d H:i:s');
        $id_user = $this->session->userdata('id_user');

        $return_array = array();
        $return_array["res"] = 1;
        $return_array["message"] = 'success';
        try {
            switch ($param['mode']) {
                case 'add':
                    $return_array['content'] = $this->load->view('v_add', $param, TRUE);
                    break;
                case 'edit':
                    $param_check_open = array();
                    $param_check_open['id'] = $this->input->post('id');
                    $data_check_open = $this->M_user->getData_user($param_check_open)['data'][0];
                    if ($data_check_open->is_open == 1 && $data_check_open->open_by != $id_user) {
                        throw new Exception('This data is being opened by another user');
                    }

                    $param_update_open = array();
                    $param_update_open['user']['id'] = $this->input->post('id');
                    $param_update_open['user']['is_open'] = 1;
                    $param_update_open['user']['open_at'] = $current_datetime;
                    $param_update_open['user']['open_by'] = $id_user;
                    $this->M_user->submit_edit($param_update_open);

                    $return_array['content'] = $this->load->view('v_edit', $param, TRUE);
                    break;
                case 'view':
                    $return_array['content'] = $this->load->view('v_view', $param, TRUE);
                    break;
                case 'psw_reset':
                    $return_array['content'] = $this->load->view('v_psw_reset', $param, TRUE);
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
        $list = $this->M_user->get_datatables();
        //        echo "<pre>"; print_r($list); die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;

            $cb_checked_is_active = $field->is_active == '1' ? 'checked' : '';
            $cb_checked_is_trash = $field->is_trash == '1' ? 'checked' : '';

            $row = array();
            $row[] = $no;
            $row[] = $field->nik;
            $row[] = $field->fullname;
            $row[] = $field->sex;
            $row[] = $field->username;
            $row[] = $field->position;
            $row[] = $field->email;
            $row[] = $field->phone;
            $row[] = $field->is_login;
            $row[] = '<div class="switch"><label><input ' . $cb_checked_is_trash . ' class="is_trash_checkbox" type="checkbox" name="cb_is_trash_' . $field->id . '" data-id="' . $field->id . '" data-username="' . $field->username . '" data-nik="' . $field->nik . '"><span class="lever"></span></label></div>';
            $row[] = '<div class="switch"><label><input ' . $cb_checked_is_active . ' class="is_active_checkbox" type="checkbox" name="cb_is_active_' . $field->id . '" data-id="' . $field->id . '" data-username="' . $field->username . '" data-nik="' . $field->nik . '"><span class="lever"></span></label></div>';


            $str = '';
            $str .= '<button style="z-index: unset;margin: 2px;" class="btn-floating btn-small orange" data-id="' . $field->id . '" onclick=getForm("view",' . $field->id . ');><i class="material-icons">search</i></button>';
            $str .= '<button style="z-index: unset;margin: 2px;" class="btn-floating btn-small green" data-id="' . $field->id . '" onclick=getForm("edit",' . $field->id . ');><i class="material-icons">edit</i></button>';
            $str .= '<button style="z-index: unset;margin: 2px;" class="btn-floating btn-small lime darken-3" data-id="' . $field->id . '" onclick=getForm("psw_reset",' . $field->id . ');><i class="material-icons">vpn_key</i></button>';
            $str .= $field->is_open == '1' && $this->session->userdata('id_user') != $field->open_by ? '<button style="z-index: unset;margin: 2px;" class="btn-floating btn-small is_open_btn deep-orange darken-1" data-id="' . $field->id . '"><i class="material-icons">lock_open</i></button>' : '';

            $row[] = $str;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_user->count_all(),
            "recordsFiltered" => $this->M_user->count_filtered(),
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

            $return_array = $this->M_user->getDataView($param);
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

            $return_array = $this->M_user->getDataEdit($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getDataResetPsw()
    {
        $return_array = array();
        try {
            $param = array();
            $param['id'] = $this->input->post('id');

            $return_array = $this->M_user->getDataView($param);
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

    public function getData_user_akses()
    {
        $return_array = array();
        try {
            $param = array();
            $param['id'] = $this->input->post('id');

            $return_array = $this->M_user_akses->getData_user_akses($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getDataSelect_parent_user()
    {
        $return_array = array();
        try {
            $param = array();
            $param['is_active'] = 1;
            $param['is_trash'] = 0;

            $return_array = $this->M_user->getData_user($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getDataSelect_position()
    {
        $return_array = array();
        try {
            $param = array();
            $param['is_active'] = 1;

            $return_array = $this->M_mst_position->getData_position($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getDataSelect_role()
    {
        $return_array = array();
        try {
            $param = array();
            $param['is_active'] = 1;

            $return_array = $this->M_mst_role->getData_role($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getDataSelect_postalcode()
    {
        $return_array = array();
        try {
            $param = array();
            $param['prov_id'] = $this->input->post('prov_id');
            $param['city_id'] = $this->input->post('city_id');
            $param['dis_id'] = $this->input->post('dis_id');
            $param['subdis_id'] = $this->input->post('subdis_id');

            $return_array = $this->M_mst_location->getData_postalcode($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getDataSelect_subdistrict()
    {
        $return_array = array();
        try {
            $param = array();
            $param['prov_id'] = $this->input->post('prov_id');
            $param['city_id'] = $this->input->post('city_id');
            $param['dis_id'] = $this->input->post('dis_id');

            $return_array = $this->M_mst_location->getData_subdistrict($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getDataSelect_district()
    {
        $return_array = array();
        try {
            $param = array();
            $param['prov_id'] = $this->input->post('prov_id');
            $param['city_id'] = $this->input->post('city_id');

            $return_array = $this->M_mst_location->getData_district($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getDataSelect_city()
    {
        $return_array = array();
        try {
            $param = array();
            $param['prov_id'] = $this->input->post('prov_id');

            $return_array = $this->M_mst_location->getData_city($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getDataSelect_province()
    {
        $return_array = array();
        try {
            $param = array();

            $return_array = $this->M_mst_location->getData_province($param);
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
            $id_parent = $this->input->post('id_parent');
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            $short_name = $this->input->post('short_name');
            $nik = $this->input->post('nik');
            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $password = password_hash($username . $current_year, PASSWORD_BCRYPT);
            $image = $this->input->post('image');
            $id_position = $this->input->post('id_position');
            $id_role = $this->input->post('id_role');
            $sex = $this->input->post('sex');
            $address = $this->input->post('address');
            $id_postalcode = $this->input->post('id_postalcode');
            $id_subdistrict = $this->input->post('id_subdistrict');
            $id_district = $this->input->post('id_district');
            $id_city = $this->input->post('id_city');
            $id_province = $this->input->post('id_province');
            $phone = $this->input->post('phone');
            $birth_date = $this->input->post('birth_date');
            $birth_place = $this->input->post('birth_place');

            $is_active = $this->input->post('is_active');
            $is_trash = $this->input->post('is_trash');

            $submit_type = $this->input->post('submit_type');

            $param_akses = array();
            $param_akses['id'] = $this->input->post('id');
            $data_akses = $this->M_user_akses->getData_user_akses($param_akses);

            if ($submit_type != 'add') {
                $param_user = array();
                $param_user['id'] = $this->input->post('id');
                $data_user = $this->M_user->getData_user($param_user)['data'][0];
            }


            //=========================================================================================================


            $param['user']['id'] = !is_null($id) ? $id : NULL;
            $param['user']['id_parent'] = !is_null($id_parent) ? $id_parent : NULL;
            $param['user']['first_name'] = !is_null($first_name) ? $first_name : NULL;
            $param['user']['last_name'] = !is_null($last_name) ? $last_name : NULL;
            $param['user']['short_name'] = !is_null($short_name) ? $short_name : NULL;
            $param['user']['nik'] = !is_null($nik) ? $nik : NULL;
            $param['user']['username'] = !is_null($username) ? $username : NULL;
            $param['user']['email'] = !is_null($email) ? $email : NULL;

            if ($submit_type == 'add') {
                $param['user']['password'] = !is_null($password) ? $password : NULL;
            }

            $param['user']['image'] = !is_null($image) ? $image : NULL;
            $param['user']['id_position'] = !is_null($id_position) ? $id_position : NULL;
            $param['user']['id_role'] = !is_null($id_role) ? $id_role : NULL;
            $param['user']['sex'] = !is_null($sex) ? $sex : NULL;
            $param['user']['address'] = !is_null($address) ? $address : NULL;
            $param['user']['id_postalcode'] = !is_null($id_postalcode) ? $id_postalcode : NULL;
            $param['user']['id_subdistrict'] = !is_null($id_subdistrict) ? $id_subdistrict : NULL;
            $param['user']['id_district'] = !is_null($id_district) ? $id_district : NULL;
            $param['user']['id_city'] = !is_null($id_city) ? $id_city : NULL;
            $param['user']['id_province'] = !is_null($id_province) ? $id_province : NULL;
            $param['user']['phone'] = !is_null($phone) ? $phone : NULL;
            $param['user']['birth_date'] = !is_null($birth_date) ? $birth_date : NULL;
            $param['user']['birth_place'] = !is_null($birth_place) ? $birth_place : NULL;

            $param['user']['is_open'] = 0;

            $param['user']['is_active'] = !is_null($is_active) ? $is_active : 1;
            $param['user']['active_by'] = !is_null($id_user) ? $id_user : NULL;
            $param['user']['active_at'] = !is_null($current_datetime) ? $current_datetime : NULL;

            if ($submit_type != 'add') {
                if ($data_user->is_active != $is_active) {
                    $param['user']['is_active'] = !is_null($is_active) ? $is_active : NULL;
                    $param['user']['active_by'] = !is_null($id_user) ? $id_user : NULL;
                    $param['user']['active_at'] = !is_null($current_datetime) ? $current_datetime : NULL;
                }

                if ($data_user->is_trash != $is_trash) {
                    $param['user']['is_trash'] = !is_null($is_trash) ? $is_trash : NULL;
                    $param['user']['trash_by'] = !is_null($id_user) ? $id_user : NULL;
                    $param['user']['trash_at'] = !is_null($current_datetime) ? $current_datetime : NULL;
                }
            }

            if ($submit_type == 'add') {
                $param['user']['created_by'] = !is_null($id_user) ? $id_user : NULL;
            }

            $param['user']['updated_by'] = !is_null($id_user) ? $id_user : NULL;

            //=========================================================================================================

            $param['user_akses_map'] = array();
            $index_pb = 0;
            foreach ($data_akses['data']['def_akses_header'] as $k_aks_h => $v_aks_h) {
                $cb_akses_h = $this->input->post('cb_akses_h-' . $v_aks_h->id);
                $is_active_h = 0;
                if ($cb_akses_h == 'on') {
                    $is_active_h = 1;
                }

                $param['user_akses_map'][$index_pb]['id_user'] = !is_null($id) ? $id : NULL;
                $param['user_akses_map'][$index_pb]['id_akses_header'] = !is_null($v_aks_h->id) ? $v_aks_h->id : NULL;
                $param['user_akses_map'][$index_pb]['id_akses_detail'] = 0;
                $param['user_akses_map'][$index_pb]['is_active'] = $is_active_h;
                $param['user_akses_map'][$index_pb]['created_by'] = $id_user;

                foreach ($data_akses['data']['def_akses_detail'] as $k_aks_d => $v_aks_d) {
                    $cb_akses_d = $this->input->post('cb_akses_d-' . $v_aks_h->id . '-' . $v_aks_d->id);
                    $is_active_d = 0;
                    if ($cb_akses_d == 'on') {
                        $is_active_d = 1;
                    }

                    $param['user_akses_map'][$index_pb]['id_user'] = !is_null($id) ? $id : NULL;
                    $param['user_akses_map'][$index_pb]['id_akses_header'] = !is_null($v_aks_h->id) ? $v_aks_h->id : NULL;
                    $param['user_akses_map'][$index_pb]['id_akses_detail'] = !is_null($v_aks_d->id) ? $v_aks_d->id : NULL;
                    $param['user_akses_map'][$index_pb]['is_active'] = $is_active_d;
                    $param['user_akses_map'][$index_pb]['created_by'] = $id_user;
                    $index_pb++;
                }
                $index_pb++;
            }

            //=========================================================================================================

            // echo "<pre>";
            // print_r($param);
            // die;

            if ($submit_type == 'add') {
                $this->M_user->submit_add($param);
            } elseif ($submit_type == 'edit') {
                $this->M_user->submit_edit($param);
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

    public function submit_ResetPsw()
    {
        $return_array = array();
        try {

            $param = array();


            $now = new DateTime(NULL, new DateTimeZone('Asia/Jakarta'));
            $current_datetime = $now->format('Y-m-d H:i:s');
            $id_user = $this->session->userdata('id_user');

            $id = $this->input->post('id');
            $password = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
            $submit_type = $this->input->post('submit_type');


            //=========================================================================================================


            $param['user']['id'] = !is_null($id) ? $id : NULL;
            $param['user']['password'] = !is_null($password) ? $password : NULL;
            $param['user']['updated_by'] = !is_null($id_user) ? $id_user : NULL;

            //=========================================================================================================


            $this->M_user->submit_edit($param);

            $return_array["res"] = 1;
            $return_array["message"] = 'success';
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function set_is_trash()
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
            $is_trash = $this->input->post('value');

            //=========================================================================================================

            $param['user']['id'] = !is_null($id) ? $id : NULL;
            $param['user']['is_trash'] = !is_null($is_trash) ? $is_trash : NULL;
            $param['user']['trash_by'] = !is_null($id_user) ? $id_user : NULL;
            $param['user']['trash_at'] = !is_null($current_datetime) ? $current_datetime : NULL;
            $param['user']['updated_by'] = !is_null($id_user) ? $id_user : NULL;

            //=========================================================================================================


            $this->M_user->submit_edit($param);

            $return_array["res"] = 1;
            $return_array["message"] = 'success';
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function set_is_active()
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
            $is_active = $this->input->post('value');



            //=========================================================================================================

            $param['user']['id'] = !is_null($id) ? $id : NULL;
            $param['user']['is_active'] = !is_null($is_active) ? $is_active : NULL;
            $param['user']['active_by'] = !is_null($id_user) ? $id_user : NULL;
            $param['user']['active_at'] = !is_null($current_datetime) ? $current_datetime : NULL;
            $param['user']['updated_by'] = !is_null($id_user) ? $id_user : NULL;

            //=========================================================================================================


            $this->M_user->submit_edit($param);

            $return_array["res"] = 1;
            $return_array["message"] = 'success';
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function set_is_open()
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
            $is_open = $this->input->post('value');



            //=========================================================================================================

            $param['user']['id'] = !is_null($id) ? $id : NULL;
            $param['user']['is_open'] = !is_null($is_open) ? $is_open : NULL;

            //=========================================================================================================


            $this->M_user->submit_edit($param);

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
