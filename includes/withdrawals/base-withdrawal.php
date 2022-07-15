<?php

namespace Withdrawals;

use RimplenetGetWallets;
use WP_Query;
use WP_Term;

abstract class Base extends RimplenetGetWallets
{

    public static $response = [
        'status_code' => 400,
        'status' => false,
        'message' => ''
    ];
    public function fetchWithdrawals($user_id="all")
    {
        $withdrawal = new WP_Query(
            array(  
              'post_type' => 'rimplenettransaction', 
              'post_status' => 'any',
              'author' => $user_id ,
            //   'posts_per_page'=>$posts_per_page,
            //   'paged'=>$pageno,
              'tax_query' => array(
                  'relation' => 'AND',
                  array(
                   'taxonomy' => 'rimplenettransaction_type',
                   'field'    => 'slug',
                   'terms'    => array( 'withdrawal','withdrawal-processed' ),
                 ),
                      ),
                   )
             );

             return $withdrawal;

             
  
    }
}
