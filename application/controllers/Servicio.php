<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servicio extends CI_Controller{
    /*
     * Constructor
     */
    function __construct() {
        parent::__construct();
        $this->load->model('post');
    }
    
/*
 * Servicio web: obtener_productos
 * Autor: Francisco Quintero y Alan Arboleda
 * Tipo: GET
 * Descripcion: devuelve la lista de todos los productos almacenados en la base
 *              de datos, con su respectiva información asociada como precio,
 *              cantidad en stock, etc.
 * Parametros: ninguno
 */
    function producto_lista() {
        $resultado= array(); // Arreglo con la información de los productos
        //
        // Se obtiene el listado de todos los productos con su información de 
        // identificacion y la categoria a la que pertenece
        $productos= $this->post->get_productos_por_cat();
        
        // Para cada producto en la lista, se obtiene la informacion meta en la
        // base de datos. Esta informacion incluye, entre otros, los identificadores
        // de las imagenes del producto, los precios, descuentos, colores, tallas
        foreach($productos as $producto){            
            $prods['post'] = $producto;
            $prods['meta'] = $this->post->get_post_meta($producto->ID);
            $resultado[] = $prods;
        }        
        $data['json'] = $resultado;
       if (!$data['json']) show_404();
       // Se retorna la informacion obtenida
        $this->load->view('json', $data);
    }
    
    /*
    * Servicio web: comprar_carrito
    * Autor: Alan Arboleda
    * Tipo: POST
    * Descripcion: registra en la base de datos toda la informacion relacionada
    *              con la compra de los productos existentes en el carrito de
    *              compras: la informacion de los productos y la informacion del
    *              comprador
    * Parametros: recibe un archivo tipo JSON con la informacion del carrito
    */
    function comprar_carrito(){
        // Se decodifica el archivo JSON enviado al servicio
        $cart = json_decode(file_get_contents('php://input'), true);
        
        $cart = (object) $cart; // Se hace un cast a objeto para manejo de informacion
        
        // Se intenta registrar la informacion de la compra y se retorna verdadero
        // si el proceso fue exitoso. Se retorna falso en caso contrario.
        $result = $this->post->post_carrito($cart); 
        echo $result;
    }
    
    /*
    * Servicio web: login
    * Autor: Francisco Quintero
    * Tipo: GET
    * Descripcion: verifica la existencia de un usuario en la base de datos, y
    *              la correspondencia de la contraseña asociada con la informacion
    *              entregada en el archivo JSON
    * Parametros: recibe un archivo tipo JSON con la informacion del usuario
    */
    function login() {
        // Se decodifica el archivo JSON enviado al servicio
        $user = json_decode(file_get_contents('php://input'));
        
        $this->load->model('user');
        
        // Se verifica el nombre de usuario y contrasena del archivo JSON con la
        // informacion registrada en la base de datos. Se retorna verdadero si la
        // verificacion fue correcta
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
