<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendor extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master/m_vendor','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(28);
    }
    
    function index() {
        if (isset($_GET['grid'])){
            echo $this->record->index();      
        }
        else {
            $this->load->view('master/v_vendor'); 
        }
    } 
    
    function create(){
        if(!isset($_POST))	
            show_404();

        $m_vend_id      = addslashes($_POST['m_vend_id']);
        $m_vend_name    = addslashes($_POST['m_vend_name']);
        $m_vend_key     = addslashes($_POST['m_vend_key']);
        $m_vend_addr    = addslashes($_POST['m_vend_addr']);
        
        echo $this->record->create($m_vend_id, $m_vend_name, $m_vend_key, $m_vend_addr);
    }     
    
    function update($m_vend_id=null){
        if(!isset($_POST))	
            show_404();
             
        $m_vend_name    = addslashes($_POST['m_vend_name']);
        $m_vend_key     = addslashes($_POST['m_vend_key']);
        $m_vend_addr    = addslashes($_POST['m_vend_addr']);
        
        echo $this->record->update($m_vend_id, $m_vend_name, $m_vend_key, $m_vend_addr);
    }
        
    function delete(){
        if(!isset($_POST))	
            show_404();

        $m_vend_id      = addslashes($_POST['m_vend_id']);
        
        echo $this->record->delete($m_vend_id);
    }                
}

/* End of file vendor.php */
/* Location: ./application/controllers/master/vendor.php */