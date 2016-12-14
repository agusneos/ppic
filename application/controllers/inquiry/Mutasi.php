<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mutasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('inquiry/m_mutasi','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(52);
    }
    
    function index(){
        $this->load->view('inquiry/mutasi/v_dialog_mutasi.php'); 
    } 
    
    
    function getItem(){
        echo $this->record->getItem();
    }
    
    function getLot($m_item_id=null){
        echo $this->record->getLot($m_item_id);
    }
    
    function showMutasi(){
        if (isset($_GET['grid'])){
            if ($_GET['lot']==''){
                echo $this->record->showMutasiItem($_GET['item']);
            }
            else{
                echo $this->record->showMutasi($_GET['lot']);
            }            
        }
        else{
            $this->load->view('inquiry/mutasi/v_mutasi');
        }
    }
    
    function showMutasiDetail(){
        if (isset($_GET['grid'])){
            if ($_GET['lot']==''){
                echo $this->record->showMutasiItemDetail($_GET['item'], $_GET['proc']);
            }
            else{
                echo $this->record->showMutasiDetail($_GET['lot'], $_GET['proc']);
            }            
        }
        else{
            $this->load->view('inquiry/mutasi/v_mutasi_detail');
        }
    }
    
}

/* End of file mutasi.php */
/* Location: ./application/controllers/inquiry/mutasi.php */