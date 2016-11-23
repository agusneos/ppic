<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Machine extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/m_machine','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(41);
    }
    
    function index() {
        if (isset($_GET['grid'])){
            echo $this->record->index();      
        }
        else {
            $this->load->view('master/v_machine'); 
        }
    } 
    
    function create() {
        if(!isset($_POST))	
            show_404();

        $m_machine_name     = addslashes($_POST['m_machine_name']);
        $m_machine_lines    = addslashes($_POST['m_machine_lines']);
        $m_machine_mac      = addslashes($_POST['m_machine_mac']);
        $m_process_cat_id   = addslashes($_POST['m_process_cat_id']);
        
        echo $this->record->create($m_machine_name, $m_machine_lines, $m_machine_mac, $m_process_cat_id);
    }     
    
    function update($m_machine_id=null) {
        if(!isset($_POST))	
            show_404();
        
        $m_machine_name     = addslashes($_POST['m_machine_name']);
        $m_machine_lines    = addslashes($_POST['m_machine_lines']);
        $m_machine_mac      = addslashes($_POST['m_machine_mac']);
        $m_process_cat_id   = addslashes($_POST['m_process_cat_id']);
        
        echo $this->record->update($m_machine_id, $m_machine_name, $m_machine_lines, $m_machine_mac, $m_process_cat_id);
    }
        
    function delete() {
        if(!isset($_POST))	
            show_404();

        $m_machine_id = addslashes($_POST['m_machine_id']);
        
        echo $this->record->delete($m_machine_id);
    }
    
    function getProces(){
        echo $this->record->getProces();
    }
                
}

/* End of file machine.php */
/* Location: ./application/controllers/master/machine.php */