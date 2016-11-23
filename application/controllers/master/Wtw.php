<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wtw extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/m_wtw','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(36);
    }
    
    function index(){
        if (isset($_GET['grid'])){
            echo $this->record->index();   
        }
        else{
            $this->load->view('master/v_wtw');  
        }
    } 
    
    function create(){
        if(!isset($_POST))	
            show_404();
        
        $m_item_bom_id      = addslashes($_POST['m_item_bom_id']);
        $m_item_bom_cat     = addslashes($_POST['m_item_bom_cat']); 
        $m_item_bom_name    = addslashes($_POST['m_item_bom_name']);
        $m_item_bom_qty     = addslashes($_POST['m_item_bom_qty']);
                           
        echo $this->record->create($m_item_bom_id, $m_item_bom_cat, $m_item_bom_name, $m_item_bom_qty);
        
    }
    
    function update($m_item_bom_id=null){
        if(!isset($_POST))	
            show_404();

        $m_item_bom_cat     = addslashes($_POST['m_item_bom_cat']); 
        $m_item_bom_name    = addslashes($_POST['m_item_bom_name']);
        $m_item_bom_qty     = addslashes($_POST['m_item_bom_qty']);
        
        echo $this->record->update($m_item_bom_id, $m_item_bom_cat, $m_item_bom_name, $m_item_bom_qty);
        
    }
        
    function delete(){
        if(!isset($_POST))	
            show_404();

        $m_item_bom_id          = addslashes($_POST['m_item_bom_id']);
        
        echo $this->record->delete($m_item_bom_id);
        
    }
        
    function enumBomCat(){
        echo $this->record->enumField('m_item_bom', 'm_item_bom_cat');
    }
    
}

/* End of file wtw.php */
/* Location: ./application/controllers/master/wtw.php */