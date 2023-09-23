<?php

class Rimplenet_Utility extends RimplenetRules{
 
 public function __construct() {
     
     //add_shortcode('rimplenet-util-form', array($this, 'RimplenetUtil'));
  
 }
    
 public function RimplenetUtil($atts) {
	        
        ob_start();

	    include plugin_dir_path( __FILE__ ) . 'layouts/rimplenet-investment-form.php';
	     
	    $output = ob_get_clean();

	    return $output;
  }

}    
$Rimplenet_Utility = new Rimplenet_Utility();


function rimplenet_pagination_bar($wp_query,$pageno=1) {
    
    global $wp;
    
    $viewed_url = add_query_arg($_SERVER['QUERY_STRING'], '', home_url($wp->request )); 
    
    $base_url = strtok($viewed_url, '?');              // Get the base url
    $parsed_url = parse_url($viewed_url);   // Get Url & Parse it 
    $url_query = $parsed_url['query'];              // Get the query string
    parse_str($url_query, $parameters );           // Convert Parameters into array
    unset( $parameters['pageno'] );               // Delete the one you want
    $new_query = http_build_query($parameters); // Rebuilt query string
    $new_url = $base_url.'?'.$new_query;            // Finally url is ready
    
    $total_pages = $wp_query->max_num_pages;
    if ($total_pages > 1){
        $current_page = max(1, $pageno);
        
     echo '<div class="rimplenet-navigation">';
        echo paginate_links(array(
            'base' => $new_url.'%_%',
            'format' => '&pageno=%#%',
            'current' => $current_page,
            'total' => $total_pages,
        ));
     echo '</div>';

    }
 }
 
?>