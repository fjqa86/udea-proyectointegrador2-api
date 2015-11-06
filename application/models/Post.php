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
    
    function post_carrito($cart){
       
       // Se insertar la orden en la tabla wp_post
       $dateS = date('Format String','Y-m-d H:i:s');
       $data = array(
           'post_author' => 1 ,
           'IDProd' => 'My title' ,
           'post_date' => $dateS ,
           'post_date_gmt' => $dateS ,
           'post_status' => 'wc-processing' ,
           'comment_status' => 'open' ,
           'ping_status' => 'closed' ,
           'post_modified' => $dateS ,
           'post_modified_gmt' => $dateS ,
           'menu_order' => 0 ,
           'post_type' => 'shop_order' 
        );
        $this->db->insert('wp_posts', $data);
        $orderID = $this->db->insert_id;
        
        insert_postmeta($orderID,$cart); //se insertan los detalles de la orden

        //Se insertan los productos asociados a la orden y su cantidad
        foreach ($cart as $key => $product){
            $product->id; // etc.            
            
            $data = array(
               'order_item_name' => $product->name ,
               'order_item_type' => 'line_item' ,
               'order_id' => $orderID
            );
            $this->db->insert('wp_woocommerce_order_items', $data);
            $orderItemID = $this->db->insert_id;
            
            $data = array(
                array(
                    'order_item_id' => $orderItemID ,
                    'meta_key' => '_qty' ,
                    'meta_value' => $product->quantity
                ),
                array(
                    'post_id' => $orderID ,
                    'meta_key' => '_product_id' ,
                    'meta_value' => $product->productID
                ),
                array(
                    'post_id' => $orderID ,
                    'meta_key' => '_variation_id' ,
                    'meta_value' => $product->varID
                ),
                array(
                    'post_id' => $orderID ,
                    'meta_key' => '_line_total' ,
                    'meta_value' => $product->lineTotal
                ),
                array( // Si tiene talla
                    'post_id' => $orderID ,
                    'meta_key' => 'tallas' ,
                    'meta_value' => $product->talla
                ),
                array( // Si tiene color
                    'post_id' => $orderID ,
                    'meta_key' => 'colores' ,
                    'meta_value' => $product->color
                )
            );
            $this->db->insert_batch('wp_woocommerce_order_items', $data);
            update_Stock($product->productID,$product->quantity);
        }
    }
    
    function update_Stock($productID, $quantity){
        $this->db->select('meta_value');
        $this->db->where('post_id =', $productID);
        $this->db->where('meta_key =', '_stock');
        $actualQty = $this->db->get('wp_postmeta')->result();
        $newQty = $actualQty - $quantity;
        
        if ($newQty == 0){
            $this->db->where('post_id', $productID);
            $this->db->update('wp_postmeta', array('_stock_status' => 'outofstock'));
        }
        $this->db->where('post_id', $productID);
        $this->db->update('wp_postmeta', array('_stock' => $newQty)); 
    }
    
    function insert_postmeta($orderID, $cart){ 
        //Se inserta cada detalle de la orden en la tabla wp_postmeta
        $data = array(
            array(
               'post_id' => $orderID ,
               'meta_key' => '_order_currency' ,
               'meta_value' => 'COP'
            ),
            array(
               'post_id' => $orderID ,
               'meta_key' => '_billing_country' ,
               'meta_value' => 'CO'
            ),
            array(
               'post_id' => $orderID ,
               'meta_key' => '_billing_first_name' ,
               'meta_value' => $cart[0]->firstName
            ),
            array(
               'post_id' => $orderID ,
               'meta_key' => '_billing_last_name' ,
               'meta_value' => $cart[0]->lastName
            ),
            array(
               'post_id' => $orderID ,
               'meta_key' => '_billing_company' ,
               'meta_value' => $cart[0]->company
            ),
            array(
               'post_id' => $orderID ,
               'meta_key' => '_billing_address_1' ,
               'meta_value' => $cart[0]->address1
            ),
            array(
               'post_id' => $orderID ,
               'meta_key' => '_billing_address_2' ,
               'meta_value' => $cart[0]->address2
            ),
            array(
               'post_id' => $orderID ,
               'meta_key' => '_billing_city' ,
               'meta_value' => $cart[0]->city
            ),
            array(
               'post_id' => $orderID ,
               'meta_key' => '_billing_state' ,
               'meta_value' => $cart[0]->state
            ),
            array(
               'post_id' => $orderID ,
               'meta_key' => '_billing_email' ,
               'meta_value' => $cart[0]->email
            ),
            array(
               'post_id' => $orderID ,
               'meta_key' => '_billing_phone' ,
               'meta_value' => $cart[0]->phone
            ),
            array(
               'post_id' => $orderID ,
               'meta_key' => '_payment_method' ,
               'meta_value' => $cart[0]->paym
            ),
            array(
               'post_id' => $orderID ,
               'meta_key' => '_cart_discount' ,
               'meta_value' => $cart[0]->discount
            ),
            array(
               'post_id' => $orderID ,
               'meta_key' => '_order_shipping_tax' ,
               'meta_value' => $cart[0]->shippingTax
            ),
            array(
               'post_id' => $orderID ,
               'meta_key' => '_order_total' ,
               'meta_value' => $cart[0]->orderTotal
            ),
            array(
               'post_id' => $orderID ,
               'meta_key' => '_recorded_sales' ,
               'meta_value' => $cart[0]->recorded
            )           
         );
        $this->db->insert_batch('wp_postmeta', $data);
    }

}
