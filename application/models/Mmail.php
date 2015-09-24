<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mmail extends CI_Model{

    var $dbTable = 'mail_log';

    public function __construct(){
        parent::__construct();
        log_message('info', 'Mmail Class start.');
    }

    public function select($num = 10, $off = 0, $itemId = NULL){
        NULL == $itemId ? NULL : $this->db->where('result', $type);
        $this->db->order_by('time', 'DESC');
        return $this->makeResult($this->db->get($this->dbTable, $num, $off)->result_array());
    }

    public function count($itemId = NULL){
        NULL == $itemId ? NULL : $this->db->where('result', $itemId);
        return $this->db->count_all_results($this->dbTable);
    }

    public function insert($email, $content, $result = '0'){
        $data = array(
            'email'     => NULL == $email ? $this->session->userdata('userEmail') : $email,
            'ip'        => $this->input->ip_address(),
            'ua'        => $_SERVER['HTTP_USER_AGENT'],
            'result'    => $result,
            'content'   => json_encode($content),
            'time'      => time()
        );
        $this->db->insert($this->dbTable, $data);
        return $this->db->insert_id();
    }
    
    public function delete($id){
        $this->db->where('mailId', $id);
        $this->db->delete($this->dbTable);
        return $this->db->affected_rows();
    }
    
    public function send($email, $subject = NULL, $content, $type = 'html'){
		$config['mailtype']     = $type;
        $this->load->library('email', array_merge($this->config->item('emailArray'), $config));
        $this->email->from('no-replay@justfree.org.cn', $this->config->item('siteName').' 邮件通知');
        $this->email->to($email);
        $this->email->subject(NULL == $subject ? $this->config->item('siteName').' 邮件通知' : $subject);
        $this->email->message(str_replace('\"', '', $content));
        if($this->email->send()){
			return TRUE;
		} else{
			log_message('error', $this->email->print_debugger());
			return FALSE;
        }
    }
	
	public function template($type = 'apply', $email, $itemId = NULL){
	   $data = array();
		switch($type){
			case 'apply':
			default:
                $data = array(
                    'title' => 'Apply Account Successfully',
                    'email' => $email,
                    'summary'   => 'Active account in 2 hours. If the link is not available, visit the website address here:{unwrap}'.site_url('user/active/'.$itemId).'{/unwrap}. The address can be clicked JUST ONLY ONCE!',
                    'link'      => '<a href=\''.site_url('user/active/'.$itemId).'\'>Click Here To Active.</a>',
                );
				break;
			case 'pass':
            case 'active':
            case 'actived':
                $data = array(
                    'title' => 'Apply Account Actived',
                    'email' => $email,
                    'summary'   => 'We have accepted the account apply. Login to start:{unwrap}'.site_url('user').'{/unwrap}. Engoy it.',
                    'link'      => '<a href=\''.site_url('user').'\'>Click Here To Start.</a>',
                );
				break;
			case 'stop':
				break;
			case 'reset':
                $data = array(
                    'title' => 'Reset Password Successfully',
                    'email' => $email,
                    'summary'   => 'Reset password successfully at '.date('Y-m-d H:i:s').'.'
                );
				break;
			case 'pay':
                $data = array(
                    'title' => 'Take '.$itemId.'GB Successfully',
                    'email' => $email,
                    'summary'   => 'Take '.$itemId.'GB Successfully at '.date('Y-m-d H:i:s').'. The traffic has been appened. Thanks a lot.'
                );
				break;
			case 'notice':
                $data = array(
                    'title' => 'Notice',
                    'email' => $email,
                    'summary'   => $itemId
                );
				break;
            case 'forget':
                $data = array(
                    'title' => 'Reset Password Request',
                    'email' => $email,
                    'summary'   => 'Reset the password in 48 hours. If the link is not available, visit the website address here:{unwrap}'.site_url('user/reset/'.$itemId).'{/unwrap}. The address can be clicked JUST ONLY ONCE!',
                    'link'      => '<a href=\''.site_url('user/reset/'.$itemId).'\'>Click HHereear To Reset Password.</a>'
                );
                break;
		}
        return array_merge($data, array('siteName'=>$this->config->item('siteName')));
	}

    private function makeResult($data = array()){
        if(empty($data)){
            return array();
        } else{
            foreach($data as $key => $item){
                $data[$key]['result']   = '0' == $item['result'] ? 'Fail' : 'Success';
                $data[$key]['time']     = date('Y-m-d H:i:s', $item['time']);
            }
            return $data;
        }
    }
}
