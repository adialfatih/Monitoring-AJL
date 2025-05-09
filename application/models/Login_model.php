<?php
class Login_model extends CI_Model{
    //cek username dan password login
    function cek_login($table,$where){      
        return $this->db->get_where($table,$where);
    }
    function cek_username($user){
        $dt = $this->db->get_where('user', ['username' => $user])->num_rows();
        if($dt == 1){
            return true;
        } else {
            return false;
        }
    }
    
    function filter($data){
        $str_in = strip_tags(htmlspecialchars($data));
        $arrrays = array('~', "`", '!', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '{', '[', ']', '}', '|', '\\', "'", '"', ':', ';', '<', ',', '>', '?', '/', "‘", '“');
        $str = str_replace($arrrays, '', $str_in);
        return $str;
    }

    function saved($table, $datalist){
        $this->db->insert($table,$datalist);
    }
    
}

?>