<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        log_message('info', 'Home Class start.');
    }

    public function index(){
		$this->loginCheck('/');
        $data['user'] = $this->user;
        $data['meta'] = array('title' => 'Home');
        $this->creatOutput('home_index', $data);
    }
}