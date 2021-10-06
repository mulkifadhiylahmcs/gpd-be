<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        // if ( !$this->check_session->check() ) {
        //     redirect( base_url( 'content/login' ) );
        //     exit();
        // }

    }

    public function index()
    {
        exit();
    }
}
