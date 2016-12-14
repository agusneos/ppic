<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produksi extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('transaksi/m_produksi','record');
        //$this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        //$this->auth->menu(37);
        //$this->load->library('FPDF_Ellipse');
        //$this->load->library('PHPExcel');
    }
        
    function getItem(){
        echo $this->record->getItem();
    }
    
}

/* End of file po.php */
/* Location: ./application/controllers/transaksi/produksi.php */