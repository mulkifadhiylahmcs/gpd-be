<?php

class Check_session
{

    function check()
    {
        $CI = &get_instance();

        if (
            is_null($CI->session->userdata('id_user')) ||
            is_null($CI->session->userdata('first_name')) ||
            is_null($CI->session->userdata('last_name')) ||
            is_null($CI->session->userdata('nik')) ||
            is_null($CI->session->userdata('username')) ||
            is_null($CI->session->userdata('email')) ||
            is_null($CI->session->userdata('image')) ||
            is_null($CI->session->userdata('sex')) ||
            is_null($CI->session->userdata('id_position')) ||
            is_null($CI->session->userdata('id_role')) ||
            is_null($CI->session->userdata('role')) ||
            is_null($CI->session->userdata('position'))
        ) {
            return false;
        } else {
            return true;
        }
    }

    function check_module($currmenu)
    {
        $CI = &get_instance();
        $arr = $CI->session->userdata('akses_header');

        
        if (isset($currmenu['sub_active'])) {
            if (array_search($currmenu['sub_active'], array_column($arr, 'code')) !== false) {
                $aa = array_search($currmenu['sub_active'], array_column($arr, 'code'));
                if ($arr[$aa]['is_active'] == '1') {
                    return true;
                }
            }
        } else {
            if (isset($currmenu['active'])) {
                if (array_search($currmenu['active'], array_column($arr, 'code')) !== false) {
                    $aa = array_search($currmenu['active'], array_column($arr, 'code'));
                    if ($arr[$aa]['is_active'] == '1') {
                        return true;
                    }
                }
            }
        }


        return false;
    }
}
