<?php
//INCLUDED from api/class-base-api.php ~ main plugin file

class ReverseTxns{
    
  public function __construct() {
    
    add_action( 'rest_api_init', array($this,'register_api_routes') );

  }
  
  public function register_api_routes() {
          register_rest_route( 'rimplenet/v1','/transactions/reverse', array(
            'methods' => 'POST',
            'permission_callback' => '__return_true',
            'callback' => array($this,'api_reverse_txns'),
          ) );
   }
  
    
  public function api_retrieve_txns(WP_REST_Request $request ) {


     global $wpdb;
     $wallet_obj = new Rimplenet_Wallets();
     
     $reverse_txn_id = sanitize_text_field(trim($_POST['reverse_txn_id']));
      $txn_reversed = get_post_meta($reverse_txn_id, "txn_reversed", true);
      $reversal_for_txn = get_post_meta($reverse_txn_id, "reversal_for_txn", true);
      $amount = get_post_meta($reverse_txn_id, "amount", true);
      $wallet_id_to_reverse = get_post_meta($reverse_txn_id, "currency", true);
      $txn_owner = get_post_field( 'post_author', $reverse_txn_id );
       $txn_type = get_post_meta($reverse_txn_id, "txn_type", true);
       if($txn_type=="CREDIT"){
          $amount_to_reverse = $amount*-1;
       }
       elseif($txn_type=="DEBIT"){
          $amount_to_reverse = $amount;
        }
        $request_id_reverse = "reverse_txn_".$reverse_txn_id;
        $note = "Reversal of TXN - #$reverse_txn_id";
        //Process txn
        if($txn_reversed=="yes" OR $reversal_for_txn=="yes"){
            $error_message = "Txn #$reverse_txn_id is not eligible for reversal";
        }
        else{
        $funds_id = $wallet_obj->rimplenet_fund_user_mature_wallet($request_id_reverse, $txn_owner, $amount_to_reverse, $wallet_id_to_reverse, $note);
            if($funds_id>1){
            add_post_meta($reverse_txn_id,'txn_reversed', 'yes');
            add_post_meta($reverse_txn_id,'txn_reversed_on_id', $funds_id);
            add_post_meta($reverse_txn_id,'txn_reversed_by_user', $current_user->ID);
            add_post_meta($reverse_txn_id, 'txn_reversed_time',time());
            add_post_meta($funds_id,'reversal_for_txn', 'yes');
            add_post_meta($funds_id,'reversal_for_txn_id', $reverse_txn_id);
            add_post_meta($funds_id,'txn_reversal_by_user', $current_user->ID);
            add_post_meta($funds_id, 'txn_reversal_time',time());
            $success_message = "Txn - #$reverse_txn_id has been succesfully reversed";
            }
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

  

}

$RetrieveTxns = new ReverseTxns();