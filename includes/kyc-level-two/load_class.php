<?php

class Rimplenet_Kyc_Users
{


    public function __construct()
    {
        $this->load_required_files();
    }

    private function load_required_files()
    {
        //Add Required Files to Load
        require_once plugin_dir_path(dirname(__FILE__)) . 'kyc-level-two/get_users.php';
    }
}


$Rimplenet_Base_Users = new Rimplenet_Kyc_Users();
