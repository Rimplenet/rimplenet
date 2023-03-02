<?php

class Rimplenet_Base_Debits_Api
{

    private $plugin_name;

    private $version;

    public function __construct()
    {
        $this->plugin_name = $this->plugin_name ?? 'RimplenetIn';
        $this->version = $this->version ?? 'v1';
        $this->load_required_files();
    }
    private function load_required_files()
    {
        //Add Required Files to Load
        include_once plugin_dir_path(dirname(__FILE__)) . 'debits/create-debits.php';
        include_once plugin_dir_path(dirname(__FILE__)) . 'debits/update-debits.php';
        include_once plugin_dir_path(dirname(__FILE__)) . 'debits/get-debits.php';
        include_once plugin_dir_path(dirname(__FILE__)) . 'debits/delete-debits.php';
    }
}

new Rimplenet_Base_Debits_Api();
