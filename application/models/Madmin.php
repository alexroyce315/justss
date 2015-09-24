<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Madmin extends CI_Model{

    var $dbTable = 'ss_admin';

    public function __construct(){
        parent::__construct();
        log_message('info', 'Madmin Class start.');
    }

    #region admin
    public function select($type = 'list', $num = 10, $off = 0){
        switch($type){
            case 'list':
            case 'index':
            default:
                break;
            case 'single':
                $this->db->where('email', $num);
                $this->db->or_where('uid', $num);
                return $this->db->get($this->dbTable, 1, 0)->row_array();
                break;
            case 'login':
                $this->db->where(array('email' => $num, 'pass' => $off));
                return $this->db->get($this->dbTable, 1, 0)->row_array();
                break;
        }
        return $this->db->get($this->dbTable, $num, $off);
    }
	
	public function count($type = 'list', $itemId = NULL){
        switch($type){
            case 'list':
            case 'index':
            default:
                break;
			case 'repeat':
				$this->db->where('email', $itemId);
				break;
            case 'check':
                $this->db->where(array('uid' => $itemId['adminId'], 'email' => $itemId['adminEmail']));
                break;
        }
        return $this->db->count_all_results($this->dbTable);
    }
	
	public function insert(){
        $data = array(
            'email'             => $this->input->post('email'),
            'pass'              => sha1($this->input->post('pass'))
        );
        $this->db->insert($this->dbTable, $data);
        return $this->db->insert_id();
    }

    public function update($itemId = NULL, $data = array()){
        $itemId = NULL == $itemId ? $this->session->userdata('userId') : $itemId;
        $this->db->where('uid', $itemId);
        $this->db->update($this->dbTable, $data);
        return $this->db->affected_rows();
    }

    public function delete($uid){
        $this->db->where('uid', $uid);
        $this->db->delete($this->dbTable);
        return $this->db->affected_rows();
    }
    #end region

    #region traffic
    public function traffic($type = 'month', $itemId = 'u'){
        switch($type){
            case 'month':
            default:
                $this->db->select_sum($itemId);
                $query = $this->db->get('user')->row_array();
                return $query[$itemId];      
                break;
        }
    }
    #end region
}