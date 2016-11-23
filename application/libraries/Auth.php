<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Auth{
    var $CI = NULL;
    function __construct(){
        $this->CI =& get_instance();
    }
    
    function do_login($username,$password){
        $this->CI->db->where('a_user_username',$username)
                     ->where('a_user_password=MD5("'.$password.'")','',false);
        $result = $this->CI->db->get('a_user');
        if($result->num_rows() == 0){
            return false;
        }
        else{
            $userdata = $result->row();
            $session_data = array(
                'id'        => $userdata->a_user_id,
                'nama'      => $userdata->a_user_name,
                'level'     => $userdata->a_user_level
            );
            $this->CI->session->set_userdata($session_data);
            return true;
        }
    }
    
    function is_logged_in(){
        if($this->CI->session->userdata('id') == ''){
            return false;
        }
        return true;
                
    }
    
    function restrict(){
        if($this->is_logged_in() == false){
            redirect('');//redirect to main
        }
    }
    
    function menu($idmenu){
        $a_group_level = $this->CI->session->userdata('level');
        
        $this->CI->db->select('a_group_status');
        $this->CI->db->where('a_group_level',$a_group_level)
                     ->where('a_group_menu',$idmenu);
        $result = $this->CI->db->get('a_group');
        $row    = $result->row();
        
        if($row->a_group_status == 0){
            redirect('');//redirect to main
        }
    }
    
    function do_logout(){
        $this->CI->session->sess_destroy();
    }

}
