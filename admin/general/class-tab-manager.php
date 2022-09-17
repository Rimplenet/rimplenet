<?php

class RimplenetAdminClassTabManagerGeneralSettings
{

    public function __construct()
    {
       add_action( 'admin_menu', array( $this, 'rimplenet_admin_menu_general_settings' ) );
    }

   public function rimplenet_admin_menu_general_settings()
   {
     add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( 'General Settigns', 'rimplenet' ),
            __( 'General Settigns', 'rimplenet' ),
            'manage_options',
            'rimplenet_general_settings_tab',
            array( $this, 'create_settings_fxn' )
            );
  }

  public function create_settings_fxn(){
 
      include_once plugin_dir_path( dirname( __FILE__ ) ) . '/general/layouts/tab-manager.php';
      // echo "hello";

    }
   

}

$RimplenetAdminClassTabManagerGeneralSettings = new RimplenetAdminClassTabManagerGeneralSettings();