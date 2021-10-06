<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_mst_position extends CI_Model
{

    //GET MAIN TABLE//

    var $column_order = array(
        'temp.id',
        'temp.code',
        'temp.name',
        'temp.parent',
        'temp.department',
        'temp.division',
        'temp.is_active',
    );

    var $column_search = array(
        'temp.code',
        'temp.name',
        'temp.parent',
        'temp.department',
        'temp.division',
        'temp.is_active',
    );

    var $order = array('temp.id' => 'desc');

    public function get_datatables()
    {

        $main_query = $this->_getdt_mainquery();
        $this->db->select('temp.id AS id');
        $this->db->select('temp.code AS code');
        $this->db->select('temp.name AS name');
        $this->db->select('temp.parent AS parent');
        $this->db->select('temp.is_active AS is_active');
        $this->db->select('temp.department AS department');
        $this->db->select('temp.division AS division');
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

        $this->db->select('p.id AS id');
        $this->db->select('p.code AS code');
        $this->db->select('p.name AS name');
        $this->db->select('IF(p.is_active = 1, "ACTIVE" , "NOT ACTIVE") AS is_active');
        $this->db->select('IF(dp.id IS NULL, "UNSET", CONCAT(dp.code,\'-\', dp.name)) AS department');
        $this->db->select('IF(dv.id IS NULL, "UNSET", CONCAT(dv.code,\'-\', dv.name)) AS division');
        $this->db->select('IF(p2.id IS NULL, "UNSET", CONCAT(p2.code,\'-\', p2.name)) AS parent');
        $this->db->from('def_position p');
        $this->db->join('def_department dp', 'dp.id = p.id_department', 'left');
        $this->db->join('def_division dv', 'dv.id = p.id_division', 'left');
        $this->db->join('def_position p2', 'p2.id = p.id_parent', 'left');
        return $this->db->get_compiled_select();
    }

    private function _getdt_filtering()
    {

        if ($this->input->post('fil_code')) {
            $this->db->like('p.code', $this->input->post('fil_code'), 'both');
        }

        if ($this->input->post('fil_name')) {
            $this->db->like('p.name', $this->input->post('fil_name'), 'both');
        }

        if ($this->input->post('fil_department')) {
            $this->db->where_in('dp.id', $this->input->post('fil_department'));
        }

        if ($this->input->post('fil_division')) {
            $this->db->where_in('dv.id', $this->input->post('fil_division'));
        }

        if ($this->input->post('fil_position_parent')) {
            $this->db->where_in('p.id_parent', $this->input->post('fil_position_parent'));
        }

        if ($this->input->post('fil_is_active')) {
            $this->db->where_in('p.is_active', $this->input->post('fil_is_active'));
        }
    }

    //GET MAIN TABLE//


    public function getDataView($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        $id = isset($param['id']) ? $param['id'] : NULL;

        $this->db->select('p.id AS id');
        $this->db->select('p.code AS code');
        $this->db->select('p.name AS name');
        $this->db->select('p.description AS description');
        $this->db->select('IF(p.is_active = 1, "ACTIVE", "NOT ACTIVE") AS is_active');
        $this->db->select('IF(dp.id IS NULL, "UNSET", CONCAT(dp.code,\'-\', dp.name)) AS department');
        $this->db->select('IF(dv.id IS NULL, "UNSET", CONCAT(dv.code,\'-\', dv.name)) AS division');
        $this->db->select('IF(p2.id IS NULL, "UNSET", CONCAT(p2.code,\'-\', p2.name)) AS parent');
        $this->db->from('def_position p');
        $this->db->join('def_department dp', 'dp.id = p.id_department', 'left');
        $this->db->join('def_division dv', 'dv.id = p.id_division', 'left');
        $this->db->join('def_position p2', 'p2.id = p.id_parent', 'left');
        $this->db->where('p.id', $id);
        $res_main = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }
        $return_array["data"]['position'] = $res_main->row();

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

        $this->db->select('p.id AS id');
        $this->db->select('p.id_parent AS id_parent');
        $this->db->select('p.code AS code');
        $this->db->select('p.name AS name');
        $this->db->select('p.description AS description');
        $this->db->select('p.is_active AS is_active');
        $this->db->select('p.level AS level');
        $this->db->select('p.has_department AS has_department');
        $this->db->select('p.id_department AS id_department');
        $this->db->select('p.has_division AS has_division');
        $this->db->select('p.id_division AS id_division');
        $this->db->from('def_position p');
        $this->db->where('p.id', $id);
        $res_main = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }
        $return_array["data"]['position'] = $res_main->row();

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


        if (!isset($param['position'])) {
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : INVALID Parameter ');
        }


        $this->db->insert('def_position', $param['position']);
        $id = $this->db->insert_id();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
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

        if (!isset($param['position'])) {
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : INVALID Parameter ');
        }

        $this->db->where('id', $param['position']['id']);
        $this->db->update('def_position', $param['position']);
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Failed SUBMIT Data ');
        } else {
            $this->db->trans_commit();
        }
    }

    public function getData_position($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();


        if (isset($param['id'])) {
            if (!is_null($param['id']) AND !empty($param['id'])) {
                $this->db->where_in('p.id', $param['id']);
            }
        }
        if (isset($param['code'])) {
            if (!is_null($param['code']) AND !empty($param['code'])) {
                $this->db->where_in('p.code', $param['code']);
            }
        }
        if (isset($param['id_department'])) {
            if (!is_null($param['id_department']) AND !empty($param['id_department'])) {
                $this->db->where_in('p.id_department', $param['id_department']);
            }
        }
        if (isset($param['id_division'])) {
            if (!is_null($param['id_division']) AND !empty($param['id_division'])) {
                $this->db->where_in('p.id_division', $param['id_division']);
            }
        }
        if (isset($param['id_parent'])) {
            if (!is_null($param['id_parent']) AND !empty($param['id_parent'])) {
                $this->db->where_in('p.id_parent', $param['id_parent']);
            }
        }
        if (isset($param['is_active'])) {
            if (!is_null($param['is_active']) AND !empty($param['is_active'])) {
                $this->db->where_in('p.is_active', $param['is_active']);
            }
        }

        $this->db->select('p.id AS id');
        $this->db->select('p.id_parent AS id_parent');
        $this->db->select('p.code AS code');
        $this->db->select('p.name AS name');
        $this->db->select('p.description AS description');
        $this->db->select('p.is_active AS is_active');
        $this->db->select('p.level AS level');
        $this->db->select('p.has_department AS has_department');
        $this->db->select('p.id_department AS id_department');
        $this->db->select('p.has_division AS has_division');
        $this->db->select('p.id_division AS id_division');

        $this->db->select('IF(dp.id IS NULL, "UNSET", CONCAT(dp.code,\'-\', dp.name)) AS department');
        $this->db->select('IF(dv.id IS NULL, "UNSET", CONCAT(dv.code,\'-\', dv.name)) AS division');
        $this->db->select('IF(p2.id IS NULL, "UNSET", CONCAT(p2.code,\'-\', p2.name)) AS parent');
        
        $this->db->from('def_position p');
        $this->db->join('def_department dp', 'dp.id = p.id_department', 'left');
        $this->db->join('def_division dv', 'dv.id = p.id_division', 'left');
        $this->db->join('def_position p2', 'p2.id = p.id_parent', 'left');
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
}
