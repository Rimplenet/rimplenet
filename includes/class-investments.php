<?php

class Rimplenet_Investments extends RimplenetRules{
 
 public function __construct() {
     
     add_shortcode('rimplenet-investment-form', array($this, 'RimplenetInvestmentForm'));
     add_filter('rimplenet_constant_var', array($this, 'apply_rimplenet_constant_var_on_package_investment_rules'), 1, 3);
     add_action('wp',array($this,'return_investment_capital'));
    
 }
 
 function return_investment_capital(){
    
    $post_per_page = rand(1,500);
    $txn_loop = new WP_Query(
      array(
      'post_type' => 'rimplenettransaction', 
      'post_status' => 'publish',
      'order' => 'ASC',
      'posts_per_page'=>$post_per_page ,
      'meta_query' => array(
          'relation' => 'AND',
          array(
              'key' => 'time_for_invested_amount_to_be_returned',
              'compare' => 'EXISTS',
              ),
         array(
             'key' => 'invesment_returned',
             'compare' => 'NOT EXISTS',
             ), 
       ),
      'tax_query'     => array(
        array(
          'taxonomy' => 'rimplenettransaction_type',
          'field'    => 'name',
          'terms'    => 'INVESTMENTS',
        ),
        ),
      'fields'        => 'ids', // Only get post IDs
      )
     );

    $investments_id_array = $txn_loop->posts;
    wp_reset_postdata();
    
    foreach($investments_id_array as $key=>$inv_id){
        $time_for_refund = get_post_meta($inv_id,"time_for_invested_amount_to_be_returned",true);
        $current_time = time();
        
        if($current_time>$time_for_refund){
          
          $wallet_obj = new Rimplenet_Wallets();
          $all_wallets = $wallet_obj->getWallets();
          $txn_owner_user_id = get_post_field('post_author',$inv_id);
          $amount_to_returned = get_post_field( "amount", $inv_id);
          $investment_wallet = get_post_field("currency", $inv_id);
          $note = "Capital Returns for Investment - #$inv_id";
          $tags["txn_ref"] = "invested_amount_returned_on_investment_".$inv_id;
          
          $funds_id_capital_returned = $wallet_obj->add_user_mature_funds_to_wallet($txn_owner_user_id, $amount_to_returned, $investment_wallet,$note,$tags);  
             
          update_post_meta($inv_id, "invesment_returned","yes");
          add_post_meta($inv_id, "time_invesment_returned",time());
          add_post_meta($inv_id, "invesment_returned_txn_id",$funds_id_capital_returned);
        }
    }
 }
 
 function rimplenet_wallet_investment($user_id, $amount_to_invest, $wallet_id, $note='Investment'){
       
        
        $wallet_obj = new Rimplenet_Wallets();
        $user_wdr_bal = $wallet_obj->get_withdrawable_wallet_bal($user_id, $wallet_id);
        $user_non_wdr_bal = $wallet_obj->get_nonwithdrawable_wallet_bal($user_id, $wallet_id);
        
        $walllets = $wallet_obj->getWallets();
        $dec = $walllets[$wallet_id]['decimal'];
        $symbol = $walllets[$wallet_id]['symbol'];
        $name = $walllets[$wallet_id]['name'];
          
        $balance = $symbol.number_format($balance,$dec);
        
         if (empty($amount_to_invest) OR empty($wallet_id) OR empty($user_id)) {
            $investment_info = 'One or more compulsory field is empty';
          }
         elseif ($amount_to_invest>$user_wdr_bal) {
            $investment_info = 'Amount to Invest - <strong>['.getRimplenetWalletFormattedAmount($amount_to_invest,$wallet_id).']</strong> is larger than the amount in your mature wallet, input amount not more than the balance in your <strong>( '.$name.' mature wallet - ['.getRimplenetWalletFormattedAmount($user_wdr_bal,$wallet_id).'] ),</strong> the balance in your <strong>( '.$name.' immature wallet  - ['. getRimplenetWalletFormattedAmount($user_non_wdr_bal,$wallet_id).'] )</strong>  cannot be invested until maturity';
          }
    
        
        else{
            
                  
        
         $amount_to_invest = $amount_to_invest * -1;
         $txn_wdr_id = $wallet_obj->add_user_mature_funds_to_wallet($user_id,$amount_to_invest, $wallet_id, $note);
    
         if (is_int($txn_wdr_id)) {
           $modified_title = 'INVESTMENT ~ '.get_the_title( $txn_wdr_id);
           $note = "Investment - #".$txn_wdr_id;
           $args = 
              array(
              'ID'    =>  $txn_wdr_id,
              'post_title'   => $modified_title,
              'post_status'   =>  'publish',
               'meta_input' => array(
                'note'=>$note,
                )
              );
              
    
             wp_set_object_terms($txn_wdr_id, 'INVESTMENTS', 'rimplenettransaction_type', true);
             wp_update_post($args);
     
             $investment_info = $txn_wdr_id;
             
          }
        }
     wp_reset_postdata();
    return $investment_info;

}

 
 public function RimplenetInvestmentForm($atts) {
            

        ob_start();

        include plugin_dir_path( __FILE__ ) . 'layouts/rimplenet-investment-form.php';
         
        $output = ob_get_clean();

        return $output;
      
 }
 
 
   function apply_rimplenet_constant_var_on_package_investment_rules($rules, $user, $obj_id) {
      
         if( strpos($obj_id, "package_") !== false AND strpos($obj_id, "_investment_") !== false ){
             
             //Extract the obj_id, investment_id, using the preg_match_all function.
              preg_match_all('!\d+!', $obj_id, $matches);
     
            //The obj_id, order_id, and qnt will be in our $matches array
             $ret_obj_id = $matches[0][0];
             $ret_investment_id = $matches[0][1];
             
            if(strpos($rules, "{GET_INVESTMENT_WALLET_ID}")!== false){
                $ret_wallet_id = get_post_meta($ret_investment_id,'currency',true);
                $rules = str_replace("{GET_INVESTMENT_WALLET_ID}",$ret_wallet_id,$rules); 
            }
                 
            if( strpos($rules, "{RIMPLENET_CONSTANT_VAR_")!== false AND strpos($rules, "_PERCENT_OF_INVESTMENT_AMOUNT}")!== false){
         
                $investment_amount = get_post_meta($ret_investment_id,'amount',true);
                
                
              if (is_numeric($investment_amount)) {
                //Extract the constant match using the preg_match_all function to $inv_amount_percent_matches.
                preg_match_all('/{RIMPLENET_CONSTANT_VAR_\S*_PERCENT_OF_INVESTMENT_AMOUNT}/', $rules, $inv_amount_percent_matches);
                 
                    
                //Any matches will be in our $inv_amount_percent_matches array of array
                foreach($inv_amount_percent_matches[0] as $inv_amount_percent_match ){
                   $initial_const_var = $inv_amount_percent_match;
                   preg_match('!\d+\.*\d*!', $inv_amount_percent_match, $ordered_price_percent);//extract the percent value to $ordered_price_percent
                    
                   $percent_value = $ordered_price_percent[0];
                   $price = ($percent_value/100) * $investment_amount;
                   
                   $rules = str_replace($initial_const_var,$price,$rules);
                   
                 }
                 
               }
                
            }
            
            
            $status = $rules;
          
          
        }
        else{
            $status = $rules;
        }
        
        return $status;
        
       }
   

}

$Rimplenet_Investments = new Rimplenet_Investments();
?>