<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marking extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/m_marking','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(42);
    }
    
    function index(){
        if (isset($_GET['grid'])){
            echo $this->record->index();      
        }
        else  {
            $this->load->view('master/v_marking'); 
        }
    } 
    
    function create(){
        move_uploaded_file($_FILES['m_marking_path']['tmp_name'],
                'assets/images/marking/' . $_FILES['m_marking_path']['name']);
        $m_marking_path = 'assets/images/marking/' . $_FILES['m_marking_path']['name'];
        $imgbinary      = fread(fopen($m_marking_path, 'r'), filesize($m_marking_path));
        $m_marking_img  = 'data:image/jpeg;base64,'.base64_encode($imgbinary);
        
        $m_marking_name = addslashes($_POST['m_marking_name']);
        
        echo $this->record->create($m_marking_name, $m_marking_img);
        
        array_map( 'unlink', glob('assets/images/marking/*.jpg'));
        array_map( 'unlink', glob('assets/images/marking/*.JPG'));
    }     
    
    function update($m_marking_id=null) {
        $m_marking_img  = '';
        $m_marking_name = addslashes($_POST['m_marking_name']);
        
        if($_FILES['m_marking_path']['name']!=''){
            move_uploaded_file($_FILES['m_marking_path']['tmp_name'],
                'assets/images/marking/' . $_FILES['m_marking_path']['name']);
            $m_marking_path = 'assets/images/marking/' . $_FILES['m_marking_path']['name'];
            $imgbinary      = fread(fopen($m_marking_path, 'r'), filesize($m_marking_path));
            $m_marking_img  = 'data:image/jpeg;base64,'.base64_encode($imgbinary);
        }
        
        echo $this->record->update($m_marking_id, $m_marking_name, $m_marking_img);
        array_map( 'unlink', glob('assets/images/marking/*.jpg'));
        array_map( 'unlink', glob('assets/images/marking/*.JPG'));
    }
        
    function delete(){
        if(!isset($_POST))	
            show_404();

        $m_marking_id = addslashes($_POST['m_marking_id']);
        
        echo $this->record->delete($m_marking_id);
    }
    
}

/* End of file marking.php */
/* Location: ./application/controllers/master/marking.php */