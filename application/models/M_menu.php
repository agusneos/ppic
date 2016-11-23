<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_menu extends CI_Model
{
    static $table1  = 'a_menu';
    static $table2  = 'a_group';
    static $table3  = 'a_level';
    static $table4  = 'a_user';
    
    public function __construct() {
        parent::__construct();
    }
    
    function ambil_menu($id_user){
        $id = isset($_POST['id']) ? intval($_POST['id']) : 'id';        
        
        $this->db->join(self::$table1, 'a_group_menu=a_menu_id', 'left')
                 ->join(self::$table4, 'a_group_level=a_user_level', 'left');
        $this->db->where('a_menu_parentId', $id)
                 ->where('a_user_id', $id_user)
                 ->where('a_group_status', 1);        
        $rs = $this->db->get(self::$table2);
        $result  = array();
        foreach ( $rs->result() as $row ){
            $node = array();
            $node['id']         = $row->a_menu_id;
            $node['text']       = $row->a_menu_name;
            $node['state']      = $this->has_child($row->a_menu_id) ? 'closed' : 'open';
            $node['uri']        = $this->link_menu($row->a_menu_uri);
            $node['iconCls']    = $row->a_menu_iconCls;
            $node['type']       = $row->a_menu_type;
            array_push($result, $node);
        }
        return json_encode($result);
    }

    function has_child($id){
        $this->db->where('a_menu_parentId',$id);
        $this->db->from(self::$table1);
        $rs = $this->db->count_all_results();
        return $rs > 0 ? true : false;
    }
    
    function link_menu($link){
        if ($link != ''){
            return site_url($link);
        } 
        else{
            return 'kosong';
        }
    }
    
}

/* End of file m_menu.php */
/* Location: ./application/models/m_menu.php */