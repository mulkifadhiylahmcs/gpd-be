<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Artikel_tag extends CI_Controller
{
    var $active_menu = array(
        'active' => 'menu_artikel',
        'sub_active' => 'submenu_artikel_tag',
    );

    function __construct()
    {
        parent::__construct();
        $this->load->model('M_artikel_tag', 'M_artikel_tag');

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
        $content['page_title'] = 'Tag Artikel';
        $content['content_title'] = 'Tag Artikel';
        if (!$this->check_session->check_module($this->active_menu)) {
            $content['maincontent'] = $this->load->view('template/v_forbidden', NULL, TRUE);
        } else {
            $content['maincontent'] = $this->load->view('v_index', NULL, TRUE);
            $content['maincontent_script'] = $this->load->view('v_script', NULL, TRUE);
        }

        $this->load->view('template/v_base', $content);
    }


    public function getTrash() {
        $content = array();

        //default content
        $content['headmenu'] = $this->load->view('template/headmenu', NULL, TRUE);
        $content['footer'] = $this->load->view('template/footer', NULL, TRUE);
        $content['lib_js'] = $this->load->view('template/init_js', $this->active_menu, TRUE);

        //content
        $content['page_title'] = 'Tag Artikel';
        $content['content_title'] = 'Tag Artikel';
        if (!$this->check_session->check_module($this->active_menu)) {
            $content['maincontent'] = $this->load->view('template/v_forbidden', NULL, TRUE);
        } else {
            $content['maincontent'] = $this->load->view('v_trash', NULL, TRUE);
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
        $list = $this->M_artikel_tag->get_datatables();
       
        //        echo "<pre>"; print_r($list); die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;

            $row = array();
            $row[] = $no;
            $row[] = $field->tag_text;
            $row[] = $field->tag_alias;
            $row[] = $field->is_publish == "1" ? 'YES' : 'NO';
            $row[] = $field->is_trash == "1" ? 'YES' : 'NO';

            $str = '';
            $str .= '<button style="z-index: unset;margin: 2px;" class="btn-floating btn-small orange" data-id="' . $field->id . '" onclick=getForm("view",' . $field->id . ');><i class="material-icons">search</i></button>';
            $str .= '<button style="z-index: unset;margin: 2px;" class="btn-floating btn-small green" data-id="' . $field->id . '" onclick=getForm("edit",' . $field->id . ');><i class="material-icons">edit</i></button>';
            $str .= '<button style="z-index: unset;margin: 2px;" class="btn-floating btn-small green lighten-3" data-id="' . $field->id . '" onclick=getPublish("publish",' . $field->id . ');><i class="material-icons">cloud_upload</i></button>';
            $str .= '<button style="z-index: unset;margin: 2px;" class="btn-floating btn-small pink lighten-3" data-id="' . $field->id . '" onclick=getPublish("unPublish",' . $field->id . '); ><i class="material-icons">cloud_download</i></button>';
            $str .= '<button style="z-index: unset;margin: 2px;" class="btn-floating btn-small red" data-id="' . $field->id . '" onclick=Trash(' . $field->id . '); ><i class="material-icons">close</i></button>';

            $row[] = $str;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_artikel_tag->count_all(),
            "recordsFiltered" => $this->M_artikel_tag->count_filtered(),
            "data" => $data
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function get_trash_table()
    {
        $list = $this->M_artikel_tag->get_datatablesTrash();
       
        //        echo "<pre>"; print_r($list); die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;

            $row = array();
            $check = '<label>
            <input type="checkbox" data-id="' . $field->id . '" name="chk[]">
            <span></span>
          </label>';
            $row[] = $check;
            $row[] = $no;
            $row[] = $field->tag_text;
            $row[] = $field->tag_alias;
            $row[] = $field->short_name;
            $row[] = $field->trash_at;

            $str = '';
            $str .= '<button style="z-index: unset;margin: 2px;" class="btn-floating btn-small blue" data-id="' . $field->id . '" onclick=unTrash(' . $field->id . ');><i class="material-icons">check</i></button>';
           
            $row[] = $str;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_artikel_tag->count_all(),
            "recordsFiltered" => $this->M_artikel_tag->count_filtered(),
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

            $return_array = $this->M_artikel_tag->getDataView($param);
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

            $return_array = $this->M_artikel_tag->getDataEdit($param);
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
            $tag_alias = preg_replace('/[[:space:]]+/', '-', $this->input->post('tag_alias'));
            // $tag_alias = $this->input->post('tag_alias');
            $tag_text = $this->input->post('tag_text');
           
            $is_publish = $this->input->post('is_publish');
            $submit_type = $this->input->post('submit_type');


            //=========================================================================================================

            $param['artikel'] = array();
            $param['artikel']['id'] = !is_null($id) ? $id : NULL;
            $param['artikel']['tag_alias'] = !is_null($tag_alias) ? $tag_alias : NULL;
            $param['artikel']['tag_text'] = !is_null($tag_text) ? $tag_text : NULL;
            $param['artikel']['is_open'] = !is_null($id_user) ? $id_user : NULL;
            $param['artikel']['open_by'] = !is_null($id_user) ? $id_user : NULL;
            $param['artikel']['open_at'] = $current_datetime;
            $param['artikel']['is_publish'] = !is_null($is_publish) ? $is_publish : NULL;
            
            if($is_publish == '1'){
                $param['artikel']['publish_by'] = !is_null($id_user) ? $id_user : NULL;
                $param['artikel']['publish_at'] = $current_datetime;
            } else {
                $param['artikel']['publish_by'] = '0';
                $param['artikel']['publish_at'] = '0000-00-00 00:00:00';
            }
            
            $param['artikel']['is_trash'] = '0';
            $param['artikel']['trash_by'] = !is_null($id_user) ? $id_user : NULL;
            $param['artikel']['trash_at'] = $current_datetime;
            $param['artikel']['created_by'] = !is_null($id_user) ? $id_user : NULL;
            $param['artikel']['updated_by'] = !is_null($id_user) ? $id_user : NULL;
            

            //=========================================================================================================


            if ($submit_type == 'add') {
                $this->M_artikel_tag->submit_add($param);
            } elseif ($submit_type == 'edit') {
                $is_trash = $this->input->post('is_trash');
                $param['artikel']['is_trash'] = !is_null($is_trash) ? $is_trash : NULL;
                $this->M_artikel_tag->submit_edit($param);
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

    public function postPublish()
    {
        // $return_array = array();
        try {     
            $id = $this->input->post('id');
            $is_publish = $this->input->post('mode');
            //=========================================================================================================
            $param['artikel'] = array();
            $param['artikel']['id'] = !is_null($id) ? $id : NULL;
           
            if($is_publish == 'publish'){
                $param['artikel']['is_publish'] = 1;
                $this->M_artikel_tag->simpan_publish($param);
            } elseif($is_publish == 'unPublish') {
                $param['artikel']['is_publish'] = '0';
                $this->M_artikel_tag->simpan_publish($param);
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

    public function postTrash()
    {
        // $return_array = array();
        try {     
            $id = $this->input->post('id');
      
            //=========================================================================================================
            $param['artikel'] = array();
            $param['artikel']['id'] = !is_null($id) ? $id : NULL;
           
            
          
            $param['artikel']['is_trash'] = '1';
            // throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] :'.$id.' INVALID Parameter ');
            $this->M_artikel_tag->simpan_trash($param);
           
            $return_array["res"] = 1;
            $return_array["message"] = 'success';
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function postunTrash()
    {
        // $return_array = array();
        try {     
            $id = $this->input->post('id');
      
            //=========================================================================================================
            $param['artikel'] = array();
            $param['artikel']['id'] = !is_null($id) ? $id : NULL;
           
            
          
            $param['artikel']['is_trash'] = 0;
            $this->M_artikel_tag->simpan_trash($param);
           
            $return_array["res"] = 1;
            $return_array["message"] = 'success';
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function postunTrashAll()
    {
        // $return_array = array();
        try {     
            $id = $this->input->post('id');
      
            //=========================================================================================================
            $param['artikel'] = array();
            
            foreach ($id as $row_id){
                $param['artikel']['id'] = !is_null($row_id) ? $row_id : NULL;
                $param['artikel']['is_trash'] = 0;
                $this->M_artikel_tag->simpan_trash($param);
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
