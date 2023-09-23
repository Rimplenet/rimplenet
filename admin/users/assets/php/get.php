<?php

$plugin_name = $this->plugin_name ?? '';
global $current_user, $wpdb, $post,  $wp;
$current_user = wp_get_current_user();


$instance = new RimplenetGetUser();
$users = $instance->get_users();
$users = $users['data']['users'];