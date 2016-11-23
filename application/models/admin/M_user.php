<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_user extends CI_Model
{    
    static $table1 = 'a_user';
    static $table2 = 'a_level';
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('security');
    }

    function index()
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'a_user_id';
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
        
        $this->db->select('a_user_id, a_user_name, a_user_username, a_user_level, a_level_name');
        $this->db->join(self::$table2, 'a_user_level=a_level_id', 'left');
        $this->db->where($cond, NULL, FALSE);
        $this->db->from(self::$table1);
        $total  = $this->db->count_all_results();
        
        $this->db->select('a_user_id, a_user_name, a_user_username, a_user_level, a_level_name');
        $this->db->join(self::$table2, 'a_user_level=a_level_id', 'left');
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
    
    function create($a_user_name, $a_user_username, $a_user_password, $a_user_level){
        $query_1 = $this->db->insert(self::$table1,array(
            'a_user_name'       => $a_user_name,
            'a_user_username'   => $a_user_username,
            'a_user_password'   => do_hash($a_user_password,'md5'),
            'a_user_level'      => $a_user_level
        ));
        if($query_1){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
    
    function update($a_user_id, $a_user_name, $a_user_username, $a_user_password, $a_user_level){        
        if($a_user_password==''){
            $this->db->where('a_user_id', $a_user_id);
            $query_1 = $this->db->update(self::$table1,array(
                'a_user_name'       => $a_user_name,
                'a_user_username'   => $a_user_username,
                'a_user_level'      => $a_user_level
            ));
            if($query_1){
                return json_encode(array('success'=>true));
            }
            else{
                return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
            }
        }
        else{
            $this->db->where('a_user_id', $a_user_id);
            $query_1 = $this->db->update(self::$table1,array(
                'a_user_name'       => $a_user_name,
                'a_user_username'   => $a_user_username,
                'a_user_password'   => do_hash($a_user_password,'md5'),
                'a_user_level'      => $a_user_level
            ));
            if($query_1){
                return json_encode(array('success'=>true));
            }
            else{
                return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
            }
        }
    }
    
    function delete($a_user_id){
        $query_1 = $this->db->delete(self::$table1, array('a_user_id' => $a_user_id));
        if($query_1){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
    
    function reset($a_user_id, $a_user_password){
        $this->db->where('a_user_id', $a_user_id);
        $query_1 = $this->db->update(self::$table1,array(
            'a_user_password'   => do_hash($a_user_password,'md5')
        ));
        if($query_1){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
    
    function getLevel(){
        $query_1  = $this->db->get(self::$table2);
                   
        $data = array();
        foreach ( $query_1->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);         
    }
}

/* End of file m_user.php */
/* Location: ./application/models/admin/m_user.php */