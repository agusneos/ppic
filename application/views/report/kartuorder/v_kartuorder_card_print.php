<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PDF extends FPDF_Ellipse
{    
    function Header()
    {
        $this->AddFont('BAVAND','','BAVAND.php');
        $this->SetFont('BAVAND','',15); 
        $this->Cell(40,7,'Hikari',0,0,'R');
        $this->Ellipse(41.5,8.5,9,4.5);
        $this->SetFont('Times','B',10);
        $this->Cell(128-40,7,'  PT. HIKARI METALINDO PRATAMA',0,0,'L');
        //$this->Image('assets/images/logo.jpg', 30, 5,80,11.2);
        $this->Ln(7);
        $this->SetFont('Arial','B',17);
        $this->Cell(128,7,'KARTU  ORDER',0,0,'C');
        $this->Ln(7);
    }
}

require_once('./assets/qrcode/qrcode.php');

// definisi class
$pdf = new PDF('P','mm','A5'); // A% = 148 x 210
// variable awal
date_default_timezone_set('Asia/Jakarta');
$pdf->SetMargins(10,5,10);
//$pdf->AliasNbPages();
$pdf->SetAutoPageBreak('on', 0); //margin bottom set to 0
//$pdf->SetLineWidth(0.5);

function tgl($date){
    setlocale (LC_TIME, 'INDONESIAN');
    $st = strftime( "%d/%m/%Y", strtotime($date));
    //return strtoupper($st);
    return $st;
}

function baking($data){
    if ($data == 1)
    {
        return 'BAKING';
    }
}

function round_up ( $value, $precision ) { 
    $pow = pow ( 10, $precision ); 
    return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow; 
} 

$height = 7 ;
$font   = 'Arial';
$size   = 10;

