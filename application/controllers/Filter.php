<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Filter extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function getFilter_department()
    {
        $return_array = array();
        try {
            $param = array();
            $param['is_active'] = 1;

            $return_array = $this->M_filter->getFilter_department($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getFilter_division()
    {
        $return_array = array();
        try {
            $param = array();
            $param['is_active'] = 1;
            $param['id_department'] = !is_null($this->input->post('id_department')) ? $this->input->post('id_department') : FALSE;

            $return_array = $this->M_filter->getFilter_division($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getFilter_role()
    {
        $return_array = array();
        try {
            $param = array();
            $param['is_active'] = 1;

            $return_array = $this->M_filter->getFilter_role($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getFilter_position()
    {
        $return_array = array();
        try {
            $param = array();
            $param['is_active'] = 1;
            $param['id_department'] = !is_null($this->input->post('id_department')) ? $this->input->post('id_department') : FALSE;
            $param['id_division'] = !is_null($this->input->post('id_division')) ? $this->input->post('id_division') : FALSE;

            $return_array = $this->M_filter->getFilter_position($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getFilter_provinces()
    {
        $return_array = array();
        try {
            $param = array();
            $param['is_active'] = 1;

            $return_array = $this->M_filter->getFilter_provinces($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getFilter_cities()
    {
        $return_array = array();
        try {
            $param = array();
            $param['is_active'] = 1;
            $param['prov_id'] = !is_null($this->input->post('prov_id')) ? $this->input->post('prov_id') : FALSE;

            $return_array = $this->M_filter->getFilter_cities($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getFilter_districts()
    {
        $return_array = array();
        try {
            $param = array();
            $param['is_active'] = 1;
            $param['city_id'] = !is_null($this->input->post('city_id')) ? $this->input->post('city_id') : FALSE;

            $return_array = $this->M_filter->getFilter_districts($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getFilter_subdistricts()
    {
        $return_array = array();
        try {
            $param = array();
            $param['is_active'] = 1;
            $param['dis_id'] = !is_null($this->input->post('dis_id')) ? $this->input->post('dis_id') : FALSE;

            $return_array = $this->M_filter->getFilter_subdistricts($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }

    public function getFilter_postalcode()
    {
        $return_array = array();
        try {
            $param = array();
            $param['is_active'] = 1;
            $param['subdis_id'] = !is_null($this->input->post('subdis_id')) ? $this->input->post('subdis_id') : FALSE;
            $param['dis_id'] = !is_null($this->input->post('dis_id')) ? $this->input->post('dis_id') : FALSE;
            $param['city_id'] = !is_null($this->input->post('city_id')) ? $this->input->post('city_id') : FALSE;
            $param['prov_id'] = !is_null($this->input->post('prov_id')) ? $this->input->post('prov_id') : FALSE;

            $return_array = $this->M_filter->getFilter_postalcode($param);
            echo json_encode($return_array);
        } catch (Exception $e) {
            $return_array["res"] = 99;
            $return_array["message"] = $e->getMessage();
            echo json_encode($return_array);
        }
    }
}
