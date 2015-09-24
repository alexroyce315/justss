<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mlog extends CI_Model{

    var $dbTable    = 'user_log';

    public function __construct(){
        parent::__construct();
        log_message('info', 'Mlog Class start.');
    }

    public function select($type = 'user', $num = 10, $off = 0, $itemId = NULL){
        $this->db->select('email,ip,ua,result, time');
        switch($type){
            case 'user':
            case '1':
            default:
                $this->db->where('type', 1);
                break;
            case 'admin':
            case '0':
                $this->db->where('type', 0);
                break;
        }
        NULL == $itemId ? NULL : $this->db->where('result', $itemId);
        $this->db->order_by('time', 'DESC');
        return $this->makeResult($this->db->get($this->dbTable, $num, $off)->result_array());
    }

    public function count($type = 'user', $itemId = NULL){
        switch($type){
            case 'user':
            case '1':
            default:
                $this->db->where('type', 1);
                break;
            case 'admin':
            case '0':
                $this->db->where('type', 0);
                break;
        }
        NULL == $itemId ? NULL : $this->db->where('result', $itemId);
        return $this->db->count_all_results($this->dbTable);
    }

    public function insert($email = NULL, $password, $result = '0', $type = '1'){
        $data = array(
            'email' => NULL == $email ? $this->session->userdata('userEmail') : $email,
            'pass'  => $password,
            'ip'    => $this->input->ip_address(),
            'ua'    => $_SERVER['HTTP_USER_AGENT'],
            'result'=> $result,
            'time'  => time(),
            'type'  => $type
        );
        $this->db->insert($this->dbTable, $data);
        return $this->db->insert_id();
    }
    
    public function delete($id){
        $this->db->where('logId', $id);
        $this->db->delete($this->dbTable);
        return $this->db->affected_rows();
    }

    private function makeResult($data = array()){
        if(empty($data)){
            return FALSE;
        } else{
            foreach($data as $key => $item){
                $data[$key]['result']   = '0' == $item['result'] ? 'Fail' : 'Success';
                $data[$key]['time']     = date('Y-m-d H:i:s', $data[$key]['time']);
            }
            return $data;
        }
    }

}