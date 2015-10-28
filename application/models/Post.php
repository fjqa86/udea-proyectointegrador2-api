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
    
    function get_productos_por_cat() {
        $this->db->select('wp_posts.ID, wp_posts.post_author, wp_posts.post_date, '
                . 'wp_posts.post_content, wp_posts.post_title, wp_posts.post_excerpt, '
                . 'wp_posts.post_status, wp_posts.comment_status, wp_posts.ping_status, '
                . 'wp_posts.post_name, wp_posts.guid, wp_posts.post_type, wp_terms.term_id, wp_terms.name');
        $this->db->from('wp_posts');
        $this->db->join('wp_term_relationships', 'wp_posts.ID = wp_term_relationships.object_id', 'inner');
        $this->db->join('wp_term_taxonomy', 'wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id', 'inner');
        $this->db->join('wp_terms', 'wp_term_taxonomy.term_id = wp_terms.term_id', 'inner');
        $where = "wp_posts.post_type='product' AND wp_term_taxonomy.taxonomy='product_cat'";
        $this->db->where($where);
        
        return $this->db->get()->result();
    }
    
    function get_post_meta2($id) {
        $this->db->where('post_id =', $id);
        $ids = array(2908, 2909, 2937, 2938, 2939, 2940, 2943, 2944, 2945, 2946,
            2951, 2952, 2955, 2957, 2959, 2962, 3373, 3374, 3375, 3376, 3377, 3378,
            3379, 3380, 3381, 3382, 3383, 3384, 3385);
        $this->db->where_in('meta_id', $ids);
        return $this->db->get('wp_postmeta')->result();
    }

}
