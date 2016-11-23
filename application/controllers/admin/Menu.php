<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/m_menu','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(7);
    }
    
    function index(){
        if (isset($_GET['grid'])){
            echo $this->record->index(); 
        }
        else{
            $this->load->view('admin/v_menu');     
        }
    }
    
    function create(){
        if(!isset($_POST))	
            show_404();
        
        $a_menu_parentId    = addslashes($_POST['a_a_menu_parentId']);
        $a_menu_name        = addslashes($_POST['a_a_menu_name']);
        $a_menu_uri         = addslashes($_POST['a_a_menu_uri']);
        $a_menu_iconCls     = addslashes($_POST['a_a_menu_iconCls']);
        $a_menu_type        = addslashes($_POST['a_a_menu_type']);
        
        echo $this->record->create($a_menu_parentId, $a_menu_name, $a_menu_uri, $a_menu_iconCls, $a_menu_type);
    }     
    
    function update($a_menu_id=null){
        if(!isset($_POST))	
            show_404();
        
        $a_menu_parentId    = addslashes($_POST['a_a_menu_parentId']);
        $a_menu_name        = addslashes($_POST['a_a_menu_name']);
        $a_menu_uri         = addslashes($_POST['a_a_menu_uri']);
        $a_menu_iconCls     = addslashes($_POST['a_a_menu_iconCls']);
        $a_menu_type        = addslashes($_POST['a_a_menu_type']);
        
        echo $this->record->update($a_menu_id, $a_menu_parentId, $a_menu_name, $a_menu_uri, $a_menu_iconCls, $a_menu_type);
    }
        
    function delete(){
        if(!isset($_POST))	
            show_404();

        $a_menu_id  = addslashes($_POST['a_menu_id']);
        echo $this->record->delete($a_menu_id);
    }
    
    function getParent(){
        echo $this->record->getParent();      
    }
    
    function enumType(){
        echo $this->record->enumField('a_menu', 'a_menu_type');
    }
               
}

/* End of file menu.php */
/* Location: ./application/controllers/admin/menu.php */