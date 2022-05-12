<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://bunnyviolablue.com
 * @since      1.0.0
 *
 * @package    Rimplenet_Mlm
 * @subpackage Rimplenet_Mlm/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Rimplenet_Mlm
 * @subpackage Rimplenet_Mlm/public
 * @author     Tech Celebrity <techcelebrity@bunnyviolablue.com>
 */
class Rimplenet_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

        //add_shortcode('rimplenet-dashboard', array($this, 'DesignDashboard'));
        add_shortcode('rimplenet-display-info', array($this, 'DesignDisplayInfo'));
        add_shortcode('rimplenet-display-user-meta', array($this, 'DesignDisplayUserMeta'));
        add_shortcode('rimplenet-display-post-meta', array($this, 'DesignDisplayPostMeta'));
        add_shortcode('rimplenet-modal', array($this, 'modalPop'));
        
		add_action( 'wp_nav_menu_item_custom_fields', array($this,'nav_menu_custom_fields'), 10, 2 );
		add_filter( 'wp_setup_nav_menu_item',array($this,'nav_menu_setup_custom_fields' ));
		add_action( 'wp_update_nav_menu_item', array($this,'nav_menu_update'), 10, 2 );

	}


 public function modalPop($atts) {

    $atts = shortcode_atts( array(

        'title' => 'No title specified',
        'content' => 'No content specified',
        'launchtext' => 'VIEW MODAL',
        'extralink' => '#',
        'extralinktext' => 'VISIT',
        'class' => '',

    ), $atts );


      ob_start();
        
      $title = $atts['title'];
      $content = apply_filters('the_content', $atts['content']);
        
      
      if(is_numeric($atts['content'])){
          
        $post_id = $atts['content']; 
        $ret_post   = get_post($post_id);
        if(!isset($title)){ $title = $ret_post->post_title;}
        $content =  apply_filters( 'the_content', $ret_post->post_content );
        
      }
      
	  
     $modal_id =  'rimplenet-modal-'.$post_id;
   
    ?>

<div class="rimplenetmlm">
 <div class="container">
  <?php if(!empty($atts['launchtext'])){ ?>
  <button type="button" class="<?php echo $atts['class']; ?>" data-toggle="modal" data-target="#<?php echo $modal_id; ?>">
    <?php echo $atts['launchtext']; ?>
  </button>
  <?php } ?>

  <!-- The Modal -->
  <div class="modal fade" id="<?php echo $modal_id; ?>">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h2 class="modal-title"><?php echo $title; ?></h2>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
          <?php echo $content; ?>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
 </div>
</div>


    <?php
    
    wp_reset_postdata();
	$output = ob_get_clean();

	return $output;

}

public function DesignDisplayUserMeta($atts) {
    global $current_user, $wp;
    wp_get_current_user();
    $atts = shortcode_atts( array(
        'field' => 'user_login',
        'user' => $current_user->ID,
        'single' => 'yes',
    ), $atts );
    
    $user_id = $atts['user'];
    $key = $atts['field'];
    $single = $atts['single'];
    ob_start();
    
    if($single=='yes'){
     $single = true;
     $userinfo = get_user_by('id', $user_id );
     $meta = $userinfo->$key;
    }
    else{
     $meta = get_user_meta($user_id, $key);
    }
    
    echo $meta;
    
	$output = ob_get_clean();
	return $output;

	}

   
public function DesignDisplayPostMeta($atts) {
    global $current_user, $post, $wp;
    wp_get_current_user();
    $atts = shortcode_atts( array(
        'field' => '',
        'post_id' => $post->ID,
        'single' => 'yes',
    ), $atts );
    
    $post_id = $atts['post_id'];
    $key = $atts['field'];
    $single = $atts['single'];
    ob_start();
    
    if($single=='yes'){
     $single = true;
    }
     $meta = get_post_meta($post_id, $key, $single);
     echo $meta;
    
	$output = ob_get_clean();
	return $output;

	}

        
