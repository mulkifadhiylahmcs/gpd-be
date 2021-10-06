<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_user_akses extends CI_Model
{

    public function getData_user_akses($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        $id = isset($param['id']) ? $param['id'] : NULL;

        //=========================================================================================================


        $this->db->select('da.id AS id');
        $this->db->select('da.code AS code');
        $this->db->select('da.name AS name');
        $this->db->from('def_akses da');
        if (!is_null($id)) {
            $this->db->select('IFNULL(uamd.is_active, 0) AS is_active');
            $this->db->join("(
                SELECT * 
                FROM user_akses_map
                WHERE id_user = '$id' AND id_akses_detail = 0
            ) uamd", 'uamd.id_akses_header = da.id', 'left');
        }

        $res_akses_header = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }
        $return_array["data"]['def_akses_header'] = $res_akses_header->result();

        //=========================================================================================================

        $this->db->select('dad.id AS id');
        $this->db->select('dad.id_header AS id_header');
        $this->db->select('dad.description AS description');
        $this->db->select('dad.name AS name');
        $this->db->from('def_akses_detail dad');
        if (!is_null($id)) {
            $this->db->select('IFNULL(uamd.is_active, 0) AS is_active');
            $this->db->join("(
                SELECT * 
                FROM user_akses_map
                WHERE id_user = '$id' AND id_akses_detail > 0
            ) uamd", 'uamd.id_akses_detail = dad.id', 'left');
        }
        $res_akses_detail = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }
        $return_array["data"]['def_akses_detail'] = $res_akses_detail->result();

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
