<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_rekap extends CI_Model
{    
    static $table1 = 't_process';
    static $table2 = 't_prod';
    static $table3 = 't_po_detail';
    static $table4 = 'm_item';
    static $table5 = 'm_process_cat';
    static $table6 = 'm_process';
     
    function __construct() {
        parent::__construct();
      //  $this->load->helper('database'); // Digunakan untuk memunculkan data Enum
        $this->load->library('subquery');
    }

    function getProces(){
        $query  = $this->db->get(self::$table5);
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
       
    function showRekapBarangHarian($proses, $tgl){
        $this->db->select('m_item_id');
        $sub = $this->subquery->start_subquery('from');
            $sub->select('m_item_id');
            $sub->from(self::$table1);
            $sub->join(self::$table2,'t_process_prod_id=t_prod_id','left')
                ->join(self::$table3,'t_prod_lot=t_po_detail_lot_no','left')
                ->join(self::$table4,'t_po_detail_item=m_item_id','left')
                ->join(self::$table6,'t_po_detail_item=m_process_id','left');
            $sub->where('t_process_cat', $proses)
                ->where('DATE(t_process_time)', $tgl)
                ->where('m_process_proc_cat_id', $proses);
            $sub->group_by('m_item_id');
            $sub->order_by('m_item_name');
        $this->subquery->end_subquery('jk');
        $total = $this->db->count_all_results();
        
        $this->db->select('m_item_id, m_item_name, SUM(t_process_qty) AS t_process_qty, 
                           COUNT(t_process_qty) AS box, ROUND((SUM(t_process_qty)*m_process_weight)/1000,1) AS kg', FALSE);
        $this->db->join(self::$table2,'t_process_prod_id=t_prod_id','left')
                 ->join(self::$table3,'t_prod_lot=t_po_detail_lot_no','left')
                 ->join(self::$table4,'t_po_detail_item=m_item_id','left')
                 ->join(self::$table6,'t_po_detail_item=m_process_id','left');
        $this->db->where('t_process_cat', $proses)
                 ->where('DATE(t_process_time)', $tgl)
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
    
    function showRekapItem($item){
        $sql_total  = ('SELECT COUNT(proc) AS proc
                        FROM ( 
                            SELECT t_process_cat AS proc, m_process_cat_name AS proses, sum(t_process_qty) AS qty
                            FROM t_process
                            LEFT JOIN m_process_cat ON t_process_cat=m_process_cat_id
                            WHERE t_process_prod_id IN (
                                SELECT t_prod_id FROM t_prod 
                                LEFT JOIN t_po_detail ON t_prod_lot=t_po_detail_lot_no
                                WHERE t_po_detail_item = "'.$item.'"
                            )
                            AND NOT EXISTS (
                                SELECT t_process_cat, t_process_proc_seq, t_process_qty 
                                FROM t_process later 
                                WHERE later.t_process_prod_id = t_process.t_process_prod_id
                                AND later.t_process_id > t_process.t_process_id
                            )
                            GROUP BY t_process_cat
                            ORDER BY t_process_proc_seq
                        ) AS res');
        $totalq     = $this->db->query($sql_total);
        $total      = $totalq->row();
        
        $sql_query  = ('SELECT t_process_cat AS proc, m_process_cat_name AS proses, sum(t_process_qty) AS qty
                        FROM t_process
                        LEFT JOIN m_process_cat ON t_process_cat=m_process_cat_id
                        WHERE t_process_prod_id IN (
                            SELECT t_prod_id FROM t_prod 
                            LEFT JOIN t_po_detail ON t_prod_lot=t_po_detail_lot_no
                            WHERE t_po_detail_item = "'.$item.'"
                        )
                        AND NOT EXISTS (
                            SELECT t_process_cat, t_process_proc_seq, t_process_qty 
                            FROM t_process later 
                            WHERE later.t_process_prod_id = t_process.t_process_prod_id
                            AND later.t_process_id > t_process.t_process_id
                        )
                        GROUP BY t_process_cat
                        ORDER BY t_process_proc_seq');
        $query      = $this->db->query($sql_query);
        
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }
 
        $result = array();
	$result['total']    = $total->proc;
	$result['rows']     = $data;
        
        return json_encode($result);
    }
    
    function showRekap($lot){
        $sql_total  = ('SELECT COUNT(proc) AS proc
                        FROM ( 
                            SELECT t_process_cat AS proc, m_process_cat_name AS proses, sum(t_process_qty) AS qty
                            FROM t_process
                            LEFT JOIN m_process_cat ON t_process_cat=m_process_cat_id
                            WHERE t_process_prod_id IN (
                                SELECT t_prod_id FROM t_prod WHERE t_prod_lot = "'.$lot.'"
                            )
                            AND NOT EXISTS (
                                SELECT t_process_cat, t_process_proc_seq, t_process_qty 
                                FROM t_process later 
                                WHERE later.t_process_prod_id = t_process.t_process_prod_id
                                AND later.t_process_id > t_process.t_process_id
                            )
                            GROUP BY t_process_cat
                            ORDER BY t_process_proc_seq
                        ) AS res');
        $totalq     = $this->db->query($sql_total);
        $total      = $totalq->row();
        
        $sql_query  = ('SELECT t_process_cat AS proc, m_process_cat_name AS proses, sum(t_process_qty) AS qty
                        FROM t_process
                        LEFT JOIN m_process_cat ON t_process_cat=m_process_cat_id
                        WHERE t_process_prod_id IN (
                            SELECT t_prod_id FROM t_prod WHERE t_prod_lot = "'.$lot.'"
                        )
                        AND NOT EXISTS (
                            SELECT t_process_cat, t_process_proc_seq, t_process_qty 
                            FROM t_process later 
                            WHERE later.t_process_prod_id = t_process.t_process_prod_id
                            AND later.t_process_id > t_process.t_process_id
                        )
                        GROUP BY t_process_cat
                        ORDER BY t_process_proc_seq');
        $query      = $this->db->query($sql_query);
        
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }
 
        $result = array();
	$result['total']    = $total->proc;
	$result['rows']     = $data;
        
        return json_encode($result);
    }
    
    function showRekapItemDetail($item, $proc){
        $sql_total  = ('SELECT COUNT(t_process_prod_id) AS t_process_prod_id
                        FROM t_process
                        LEFT JOIN t_prod ON t_process_prod_id=t_prod_id
                        WHERE t_process_prod_id IN (
                            SELECT t_prod_id FROM t_prod 
                            LEFT JOIN t_po_detail ON t_prod_lot=t_po_detail_lot_no
                            WHERE t_po_detail_item = "'.$item.'"
                        )
                        AND t_process_cat = '.$proc.'
                        AND NOT EXISTS (
                            SELECT t_process_cat, t_process_proc_seq, t_process_qty 
                            FROM t_process later 
                            WHERE later.t_process_prod_id = t_process.t_process_prod_id
                            AND later.t_process_id > t_process.t_process_id
                        )
                        ORDER BY t_prod_sublot, t_prod_card');
        $totalq     = $this->db->query($sql_total);
        $total      = $totalq->row();
        
        $sql_query  = ('SELECT t_prod_lot, t_prod_sublot, t_prod_card, t_process_qty, t_process_time
                        FROM t_process
                        LEFT JOIN t_prod ON t_process_prod_id=t_prod_id
                        WHERE t_process_prod_id IN (
                            SELECT t_prod_id FROM t_prod 
                            LEFT JOIN t_po_detail ON t_prod_lot=t_po_detail_lot_no
                            WHERE t_po_detail_item = "'.$item.'"
                        )
                        AND t_process_cat = '.$proc.'
                        AND NOT EXISTS (
                            SELECT t_process_cat, t_process_proc_seq, t_process_qty 
                            FROM t_process later 
                            WHERE later.t_process_prod_id = t_process.t_process_prod_id
                            AND later.t_process_id > t_process.t_process_id
                        )
                        ORDER BY t_prod_sublot, t_prod_card');
        $query      = $this->db->query($sql_query);
        
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }
 
        $result = array();
	$result['total']    = $total->t_process_prod_id;
	$result['rows']     = $data;
        
        return json_encode($result);
    }
    
    function showRekapDetail($lot, $proc){
        $sql_total  = ('SELECT COUNT(t_process_prod_id) AS t_process_prod_id
                        FROM t_process
                        LEFT JOIN t_prod ON t_process_prod_id=t_prod_id
                        WHERE t_process_prod_id IN (
                            SELECT t_prod_id FROM t_prod WHERE t_prod_lot = "'.$lot.'"
                        )
                        AND t_process_cat = '.$proc.'
                        AND NOT EXISTS (
                            SELECT t_process_cat, t_process_proc_seq, t_process_qty 
                            FROM t_process later 
                            WHERE later.t_process_prod_id = t_process.t_process_prod_id
                            AND later.t_process_id > t_process.t_process_id
                        )
                        ORDER BY t_prod_sublot, t_prod_card');
        $totalq     = $this->db->query($sql_total);
        $total      = $totalq->row();
        
        $sql_query  = ('SELECT t_prod_lot, t_prod_sublot, t_prod_card, t_process_qty, t_process_time
                        FROM t_process
                        LEFT JOIN t_prod ON t_process_prod_id=t_prod_id
                        WHERE t_process_prod_id IN (
                            SELECT t_prod_id FROM t_prod WHERE t_prod_lot = "'.$lot.'"
                        )
                        AND t_process_cat = '.$proc.'
                        AND NOT EXISTS (
                            SELECT t_process_cat, t_process_proc_seq, t_process_qty 
                            FROM t_process later 
                            WHERE later.t_process_prod_id = t_process.t_process_prod_id
                            AND later.t_process_id > t_process.t_process_id
                        )
                        ORDER BY t_prod_sublot, t_prod_card');
        $query      = $this->db->query($sql_query);
        
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }
 
        $result = array();
	$result['total']    = $total->t_process_prod_id;
	$result['rows']     = $data;
        
        return json_encode($result);
    }
}

/* End of file m_rekap.php */
/* Location: ./application/models/inquiry/m_rekap.php */