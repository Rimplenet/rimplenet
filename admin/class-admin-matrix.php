<?php

class Rimplenet_Admin_Matrix{
    public $admin_post_page_type, $viewed_url, $post_id;
    
    public function __construct() {
        
        $this->viewed_url = $_SERVER['REQUEST_URI'];
        $this->admin_post_page_type = sanitize_text_field($_GET["rimplenettransaction_type"] ?? '');
        $this->post_id = sanitize_text_field($_GET['post'] ?? '');
        
        add_action('init',  array($this,'required_admin_functions_loaded'));
        //save meta value with save post hook when Template Settings is POSTED
        add_action('save_post_rimplenettransaction',  array($this,'save_matrix_settings'), 10,3 );
        
        
    }
    
    function required_admin_functions_loaded() {
         if($this->admin_post_page_type=='rimplenet-mlm-matrix' OR has_term('rimplenet-mlm-matrix','rimplenettransaction_type',$this->post_id)){
          //Register Rimplenet Template Settings Meta Box
          add_action('add_meta_boxes',  array($this,'rimplenet_template_register_meta_box'));
        
        }
    }
    function rimplenet_template_register_meta_box() {
        
        add_meta_box( 'rimplenet-admin-matrix-settings-meta-box', esc_html__( 'Matrix Settings', 'rimplenet' ),   array($this,'rimplenet_admin_matrix_meta_box_callback'), 'rimplenettransaction', 'normal', 'high' );
        add_meta_box( 'rimplenet-admin-matrix-tree-shortcode-meta-box', esc_html__( 'Matrix Tree Shortcode', 'rimplenet' ),   array($this,'rimplenet_admin_matrix_tree_shortcode_meta_box_callback'), 'rimplenettransaction', 'side', 'high' );  
        
    }
    
    function rimplenet_admin_matrix_meta_box_callback( $meta_id ) {
        
       include_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/partials/metabox-admin-matrix-settings.php';
    
     }
    
    function rimplenet_admin_matrix_tree_shortcode_meta_box_callback($meta_id) {
        
        $wallet_post_id = $meta_id->ID;
        $wallet_id = get_post_meta($wallet_post_id, 'rimplenet_wallet_id', true);
        $user_balance_shortcode  = '[rimplenet-wallet action="view_balance" wallet_id="'.$wallet_id.'"]';
        if(!empty($this->post_id) AND has_term('rimplenet-mlm-matrix','rimplenettransaction_type',$this->post_id)){
          echo '<code class="rimplenet_click_to_copy">'.$user_balance_shortcode.'</code>';
        }
        else{
            echo "<p style='color:red;'>Matrix Tree Shortcode for displaying matrix tree will appear here after publish</p>";
        }
    
     }
     
