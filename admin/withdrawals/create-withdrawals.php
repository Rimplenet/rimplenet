<?php

class RimplenetAdminCreateWallet
{

    public function __construct()
    {
       add_action( 'admin_menu', array( $this, 'rimplenet_admin_menu_create_wallets' ) );
    }

   public function rimplenet_admin_menu_create_wallets()
   {
     add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( 'Create Wallets', 'rimplenet' ),
            __( 'Create Wallets', 'rimplenet' ),
            'manage_options',
            'create_wallets',
            array( $this, 'create_wallets_fxn' )
            );
  }

  public function create_wallets_fxn(){
 
      include_once plugin_dir_path( dirname( __FILE__ ) ) . '/wallets/layouts/admin-settings-wallets.php';
      // echo "hello";

    }
   

}

$RimplenetAdminCreateWallet = new RimplenetAdminCreateWallet();