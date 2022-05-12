<?php

    /**
     * Register all actions and filters for the plugin
     *
     * @link       https://bunnyviolablue.com
     * @since      1.0.0
     *
     * @package    Rimplenet_Mlm
     * @subpackage Rimplenet_Mlm/includes
     */
    
    /**
     * Register Postype
     *
     * @package    Rimplenet_Mlm
     * @subpackage Rimplenet_Mlm/includes
     * @author     Tech Celebrity <techcelebrity@bunnyviolablue.com>
     */
    
    namespace PostType;
     
    /**
     * Class Rimplenet_General_Acts
     * @package PostType
     *
     * Use actual name of post type for
     * easy readability.
     *
     * Potential conflicts removed by namespace
     */
    class RimplenetRegisterCPT {
     
        /**
         * @var string
         *
         * Set post type params
         */
        private $unique_name        = 'rimplenettransaction';
        private $tax_unique_name    = 'rimplenettransaction_type';
        private $type               = 'rimplenettransaction';
        private $slug               = 'rimplenettransaction';
        private $name               = 'Rimplenet E-banking, E-Wallet, Investment, MLM, Matrix';
        private $singular_name      = 'Rimplenet Transaction';
        private $rimplenettransaction_type, $post_id;
        
        public function __construct() {
            add_action('init', array($this, 'register'));// Register CPT
            add_filter( 'manage_edit-'.$this->type.'_columns',array($this, 'set_columns'), 10, 1) ;// Admin set post columns
            add_action( 'manage_'.$this->type.'_posts_custom_column', array($this, 'edit_columns'), 10, 2 );//Admin edit post columns
            add_action( 'admin_menu',  array($this,'change_rimplenet_cpt_edit_post_labels' ),10);//Change Labels Edit Post  based on viewed url
            add_action( 'enter_title_here',  array($this,'change_rimplenet_cpt_edit_post_title' ),10,2);//Change Labels Edit Post  based on viewed url
            
        }
       
        
        public function change_rimplenet_cpt_edit_post_labels() {
            global $wp_post_types;
            $labels = &$wp_post_types['rimplenettransaction']->labels;
        
           if($this->rimplenettransaction_type=='rimplenet-mlm-matrix'){
               
            $labels->name = __('MLM Matrix');
            $labels->singular_name = __('MLM Matrix','rimplenet');
            $labels->all_items = __('MLM Matrix','rimplenet');
            $labels->add_new = __('Add New MLM Matrix','rimplenet');
            $labels->new_item = __('Add New MLM Matrix','rimplenet');
            $labels->add_new_item = __('Add New MLM Matrix','rimplenet');
           } 
           elseif($this->rimplenettransaction_type=='rimplenet-mlm-packages'){
               
            $labels->name = __('Investment Packages');
            $labels->singular_name = __('Investment Package','rimplenet');
            $labels->all_items = __('Investment Packages','rimplenet');
            $labels->add_new = __('Add New Investment Package','rimplenet');
            $labels->new_item = __('Add New Investment Package','rimplenet');
            $labels->add_new_item = __('Add New Investment Package','rimplenet');
            
           }
           else{
               
            $labels->name = __('Wallets','rimplenet');
            $labels->singular_name = __('Wallet','rimplenet');
            $labels->all_items = __('All Wallets','rimplenet');
            $labels->add_new = __('Add New Wallet','rimplenet');
            $labels->new_item = __('Add New Wallet','rimplenet');
            $labels->add_new_item = __('Add New Wallet','rimplenet');
           }  
          
        }
         
        public function change_rimplenet_cpt_edit_post_title( $title, $post ){ 
           if  ( 'rimplenettransaction' == $post->post_type ) { 
            if($this->rimplenettransaction_type=='rimplenet-mlm-matrix'){
              $title = __("Enter Matrix Name","rimplenet"); 
            }
            elseif($this->rimplenettransaction_type=='rimplenet-mlm-packages'){
              $title = __("Enter Investment Package Name","rimplenet"); 
            }
            else{
              $title =  __("Enter Wallet Name","rimplenet"); 
            }
               
           }
        
           return $title;
        }
        
        public function register() {
            $labels = array(
                'name'                  => $this->name,
                'singular_name'         => $this->singular_name,
                'add_new'               => 'Add New',
                'add_new_item'          => 'Add New '   . $this->singular_name,
                'edit_item'             => 'Edit '      . $this->singular_name,
                'new_item'              => 'New '       . $this->singular_name,
                'all_items'             => 'All Transactions',
                'view_item'             => 'View Rimplenet Transaction',
                'search_items'          => 'Search ',
                'not_found'             => 'No Rimplenet transaction found',
                'not_found_in_trash'    => 'No Rimplenet transaction found in Trash',
                'parent_item_colon'     => '',
                'menu_name'             => $this->name
                );
     
             
              $args = array(
                'labels' =>  $labels,
                'public' => false,
                'publicly_queryable' => false,
                'exclude_from_search'=>true,
                'show_ui' => true,
                'query_var' => true,
                'rewrite' => true,
                'capability_type' => 'post',
                'capabilities' => array(
                'create_posts1' => 'do_not_allow', // false < WP 4.5
              ),
                'map_meta_cap' => true, // Set to `false`, if users are not allowed to edit/delete existing posts
    
                'hierarchical' => false,
                'menu_position' => null,
                'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields')
                ); 
             
              register_post_type( $this->unique_name , $args );
               /*
    
            Below adds taxonomy called 
    
            */
            register_taxonomy($this->tax_unique_name, array($this->unique_name), array("hierarchical" => true, "label" => $this->singular_name." Type", "singular_label" => $this->singular_name." Type", "rewrite" => true));
        }
    
        public function set_columns($columns) {
            // Set/unset post type table columns here
     
            return $columns;
        }
     
        
        public function edit_columns($column, $post_id) {
            // Post type table column content code here
        }
 
  }
     
  // Instantiate class, creating post type
  if (!class_exists('RimplenetRegisterCPT' )){
        $RimplenetRegisterCPT = new RimplenetRegisterCPT();
   }