    function save_matrix_settings($post_id, $post, $update){
        
      $rimplenettransaction_type = sanitize_text_field($_POST['rimplenettransaction_type']);
      if($rimplenettransaction_type=="rimplenet-mlm-matrix"){ 
        $MATRIX_CAT_NAME = 'RIMPLENET MLM MATRIX';
        wp_set_object_terms($post_id, $MATRIX_CAT_NAME, 'rimplenettransaction_type');
        
        $matrix_name = $post->post_title;
        $matrix_desc = $post->post_content;
        $matrix_id = $post_id;
        $rimplenet_matrix_width = sanitize_text_field( $_POST['rimplenet_matrix_width'] );
        $rimplenet_matrix_depth = sanitize_text_field( $_POST['rimplenet_matrix_depth'] );
        $user_placement_method_in_matrix = sanitize_text_field( $_POST['user_placement_method_in_matrix'] );
        $rimplenet_rules_before_matrix_entry = sanitize_text_field( $_POST['rimplenet_rules_before_matrix_entry'] );
        $rimplenet_rules_inside_matrix = sanitize_text_field( $_POST['rimplenet_rules_inside_matrix'] );
        $rimplenet_rules_after_matrix_complete = sanitize_text_field( $_POST['rimplenet_rules_after_matrix_complete'] );
        
        $create_matrix_tree_page = sanitize_text_field( $_POST['create_matrix_tree_page'] );
        
        $metas = array( 
              'rimplenettransaction_type' => 'rimplenet-mlm-matrix',
              'width' => $rimplenet_matrix_width,
              'depth' => $rimplenet_matrix_depth,
              'user_placement_method_in_matrix' => $user_placement_method_in_matrix,
              
              'rules_before_matrix_entry' => $rimplenet_rules_before_matrix_entry,
              'rules_inside_matrix' => $rimplenet_rules_inside_matrix,
              'rules_after_matrix_complete' => $rimplenet_rules_after_matrix_complete,
            );
            
         foreach ($metas as $key => $value) {
          update_post_meta($post_id, $key, $value);
         }
        
          if($create_matrix_tree_page=="yes"){//If yes Create Matrix Tree Page
            $page_content = '[rimplenet-draw-mlm-tree default="'.$matrix_id.'"]';
            $args_1 = array(
            		'post_title' => $matrix_name,
            		'post_content' => $page_content,
            		'post_status' => 'publish',
            		'post_type' => "page",
            		) ;  
            $matrix_page_id = wp_insert_post( $args_1 );
            wp_set_object_terms($matrix_page_id, $MATRIX_CAT_NAME, 'category' );
            update_post_meta($matrix_page_id,'linked_matrix_id', $matrix_id);
            update_post_meta($matrix_id,'linked_matrix_tree_page_id', $matrix_page_id);
            
          }
         
          if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
           //if Woocommerce is Installed and Activated
           $create_matrix_entry_woocommerce_product = sanitize_text_field( $_POST['create_matrix_entry_woocommerce_product'] );
           $rimplenet_matrix_price = sanitize_text_field( $_POST['rimplenet_matrix_price'] );
           $use_rimplenet_woocommerce_template = sanitize_text_field( $_POST['use_rimplenet_woocommerce_template'] );
           $rimplenet_order_redirection_page = sanitize_text_field( $_POST['rimplenet_order_redirection_page'] );
              if($create_matrix_entry_woocommerce_product=="yes"){//If yes Create Matrix Product          
                $args_2 = array(
                		'post_title' => $matrix_name,
                		'post_content' => $matrix_desc,
                		'post_status' => 'publish',
                		'post_type' => "product",
                		) ;  
                $matrix_product_id = wp_insert_post( $args_2 );
                wp_set_object_terms($matrix_product_id, $MATRIX_CAT_NAME, 'product_cat' );
                
                $product_metas = array(
                  '_visibility' => 'visible',
                  '_stock_status' => 'instock',
                  'total_sales' => '0',
                  '_downloadable' => 'no',
                  '_virtual' => 'yes',
                  '_regular_price' => $rimplenet_matrix_price,
                  '_sale_price' => $rimplenet_matrix_price,
                  '_purchase_note' => '',
                  '_featured' => 'no',
                  '_weight' => '',
                  '_length' => '',
                  '_width' => '',
                  '_height' => '',
                  '_sku' => '',
                  '_product_attributes' => array(),
                  '_sale_price_dates_from' => '',
                  '_sale_price_dates_to' => '',
                  '_price' => $rimplenet_matrix_price,
                  'rimplenet_product_min_price' => $rimplenet_matrix_min_price_form,
                  'rimplenet_product_max_price' => $rimplenet_matrix_max_price_form,
                  'rimplenet_cur' => 'woocommerce_base_cur',
                  'use_rimplenet_woocommerce_template' => $use_rimplenet_woocommerce_template,
                  'rimplenet_order_redirection_page' => $rimplenet_order_redirection_page,
                  'rimplenet_rules_inside_matrix_and_linked_product_ordered' => $rimplenet_rules_inside_matrix_and_linked_product_ordered_form,
                  'apply_rules_per_woocommerce_order_instance' => $apply_rules_per_woocommerce_order_instance,
                  'apply_rules_per_woocommerce_order_product_quantity_instance' => $apply_rules_per_woocommerce_order_product_quantity_instance,
                  '_sold_individually' => TRUE,
                  '_manage_stock' => 'no',
                  '_backorders' => 'no',
                  '_stock' => ''
                 );
                 foreach ($product_metas as $key => $value) {
                  update_post_meta($matrix_product_id, $key, $value);
                 }
              }
              
          }
        
       }
    }
  
        
}


$Rimplenet_Admin_Matrix = new Rimplenet_Admin_Matrix();