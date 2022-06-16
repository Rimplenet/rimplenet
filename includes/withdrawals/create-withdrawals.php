<?php

// namespace Debits\CreateDebits;

// use Rimplenet_Wallets;
use Withdrawals\Base;
use Traits\Wallet\RimplenetWalletTrait;

class RimplenetCreateWithdrawals extends Base
{

    use RimplenetWalletTrait;
    protected function createWithdrawals(array $req = [])
    {
        // $request_id, $user_id, $amount_to_withdraw, $wallet_id, $wdr_dest, $wdr_dest_data, $note='Withdrawal',$extra_data=''


        $prop = empty($req) ? $this->req : $req;
        extract($prop);

        $wallet_obj = $this->getWallet($wallet_id);




        // $wallet_obj = new Rimplenet_Wallets();
        // $all_wallets = $wallet_obj->getWallets();
         
        // $user_wdr_bal = $wallet_obj->get_withdrawable_wallet_bal($user_id, $wallet_id);
        $this->get_withdrawable_wallet_bal($user_id, $wallet_id);
        // $user_non_wdr_bal = $wallet_obj->get_nonwithdrawable_wallet_bal($user_id, $wallet_id);
        $this->get_nonwithdrawable_wallet_bal($user_id, $wallet_id);
        
        // $amount_to_withdraw_formatted = getRimplenetWalletFormattedAmount($amount_to_withdraw,$wallet_id);
        $this->getRimplenetWalletFormattedAmount($amount,$wallet_id,$include_data='');
         
         
        // $walllets = $wallet_obj->getWallets();
        $walllets = $wallet_obj;
        $dec = $walllets['wallet_decimal'];
        $min_wdr_amount = $walllets['wallet_min_wdr_amount'];
        $min_wdr_amount_formatted = $this->getRimplenetWalletFormattedAmount($min_wdr_amount,$wallet_id);
        $max_wdr_amount = $walllets['wallet_max_wdr_amount'];
        $max_wdr_amount_formatted = $this->getRimplenetWalletFormattedAmount($max_wdr_amount,$wallet_id);
        $symbol = $walllets['wallet_symbol'];
        $name = $walllets['wallet_name'];
            
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
           
           $txn_wdr_id = $this->rimplenet_fund_user_mature_wallet($request_id,$user_id, $amount_to_withdraw_ready, $wallet_id, $note);
           
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


    /**
     * Check Transaction Exists
     * @return
     */
    protected function debitsExists($value, string $type = '')
    {
        global $wpdb;
        $row = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key='txn_request_id' AND meta_value='$value'");
        if ($row) :
            $this->response['status_code'] = 409;
            $this->response['response_message'] = "Transaction Already Executed";
            $this->response['data']['txn_id'] = $row->post_id;
            return false;
            exit;
        endif;
        return true;
    }
}
