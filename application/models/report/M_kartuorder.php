<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_kartuorder extends CI_Model
{    
    static $table1  = 't_po_header';
    static $table2  = 't_po_detail';
    static $table3  = 'm_item';
    static $table4  = 'm_cust';
    static $table5  = 't_prod';
    static $table6  = 'm_bom';
    static $table7  = 'm_item_bom';
    static $table8  = 'm_process';
    static $table9  = 'm_process_cat';
    static $table10 = 'm_marking';
    
    function __construct() {
        parent::__construct();
        
    }
          
    function getLot(){
        $query  = $this->db->get(self::$table2);
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    
    function lot($lot_id=null)
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 't_prod_id';
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
        
        $this->db->where($cond, NULL, FALSE)
                 ->where('t_prod_lot', $lot_id);
        $total  = $this->db->count_all_results(self::$table5);
        
        $this->db->where($cond, NULL, FALSE)
                 ->where('t_prod_lot', $lot_id);
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $query  = $this->db->get(self::$table5);
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }
 
        $result = array();
	$result["total"] = $total;
	$result['rows'] = $data;
        
        return json_encode($result);
    }
    
    // PRINT CARD //
    function printAll($id)
    {
        $sql = 'SELECT t_prod_id, t_po_header_date, t_po_detail_qty, t_po_detail_no, t_po_detail_prod,
                t_po_detail_cust, t.t_prod_lot AS t_prod_lot, t.t_prod_sublot AS t_prod_sublot, t_prod_card, m_item_name, m_item_baking,
                m_item_bom_name, t_po_detail_prod, m_bom_qty, t_po_detail_prod_date, t_prod_qty,
                t_po_detail_delv_date, m_item_mark, m_item_note, t_prod_card_cnt, t_prod_qty_sum, m_marking_img
                FROM '.self::$table5.' t
                LEFT JOIN '.self::$table2.' ON t.t_prod_lot=t_po_detail_lot_no
                LEFT JOIN '.self::$table3.' ON t_po_detail_item=m_item_id
                LEFT JOIN '.self::$table1.' ON t_po_detail_no=t_po_header_no
                LEFT JOIN '.self::$table6.' ON m_item_id=m_bom_id
                LEFT JOIN '.self::$table7.' ON m_bom_item=m_item_bom_id
                LEFT JOIN '.self::$table10.' ON m_item_mark=m_marking_id
                LEFT JOIN ( SELECT t_prod_lot, t_prod_sublot, COUNT(t_prod_card) t_prod_card_cnt,
                            SUM(t_prod_qty) t_prod_qty_sum
                            FROM t_prod
                            GROUP by t_prod_lot, t_prod_sublot ) t2 
                            ON t.t_prod_lot = t2.t_prod_lot and t.t_prod_sublot = t2.t_prod_sublot
                WHERE t.t_prod_lot="'.$id.'" AND m_item_bom_cat="WIRE" ';
        $header = $this->db->query($sql);
        
        ///// process
        $this->db->select('m_process_cat_name, m_process_weight, t_prod_qty');
        $this->db->join(self::$table9, 'm_process_proc_cat_id=m_process_cat_id', 'left')
                 ->join(self::$table2, 'm_process_id=t_po_detail_item', 'left')
                 ->join(self::$table5, 't_po_detail_lot_no=t_prod_lot', 'left');
        $this->db->where('t_po_detail_lot_no', $id);
        $this->db->group_by('m_process_proc_cat_id');
        $this->db->order_by('m_process_seq', 'ASC');
        $detail = $this->db->get(self::$table8);
        
        $result = array();
	$result['rows'] = $header;
	$result['detail'] = $detail;
        
        return $result;
    }
    
    function printSublot($lot, $sublot)
    {
        $sql = 'SELECT t_prod_id, t_po_header_date, t_po_detail_qty, t_po_detail_no, t_po_detail_prod,
                t_po_detail_cust, t.t_prod_lot AS t_prod_lot, t.t_prod_sublot AS t_prod_sublot, t_prod_card, m_item_name, m_item_baking,
                m_item_bom_name, t_po_detail_prod, m_bom_qty, t_po_detail_prod_date, t_prod_qty,
                t_po_detail_delv_date, m_item_mark, m_item_note, t_prod_card_cnt, t_prod_qty_sum, m_marking_img
                FROM '.self::$table5.' t
                LEFT JOIN '.self::$table2.' ON t.t_prod_lot=t_po_detail_lot_no
                LEFT JOIN '.self::$table3.' ON t_po_detail_item=m_item_id
                LEFT JOIN '.self::$table1.' ON t_po_detail_no=t_po_header_no
                LEFT JOIN '.self::$table6.' ON m_item_id=m_bom_id
                LEFT JOIN '.self::$table7.' ON m_bom_item=m_item_bom_id
                LEFT JOIN '.self::$table10.' ON m_item_mark=m_marking_id
                LEFT JOIN ( SELECT t_prod_lot, t_prod_sublot, COUNT(t_prod_card) t_prod_card_cnt,
                            SUM(t_prod_qty) t_prod_qty_sum
                            FROM t_prod
                            GROUP by t_prod_lot, t_prod_sublot ) t2 
                            ON t.t_prod_lot = t2.t_prod_lot and t.t_prod_sublot = t2.t_prod_sublot
                WHERE t.t_prod_lot="'.$lot.'" AND t.t_prod_sublot="'.$sublot.'" AND m_item_bom_cat="WIRE" ';
        $header = $this->db->query($sql);
        
        ///// process
        $this->db->select('m_process_cat_name, m_process_weight, t_prod_qty');
        $this->db->join(self::$table9, 'm_process_proc_cat_id=m_process_cat_id', 'left')
                 ->join(self::$table2, 'm_process_id=t_po_detail_item', 'left')
                 ->join(self::$table5, 't_po_detail_lot_no=t_prod_lot', 'left');
        $this->db->where('t_po_detail_lot_no', $lot);
        $this->db->group_by('m_process_proc_cat_id');
        $this->db->order_by('m_process_seq', 'ASC');
        $detail = $this->db->get(self::$table8);
        
        $result = array();
	$result['rows'] = $header;
	$result['detail'] = $detail;
        
        return $result;
    }
    
    function printSelected($id)
    {
        $sql = 'SELECT t_prod_id, t_po_header_date, t_po_detail_qty, t_po_detail_no, t_po_detail_prod,
                t_po_detail_cust, t.t_prod_lot AS t_prod_lot, t.t_prod_sublot AS t_prod_sublot, t_prod_card, m_item_name, m_item_baking,
                m_item_bom_name, t_po_detail_prod, m_bom_qty, t_po_detail_prod_date, t_prod_qty,
                t_po_detail_delv_date, m_item_mark, m_item_note, t_prod_card_cnt, t_prod_qty_sum, m_marking_img
                FROM '.self::$table5.' t
                LEFT JOIN '.self::$table2.' ON t.t_prod_lot=t_po_detail_lot_no
                LEFT JOIN '.self::$table3.' ON t_po_detail_item=m_item_id
                LEFT JOIN '.self::$table1.' ON t_po_detail_no=t_po_header_no
                LEFT JOIN '.self::$table6.' ON m_item_id=m_bom_id
                LEFT JOIN '.self::$table7.' ON m_bom_item=m_item_bom_id
                LEFT JOIN '.self::$table10.' ON m_item_mark=m_marking_id
                LEFT JOIN ( SELECT t_prod_lot, t_prod_sublot, COUNT(t_prod_card) t_prod_card_cnt,
                            SUM(t_prod_qty) t_prod_qty_sum
                            FROM t_prod
                            GROUP by t_prod_lot, t_prod_sublot ) t2 
                            ON t.t_prod_lot = t2.t_prod_lot and t.t_prod_sublot = t2.t_prod_sublot
                WHERE t.t_prod_id="'.$id.'" AND m_item_bom_cat="WIRE" ';
        $header = $this->db->query($sql);
        
        ///// process
        $this->db->select('m_process_cat_name, m_process_weight, t_prod_qty');
        $this->db->join(self::$table9, 'm_process_proc_cat_id=m_process_cat_id', 'left')
                 ->join(self::$table2, 'm_process_id=t_po_detail_item', 'left')
                 ->join(self::$table5, 't_po_detail_lot_no=t_prod_lot', 'left');
        $this->db->where('t_prod_id', $id);
        $this->db->group_by('m_process_proc_cat_id');
        $this->db->order_by('m_process_seq', 'ASC');
        $detail = $this->db->get(self::$table8);
        
        $result = array();
	$result['rows'] = $header;
	$result['detail'] = $detail;
        
        return $result;
    }
}
?>
