<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_marking extends CI_Model
{    
    static $table1 = 'm_marking';
     
    public function __construct() {
        parent::__construct();
      //  $this->load->helper('database'); // Digunakan untuk memunculkan data Enum
    }

    function index()
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'm_marking_id';
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
        
        $this->db->select('m_marking_id, m_marking_name, CONCAT("<img src=", m_marking_img, " />") AS m_marking_img', FALSE);
        $this->db->where($cond, NULL, FALSE);
        $total  = $this->db->count_all_results(self::$table1);
        
        $this->db->select('m_marking_id, m_marking_name, CONCAT("<img src=", m_marking_img, " />") AS m_marking_img', FALSE);
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
        
    function create($m_marking_name, $m_marking_img){
        $query = $this->db->insert(self::$table1,array(
            'm_marking_name'    => $m_marking_name,
            'm_marking_img'     => $m_marking_img
        ));
        if($query){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }        
    }
    
    function update($m_marking_id, $m_marking_name, $m_marking_img){
        if($m_marking_img==''){
            $this->db->where('m_marking_id', $m_marking_id);
            $query = $this->db->update(self::$table1,array(
                'm_marking_name'    => $m_marking_name
            ));
            if($query){
                return json_encode(array('success'=>true));
            }
            else{
                return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
            }
        }
        else{
            $this->db->where('m_marking_id', $m_marking_id);
            $query = $this->db->update(self::$table1,array(
                'm_marking_name'    => $m_marking_name,
                'm_marking_img'    => $m_marking_img
            ));
            if($query){
                return json_encode(array('success'=>true));
            }
            else{
                return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
            }
        }
        
    }
    
    function delete($m_marking_id) {
        $query_1 = $this->db->delete(self::$table1, array('m_marking_id' => $m_marking_id));
        if($query_1){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
    
}

/* End of file m_marking.php */
/* Location: ./application/models/master/m_marking.php */