<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_group extends CI_Model
{    
    static $table1 = 'a_level';
    static $table2 = 'a_group';
    static $table3 = 'a_menu';
     
    public function __construct() {
        parent::__construct();
      //  $this->load->helper('database'); // Digunakan untuk memunculkan data Enum
    }

    function index()
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'a_level_id';
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
        
        $this->db->where($cond, NULL, FALSE);
        $total  = $this->db->count_all_results(self::$table1);
        
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
	$result['total'] = $total;
	$result['rows'] = $data;
        
        return json_encode($result);          
    }   
        
    function create($a_level_name){
        $query_1 = $this->db->insert(self::$table1,array(
            'a_level_name'    => $a_level_name
        ));
        if($query_1){
            $this->db->select_max('a_level_id');
            $query_2    = $this->db->get(self::$table1);
            $row_2      = $query_2->row();
            if($row_2){
                $this->db->select('a_menu_id');
                $query_3    = $this->db->get(self::$table3);
                foreach ( $query_3->result() as $row_3 ){
                    $this->db->insert(self::$table2,array(
                        'a_group_level' => $row_2->a_level_id,
                        'a_group_menu'  => $row_3->a_menu_id,
                        'a_group_status'=> 0
                    ));
                }
                return json_encode(array('success'=>true));
            }
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }        
    }
    
    function update($a_level_id, $a_level_name)
    {
        if($a_level_id=='1'){
            return json_encode(array('success'=>false,'error'=>'Group Administrator tidak boleh diupdate !'));
        }
        else{
            $this->db->where('a_level_id', $a_level_id);
            $query = $this->db->update(self::$table1,array(
                'a_level_name'    => $a_level_name
            ));
            if($query){
                return json_encode(array('success'=>true));
            }
            else{
                return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
            }
        }
    }
    
    function delete($a_level_id){
        if($a_level_id=='1'){
            return json_encode(array('success'=>false,'error'=>'Group Administrator tidak boleh dihapus !'));
        }
        else{
            $query = $this->db->delete(self::$table1, array('a_level_id' => $a_level_id));
            if($query){
                return json_encode(array('success'=>true));
            }
            else{
                return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
            }
        }
        
    }
    
    function menu($a_level_id){
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;        
        
        $this->db->join(self::$table3, 'a_group_menu=a_menu_id', 'left');
        $this->db->where('a_menu_parentId', $id)
                 ->where('a_group_level', $a_level_id);    
        $rs = $this->db->get(self::$table2);
        $result  = array();
        foreach ( $rs->result() as $row ){
            $node = array();
            $node['groupId']    = $row->a_group_id;
            $node['id']         = $row->a_menu_id;
            $node['text']       = $row->a_menu_name;
            $node['state']      = $this->has_child($row->a_menu_id) ? 'closed' : 'open';
            $node['iconCls']    = $row->a_menu_iconCls;
            $node['checked']    = $row->a_group_status ? TRUE : FALSE;
            array_push($result, $node);
        }
        return json_encode($result);
    }
    
    function has_child($id){
        $this->db->where('a_menu_parentId',$id);
        $this->db->from(self::$table3);
        $rs = $this->db->count_all_results();
        return $rs > 0 ? true : false;
    }
    
    function menu_update($a_group_id, $a_group_status){
        $this->db->where('a_group_id', $a_group_id);
        $query = $this->db->update(self::$table2,array(
            'a_group_status'    => $this->checkStat($a_group_status)
        ));
        if($query){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
    
    function checkStat($stat){
        if($stat=='true'){
            return 1;
        }
        else{
            return 0;
        }
    }
}

/* End of file m_group.php */
/* Location: ./application/models/admin/m_group.php */