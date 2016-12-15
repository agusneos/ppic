<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekap extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('inquiry/m_rekap','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(54);
    }
    
    function index(){
        $this->load->view('inquiry/rekap/v_dialog_rekap'); 
    } 
        
    function getProces(){
        echo $this->record->getProces();
    }
           
    function showRekapBarang(){
        if (isset($_GET['grid'])){
            echo $this->record->showRekapBarang($_GET['rekap_proses'], $_GET['rekap_tgl_from'], $_GET['rekap_tgl_to']);
        }
        else{
            $this->load->view('inquiry/rekap/v_rekap_barang');
        }
    }
    
    function showRekapBarangDetail(){
        if (isset($_GET['grid'])){
            echo $this->record->showRekapBarangDetail($_GET['rekap_proses'], $_GET['rekap_item'], $_GET['rekap_tgl_from'], $_GET['rekap_tgl_to']);
        }
        else{
            $this->load->view('inquiry/rekap/v_rekap_barang_detail');
        }
    }
    
    function showRekapMesin(){
        if (isset($_GET['grid'])){
            echo $this->record->showRekapMesin($_GET['rekap_proses'], $_GET['rekap_tgl_from'], $_GET['rekap_tgl_to']);
        }
        else{
            $this->load->view('inquiry/rekap/v_rekap_mesin');
        }
    }
        
    function showRekapMesinDetail(){
        if (isset($_GET['grid'])){
            echo $this->record->showRekapMesinDetail($_GET['rekap_proses'], $_GET['rekap_item'], $_GET['rekap_mesin'], $_GET['rekap_tgl_from'], $_GET['rekap_tgl_to']);
        }
        else{
            $this->load->view('inquiry/rekap/v_rekap_mesin_detail');
        }
    }
    
}

/* End of file rekap.php */
/* Location: ./application/controllers/inquiry/rekap.php */