foreach($rows->result() as $data)
{
    $pdf->AddPage();
    
    
    
    $pdf->SetLineWidth(1);
    $pdf->Rect(10, 19, 32*4, 162);
    $pdf->Rect(10, 19+($height*4), 32*4, $height);
    $pdf->Rect(10, 19+($height*5), 32*4, $height*2);
    $pdf->Rect(10, 19+($height*11), 32*4, $height);
    $pdf->SetLineWidth(0.2);
    
    $pdf->SetFont($font,'B',$size-1);
    $pdf->Cell(32,$height,'TGL P/O',1,0,'L');
    $pdf->SetFont($font,'',$size);
    $pdf->Cell(32,$height,tgl($data->t_po_header_date),1,0,'C'); //Tanggal po
    $pdf->SetFont($font,'B',$size-1);
    $pdf->Cell(32,$height,'CUSTOMER',1,0,'L');
    $pdf->SetFont($font,'',$size);
    $pdf->Cell(32,$height,$data->t_po_detail_cust,1,0,'C'); // Kode Cust
    $pdf->Ln($height);
    
    $pdf->SetFont($font,'B',$size-1);
    $pdf->Cell(32,$height,'JUMLAH P/O',1,0,'L');
    $pdf->SetFont($font,'',$size);
    $pdf->Cell(32,$height,  number_format($data->t_po_detail_qty, 0, '', '.'),1,0,'C'); //Jumlah PO
    $pdf->SetFont($font,'B',$size-1);
    $pdf->Cell(32,$height,'No. LOT',1,0,'L');
    $pdf->SetFont($font,'',$size);
    $pdf->Cell(32,$height,$data->t_prod_lot.'-'.$data->t_prod_sublot,1,0,'C'); // Nomor lot+sublot
    $pdf->Ln($height);
    
    $pdf->SetFont($font,'B',$size-1);
    $pdf->Cell(32,$height,'No.PO / SPK',1,0,'L');
    $pdf->SetFont($font,'',$size);
    $pdf->Cell(32,$height,$data->t_po_detail_no,1,0,'C'); // Nomor PO
    $pdf->SetFont($font,'B',$size-1);
    $pdf->Cell(32,$height,'Qty Prod. / Lot',1,0,'L');
    $pdf->SetFont($font,'',$size);
    $pdf->Cell(32,$height,number_format($data->t_prod_qty_sum, 0, '', '.'),1,0,'C'); // Total qty per sublot
    $pdf->Ln($height);
    
    $pdf->SetFont($font,'B',$size-1);
    $pdf->Cell(32,$height,'TTL PROD (Pcs)',1,0,'L');
    $pdf->SetFont($font,'',$size);
    $pdf->Cell(32,$height,number_format($data->t_po_detail_prod, 0, '', '.'),1,0,'C'); // Total Produksi
    $pdf->SetFont($font,'B',$size-1);
    $pdf->Cell(32,$height,'JUMLAH KARTU',1,0,'L');
    $pdf->SetFont($font,'',$size);
    $pdf->Cell(32,$height,$data->t_prod_card.' / '.$data->t_prod_card_cnt,1,0,'C'); // Jumlah Kartu
    $pdf->Ln($height);
    
    $pdf->SetFont($font,'B',$size-1);
    $pdf->Cell(96,$height,'JENIS SCREW / UKURAN / PLATING',1,0,'C');
    $pdf->Cell(32,$height,'No. SPECIAL',1,0,'C');
    $pdf->Ln($height);
    
    $pdf->SetFont($font,'B',$size+6); // Size Font Item Name
    //$pdf->Cell(96,$height*2,$data->m_item_name,1,0,'C'); // Item Name
    $pdf->MultiCell(96, $height, utf8_decode($data->m_item_name), 0, 'C');
    $pdf->SetXY(96+10, ($height*8)-2);
    $pdf->Cell(32,$height*2,baking($data->m_item_baking),1,0,'C'); // BAKING
    $pdf->Ln($height*2);
    
    $pdf->SetFont($font,'B',$size-1);
    $pdf->Cell(25,$height,'No. COIL',1,0,'L');
    $pdf->Cell(39,$height,'',1,0,'C');
    $pdf->SetFont($font,'',$size*0.75);
    $pdf->Cell(32,$height*2,$data->t_prod_id,1,0,'C'); //ID Kartu
    $qrcode = new QRcode($data->t_prod_id, 'H'); // error level : L, M, Q, H
    $qrcode->displayFPDF($pdf, 115, 68, 14);
    $pdf->Cell(32,$height*2,'',1,0,'C'); //bARCODE
    $pdf->Ln($height);
    
    $pdf->SetFont($font,'B',$size-1);
    $pdf->Cell(25,$height,'TYPE WIRE',1,0,'L');
    $pdf->SetFont($font,'',$size);
    $pdf->Cell(39,$height,$data->m_item_bom_name,1,0,'C'); // Tipe Wire
    $pdf->Ln($height);
    
    $pdf->SetFont($font,'B',$size-1);
    $pdf->Cell(32,$height,'TOTAL WIRE(Kg)',1,0,'L');
    $pdf->SetFont($font,'',$size);
    $pdf->Cell(32,$height,number_format(round_up(($data->t_po_detail_prod*$data->m_bom_qty)/1000,2),0, ',', '.'),1,0,'C'); // Total Wire
    $pdf->SetFont($font,'B',$size-1);
    $pdf->Cell(32,$height,'ISI 1 BOX (Pcs)',1,0,'L');
    $pdf->SetFont($font,'',$size);
    $pdf->Cell(32,$height,number_format($data->t_prod_qty, 0, '', '.'),1,0,'C'); // Qty Per Box
    $pdf->Ln($height);
    
    $pdf->SetFont($font,'B',$size-1);
    $pdf->Cell(32,$height,'SCHEDULE PROD.',1,0,'L');
    $pdf->SetFont($font,'',$size);
    $pdf->Cell(32,$height,tgl($data->t_po_detail_prod_date),1,0,'C'); // Schedule Prod
    $pdf->SetFont($font,'B',$size-1);
    $pdf->Cell(32,$height,'TGL DELIVERY',1,0,'L');
    $pdf->SetFont($font,'',$size);
    $pdf->Cell(32,$height,tgl($data->t_po_detail_delv_date),1,0,'C'); // Tgl Delivery
    $pdf->Ln($height);
    
    $pdf->SetFont($font,'B',$size-1);
    $pdf->Cell(25,$height,'PROSES',1,0,'C');
    $pdf->Cell(14,$height,'Std. Kg.',1,0,'C');
    $pdf->Cell(25,$height,'No.MESIN',1,0,'C');
    $pdf->Cell(16,$height,'Tgl. Prod.',1,0,'C');
    $pdf->Cell(16,$height,'Act. Kg.',1,0,'C');
    $pdf->Cell(16,$height,'OPR',1,0,'C');
    $pdf->Cell(16,$height,'PARAF',1,0,'C');
    $pdf->Ln($height);
     
    foreach($detail->result() as $proses)
    {
        $pdf->SetFont($font,'',$size);
        $pdf->Cell(25,$height-0.5,$proses->m_process_cat_name,1,0,'C'); // Nama Proses
        $pdf->Cell(14,$height-0.5, number_format(round_up(($proses->m_process_weight*$data->t_prod_qty)/1000,2),2, ',', '.'),1,0,'C'); // Berat Proses
        $pdf->Cell(25,$height-0.5,'',1,0,'C');
        $pdf->Cell(16,$height-0.5,'',1,0,'C');
        $pdf->Cell(16,$height-0.5,'',1,0,'C');
        $pdf->Cell(16,$height-0.5,'',1,0,'C');
        $pdf->Cell(16,$height-0.5,'',1,0,'C');
        $pdf->Ln($height-0.5);
    }
    
    $pdf->SetY(182);
    $pdf->SetFont($font,'B',$size);
    $pdf->Cell(32,$height-2,'PERHATIAN :',0,0,'L');
    $pdf->Ln($height);
    
   // $pdf->Image($data->m_marking_path, 13, 181+$height,18,18);
    $pdf->Image($data->m_marking_img, 13, 181+$height,18,18, 'JPEG');
    
    $pdf->SetXY(42,182);
    $pdf->MultiCell(96,$height-2,$data->m_item_note,0,'L'); // Catatan
    
    
    
}


$pdf->Output("Kartu Order.pdf","I");

// End of file v_kartuorder_card_print.php 
// Location: ./application/views/report/kartuorder/v_kartuorder_card_print.php