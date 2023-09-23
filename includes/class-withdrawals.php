<?php

class Rimplenet_Withdrawals  extends RimplenetRules{
 
  public function __construct() {
        
       add_shortcode('rimplenet-withdrawal-form', array($this, 'RimplenetWithdrawalForm'));
       add_action('add_decode_rimplenet_rules', array($this,'rimplenet_rules_allowed_amount_for_level_account_tier'), 25, 4 );
       add_action('rimplenet_wallet_history_txn_action', array($this,'walletHistoryDisplayActionsButton'), 10, 5 );
       
    }
 
   
   public function withdraw_user_wallet_bal($request_id, $user_id, $amount_to_withdraw, $wallet_id, $wdr_dest, $wdr_dest_data, $note='Withdrawal',$extra_data=''){
        
      $wallet_obj = new Rimplenet_Wallets();
      $all_wallets = $wallet_obj->getWallets();
       
      $user_wdr_bal = $wallet_obj->get_withdrawable_wallet_bal($user_id, $wallet_id);
      $user_non_wdr_bal = $wallet_obj->get_nonwithdrawable_wallet_bal($user_id, $wallet_id);
      
      $amount_to_withdraw_formatted = getRimplenetWalletFormattedAmount($amount_to_withdraw,$wallet_id);
       
       
      $walllets = $wallet_obj->getWallets();
      $dec = $walllets[$wallet_id]['decimal'];
      $min_wdr_amount = $walllets[$wallet_id]['min_wdr_amount'];
      $min_wdr_amount_formatted = getRimplenetWalletFormattedAmount($min_wdr_amount,$wallet_id);
      $max_wdr_amount = $walllets[$wallet_id]['max_wdr_amount'];
      $max_wdr_amount_formatted = getRimplenetWalletFormattedAmount($max_wdr_amount,$wallet_id);
      $symbol = $walllets[$wallet_id]['symbol'];
      $name = $walllets[$wallet_id]['name'];
          
        $balance = $symbol.number_format($balance,$dec);
      
      
        $inputed_data = array(
           "request_id"=>$request_id,"user_id"=>$user_id, "amount_to_withdraw"=>$amount_to_withdraw, "wallet_id"=>$wallet_id, "wdr_dest"=>$wdr_dest, "wdr_dest_data"=>$wdr_dest_data);
        
        $empty_input_array = array(); 
        //Loop & Find out empty inputs
        foreach($inputed_data as $input_key=>$single_data){ 
            if(empty($single_data)){
              $empty_input_array[$input_key]  = "field_required" ;
            }
        } 
        
        if(!empty($empty_input_array)){
          //if atleast one required input is empty
          $status = "one_or_more_input_required";
          $response_message = "One or more input field is required";
          $data = array("error"=>$empty_input_array);
          
        }
        elseif($user_wdr_bal<=0){
          $status = "user_wdr_bal_is_zero_or_less";
          $response_message = "User Withdrawable Balance should not be Zero or Less";
          $data = array("error"=>"User Withdrawable Balance should not be Zero or Less");
       }
        elseif($amount_to_withdraw<=0){
          $status = "amount_is_zero_or_less";
          $response_message = "Amount should not be Zero or Less";
          $data = array("error"=>"Amount is zero or less");
       }
       elseif ($amount_to_withdraw<$min_wdr_amount) {
          $message = 'Requested amount ['.$amount_to_withdraw_formatted.'] is below minimum withdrawal amount, input amount not less than '.$min_wdr_amount_formatted;
          
          $status = "minimum_withdrawal_amount_error";
          $response_message = $message;
          $data = array("error"=>"Amount Requested is not up to Minimum Withdrawal Amount");
       } 
       elseif ($amount_to_withdraw>$max_wdr_amount) {
          $message = 'Requested amount ['.$amount_to_withdraw_formatted.'] is above maximum withdrawal amount, input amount not more than '.$max_wdr_amount_formatted;
          
          $status = "maximum_withdrawal_amount_error";
          $response_message = $message;
          $data = array("error"=>"Amount Requested is more than Maximum Withdrawal Amount");
       }
       else{
          
         $amount_to_withdraw_ready = $amount_to_withdraw * -1;
         $meta_input = $wdr_dest_data;
         
         $txn_wdr_id = $wallet_obj->rimplenet_fund_user_mature_wallet($request_id,$user_id, $amount_to_withdraw_ready, $wallet_id, $note);
         
         if (is_int($txn_wdr_id)) {
             
           wp_set_object_terms($txn_wdr_id, 'WITHDRAWAL', 'rimplenettransaction_type', true);
           $modified_title = 'WITHDRAWAL ~ '.get_the_title( $txn_wdr_id);
           $meta_input["note"] = $note;
           $args = 
              array(
              'ID'    =>  $txn_wdr_id,
              'post_title'   => $modified_title,
              'post_status'   =>  'pending',
              'meta_input' => $meta_input
              );
              
    
             wp_update_post($args);
     
             
             $status = "success";
             $response_message = "Withdrawal Request Submitted Successful";
             do_action('rimplenet_withdraw_user_wallet_bal_submitted_success',$txn_wdr_id, $wallet_id, $amount_to_withdraw, $user_id_withdrawing );
             $data = array("txn_id"=>$txn_wdr_id);
          }
          else{
              
              $wdr_info = json_decode($txn_wdr_id);
              $status = $wdr_info->status;
              $response_message = $wdr_info->message;
              $data = $wdr_info->data;
              
          }
      
          
       }
     wp_reset_postdata();
    
    $result = array("status"=>$status,
                      "message"=>$response_message,
                      "data"=>$data); 
    $result = json_encode($result);
    
    return $result;

 }


