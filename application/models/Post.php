<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Post extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_lista_productos() {
        $this->db->where('post_type =', "product");
        $this->db->where('ID >', 200);
        return $this->db->get('wp_posts')->result();
    }

    function get_post_meta($id) {
        $this->db->where('post_id =', $id);
        return $this->db->get('wp_postmeta')->result();
    }
    
    function get_term_relationship($id) {
        $this->db->where('object_id =', $id);
        return $this->db->get('wp_term_relationships')->result();
    }
    
    function get_term_taxonomy($id) {
        $this->db->where('term_taxonomy_id =', $id);
        return $this->db->get('wp_term_taxonomy')->result();
    }
    
    function get_terms($id) {
        $this->db->where('term_id =', $id);
        return $this->db->get('wp_terms')->result();
    }

}
