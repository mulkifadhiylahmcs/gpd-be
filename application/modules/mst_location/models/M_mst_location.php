<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_mst_location extends CI_Model
{

    //GET MAIN TABLE//

    var $column_order = array(
        'temp.province',
        'temp.city',
        'temp.distrinct',
        'temp.subdistrict',
        'temp.postalcode',
    );

    var $column_search = array(
        'temp.province',
        'temp.city',
        'temp.distrinct',
        'temp.subdistrict',
        'temp.postalcode',
    );

    var $order = array(
        'temp.province' => "asc",
        'temp.city' => "asc",
        'temp.distrinct' => "asc",
        'temp.subdistrict' => "asc",
        'temp.postalcode' => "asc",
    );

    public function get_datatables()
    {

        $main_query = $this->_getdt_mainquery();
        $this->db->select('temp.province as province');
        $this->db->select('temp.city as city');
        $this->db->select('temp.distrinct as distrinct');
        $this->db->select('temp.subdistrict as subdistrict');
        $this->db->select('temp.postalcode as postalcode');
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

        $this->db->select('dpc.postal_id as id');
        $this->db->select('dp.prov_name as province');
        $this->db->select('dc.city_name as city');
        $this->db->select('dd.dis_name as distrinct');
        $this->db->select('dsd.subdis_name as subdistrict');
        $this->db->select('dpc.postal_code as postalcode');
        $this->db->from('def_postalcode dpc');
        $this->db->join('def_subdistricts dsd', 'dsd.subdis_id = dpc.subdis_id', 'left');
        $this->db->join('def_districts dd', 'dd.dis_id = dsd.dis_id', 'left');
        $this->db->join('def_cities dc', 'dc.city_id = dd.city_id', 'left');
        $this->db->join('def_provinces dp', 'dp.prov_id = dc.prov_id', 'left');
        return $this->db->get_compiled_select();
    }

    private function _getdt_filtering()
    {

        if ($this->input->post('fil_province')) {
            $this->db->like('dp.prov_name', $this->input->post('fil_province'), 'both');
        }

        if ($this->input->post('fil_city')) {
            $this->db->like('dc.city_name', $this->input->post('fil_city'), 'both');
        }

        if ($this->input->post('fil_district')) {
            $this->db->like('dd.dis_name', $this->input->post('fil_district'), 'both');
        }

        if ($this->input->post('fil_subdistrict')) {
            $this->db->like('dsd.subdis_name', $this->input->post('fil_subdistrict'), 'both');
        }

        if ($this->input->post('fil_postalcode')) {
            $this->db->like('dpc.postal_code', $this->input->post('fil_postalcode'), 'both');
        }
    }

    //GET MAIN TABLE//


    public function getData_province($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        if (isset($param['prov_id'])) {
            if (!is_null($param['prov_id']) AND !empty($param['prov_id'])) {
                $this->db->where_in('dp.prov_id', $param['prov_id']);
            }
        }

        $this->db->select('dp.prov_id AS prov_id');
        $this->db->select('dp.prov_name AS prov_name');
        $this->db->from('def_provinces dp');
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

    public function getData_city($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        if (isset($param['prov_id'])) {
            if (!is_null($param['prov_id']) AND !empty($param['prov_id'])) {
                $this->db->where_in('dp.prov_id', $param['prov_id']);
            }
        }

        if (isset($param['city_id'])) {
            if (!is_null($param['city_id']) AND !empty($param['city_id'])) {
                $this->db->where_in('dc.city_id', $param['city_id']);
            }
        }

        $this->db->select('dp.prov_id AS prov_id');
        $this->db->select('dp.prov_name AS prov_name');
        $this->db->select('dc.city_id AS city_id');
        $this->db->select('dc.city_name AS city_name');
        $this->db->from('def_cities dc');
        $this->db->join('def_provinces dp', 'dp.prov_id = dc.prov_id', 'left');
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

    public function getData_district($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        if (isset($param['prov_id'])) {
            if (!is_null($param['prov_id']) AND !empty($param['prov_id'])) {
                $this->db->where_in('dp.prov_id', $param['prov_id']);
            }
        }

        if (isset($param['city_id'])) {
            if (!is_null($param['city_id']) AND !empty($param['city_id'])) {
                $this->db->where_in('dc.city_id', $param['city_id']);
            }
        }

        if (isset($param['dis_id'])) {
            if (!is_null($param['dis_id']) AND !empty($param['dis_id'])) {
                $this->db->where_in('dd.dis_id', $param['dis_id']);
            }
        }

        $this->db->select('dp.prov_id AS prov_id');
        $this->db->select('dp.prov_name AS prov_name');
        $this->db->select('dc.city_id AS city_id');
        $this->db->select('dc.city_name AS city_name');
        $this->db->select('dd.dis_id AS dis_id');
        $this->db->select('dd.dis_name AS dis_name');
        $this->db->from('def_districts dd');
        $this->db->join('def_cities dc', 'dc.city_id = dd.city_id', 'left');
        $this->db->join('def_provinces dp', 'dp.prov_id = dc.prov_id', 'left');
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

    public function getData_subdistrict($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        if (isset($param['prov_id'])) {
            if (!is_null($param['prov_id']) AND !empty($param['prov_id'])) {
                $this->db->where_in('dp.prov_id', $param['prov_id']);
            }
        }

        if (isset($param['city_id'])) {
            if (!is_null($param['city_id']) AND !empty($param['city_id'])) {
                $this->db->where_in('dc.city_id', $param['city_id']);
            }
        }

        if (isset($param['dis_id'])) {
            if (!is_null($param['dis_id']) AND !empty($param['dis_id'])) {
                $this->db->where_in('dd.dis_id', $param['dis_id']);
            }
        }

        if (isset($param['subdis_id'])) {
            if (!is_null($param['subdis_id']) AND !empty($param['subdis_id'])) {
                $this->db->where_in('dsd.subdis_id', $param['subdis_id']);
            }
        }

        $this->db->select('dp.prov_id AS prov_id');
        $this->db->select('dp.prov_name AS prov_name');
        $this->db->select('dc.city_id AS city_id');
        $this->db->select('dc.city_name AS city_name');
        $this->db->select('dd.dis_id AS dis_id');
        $this->db->select('dd.dis_name AS dis_name');
        $this->db->select('dsd.subdis_id AS subdis_id');
        $this->db->select('dsd.subdis_name AS subdis_name');
        $this->db->from('def_subdistricts dsd');
        $this->db->join('def_districts dd', 'dd.dis_id = dsd.dis_id', 'left');
        $this->db->join('def_cities dc', 'dc.city_id = dd.city_id', 'left');
        $this->db->join('def_provinces dp', 'dp.prov_id = dc.prov_id', 'left');
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

    public function getData_postalcode($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        if (isset($param['prov_id'])) {
            if (!is_null($param['prov_id']) AND !empty($param['prov_id'])) {
                $this->db->where_in('dp.prov_id', $param['prov_id']);
            }
        }

        if (isset($param['city_id'])) {
            if (!is_null($param['city_id']) AND !empty($param['city_id'])) {
                $this->db->where_in('dc.city_id', $param['city_id']);
            }
        }

        if (isset($param['dis_id'])) {
            if (!is_null($param['dis_id']) AND !empty($param['dis_id'])) {
                $this->db->where_in('dd.dis_id', $param['dis_id']);
            }
        }

        if (isset($param['subdis_id'])) {
            if (!is_null($param['subdis_id']) AND !empty($param['subdis_id'])) {
                $this->db->where_in('dsd.subdis_id', $param['subdis_id']);
            }
        }

        if (isset($param['postal_id'])) {
            if (!is_null($param['postal_id']) AND !empty($param['postal_id'])) {
                $this->db->where_in('dpc.postal_id', $param['postal_id']);
            }
        }

        if (isset($param['postal_code'])) {
            if (!is_null($param['postal_code']) AND !empty($param['postal_code'])) {
                $this->db->where_in('dpc.postal_code', $param['postal_code']);
            }
        }

        $this->db->select('dp.prov_id AS prov_id');
        $this->db->select('dp.prov_name AS prov_name');
        $this->db->select('dc.city_id AS city_id');
        $this->db->select('dc.city_name AS city_name');
        $this->db->select('dd.dis_id AS dis_id');
        $this->db->select('dd.dis_name AS dis_name');
        $this->db->select('dsd.subdis_id AS subdis_id');
        $this->db->select('dsd.subdis_name AS subdis_name');
        $this->db->select('dpc.postal_id AS postal_id');
        $this->db->select('dpc.postal_code AS postal_code');
        $this->db->from('def_postalcode dpc');
        $this->db->join('def_subdistricts dsd', 'dsd.subdis_id = dpc.subdis_id', 'left');
        $this->db->join('def_districts dd', 'dd.dis_id = dsd.dis_id', 'left');
        $this->db->join('def_cities dc', 'dc.city_id = dd.city_id', 'left');
        $this->db->join('def_provinces dp', 'dp.prov_id = dc.prov_id', 'left');
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
