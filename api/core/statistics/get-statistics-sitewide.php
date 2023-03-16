<?php
/**
 * Determines if the file being uploaded is a legitimate image or not. 
 * If so, allows the file to be uploaded. Otherwise, prevents the upload 
 * from occurring. 
 * PHP Version 8
 * 
 * @category Fintech
 * @package  Rimplenet Wallet Addon
 * @author   Nellalink <tom@tommcfarlin.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://neallalink.com
 * @since    1.0.0
 **/

$rimplenet_wallet_addon_get_statistic_sitewide = new class extends RimplenetStatistics
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_api_routes()
    {
        register_rest_route('/rimplenet/v1', 'statistics/(?P<meta_key>\S+)/(?P<wallet_id>[a-zA-Z0-9-]+)', [
            'methods' => 'GET',
            'callback' => [$this, 'api_get_statistics']
        ]);
    }

    /** 
     * meta key should be total_debit, total_credit, highest_amount
    */
    public function api_get_statistics(WP_REST_Request $req)
    {
        $this->req = [
            'entity_type'          => 'sitewide',
            'meta_key'     => sanitize_text_field(strtolower($req['meta_key'])), 
            'wallet_id'     => sanitize_text_field(strtolower($req['wallet_id'])),
            'date'     => sanitize_text_field(strtolower($req['date']) ?? ''), 
        ];
        return new WP_REST_Response($this->query());
    }
};
