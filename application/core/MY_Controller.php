<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    var $user = array();

    public function __construct() {
        parent::__construct();
        log_message('info', 'MY_Controller Class Initialized');
    }

    private function meta($data = array()){
        $data['title'] = isset($data['title']) ? explode(' &nbsp; ', $data['title']) : 'title';
        $data = array(
            'title'         => 'title' == $data['title'] ?  'title' : $data['title'][0],
            'siteName'      => isset($data['siteName']) ? $data['siteName'] : $this->config->item('siteName'),
            'siteAuthor'    => isset($data['siteAuthor']) ? $data['siteAuthor'] :  $this->config->item('siteAuthor')
        );
        $data['title'] = $data['title'].' - '.$data['siteName'];
        return $this->load->view('block/meta', $data, TRUE);
    }

    private function navbar(){
        $data['user'] = $this->user;
        return $this->load->view('block/navbar', $data, TRUE);
    }
    
    private function adminNavbar(){
        $data['user'] = $this->user;
        return $this->load->view('block/navbar_admin', $data, TRUE);
    }

    public function creatOutput($view, $data, $type = '1'){
        if($this->checkPjax()){
            echo $this->load->view($view, $data, TRUE);
        } else{
        	$content = array(
	            'meta'      => $this->meta($data['meta']),
                'navbar'    => '0' == $type ? $this->adminNavbar() : $this->navbar(),
                'content'   => $this->load->view($view, $data, TRUE)
	        );
            $this->parser->parse('block/layout', $content);
        }
    }

    public function createTable($header = array()){
        $tmplate = array(
            'table_open'          => '<table id="example" class="u-full-width">',
            'heading_row_start'   => '<tr>',
            'heading_row_end'     => '</tr>',
            'heading_cell_start'  => '<th>',
            'heading_cell_end'    => '</th>',

            'row_start'           => '<tr>',
            'row_end'             => '</tr>',
            'cell_start'          => '<td>',
            'cell_end'            => '</td>',

            'row_alt_start'       => '<tr>',
            'row_alt_end'         => '</tr>',
            'cell_alt_start'      => '<td>',
            'cell_alt_end'        => '</td>',

            'table_close'         => '</table>'
        );
        $this->load->library('table');
        $this->table->set_template($tmplate);
        count($header) > 0 ? $this->table->set_heading($header) : NULL;
        //return $this->table->generate($data);
    }

    public function layoutArray($data){
        return array(
            'meta'          => $this->meta($data['meta']),
            'navbar'        => $this->navbar()
        );
    }

    public function pagerArray($itemId = TRUE){
        $modify = array(
            'cur_rows_tag_open'     => '<li>',
            'cur_rows_tag_middle'   => ' ~ ',
            'cur_rows_tag_close'    => '</li>',
            'total_rows_tag_open'   => '<li>',
            'total_rows_tag_close'  => '</li>',
            'total_pages_tag_open'  => '<li>',
            'total_pages_tag_close' => '</li>'
        );

        $config = array(
            'per_page'              => 10,
            'num_links'             => 3,
            'full_tag_open'         => '<div class="pagination">',
            'full_tag_close'        => '</div>',
            'use_page_numbers'      => TRUE,
            'first_link'            => '&lsaquo;',
            'last_link'             => '&rsaquo;',
            //'num_tag_open'          => '<li>',
            //'num_tag_close'         => '</li>',
            //'next_tag_open'         => '<li class="next">',
            'next_link'             => '&raquo;',
            //'next_tag_close'        => '</li>',
            //'prev_tag_open'         => '<li class="prev">',
            'prev_link'             => '&laquo;',
            //'prev_tag_close'        => '</li>',
            'cur_tag_open'          => '<span class="page-numbers current">',
            'cur_tag_close'         => '</span>',
            //'first_tag_open'        => '<li>',
            //'first_tag_close'       => '</li>',
            //'last_tag_open'         => '<li>',
            //'last_tag_close'        => '</li>',
            'anchor_class'          => 'class="page-numbers"',
            'anchor_data'           => 'data-pjax="#pjax-container"'
        );

        return $itemId ? array_merge($config, $modify) : $config;
    }

    private function checkPjax(){
        if(array_key_exists('HTTP_X_PJAX', $_SERVER) && $_SERVER['HTTP_X_PJAX']){
            return TRUE;
        } else{
            $this->load->library('parser');
            return FALSE;
        }
    }

    public function loginCheck($itemId = NULL){
        if($this->session->userdata('userId')){
            $this->user = array(
                'userId'    => $this->session->userdata('userId'),
                'userEmail' => $this->session->userdata('userEmail')
            );
            if(NULL == $itemId){
                return TRUE;
            } else{
                redirect('user/'.$itemId);
            }
        } else{
            if(NULL == $itemId){
                redirect('user/login');
            } else{
                return FALSE;
            }
        }
    }

    public function adminCheck($itemId = NULL){
        if($this->session->userdata('adminEmail')){
            $this->user = array(
                'adminId'       => $this->session->userdata('adminId'),
                'adminEmail'    => $this->session->userdata('adminEmail')
            );
            $this->load->model('Madmin');
            if(1 === $this->Madmin->count('check', $this->user)){
                return TRUE;
            }
        } else{
            if(NULL == $itemId){
                $this->session->sess_destroy();
                redirect('admin/login');
            }
        }
        return FALSE;
    }
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */