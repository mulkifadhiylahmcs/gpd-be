<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_mst_division extends CI_Model
{

    //GET MAIN TABLE//

    var $column_order = array(
        'temp.id',
        'temp.code',
        'temp.name',
        'temp.description',
        'temp.department',
        'temp.is_active',
    );

    var $column_search = array(
        'temp.code',
        'temp.name',
        'temp.description',
        'temp.department',
        'temp.is_active',
    );

    var $order = array('temp.id' => 'desc');

    public function get_datatables()
    {

        $main_query = $this->_getdt_mainquery();
        $this->db->select('temp.id AS id');
        $this->db->select('temp.code AS code');
        $this->db->select('temp.name AS name');
        $this->db->select('temp.description AS description');
        $this->db->select('temp.is_active AS is_active');
        $this->db->select('temp.department AS department');
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

        $this->db->select('d.id AS id');
        $this->db->select('d.code AS code');
        $this->db->select('d.name AS name');
        $this->db->select('d.description AS description');
        $this->db->select('IF(d.is_active = 1, "ACTIVE" , "NOT ACTIVE") AS is_active');
        $this->db->select('IF(dp.id IS NULL, "UNSET", CONCAT(dp.code,\'-\', dp.name)) AS department');
        $this->db->from('def_division d');
        $this->db->join( 'def_department dp' , 'dp.id = d.id_department' , 'left' );
        return $this->db->get_compiled_select();
    }

    private function _getdt_filtering()
    {

        if ($this->input->post('fil_code')) {
            $this->db->like('d.code', $this->input->post('fil_code'), 'both');
        }

        if ($this->input->post('fil_name')) {
            $this->db->like('d.name', $this->input->post('fil_name'), 'both');
        }

        if ($this->input->post('fil_description')) {
            $this->db->like('d.description', $this->input->post('fil_description'), 'both');
        }

        if ($this->input->post('fil_is_active')) {
            $this->db->where_in('d.is_active', $this->input->post('fil_is_active'));
        }

        if ($this->input->post('fil_department')) {
            $this->db->where_in('d.id_department', $this->input->post('fil_department'));
        }
    }

    //GET MAIN TABLE//


    public function getDataView($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        $id = isset($param['id']) ? $param['id'] : NULL;

        $this->db->select('d.id AS id');
        $this->db->select('d.code AS code');
        $this->db->select('d.name AS name');
        $this->db->select('d.description AS description');
        $this->db->select('IF(d.is_active = 1, "ACTIVE", "NOT ACTIVE") AS is_active');
        $this->db->select('IF(dp.id IS NULL, "UNSET", CONCAT(dp.code,\'-\', dp.name)) AS department');
        $this->db->from('def_division d');
        $this->db->join( 'def_department dp' , 'dp.id = d.id_department' , 'left' );
        $this->db->where('d.id', $id);
        $res_main = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }
        $return_array["data"]['division'] = $res_main->row();

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

        $this->db->select('d.id AS id');
        $this->db->select('d.code AS code');
        $this->db->select('d.name AS name');
        $this->db->select('d.description AS description');
        $this->db->select('d.is_active AS is_active');
        $this->db->select('d.id_department AS id_department');
        $this->db->from('def_division d');
        $this->db->where('d.id', $id);
        $res_main = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }
        $return_array["data"]['division'] = $res_main->row();

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


        if (!isset($param['division'])) {
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : INVALID Parameter ');
        }


        $this->db->insert('def_division', $param['division']);
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

        if (!isset($param['division'])) {
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : INVALID Parameter ');
        }

        $this->db->where('id', $param['division']['id']);
        $this->db->update('def_division', $param['division']);
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

    public function getData_division($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();


        if (isset($param['id'])) {
            if (!is_null($param['id']) AND !empty($param['id'])) {
                $this->db->where_in('d.id', $param['id']);
            }
        }
        if (isset($param['code'])) {
            if (!is_null($param['code']) AND !empty($param['code'])) {
                $this->db->where_in('d.code', $param['code']);
            }
        }
        if (isset($param['is_active'])) {
            if (!is_null($param['is_active']) AND !empty($param['is_active'])) {
                $this->db->where_in('d.is_active', $param['is_active']);
            }
        }
        if (isset($param['id_department'])) {
            if (!is_null($param['id_department']) AND !empty($param['id_department'])) {
                $this->db->where_in('d.id_department', $param['id_department']);
            }
        }

        $this->db->select('d.id AS id');
        $this->db->select('d.code AS code');
        $this->db->select('d.name AS name');
        $this->db->select('d.description AS description');
        $this->db->select('d.is_active AS is_active');
        $this->db->select('d.id_department AS id_department');
        $this->db->from('def_division d');
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
