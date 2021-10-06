<?php

class Main_lib {

    function mlib_getNumberPromocard( $date , $department_id , $user_id ) {
        $CI =& get_instance();

        //get_date_part
        $year = date( "Y" , strtotime( $date ) );
        $month = date( "m" , strtotime( $date ) );

        //get_dept_part
        $CI->db->select( 'department.department_code as department_code' );
        $CI->db->from( 'department' );
        $CI->db->where( 'department.department_id' , $department_id );
        $result_dept = $CI->db->get()->result();
        $dept_code = $result_dept[ 0 ]->department_code;

        //get_user_part
        $CI->db->select( 'user_login.fullname as fullname' );
        $CI->db->from( 'user_login' );
        $CI->db->where( 'user_login.user_id' , $user_id );
        $result_user = $CI->db->get()->result();
        $fullname = $result_user[ 0 ]->fullname;
        $name = explode( ' ' , $fullname );
        if ( count( $name ) > 1 ) {
            $name1 = strtoupper( substr( $name[ 0 ] , 0 , 2 ) );
            $name2 = strtoupper( substr( $name[ 1 ] , 0 , 2 ) );
        } else {
            $name1 = strtoupper( substr( $name[ 0 ] , 0 , 2 ) );
            $name2 = strtoupper( substr( $name[ 0 ] , 2 , 2 ) );
        }
        $user_code = $name1 . $name2;


        // seq_number_code
        $CI->db->select( 'TRIM(SUBSTRING_INDEX(IFNULL(promocard.promocard_number,"001"),"-",1)) as seq_number' );
        $CI->db->from( 'promocard' );
        $CI->db->where( 'YEAR(promocard.promocard_date)' , date( "Y" , strtotime( $date ) ) );
        $CI->db->where( 'MONTH(promocard.promocard_date)' , date( "m" , strtotime( $date ) ) );
        $CI->db->order_by( 'ABS(seq_number) DESC' );
        $CI->db->limit( 1 );
        $result_seq = $CI->db->get()->row();
        if ( isset( $result_seq->seq_number ) ) {
            if ( strlen( $result_seq->seq_number ) > 0 ) {
                $seq_number = intval( $result_seq->seq_number ) + 1;
                $seq_code = str_pad( $seq_number , 3 , '0' , STR_PAD_LEFT );
            } else {
                $seq_code = '001';
            }
        } else {
            $seq_code = '001';
        }


        $fix_number = $seq_code . '-PrmCard-' . strtoupper( $dept_code ) . '-' . strtoupper( $user_code ) . '-' . $month . '-' . $year;
        return $fix_number;
    }

    function mlib_getNumberClaim( $distributor_id , $date ) {
        $CI =& get_instance();

        $year = date( "Y" , strtotime( $date ) );
        $month = date( "m" , strtotime( $date ) );

        $res_dist_code = $CI->db->select( 'd.distributor_code AS distributor_code' )
            ->from( 'sr_distributor d' )
            ->where_in( 'd.distributor_id' , $distributor_id )
            ->get();
        $distributor_code = $res_dist_code->row()->distributor_code;

        $res_claim = $CI->db
            ->select( 'TRIM(SUBSTRING_INDEX(IFNULL(c.claim_reference_number,"0001"),"-",1)) as seq_number' )
            ->from( 'claim c' )
            ->join( 'sr_distributor d' , 'd.distributor_id = c.distributor_id' )
            ->where( 'd.distributor_code' , $distributor_code )
            ->where( 'year(c.claim_date)' , $year )
            ->where( 'month(c.claim_date)' , $month )
            ->get();

        if ( isset( $res_claim->seq_number ) ) {
            if ( strlen( $res_claim->seq_number ) > 0 ) {
                $seq_number = intval( $res_claim->seq_number ) + 1;
                $seq_code = str_pad( $seq_number , 4 , '0' , STR_PAD_LEFT );
            } else {
                $seq_code = '0001';
            }
        } else {
            $seq_code = '0001';
        }

        $fix_number = $seq_code . '-Claim-' . strtoupper( $distributor_code ) . '-' . $month . '-' . $year;
        return $fix_number;
    }

    function mlib_getNumberFoc( $distributor_id , $date ) {
        $CI =& get_instance();

        $year = date( "Y" , strtotime( $date ) );
        $month = date( "m" , strtotime( $date ) );

        $res_dist_code = $CI->db->select( 'd.distributor_code AS distributor_code' )
            ->from( 'sr_distributor d' )
            ->where_in( 'd.distributor_id' , $distributor_id )
            ->get();
        $distributor_code = $res_dist_code->row()->distributor_code;

        $res_claim = $CI->db->select( 'TRIM(SUBSTRING_INDEX(IFNULL(f.foc_number,"0001"),"-",1)) as seq_number' )
            ->from( 'foc f' )
            ->join( 'sr_distributor d' , 'd.distributor_id = f.distributor_id' )
            ->where( 'd.distributor_code' , $distributor_code )
            ->where( 'year(f.foc_date)' , $year )
            ->where( 'month(f.foc_date)' , $month )
            ->get();

        if ( isset( $res_claim->seq_number ) ) {
            if ( strlen( $res_claim->seq_number ) > 0 ) {
                $seq_number = intval( $res_claim->seq_number ) + 1;
                $seq_code = str_pad( $seq_number , 4 , '0' , STR_PAD_LEFT );
            } else {
                $seq_code = '0001';
            }
        } else {
            $seq_code = '0001';
        }

        $fix_number = $seq_code . '-FOCK-' . strtoupper( $distributor_code ) . '-' . $month . '-' . $year;
        return $fix_number;
    }

