<?php

$plugin_name = $this->plugin_name ?? '';
$instance = new RimplenetGetKycUser();
$users = $instance->get_kyc_users();