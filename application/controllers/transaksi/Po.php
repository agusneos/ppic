<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Po extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi/m_po','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(37);
        $this->load->library('FPDF_Ellipse');
        $this->load->library('PHPExcel');
    }
    
    function index(){
        if (isset($_GET['grid'])){
            echo $this->record->index();   
        }
        else {
            $this->load->view('transaksi/po/v_po');  
        }
    } 
    
    function create(){
        if(!isset($_POST))	
            show_404();
        
        $t_po_header_no     = addslashes($_POST['t_po_header_no']);
        $t_po_header_cust   = addslashes($_POST['t_po_header_cust']); 
        $t_po_header_date   = addslashes($_POST['t_po_header_date']);
                           
        echo $this->record->create($t_po_header_no, $t_po_header_cust, $t_po_header_date);
        
    }
    
    function update(){
        if(!isset($_POST))	
            show_404();

        $t_po_header_no_old = addslashes($_POST['t_po_header_no_old']);
        $t_po_header_no     = addslashes($_POST['t_po_header_no']);
        $t_po_header_cust   = addslashes($_POST['t_po_header_cust']); 
        $t_po_header_date   = addslashes($_POST['t_po_header_date']);
        
        echo $this->record->update($t_po_header_no_old, $t_po_header_no, $t_po_header_cust, $t_po_header_date);
        
    }
        
    function delete(){
        if(!isset($_POST))	
            show_404();

        $t_po_header_no          = addslashes($_POST['t_po_header_no']);
        
        echo $this->record->delete($t_po_header_no);
        
    }
    
    
    //--DETAIL--//
    function detailIndex(){
        if (isset($_GET['grid'])) {
            echo $this->record->detailIndex($_GET['nilai']);   
        }
        else {
            $this->load->view('transaksi/po/v_po');  
        }
    }
    
    function detailCreate() {
        if(!isset($_POST))	
            show_404();
        
        $t_po_detail_no             = addslashes($_POST['t_po_detail_no']);
        $t_po_detail_date           = addslashes($_POST['t_po_detail_date']); 
        $t_po_detail_lot_no         = addslashes($_POST['t_po_detail_lot_no']);
        $t_po_detail_cust           = addslashes($_POST['t_po_detail_cust']);
        $t_po_detail_item           = addslashes($_POST['t_po_detail_item']);
        $t_po_detail_qty            = addslashes($_POST['t_po_detail_qty']);
        $t_po_detail_prod           = addslashes($_POST['t_po_detail_prod']);
        $t_po_detail_prod_date      = addslashes($_POST['t_po_detail_prod_date']);        
        $t_po_detail_delv_date      = addslashes($_POST['t_po_detail_delv_date']);        
        $t_po_detail_prod_weight    = addslashes($_POST['t_po_detail_prod_weight']);
                           
        echo $this->record->detailCreate($t_po_detail_no, $t_po_detail_date, $t_po_detail_lot_no, $t_po_detail_cust,
                                         $t_po_detail_item, $t_po_detail_qty, $t_po_detail_prod, $t_po_detail_prod_date,
                                         $t_po_detail_delv_date, $t_po_detail_prod_weight);
        
    }
    
    function detailUpdate($t_po_detail_lot_no=null){
        if(!isset($_POST))	
            show_404();

        $t_po_detail_cust           = addslashes($_POST['t_po_detail_cust']);
        $t_po_detail_item           = addslashes($_POST['t_po_detail_item']);
        $t_po_detail_qty            = addslashes($_POST['t_po_detail_qty']);
        $t_po_detail_prod           = addslashes($_POST['t_po_detail_prod']);
        $t_po_detail_prod_date      = addslashes($_POST['t_po_detail_prod_date']);        
        $t_po_detail_delv_date      = addslashes($_POST['t_po_detail_delv_date']);        
        $t_po_detail_prod_weight    = addslashes($_POST['t_po_detail_prod_weight']);
                           
        echo $this->record->detailUpdate($t_po_detail_lot_no, $t_po_detail_cust, $t_po_detail_item, $t_po_detail_qty, 
                                         $t_po_detail_prod, $t_po_detail_prod_date, $t_po_detail_delv_date,
                                         $t_po_detail_prod_weight);
    }
    
    function detailDelete(){
        if(!isset($_POST))	
            show_404();

        $t_po_detail_lot_no     = addslashes($_POST['t_po_detail_lot_no']);
        
        echo $this->record->detailDelete($t_po_detail_lot_no);
        
    }
    
    function convertDate($date){
        if($date==''){
            return '0000-00-00';
        }
        else{
            $tgl_asli = str_replace('/', '-', $date);
            $exp_tgl_asli = explode('-', $tgl_asli);  
            //$exp_tahun = explode(' ', isset($exp_tgl_asli[2]));
            $tgl_sql = $exp_tgl_asli[2].'-'.$exp_tgl_asli[0].'-'.$exp_tgl_asli[1]; // pERUBAHAN FORMAT TANGGAL KE MYSQL
            return $tgl_sql;
        }
    }
    
    function detailUpload(){
        move_uploaded_file($_FILES["detailPo"]["tmp_name"],
                "assets/temp_upload/" . $_FILES["detailPo"]["name"]);
        ini_set('memory_limit', '-1');
        $inputFileName = 'assets/temp_upload/' . $_FILES["detailPo"]["name"];
        try {
            $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
        }
        catch(Exception $e) {
            die('Error loading file :' . $e->getMessage());
        }
        $worksheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        $baris  = count($worksheet);
        $ok     = 0;
        $ng     = 0;
        
        for ($i = 2; $i < ($baris+1); $i++){            
            $t_po_detail_no             = $worksheet[$i]['A'];
            $t_po_detail_item           = $worksheet[$i]['B'];
            $t_po_detail_date           = $this->convertDate($worksheet[$i]['C']);
            $t_po_detail_cust           = $worksheet[$i]['D'];
            $t_po_detail_qty            = $worksheet[$i]['E'];
            $t_po_detail_prod           = $worksheet[$i]['F'];
            $t_po_detail_lot_no         = $worksheet[$i]['G'];
            $t_po_detail_prod_date      = $this->convertDate($worksheet[$i]['I']);
            $t_po_detail_delv_date      = $this->convertDate($worksheet[$i]['J']); 
            $t_po_detail_prod_weight    = $worksheet[$i]['M'];
            
            $query = $this->record->detailUpload($t_po_detail_no, $t_po_detail_item, $t_po_detail_date,
                                                 $t_po_detail_cust, $t_po_detail_qty, $t_po_detail_prod,
                                                 $t_po_detail_lot_no, $t_po_detail_prod_date,
                                                 $t_po_detail_delv_date, $t_po_detail_prod_weight);
            if ($query){
                $ok++;
            }
            else{
                $ng++;
            }
        }
        unlink('assets/temp_upload/' . $_FILES["detailPo"]["name"]);
        echo json_encode(array('success'=>true,
                                'total'=>'Total Data: '.($baris),
                                'ok'=>'Data OK: '.$ok,
                                'ng'=>'Data NG: '.$ng));
        
    }
        
    function getItem(){
        echo $this->record->getItem();
    }
    
    function getCust(){
        echo $this->record->getCust();
    }
    
    function calcProdQty(){
        $m_item_id = addslashes($_POST['m_item_id']);
        $query = $this->record->calcProdQty($m_item_id);
        foreach ($query->result() as $data){
            echo json_encode(array('success'=>true,'qty'=>$data->m_item_qty_box));
        }
    }
    
    function detailGenerateProd(){
        if(!isset($_POST))	
            show_404();
        
        $item_id    = addslashes($_POST['item_id']);
        $prod_qty   = addslashes($_POST['prod_qty']);
        $lot        = addslashes($_POST['lot']);
        
        echo $this->record->detailGenerateProd($item_id, $prod_qty, $lot);

    }
    
    // LOT //
    function lot(){
        if (isset($_GET['grid'])){
            echo $this->record->lot($_GET['nilailot']);
        }
        else{
            $this->load->view('transaksi/po/v_po');
        }
    }
    
    // PRINT CARD //
    function printAll($id=null){
        define('FPDF_FONTPATH',$this->config->item('fonts_path'));
        $data = $this->record->printAll($id);
        $this->load->view('transaksi/po/v_po_card_print.php',$data);
    }
    
    function printSublot(){
        define('FPDF_FONTPATH',$this->config->item('fonts_path'));
        $data= $this->record->printSublot($_GET['lot'], $_GET['sublot']);
        $this->load->view('transaksi/po/v_po_card_print.php',$data);
    }
    
    function printSelected($id=null){
        define('FPDF_FONTPATH',$this->config->item('fonts_path'));
        $data = $this->record->printSelected($id);
        $this->load->view('transaksi/po/v_po_card_print.php',$data);
    }
}

/* End of file po.php */
/* Location: ./application/controllers/transaksi/po.php */