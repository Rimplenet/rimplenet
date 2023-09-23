<?php

class Rimplenet_Create_Debits
{

    public function __construct()
    {
        // add_shortcode('rimplenet-fintech-admin-refund-transaction', array($this, 'getTransactions'));
        // add_shortcode('rimplenet-fintech-admin-search-transactions', array($this, 'searchTransaction'));
        add_shortcode('rimplenet-fintech-add-debits', array($this, 'addDebits'));
        // add_shortcode('rimplenet_revert_transaction', array($this, 'reverseTransactions'));
    }

    public function addDebits()
    {
        ob_start();
        include plugin_dir_path(__FILE__) . 'layouts/create-debits.php';
        $output = ob_get_clean();
        return $output;
    }

   

}

$Rimplenet_Create_Debits = new Rimplenet_Create_Debits();