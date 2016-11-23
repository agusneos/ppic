<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Operator extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master/m_operator','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(53);
    }
    
    function index() {
        if (isset($_GET['grid'])) {
            echo $this->record->index();      
        }
        else  {
            $this->load->view('master/v_operator'); 
        }
    } 
    
    function create(){
        if(!isset($_POST))	
            show_404();

        $m_operator_nik     = addslashes($_POST['m_operator_nik']);
        $m_operator_name    = addslashes($_POST['m_operator_name']);
        $m_operator_auth    = addslashes($_POST['m_operator_auth']);
        
        echo $this->record->create($m_operator_nik, $m_operator_name, $m_operator_auth);
    }     
    
    function update($m_operator_nik=null) {
        if(!isset($_POST))	
            show_404();
        
        $m_operator_name    = addslashes($_POST['m_operator_name']);
        $m_operator_auth    = addslashes($_POST['m_operator_auth']);
        
        echo $this->record->update($m_operator_nik, $m_operator_name, $m_operator_auth);
    }
        
    function delete() {
        if(!isset($_POST))	
            show_404();

        $m_operator_nik     = addslashes($_POST['m_operator_nik']);
        
        echo $this->record->delete($m_operator_nik);
    }
    
    function getProces() {
        echo $this->record->getProces();
    }
                
}

/* End of file operator.php */
/* Location: ./application/controllers/master/operator.php */