<?php

class RimplenetCreateInvestment
{
    public $validation_error = [];

    public function __construct()
    {
        add_shortcode('rimplenet-create-investment', array($this, 'create_investment_test'));
    }

    public function create_investment_test() {
        ob_start();
        var_dump($this->create_investment([]));
        return ob_get_clean();
    }

    public function create_investment($data)
    {
        
        $InvestmentId = wp_insert_post([
            'post_title'    => $data['investment_name'],
            'post_content'  => "",
            'post_status'   => 'publish',
            'post_type'     => 'rimplenettransaction'
        ]);

        wp_set_object_terms($InvestmentId, 'INVESTMENTS', 'rimplenettransaction_type');

        $roi_amount_to_be_paid_per_interval = ($data['amount_invested'] + $data['amount_to_repay_on_roi']) / $data['time_to_end_investment'];

        $save_investment = [
            'investment_name'                       => $data['investment_name'],
            'investment_description'                => $data['investment_description'],
            'investment_wallet'                     => $data['investment_wallet'],
            'amount_invested'                       => $data['amount_invested'],
            'amount_to_repay_on_roi'                => $data['amount_to_repay_on_roi'],
            'roi_repayment_interval'                => $data['roi_repayment_interval'],
            'roi_amount_to_be_paid_per_interval'    => $roi_amount_to_be_paid_per_interval,
            'time_to_end_investment'                => $data['time_to_end_investment'],
            'investment_group_id'                   => $data['investment_group_id']
        ];

        foreach ($save_investment as $key => $value) {
            update_post_meta($InvestmentId, $key, $value);
        }

        return $this->response(201, true, "New investment created", $save_investment, []);

    }

    public function response($status_code, $status, $message, $data=[], $error=[])
    {
        return [
            "status_code" => $status_code,
            "status" => $status,
            "message" => $message,
            "data" => $data,
            "error" =>$error
        ];
    }
    

}

$RimplenetCreateInvestment = new RimplenetCreateInvestment();