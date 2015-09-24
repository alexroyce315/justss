<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User
 * 
 * @package justss
 * @author 幽蓝冰魄
 * @copyright 2015
 * @version $Id$
 * @access public
 */
class User extends MY_Controller{

    /**
     * User::__construct()
     * 构造方法
     * @return void
     */
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Muser');
        log_message('info', 'User Class start.');
    }

    /**
     * User::index()
     * 用户首页
     * @return void
     */
    public function index(){
        $this->loginCheck();
        $data['meta']       = array('title' => 'Account');
        $data['user']       = $this->Muser->select('single', $this->user['userId']);
        $data['user']['u']  = sprintf("%.4f", $data['user']['u']/1024/1024/1024);
        $data['user']['d']  = sprintf("%.4f", $data['user']['d']/1024/1024/1024);
        $data['user']['transfer_enable']  = sprintf("%.4f", $data['user']['transfer_enable']/1024/1024/1024);
        $this->load->model('Mnode');
        $data['nodes']      = $this->Mnode->select('user', $this->user['userId']);
        $this->creatOutput('user/index', $data);
    }

    /**
     * User::node()
     * 查看节点
     * @param string $itemId 节点名称/编号
     * @return void
     */
    public function node($itemId = NULL){
        $this->loginCheck();
        if(NULL != $itemId){
            $this->load->model('Mnode');
            $data['node'] = $this->Mnode->select('single', $itemId);
            if(!empty($data['node'])){
                $data['user']                   = $this->Muser->select('single', $this->user['userId']);
                $data['node']['node_port']      = $data['user']['port'];
                $data['node']['node_password']  = $data['user']['passwd'];
                $data['meta']   = array();
                $data['ssurl'] = 'ss://' . base64_encode($data['node']['node_method'] . ":" . $data['node']['node_password'] . "@" . $data['node']['node_server'] . ":" . $data['node']['node_port']);
                $this->creatOutput('user/node', $data);
            }
        }
    }
    
    /**
     * User::tool()
     * 查看工具
     * @return void
     */
    public function tool(){
        $this->loginCheck();
        $this->load->model('Mnode');
        $data['node'] = $this->Mnode->select('single', '1');
        $data['user']                   = $this->Muser->select('single', $this->user['userId']);
        $data['node']['node_port']      = $data['user']['port'];
        $data['node']['node_password']  = $data['user']['passwd'];
        $data['meta']                   = array('title' => 'Tool');
        $this->creatOutput('user/tool', $data);
    }

    /**
     * User::profile()
     * 查看及更改用户资料
     * @return void
     */
    public function profile(){
        $this->loginCheck();
        $userEmail = $this->input->post('email');
        if($userEmail && $this->input->is_ajax_request()){
            // 新密码为空或新密码与确认密码匹配时
            if($this->input->post('newPass') == $this->input->post('confirmPass')){
                if(1 == $this->Muser->update($this->user['userId'])){
                    echo json_encode(array('msg'=>'Update Successful.'));
                } else{
                    echo json_encode(array('msg'=>'Update Fail, Tray Again.'));
                }
            } else{
                 echo json_encode(array('result' => FALSE, 'msg'=>'The Confirm Password Must Be The Same As New Password.'));
            }
        } else{
            $data['user'] = $this->Muser->select('single', $this->user['userId']);
            $data['meta']               = array('title' => 'Profile');
            $this->load->helper('form');
            $this->creatOutput('user/profile', $data);
        }
    }

    /**
     * User::portGenerate()
     * 重新生成用户端口，支持管理员操作
     * @return void
     */
    public function portGenerate(){
        if(($this->adminCheck('port') || $this->loginCheck()) && $this->input->is_ajax_request()){
            $port = $this->Muser->portGenerate();
            echo json_encode(array('result' => TRUE, 'msg'=>'Generate Successful.', 'port' => $port));
        }
    }

    /**
     * User::apply()
     * 申请帐号即提交处理
     * @return void
     */
    public function apply(){
        $userEmail = $this->input->post('email');
        if($userEmail && $this->input->is_ajax_request()){
            $pass = $this->input->post('pass');
            if($pass && '' != $pass && $pass == $this->input->post('confirmPass')){
                if($this->Muser->count('repeat', $userEmail) > 0){
                    echo json_encode(array('result' => FALSE,'msg'=>'Email Have Applyed.'));
                } else{
                    $this->load->model('Mactive');
                    if($this->Muser->Insert() > 0){
                        $code = $this->Mactive->insert($userEmail);
                        $this->load->model('Mmail');
                        $data = $this->Mmail->template('apply', $userEmail, $code);
                        $result = $this->Mmail->send($userEmail, $data['title'], $this->load->view('block/mail_template', $data, TRUE));
                        $this->Mmail->insert($userEmail, $data, $result);
                        echo json_encode(array('msg'=>'Appled Successful, Check Email.'));
                    } else{
                        echo json_encode(array('result' => FALSE, 'msg'=>'Apply Fail, Tray Again Later.'));
                    }
                }
            } else{
                 echo json_encode(array('result' => FALSE, 'msg'=>'Confirm Password Must Be The Same As New Password.'));
            }
        } else{
            // 激活模式
            $this->load->helper('form');
            $data['meta']               = array('title' => 'Apply An Account');
            $this->creatOutput('user/apply', $data); 
        }
    }
    
    /**
     * User::active()
     * 根据激活码激活注册用户，2 小时内有效，且激活码仅可使用一次
     * @param string $itemId 激活码
     * @return void
     */
    public function active($itemId = NULL){
        $itemId = NULL == $itemId ? $this->input->get('itemId') : $itemId;
        if(!$itemId || '' == $itemId){
            show_404();
            exit();
        }
        
        $this->load->model('Mactive');
        $active = $this->Mactive->select('single', $itemId);
        if(empty($active)){
            show_error('The Active Code Is Not Available. Try Again');
            sleep(2);
            redirect('user/apply');
        } else{
            $this->Mactive->update($itemId);
            $this->Muser->active($active['email']);
            $this->load->helper('form');
            $data['meta']               = array('title' => 'Account Acitve Successfully');
            $data['result']             = anchor('user/login', 'Login to Start');
            $this->creatOutput('user/tip', $data);
        }
    }

    /**
     * User::checkInLog()
     * 签到记录
     * @param integer $num 页码起始
     * @return void
     */
    public function checkInLog($num = 0){
        $this->loginCheck();
        $num = is_numeric($num) ? $num : 0;
        $this->load->model('Mcheckin');

        $this->load->library('pagination');
        $config['pagination_fix']   = FALSE;
        $config['first_url']        = site_url('user/checkInLog');
        $config['base_url']         = '/user/checkInLog';
        $config['total_rows']       = $this->Mcheckin->count();
        $config                     = array_merge($config, $this->pagerArray($config['pagination_fix']));
        $config['num_links']        = 5;
        $this->pagination->initialize($config);
        $data['list']               = $this->Mcheckin->select($config['per_page'], $num);
        $data['num']                = $num;
        $data['meta']               = array('title' => 'CheckIn History');

        $this->createTable(array('Traffic (MB)', 'CheckIn Time'));
        $this->creatOutput('block/list_content', $data);
    }

    /**
     * User::checkIn()
     * 签到，每 24h 可以签到一次，每次后屈 10-80MB 的随机流量
     * @return void
     */
    public function checkIn(){
        if($this->loginCheck() && $this->input->is_ajax_request()){
            // 检查当天是否已签到
            $data['user'] = $this->Muser->select('single', $this->user['userId']);
            if($data['user']['last_check_in_time'] > strtotime('-1 day')){
                echo json_encode(array('msg'=>'CheckIn Once in 24 hours. Gets 10~80 MB Traffic.'));
            } else{
                // 执行签到，随机累加 10-80M 流量
                $transfer = rand(10, 80);
                $this->Muser->update($this->user['userId'], array(
                        'transfer_enable'       => $transfer * 1024 * 1024
                ));
                $this->load->model('Mcheckin');
                $this->Mcheckin->insert($transfer);
                echo json_encode(array('result' => TRUE,'msg'=>'CheckIn Succcessful, Got '.$transfer.' MB Traffic.'));
            }
        }
    }
    
    /**
     * User::reset()
     * 根据重置码重置用户密码，48h 内有效，且重置码仅可使用一次
     * @param string $itemId 重置码
     * @return void
     */
    public function reset($itemId = NULL){
        if($this->input->is_ajax_request()){
            $pass = $this->input->post('newPass');
            if($pass && '' != $pass && $pass == $this->input->post('confirmPass')){
                $userEmail = $this->input->post('email');
                if('1' == $this->Muser->reset($userEmail, array('pass' => sha1($pass)))){
                    $this->load->model('Mmail');
                    $data = $this->Mmail->template('reset', $userEmail);
                    $result = $this->Mmail->send($userEmail, $data['title'], $this->load->view('block/mail_template', $data, TRUE));
                    $this->Mmail->insert($userEmail, $data, $result);
                    echo json_encode(array('msg'=>'Password Reset Successful, You Can Login With The New Password.'));
                    sleep(2);
                    redirect('user/login');
                } else{
                    echo json_encode(array('msg'=>'Password Reset Fail, Tray Again.'));
                }
            } else{
                echo json_encode(array('result' => FALSE, 'msg'=>'Confirm Password Must Be The Same As New Password.'));
            }
        } else{
            $itemId = NULL == $itemId ? $this->input->get('itemId') : $itemId;
            if(!$itemId || NULL == $itemId){
                show_404();
                exit;
            } else{
                $this->load->model('Mreset');
                $reset = $this->Mreset->select('single', $itemId);
                if(empty($reset)){
                    show_error('The Reset Code Is Not Available. Try Again');
                    sleep(2);
                    redirect('user/reset');
                } else{
                    $this->Mreset->update($itemId);
                    $this->load->helper('form');
                    $data['meta']               = array('title' => 'Reset Password');
                    $data['user'] = $this->Muser->select('single', $reset['email']);
                    $this->creatOutput('user/reset', $data);
                }
            }
        }     
    }
    
    /**
     * User::forget()
     * 请求重置密码
     * @return void
     */
    public function forget(){
        $userEmail = $this->input->post('email');
        if($userEmail && $this->input->is_ajax_request()){
            $this->load->model('Mreset');
            $code = $this->Mreset->insert($userEmail);
            if($code){
                $this->load->model('Mmail');
                $data = $this->Mmail->template('forget', $userEmail, $code);
                $result = $this->Mmail->send($userEmail, $data['title'], $this->load->view('block/mail_template', $data, TRUE));
                $this->Mmail->insert($userEmail, $data, $result);
                echo json_encode(array('msg'=>'Password Reset Request Successful, Check Email Box.'));
            } else{
                echo json_encode(array('msg'=>'Password Reset Request Fail, Tray Again Later.'));
            }
        } else{
            $this->load->helper('form');
            $data['meta']               = array('title' => 'Request A Reset');
            $this->creatOutput('user/forget', $data);
        }
    }
    
    /**
     * User::repeat()
     * 按照邮件检查用户是否重复注册
     * @param string $itemId 用户邮件
     * @return void
     */
    public function repeat($itemId = NULL){
        if($this->input->is_ajax_request()){
            $userEmail = NULL == $itemId ? $this->input->get_post('itemId') : $itemId;
            if($this->Muser->count('repeat', $userEmail) > 0){
                echo json_encode(array('result' => FALSE,'msg'=>'Email Has Been Taken.'));
            } else{
                echo json_encode(array('result' => TRUE,'msg'=>'Email Is Available.'));
            }
        }
    }

    /**
     * User::login()
     * 登录
     * @return void
     */
    public function login(){
        $userEmail = $this->input->post('email');
        if($userEmail && $this->input->is_ajax_request()){
            $pass = $this->input->post('password');
            $user = $this->Muser->select('single', $userEmail);
            $this->load->model('Mlog');
            if(empty($user)){
                $this->Mlog->insert($userEmail, $pass, 0);
                echo json_encode(array('result' => FALSE,'msg'=>'Login Fail, The Email Is Not Exist.'));
                exit;
            }

            if($user['pass'] == sha1($pass)){
                if('1' == $user['enable']){
                    $this->user = array(
                        'userId'    => $user['uid'],
                        'userEmail' => $userEmail
                    );
                    $this->session->set_userdata($this->user);
                    echo json_encode(array('result' => TRUE,'msg'=> site_url('user')));
                    exit;
                } else{
                    echo json_encode(array('result' => FALSE,'msg'=>'Login Fail, The Email Just Applyed.'));
                    exit;
                }
            } else{
                echo json_encode(array('result' => FALSE,'msg'=>'Login Fail, The Password Was Wrong.'));
                exit;
            }
        } else{
            $this->loginCheck('index');

            $this->load->helper('form');
            $data['meta']               = array('title' => 'Login');
            $this->creatOutput('user/login', $data);
        }
    }

    /**
     * User::logout()
     * 注销
     * @return void
     */
    public function logout(){
        $this->session->sess_destroy();
        redirect('user/login');
    }

}
