<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_operator extends CI_Model
{    
    static $table1 = 'm_operator';
     
    function __construct() {
        parent::__construct();
      //  $this->load->helper('database'); // Digunakan untuk memunculkan data Enum
    }

    function index() {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'm_operator_name';
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
	$result['total']    = $total;
	$result['rows']     = $data;
        
        return json_encode($result);          
    }   
        
    function create($m_operator_nik, $m_operator_name, $m_operator_auth) {
        $query = $this->db->insert(self::$table1,array(
            'm_operator_nik'    => $m_operator_nik,
            'm_operator_name'   => $m_operator_name,
            'm_operator_auth'   => $m_operator_auth
        ));
        if($query) {
            return json_encode(array('success'=>true));
        }
        else {
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }        
    }
    
    function update($m_operator_nik, $m_operator_name, $m_operator_auth) {
        $this->db->where('m_operator_nik', $m_operator_nik);
        $query = $this->db->update(self::$table1,array(
            'm_operator_name'   => $m_operator_name,
            'm_operator_auth'   => $m_operator_auth
        ));
        if($query) {
            return json_encode(array('success'=>true));
        }
        else {
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
    
    function delete($m_operator_nik) {
        $query = $this->db->delete(self::$table1, array('m_operator_nik' => $m_operator_nik));
        if($query) {
            return json_encode(array('success'=>true));
        }
        else {
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
    
    function getProces(){
        $query  = $this->db->get(self::$table2);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
    }        
}

/* End of file m_operator.php */
/* Location: ./application/models/master/m_operator.php */