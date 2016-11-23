<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Prodexcel extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('report/m_prodexcel','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(39);
    }
    
    function index(){
        $this->load->view('report/prodexcel/v_dialog_prodexcel.php');
    }
    
    function exportExcel(){
        $proc_id    = $_GET['proc'];
        $start      = $_GET['start'];
        $end        = $_GET['end'];
        
        $data = $this->record->exportExcel($proc_id, $start, $end);
        $this->load->view('report/prodexcel/v_prodexcel.php',$data);
    }
    
    function getProces(){
        echo $this->record->getProces();
    }
}
?>
