<?php

class RimplenetCreateInvestment
{
    public $validation_error = [];

    public function __construct()
    {
        add_shortcode('rimplenet-create-investment', array($this, 'create_investment_test'));
        // add_action('init', array($this, 'process_investments_roi'));
    }

    public function create_investment_test() {
        ob_start();
        var_dump($this->create_investment([]));
        return ob_get_clean();
    }

    public function create_investment($data)
    {
        
        $debit = new RimplenetCreateDebits;
        $params = [
            "wallet_id" => $data['investment_wallet'], 
            "amount" => $data['amount_invested'],
            "request_id" =>  "investment_debit_".$data['investment_id'],
            "user_id" => $data['user_id'],
            "note" =>  $data['investment_description']
        ];
        
        $debit->createDebits($params);
        
        // if (!empty($debit::$response['error'])) return $debit::$response;
        
        $txn_request_id = $params['user_id'].'_'.$params['request_id'];
        
        if ($this->txnExists($txn_request_id)) {
            
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
                'investment_group_id'                   => $data['investment_group_id'],
                'investment_id'                         => $data['investment_id'],
                'user_id'                               => $data['user_id']
            ];

            foreach ($save_investment as $key => $value) {
                update_post_meta($InvestmentId, $key, $value);
            }

            return $this->response(201, true, "New investment created", $save_investment, []);
        }

    }

    public function process_investments_roi()
    {
        if ($this->availableInvestments()->found_posts > 0) {

            foreach ($this->availableInvestments()->get_posts() as $post) {
                // echo $post->ID;
                // echo get_post_meta($post->ID, 'investment_wallet', true);
                $credit = new RimplenetCreateCredits;
                $params = [
                    "wallet_id"    => get_post_meta($post->ID, 'investment_wallet', true), 
                    "amount"       => get_post_meta($post->ID, 'roi_amount_to_be_paid_per_interval', true),
                    "request_id"   => 'investment_credit_'. get_post_meta($post->ID, 'investment_id', true),
                    "user_id"      => get_post_meta($post->ID, 'user_id', true),
                    "note"         => get_post_meta($post->ID, 'investment_description', true)
                ];
                
                $credit->createCredits($params);
            }
        }
        
    }

    public function availableInvestments()
    {

        return new WP_Query(
            array(
                'post_type' => 'rimplenettransaction',
                'post_status' => 'publish',
                'author' => 'any',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'rimplenettransaction_type',
                        'field'    => 'name',
                        'terms'    =>  'INVESTMENTS'
                    ),
                ),
            )
        );

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

    public function txnExists($txn_id)
    {
        global $wpdb;
        $row = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='txn_request_id' AND meta_value='$txn_id'");
        if ($row) return true;
  
        return false;
    }
    

}

$RimplenetCreateInvestment = new RimplenetCreateInvestment();