<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_item extends CI_Model
{    
    static $table1  = 'm_item';
    static $table2  = 'm_process';
    static $table3  = 'm_process_cat';
    static $table4  = 'm_bom';
    static $table5  = 'm_item_bom';
    static $table6  = 'm_marking';

    public function __construct() {
        parent::__construct();
        $this->load->helper('database'); // Digunakan untuk memunculkan data Enum
    }
    
    function enumField($table, $field){
        $enums = field_enums($table, $field);
        return json_encode($enums);
    }
    
    function index(){
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'm_item_id';
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
        
        $this->db->join(self::$table6, 'm_item_mark=m_marking_id', 'left');
        $this->db->where($cond, NULL, FALSE);
        $total  = $this->db->count_all_results(self::$table1);
        
        $this->db->join(self::$table6, 'm_item_mark=m_marking_id', 'left');
        $this->db->where($cond, NULL, FALSE);
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $query  = $this->db->get(self::$table1);
                   
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
        
    function create($m_item_id, $m_item_name, $m_item_net_weight, $m_item_ext_id,
                    $m_item_qty_box, $m_item_note, $m_item_mark, $m_item_baking){
        $query = $this->db->insert(self::$table1,array(
            'm_item_id'         => $m_item_id,
            'm_item_name'       => $m_item_name,
            'm_item_net_weight' => $m_item_net_weight,
            'm_item_ext_id'     => $m_item_ext_id,
            'm_item_qty_box'    => $m_item_qty_box,
            'm_item_note'       => $m_item_note,
            'm_item_mark'       => $m_item_mark,
            'm_item_baking'     => $m_item_baking
        ));
        if($query){
            return json_encode(array('success'=>true));
        }
        else {
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        } 
        
    }
    
    function update($m_item_id, $m_item_name, $m_item_net_weight, $m_item_ext_id,
                    $m_item_qty_box, $m_item_note, $m_item_mark, $m_item_baking){
        $this->db->where('m_item_id', $m_item_id);
        $query = $this->db->update(self::$table1,array(
            'm_item_name'       => $m_item_name,
            'm_item_net_weight' => $m_item_net_weight,
            'm_item_ext_id'     => $m_item_ext_id,
            'm_item_qty_box'    => $m_item_qty_box,
            'm_item_note'       => $m_item_note,
            'm_item_mark'       => $m_item_mark,
            'm_item_baking'     => $m_item_baking
        ));
        if($query){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
    
    function delete($m_item_id){
        $query = $this->db->delete(self::$table1, array('m_item_id' => $m_item_id));
        if($query){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
    
    // Fungsi Upload Pending
    function upload($m_item_id, $m_item_name, $m_item_net_weight){       
        $this->db->where('m_item_id', $m_item_id);
        $res = $this->db->get(self::$table1);
        
        if($res->num_rows == 0){
            return $this->db->insert(self::$table1,array(
                'm_item_id'               => $m_item_id,
                'm_item_name'             => $m_item_name,
                'm_item_net_weight'           => $m_item_net_weight,
                'm_item_ext_id'    => $m_item_ext_id
            ));
        }
        else{
            return false;
        }
    }
    
    /// Validasi BOM dan Route ///
    function validBomRoute($m_item_id, $m_item_bom_stat, $m_item_route_stat){
        $this->db->where('m_item_id', $m_item_id);
        $query = $this->db->update(self::$table1,array(
            'm_item_bom_stat'       => $m_item_bom_stat,
            'm_item_route_stat'     => $m_item_route_stat
        ));
        if($query){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
    
    function getMarking(){
        $this->db->select('m_marking_id, m_marking_name');
        $query  = $this->db->get(self::$table6);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    ///PROSES///
    function proses($process_id){
        $this->db->where('m_process_id', $process_id);
        $this->db->join(self::$table3, 'm_process_proc_cat_id=m_process_cat_id', 'left');
        $total  = $this->db->count_all_results(self::$table2);
        
        $this->db->where('m_process_id', $process_id);
        $this->db->join(self::$table3, 'm_process_proc_cat_id=m_process_cat_id', 'left');
        $this->db->order_by('m_process_seq', 'asc');
        $query  = $this->db->get(self::$table2);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }
 
        $result = array();
	$result['total'] = $total;
	$result['rows'] = $data;
        
        return json_encode($result);          
    }
    
    function prosesCreate($m_process_id, $m_process_seq, $m_process_name, $m_process_loc, $m_process_weight){
        $this->db->where('m_process_id', $m_process_id)
                 ->where('(m_process_seq = '.$m_process_seq.' OR m_process_proc_cat_id='.$m_process_name.')', NULL, FALSE);
        $res = $this->db->get(self::$table2);
        
        if($res->num_rows == 0){
            $query = $this->db->insert(self::$table2,array(
                'm_process_id'          => $m_process_id,
                'm_process_seq'         => $m_process_seq,
                'm_process_proc_cat_id' => $m_process_name,
                'm_process_loc'         => $m_process_loc,
                'm_process_weight'      => $m_process_weight
            ));
            if($query){
                return json_encode(array('success'=>true));
            }
            else{
                return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
            }
        }
        else{
            return json_encode(array('success'=>false,'error'=>'No. Proses atau Nama Proses Sudah Ada'));
        }      
        
    }
    
    function prosesUpdate($m_process_id, $m_process_seq, $m_process_name, $m_process_loc, $m_process_weight){
        $this->db->where('m_process_id', $m_process_id)
                 ->where('m_process_proc_cat_id', $m_process_name);
        $res = $this->db->get(self::$table2);
        
        if($res->num_rows == 0){
            $this->db->where('m_process_id', $m_process_id)
                     ->where('m_process_seq', $m_process_seq);
            $query = $this->db->update(self::$table2,array(
                'm_process_proc_cat_id' => $m_process_name,
                'm_process_loc'         => $m_process_loc,
                'm_process_weight'      => $m_process_weight
            ));
            if($query){
                return json_encode(array('success'=>true));
            }
            else{
                return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
            }
        }
        else{
            return json_encode(array('success'=>false,'error'=>'Proses Sudah Ada'));
        }
        
               
    }
    
    function prosesDelete($m_process_id, $m_process_seq){
        $query = $this->db->delete(self::$table2, array('m_process_id'    => $m_process_id,
                                                     'm_process_seq'    => $m_process_seq));
        if($query){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
    
    function getProses(){
        
        $query  = $this->db->get(self::$table3);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    
    function getProcSeq($m_process_id){
        $this->db->select_max('m_process_seq', 'm_process_seq');
        $this->db->where('m_process_id', $m_process_id);
        return $this->db->get(self::$table2);
    }
    
    function getItemProses(){
        $this->db->select('m_process_id, m_item_name');
        $this->db->join(self::$table1, 'm_process_id=m_item_id', 'left');
        $this->db->group_by('m_process_id');
        $query  = $this->db->get(self::$table2);
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    
    function prosesCopy($m_process_id, $copy_item){
        $this->db->where('m_process_id', $m_process_id);
        $query_1    = $this->db->count_all_results(self::$table2);;
        if($query_1 > 0){
            return json_encode(array('success'=>false,'error'=>'Proses Sebelumnya Harus Dikosongkan'));
        }
        else{
            $this->db->where('m_process_id', $copy_item);
            $query_2    = $this->db->get(self::$table2);
            if($query_2){
                foreach ( $query_2->result() as $row_2 ){
                    $this->db->insert(self::$table2,array(
                        'm_process_id'          => $m_process_id,
                        'm_process_seq'         => $row_2->m_process_seq,
                        'm_process_proc_cat_id' => $row_2->m_process_proc_cat_id,
                        'm_process_loc'         => $row_2->m_process_loc,
                        'm_process_weight'      => $row_2->m_process_weight
                    ));
                }
                return json_encode(array('success'=>true));
            }
            else{
                return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
            }
        }
        
    }
    
    ///BOM///
    function bom($bom_id){
        $this->db->select('m_bom_id, m_bom_item, m_bom_qty, m_item_bom_cat, m_item_bom_name');
        $this->db->join(self::$table5, 'm_bom_item = m_item_bom_id', 'left' );
        $this->db->where('m_bom_id', $bom_id);
        $total  = $this->db->count_all_results(self::$table4);
        
        $this->db->join(self::$table5, 'm_bom_item = m_item_bom_id', 'left' );
        $this->db->where('m_bom_id', $bom_id);
        $this->db->order_by('m_item_bom_cat', 'asc')
                 ->order_by('m_bom_item', 'asc');
        $query  = $this->db->get(self::$table4);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }
 
        $result = array();
	$result['total'] = $total;
	$result['rows'] = $data;
        
        return json_encode($result);          
    }
    
    function bomCreateCheck($m_bom_id, $m_item_bom_cat, $m_item_bom_name, $m_bom_qty){
        if($m_item_bom_cat == 'WIRE'){
            $this->db->join(self::$table5, 'm_bom_item = m_item_bom_id', 'left' );
            $this->db->where('m_bom_id', $m_bom_id)
                     ->where('m_item_bom_cat', 'WIRE');
            $res = $this->db->get(self::$table4);
            if($res->num_rows == 0){
                return $this->bomCreate($m_bom_id, $m_item_bom_name, $m_bom_qty);
            }
            else{
                return json_encode(array('success'=>false,'error'=>'WIRE tidak boleh lebih dari 1 !'));
            }                        
        }
        else{
            return $this->bomCreate($m_bom_id, $m_item_bom_name, $m_bom_qty);
        }        
    }
    
    function bomCreate($m_bom_id, $m_item_bom_name, $m_bom_qty){
        $this->db->where('m_bom_id', $m_bom_id)
                 ->where('m_bom_item', $m_item_bom_name);
        $res = $this->db->get(self::$table4);
        
        if($res->num_rows == 0){
            $query = $this->db->insert(self::$table4,array(
                'm_bom_id'      => $m_bom_id,
                'm_bom_item'    => $m_item_bom_name,
                'm_bom_qty'     => $m_bom_qty
            ));
            if($query){
                return json_encode(array('success'=>true));
            }
            else{
                return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
            }
        }
        else{
            return json_encode(array('success'=>false,'error'=>'Nama Barang Sudah Ada'));
        }      
        
    }
    
    function bomUpdate($m_bom_id, $m_bom_item, $m_bom_qty){
        $this->db->where('m_bom_id', $m_bom_id)
                 ->where('m_bom_item', $m_bom_item);
        $query = $this->db->update(self::$table4,array(
            'm_bom_qty'    => $m_bom_qty
        ));
        if($query){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
    
    function bomDelete($m_bom_id, $m_bom_item){
        $query = $this->db->delete(self::$table4, array('m_bom_id'    => $m_bom_id,
                                                     'm_bom_item'   => $m_bom_item));
        if($query){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
        
    function getBomItem($cat){
        $this->db->where('m_item_bom_cat', $cat);
        $query  = $this->db->get(self::$table5);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    
    function getBomQty($m_item_bom_id){
        $this->db->select('m_item_bom_qty');
        $this->db->where('m_item_bom_id', $m_item_bom_id);
        return $this->db->get(self::$table5);
    }
    
    function getItemBom(){
        $this->db->select('m_bom_id, m_item_name');
        $this->db->join(self::$table1, 'm_bom_id=m_item_id', 'left');
        $this->db->group_by('m_bom_id');
        $query  = $this->db->get(self::$table4);
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    
    function bomCopy($m_process_id, $copy_item){
        $this->db->where('m_bom_id', $m_process_id);
        $query_1    = $this->db->count_all_results(self::$table4);;
        if($query_1 > 0){
            return json_encode(array('success'=>false,'error'=>'BOM Sebelumnya Harus Dikosongkan'));
        }
        else{
            $this->db->where('m_bom_id', $copy_item);
            $query_2    = $this->db->get(self::$table4);
            if($query_2){
                foreach ( $query_2->result() as $row_2 ){
                    $this->db->insert(self::$table4,array(
                        'm_bom_id'      => $m_process_id,
                        'm_bom_item'    => $row_2->m_bom_item,
                        'm_bom_qty'     => $row_2->m_bom_qty
                    ));
                }
                return json_encode(array('success'=>true));
            }
            else{
                return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
            }
        }
    }
    
}

/* End of file m_item.php */
/* Location: ./application/models/master/m_item.php */