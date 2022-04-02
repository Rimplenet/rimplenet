<?php  
//This file is included at includes/rimplenet.php

class RimplenetAdminSidebarMenuSettings {
    public function __construct() {
        
        //Hook into the admin menu
        add_action( 'admin_menu', array( $this, 'admin_menu_general' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu_dashboard' ) );
        //add_action( 'admin_menu', array( $this, 'admin_menu_action_and_rules' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu_wallets' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu_matrix' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu_package_settings_and_rules' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu_pairing' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu_referral' ) );
        
        //add_action( 'admin_menu', array( $this, 'admin_menu_all_wallets' ) );
        //add_action( 'admin_menu', array( $this, 'admin_menu_all_investment_packages' ) );
        //add_action( 'admin_menu', array( $this, 'admin_menu_all_mlm_matrix' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu_withdrawal' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu_docs_setup_link' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu_donate_link' ) );
        
        add_filter("plugin_action_links_rimplenet/rimplenet.php", array( $this, 'my_plugin_settings_link') );
        add_action( 'admin_footer', array($this,'make_class_menu_target_blank' ));    

        
    }
 
  public function admin_menu_dashboard(){
     add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( 'Dashboard', 'rimplenet' ),
            __( 'Dashboard', 'rimplenet' ),
            'manage_options',
            'dashboard',
            array( $this, 'tab_manager_dashboard_fxn' )
            );
  }

  public function tab_manager_dashboard_fxn(){
      include_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/layouts/tab-manager-dashboard.php';
   }

  public function admin_menu_action_and_rules(){
     add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( 'Actions & Rules', 'rimplenet' ),
            __( 'Actions & Rules', 'rimplenet' ),
            'manage_options',
            'actions_and_rules',
            array( $this, 'tab_manager_action_and_rules_fxn' )
            );
  }

  public function tab_manager_action_and_rules_fxn(){
      include_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/layouts/tab-manager-actions-and-rules.php';
   }

  public function admin_menu_all_wallets(){
     add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( 'Wallets', 'rimplenet' ),
            __( 'Wallets', 'rimplenet' ),
            'manage_options',
            'edit.php?rimplenettransaction_type=rimplenet-wallets&post_type=rimplenettransaction'
            );
  }
  
  public function admin_menu_all_investment_packages(){
     add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( 'Investment Packages', 'rimplenet' ),
            __( 'Investment Packages', 'rimplenet' ),
            'manage_options',
            'edit.php?rimplenettransaction_type=rimplenet-mlm-packages&post_type=rimplenettransaction'
            );
  }
  
  public function admin_menu_all_mlm_matrix(){
     add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( 'MLM Matrix', 'rimplenet' ),
            __( 'MLM Matrix', 'rimplenet' ),
            'manage_options',
            'edit.php?rimplenettransaction_type=rimplenet-mlm-matrix&post_type=rimplenettransaction'
            );
  }

 public function admin_menu_general(){
     add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( 'Settings: General', 'rimplenet' ),
            __( 'Settings: General', 'rimplenet' ),
            'manage_options',
            'settings_general',
            array( $this, 'settings_general_fxn' )
            );
  }

  public function settings_general_fxn()
  {

    include_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/layouts/admin-settings-general.php';

     }

     public function admin_menu_wallets()
  {
     add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( 'Settings: Wallets', 'rimplenet' ),
            __( 'Settings: Wallets', 'rimplenet' ),
            'manage_options',
            'settings_wallets',
            array( $this, 'settings_wallets_fxn' )
            );
  }

    public function settings_wallets_fxn(){
 
      include_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/layouts/admin-settings-wallets.php';

    }


     public function admin_menu_matrix(){
     add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( 'Settings: Matrix', 'rimplenet' ),
            __( 'Settings: Matrix', 'rimplenet' ),
            'manage_options',
            'settings_matrix',
            array( $this, 'settings_matrix_fxn' )
            );
    }

    public function settings_matrix_fxn(){

      include_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/layouts/admin-settings-matrix.php';

     }



    public function admin_menu_pairing(){
     add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( 'Settings: Pairing and Rules', 'rimplenet' ),
            __( 'Settings: Pairing and Rules', 'rimplenet' ),
            'manage_options',
            'settings_pairing_and_rules',
            array( $this, 'settings_pairing_and_rules_fxn' )
            );
    }

    public function settings_pairing_and_rules_fxn(){

      include_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/layouts/admin-settings-pairing.php';

    }


    public function admin_menu_package_settings_and_rules(){
     add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( 'Settings: Packages / Plans and Rules', 'rimplenet' ),
            __( 'Settings: Packages / Plans and Rules', 'rimplenet' ),
            'manage_options',
            'settings_package_plans_and_rules',
            array( $this, 'settings_package_plans_and_rules_fxn' )
            );
    }

    public function settings_package_plans_and_rules_fxn(){

      include_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/layouts/admin-settings-package.php';

    }

    public function admin_menu_referral(){
     add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( 'Settings: Referral', 'rimplenet' ),
            __( 'Settings: Referral', 'rimplenet' ),
            'manage_options',
            'settings_referral',
            array( $this, 'settings_referral_fxn' )
            );
    }

    public function settings_referral_fxn(){

      include_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/layouts/admin-settings-referral.php';

     }
     
     
    public function admin_menu_withdrawal(){
     add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( 'Settings: Withdrawal', 'rimplenet' ),
            __( 'Settings: Withdrawal', 'rimplenet' ),
            'manage_options',
            'settings_withdrawal',
            array( $this, 'settings_withdrawal_fxn' )
            );
            
    }

    public function settings_withdrawal_fxn(){

      include_once plugin_dir_path( dirname( __FILE__ ) ) . '/admin/layouts/admin-settings-withdrawal.php';

     }
      
    public function admin_menu_docs_setup_link(){
     add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( '<strong style="color:#FCB214;" class="open-submenu-blank"> Docs / Setup Info</strong>', 'rimplenet' ),
            __( '<strong style="color:#FCB214;" class="open-submenu-blank"> Docs / Setup Info</strong>', 'rimplenet' ),
            'manage_options',
            'https://rimplenet.com/docs'
            );
       
    }


     
    public function admin_menu_donate_link(){
     add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( '<strong style="color:#31a231;" class="open-submenu-blank"> Donate </strong>', 'rimplenet' ),
            __( '<strong style="color:#31a231;" class="open-submenu-blank"> Donate </strong>', 'rimplenet' ),
            'manage_options',
            'https://rimplenet.com/donate'
            
            );
    }
  
    function make_class_menu_target_blank(){
        ?>
        <script type="text/javascript">
          jQuery(document).ready(function($) {
            $('.open-submenu-blank').parent().attr('target','_blank');
          });
        </script>
        <?php
    }
     
    function my_plugin_settings_link($links) { 
      
      // Build and escape the URL.
      $url = esc_url('https://rimplenet.com/docs');
      // Create the link.
      $docs_link = "<a href='https://rimplenet.com/docs'  style='color: #93003c;font-weight: 800;' target='_blank'>" . __( 'Docs / Setup Info' ) . '</a>';
      $donate_link = "<a href='https://rimplenet.com/donate' style='color: #FCB214;font-weight: 800;' target='_blank'>" . __( 'Donate' ) . '</a>';
      // Adds the link to the end of the array.
      $added_links = array($docs_link,$donate_link);
      
      $all_links = array_merge($added_links,$links);
      return $all_links;
    }
     
  
 }

$RimplenetAdminSidebarMenuSettings = new RimplenetAdminSidebarMenuSettings();
?>