 public function RimplenetWithdrawalForm($atts) {
            

        ob_start();

        include plugin_dir_path( __FILE__ ) . 'layouts/rimplenet-withdrawal-form.php';
         
        $output = ob_get_clean();

        return $output;
      
 }
  
  public function walletHistoryDisplayActionsButton($txn_id, $wallet_id, $amount, $txn_type, $note){
     
    $viewed_url = $_SERVER['REQUEST_URI'];
   if(has_term('withdrawal', 'rimplenettransaction_type',$txn_id) && $txn_type=="DEBIT" && get_post_status($txn_id)!='publish'){  
     if(isset($_POST['rimplenet_cancel_withdrawal']) && wp_verify_nonce($_POST['rimplenet_cancel_withdrawal'], 'rimplenet_cancel_withdrawal' )) {
      global $wpdb;
      
      $withdrawal_id = sanitize_text_field(trim($_POST['withdrawal_id']));
      //$rimplenet_success_message = sanitize_text_field(trim($_POST['rimplenet_success_message']));
     // $rimplenet_error_message = sanitize_text_field(trim($_POST['rimplenet_error_message']));
     $rimplenet_success_message = "Withdrawal Successfully Cancelled - #".$txn_id;
     $rimplenet_error_message = "Error in Cancelling Withdrawal - #".$txn_id;
      
      $txn_status = get_post_meta($txn_id,'txn_status',true);
      if($withdrawal_id==$txn_id && $txn_status!='rejected_and_refunded'){
        
        $cancellation_info = $this->cancel_withdrawal_and_refund($txn_id);
        if($cancellation_info>1){
          $redirect_url = add_query_arg( array(
                        'rimplenet_success_message' => urlencode ($rimplenet_success_message),
                        'withdrawal_id' => $rimplenet_success_message,
                    ), $viewed_url ); 
        }else{
          $redirect_url = add_query_arg( array(
                        'rimplenet_success_message' => urlencode ($cancellation_info),
                        'withdrawal_id' => $withdrawal_id,
                    ), $viewed_url );
        }
        wp_safe_redirect( esc_url($redirect_url) ); exit;
                       
      }
        
    }
  
     $txn_status = get_post_meta($txn_id,'txn_status',true);
     if($txn_status!='rejected_and_refunded'){
       
    ?>
      <form method="POST" class="rimplenet-cancel-withdrawal-form" id="rimplenet-cancel-withdrawal-form" >
         
      <?php wp_nonce_field( 'rimplenet_cancel_withdrawal', 'rimplenet_cancel_withdrawal' ); ?>
      <input type="hidden" name="withdrawal_id" value="<?php echo $txn_id; ?>">
        <button class="rimplenet-button rimplenet-cancel-withdraw-btn btn btn-danger btn-sm" id="rimplenet-cancel-withdraw-btn"
        type="submit" value="CANCEL WITHDRAWAL">
             CANCEL WITHDRAWAL
        </button>
      </form>

      <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('form#rimplenet-cancel-withdrawal-form').submit(function(){
       $(this).find(':input[type=submit]').prop('disabled', true);
         });
        
        });
      </script>
   <?php
    }
    
   }
  
  }
  
  public function cancel_withdrawal_and_refund($txn_id){
        
             $wallet_obj = new Rimplenet_Wallets();
             
           $rimplenet_txn_ref = 'wdr_refund_'.$txn_id;
             $tags['txn_ref'] = $rimplenet_txn_ref;
             $rimplenet_user = get_post_field( 'post_author', $txn_id );
             $rimplenet_amount = get_post_meta($txn_id, 'amount',true);
             $wallet_id = get_post_meta($txn_id, 'currency',true);
             $funds_note = "Reversal of Withdrawal ";
             
            $ext_txn_id = rimplenet_txn_exist($rimplenet_user,$rimplenet_txn_ref);
          //$funds_id = $wallet_obj->add_user_mature_funds_to_wallet($rimplenet_user, $rimplenet_amount, $wallet_id, $funds_note, $tags); 
     
        $request_id = $rimplenet_txn_ref;
        $author_id = $rimplenet_user;
        $amount_txn = $rimplenet_amount;
    
       
           
            if($ext_txn_id>1){
              $info = "This transaction with ref-$rimplenet_txn_ref was already cancelled and refunded. Lookup ID = #$ext_txn_id";
            }
    else{
       $funds_id = $wallet_obj->rimplenet_fund_user_mature_wallet($request_id,$author_id, $amount_txn, $wallet_id, $funds_note); 
    }
            if($funds_id>1){
                
              add_post_meta($txn_id, 'txn_status','rejected_and_refunded');
              add_post_meta($txn_id, 'refund_time',time());
              add_post_meta($txn_id, 'funds_refund_id',$funds_id);
              add_post_meta($funds_id, 'funds_wdr_refunded_id',$txn_id);
              
              $info = $funds_id;
            }
            else{
              $info = $funds_id;
            } 
            
            //hook
            $withdrawal_id = $txn_id;
            $refund_id = $funds_id;
            do_action('rimplenet_withdrawal_rejected_and_refunded_action', $withdrawal_id, $refund_id, $wallet_id, $rimplenet_amount, $rimplenet_user);
   
            return $info;
    
      
  }
    
    
  public function rimplenet_rules_allowed_amount_for_level_account_tier($rule,$user, $obj_id='', $args=''){
       
       $amount  = trim($args[0]);
       $wallet_id  = trim($args[1]);
    }
    

 }
    
  function display_withdrawal_txns($user_id="all",$wallet_id="all",$date_range="all",$txn_type="withdrawal",$per_page=10){
   include plugin_dir_path( dirname( __FILE__ ) ) . 'includes/layouts/withdrawal-request-txns.php';
  }  

$Rimplenet_Withdrawals = new Rimplenet_Withdrawals();