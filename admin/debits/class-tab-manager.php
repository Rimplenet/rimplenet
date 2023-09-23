<?php

class RimplenetAdminClassTabManagerDebits
{

    public function __construct()
    {
       add_action( 'admin_menu', array( $this, 'rimplenet_admin_menu_create_wallets' ) );
    }

   public function rimplenet_admin_menu_create_wallets()
   {
     add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( 'Debits', 'rimplenet' ),
            __( 'Debits', 'rimplenet' ),
            'manage_options',
            'rimplenet_debits_tab',
            array( $this, 'create_debits_fxn' )
            );
  }

  public function create_debits_fxn(){
 
      include_once plugin_dir_path( dirname( __FILE__ ) ) . '/debits/layouts/tab-manager.php';
      // echo "hello";

    }
   

}

$RimplenetAdminClassTabManagerDebits = new RimplenetAdminClassTabManagerDebits();