<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_subout extends CI_Model
{    
    static $table1  = 'view_t_proc'; //t_process
    static $table2  = 't_prod';
    static $table3  = 't_po_detail';
    static $table4  = 'm_process';
    static $table5  = 'm_machine';
    static $table6  = 'm_process_cat';
    static $table7  = 't_sub_in_head';
    static $table8  = 'm_vend';
    static $table9  = 'm_operator';
    static $table10 = 't_sub_in_line';
    static $table11 = 'm_item';

    function __construct() {
        parent::__construct();
        //$this->load->helper('database'); // Digunakan untuk memunculkan data Enum
    }
    
    function round_up ( $value, $precision ) { 
        $pow = pow ( 10, $precision ); 
        return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow; 
    }
    
    function headIndex()
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 't_sub_in_head_no';
        $order  = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        
        $filterRules = isset($_POST['filterRules']) ? ($_POST['filterRules']) : '';
	$cond = '1=1';
	if (!empty($filterRules)){
            $filterRules = json_decode($filterRules);
            //print_r ($filterRules);
            foreach($filterRules as $rule){
                $rule = get_object_vars($rule);
                $field = $rule['field'];
                $op = $rule['op'];
                $value = $rule['value'];
                if (!empty($value)){
                    if ($op == 'contains'){
                        $cond .= " and ($field like '%$value%')";
                    } else if ($op == 'beginwith'){
                        $cond .= " and ($field like '$value%')";
                    } else if ($op == 'endwith'){
                        $cond .= " and ($field like '%$value')";
                    } else if ($op == 'equal'){
                        $cond .= " and $field = $value";
                    } else if ($op == 'notequal'){
                        $cond .= " and $field != $value";
                    } else if ($op == 'less'){
                        $cond .= " and $field < $value";
                    } else if ($op == 'lessorequal'){
                        $cond .= " and $field <= $value";
                    } else if ($op == 'greater'){
                        $cond .= " and $field > $value";
                    } else if ($op == 'greaterorequal'){
                        $cond .= " and $field >= $value";
                    } 
                }
            }
	}
        
        $this->db->select('t_sub_in_head.*, m_vend_name, m_process_cat_name, m_operator_name,
                           m_operator_nik, m_process_cat_table, m_process_cat_id');
        $this->db->where($cond, NULL, FALSE);
        $this->db->join(self::$table8, 't_sub_in_head_vendor=m_vend_id', 'left')
                 ->join(self::$table6, 't_sub_in_head_proc=m_process_cat_id', 'left')
                 ->join(self::$table9, 't_sub_in_head_opr=m_operator_nik', 'left');
        $total  = $this->db->count_all_results(self::$table7);
                
        $this->db->select('t_sub_in_head.*, m_vend_name, m_process_cat_name, m_operator_name,
                           m_operator_nik, m_process_cat_table, m_process_cat_id');
        $this->db->where($cond, NULL, FALSE);
        $this->db->join(self::$table8, 't_sub_in_head_vendor=m_vend_id', 'left')
                 ->join(self::$table6, 't_sub_in_head_proc=m_process_cat_id', 'left')
                 ->join(self::$table9, 't_sub_in_head_opr=m_operator_nik', 'left');
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $query  = $this->db->get(self::$table7);
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }
 
        $result = array();
	$result['total'] = $total;
	$result['rows'] = $data;
        
        return json_encode($result);          
    }
    
    function headCreate($t_sub_in_head_no, $t_sub_in_head_vendor, $t_sub_in_head_proc,
                        $t_sub_in_head_date, $t_sub_in_head_opr, $t_sub_in_head_unit, $t_sub_in_head_retur){
        $query = $this->db->insert(self::$table7,array(
            't_sub_in_head_no'      => $t_sub_in_head_no,
            't_sub_in_head_vendor'  => $t_sub_in_head_vendor,
            't_sub_in_head_proc'    => $t_sub_in_head_proc,
            't_sub_in_head_date'    => $t_sub_in_head_date,
            't_sub_in_head_opr'     => $t_sub_in_head_opr,
            't_sub_in_head_unit'    => $t_sub_in_head_unit,
            't_sub_in_head_retur'   => $t_sub_in_head_retur
        ));
        if($query){
            return json_encode(array('success'=>true));
        }
        else {
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        } 
        
    }
    
    function headUpdate($t_sub_in_head_id, $t_sub_in_head_no, $t_sub_in_head_vendor, $t_sub_in_head_proc,
                        $t_sub_in_head_date, $t_sub_in_head_opr, $t_sub_in_head_unit, $t_sub_in_head_retur){
        $this->db->where('t_sub_in_head_id', $t_sub_in_head_id);
        $query = $this->db->update(self::$table7,array(
            't_sub_in_head_no'      => $t_sub_in_head_no,
            't_sub_in_head_vendor'  => $t_sub_in_head_vendor,
            't_sub_in_head_proc'    => $t_sub_in_head_proc,
            't_sub_in_head_date'    => $t_sub_in_head_date,
            't_sub_in_head_opr'     => $t_sub_in_head_opr,
            't_sub_in_head_unit'    => $t_sub_in_head_unit,
            't_sub_in_head_retur'   => $t_sub_in_head_retur
        ));
        if($query){
            return json_encode(array('success'=>true));
        }
        else {
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        } 
        
    }
    
    function headDelete($t_sub_in_head_id){
        $query = $this->db->delete(self::$table7, array('t_sub_in_head_id' => $t_sub_in_head_id));
        if($query){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
    
    function getVendor(){
        $this->db->select('m_vend_id, m_vend_name');
        $query  = $this->db->get(self::$table8);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    
    function getProc(){
        $this->db->select('m_process_cat_id, m_process_cat_name');
        $query  = $this->db->get(self::$table6);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    
    function getOpr(){
        $this->db->select('m_operator_nik, m_operator_name');
        $query  = $this->db->get(self::$table9);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    
    ////////////// LINE DETAIL ///////
    function detailIndex($head_id=null, $proc_id=null) {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 't_sub_in_line_id';
        $order  = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        
        $filterRules = isset($_POST['filterRules']) ? ($_POST['filterRules']) : '';
	$cond = '1=1';
	if (!empty($filterRules)){
            $filterRules = json_decode($filterRules);
            //print_r ($filterRules);
            foreach($filterRules as $rule){
                $rule = get_object_vars($rule);
                $field = $rule['field'];
                $op = $rule['op'];
                $value = $rule['value'];
                if (!empty($value)){
                    if ($op == 'contains'){
                        $cond .= " and ($field like '%$value%')";
                    } else if ($op == 'beginwith'){
                        $cond .= " and ($field like '$value%')";
                    } else if ($op == 'endwith'){
                        $cond .= " and ($field like '%$value')";
                    } else if ($op == 'equal'){
                        $cond .= " and $field = $value";
                    } else if ($op == 'notequal'){
                        $cond .= " and $field != $value";
                    } else if ($op == 'less'){
                        $cond .= " and $field < $value";
                    } else if ($op == 'lessorequal'){
                        $cond .= " and $field <= $value";
                    } else if ($op == 'greater'){
                        $cond .= " and $field > $value";
                    } else if ($op == 'greaterorequal'){
                        $cond .= " and $field >= $value";
                    } 
                }
            }
	}
        
        $this->db->select('SUM(t_sub_in_line_qty) AS t_sub_in_line_qty, COUNT(t_prod_id) AS t_prod_id, t_po_detail_no,
                           t_prod_lot, t_prod_sublot, t_po_detail_item, m_item_name, 
                           SUM((t_sub_in_line_qty*m_process_weight)/1000) AS m_process_weight', NULL);
        $this->db->join(self::$table2, 't_sub_in_line_prod_id=t_prod_id', 'left')
                 ->join(self::$table3, 't_prod_lot=t_po_detail_lot_no', 'left')
                 ->join(self::$table11, 't_po_detail_item=m_item_id', 'left')
                 ->join(self::$table4, 't_po_detail_item=m_process_id', 'left');
        $this->db->where($cond, NULL, FALSE)
                 ->where('t_sub_in_line_header', $head_id)
                 ->where('m_process_proc_cat_id', $proc_id);
        $this->db->group_by('t_prod_lot, t_prod_sublot');
        $total  = $this->db->count_all_results(self::$table10);
        
        $this->db->select('SUM(t_sub_in_line_qty) AS t_sub_in_line_qty, COUNT(t_prod_id) AS t_prod_id, t_po_detail_no,
                           t_prod_lot, t_prod_sublot, t_po_detail_item, m_item_name, 
                           SUM((t_sub_in_line_qty*m_process_weight)/1000) AS m_process_weight', NULL);
        $this->db->join(self::$table2, 't_sub_in_line_prod_id=t_prod_id', 'left')
                 ->join(self::$table3, 't_prod_lot=t_po_detail_lot_no', 'left')
                 ->join(self::$table11, 't_po_detail_item=m_item_id', 'left')
                 ->join(self::$table4, 't_po_detail_item=m_process_id', 'left');
        $this->db->where($cond, NULL, FALSE)
                 ->where('t_sub_in_line_header', $head_id)
                 ->where('m_process_proc_cat_id', $proc_id);
        $this->db->group_by('t_prod_lot, t_prod_sublot');
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $query  = $this->db->get(self::$table10);
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }
 
        $result = array();
	$result['total']    = $total;
	$result['rows']     = $data;
        
        return json_encode($result);          
    }
    
    function detailCreate($head, $id, $procid, $procName, $proc_tbl, $nik, $mcid){        
        $this->db->select('t_prod_qty, m_process_seq, t_prod_qty');
        $this->db->join(self::$table3, 't_prod_lot = t_po_detail_lot_no', 'left')
                 ->join(self::$table4, 't_po_detail_item = m_process_id', 'left');
        $this->db->where('t_prod_id', $id)
                 ->where('m_process_proc_cat_id', $procid);
        $query_1    = $this->db->get(self::$table2);
        $row_1      = $query_1->row();
        if($row_1){     // Memeriksa apakah item tsb mempunyai proses yang akan diinput + sequence
            $this->db->select('t_proc_id, t_proc_cat, t_proc_cat_table, t_po_detail_item, t_proc_qty_in, t_proc_kbm');
            $this->db->join(self::$table2, 't_proc_prod_id = t_prod_id', 'left')
                     ->join(self::$table3, 't_prod_lot = t_po_detail_lot_no', 'left');
            $this->db->where('t_proc_prod_id', $id);
            $this->db->order_by('t_proc_id', 'desc');
            $this->db->limit(1);
            $query_3    = $this->db->get(self::$table1);
            $row_3      = $query_3->row();
            if($row_3){      // memeriksa apakah sudah pernah masuk card tsb di tabel proses                  
                $this->db->select('m_process_seq, m_process_weight');
                $this->db->where('m_process_id', $row_3->t_po_detail_item)
                         ->where('m_process_proc_cat_id', $row_3->t_proc_cat);
                $query_4    = $this->db->get(self::$table4);
                $row_4      = $query_4->row();
                $lastProcess = floor($row_4->m_process_seq)+1;
                $nextProcess = floor($row_1->m_process_seq);
                if($lastProcess == $nextProcess){   // Memeriksa apakah urutan prosesnya benar ?
                    if($row_3->t_proc_kbm==0){ // memeriksa apakah dalam proses KBM
                        $stdQty     = $row_1->t_prod_qty;
                        $lastQty    = $row_3->t_proc_qty_in;
                        if($stdQty <> $lastQty){    // memeriksa apakah qty proses terakhir masih std per kartu?
                            $berat      = $this->round_up(($row_4->m_process_weight*$lastQty)/1000,2);
                            $warning    = TRUE;
                            $info       = 'Standard Qty sudah berubah dari proses sebelumnya. Harap sesuaikan beratnya menjadi '.$berat.' Kg';
                        }
                        else{
                            $warning    = FALSE;
                            $info       = '';
                        }
                        $query = $this->db->insert($proc_tbl,array(
                            't_proc_seq'             => $nextProcess,
                            't_proc_cat'             => $procid,
                            't_proc_prod_id'         => $id,
                            't_proc_qty_in'          => $lastQty,
                            't_proc_qty_out'         => 0,
                            't_proc_opr_nik'         => $nik,
                            't_proc_machine'         => $mcid
                        ));
                        if($query){ //update proses sebelumnya
                            $this->db->where('t_proc_id', $row_3->t_proc_id);
                            $query2 = $this->db->update($row_3->t_proc_cat_table,array(
                                't_proc_done'            => 1
                            ));
                            if($query2){ //insert sub_in_line
                                $query3 = $this->detailSubCreate($head, $id, $procid, $lastQty);
                                if($query3){
                                    return json_encode(array('success'=>true,'warning'=>$warning,'info'=>$info));
                                }
                                else{
                                    return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
                                }
                            }
                            else{
                                return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
                            }
                        }
                        else{
                            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
                        }
                        
                    }
                    else{
                        return json_encode(array('success'=>false,'error'=>'Kartu Ini Dalam Proses KBM'));
                    }
                }
                else if($lastProcess < $nextProcess){
                    return json_encode(array('success'=>false,'error'=>'Proses Sebelumnya Terlewati'));
                }
                else{
                    return json_encode(array('success'=>false,'error'=>'Proses Sudah pernah diinput'));
                }
            }
            else{
                if($row_1->m_process_seq==1){
                    $query = $this->db->insert($proc_tbl,array(
                        't_proc_seq'             => 1,
                        't_proc_cat'             => $procid,
                        't_proc_prod_id'         => $id,
                        't_proc_qty_in'          => $row_1->t_prod_qty,
                        't_proc_qty_out'         => 0,
                        't_proc_opr_nik'         => $nik,
                        't_proc_machine'         => $mcid
                    ));
                    if($query){
                        return json_encode(array('success'=>true));
                    }
                    else{
                        return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
                    }
                }
                else{
                    return json_encode(array('success'=>false,'error'=>'Proses Awal Belum Diinput'));
                }
            }
        }
        else{
            return json_encode(array('success'=>false,'error'=>'Proses '.$procName.' Tidak ada untuk item tersebut'));
        }
    }
    
    function detailSubCreate($head, $prod_id, $proc_cat, $qty){
        $query = $this->db->insert(self::$table10,array(
            't_sub_in_line_header'      => $head,
            't_sub_in_line_prod_id'     => $prod_id,
            't_sub_in_line_proc_cat'    => $proc_cat,
            't_sub_in_line_qty'         => $qty,
            't_sub_in_line_done'        => 0
        ));
        if($query){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
}

/* End of file m_po.php */
/* Location: ./application/models/transaksi/m_subout.php */