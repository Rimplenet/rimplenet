<?php
    //This file is Included at admin/class-admin-sidebar-menu-settings.php
    $plugin_name = $this->plugin_name;
    global $current_user,$wpdb, $post,  $wp;
     $current_user = wp_get_current_user();
?>
<div class="wrap rimplenet-wrap">
    
    <div id="icon-options-rimplenet-action-and-rules" class="icon32"></div>
    <h1><?php _e('Rimplenet Action & Rules', 'rimplenet'); ?></h1>
     <!-- wordpress provides the styling for tabs. -->
    <h2 class="nav-tab-wrapper">
        <!-- when tab buttons are clicked we jump back to the same page but with a new parameter that represents the clicked tab. accordingly we make it active -->
         <?php
            $active_tab = $_GET["tab"] ;
            
            if($_GET["tab"] == "api-settings")
             {
               $api_settings_tab_active = "nav-tab-active";
               $path_to_tab = plugin_dir_path( dirname( __FILE__ ) ) . "layouts/tab-api-settings.php";
             }
            elseif($_GET["tab"] == "setup")
             {
               $setup_tab_active = "nav-tab-active";
               $path_to_tab = plugin_dir_path( dirname( __FILE__ ) ) . "layouts/tab-dashboard-demo-data-setup.php";
             }
            else
             { 
               $active_tab  = "dashboard-overview";
               $overview_tab_active = "nav-tab-active";
               $path_to_tab = plugin_dir_path( dirname( __FILE__ ) ) . "layouts/dashboard-overview.php";
             }
             
             //Set the url for each of the tab
             $overview_tab_url = add_query_arg( array( 'post_type'=>$_GET["post_type"], 'page'=>$_GET["page"], 'tab'=>'dashboard-overview', 'viewing_user'=>$current_user->ID), admin_url( "edit.php") );
             $setup_tab_url = add_query_arg( array( 'post_type'=>$_GET["post_type"], 'page'=>$_GET["page"], 'tab'=>'setup', 'viewing_user'=>$current_user->ID), admin_url( "edit.php") );
             $api_settings_tab_url = add_query_arg( array( 'post_type'=>$_GET["post_type"], 'page'=>$_GET["page"], 'tab'=>'api-settings', 'viewing_user'=>$current_user->ID), admin_url( "edit.php") );
             
         ?>
         <style>
             .nav-tab{margin-bottom:5px;}
         </style>
        <a href="<?php echo $overview_tab_url; ?>" class="nav-tab <?php echo $overview_tab_active; ?>">
            <?php _e('Overview', 'rimplenet'); ?>
        </a>
        
        <a href="<?php echo $setup_tab_url; ?>" class="nav-tab <?php echo $setup_tab_active; ?>">
            <?php _e('Setup & Demo Data', 'rimplenet'); ?>
        </a>

        <a href="<?php echo $api_settings_tab_url; ?>" class="nav-tab <?php echo $api_settings_tab_active; ?>">
            <?php _e('Rimplenet API Settings', 'rimplenet'); ?>
        </a>
        
        
    </h2>
    <br>
    <div class="clearfix"></div>
     <?php 
        //show the content as per tab from file
        include_once $path_to_tab; 
     ?>
        

    
</div>