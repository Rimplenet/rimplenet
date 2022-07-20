<?php
global $current_user,$wp;
wp_get_current_user();
// $wallet_obj = new Rimplenet_Wallets();

$wallet_obj = new RimplenetGetWithdrawals();
// $wallet_obj->createQuery();
$wallet_obj->getWithdrawals();
// $all_wallets=$wallet_obj->response['data'];
// $all_wallets=$wallet_obj->getWallets();
// $all_wallets=$wallet_obj::$response['data'];
$withdrawal=$wallet_obj::$response['data'];
// var_dump($all_wallets);
// die;


?>

<!-- <style>
  @media screen and (max-width: 600px) {	

      table {width:100%;}

      /* thead {display: none;} */

      tr:nth-of-type(2n) {background-color: inherit;}

      tr td:first-child {background: #f0f0f0; font-weight:bold;font-size:1.3em;}

      /* tbody td {display: block;  text-align:center;} */

      tbody td:before {

        /* content: attr(data-th); */

        display: block;

        text-align:center; 

      }

}
</style> -->







<h2> All Withdrawals</h2>
<div class="table-responsive bg-white p-5 mr-3 ml-3 rimplenet-bs5">
  <?php 
if( $withdrawal->have_posts() ){
   ?>
   
   <table class="table table-responsive-md wp-list-table widefat fixed striped posts" >
       <thead class="thead-dark">
         <tr>
             <th scope="col">ID</th>
             <th scope="col">Date</th>
             <th scope="col">Amount</th>
             <th scope="col">User</th>
             <th scope="col">Withdrawal Destination</th>
             <th scope="col">Info / Note</th>
               <th scope="col">Action</th>
         </tr>
        </thead>
         
       <tbody>
    
<?php
           
   while( $withdrawal->have_posts() ){
       $withdrawal->the_post();
       $txn_id = get_the_ID(); 
       $status = get_post_status();
       
       $approval_action = get_post_meta($txn_id, 'approval_action', true);
       if($approval_action=="rejected_and_refunded"){
           $status = '<font color="red">Rejected & Refunded</font>';
       }
       elseif($status=="pending"){
           $status = '<font color="red">Pending</font>';
       }
       elseif($status=="publish"){
           $status = '<font color="green">Approved</font>';
       }
       
       $date_time = get_the_date('D, M j, Y', $txn_id).'<br>'.get_the_date('g:i A', $txn_id);
       $wallet_id = get_post_meta($txn_id, 'currency', true);

       $all_rimplenet_wallets = $wallet_obj->getWallets();
       
       $wallet_symbol = $all_rimplenet_wallets[$wallet_id]['symbol'];
       $wallet_decimal = $all_rimplenet_wallets[$wallet_id]['decimal'];
       
       $author_id = get_post_field( 'post_author', $txn_id );
       $amount = get_post_meta($txn_id, 'amount', true);
       $txn_type = get_post_meta($txn_id, 'txn_type', true);
       
       if($txn_type=="CREDIT"){
       $amount_formatted_disp = '<font color="green">+'.$wallet_symbol.number_format($amount,$wallet_decimal).'</font>';
       }
       elseif($txn_type=="DEBIT"){
           $amount_formatted_disp = '<font color="red">-'.$wallet_symbol.number_format($amount,$wallet_decimal).'</font>';
       }
       
       $amount_formatted_disp = apply_filters("rimplenet_history_amount_formatted", $amount_formatted_disp,$txn_id, $txn_type, $amount, $amount_formatted_disp);
       
       $note = get_post_meta($txn_id, 'note', true);
       
       $withdrawal_address_to = get_post_meta($txn_id, 'withdrawal_address_to', true);
       
       $withdrawal_bank_name = get_post_meta($txn_id, 'withdrawal_bank_name', true);
       $withdrawal_account_number = get_post_meta($txn_id, 'withdrawal_account_number', true);
       $withdrawal_account_name = get_post_meta($txn_id, 'withdrawal_account_name', true);
       $withdrawal_crypto_address = get_post_meta($txn_id, 'withdrawal_crypto_address', true);
       
       $withdrawal_destination = get_post_meta($txn_id, 'withdrawal_destination', true);
       
       $withdrawal_info = "";
       if(!empty($withdrawal_account_number)){
          $withdrawal_info .= "$withdrawal_bank_name<br>
                              <code class='rimplenet_click_to_copy'>$withdrawal_account_number</code> <br>
                              <strong>$withdrawal_account_name</strong>"; 
       }
       
       if(!empty($withdrawal_crypto_address)){
          $withdrawal_info .= "<code class='rimplenet_click_to_copy'>$withdrawal_crypto_address</code>"; 
       }
       
       if(!empty($withdrawal_destination) or !empty($withdrawal_address_to)){
          $withdrawal_info .= "<br><small>$withdrawal_destination $withdrawal_address_to</small>"; 
       }
       

          $txn_nonce = wp_create_nonce('txn_nonce');
          
       $approve_txn_url = add_query_arg( array( 'action'=>'approve','txn_id'=>$txn_id,'txn_nonce'=>$txn_nonce), $viewed_url );
       $reject_refund_txn_url = add_query_arg( array( 'action'=>'reject_and_refund','txn_id'=>$txn_id,'txn_nonce'=>$txn_nonce), $viewed_url );
       $reject_no_refund_txn_url = add_query_arg( array( 'action'=>'delete','txn_id'=>$txn_id,'txn_nonce'=>$txn_nonce), $viewed_url );



   ?>

       <tr>
         <th scope="row"> #<?php echo $txn_id ?> <br> <?php echo $status; ?></th>
         <td> <?php echo $date_time ?></td>
         <td> <?php echo $amount_formatted_disp; ?> </td>
         <td> <?php echo get_the_author_meta('user_login',$author_id); ?> </td>
         <td> <?php echo $withdrawal_info; ?> </td>
         <td> <?php echo $txn_type; ?><br><?php echo $note; ?></td>
         <td>
          
       <?php if($status!="publish"){ ?>
       
       <?php if(empty($approval_action)){ ?>   
             <a href="<?php echo $approve_txn_url; ?>"  style="margin: 1px;">
                 <button class="btn btn-success btn-sm">Approve</button> 
       <?php } ?>
                 
       <?php if($approval_action!="rejected_and_refunded"){ ?>
             <a href="<?php echo $reject_refund_txn_url; ?>"  style="margin: 1px;">
                 <button class="btn btn-danger btn-sm">Reject & Refund</button>
              
       <?php } ?>
       
       
       <?php if(current_user_can('administrator') AND empty($approval_action)){ ?>
             <a href="<?php echo $reject_no_refund_txn_url; ?>"  style="margin: 1px;">
                 <button class="btn btn-danger btn-sm">Delete With No Refund </button>
       <?php } ?>
       
       <?php }
       else{ echo '<b>Approved</b>'; }?>
                 
             </a>
         </td>
       </tr>

       <?php
          
         }
        ?>
        
        
         </tbody>
     </table>
        
       <?php

        }

        ?>
</div>
