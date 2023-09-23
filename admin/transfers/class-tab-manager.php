<?php

class RimplenetAdminCreateTransfers
{

  public function __construct()
  {
    add_action('admin_menu', array($this, 'rimplenet_admin_menu_create_Transfers'));
  }

  public function rimplenet_admin_menu_create_Transfers()
  {
    add_submenu_page(
      'edit.php?post_type=rimplenettransaction',
      __('Transfers', 'rimplenet'),
      __('Transfers', 'rimplenet'),
      'manage_options',
      'transfers',
      array($this, 'transfers_fxn')
    );
  }

  public function transfers_fxn()
  {

    include_once plugin_dir_path(dirname(__FILE__)) . 'transfers/layouts/tab-manager.php';
  }
}

$RimplenetAdminCreateTransfers = new RimplenetAdminCreateTransfers();
