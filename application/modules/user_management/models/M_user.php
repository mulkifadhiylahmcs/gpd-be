<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_user extends CI_Model
{

    //GET MAIN TABLE//

    var $column_order = array(
        'temp.id',
        'temp.nik',
        'temp.fullname',
        'temp.sex',
        'temp.username',
        'temp.position',
        'temp.email',
        'temp.phone',
        'temp.is_login',
        'temp.is_trash',
        'temp.is_active',
    );

    var $column_search = array(
        'temp.nik',
        'temp.fullname',
        'temp.sex',
        'temp.username',
        'temp.position',
        'temp.email',
        'temp.phone',
        'temp.is_login',
        'temp.is_trash',
        'temp.is_active',
    );

    var $order = array('temp.id' => 'desc');

    public function get_datatables()
    {

        $main_query = $this->_getdt_mainquery();

        $this->db->select('temp.id as id');
        $this->db->select('temp.fullname as fullname');
        $this->db->select('temp.nik as nik');
        $this->db->select('temp.username as username');
        $this->db->select('temp.sex as sex');
        $this->db->select('temp.email as email');
        $this->db->select('temp.phone as phone');
        $this->db->select('temp.position as position');
        $this->db->select('temp.is_open as is_open');
        $this->db->select('temp.open_by as open_by');
        $this->db->select('temp.is_active as is_active');
        $this->db->select('temp.is_login as is_login');
        $this->db->select('temp.is_trash as is_trash');
        $this->db->from('( ' . $main_query . ' ) temp');
        $this->_getdt_searchsort();

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered()
    {

        $main_query = $this->_getdt_mainquery();
        $this->db->select('COUNT(temp.id) as count');
        $this->db->from('( ' . $main_query . ' ) temp');
        $this->_getdt_searchsort();
        $query = $this->db->get()->row();
        return $query->count;
    }

    public function count_all()
    {

        $main_query = $this->_getdt_mainquery();
        $this->db->select('COUNT(temp.id) as count');
        $this->db->from('( ' . $main_query . ' ) temp');

        $query = $this->db->get()->row();
        return $query->count;
    }

    private function _getdt_searchsort()
    {

        $i = 0;
        foreach ($this->column_search as $item) { // looping awal
            if ($_POST['search']['value']) { // jika datatable mengirimkan pencarian dengan metode POST

                if ($i === 0) { // looping awal
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            if (isset($this->order)) {
                $order = $this->order;

                foreach ($order as $key => $val) {
                    $this->db->order_by($key, $val);
                }
            }
        }
    }

    private function _getdt_mainquery()
    {

        $this->_getdt_filtering();

        $this->db->select('u.id AS id');
        $this->db->select('CONCAT(u.first_name," ",u.last_name) AS fullname');
        $this->db->select('u.nik AS nik');
        $this->db->select('u.username AS username');
        $this->db->select('u.sex AS sex');
        $this->db->select('u.email AS email');
        $this->db->select('u.phone AS phone');
        $this->db->select('u.is_open AS is_open');
        $this->db->select('u.open_by AS open_by');
        $this->db->select('u.is_active AS is_active');
        $this->db->select('IF(u.is_login = 1, "YES" , "NO") AS is_login');
        $this->db->select('u.is_trash AS is_trash');
        $this->db->select('CONCAT(dpos.code," - ",dpos.name) AS position');
        $this->db->from('user u');
        $this->db->join('def_position dpos', 'dpos.id = u.id_position', 'left');
        return $this->db->get_compiled_select();
    }

    private function _getdt_filtering()
    {

        if ($this->input->post('fil_fullname')) {
            $this->db->group_start();
            $this->db->like('u.firstname', $this->input->post('fil_fullname'), 'both');
            $this->db->or_like('u.lastname', $this->input->post('fil_fullname'), 'both');
            $this->db->or_like('CONCAT(u.firstname," ",u.lastname', $this->input->post('fil_fullname'), 'both');
            $this->db->group_end();
        }

        if ($this->input->post('fil_username')) {
            $this->db->like('u.username', $this->input->post('fil_username'), 'both');
        }

        if ($this->input->post('fil_nik')) {
            $this->db->like('u.nik', $this->input->post('fil_nik'), 'both');
        }

        if ($this->input->post('fil_position')) {
            $this->db->where_in('u.id_position', $this->input->post('fil_position'));
        }

        if ($this->input->post('fil_sex')) {
            $this->db->where_in('u.sex', $this->input->post('fil_sex'));
        }

        if ($this->input->post('fil_is_trash')) {
            $this->db->where_in('u.is_trash', $this->input->post('fil_is_trash'));
        }

        if ($this->input->post('fil_is_active')) {
            $this->db->where_in('u.is_active', $this->input->post('fil_is_active'));
        }
    }

    //GET MAIN TABLE//

    public function getData_user($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        if (isset($param['id'])) {
            if (!is_null($param['id']) and !empty($param['id'])) {
                $this->db->where_in('u.id', $param['id']);
            }
        }
        if (isset($param['id_parent'])) {
            if (!is_null($param['id_parent']) and !empty($param['id_parent'])) {
                $this->db->where_in('u.id_parent', $param['id_parent']);
            }
        }
        if (isset($param['is_active'])) {
            if (!is_null($param['is_active']) and !empty($param['is_active'])) {
                $this->db->where_in('u.is_active', $param['is_active']);
            }
        }
        if (isset($param['is_trash'])) {
            if (!is_null($param['is_trash']) and !empty($param['is_trash'])) {
                $this->db->where_in('u.is_trash', $param['is_trash']);
            }
        }
        if (isset($param['nik'])) {
            if (!is_null($param['nik']) and !empty($param['nik'])) {
                $this->db->like('u.nik', $param['nik']);
            }
        }
        if (isset($param['fullname'])) {
            if (!is_null($param['fullname']) and !empty($param['fullname'])) {
                $this->db->group_start();
                $this->db->like('u.firstname', $param['fullname'], 'both');
                $this->db->or_like('u.lastname', $param['fullname'], 'both');
                $this->db->or_like('CONCAT(u.firstname," ",u.lastname', $param['fullname'], 'both');
                $this->db->group_end();
            }
        }
        if (isset($param['username'])) {
            if (!is_null($param['username']) and !empty($param['username'])) {
                $this->db->like('u.username', $param['username']);
            }
        }
        if (isset($param['email'])) {
            if (!is_null($param['email']) and !empty($param['email'])) {
                $this->db->like('u.email', $param['email']);
            }
        }
        if (isset($param['id_position'])) {
            if (!is_null($param['id_position']) and !empty($param['id_position'])) {
                $this->db->where_in('u.id_position', $param['id_position']);
            }
        }
        if (isset($param['sex'])) {
            if (!is_null($param['sex']) and !empty($param['sex'])) {
                $this->db->where_in('u.sex', $param['sex']);
            }
        }
        if (isset($param['id_postalcode'])) {
            if (!is_null($param['id_postalcode']) and !empty($param['id_postalcode'])) {
                $this->db->where_in('u.id_postalcode', $param['id_postalcode']);
            }
        }
        if (isset($param['id_subdistrict'])) {
            if (!is_null($param['id_subdistrict']) and !empty($param['id_subdistrict'])) {
                $this->db->where_in('u.id_subdistrict', $param['id_subdistrict']);
            }
        }
        if (isset($param['id_district'])) {
            if (!is_null($param['id_district']) and !empty($param['id_district'])) {
                $this->db->where_in('u.id_district', $param['id_district']);
            }
        }
        if (isset($param['id_city'])) {
            if (!is_null($param['id_city']) and !empty($param['id_city'])) {
                $this->db->where_in('u.id_city', $param['id_city']);
            }
        }
        if (isset($param['id_province'])) {
            if (!is_null($param['id_province']) and !empty($param['id_province'])) {
                $this->db->where_in('u.id_province', $param['id_province']);
            }
        }
        if (isset($param['is_login'])) {
            if (!is_null($param['is_login']) and !empty($param['is_login'])) {
                $this->db->where_in('u.is_login', $param['is_login']);
            }
        }
        if (isset($param['is_open'])) {
            if (!is_null($param['is_open']) and !empty($param['is_open'])) {
                $this->db->where_in('u.is_open', $param['is_open']);
            }
        }

        $this->db->select('u.id AS id');
        $this->db->select('u.id_parent AS id_parent');
        $this->db->select('u.first_name AS first_name');
        $this->db->select('u.last_name AS last_name');
        $this->db->select('u.short_name AS short_name');
        $this->db->select('u.nik AS nik');
        $this->db->select('u.username AS username');
        $this->db->select('u.email AS email');
        $this->db->select('u.image AS image');
        $this->db->select('u.id_position AS id_position');
        $this->db->select('u.id_role AS id_role');
        $this->db->select('u.sex AS sex');
        $this->db->select('u.address AS address');
        $this->db->select('u.id_postalcode AS id_postalcode');
        $this->db->select('u.id_subdistrict AS id_subdistrict');
        $this->db->select('u.id_district AS id_district');
        $this->db->select('u.id_city AS id_city');
        $this->db->select('u.id_province AS id_province');
        $this->db->select('u.phone AS phone');
        $this->db->select('u.birth_date AS birth_date');
        $this->db->select('u.birth_place AS birth_place');
        $this->db->select('u.is_login AS is_login');
        $this->db->select('u.http_cookie AS http_cookie');
        $this->db->select('u.last_login_ip AS last_login_ip');
        $this->db->select('u.is_active AS is_active');
        $this->db->select('u.active_by AS active_by');
        $this->db->select('u.active_at AS active_at');
        $this->db->select('u.is_open AS is_open');
        $this->db->select('u.open_by AS open_by');
        $this->db->select('u.open_at AS open_at');
        $this->db->select('u.is_trash AS is_trash');
        $this->db->select('u.trash_by AS trash_by');
        $this->db->select('u.trash_at AS trash_at');
        $this->db->select('u.created_by AS created_by');
        $this->db->select('u.created_at AS created_at');
        $this->db->select('u.updated_by AS updated_by');
        $this->db->select('u.updated_at AS updated_at');
        $this->db->from('user u');
        $res_main = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }
        $return_array["data"] = $res_main->result();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Failed GET Data ');
        } else {
            $this->db->trans_commit();
            $return_array["res"] = 1;
            $return_array["message"] = 'success';
            return $return_array;
        }
    }

    public function submit_add($param = array())
    {

        $this->db->db_debug = FALSE;
        $this->db->trans_begin();


        if (!isset($param['user'])) {
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : INVALID Parameter ');
        }


        $this->db->insert('user', $param['user']);
        $id = $this->db->insert_id();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }

        if (sizeof($param['user_akses_map']) > 0) {
            foreach ($param['user_akses_map'] as $k => $item) {
                $param['user_akses_map'][$k]['id_user'] = $id;
            }

            $this->db->where('id_user', $id);
            $this->db->delete('user_akses_map');
            $db_error = $this->db->error();
            if (!empty($db_error) && $db_error['code'] != '0') {
                $this->db->trans_rollback();
                throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
            }

            $this->db->insert_batch('user_akses_map', $param['user_akses_map']);
            $db_error = $this->db->error();
            if (!empty($db_error) && $db_error['code'] != '0') {
                $this->db->trans_rollback();
                throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Failed SUBMIT Data ');
        } else {
            $this->db->trans_commit();
        }
    }

    public function submit_edit($param = array())
    {

        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        if (!isset($param['user'])) {
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : INVALID Parameter ');
        }

        $this->db->where('id', $param['user']['id']);
        $this->db->update('user', $param['user']);
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }


        if (isset($param['user_akses_map'])) {
            $this->db->where('id_user', $param['user']['id']);
            $this->db->delete('user_akses_map');
            $db_error = $this->db->error();
            if (!empty($db_error) && $db_error['code'] != '0') {
                $this->db->trans_rollback();
                throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
            }

            if (sizeof($param['user_akses_map']) > 0) {
                $this->db->insert_batch('user_akses_map', $param['user_akses_map']);
                $db_error = $this->db->error();
                if (!empty($db_error) && $db_error['code'] != '0') {
                    $this->db->trans_rollback();
                    throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
                }
            }
        }


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Failed SUBMIT Data ');
        } else {
            $this->db->trans_commit();
        }
    }

    public function getDataView($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        $id = isset($param['id']) ? $param['id'] : NULL;

        $this->db->select('u.id AS id');

        $this->db->select('u.id_parent AS id_parent');
        $this->db->select('IF(up.id IS NULL, "UNSET",  CONCAT(up.first_name,\' \',up.last_name,\'-\',up.nik)) AS parent');

        $this->db->select('u.first_name AS first_name');
        $this->db->select('u.last_name AS last_name');
        $this->db->select('u.short_name AS short_name');
        $this->db->select('u.nik AS nik');
        $this->db->select('u.username AS username');
        $this->db->select('u.email AS email');
        $this->db->select('u.image AS image');

        $this->db->select('u.id_position AS id_position');
        $this->db->select('IF(dpos.id IS NULL, "UNSET", CONCAT(dpos.code,\'-\', dpos.name)) AS position');
        $this->db->select('IF(dp.id IS NULL, "UNSET", CONCAT(dp.code,\'-\', dp.name)) AS department');
        $this->db->select('IF(dv.id IS NULL, "UNSET", CONCAT(dv.code,\'-\', dv.name)) AS division');

        $this->db->select('u.id_role AS id_role');
        $this->db->select('IF(dr.id IS NULL, "UNSET", dr.name) AS role');

        $this->db->select('u.sex AS sex');
        $this->db->select('u.address AS address');

        $this->db->select('u.id_postalcode AS id_postalcode');
        $this->db->select('IF(dpostal.postal_id IS NULL, "UNSET", dpostal.postal_code) AS postalcode');

        $this->db->select('u.id_subdistrict AS id_subdistrict');
        $this->db->select('IF(dsubdis.subdis_id IS NULL, "UNSET", dsubdis.subdis_name) AS subdistrict');

        $this->db->select('u.id_district AS id_district');
        $this->db->select('IF(ddis.dis_id IS NULL, "UNSET", ddis.dis_name) AS district');

        $this->db->select('u.id_city AS id_city');
        $this->db->select('IF(dcit.city_id IS NULL, "UNSET", dcit.city_name) AS city');

        $this->db->select('u.id_province AS id_province');
        $this->db->select('IF(dprov.prov_id IS NULL, "UNSET", dprov.prov_name) AS province');

        $this->db->select('u.phone AS phone');
        $this->db->select('u.birth_date AS birth_date');
        $this->db->select('u.birth_place AS birth_place');

        $this->db->select('u.is_login AS is_login');
        $this->db->select('u.http_cookie AS http_cookie');
        $this->db->select('u.last_login_ip AS last_login_ip');

        $this->db->select('IF(u.is_active = 1, "ACTIVE" , "NOT ACTIVE") AS is_active');
        $this->db->select('u.active_by AS active_by');
        $this->db->select('u.active_at AS active_at');

        $this->db->select('IF(u.is_login = 1, "YES" , "NO") AS is_login');
        $this->db->select('u.open_by AS open_by');
        $this->db->select('u.open_at AS open_at');

        $this->db->select('IF(u.is_trash = 1, "TRASHED" , "UNTRASHED") AS is_trash');
        $this->db->select('u.trash_by AS trash_by');
        $this->db->select('u.trash_at AS trash_at');

        $this->db->select('u.created_by AS created_by');
        $this->db->select('u.created_at AS created_at');
        $this->db->select('u.updated_by AS updated_by');
        $this->db->select('u.updated_at AS updated_at');

        $this->db->from('user u');

        $this->db->join('user up', 'up.id = u.id_parent', 'left');

        $this->db->join('def_role dr', 'dr.id = u.id_role', 'left');
        $this->db->join('def_position dpos', 'dpos.id = u.id_position', 'left');
        $this->db->join('def_department dp', 'dp.id = dpos.id_department', 'left');
        $this->db->join('def_division dv', 'dv.id = dpos.id_division', 'left');

        $this->db->join('def_postalcode dpostal', 'dpostal.postal_id = u.id_postalcode', 'left');
        $this->db->join('def_subdistricts dsubdis', 'dsubdis.subdis_id = u.id_subdistrict', 'left');
        $this->db->join('def_districts ddis', 'ddis.dis_id = u.id_district', 'left');
        $this->db->join('def_cities dcit', 'dcit.city_id = u.id_city', 'left');
        $this->db->join('def_provinces dprov', 'dprov.prov_id = u.id_province', 'left');

        $this->db->where('u.id', $id);
        $res_main = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }
        $return_array["data"]['user'] = $res_main->row();

        //=========================================================================================================


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Failed GET Data ');
        } else {
            $this->db->trans_commit();
            $return_array["res"] = 1;
            $return_array["message"] = 'success';
            return $return_array;
        }
    }

    public function getDataEdit($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        $id = isset($param['id']) ? $param['id'] : NULL;

        $this->db->select('u.id AS id');
        $this->db->select('u.id_parent AS id_parent');
        $this->db->select('u.first_name AS first_name');
        $this->db->select('u.last_name AS last_name');
        $this->db->select('u.short_name AS short_name');
        $this->db->select('u.nik AS nik');
        $this->db->select('u.username AS username');
        $this->db->select('u.email AS email');
        $this->db->select('u.image AS image');
        $this->db->select('u.id_position AS id_position');
        $this->db->select('u.id_role AS id_role');
        $this->db->select('u.sex AS sex');
        $this->db->select('u.address AS address');
        $this->db->select('u.id_postalcode AS id_postalcode');
        $this->db->select('u.id_subdistrict AS id_subdistrict');
        $this->db->select('u.id_district AS id_district');
        $this->db->select('u.id_city AS id_city');
        $this->db->select('u.id_province AS id_province');
        $this->db->select('u.phone AS phone');
        $this->db->select('u.birth_date AS birth_date');
        $this->db->select('u.birth_place AS birth_place');
        $this->db->select('IF(u.is_login = 1, "YES" , "NO") AS is_login');
        $this->db->select('u.http_cookie AS http_cookie');
        $this->db->select('u.last_login_ip AS last_login_ip');
        $this->db->select('u.is_active AS is_active');
        $this->db->select('u.active_by AS active_by');
        $this->db->select('u.active_at AS active_at');
        $this->db->select('u.is_open AS is_open');
        $this->db->select('u.open_by AS open_by');
        $this->db->select('u.open_at AS open_at');
        $this->db->select('u.is_trash AS is_trash');
        $this->db->select('u.trash_by AS trash_by');
        $this->db->select('u.trash_at AS trash_at');
        $this->db->select('u.created_by AS created_by');
        $this->db->select('u.created_at AS created_at');
        $this->db->select('u.updated_by AS updated_by');
        $this->db->select('u.updated_at AS updated_at');
        $this->db->from('user u');
        $this->db->where('u.id', $id);
        $res_main = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }
        $return_array["data"]['user'] = $res_main->row();

        //=========================================================================================================


        if ($this->db->trans_status() === FALSE) {
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Failed GET Data ');
        } else {
            $this->db->trans_commit();
            $return_array["res"] = 1;
            $return_array["message"] = 'success';
            return $return_array;
        }
    }
}
