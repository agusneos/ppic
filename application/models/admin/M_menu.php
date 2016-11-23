<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_menu extends CI_Model
{    
    static $table1  = 'a_menu';
    static $table2  = 'a_group';
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('database');
    }

    function index()
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'a.a_menu_parentId';
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
        
        $this->db->select('a.a_menu_id AS "a.a_menu_id", a.a_menu_parentId AS "a.a_menu_parentId",
                           b.a_menu_name AS "b.a_menu_name", a.a_menu_name AS "a.a_menu_name",
                           a.a_menu_uri AS "a.a_menu_uri", a.a_menu_iconCls AS "a.a_menu_iconCls",
                           a.a_menu_type AS "a.a_menu_type"');       
        $this->db->join(self::$table1.' b', 'a.a_menu_parentId = b.a_menu_id', 'left');
        $this->db->where($cond, NULL, FALSE);
        //$this->db->from(self::$table1.' a');
        $total  = $this->db->count_all_results(self::$table1.' a');
        
        $this->db->select('a.a_menu_id AS "a.a_menu_id", a.a_menu_parentId AS "a.a_menu_parentId",
                           b.a_menu_name AS "b.a_menu_name", a.a_menu_name AS "a.a_menu_name",
                           a.a_menu_uri AS "a.a_menu_uri", a.a_menu_iconCls AS "a.a_menu_iconCls",
                           a.a_menu_type AS "a.a_menu_type"');       
        $this->db->join(self::$table1.' b', 'a.a_menu_parentId = b.a_menu_id', 'left');
        $this->db->where($cond, NULL, FALSE);
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $query  = $this->db->get(self::$table1.' a');
                   
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
    
    function create($a_menu_parentId, $a_menu_name, $a_menu_uri, $a_menu_iconCls, $a_menu_type){
        $query = $this->db->insert(self::$table1,array(
            'a_menu_parentId'   => $a_menu_parentId,
            'a_menu_name'       => $a_menu_name,
            'a_menu_uri'        => $a_menu_uri,
            'a_menu_iconCls'    => $a_menu_iconCls,
            'a_menu_type'       => $a_menu_type
        ));
        if($query){
            $this->db->select_max('a_menu_id');
            $query_2    = $this->db->get(self::$table1);
            $row_2      = $query_2->row();
            if($row_2){
                $this->db->select('a_group_level');
                $this->db->group_by('a_group_level');
                $query_3    = $this->db->get(self::$table2);
                foreach ( $query_3->result() as $row_3 ){
                    if($row_3->a_group_level==1){
                        $this->db->insert(self::$table2,array(
                            'a_group_level' => $row_3->a_group_level,
                            'a_group_menu'  => $row_2->a_menu_id,
                            'a_group_status'=> 1
                        ));
                    }
                    else{
                        $this->db->insert(self::$table2,array(
                            'a_group_level' => $row_3->a_group_level,
                            'a_group_menu'  => $row_2->a_menu_id,
                            'a_group_status'=> 0
                        ));
                    }
                }
                return json_encode(array('success'=>true));
            }
            else{
                return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
            }
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
    
    function update($a_menu_id, $a_menu_parentId, $a_menu_name, $a_menu_uri, $a_menu_iconCls, $a_menu_type){
        $this->db->where('a_menu_id', $a_menu_id);
        $query = $this->db->update(self::$table1,array(
            'a_menu_parentId'   => $a_menu_parentId,
            'a_menu_name'       => $a_menu_name,
            'a_menu_uri'        => $a_menu_uri,
            'a_menu_iconCls'    => $a_menu_iconCls,
            'a_menu_type'       => $a_menu_type
        ));
        if($query){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
   
    function delete($a_menu_id){
        $query = $this->db->delete(self::$table1, array('a_menu_id' => $a_menu_id)); 
        if($query){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
    
    function getParent(){
        $this->db->select('a_menu_id, a_menu_name');
        $this->db->order_by('a_menu_name', 'asc');
        $query  = $this->db->get(self::$table1);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);         
    }
    
    function enumField($table, $field){
        $enums = field_enums($table, $field);
        return json_encode($enums);
    }
}

/* End of file m_menu.php */
/* Location: ./application/models/admin/m_menu.php */