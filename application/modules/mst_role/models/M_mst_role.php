<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_mst_role extends CI_Model
{

    //GET MAIN TABLE//

    var $column_order = array(
        'temp.id',
        'temp.name',
        'temp.description',
        'temp.is_active',
    );

    var $column_search = array(
        'temp.name',
        'temp.description',
    );

    var $order = array('temp.id' => 'desc');

    public function get_datatables()
    {

        $main_query = $this->_getdt_mainquery();
        $this->db->select('temp.id as id');
        $this->db->select('temp.name as name');
        $this->db->select('temp.description as description');
        $this->db->select('temp.is_active as is_active');
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
        $this->db->select('d.name AS name');
        $this->db->select('d.description AS description');
        $this->db->select('IF(d.is_active = 1, "ACTIVE" , "NOT ACTIVE") AS is_active');
        $this->db->from('def_role d');
        return $this->db->get_compiled_select();
    }

    private function _getdt_filtering()
    {

        if ($this->input->post('fil_name')) {
            $this->db->like('d.name', $this->input->post('fil_name'), 'both');
        }

        if ($this->input->post('fil_description')) {
            $this->db->like('d.description', $this->input->post('fil_description'), 'both');
        }

        if ($this->input->post('fil_is_active')) {
            $this->db->where_in('d.is_active', $this->input->post('fil_is_active'));
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
        $this->db->select('d.name AS name');
        $this->db->select('d.description AS description');
        $this->db->select('IF(d.is_active = 1, "ACTIVE", "NOT ACTIVE") AS is_active');
        $this->db->from('def_role d');
        $this->db->where('d.id', $id);
        $res_main = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }
        $return_array["data"]['role'] = $res_main->row();

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
        $this->db->select('d.name AS name');
        $this->db->select('d.description AS description');
        $this->db->select('d.is_active AS is_active');
        $this->db->from('def_role d');
        $this->db->where('d.id', $id);
        $res_main = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }
        $return_array["data"]['role'] = $res_main->row();

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

    public function submit_add($param = array())
    {

        $this->db->db_debug = FALSE;
        $this->db->trans_begin();


        if (!isset($param['role'])) {
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : INVALID Parameter ');
        }


        $this->db->insert('def_role', $param['role']);
        $id = $this->db->insert_id();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }

        if ( sizeof( $param[ 'def_role_akses_map' ] ) > 0 ) {
            foreach ( $param[ 'def_role_akses_map' ] as $k => $item ) {
                $param[ 'def_role_akses_map' ][ $k ][ 'id_role' ] = $id;
            }

            $this->db->where( 'id_role' , $id );
            $this->db->delete( 'def_role_akses_map' );
            $db_error = $this->db->error();
            if ( !empty( $db_error ) && $db_error[ 'code' ] != '0' ) {
                $this->db->trans_rollback();
                throw new Exception( __DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error[ 'code' ] . '] : ' . $db_error[ 'message' ] );
            }

            $this->db->insert_batch( 'def_role_akses_map' , $param[ 'def_role_akses_map' ] );
            $db_error = $this->db->error();
            if ( !empty( $db_error ) && $db_error[ 'code' ] != '0' ) {
                $this->db->trans_rollback();
                throw new Exception( __DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error[ 'code' ] . '] : ' . $db_error[ 'message' ] );
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

        if (!isset($param['role'])) {
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : INVALID Parameter ');
        }

        $this->db->where('id', $param['role']['id']);
        $this->db->update('def_role', $param['role']);
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }


        if ( isset( $param[ 'def_role_akses_map' ] ) ) {
            $this->db->where( 'id_role' , $param['role']['id'] );
            $this->db->delete( 'def_role_akses_map' );
            $db_error = $this->db->error();
            if ( !empty( $db_error ) && $db_error[ 'code' ] != '0' ) {
                $this->db->trans_rollback();
                throw new Exception( __DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error[ 'code' ] . '] : ' . $db_error[ 'message' ] );
            }

            if ( sizeof( $param[ 'def_role_akses_map' ] ) > 0 ) {
                $this->db->insert_batch( 'def_role_akses_map' , $param[ 'def_role_akses_map' ] );
                $db_error = $this->db->error();
                if ( !empty( $db_error ) && $db_error[ 'code' ] != '0' ) {
                    $this->db->trans_rollback();
                    throw new Exception( __DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error[ 'code' ] . '] : ' . $db_error[ 'message' ] );
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

    public function getData_role($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();


        if (isset($param['id'])) {
            if (!is_null($param['id']) and !empty($param['id'])) {
                $this->db->where_in('ur.id', $param['id']);
            }
        }
        if (isset($param['is_active'])) {
            if (!is_null($param['is_active']) and !empty($param['is_active'])) {
                $this->db->where_in('ur.is_active', $param['is_active']);
            }
        }

        $this->db->select('ur.id AS id');
        $this->db->select('ur.name AS name');
        $this->db->select('ur.is_active AS is_active');
        $this->db->from('def_role ur');
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
