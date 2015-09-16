<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servicio extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        $this->load->model('post');
    }
    
        
    function producto_lista() {       
       $data['json'] = $this->post->get_lista_productos();
       if (!$data['json']) show_404();
        $this->load->view('json', $data);
    }     
}
