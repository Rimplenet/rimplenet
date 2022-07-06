<?php

class RimplenetDeleteInvestmentApi
{

    public function __construct()
    {
        add_action('rest_api_init', array($this, 'delete_api_routes'));
    }

    public function delete_api_routes()
    {
        register_rest_route(
            'rimplenet/v1', '/investments/(?P<investment_id>\d+)',
            [
                'methods' => 'DELETE',
                'callback' => [$this, 'delete_investment']
            ]
        );
    }

    public function delete_investment(WP_REST_Request $request)
    {
        do_action('rimplenet_api_request_started', $request, $allowed_roles=['administrator'], $action='rimplenet_delete_investments');

        $investment = new RimplenetDeleteInvestment();
        $delete_investment = $investment->delete_investment($request->get_param('investment_id'));
        
        return new WP_REST_Response($delete_investment, $delete_investment['status_code']);
        
    }

}

$RimplenetDeleteInvestmentApi = new RimplenetDeleteInvestmentApi();