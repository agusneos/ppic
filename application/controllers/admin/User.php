<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/m_user','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(6);
    }
    
    function index(){
        if (isset($_GET['grid'])) {
            echo $this->record->index();      
        }
        else {
            $this->load->view('admin/v_user');     
        }
    } 
    
    function create(){
        if(!isset($_POST))	
            show_404();

        $a_user_name        = addslashes($_POST['a_user_name']);
        $a_user_username    = addslashes($_POST['a_user_username']);
        $a_user_password    = addslashes($_POST['a_user_password']);
        $a_user_level       = addslashes($_POST['a_user_level']);
        
        echo $this->record->create($a_user_name, $a_user_username, $a_user_password, $a_user_level);
    }     
    
    function update($a_user_id=null){
        if(!isset($_POST))	
            show_404();
        
        $a_user_name        = addslashes($_POST['a_user_name']);
        $a_user_username    = addslashes($_POST['a_user_username']);
        $a_user_password    = addslashes($_POST['a_user_password']);
        $a_user_level       = addslashes($_POST['a_user_level']);
        
        echo $this->record->update($a_user_id, $a_user_name, $a_user_username, $a_user_password, $a_user_level);
    }
    
    function delete(){
        if(!isset($_POST))	
            show_404();

        $a_user_id  = addslashes($_POST['a_user_id']);
        echo $this->record->delete($a_user_id);
    }
    
    function reset($a_user_id=null){
        if(!isset($_POST))	
            show_404();
        
        $a_user_password    = addslashes($_POST['a_user_password']);
        echo $this->record->reset($a_user_id, $a_user_password);
    }
    
    function getLevel(){
        echo $this->record->getLevel();
    }
        
}

/* End of file user.php */
/* Location: ./application/controllers/admin/user.php */