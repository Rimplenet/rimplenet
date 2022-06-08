<?php

class RimplenetAdminClassTabManagerReferrals
{

    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'rimplenet_admin_menu_create_referrals' ) );
    }

    public function rimplenet_admin_menu_create_referrals()
    {
        add_submenu_page(
            'edit.php?post_type=rimplenettransaction',
            __( 'Referrals', 'rimplenet' ),
            __( 'Referrals', 'rimplenet' ),
            'manage_options',
            'referrals-tab',
            array( $this, 'create_referrals_fxn' )
        );
    }

    public function create_referrals_fxn(){
 
        include_once plugin_dir_path( dirname( __FILE__ ) ) . '/referrals/layouts/tab-manager.php';
        // echo "hello";

    }
   

}

$RimplenetAdminClassTabManagerReferrals = new RimplenetAdminClassTabManagerReferrals();