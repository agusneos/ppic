<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_dashboard','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        //$this->auth->menu();
    }
    
    function index(){
        if (isset($_GET['grid'])){
            echo $this->record->index();   
        }
        else{
            $this->load->view('v_dashboard');   
        }
    }
    
    function willindex(){
        if (isset($_GET['grid'])){
            echo $this->record->willindex(); 
        }
        else{
            $this->load->view('v_dashboard');
        }
    }

}