<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servicio extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        $this->load->model('post');
    }
    
        
    function producto_lista() {
        $resultado= array();
        $posts = $this->post->get_lista_productos();
        foreach($posts as $post){            
            $post_postmeta['post'] = $post;
            $post_postmeta['meta'] = $this->post->get_post_meta($post->ID);
            $resultado[] = $post_postmeta;
        }        
        $data['json'] = $resultado;
       if (!$data['json']) show_404();
        $this->load->view('json', $data);
    } 
    
    function producto_lista2() {
        $resultado= array();
        $posts = $this->post->get_lista_productos();
        foreach($posts as $post){            
            $post_meta_tax_term['post'] = $post;
            $post_meta_tax_term['meta'] = $this->post->get_post_meta($post->ID);
            $post_meta_tax_term['relationship'] = $this->post->get_term_relationship($post->ID);
            $post_meta_tax_term['taxonomy'] = $this->post->get_term_taxonomy($post_meta_tax_term['relationship']->term_taxonomy_id);
            $post_meta_tax_term['term'] = $this->post->get_terms($post_meta_tax_term['taxonomy']->term_id);
            $resultado[] = $post_meta_tax_term;
        }        
        $data['json'] = $resultado;
       if (!$data['json']) show_404();
        $this->load->view('json', $data);
    }
}
