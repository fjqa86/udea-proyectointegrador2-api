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
        $productos= $this->post->get_productos_por_cat();
        foreach($productos as $producto){            
            $prods['post'] = $producto;
            $prods['meta'] = $this->post->get_post_meta2($producto->ID);
            $resultado[] = $prods;
        }        
        $data['json'] = $resultado;
       if (!$data['json']) show_404();
        $this->load->view('json', $data);
    }
    
    function comprar_carrito(){
        
        $cart = json_decode(file_get_contents('php://input'), true);
        $cart = (object) $cart;
        $result = $this->post->post_carrito($cart); 
        echo $result;
    }
    
    function login() {
        $user = json_decode(file_get_contents('php://input'));
        $this->load->model('user');
        $result = $this->user->get_by_id($user->id);
        $data['json'] = false;
        if ($result != 0) {
            if ($result->user_pass == $user->user_pass) {
                $data['json'] = true;
            }
        }
        $this->load->view('json', $data);
    }
}
