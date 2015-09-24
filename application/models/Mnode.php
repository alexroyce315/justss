<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mnode extends CI_Model{

    var $dbTable = 'ss_node';

    public function __construct(){
        parent::__construct();
        log_message('info', 'Mnode Class start.');
    }

    public function select($type = 'list', $num = 10, $off = 0){
        switch($type){
            case 'user':
                return $this->makeResult($this->db->get($this->dbTable)->result_array());
                break;
            case 'list':
            case 'index':
            default:
                $this->db->select('id,node_name,node_server,node_status,node_method');
                break;
            case 'single':
                $this->db->where('id', $num);
                return $this->db->get($this->dbTable, 1, 0)->row_array();
                break;
        }
        $this->db->order_by('node_order', 'ASC');
        return $this->makeResult($this->db->get($this->dbTable, $num, $off)->result_array());
    }

    public function count($type = 'list', $itemId = NULL){
        switch($type){
            case 'user':
            case 'list':
            case 'index':
            default:
                break;
			case 'repeat':
				$this->db->where('node_server', $itemId);
				$this->db->or_where('node_name', $itemId);
				break;
        }
        return $this->db->count_all_results($this->dbTable);
    }

    public function insert(){
        $data = array(
            'node_name'     => $this->input->post('name'),
            'node_server'   => $this->input->post('server'),
            'node_info'     => $this->input->post('info'),
            'node_method'   => $this->config->item('methods')[$this->input->post('method')],
            'node_type'     => $this->input->post('type'),
            'node_status'   => $this->input->post('status'),
            'node_order'    => $this->input->post('order')
        );
        $this->db->insert($this->dbTable, $data);
        return $this->db->insert_id();
    }

    public function update($itemId){
        $data = array(
            'node_name'     => $this->input->post('name'),
            'node_server'   => $this->input->post('server'),
            'node_info'     => $this->input->post('info'),
            'node_method'   => $this->config->item('methods')[$this->input->post('method')],
            'node_type'     => $this->input->post('type'),
            'node_status'   => $this->input->post('status'),
            'node_order'    => $this->input->post('order')
        );
        $this->db->where('id', $itemId);
        $this->db->update($this->dbTable, $data);
        return $this->db->affected_rows();
    }

    public function delete($itemId){
        $this->db->where('id', $itemId);
        $this->db->delete($this->dbTable);
        return $this->db->affected_rows();
    }

    private function makeResult($data = array()){
        if(empty($data)){
            return FALSE;
        } else{
            foreach($data as $key => $item){
                $data[$key]['node_name']    = anchor('admin/nodeEdit/'.$data[$key]['id'],$data[$key]['node_name'],'data-pjax="#pjax-container"');
                $data[$key]['node_status']  = '0' == $item['node_status'] ? 'Stoped' : 'Normal';
                //$data[$key]['node_type']    = '0' == $item['node_type'] ? 'Undefined' : 'Normal';
                // 构造最后一列
                $data[$key] = array_merge_recursive($data[$key], array('manage' => anchor('admin/nodeEdit/'.$data[$key]['id'],'Edit', 'data-pjax="#pjax-container"').' &nbsp; '.anchor('admin/nodeDelete/'.$data[$key]['id'],'Delete', 'data-pjax="#pjax-container"')));
            }
            return $data;
        }
    }
}