<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kartuorder extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('report/m_kartuorder','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(55);
        $this->load->library('FPDF_Ellipse');
    }
    
    function index(){
        $this->load->view('report/kartuorder/v_dialog_kartuorder');
    }
    
    function getLot(){
        echo $this->record->getLot();
    }
    
    function lot(){
        if (isset($_GET['grid'])){
            echo $this->record->lot($_GET['nilailot']);
        }
        else{
            $this->load->view('report/kartuorder/v_kartuorder');
        }
    }
    
    // PRINT CARD //
    function printAll($id=null){
        define('FPDF_FONTPATH',$this->config->item('fonts_path'));
        $data = $this->record->printAll($id);
        $this->load->view('report/kartuorder/v_kartuorder_card_print',$data);
    }
    
    function printSublot(){
        define('FPDF_FONTPATH',$this->config->item('fonts_path'));
        $data= $this->record->printSublot($_GET['lot'], $_GET['sublot']);
        $this->load->view('report/kartuorder/v_kartuorder_card_print',$data);
    }
    
    function printSelected($id=null){
        define('FPDF_FONTPATH',$this->config->item('fonts_path'));
        $data = $this->record->printSelected($id);
        $this->load->view('report/kartuorder/v_kartuorder_card_print',$data);
    }
}
?>
