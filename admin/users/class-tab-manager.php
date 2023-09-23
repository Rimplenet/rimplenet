<?php

class RimplenetAdminCreateUsers
{

  public function __construct()
  {
    add_action('admin_menu', array($this, 'rimplenet_admin_menu_create_Users'));
  }

  public function rimplenet_admin_menu_create_Users()
  {
    add_submenu_page(
      'edit.php?post_type=rimplenettransaction',
      __('Users', 'rimplenet'),
      __('Users', 'rimplenet'),
      'manage_options',
      'users',
      array($this, 'users_fxn')
    );
  }

  public function users_fxn()
  {

    include_once plugin_dir_path(dirname(__FILE__)) . 'users/layouts/tab-manager.php';
  }
}

$RimplenetAdminCreateUsers = new RimplenetAdminCreateUsers();
