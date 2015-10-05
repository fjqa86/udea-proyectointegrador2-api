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
}
