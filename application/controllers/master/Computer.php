<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Computer extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/m_computer','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(40);
    }
    
    function index() {
        if (isset($_GET['grid'])) {
            echo $this->record->index();      
        }
        else  {
            $this->load->view('master/v_computer'); 
        }
    } 
    
    function create(){
        if(!isset($_POST))	
            show_404();

        $m_computer_name    = addslashes($_POST['m_computer_name']);
        $m_computer_ip      = addslashes($_POST['m_computer_ip']);
        $m_process_cat_id   = addslashes($_POST['m_process_cat_id']);
        
        echo $this->record->create($m_computer_name, $m_computer_ip, $m_process_cat_id);
    }     
    
    function update($m_computer_id=null) {
        if(!isset($_POST))	
            show_404();
        
        $m_computer_name    = addslashes($_POST['m_computer_name']);
        $m_computer_ip      = addslashes($_POST['m_computer_ip']);
        $m_process_cat_id   = addslashes($_POST['m_process_cat_id']);
        
        echo $this->record->update($m_computer_id, $m_computer_name, $m_computer_ip, $m_process_cat_id);
    }
        
    function delete() {
        if(!isset($_POST))	
            show_404();

        $m_computer_id = addslashes($_POST['m_computer_id']);
        
        echo $this->record->delete($m_computer_id);
    }
    
    function getProces() {
        echo $this->record->getProces();
    }
                
}

/* End of file computer.php */
/* Location: ./application/controllers/master/computer.php */