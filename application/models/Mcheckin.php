<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mcheckin extends CI_Model{

    var $dbTable = 'checkin';

    public function __construct(){
        parent::__construct();
        log_message('info', 'Mcheckin Class start.');
    }

    public function select($num = 10, $off = 0, $itemId = NULL){
        $this->db->select('traffic,time');
        $this->db->where('uid', NULL == $itemId ? $this->session->userdata('userId') : $itemId);
        $this->db->order_by('time', 'DESC');
        return $this->makeResult($this->db->get($this->dbTable, $num, $off)->result_array());
    }

    public function count($itemId = NULL){
        $this->db->where('uid', NULL == $itemId ? $this->session->userdata('userId') : $itemId);
        return $this->db->count_all_results($this->dbTable);
    }

    public function insert($itemId = 10){
        $data = array(
            'uid'       => $this->session->userdata('userId'),
            'time'      => time(),
            'traffic'   => $itemId
        );
        $this->db->insert($this->dbTable, $data);
        return $this->db->insert_id();
    }

    private function makeResult($data = array()){
        if(empty($data)){
            return FALSE;
        } else{
            foreach($data as $key => $item){
                $data[$key]['time']     = date('Y-m-d H:i:s', $item['time']);
            }
            return $data;
        }
    }
}