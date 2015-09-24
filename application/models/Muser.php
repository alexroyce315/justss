<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Muser extends CI_Model{

    var $dbTable = 'user';

    public function __construct(){
        parent::__construct();
        log_message('info', 'Muser Class start.');
    }
    
    public function select($type = 'list', $num = 10, $off = 0){
        switch($type){
            case 'active':
                $this->db->where('enable', '1');
            case 'list':
            case 'index':
            default:
                $this->db->select('uid,email,u,d,transfer_enable,enable,reg_date,reg_tag');
                $this->db->order_by('reg_date', 'DESC');
                break;
            case 'login':
                $this->db->where(array('email' => $num, 'pass' => $off, 'enable' => 1));
                return $this->db->get($this->dbTable, 1, 0)->row_array();
                break;
            case 'single':
                $this->db->where('email', $num);
                $this->db->or_where('uid', $num);
                return $this->db->get($this->dbTable, 1, 0)->row_array();
                break;
        }
        return $this->makeResult($this->db->get($this->dbTable, $num, $off)->result_array());
    }

    public function count($type = 'list', $itemId = NULL){
        switch($type){
            case 'active':
                $this->db->where('enable', '1');
            case 'list':
            case 'index':
            default:
                break;
            case 'online':
                $this->db->where('t >', time() - $itemId);
                break;
			case 'repeat':
				$this->db->where('email', $itemId);
				break;
        }
        return $this->db->count_all_results($this->dbTable);
    }

    public function insert($data = array()){
        $data = array(
            'email'             => isset($data['email']) ? $data['email'] : $this->input->post('email'),
            'pass'              => sha1(isset($data['pass']) ? $data['pass'] : $this->input->post('pass')),
            'passwd'            => rand(10000,99999),
            't'                 => 0,
            'u'                 => 0,
            'd'                 => 0,
            'transfer_enable'   => 2 * 1024 * 1024 * 1024,
            'enable'            => 0,
            'port'              => $this->portGenerate(),
            'type'              => 7,
            'switch'            => 1,
            'money'             => 0,
            'reg_date'          => time(),
            'reg_tag'           => $this->config->item('siteName')
        );
        $this->db->insert($this->dbTable, $data);
        return $this->db->insert_id();
    }

    public function update($itemId = NULL, $data = array()){
        $itemId = NULL == $itemId ? $this->session->userdata('userId') : $itemId;
        if(isset($data['transfer_enable'])){
            $this->db->set('last_check_in_time', time());
            $this->db->set('transfer_enable', '`transfer_enable` +'. $data['transfer_enable'], FALSE);
            $this->db->where('uid', $itemId);
            $this->db->update($this->dbTable);
        } else{
            $data = array(
                'passwd'    => $this->input->post('passwd'),
                'port'      => $this->input->post('port')
            );
            $data = $this->session->userdata('adminId') ? array_merge($data, array('enable' => $this->input->post('enable'))) : $data;
            $pass = $this->input->post('newPass');
            if($pass){
                $data = array_merge($data, array('pass' => sha1($pass)));
                $this->db->where(array('uid' => $itemId, 'pass' => sha1($this->input->post('pass'))));
            } else{
                $this->db->where('uid', $itemId);
            }
            $this->db->update($this->dbTable, $data);
        }
        return $this->db->affected_rows();
    }
    
    public function reset($itemId = NULL, $data = array()){
        $this->db->set('pass', $data['pass']);
        $this->db->where('email', $itemId);
        $this->db->update($this->dbTable);
        return $this->db->affected_rows();
    }

    public function active($itemId = NULL){
        $this->db->set('enable', 1);
        $this->db->where('email', $itemId);
	$this->db->update($this->dbTable);
	return $this->db->affected_rows(); 
    }

    public function delete($itemId){
        $this->db->where('uid', $itemId);
        $this->db->delete($this->dbTable);
        return $this->db->affected_rows();
    }
    
    public function portGenerate(){
        // 取出所有的已用端口
        $port = array();
        $ports = $this->db->select('port')->get($this->dbTable)->result_array();
        foreach($ports as $item){
            $port[] = $item['port'];
        }
        $ports = $this->uniqueRand(50001, 60000, 40);
        // 排除常用和已用端口
        $ports = array_diff($ports, $port, array('50000,5000,443,88,993,995,52,80,21,22,20'));
        return $ports[array_rand($ports)];
    }

    private function makeResult($data = array()){
        if(empty($data)){
            return FALSE;
        } else{
            foreach($data as $key => $item){
                $data[$key]['email']            = $item['email'];
                $data[$key]['u']                = sprintf("%.4f", $item['u']/1024/1024/1024);
                $data[$key]['d']                = sprintf("%.4f", $item['d']/1024/1024/1024);
                $data[$key]['transfer_enable']  = sprintf("%.4f", $item['transfer_enable']/1024/1024/1024);
                $data[$key]['enable']           = '0' == $item['enable'] ? 'Stoped' : 'Normal';
                $data[$key]['reg_date']         = date('Y-m-d H:i:s', $item['reg_date']);
                // 构造最后一列
                $data[$key] = array_merge_recursive($data[$key], array('manage' => anchor('admin/userEdit/'.$data[$key]['uid'],'Edit','data-pjax="#pjax-container"').' &nbsp; '.anchor('admin/userDelete/'.$data[$key]['uid'],'Delete','data-pjax="#pjax-container"')));
                // 删除第一列 uid
                array_shift($data[$key]);  
            }
            return $data;
        }
    }
    
    /*
    * array unique_rand( int $min, int $max, int $num )
    * 生成一定数量的不重复随机整数
    * $min 和 $max: 指定随机数的范围
    * $num: 指定生成数量
    */
    private function uniqueRand($min, $max, $num) {
        $count = 0;
        $return = array();
        while ($count < $num) {
            $return[] = mt_rand($min, $max);
            $return = array_flip(array_flip($return));
            $count = count($return);
        }
        shuffle($return);
        return $return;
    }
}
