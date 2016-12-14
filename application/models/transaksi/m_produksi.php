<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_produksi extends CI_Model
{    
    static $table1  = 'm_item';

    function __construct() {
        parent::__construct();
        //$this->load->helper('database'); // Digunakan untuk memunculkan data Enum
    }
    
    function getItem()
    {
        $this->db->select('m_item_qty_box');
        $this->db->where('m_item_id', '10000001');
        $query  = $this->db->get(self::$table1);
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }       
        return json_encode($data);
       // return $data;
    }
    
}

/* End of file m_po.php */
/* Location: ./application/models/transaksi/m_produksi.php */