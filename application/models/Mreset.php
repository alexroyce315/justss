<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mreset extends CI_Model{

    var $dbTable = 'reset';

    public function __construct(){
        parent::__construct();
        log_message('info', 'Mreset Class start.');
    }
    
    public function select($type = 'reset', $num = 10, $off = 0, $itemId = NULL){
        switch($type){
            case 'list':
            case 'index':
                $this->db->select('email,ip,ip,time,active,usedTime');
                break;
            case 'reset':
            case 'single':
            default:
                $this->db->where(array('code' => $num, 'active' => 1, 'time >= ' => strtotime('-2 days')));
                return $this->db->get($this->dbTable, 1, 0)->row_array();
                break;
        }
        return $this->makeResult($this->db->get($this->dbTable, $num, $off)->result_array());
    }
    
    public function count($type = 'list', $itemId = NULL){
        switch($type){
            case 'list':
            case 'index':
                break;
        }
        return $this->db->count_all_results($this->dbTable);
    }
    
    public function insert($email){
        $data = array(
            'email'     => $email,
            'ip'        => $this->input->ip_address(),
            'ua'        => $_SERVER['HTTP_USER_AGENT'],
            'time'      => time(),
            'active'    => 1
        );
        $data['code']   = $this->uniqueRand($data);
        return $this->db->insert($this->dbTable, $data) ? $data['code'] : FALSE;
    }

    public function update($itemId, $data = array()){
        $data = empty($data) ? array(
            'active'    => 0,
            'usedTime'  => time()
        ) : $data;
        $data['active'] = isset($data['active']) ? $data['active'] : 0;
        $this->db->where(array('code' => $itemId, 'active' => 1));
        $this->db->update($this->dbTable, $data);
        return $this->db->affected_rows();
    }

    private function makeResult($data = array()){
        if(empty($data)){
            return FALSE;
        } else{
            foreach($data as $key => $item){
                $data[$key]['active']   = 0 == $item['active'] ? 'Used' : 'Unused';
                $data[$key]['time']     = date('Y-m-d H:i:s', $item['time']);
                $data[$key]['usedTime'] = date('Y-m-d H:i:s', $item['usedTime']);
            }
            return $data;
        }
    }
        
    private function uniqueRand($data){
        return urlencode(base64_encode(sha1(implode('', $data))));
    }
}