function nav_menu_custom_fields( $item_id, $item ) {
 
	wp_nonce_field( 'rimplenet_menu_meta_nonce', '_rimplenet_menu_meta_nonce_name' );
	$custom_menu_meta = get_post_meta( $item_id, '_rimplenet_menu_meta', true );
	?>
 
	<input type="hidden" name="rimplenet-menu-meta-nonce" value="<?php echo wp_create_nonce( 'rimplenet-menu-meta-name' ); ?>" />
 
	<div class="field-rimplenet_menu_meta description-wide" style="margin: 5px 0;">
	    <span class="description"><?php _e('Input BNVB Icon Name - Icons @ <a href="https://material.io/resources/icons/" target="_blank">https://material.io/resources/icons/</a> ', 'rimplenet-menu-meta' ); ?></span>
	    <br>
	    <input type="hidden" class="nav-menu-id" value="<?php echo $item_id ;?>" />
 
	    <div class="logged-input-holder">
	        <input class="widefat edit-menu-item-rimplenet" type="text" name="rimplenet_menu_meta[<?php echo $item_id ;?>]" id="rimplenet-menu-meta-for-<?php echo $item_id ;?>" placeholder="account_box" value="<?php echo esc_attr( $custom_menu_meta ); ?>" />
	        <label for="rimplenet-menu-meta-for-<?php echo $item_id ;?>">
	            <?php _e( 'Input the icon_name, the icon may only appear when used with BNVB dashboard Menu', 'rimplenet-menu-meta'); ?>
	        </label>
	    </div>
	</div>
 
	<?php
}

function nav_menu_setup_custom_fields($menu_item) {
    $menu_item->custom = get_post_meta( $menu_item->ID, '_rimplenet_menu_meta', true );
    return $menu_item;
}

function nav_menu_update( $menu_id, $menu_item_db_id ) {
 
	// Verify this came from our screen and with proper authorization.
	if ( ! isset( $_POST['_rimplenet_menu_meta_nonce_name'] ) || ! wp_verify_nonce( $_POST['_rimplenet_menu_meta_nonce_name'], 'rimplenet_menu_meta_nonce' ) ) {
		return $menu_id;
	}
 
	if ( isset( $_POST['rimplenet_menu_meta'][$menu_item_db_id]  ) ) {
		$sanitized_data = sanitize_text_field( $_POST['rimplenet_menu_meta'][$menu_item_db_id] );
		update_post_meta( $menu_item_db_id, '_rimplenet_menu_meta', $sanitized_data );
	} else {
		delete_post_meta( $menu_item_db_id, '_rimplenet_menu_meta' );
	}
}


 public function DesignDisplayInfo($atts) {
	        

	    ob_start();

	    include plugin_dir_path( __FILE__ ) . 'layouts/design-rimplenet-relevant-info.php';
	     
	    $output = ob_get_clean();

	    return $output;
	  
 }
 
 public function DesignDashboard($atts) {
	        

	    ob_start();

	    //include plugin_dir_path( __FILE__ ) . 'layouts/design-dashboard.php';
	     
	    $output = ob_get_clean();

	    return $output;
	  
 }


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rimplenet_Mlm_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rimplenet_Mlm_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


		wp_enqueue_style($this->plugin_name.'material-kit', plugin_dir_url( __FILE__ ) . 'css/material-kit.css', array(), $this->version, 'all' );

		wp_enqueue_style($this->plugin_name.'-bootstrap-default', plugin_dir_url( __FILE__ ) . 'css/rimplenet-bootstrap-default.css', array(), $this->version, 'all' );
		
		wp_enqueue_style( $this->plugin_name.'-matrix-tree', plugin_dir_url( __FILE__ ) . 'css/rimplenet-mlm-style-matrix-tree.css', array(), $this->version, 'all' );
        
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rimplenet-public.css', array(), $this->version, 'all' );

	}




	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rimplenet_Mlm_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rimplenet_Mlm_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */



		 /**
		 * Register core Material-Kit JS
		 *
		 * @since    1.0.0
		 */
 
		wp_enqueue_script($this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rimplenet-public.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script($this->plugin_name."-qrcode", plugin_dir_url( __FILE__ ) . 'js/jquery.qrcode.min.js', array( 'jquery' ), $this->version, true );
        
	}

}




function getRimplenetMenu($string=''){
	if (strtolower(substr($string, 0, 4))=='menu') {
	return substr($string, 5);
   }
   return false;
}

function getRimplenetWidget($string=''){
	if (strtolower(substr($string, 0, 6))=='widget') {
	return substr($string, 7);
   }

   return false;
}


function getRimplenetDashboardSuppliedDesign($string=''){

  if (strtolower(substr($string, 0, 4))=='menu') {
	 return 'menu';
   }

   elseif (strtolower(substr($string, 0, 6))=='widget') {
	return 'widget';
   }
   else{
   	return false;
   }

}

?>