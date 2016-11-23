<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/m_item','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(10);
    }
    
    function index(){
        if (isset($_GET['grid'])){
            echo $this->record->index();   
        }
        else {
            $this->load->view('master/item/v_item');  
        }
    } 
    
    function create(){
        if(!isset($_POST))	
            show_404();
        
        $m_item_id          = addslashes($_POST['m_item_id']);
        $m_item_name        = addslashes($_POST['m_item_name']); 
        $m_item_net_weight  = addslashes($_POST['m_item_net_weight']);
        $m_item_ext_id      = addslashes($_POST['m_item_ext_id']);
        $m_item_qty_box     = addslashes($_POST['m_item_qty_box']);
        $m_item_note        = addslashes($_POST['m_item_note']);
        $m_item_mark        = addslashes($_POST['m_marking_id']);
        $m_item_baking      = addslashes($_POST['m_item_baking']);
                           
        echo $this->record->create($m_item_id, $m_item_name, $m_item_net_weight, $m_item_ext_id,
                                 $m_item_qty_box, $m_item_note, $m_item_mark, $m_item_baking);
        
    }     
    
    function update($m_item_id=null){
        if(!isset($_POST))	
            show_404();

        $m_item_name        = addslashes($_POST['m_item_name']); 
        $m_item_net_weight  = addslashes($_POST['m_item_net_weight']);
        $m_item_ext_id      = addslashes($_POST['m_item_ext_id']);
        $m_item_qty_box     = addslashes($_POST['m_item_qty_box']);
        $m_item_note        = addslashes($_POST['m_item_note']);
        $m_item_mark        = addslashes($_POST['m_marking_id']);
        $m_item_baking      = addslashes($_POST['m_item_baking']);
        
        echo $this->record->update($m_item_id, $m_item_name, $m_item_net_weight, $m_item_ext_id,
                                 $m_item_qty_box, $m_item_note, $m_item_mark, $m_item_baking);
        
    }
        
    function delete(){
        if(!isset($_POST))	
            show_404();

        $m_item_id          = addslashes($_POST['m_item_id']);
        
        echo $this->record->delete($m_item_id);
        
    }
    
    //Fungsi Upload Dipending
    function upload() {
        move_uploaded_file($_FILES["filea"]["tmp_name"],
                "assets/temp_upload/" . $_FILES["filea"]["name"]);
        $this->load->library('excel_reader');
        $this->excel_reader->setOutputEncoding('CP1251');
        $this->excel_reader->read('assets/temp_upload/' . $_FILES["filea"]["name"]);
        error_reporting(E_ALL ^ E_NOTICE);
        
        // Get the contents of the first worksheet
        $data = $this->excel_reader->sheets[0];
        
        // jumlah baris
        $baris  = $data['numRows'];
        $ok = 0;
        $ng = 0;
        
        for ($i = 1; $i <= $baris; $i++)
        {
           $item_id             = $data['cells'][$i][1];
           $item_name           = $data['cells'][$i][2];
           $item_weight         = $data['cells'][$i][3];
           $item_weight_turret  = $data['cells'][$i][4];
           
           $query   = $this->record->upload($item_id, $item_name, $item_weight, $item_weight_turret);
           if ($query)
           {
               $ok++;
           }
           else
           {
               $ng++;
           }
        }
        unlink('assets/temp_upload/' . $_FILES["filea"]["name"]);
        echo json_encode(array('success'=> true,
                                'total' => 'Total Data: '.($baris),
                                'ok'    => 'Data OK: '.$ok,
                                'ng'    => 'Data NG: '.$ng));
    }
    
    //// Validasi BOM dan ROute ///
    
    function validBomRoute($m_item_id=null){
        if(!isset($_POST))	
            show_404();
        
        $m_item_bom_stat     = addslashes($_POST['m_item_bom_stat']);
        $m_item_route_stat   = addslashes($_POST['m_item_route_stat']);
                           
        echo $this->record->validBomRoute($m_item_id, $m_item_bom_stat, $m_item_route_stat);
    }
    
    function getMarking() {
        echo $this->record->getMarking();
    }
    //// PROSES ////
    function proses(){            
        if (isset($_GET['grid']))          
            echo $this->record->proses($_GET['item']); 
        else             
            $this->load->view('master/item/v_item_proses');    
    }
    
    function prosesCreate($m_process_id=null){
        if(!isset($_POST))	
            show_404();
        
        $m_process_seq      = addslashes($_POST['m_process_seq']); 
        $m_process_name     = addslashes($_POST['m_process_cat_name']);
        $m_process_loc      = addslashes($_POST['m_process_loc']);
        $m_process_weight   = addslashes($_POST['m_process_weight']);
                           
        echo $this->record->prosesCreate($m_process_id, $m_process_seq, $m_process_name, $m_process_loc, $m_process_weight);
        
    }
    
    function prosesUpdate($id=null){
        if(!isset($_POST))	
            show_404();
        
        $pecah              = explode('-', $id);
        $m_process_id       = $pecah[0];
        $m_process_seq      = $pecah[1];
        
        $m_process_name     = addslashes($_POST['m_process_cat_name']);
        $m_process_loc      = addslashes($_POST['m_process_loc']);
        $m_process_weight   = addslashes($_POST['m_process_weight']);
                           
        echo $this->record->prosesUpdate($m_process_id, $m_process_seq, $m_process_name, $m_process_loc, $m_process_weight);
        
    }
    
    function prosesDelete(){
        if(!isset($_POST))	
            show_404();

        $m_process_id   = addslashes($_POST['m_process_id']);
        $m_process_seq  = addslashes($_POST['m_process_seq']);
        
        echo $this->record->prosesDelete($m_process_id, $m_process_seq);
        
    }
    
    function getProses(){
        echo $this->record->getProses();
    }
    
    function getProcSeq(){
        $m_process_id = addslashes($_POST['m_process_id']);
        $query = $this->record->getProcSeq($m_process_id);
        foreach ($query->result() as $data)
        {
            echo json_encode(array('seq'=>$data->m_process_seq));
        }
    }
    
    function getItemProses(){
        echo $this->record->getItemProses();
    }
    
    function prosesCopy($m_process_id=null){
        if(!isset($_POST))	
            show_404();
        
        $copy_item  = addslashes($_POST['copy_item_proses']);
        echo $this->record->prosesCopy($m_process_id, $copy_item);
    }
    
    //// BOM ////
    function bom(){
        if (isset($_GET['grid']))
            echo $this->record->bom($_GET['item']);
        else
            $this->load->view('master/item/v_item_bom');
    }
    
    function bomCreate($m_bom_id=null){
        if(!isset($_POST))	
            show_404();
                
        $m_item_bom_cat    = addslashes($_POST['m_item_bom_cat']);
        $m_item_bom_name    = addslashes($_POST['m_item_bom_name']);
        $m_bom_qty          = addslashes($_POST['m_bom_qty']);
                           
        echo $this->record->bomCreateCheck($m_bom_id, $m_item_bom_cat, $m_item_bom_name, $m_bom_qty);
        
    }
    
    function bomUpdate($id=null){
        if(!isset($_POST))	
            show_404();
        
        $pecah          = explode('-', $id);
        $m_bom_id       = $pecah[0];
        $m_bom_item     = $pecah[1];
        
        $m_bom_qty      = addslashes($_POST['m_bom_qty']);
                           
        echo $this->record->bomUpdate($m_bom_id, $m_bom_item, $m_bom_qty);
        
    }
    
    function bomDelete() {
        if(!isset($_POST))	
            show_404();

        $m_bom_id       = addslashes($_POST['m_bom_id']);
        $m_bom_item     = addslashes($_POST['m_bom_item']);
        
        echo $this->record->bomDelete($m_bom_id, $m_bom_item);
        
    }
    
    function enumBomCat(){
        echo $this->record->enumField('m_item_bom', 'm_item_bom_cat');
    }
    
    function getBomItem($cat=null){
        echo $this->record->getBomItem($cat);
    }
    
    function getBomQty(){
        $m_item_bom_id = addslashes($_POST['m_item_bom_id']);
        $query = $this->record->getBomQty($m_item_bom_id);
        foreach ($query->result() as $data){
            echo json_encode(array('success'=>true,'qty'=>$data->m_item_bom_qty));
        }
    }
    
    function getItemBom(){
        echo $this->record->getItemBom();
    }
    
    function bomCopy($m_process_id=null){
        if(!isset($_POST))	
            show_404();
        
        $copy_item  = addslashes($_POST['copy_item_bom']);
        echo $this->record->bomCopy($m_process_id, $copy_item);
    }
}

/* End of file item.php */
/* Location: ./application/controllers/master/item.php */