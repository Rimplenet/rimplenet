<?php

$getCredits = new class extends RimplenetGetCredits
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('rimplenet/v1', 'credits', [
            'methods' => 'GET',
            'callback' => [$this, 'api_get_credits']
        ]);
    }

    public function api_get_credits(WP_REST_Request $req)
    {
        # ================= set fields ============
        $wlt_id  = sanitize_text_field($req['credit_id']);
        $page      = $req['page'] ?? 1;

        // if ($wlt_id !== '') :
            $this->getCredits($wlt_id, 'credit');
            return new WP_REST_Response($this->response, $this->response['status_code']);
        // else :
        //     $txn_loop = new WP_Query(
        //         array(
        //             'post_type' => 'rimplenettransaction',
        //             'post_status' => 'any',
        //             //                                 'author' => $user_id,
        //             'author' => 'any',
        //             'posts_per_page' => -1,
        //             'paged' => 1,
        //             'tax_query' => array(
        //                 //                                       array(
        //                 //                                        'taxonomy' => 'rimplenettransaction_type',
        //                 //                                        'field'    => 'name',
        //                 //                                        'terms'    => array( 'CREDIT' ),
        //                 //                                      ),
        //                 array(
        //                     'taxonomy' => 'rimplenettransaction_type',
        //                     'field'    => 'name',
        //                     'terms'    => 'DEBIT'
        //                 ),
        //             ),
        //         )
        //     );

        //     return $txn_loop;
        // endif;
    }
};
