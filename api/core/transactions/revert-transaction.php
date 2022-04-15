<?php
//INCLUDED from api/class-base-api.php ~ main plugin file

class RevertTxns{
    
  public function __construct() {
    
    add_action( 'rest_api_init', array($this,'register_api_routes') );

  }
  
  public function register_api_routes() {
          register_rest_route( 'rimplenet/v1','/transactions/revert', array(
            'methods' => 'POST',
            'permission_callback' => '__return_true',
            'callback' => array($this,'api_reverse_txns'),
          ) );
   }
  
    
  public function api_retrieve_txns(WP_REST_Request $request ) {


    if ($this->checkIfAlreadyRefunded($_POST['post_id'])) {

        $data = [
            'status_code'=>401,
            'status' => false,
            'message' => "Transaction Already Reversed!!",
            'data'=>''
        ];
        return $this->returndata($data);
    } elseif ($this->checkIfAlreadyReversed($_POST['post_id'])) {
        // return false;
        $data = [
            'status_code'=>401,
            'status' => false,
            'message' => "Transaction Already Reversed!!",
            'data'=>''
        ];
        return $this->returndata($data);
    } else {
        $rimplewallet = new Rimplenet_Wallets();
        if ($rimplewallet->add_user_mature_funds_to_wallet($_POST['user_id'], $_POST['amount_to_add'], $_POST['wallet_id'], $_POST['note'], $tags = [])) {
            $this->addAlreadyRefundedMeta($_POST['post_id']);
            $data['amount_to_add'] = 0 - $_POST['amount_to_add'];

            $rimplewallet->add_user_mature_funds_to_wallet($data['user_id2'], $data['amount_to_add'], $data['wallet_id2'], $data['note'], $tags = []);
            $this->addAlreadyRefundedMeta($data['post_id2']);

            $data = [
                'status_code'=>200,
                'status' => true,
                'message' => "Transaction Refunded!!",
                'data'=>''
            ];
            return $this->returndata($data);
        } else {
            $data = [
                'status_code'=>500,
                'status' => false,
                'message' => "Something went wrong!!",
                'data'=>''
            ];

            return $this->returndata($data);
        }
    }


   
  }

  public function returndata($data)
  {
      //RETURN RESPONSE
      if($data['status_code']) { 
        //learn more about status_code to return at https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
              
              //OR if !is_wp_error($request) 
             return new WP_REST_Response(
             array(
              'status_code' => $data['status_code'],
              'status' => $data['status'],
              'message' => $data['message'],
              'data' => $data['data'] 
              )
             );
            
          }
          else {
              
            $status_code = 400;
            $response_message = "Unknown Error";
            $data = array(
                "error"=>"unknown_error"
            ); 
            return new WP_Error($status_code, $response_message, $data);
          }
  }
  

  public function formatTransactions($data)
  {

    foreach ($data as $key => $value) {
      
      $txn_id=$value->ID;
      
                        
      $data[$key]->date_time = get_the_date('D, M j, Y', $txn_id).'<br>'.get_the_date('g:i A', $txn_id);
      $wallet_id = get_post_meta($txn_id, 'currency', true);

      $all_rimplenet_wallets = $this->getWallets();
      
      $data[$key]->wallet_symbol = $all_rimplenet_wallets[$wallet_id]['symbol'];
      $data[$key]->wallet_decimal = $all_rimplenet_wallets[$wallet_id]['decimal'];
      
      
      $data[$key]->amount = get_post_meta($txn_id, 'amount', true);
      $data[$key]->txn_type = get_post_meta($txn_id, 'txn_type', true);

      $data[$key]->amount_formatted_disp = apply_filters("rimplenet_history_amount_formatted", $amount_formatted_disp,$txn_id, $txn_type, $amount, $amount_formatted_disp);
                        
      $data[$key]->note = get_post_meta($txn_id, 'note', true);

      
    }


    return $data;
  }



  public function searchTransaction()
    {
        ob_start();

        include plugin_dir_path(__FILE__) . 'layouts/search-transaction.php';

        $output = ob_get_clean();

        return $output;
    }

    private function checkIfAlreadyReversed($post_id)
    {
        $data = get_metadata('post', $post_id, "already_reversed", "true");


        if (empty($data) || $data == "" || $data == null || $data == false) {
            return false;
        } else {
            return true;
        }
    }

    private function addAlreadyReversedMeta($post_id)
    {
        if (add_post_meta($post_id, "already_reversed", "true")) {
            return true;
        } else {
            return false;
        }
    }

    private function addAlreadyRefundedMeta($post_id)
    {
        if (add_post_meta($post_id, "already_refunded", "true")) {
            return true;
        } else {
            return false;
        }
    }

