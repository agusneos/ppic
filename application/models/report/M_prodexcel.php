<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_prodexcel extends CI_Model
{    
    static $table1 = 'm_process_cat';
    
    public function __construct() {
        parent::__construct();
        
    }
      
    function exportExcel($proc_id, $start, $end){
        $sql = 'SELECT t_po_detail_item, t_process_shif, m_machine_lines, m_machine_mac, t_po_detail_no, t_prod_lot, CONCAT_WS("-",t_prod_lot, t_prod_sublot) AS nolot,
                COUNT(t_prod_sublot) AS qtybox, SUM(t_process_qty) AS qtypcs, ROUND((SUM(t_process_qty)*m_process_weight)/1000,1) AS qtyberat
                FROM t_process
                LEFT JOIN t_prod ON t_process_prod_id = t_prod_id
                LEFT JOIN t_po_detail ON t_prod_lot = t_po_detail_lot_no
                LEFT JOIN m_machine ON t_process_machine = m_machine_id
                LEFT JOIN m_process ON CONCAT_WS("-",t_po_detail_item, t_process_cat) = CONCAT_WS("-",m_process_id, m_process_proc_cat_id)
                WHERE t_process_cat="'.$proc_id.'" AND t_process_time BETWEEN "'.$start.'" AND "'.$end.'"
                GROUP BY t_process_shif, t_process_machine, (nolot)';
        
        $query  = $this->db->query($sql);
        
       // $tgl = $this->db->query('SELECT "'.$date.'" as Tanggal');
        
        $result = array();
	//$result['date'] = $tgl;
	$result['rows'] = $query;
        
        return $result;
    }
    
    function getProces(){
        $query  = $this->db->get(self::$table1);
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
}
?>
