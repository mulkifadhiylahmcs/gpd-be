<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_auth extends CI_Model
{

    public function web_login($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        $username = isset($param['username']) ? $param['username'] : NULL;
        $password = isset($param['password']) ? $param['password'] : NULL;

        if ($username != NULL) {
            $this->db->where('u.username', $username);
        }

        $this->db->select('u.password as password');
        $this->db->from('user u');
        $result = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }

        if ($result->num_rows() > 0) {
            $hash = $result->row();
            $verify = password_verify($password, $hash->password);
            if (!$verify) {
                $this->db->trans_rollback();
                throw new Exception("Username or Password is INVALID");
            }
        } else {
            $this->db->trans_rollback();
            throw new Exception("Username or Password is INVALID");
        }



        //====================================================================================


        if ($username != NULL) {
            $this->db->where('u.username', $username);
        }

        $this->db->select('u.id as id_user');
        $this->db->select('u.first_name as first_name');
        $this->db->select('u.last_name as last_name');
        $this->db->select('u.username as username');
        $this->db->select('u.nik as nik');
        $this->db->select('u.email as email');
        $this->db->select('u.sex as sex');
        $this->db->select('u.image as image');
        $this->db->select('u.id_position as id_position');
        $this->db->select('u.id_role as id_role');
        $this->db->select('u.is_login as is_login');
        $this->db->select('u.is_active as is_active');
        $this->db->select('u.is_trash as is_trash');
        $this->db->select('IFNULL(u.http_cookie,"0") as http_cookie');
        $this->db->select('IFNULL(u.last_login_ip,"0") as last_login_ip');
        $this->db->select('ur.name as role');
        $this->db->select('up.name as position');
        $this->db->from('user u');
        $this->db->join('def_position up', 'up.id = u.id_position', 'left');
        $this->db->join('def_role ur', 'ur.id = u.id_role', 'left');
        $result = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }

        if ($result->num_rows() > 0) {

            $obj = $result->row();

            $http_cookie = isset($_SERVER['HTTP_COOKIE']) ? $_SERVER['HTTP_COOKIE'] : false;
            $last_login_ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
            $http_cookie_db = ($obj->http_cookie != '0') ? $obj->http_cookie : false;
            $last_login_ip_db = ($obj->last_login_ip != '0') ? $obj->last_login_ip : false;


            if ($obj->is_trash == '1') {
                $this->db->trans_rollback();
                throw new Exception("Your Account ($username) is DELETE by Administrator.");
            } elseif ($obj->is_active == '0') {
                $this->db->trans_rollback();
                throw new Exception("Your Account ($username) is NOT ACTIVE. Please contact Administrator to ACTIVATE your account.");
            } 
            // elseif ($obj->is_login == '1') {
            //     if ($last_login_ip_db !== false && $last_login_ip_db !== $last_login_ip) {
            //         $this->db->trans_rollback();
            //         throw new Exception("Your Account ($username) is LOGON in other device($last_login_ip_db). Please contact Administrator for this case.");
            //     }
            // }


            $return_array["data"]['id_user'] = !is_null($obj->id_user) ? $obj->id_user : '0';
            $return_array["data"]['first_name'] = !is_null($obj->first_name) ? $obj->first_name : 'Unknown';
            $return_array["data"]['last_name'] = !is_null($obj->last_name) ? $obj->last_name : 'Unknown';
            $return_array["data"]['nik'] = !is_null($obj->nik) ? $obj->nik : 'Unknown';
            $return_array["data"]['username'] = !is_null($obj->username) ? $obj->username : 'Unknown';
            $return_array["data"]['email'] = !is_null($obj->email) ? $obj->email : 'Unknown';
            $return_array["data"]['image'] = !is_null($obj->image) ? $obj->image : '0';
            $return_array["data"]['sex'] = !is_null($obj->sex) ? $obj->sex : 'Unknown';
            $return_array["data"]['id_position'] = !is_null($obj->id_position) ? $obj->id_position : '0';
            $return_array["data"]['id_role'] = !is_null($obj->id_role) ? $obj->id_role : '0';
            $return_array["data"]['position'] = !is_null($obj->position) ? $obj->position : 'Unknown';
            $return_array["data"]['role'] = !is_null($obj->role) ? $obj->role : 'Unknown';


            //====================================================================================



            $this->db->distinct();
            $this->db->select('da.id as akses_h_id');
            $this->db->select('da.code as akses_h_code');
            $this->db->select('IFNULL(uam.is_active, "0") as is_active');
            $this->db->from('def_akses da');
            $this->db->join('(
                SELECT DISTINCT
                id as id,
                id_user as id_user,
                id_akses_header as id_akses_header,
                is_active as is_active
                FROM user_akses_map 
                WHERE 
                id_akses_detail = 0 AND
                id_user = ' . $obj->id_user . '
                ) uam', 'uam.id_akses_header = da.id', 'left');
            $result_akses_header = $this->db->get();
            $db_error = $this->db->error();
            if (!empty($db_error) && $db_error['code'] != '0') {
                $this->db->trans_rollback();
                throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
            }
            if ($result_akses_header->num_rows() > 0) {
                foreach ($result_akses_header->result_array() as $value) {
                    $return_array["data"]['akses_header'][] = array(
                        'id' => $value['akses_h_id'],
                        'code' => $value['akses_h_code'],
                        'is_active' => $value['is_active']
                    );
                }
            }
        } else {
            $this->db->trans_rollback();
            throw new Exception("Username or Password is INVALID");
        }

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


    public function get_akses_detail($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        $akses_h_id = isset($param['akses_h_id']) ? $param['akses_h_id'] : NULL;
        $id_user = isset($param['id_user']) ? $param['id_user'] : NULL;

        if (is_null($akses_h_id) || is_null($id_user)) {
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-000] : SESSION is NULL or INVALID PARAMETER');
        }


        $this->db->distinct();
        $this->db->select('dad.id as id');
        $this->db->select('dad.id_header as id_header');
        $this->db->select('dad.element as element');
        $this->db->select('dad.selector as selector');
        $this->db->select('dad.selector_by as selector_by');
        $this->db->select('dad.action_type as action_type');
        $this->db->select('uam.is_active as is_active');
        $this->db->from('user_akses_map uam');
        $this->db->join('def_akses_detail dad', 'dad.id = uam.id_akses_detail');
        $this->db->where('uam.id_user', $id_user);
        $this->db->where('dad.id_header', $akses_h_id);
        $result_akses_detail = $this->db->get();
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }


        if ($result_akses_detail->num_rows() > 0) {
            foreach ($result_akses_detail->result_array() as $value_detail) {
                $return_array['data'][] = array(
                    'id' => $value_detail['id'],
                    'id_header' => $value_detail['id_header'],
                    'element' => $value_detail['element'],
                    'selector' => $value_detail['selector'],
                    'selector_by' => $value_detail['selector_by'],
                    'action_type' => $value_detail['action_type'],
                    'is_active' => $value_detail['is_active']
                );
            }
        }

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


    public function update_status_login($param = array())
    {
        $return_array = array();
        $this->db->db_debug = FALSE;
        $this->db->trans_begin();

        $this->db->where('username', $param['user']['username']);
        $this->db->update('user', $param['user']);
        $db_error = $this->db->error();
        if (!empty($db_error) && $db_error['code'] != '0') {
            $this->db->trans_rollback();
            throw new Exception(__DIR__ . '--' . __METHOD__ . ' [' . __LINE__ . '] : Error Code [99-' . $db_error['code'] . '] : ' . $db_error['message']);
        }


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
