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
            if ($_GET['rekap_periode']==1){
                echo $this->record->showRekapBarangHarian($_GET['rekap_proses'], $_GET['rekap_tgl']);
            }
            elseif ($_GET['rekap_periode']==2) {
                echo $this->record->showRekapBarangMingguan($_GET['rekap_proses'], $_GET['rekap_tgl']);
            }
            elseif ($_GET['rekap_periode']==3) {
                echo $this->record->showRekapBarangBulanan($_GET['rekap_proses'], $_GET['rekap_tgl']);
            }
            else {
                echo $this->record->showRekapBarangTahunan($_GET['rekap_proses'], $_GET['rekap_tgl']);
            }
        }
        else{
            $this->load->view('inquiry/rekap/v_rekap_barang');
        }
    }
    
    function showRekap(){
        if (isset($_GET['grid'])){
            if ($_GET['lot']==''){
                echo $this->record->showRekapItem($_GET['item']);
            }
            else{
                echo $this->record->showRekap($_GET['lot']);
            }            
        }
        else{
            $this->load->view('inquiry/rekap/v_rekap');
        }
    }
    
    function showRekapDetail(){
        if (isset($_GET['grid'])){
            if ($_GET['lot']==''){
                echo $this->record->showRekapItemDetail($_GET['item'], $_GET['proc']);
            }
            else{
                echo $this->record->showRekapDetail($_GET['lot'], $_GET['proc']);
            }            
        }
        else{
            $this->load->view('inquiry/rekap/v_rekap_detail');
        }
    }
    
}

/* End of file rekap.php */
/* Location: ./application/controllers/inquiry/rekap.php */