    private function checkIfAlreadyRefunded($post_id)
    {
        $data = get_metadata('post', $post_id, "already_refunded", "true");


        if (empty($data) || $data == "" || $data == null || $data == false) {
            return false;
        } else {
            return true;
        }
    }

    public function getAllWallets($include_only = '')
    { //$exclude can be default, woocommerce, or db
        if (empty($include_only)) {
            $include_only = array('default', 'woocommerce', 'db');
        }

        $activated_wallets = array();
        $wallet_type = array('mature', 'immature');


        if (in_array('default', $include_only)) {

            $activated_wallets['rimplenetcoin'] = array(
                "id" => "rimplenetcoin",
                "name" => "RIMPLENET Coin",
                "symbol" => "RMPNCOIN",
                "symbol_position" => "right",
                "value_1_to_base_cur" => 0.01,
                "value_1_to_usd" => 1,
                "value_1_to_btc" => 0.01,
                "decimal" => 0,
                "min_wdr_amount" => 0,
                "max_wdr_amount" => INF,
                "include_in_withdrawal_form" => "yes",
                "include_in_woocommerce_currency_list" => "no",
                "action" => array(
                    "deposit" => "yes",
                    "withdraw" => "yes",
                )
            );
        }

        if (in_array('woocommerce', $include_only) and in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            //For Woocommerce
            $activated_wallets['woocommerce_base_cur']  = apply_filters('rimplenet_filter_woocommerce_base_cur', get_option('rimplenet_woocommerce_wallet_and_currency'));
        }

        /*
     $activated_wallets['btc'] = array( 
          "id" => "btc",  
          "name" => "Bitcoin", 
          "symbol" => "BTC", 
          "value_1_to_base_cur" => 0.01, 
          "value_1_to_usd" => 0.01, 
          "value_1_to_btc" => 0.01, 
          "decimal" => 8, 
          "include_in_woocommerce_currency_list" => 'no',
          "action" => array( 
              "deposit" => "yes",  
              "withdraw" => "yes", 
          ) 
      ); 
      
      */



        if (in_array('db', $include_only)) {
            //Add Wallets saved in database
            $WALLET_CAT_NAME = 'RIMPLENET WALLETS';
            $txn_loop = new WP_Query(
                array(
                    'post_type' => 'rimplenettransaction',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'rimplenettransaction_type',
                            'field'    => 'name',
                            'terms'    => $WALLET_CAT_NAME,
                        ),
                    ),
                )
            );
            if ($txn_loop->have_posts()) {
                while ($txn_loop->have_posts()) {
                    $txn_loop->the_post();
                    $txn_id = get_the_ID();
                    $status = get_post_status();
                    $wallet_name = get_the_title();
                    $wallet_desc  = get_the_content();

                    $wallet_decimal = get_post_meta($txn_id, 'rimplenet_wallet_decimal', true);
                    $min_wdr_amount = get_post_meta($txn_id, 'rimplenet_min_withdrawal_amount', true);
                    if (empty($min_wdr_amount)) {
                        $min_wdr_amount = 0;
                    }

                    $max_wdr_amount = get_post_meta($txn_id, 'rimplenet_max_withdrawal_amount', true);
                    if (empty($max_wdr_amount)) {
                        $max_wdr_amount = INF;
                    }

                    $wallet_symbol = get_post_meta($txn_id, 'rimplenet_wallet_symbol', true);
                    $wallet_symbol_position = get_post_meta($txn_id, 'rimplenet_wallet_symbol_position', true);
                    $wallet_id = get_post_meta($txn_id, 'rimplenet_wallet_id', true);
                    $include_in_withdrawal_form = get_post_meta($txn_id, 'include_in_withdrawal_form', true);
                    $include_in_woocommerce_currency_list = get_post_meta($txn_id, 'include_in_woocommerce_currency_list', true);

                    $activated_wallets[$wallet_id] = array(
                        "id" => $wallet_id,
                        "name" => $wallet_name,
                        "symbol" => $wallet_symbol,
                        "symbol_position" => $wallet_symbol_position,
                        "value_1_to_base_cur" => 0.01,
                        "value_1_to_usd" => 1,
                        "value_1_to_btc" => 0.01,
                        "decimal" => $wallet_decimal,
                        "min_wdr_amount" => $min_wdr_amount,
                        "max_wdr_amount" => $max_wdr_amount,
                        "include_in_withdrawal_form" => "yes",
                        "include_in_woocommerce_currency_list" => $include_in_woocommerce_currency_list,
                        "action" => array(
                            "deposit" => "yes",
                            "withdraw" => "yes",

                        )
                    );
                }
            }

            wp_reset_postdata();
        }


        return $activated_wallets;
    }

  

}

$RetrieveTxns = new RevertTxns();