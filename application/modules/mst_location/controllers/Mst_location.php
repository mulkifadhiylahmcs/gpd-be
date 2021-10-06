<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mst_location extends CI_Controller
{
    var $active_menu = array(
        'active' => 'menu_master',
        'sub_active' => 'submenu_master_location',
    );

    function __construct()
    {
        parent::__construct();
        $this->load->model('M_mst_location', 'M_mst_location');

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
        $content['page_title'] = 'Master Location';
        $content['content_title'] = 'Master Location';
        if (!$this->check_session->check_module($this->active_menu)) {
            $content['maincontent'] = $this->load->view('template/v_forbidden', NULL, TRUE);
        } else {
            $content['maincontent'] = $this->load->view('v_index', NULL, TRUE);
            $content['maincontent_script'] = $this->load->view('v_script', NULL, TRUE);
        }

        $this->load->view('template/v_base', $content);
    }

    // ================= CONTENT PURPOSES ====================//

    // ================= GET DATATABLES ====================//

    public function get_main_table()
    {
        $list = $this->M_mst_location->get_datatables();
        //        echo "<pre>"; print_r($list); die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;

            $row = array();
            $row[] = $field->province;
            $row[] = $field->city;
            $row[] = $field->distrinct;
            $row[] = $field->subdistrict;
            $row[] = $field->postalcode;


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_mst_location->count_all(),
            "recordsFiltered" => $this->M_mst_location->count_filtered(),
            "data" => $data
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    // ================= GET DATATABLES ====================//

    // ================= GET DATA PURPOSES ====================//



    // ================= GET DATA PURPOSES ====================//

    // ================= CUD PURPOSES START ====================//



    // ================= CUD PURPOSES END ====================//
}
