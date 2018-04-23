<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proccat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/m_proccat','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(35);
    }
    
    function index(){
        if (isset($_GET['grid'])) {
            echo $this->record->index();   
        }
        else {
            $this->load->view('master/v_proccat');  
        }
    } 
    
    function create() {
        if(!isset($_POST))	
            show_404();
        
        $m_process_cat_name  = addslashes($_POST['m_process_cat_name']);
        $m_process_cat_table = addslashes($_POST['m_process_cat_table']);
                           
        echo $this->record->create($m_process_cat_name, $m_process_cat_table);
    }     
    
    function update($m_process_cat_id=null) {
        if(!isset($_POST))	
            show_404();
        
        $m_process_cat_name     = addslashes($_POST['m_process_cat_name']);
        $m_process_cat_table    = addslashes($_POST['m_process_cat_table']);
        
        echo $this->record->update($m_process_cat_id, $m_process_cat_name, $m_process_cat_table);
            
    }
            
    function delete(){
        if(!isset($_POST))	
            show_404();

        $m_process_cat_name = addslashes($_POST['m_process_cat_name']);
        
        echo $this->record->delete($m_process_cat_name);
    }
    
    function getItemBom(){
        echo $this->record->getTable();
    }
}

/* End of file proccat.php */
/* Location: ./application/controllers/master/proccat.php */