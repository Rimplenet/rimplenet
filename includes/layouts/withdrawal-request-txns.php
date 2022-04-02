<?php
//Included from shortcode in includes/.php
//use case [rimplenet-withdrawal-form user_id="1"]
 global $current_user;
 wp_get_current_user();
 ?>
 <?php
   if(!is_user_logged_in()) {
?>
  <center>
   <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong> ERROR: </strong> Please Login or Register to Procced
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
   </div>
  </center>
<?php
     return ;// END PROCESS IF NOT LOGGED IN
   }
?> 
<?php
 $user = wp_get_current_user();
 if ( in_array( 'subscriber', (array) $user->roles ) ) {
    //The user has the above role
?>
  <center>
   <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong> ERROR: </strong>Restricted Zone: Your are not Allowed 
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
   </div>
  </center>
<?php
     return ;// END PROCESS
   }
?>
<?php

    global $current_user,$wp;
    wp_get_current_user();
    
    $viewed_url = $_SERVER['REQUEST_URI'];
    
    $atts = shortcode_atts( array(
       'posts_per_page' => get_option( 'posts_per_page' ),
       'allowed_users' => get_option( 'allowed_users' ),
     ), $atts );
    
    $wallet_obj = new Rimplenet_Wallets();
    $all_wallets = $wallet_obj->getWallets();
    
    $posts_per_page = $atts['posts_per_page'];
    $allowed_users = $atts['allowed_users'];
    
    $action = sanitize_text_field($_GET['action']);
    
    $action_nonce = sanitize_text_field($_GET['txn_nonce']);
    $txn_id = sanitize_text_field($_GET['txn_id']);
     
    if(wp_verify_nonce($action_nonce, 'txn_nonce')){
       
       if($action=='approve'){
            //Approve Post
            wp_publish_post( $txn_id );
            
            add_post_meta($txn_id, 'approval_action','approved');
            add_post_meta($txn_id, 'approval_user',$current_user->ID);
            $key_appr_time = 'approval_time_by_user_'.$current_user->ID;
            add_post_meta($txn_id, $key_appr_time, time() );
            
            //hook
            do_action('rimplenet_withdrawal_on_approval_action', $txn_id );
            $success_message = '<strong>APPROVAL SUCCESSFUL</strong>: Withdrawal Request has been approved.';
	   }
	   elseif($action=='reject_and_refund'){
	       
            $withdrawal_obj = new Rimplenet_Withdrawals();
            $cancellation_info = $withdrawal_obj->cancel_withdrawal_and_refund();
    	    if($cancellation_info>1){
                
              add_post_meta($txn_id, 'approval_action','rejected_and_refunded');
              add_post_meta($txn_id, 'approval_user',$current_user->ID);
              $key_appr_time = 'approval_time_by_user_'.$current_user->ID;
              add_post_meta($txn_id, $key_appr_time, time() );
              
              $success_message = '<strong>REJECTED AND REFUND SUCCESSFUL</strong>: Withdrawal Rejected Successful, Refund has been made to User.';
            }
            else{
              $error_message = $cancellation_id;
            }
            
            
	   }
	   
	   elseif($action=='delete' AND current_user_can('administrator')){
	       
          add_post_meta($txn_id, 'approval_action','approved');
          add_post_meta($txn_id, 'approval_user',$current_user->ID);
          $key_appr_time = 'approval_time_by_user_'.$current_user->ID;
          add_post_meta($txn_id, $key_appr_time, time() );
          wp_trash_post( $txn_id );
          
          $success_message = '<strong>DELETE SUCCESSFUL</strong>: Withdrawal Request Deleted Successful.';
        
	   }
		
        
    }
?>
<div class="rimplenet-mt"> 

						<?php

                           if (!empty($success_message)) {
                         
                        ?>
                        <br>
                         <div class="alert alert-success alert-dismissible fade show" role="alert">
                          <?php echo $success_message; ?>
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                         </div>
                        <br>
                        <?php
                          }
    

                     ?>

					<?php

                           if (!empty($error_message)) {
                         
                        ?>
                        <br>
                         <div class="alert alert-danger alert-dismissible fade show" role="alert">
                          <?php echo $error_message; ?>
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                         </div>
                        <br>
                        <?php
                          }
    

                     ?>

      
    <div class="row">
	 	<div class="col-md-12"> 

	 
			<?php
			
			    if(!empty($pageno) OR $_GET['pageno']>1) {
				  $pageno = sanitize_text_field($_GET['pageno']);
				}else{
				  $pageno = 1;
				}
	      	   $txn_loop = new WP_Query(
                             array(  
                               'post_type' => 'rimplenettransaction', 
                               'post_status' => 'any',
                               'author' => $user_id ,
                               'posts_per_page'=>$posts_per_page,
                               'paged'=>$pageno,
                               'tax_query' => array(
                                   'relation' => 'AND',
                                   array(
                                    'taxonomy' => 'rimplenettransaction_type',
                                    'field'    => 'slug',
                                    'terms'    => array( 'withdrawal','withdrawal-processed' ),
                                  ),
                                       ),
                                    )
                              );
                              
                    if( $txn_loop->have_posts() ){
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
                            
                    while( $txn_loop->have_posts() ){
                        $txn_loop->the_post();
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
				         else{
				         	echo "<center>No Transaction found for this request</center>";
				         }

                        rimplenet_pagination_bar($txn_loop,$pageno);
                        wp_reset_postdata();

				        ?>


         </div>
	 	</div>
	   </div>