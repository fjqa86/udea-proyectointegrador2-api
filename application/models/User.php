<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    /*
     * Funcion para obtener la informacion de un usuario identificado con el id
     * $id
     */
    function get_by_id($id) {
        $this->db->where('user_login =', $id); 
        $query = $this->db->get('wp_users');
        if ($query->num_rows() > 0) {
            $query->result();
        }
        return 0;
    }
    
    /*
     * Funcion para obtener la informacion de un usuario a partir de su email
     */
    function get_by_email($email) {
        $this->db->where('user_email =', $email);        
        return $this->db->get('wp_users')->result();
    }
}