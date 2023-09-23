<?php

class RimplenetAdminCreateApiKeys
{

  public function __construct()
  {
    add_action('admin_menu', array($this, 'rimplenet_admin_menu_create_ApiKeys'));
  }

  public function rimplenet_admin_menu_create_ApiKeys()
  {
    add_submenu_page(
      'edit.php?post_type=rimplenettransaction',
      __('ApiKeys', 'rimplenet'),
      __('ApiKeys', 'rimplenet'),
      'manage_options',
      'apiKeys',
      array($this, 'apiKeys_fxn')
    );
  }

  public function apiKeys_fxn()
  {

    include_once plugin_dir_path(dirname(__FILE__)) . 'api-keys/layouts/tab-manager.php';
  }
}

$RimplenetAdminCreateApiKeys = new RimplenetAdminCreateApiKeys();
