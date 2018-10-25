<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subout extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('transaksi/m_subout','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(56);
        //$this->load->library('FPDF_Ellipse');
        //$this->load->library('PHPExcel');
    }
        
    function headIndex(){
        if (isset($_GET['grid'])){
            echo $this->record->headIndex();   
        }
        else {
            $this->load->view('transaksi/subout/v_subout');  
        }
    }
    
    function headCreate(){
        if(!isset($_POST))	
            show_404();
        
        $t_sub_in_head_no       = addslashes($_POST['t_sub_in_head_no']);
        $t_sub_in_head_vendor   = addslashes($_POST['t_sub_in_head_vendor']); 
        $t_sub_in_head_proc     = addslashes($_POST['t_sub_in_head_proc']);
        $t_sub_in_head_date     = addslashes($_POST['t_sub_in_head_date']);
        $t_sub_in_head_opr      = addslashes($_POST['t_sub_in_head_opr']);
        $t_sub_in_head_unit     = addslashes($_POST['t_sub_in_head_unit']);
        $t_sub_in_head_retur    = addslashes($_POST['t_sub_in_head_retur']);
                           
        echo $this->record->headCreate($t_sub_in_head_no, $t_sub_in_head_vendor, $t_sub_in_head_proc,
                                   $t_sub_in_head_date, $t_sub_in_head_opr, $t_sub_in_head_unit, $t_sub_in_head_retur);
    }
    
    function headUpdate($t_sub_in_head_id=null){
        if(!isset($_POST))	
            show_404();

        $t_sub_in_head_no       = addslashes($_POST['t_sub_in_head_no']);
        $t_sub_in_head_vendor   = addslashes($_POST['t_sub_in_head_vendor']); 
        $t_sub_in_head_proc     = addslashes($_POST['t_sub_in_head_proc']);
        $t_sub_in_head_date     = addslashes($_POST['t_sub_in_head_date']);
        $t_sub_in_head_opr      = addslashes($_POST['t_sub_in_head_opr']);
        $t_sub_in_head_unit     = addslashes($_POST['t_sub_in_head_unit']);
        $t_sub_in_head_retur    = addslashes($_POST['t_sub_in_head_retur']);
        
        echo $this->record->headUpdate($t_sub_in_head_id, $t_sub_in_head_no, $t_sub_in_head_vendor, $t_sub_in_head_proc,
                                       $t_sub_in_head_date, $t_sub_in_head_opr, $t_sub_in_head_unit, $t_sub_in_head_retur);
    }
    
    function headDelete(){
        if(!isset($_POST))	
            show_404();

        $t_sub_in_head_id          = addslashes($_POST['t_sub_in_head_id']);
        
        echo $this->record->headDelete($t_sub_in_head_id);
        
    }
    
    function getVendor() {
        echo $this->record->getVendor();
    }
    
    function getProc() {
        echo $this->record->getProc();
    }
    
    function getOpr() {
        echo $this->record->getOpr();
    }
    
    //--DETAIL--//
    function detailIndex(){
        $pecah              = explode('-', $_GET['nilai']);
        $head_id       = $pecah[0];
        $proc_id       = $pecah[1];
        
        if (isset($_GET['grid'])) {
            echo $this->record->detailIndex($head_id, $proc_id);   
        }
        else {
            $this->load->view('transaksi/subout/v_subout');  
        }
    }
    
    function detailCreate() {        
        if(!isset($_POST))	
            show_404();
        
        $id         = addslashes($_POST['t_subout_detail_kartu']);
        $mcid       = 7; //PL1-1
        $procid     = addslashes($_POST['t_subout_detail_procid']);
        $proc       = addslashes($_POST['t_subout_detail_proc']);
        $proc_tbl   = addslashes($_POST['t_subout_detail_proc_tbl']);
        $nik        = addslashes($_POST['t_subout_detail_nik']);
        $head       = addslashes($_POST['t_subout_detail_head']);
                           
        echo $this->record->detailCreate($head, $id, $procid, $proc, $proc_tbl, $nik, $mcid);
        
    }
}

/* End of file po.php */
/* Location: ./application/controllers/transaksi/subout.php */