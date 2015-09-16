<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Post extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_lista_productos() {
        $this->db->select('title, content, date');
        $this->db->where('post_type', "product");
        $this->db->from('wp_posts as p');
        $this->db->join('wp_postmeta pm', 'p.ID = pm.post_id');
        return $this->db->get()->result();
    }

}
