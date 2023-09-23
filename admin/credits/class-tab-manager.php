<?php

class RimplenetAdminClassTabManagerCredits
{

    public function __construct()
    {
       add_action( 'admin_menu', array( $this, 'rimplenet_admin_menu_create_credits' ) );
    }

   public function rimplenet_admin_menu_create_credits()
   {
     add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( 'Credits', 'rimplenet' ),
            __( 'Credits', 'rimplenet' ),
            'manage_options',
            'rimplenet_credits_tab',
            array( $this, 'create_credits_fxn' )
            );
  }

  public function create_credits_fxn(){
 
      include_once plugin_dir_path( dirname( __FILE__ ) ) . '/credits/layouts/tab-manager.php';
      // echo "hello";

    }
   

}

$RimplenetAdminClassTabManagerCredits = new RimplenetAdminClassTabManagerCredits();