<?php

class Rimplenet_Create_Credits
{

    public function __construct()
    {
        // add_shortcode('rimplenet-fintech-admin-refund-transaction', array($this, 'getTransactions'));
        // add_shortcode('rimplenet-fintech-admin-search-transactions', array($this, 'searchTransaction'));
        add_shortcode('rimplenet-fintech-add-credits', array($this, 'addCredits'));
        // add_shortcode('rimplenet_revert_transaction', array($this, 'reverseTransactions'));
    }

    public function addCredits()
    {
        ob_start();
        include plugin_dir_path(__FILE__) . 'layouts/create-credits.php';
        $output = ob_get_clean();
        return $output;
    }

   

}

$Rimplenet_Create_Credits = new Rimplenet_Create_Credits();