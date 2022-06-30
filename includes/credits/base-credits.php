<?php

namespace Credits;
use Utils\Utils;
abstract class Credits extends Utils
{

    /**
     * Check  if transaction has been executed before time
     * @param int $id > id of transaction
     * @param string $type > type of transaction (credit / debit)
     * @return object>boolean
     */
    protected function creditsExists(int $id, string $type= 'credit')
    {
        global $wpdb;
        $type = strtoupper($type);
        return $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE post_id ='$id' AND meta_key='request_id' AND meta_value = '$type' ");
    }
}
