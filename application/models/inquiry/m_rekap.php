<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_rekap extends CI_Model
{    
    static $table1 = 't_process';
    static $table2 = 't_prod';
    static $table3 = 't_po_detail';
    static $table4 = 'm_item';
    static $table5 = 'm_process_cat';
    static $table6 = 'm_process';
    static $table7 = 'm_machine';
            
    function __construct() {
        parent::__construct();
      //  $this->load->helper('database'); // Digunakan untuk memunculkan data Enum
        $this->load->library('subquery');
    }

    function getProces(){
        $query  = $this->db->get(self::$table5);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
       
    function showRekapBarang($proses, $tgl_from, $tgl_to){
        $this->db->select('t_po_detail_item');
        $sub = $this->subquery->start_subquery('from');
            $sub->select('t_po_detail_item');
            $sub->from(self::$table1);
            $sub->join(self::$table2,'t_process_prod_id=t_prod_id','left')
                ->join(self::$table3,'t_prod_lot=t_po_detail_lot_no','left');
            $sub->where('t_process_cat', $proses)
                ->where('DATE(t_process_time) >=', $tgl_from)
                ->where('DATE(t_process_time) <=', $tgl_to);
            $sub->group_by('t_po_detail_item');
        $this->subquery->end_subquery('query');
        $total = $this->db->count_all_results();
        
        $this->db->select('m_item_id, m_item_name, SUM(t_process_qty) AS t_process_qty, 
                           COUNT(t_process_qty) AS box, ROUND((SUM(t_process_qty)*m_process_weight)/1000,1) AS kg', FALSE);
        $this->db->join(self::$table2,'t_process_prod_id=t_prod_id','left')
                 ->join(self::$table3,'t_prod_lot=t_po_detail_lot_no','left')
                 ->join(self::$table4,'t_po_detail_item=m_item_id','left')
                 ->join(self::$table6,'t_po_detail_item=m_process_id','left');
        $this->db->where('t_process_cat', $proses)
                 ->where('DATE(t_process_time) >=', $tgl_from)
                 ->where('DATE(t_process_time) <=', $tgl_to)
                 ->where('m_process_proc_cat_id', $proses);
        $this->db->group_by('m_item_id');
        $this->db->order_by('m_item_name');
        $query  = $this->db->get(self::$table1);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }
 
        $result = array();
	$result['total']    = $total;
	$result['rows']     = $data;
        
        return json_encode($result);
    }
           
    function showRekapBarangDetail($proses, $item_id, $tgl_from, $tgl_to){
        $this->db->select('t_prod_lot, t_prod_sublot, t_prod_card, t_process_qty, t_process_time');
        $this->db->join(self::$table2,'t_process_prod_id=t_prod_id','left')
                 ->join(self::$table3,'t_prod_lot=t_po_detail_lot_no','left');
        $this->db->where('t_process_cat', $proses)
                 ->where('t_po_detail_item', $item_id)
                 ->where('DATE(t_process_time) >=', $tgl_from)
                 ->where('DATE(t_process_time) <=', $tgl_to);
        $this->db->order_by('t_prod_lot')
                 ->order_by('t_prod_sublot')
                 ->order_by('t_prod_card');
        $total = $this->db->count_all_results(self::$table1);
        
        $this->db->select('t_prod_lot, t_prod_sublot, t_prod_card, t_process_qty, t_process_time');
        $this->db->join(self::$table2,'t_process_prod_id=t_prod_id','left')
                 ->join(self::$table3,'t_prod_lot=t_po_detail_lot_no','left');
        $this->db->where('t_process_cat', $proses)
                 ->where('t_po_detail_item', $item_id)
                 ->where('DATE(t_process_time) >=', $tgl_from)
                 ->where('DATE(t_process_time) <=', $tgl_to);
        $this->db->order_by('t_prod_lot')
                 ->order_by('t_prod_sublot')
                 ->order_by('t_prod_card');
        $query  = $this->db->get(self::$table1);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }
 
        $result = array();
	$result['total']    = $total;
	$result['rows']     = $data;
        
        return json_encode($result);
    }
    
    function showRekapMesin($proses, $tgl_from, $tgl_to){
        /*$this->db->select('t_process_machine');
        $sub = $this->subquery->start_subquery('from');
            $sub->select('t_process_machine');
            $sub->from(self::$table1);
            $sub->join(self::$table2,'t_process_prod_id=t_prod_id','left')
                ->join(self::$table3,'t_prod_lot=t_po_detail_lot_no','left');
            $sub->where('t_process_cat', $proses)
                ->where('DATE(t_process_time) >=', $tgl_from)
                ->where('DATE(t_process_time) <=', $tgl_to);
            $sub->group_by('t_process_machine')
                ->group_by('t_po_detail_item');
        $this->subquery->end_subquery('query');
        $total = $this->db->count_all_results();*/
        $sql_total  = ('SELECT COUNT(t_po_detail_item) AS t_po_detail_item
                        FROM (
                            SELECT t_po_detail_item
                            FROM t_process
                            LEFT JOIN t_prod ON t_process_prod_id=t_prod_id
                            LEFT JOIN t_po_detail ON t_prod_lot=t_po_detail_lot_no
                            WHERE t_process_cat = "'.$proses.'" AND
                                    DATE(t_process_time) >= "'.$tgl_from.'" AND
                                    DATE(t_process_time) <= "'.$tgl_to.'"
                            GROUP BY t_process_machine, t_po_detail_item
                        ) AS total');
        $totalq     = $this->db->query($sql_total);
        $total      = $totalq->row();
        
        $this->db->select('m_item_id, m_item_name, t_process_machine, m_machine_lines, m_machine_mac, SUM(t_process_qty) AS t_process_qty, 
                           COUNT(t_process_qty) AS box, ROUND((SUM(t_process_qty)*m_process_weight)/1000,1) AS kg', FALSE);
        $this->db->join(self::$table2,'t_process_prod_id=t_prod_id','left')
                 ->join(self::$table3,'t_prod_lot=t_po_detail_lot_no','left')
                 ->join(self::$table4,'t_po_detail_item=m_item_id','left')
                 ->join(self::$table6,'t_po_detail_item=m_process_id','left')
                 ->join(self::$table7,'t_process_machine=m_machine_id','left');
        $this->db->where('t_process_cat', $proses)
                 ->where('DATE(t_process_time) >=', $tgl_from)
                 ->where('DATE(t_process_time) <=', $tgl_to)
                 ->where('m_process_proc_cat_id', $proses);
        $this->db->group_by('m_machine_lines')
                 ->group_by('m_machine_mac')
                 ->group_by('m_item_id');
        $this->db->order_by('m_item_name')
                 ->order_by('m_machine_lines')
                 ->order_by('m_machine_mac');
        $query  = $this->db->get(self::$table1);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }
 
        $result = array();
	$result['total']    = $total->t_po_detail_item;
	$result['rows']     = $data;
        
        return json_encode($result);
    }
    
    function showRekapMesinDetail($proses, $item_id, $machine_id, $tgl_from, $tgl_to){
        $this->db->select('t_prod_lot, t_prod_sublot, t_prod_card, t_process_qty, t_process_time');
        $this->db->join(self::$table2,'t_process_prod_id=t_prod_id','left')
                 ->join(self::$table3,'t_prod_lot=t_po_detail_lot_no','left');
        $this->db->where('t_process_cat', $proses)
                 ->where('t_po_detail_item', $item_id)
                 ->where('t_process_machine', $machine_id)
                 ->where('DATE(t_process_time) >=', $tgl_from)
                 ->where('DATE(t_process_time) <=', $tgl_to);
        $this->db->order_by('t_prod_lot')
                 ->order_by('t_prod_sublot')
                 ->order_by('t_prod_card');
        $total = $this->db->count_all_results(self::$table1);
        
        $this->db->select('t_prod_lot, t_prod_sublot, t_prod_card, t_process_qty, t_process_time');
        $this->db->join(self::$table2,'t_process_prod_id=t_prod_id','left')
                 ->join(self::$table3,'t_prod_lot=t_po_detail_lot_no','left');
        $this->db->where('t_process_cat', $proses)
                 ->where('t_po_detail_item', $item_id)
                 ->where('t_process_machine', $machine_id)
                 ->where('DATE(t_process_time) >=', $tgl_from)
                 ->where('DATE(t_process_time) <=', $tgl_to);
        $this->db->order_by('t_prod_lot')
                 ->order_by('t_prod_sublot')
                 ->order_by('t_prod_card');
        $query  = $this->db->get(self::$table1);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }
 
        $result = array();
	$result['total']    = $total;
	$result['rows']     = $data;
        
        return json_encode($result);
    }
}

/* End of file m_rekap.php */
/* Location: ./application/models/inquiry/m_rekap.php */