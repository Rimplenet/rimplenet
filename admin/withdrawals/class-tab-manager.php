<?php

class RimplenetAdminClassTabManagerWithdrawals
{

    public function __construct()
    {
       add_action( 'admin_menu', array( $this, 'rimplenet_admin_menu_create_wallets' ) );
    }

   public function rimplenet_admin_menu_create_wallets()
   {
     add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( 'Withdrawals', 'rimplenet' ),
            __( 'Withdrawals', 'rimplenet' ),
            'manage_options',
            'withdrawals-tab',
            array( $this, 'create_withdrawals_fxn' )
            );
  }

  public function create_withdrawals_fxn(){
 
      include_once plugin_dir_path( dirname( __FILE__ ) ) . '/withdrawals/layouts/tab-manager.php';
      // echo "hello";

    }
   

}

$RimplenetAdminClassTabManagerWithdrawals = new RimplenetAdminClassTabManagerWithdrawals();