<?php

class BaseStatistics
{

    public $meta_prefix =  '';
    public $user_id =  '';
    public $wallet_id =  '';
    public $date =  '';

    public function setPrefix($prefix)
    {
        $this->meta_prefix = $prefix;
        return $this;
    }

    public function setuserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function setwalletId($wallet_id)
    {
        $this->wallet_id = $wallet_id;
        return $this;
    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function userqueryBuilder()
    {
        // $user_meta = get_user_meta($this->user_id);
        $common_user_meta = array();

        foreach (get_user_meta($this->user_id) as $key => $value ) {            
            if ( strpos( $key, $this->meta_prefix.'_'.$this->date ) === 0  && str_ends_with($key, $this->wallet_id)) {
                $common_user_meta[$key] =floatval(end($value));        
            }
        }
        return $common_user_meta;
    }

    public function siteWideQueryBuilder()
    {
        global $wpdb;
        $query= $this->meta_prefix.'_'.$this->date;
        $sql = "SELECT `option_name` AS `name`, `option_value` AS `value`
                FROM  $wpdb->options
                WHERE `option_name` LIKE '%$query%'
                AND `option_name` LIKE '%$this->wallet_id%'
                ORDER BY `option_name`";
    
        $results = $wpdb->get_results( $sql );
        $common_user_meta = [];

        foreach ($results as $key => $value) {
            $common_user_meta[$value->name] = floatval($value->value);
        }
        return $common_user_meta;
    }
}


$Rimplenet_Base_Statistics = new BaseStatistics();
