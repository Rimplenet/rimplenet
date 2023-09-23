<?php

abstract class MLM
{
    /**
     * @var string
     */
    const MLM_MATRIX = "MLM MATRIX";
    
    public static function mlm_exists($name)
    {
        global $wpdb;
        $row = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='matrix_name' OR meta_key='matrix_id' AND meta_value='$name'");
        if ($row) :
            Res::error([
                'txn_id' => $row->post_id,
                'exist' => "MlM already exists"
            ], "MLM already exists", 409);
            return true;
        endif;
        return false;
    }

    public static function mlm_by_id($id)
    {
        global $wpdb;
        $row = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='matrix_id' AND meta_value='$id'");
        if ($row) :
            Res::error([
                'txn_id' => $row->post_id,
                'exist' => "MlM already exists"
            ], "MLM already exists", 409);
            return true;
        endif;
        return false;
    }
}
