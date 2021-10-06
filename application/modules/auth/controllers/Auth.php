<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (
            is_null($this->session->userdata('id_user')) ||
            is_null($this->session->userdata('first_name')) ||
            is_null($this->session->userdata('last_name')) ||
            is_null($this->session->userdata('nik')) ||
            is_null($this->session->userdata('username')) ||
            is_null($this->session->userdata('email')) ||
            is_null($this->session->userdata('image')) ||
            is_null($this->session->userdata('sex')) ||
            is_null($this->session->userdata('id_position')) ||
            is_null($this->session->userdata('id_role')) ||
            is_null($this->session->userdata('role')) ||
            is_null($this->session->userdata('position'))
        ) {
            $data['page_title'] = 'Login';
            $this->load->view('template/v_login', $data);
        } else {
            redirect(base_url('dashboard'));
        }
    }

    public function do_login()
    {
        $return_array = array();
        try {
            $param = array();
            $param['username'] = $this->input->post('gp_username');
            $param['password'] = $this->input->post('gp_password');

            $return_array = $this->M_auth->web_login($param);
            if ($return_array['data']) {
                $this->session->set_userdata($return_array['data']);
                if (
                    is_null($this->session->userdata('id_user')) ||
                    is_null($this->session->userdata('first_name')) ||
                    is_null($this->session->userdata('last_name')) ||
                    is_null($this->session->userdata('nik')) ||
                    is_null($this->session->userdata('username')) ||
                    is_null($this->session->userdata('email')) ||
                    is_null($this->session->userdata('image')) ||
                    is_null($this->session->userdata('sex')) ||
                    is_null($this->session->userdata('id_position')) ||
                    is_null($this->session->userdata('id_role')) ||
                    is_null($this->session->userdata('role')) ||
                    is_null($this->session->userdata('position'))
                ) {
                    $this->session->sess_destroy();
                    throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-000] : FAILED GENERATE SESSION, Please contact ITDEV');
                } else {
                    $param = array();
                    $param['user']['username'] = $this->session->userdata('username');
                    $param['user']['is_login'] = '1';
                    $param['user']['http_cookie'] = isset($_SERVER['HTTP_COOKIE']) ? $_SERVER['HTTP_COOKIE'] : NULL;
                    $param['user']['last_login_ip'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : NULL;
                    $this->M_auth->update_status_login($param);
                    unset($return_array['data']);
                }

                echo json_encode($return_array);
            } else {
                $this->do_logout();
            }
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function do_logout()
    {
        $return_array = array();
        try {
            if ($this->session->userdata('username')) {
                $param = array();
                $param['user']['username'] = $this->session->userdata('username');
                $param['user']['is_login'] = '0';
                $param['user']['http_cookie'] = NULL;

                $this->M_auth->update_status_login($param);
            }

            $this->session->sess_destroy();
            redirect(base_url('auth'));
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function get_akses_detail()
    {
        $return_array = array();
        try {
            $param = array();
            $param['akses_h_id'] = $this->input->post('akses_h_id');
            $param['id_user'] = $this->session->userdata('id_user');

            $return_array = $this->M_auth->get_akses_detail($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function get_akses_header()
    {
        $return_array = array();
        try {
            $return_array["res"] = 1;
            $return_array["message"] = 'success';
            $return_array["data"] = $this->session->userdata('akses_header');
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }
}
