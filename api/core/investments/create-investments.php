<?php

class RimplenetCreateInvestmentApi
{

    public function __construct()
    {
        add_action('rest_api_init', array($this, 'create_api_routes'));
    }

    public function create_api_routes()
    {
        register_rest_route(
            'rimplenet/v1', '/investments',
            [
                'methods' => 'POST',
                'callback' => [$this, 'create_investment']
            ]
        );
    }

    public function create_investment(WP_REST_Request $request)
    {
        do_action('rimplenet_api_request_started', $request, $allowed_roles=['administrator', 'subscriber'], $action='rimplenet_create_investments');

        $data = [
            'investment_name'                       => $request->get_param('investment_name'),
            'investment_description'                => $request->get_param('investment_description'),
            'investment_wallet'                     => $request->get_param('investment_wallet'),
            'amount_invested'                       => $request->get_param('amount_invested'),
            'amount_to_repay_on_roi'                => $request->get_param('amount_to_repay_on_roi'),
            'roi_repayment_interval'                => $request->get_param('roi_repayment_interval'),
            'time_to_end_investment'                => $request->get_param('time_to_end_investment'),
            'investment_group_id'                   => $request->get_param('investment_group_id'),
            'investment_id'                         => $request->get_param('investment_id'),
            'user_id'                               => $request->get_param('user_id')
        ];

        $investment = new RimplenetCreateInvestment();
        $create_investment = $investment->create_investment($data);
        
        return new WP_REST_Response($create_investment, $create_investment['status_code']);
        
    }

}

$RimplenetCreateInvestmentApi = new RimplenetCreateInvestmentApi();