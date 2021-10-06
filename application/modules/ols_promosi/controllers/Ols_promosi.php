<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ols_promosi extends CI_Controller
{
    var $active_menu = array(
        'active' => 'menu_ols',
        'sub_active' => 'submenu_ols_promosi',
    );

    function __construct()
    {
        parent::__construct();

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
        $content['page_title'] = 'Promosi';
        $content['content_title'] = 'Promosi';
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

    // Codes Here

    // ================= GET DATATABLES END ====================//

    // ================= GET DATA PURPOSES START ====================//

    // Codes Here

    // ================= GET DATA PURPOSES END ====================//

    // ================= CUD PURPOSES START ====================//

    // Codes Here

    // ================= CUD PURPOSES END ====================//
}
