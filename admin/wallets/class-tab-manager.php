<?php

class RimplenetAdminClassTabManagerWallets
{

    public function __construct()
    {
       add_action( 'admin_menu', array( $this, 'rimplenet_admin_menu_create_wallets' ) );
    }

   public function rimplenet_admin_menu_create_wallets()
   {
     add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( 'Wallets', 'rimplenet' ),
            __( 'Wallets', 'rimplenet' ),
            'manage_options',
            'wallets-tab',
            array( $this, 'create_wallets_fxn' )
            );
  }

  public function create_wallets_fxn(){
 
      include_once plugin_dir_path( dirname( __FILE__ ) ) . '/wallets/layouts/tab-manager.php';
      // echo "hello";

    }
   

}

$RimplenetAdminClassTabManagerWallets = new RimplenetAdminClassTabManagerWallets();