<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/m_group','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(38);
    }
    
    function index(){
        if (isset($_GET['grid'])){
            echo $this->record->index();      
        }
        else {
            $this->load->view('admin/v_group'); 
        }
    } 
    
    function create(){
        if(!isset($_POST))	
            show_404();

        $a_level_name   = addslashes($_POST['a_level_name']);
        
        echo $this->record->create($a_level_name);
    }     
    
    function update($a_level_id=null){
        if(!isset($_POST))	
            show_404();
        
        $a_level_name   = addslashes($_POST['a_level_name']);
        
        echo $this->record->update($a_level_id, $a_level_name);
    }
        
    function delete(){
        if(!isset($_POST))	
            show_404();

        $a_level_id = addslashes($_POST['a_level_id']);
        
        echo $this->record->delete($a_level_id);
    }

    function menu($a_level_id=null){      
        echo $this->record->menu($a_level_id);               
    }
    
    function menu_update(){
        $a_group_id     = addslashes($_POST['a_group_id']);
        $a_group_status = addslashes($_POST['a_group_status']);
        
        echo $this->record->menu_update($a_group_id, $a_group_status);               
    }
                
}

/* End of file group.php */
/* Location: ./application/controllers/admin/group.php */