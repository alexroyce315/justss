<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller{
    
    function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Madmin');
        log_message('info', 'Admin Class start.');
    }

    function index(){
        $this->adminCheck();
        $data['meta']               = array('title' => 'Home');
        $data['traffic'] = array(
            'u' => sprintf("%.4f", $this->Madmin->traffic('month', 'u')/1024/1024/1024),
            'd' => sprintf("%.4f", $this->Madmin->traffic('month', 'd')/1024/1024/1024)
        );
        $this->load->model('Mnode');
        $data['nodes']  = $this->Mnode->select('list');
        $this->load->model('Muser');
        $data['user'] = array(
            'active'    => $this->Muser->count('active'),
            'user'      => $this->Muser->count('list'),
            '300'       => $this->Muser->count('online', 300),
            '3600'      => $this->Muser->count('online', 3600),
            '86400'     => $this->Muser->count('online', 86400)
        );
        $this->creatOutput('admin/index', $data, '0');
    }

    #region node
    public function node($num = 0){
        $this->adminCheck();
        $num = is_numeric($num) ? $num : 0;
        $this->load->model('Mnode');

        $this->load->library('pagination');
        $config['pagination_fix']   = FALSE;
        $config['first_url']        = site_url('admin/node');
        $config['base_url']         = '/admin/node';
        $config['total_rows']       = $this->Mnode->count();
        $config                     = array_merge($config, $this->pagerArray($config['pagination_fix']));
        $config['num_links']        = 5;
        $this->pagination->initialize($config);
        $data['list']               = $this->Mnode->select('list', $config['per_page'], $num);
        $data['num']                = $num;
        $data['meta']               = array('title' => 'Node List &nbsp; ' . anchor('admin/nodeEdit', 'Add', 'data-pjax="#pjax-container"'));

        $this->createTable(array('Id', 'Name', 'IP/Server', 'Status', 'Method', 'Manage'));
        $this->creatOutput('block/list_content', $data, '0');
    }
	
	public function nodeEdit($itemId = NULL){
		$this->adminCheck();
        $nodeName = $this->input->post('name');
		$this->load->model('Mnode');
		if($nodeName && $this->input->is_ajax_request()){
            if(NULL == $itemId){
                if($this->Mnode->insert() > 0){
                    echo json_encode(array('result' => TRUE,'msg'=>'Add A Node Successfully.'));
                } else{
                    echo json_encode(array('result' => FALSE,'msg'=>'Fail, Try Again.'));
                }
            } else{
                if($this->Mnode->update($itemId) > 0){
                    echo json_encode(array('result' => TRUE,'msg'=>'Edit The Node Successfully.'));
                } else{
                    echo json_encode(array('result' => FALSE,'msg'=>'Fail, Try Again.'));
                }
            }
		} else{
			$data['node']   = NULL == $itemId ? array() : $this->Mnode->select('single', $itemId);
			$data['meta']   = array('title' => 'Node Edit');
            $data['methods']= $this->config->item('methods');
			$data = array_filter($data);
            $this->load->helper('form');
            $this->creatOutput('admin/node_edit', $data, '0');
		}
    }

    public function nodeDelete(){
        $this->adminCheck();
		// todo
    }

    public function nodeRepeat($itemId = NULL){
		$this->adminCheck();
        $itemId = NULL == $itemId ? $this->inpit->get_post('itemId') : $itemId;
		$this->load->model('Mnode');
		echo $this->Mnode->count('repeat', $itemId);
    }
    #end region

    #region user
    public function user($num = 0){
        $this->adminCheck();
        $num = is_numeric($num) ? $num : 0;
        $this->load->model('Muser');

        $this->load->library('pagination');
        $config['pagination_fix']   = FALSE;
        $config['first_url']        = site_url('admin/user');
        $config['base_url']         = '/admin/user';
        $config['total_rows']       = $this->Muser->count('list');
        $config                     = array_merge($config, $this->pagerArray($config['pagination_fix']));
        $config['num_links']        = 5;
        $this->pagination->initialize($config);
        $data['list']               = $this->Muser->select('list', $config['per_page'], $num);
        $data['num']                = $num;
        $data['meta']               = array('title' => 'User List &nbsp; ' . anchor('admin/userEdit', 'Add', 'data-pjax="#pjax-container"'));

        $this->createTable(array('Email', 'Upload (GB)', 'Download (GB)', 'Traffic (GB)', 'Status', 'AddTime', 'Source', 'Manage'));
        $this->creatOutput('block/list_content', $data, '0');
    }

    public function userEdit($itemId = NULL){
        $this->adminCheck();
		$userEmail = $this->input->post('email');
		$this->load->model('Muser');
		if($userEmail && $this->input->is_ajax_request()){
            //if('1' == $this->input->post('enable')){
            //    $this->load->model('Mmail');
            //    $data = $this->Mmail->template('pass', $userEmail);
            //    $result = $this->Mmail->send($userEmail, $data['title'], $this->load->view('block/mail_template', $data, TRUE));
            //    $this->Mmail->insert($userEmail, $data, $result);
            //}
			if(NULL == $itemId){
                if($this->Muser->insert() > 0){
                    echo json_encode(array('result' => TRUE,'msg'=>'Add A User Successfully.'));
                } else{
                    echo json_encode(array('result' => FALSE,'msg'=>'Fail, Try Again.'));
                }
            } else{
                if($this->Muser->update($itemId) > 0){
                    echo json_encode(array('result' => TRUE,'msg'=>'Edit The User Successfully.'));
                } else{
                    echo json_encode(array('result' => FALSE,'msg'=>'Fail, Try Again.'));
                }
            }
		} else{
			$data['user']   = NULL == $itemId ? array() : $this->Muser->select('single', $itemId);
			$data['meta']   = array('title' => 'User Edit');
			$data = array_filter($data);
            $this->load->helper('form');
            $this->creatOutput('admin/user_edit', $data, '0');
		}
    }

    public function userDelete(){
        if($this->adminCheck() && $this->input->is_ajax_request()){
			$itemId = NULL == $itemId ? $this->inpit->get_post('itemId') : $itemId;
			$this->load->model('Muser');
			echo $this->Muser->delete($itemId);
		} else{
			echo 'W.T.F';
		}
    }

    public function userRepeat($itemId = NULL){
		if($this->adminCheck() && $this->input->is_ajax_request()){
			$itemId = NULL == $itemId ? $this->inpit->get_post('itemId') : $itemId;
			$this->load->model('Muser');
			echo $this->Muser->count('repeat', $itemId);
		} else{
			echo 'W.T.F';
		}
    }
    #end region

    public function login(){
        $this->adminCheck('index');
        if($userEmail = $this->input->post('email')){
            $pass = sha1($this->input->post('password'));
            $user = $this->Madmin->select('login', $userEmail, $pass);
            $this->load->model('Mlog');
            if(isset($user['uid'])){
                $this->Mlog->insert($userEmail, $pass, 1, 0);
                $array = array(
                    'adminId'       => $user['uid'],
                    'adminEmail'    => $userEmail
                );
                $this->session->set_userdata($array);
                echo json_encode(array('result' => TRUE,'msg'=>site_url('admin')));
            } else{
                $this->Mlog->insert($userEmail, $pass, 0, 0);
                echo json_encode(array('result' => FALSE,'msg'=>'Login Fail.'));
                exit;
            }
        } else{
            $this->load->helper('form');
            $data['meta']               = array('title' => 'Login');
            $this->creatOutput('admin/login', $data, '0');
        }
    }

    public function profile(){
        $this->adminCheck();
		$userEmail = $this->input->post('email');
        if($userEmail && $this->input->is_ajax_request()){
            $this->Madmin->update($this->user['adminId']);
        } else{
            $data['user'] = $this->Madmin->select('single', $this->user['adminId']);
            $data['meta']               = array('title' => 'Profile');
            $this->load->helper('form');
            $this->creatOutput('admin/profile', $data, '0');
        }
    }

    public function logout(){
        $this->session->sess_destroy();
        redirect(site_url('admin/login'));
    }

    public function userLog($num = 0){
        $this->adminCheck();
        $num = is_numeric($num) ? $num : 0;
        $this->load->model('Mlog');

        $this->load->library('pagination');
        $config['pagination_fix']   = FALSE;
        $config['first_url']        = site_url('admin/userLog');
        $config['base_url']         = '/admin/userLog';
        $config['total_rows']       = $this->Mlog->count('1');
        $config                     = array_merge($config, $this->pagerArray($config['pagination_fix']));
        $this->pagination->initialize($config);
        $data['list']               = $this->Mlog->select('1', $config['per_page'], $num);
        $data['num']                = $num;
        $data['meta']               = array('title' => 'User Logs');

        $this->createTable(array('Email', 'IP', 'User-Agent', 'Result', 'Time'));
        $this->creatOutput('block/list_content', $data, '0');
    }

    public function adminLog($num = 0){
        $this->adminCheck();
        $num = is_numeric($num) ? $num : 0;
        $this->load->model('Mlog');

        $this->load->library('pagination');
        $config['pagination_fix']   = FALSE;
        $config['first_url']        = site_url('admin/adminLog');
        $config['base_url']         = '/admin/adminLog';
        $config['total_rows']       = $this->Mlog->count('0');
        $config                     = array_merge($config, $this->pagerArray($config['pagination_fix']));
        $this->pagination->initialize($config);
        $data['list']               = $this->Mlog->select('0', $config['per_page'], $num);
        $data['num']                = $num;
        $data['meta']               = array('title' => 'Admin Logs');

        $this->createTable(array('Email', 'IP', 'User-Agent', 'Result', 'Time'));
        $this->creatOutput('block/list_content', $data, '0');
    }

    public function mailLog($num = 0){
        $this->adminCheck();
        $num = is_numeric($num) ? $num : 0;
        $this->load->model('Mmail');

        $this->load->library('pagination');
        $config['pagination_fix']   = FALSE;
        $config['first_url']        = site_url('admin/mailLog');
        $config['base_url']         = '/admin/mailLog';
        $config['total_rows']       = $this->Mmail->count();
        $config                     = array_merge($config, $this->pagerArray($config['pagination_fix']));
        $this->pagination->initialize($config);
        $data['list']               = $this->Mmail->select($config['per_page'], $num);
        $data['num']                = $num;
        $data['meta']               = array('title' => 'Mail Logs');

        $this->createTable(array('Email', 'IP', 'User-Agent', 'Result', 'Content', 'Time'));
        $this->creatOutput('block/list_content', $data, '0');
    }
    
}