    function mlib_getNumberBudget( $department_id , $distributor_id , $date ) {
        $CI =& get_instance();

        $year = date( "Y" , strtotime( $date ) );
        $month = date( "m" , strtotime( $date ) );

        $res_dist_code = $CI->db->select( 'd.distributor_code AS distributor_code' )
            ->from( 'sr_distributor d' )
            ->where_in( 'd.distributor_id' , $distributor_id )
            ->get();
        $distributor_code = $res_dist_code->row()->distributor_code;

        $res_claim = $CI->db
            ->select( 'TRIM(SUBSTRING_INDEX(IFNULL(c.claim_reference_number,"001"),"-",1)) as seq_number' )
            ->from( 'claim c' )
            ->join( 'sr_distributor d' , 'd.distributor_id = c.distributor_id' )
            ->where( 'd.distributor_code' , $distributor_code )
            ->where( 'year(c.claim_date)' , $year )
            ->where( 'month(c.claim_date)' , $month )
            ->get();

        if ( isset( $res_claim->seq_number ) ) {
            if ( strlen( $res_claim->seq_number ) > 0 ) {
                $seq_number = intval( $res_claim->seq_number ) + 1;
                $seq_code = str_pad( $seq_number , 4 , '0' , STR_PAD_LEFT );
            } else {
                $seq_code = '0001';
            }
        } else {
            $seq_code = '0001';
        }


        $fix_number = $seq_code . '-Budget-' . strtoupper( "Brand" ) . '-' . strtoupper( "department" ) . '-' . $year;
        return $fix_number;
    }

    function mlib_getSex( $param ) {
        $x = intval( $param );
        $namaSex = array(
            'm' => 'Male' ,
            'f' => 'Female' ,
        );
        $ret = $namaSex[ $x ];

        return $ret;
    }

    function mlib_getBulanLong( $param ) {
        $x = intval( $param );
        $namaBulan = array(
            '1' => 'Januari' ,
            '2' => 'Februari' ,
            '3' => 'Maret' ,
            '4' => 'April' ,
            '5' => 'Mei' ,
            '6' => 'Juni' ,
            '7' => 'Juli' ,
            '8' => 'Agustus' ,
            '9' => 'September' ,
            '10' => 'Oktober' ,
            '11' => 'November' ,
            '12' => 'Desember'
        );
        $ret = $namaBulan[ $x ];

        return $ret;
    }

    function mlib_getBulanShort( $param ) {
        $x = intval( $param );
        $namaBulan = array(
            '1' => 'Jan' ,
            '2' => 'Feb' ,
            '3' => 'Mar' ,
            '4' => 'Apr' ,
            '5' => 'Mei' ,
            '6' => 'Jun' ,
            '7' => 'Jul' ,
            '8' => 'Agu' ,
            '9' => 'Sep' ,
            '10' => 'Okt' ,
            '11' => 'Nov' ,
            '12' => 'Des'
        );
        $ret = $namaBulan[ $x ];

        return $ret;
    }

    function mlib_getHariLong( $param ) {
        $x = intval( $param );
        $namaHari = array(
            '1' => 'Senin' ,
            '2' => 'Selasa' ,
            '3' => 'Rabu' ,
            '4' => 'Kamis' ,
            '5' => 'Jum\'at' ,
            '6' => 'Sabtu' ,
            '7' => 'Minggu'
        );
        $ret = $namaHari[ $x ];

        return $ret;
    }

    function mlib_getHariShort( $param ) {
        $x = intval( $param );
        $namaHari = array(
            '1' => 'Sen' ,
            '2' => 'Sel' ,
            '3' => 'Rab' ,
            '4' => 'Kam' ,
            '5' => 'Jum' ,
            '6' => 'Sab' ,
            '7' => 'Min'
        );
        $ret = $namaHari[ $x ];

        return $ret;
    }

    function mlib_getWeekOfMonth( $date ) {
        //Get the first day of the month.
        $firstOfMonth = date( "Y-m-01" , strtotime( $date ) );
        //Apply above formula.
        return ( intval( date( "W" , strtotime( $date ) ) ) - intval( date( "W" , strtotime( $firstOfMonth ) ) ) ) + 1;
    }

    function mlib_getRomanNum( $param ) {
        $number = intval( $param );
        $map = array(
            'M' => 1000 ,
            'CM' => 900 ,
            'D' => 500 ,
            'CD' => 400 ,
            'C' => 100 ,
            'XC' => 90 ,
            'L' => 50 ,
            'XL' => 40 ,
            'X' => 10 ,
            'IX' => 9 ,
            'V' => 5 ,
            'IV' => 4 ,
            'I' => 1
        );
        $returnValue = '';
        while ( $number > 0 ) {
            foreach ( $map as $roman => $int ) {
                if ( $number >= $int ) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    function mlib_getRandomChar( $length = 0 ) {
        $str_return = '';
        if ( $length > 0 ) {
            $seed = str_split( 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789' );

            for ( $i = 0; $i < $length; $i++ ) {
                $k = mt_rand( 0 , 61 );
                $str_return .= $seed[ $k ];
            }
        }

        return $str_return;
    }
    
}

?>
