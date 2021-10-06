<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_filter extends CI_Model
{

    public function getFilter_department($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        if ($param['is_active']) {
            $this->db->where_in('d.is_active', $param['is_active']);
        }

        $this->db->select('d.id AS id');
        $this->db->select('d.code AS code');
        $this->db->select('d.name AS name');
        $this->db->from('def_department d');
        $res_main = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }
        $return_array["data"] = $res_main->result();

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

    public function getFilter_division($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        if ($param['is_active']) {
            $this->db->where_in('d.is_active', $param['is_active']);
        }

        if ($param['id_department']) {
            $this->db->where_in('d.id_department', $param['id_department']);
        }

        $this->db->select('d.id AS id');
        $this->db->select('d.code AS code');
        $this->db->select('d.name AS name');
        $this->db->from('def_division d');
        $res_main = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }
        $return_array["data"] = $res_main->result();

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

    public function getFilter_role($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        if ($param['is_active']) {
            $this->db->where_in('d.is_active', $param['is_active']);
        }

        $this->db->select('d.id AS id');
        $this->db->select('d.name AS name');
        $this->db->from('def_role d');
        $res_main = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }
        $return_array["data"] = $res_main->result();

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

    public function getFilter_position($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        if ($param['is_active']) {
            $this->db->where_in('d.is_active', $param['is_active']);
        }

        if ($param['id_department']) {
            $this->db->where_in('d.id_department', $param['id_department']);
        }

        if ($param['id_division']) {
            $this->db->where_in('d.id_division', $param['id_division']);
        }

        $this->db->select('d.id AS id');
        $this->db->select('d.code AS code');
        $this->db->select('d.name AS name');
        $this->db->from('def_position d');
        $res_main = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }
        $return_array["data"] = $res_main->result();

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

    public function getFilter_provinces($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        if ($param['is_active']) {
            $this->db->where_in('d.is_active', $param['is_active']);
        }

        $this->db->select('d.prov_id AS id');
        $this->db->select('d.prov_name AS name');
        $this->db->from('def_provinces d');
        $res_main = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }
        $return_array["data"] = $res_main->result();

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

    public function getFilter_cities($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        if ($param['is_active']) {
            $this->db->where_in('d.is_active', $param['is_active']);
        }

        if ($param['prov_id']) {
            $this->db->where_in('d.prov_id', $param['prov_id']);
        }

        $this->db->select('d.city_id AS id');
        $this->db->select('d.city_name AS name');
        $this->db->from('def_cities d');
        $res_main = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }
        $return_array["data"] = $res_main->result();

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

    public function getFilter_districts($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        if ($param['is_active']) {
            $this->db->where_in('d.is_active', $param['is_active']);
        }

        if ($param['city_id']) {
            $this->db->where_in('d.city_id', $param['city_id']);
        }

        $this->db->select('d.dis_id AS id');
        $this->db->select('d.dis_name AS name');
        $this->db->from('def_districts d');
        $res_main = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }
        $return_array["data"] = $res_main->result();

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

    public function getFilter_subdistricts($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        if ($param['is_active']) {
            $this->db->where_in('d.is_active', $param['is_active']);
        }

        if ($param['dis_id']) {
            $this->db->where_in('d.dis_id', $param['dis_id']);
        }

        $this->db->select('d.subdis_id AS id');
        $this->db->select('d.subdis_name AS name');
        $this->db->from('def_subdistricts d');
        $res_main = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }
        $return_array["data"] = $res_main->result();

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

    public function getFilter_postalcode($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        if ($param['is_active']) {
            $this->db->where_in('d.is_active', $param['is_active']);
        }

        if ($param['subdis_id']) {
            $this->db->where_in('d.subdis_id', $param['subdis_id']);
        }

        if ($param['dis_id']) {
            $this->db->where_in('d.dis_id', $param['dis_id']);
        }

        if ($param['city_id']) {
            $this->db->where_in('d.city_id', $param['city_id']);
        }

        if ($param['prov_id']) {
            $this->db->where_in('d.prov_id', $param['prov_id']);
        }


        $this->db->select('d.postal_id AS id');
        $this->db->select('d.postal_code AS name');
        $this->db->from('def_postalcode d');
        $res_main = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }
        $return_array["data"] = $res_main->result();

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

    
}
