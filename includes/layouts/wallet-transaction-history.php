<?php
//Included from shortcode in includes/class-wallets.php
//use case [rimplenet-wallet-history wallet_id="rimp,usd,ngn"]
 global $current_user;
 wp_get_current_user();

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
     return ; // DONT'T PROCCEED
   }
   
 //Catch Error Messages
 if(!empty($_POST['rimplenet_success_message'])){
    $status_success =  sanitize_text_field($_POST['rimplenet_success_message']);
 }
 elseif(!empty($_GET['rimplenet_success_message'])){
    $status_success =  sanitize_text_field($_GET['rimplenet_success_message']);
 }
 //Catch Error Messages 
 if(!empty($_POST['rimplenet_error_message'])){
    $status_error =  sanitize_text_field($_POST['rimplenet_error_message']);
 }
 elseif(!empty($_GET['rimplenet_error_message'])){
    $status_error =  sanitize_text_field($_GET['rimplenet_error_message']);
 }
?>
<?php
$all_wallets = $this->getWallets();

$atts = shortcode_atts( array(

    'action' => 'empty',
    'user_id' => $current_user->ID,
    'wallet_id' => 'woocommerce_base_cur',
    'cancel_wdr_button_text' => 'cancel_wdr_button_text',
    'action_header_text' => 'action_header_text',
    'posts_per_page' => get_option( 'posts_per_page' ),
), $atts );


$action = $atts['action'];
$user_id = $atts['user_id'];
$wallet_id = $atts['wallet_id'];
$cancel_wdr_button_text = $atts['cancel_wdr_button_text'];
$action_header_text = $atts['action_header_text '];
$posts_per_page = $atts['posts_per_page'];


$viewed_url = $_SERVER['REQUEST_URI'];




?>
 <div class="rimplenet-status-msg">
    <center>
          <?php
            if(!empty($status_success)) {
           ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $status_success; ?>
              </div>
          <?php
                }
            ?>
            
          <?php
             if (!empty($status_error)) {
           ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $status_error; ?>
              </div>
            <?php
                }
            ?>
    </center>
 </div>
        
<div class="rimplenet-mt"> 
    <div class="row">
        <div class="col-md-12"> 

     
            <?php
            
			    
				if (!empty($pageno) OR $_GET['pageno']>1) {
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
                                   'relation' => 'OR',
                                   array(
                                    'taxonomy' => 'rimplenettransaction_type',
                                    'field'    => 'name',
                                    'terms'    => array( 'CREDIT' ),
                                  ),
                                  array(
                                    'taxonomy' => 'rimplenettransaction_type',
                                    'field'    => 'name',
                                    'terms'    => array( 'DEBIT' ),
                                        ),
                                       ),
                                    )
                              );
                              
                    if( $txn_loop->have_posts() ){
                    ?>
                    <style>
                        /**
                        .rimplenet-mt .table .td-note {
                            max-width: 60px;
                            word-break: break-word;
                        }
                        **/
                    </style>
                     
                     <table class="table table-responsive-md">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">ID</th>
                          <th scope="col">Date</th>
                          <th scope="col">Amount</th>
                          <th scope="col">Type</th>
                          <th scope="col">Note</th>
                          <th scope="col">Action</th>

                        </tr>
                      </thead>
                          <tbody>
                <?php
                            
                    while( $txn_loop->have_posts() ){
                        $txn_loop->the_post();
                        $txn_id = get_the_ID(); 
                        $status = get_post_status();
                        
                        $date_time = get_the_date('D, M j, Y', $txn_id).'<br>'.get_the_date('g:i A', $txn_id);
                        $wallet_id = get_post_meta($txn_id, 'currency', true);

                        $all_rimplenet_wallets = $this->getWallets();
                        
                        $wallet_symbol = $all_rimplenet_wallets[$wallet_id]['symbol'];
                        $wallet_decimal = $all_rimplenet_wallets[$wallet_id]['decimal'];
                        
                        
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

                        $view_txn_nonce = wp_create_nonce('view_txn_nonce');
                        //$txn_view_url = add_query_arg( array( 'txn_id'=>$txn_id,'view_txn_nonce'=>$view_txn_nonce), home_url(add_query_arg(array(),$wp->request)) );



                    ?>

                        <tr>
                          <th scope="row"> #<?php echo $txn_id ?></th>
                          <td> <?php echo $date_time ?></td>
                          <td> <?php echo $amount_formatted_disp; ?> </td>
                          <td> <?php echo $txn_type; ?> </td>
                          <td class="td-note"><?php echo $note; ?></td>
                          <td> 
                           <?php do_action('rimplenet_wallet_history_txn_action', $txn_id, $wallet_id, $amount, $txn_type, $note ); ?>
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