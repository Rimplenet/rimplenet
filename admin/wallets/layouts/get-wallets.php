<?php
global $current_user,$wp;
wp_get_current_user();
// $wallet_obj = new Rimplenet_Wallets();

$wallet_obj = new RimplenetGetWallets();
$wallet_obj->createQuery();
$all_wallets = $wallet_obj->getWallets();

// var_dump($all_wallets);


?>

<h2> ACTIVE WALLETS</h2>
<table class="wp-list-table widefat fixed striped posts" >

 <thead>
  <tr>
    <th> Wallet Name </th>
    <th> Description </th>
    <th> Wallet Symbol - (ID) </th>
    <th> Wallet Decimal </th>
    <th> User Balance Shortcode </th>
    <th> Include Wallet in Withdrawal Form</th>
    <th> Include Wallet in Woocommerce Currency List</th>
    <th> Actions </th>
  </tr>
 </thead>
  
 <tbody>

<?php
    foreach ($all_wallets as $key => $value) {
        // $wallet_id = get_post_meta($txn_id, 'rimplenet_wallet_id', true);
        $user_balance_shortcode  = '[rimplenet-wallet action="view_balance" wallet_id="'.$value->wallet_id.'"]';
        $edit_wallet_link = '<a href="'.get_edit_post_link($txn_id).'" target="_blank">Edit Wallet & Rules</a>';
        if(!empty($linked_page_id)){
            $view_wallet_page_link = ' | <a href="'.get_permalink($linked_page_id).'" target="_blank">View Wallet Page</a>' ;
        }
        
    //     //$view_linked_product_link = ' | <a href="'.get_post_permalink($linked_woocommerce_product_id).'"  target="_blank">View Linked Product</a>';

 ?>
  
  <tr>
    <td><?php echo $value['wallet_name']; ?></td>
    <td><?php echo $value['description']; ?></td>
    <td><?php echo $value['wallet_symbol']; ?> - (<?php echo $value['wallet_id']; ?>)</td>
    <td><?php echo $value['wallet_decimal']; ?></td>
    <td> <code class="rimplenet_click_to_copy"> <?php echo $user_balance_shortcode; ?></code> </td>
    <td><?php echo $value['include_in_withdrawal_form']; ?></td>
    <td><?php echo $value['include_in_woocommerce_currency_list']; ?></td>
    <td> 
      <?php echo $edit_wallet_link; ?> <?php echo $view_wallet_page_link; ?> <?php echo $edit_linked_product_link; ?> <?php echo $view_linked_product_link; ?>
    </td>

    
  </tr>

  <?php

    }

  

  ?>
  
</tbody>

 <tfoot>
  <tr>
    <th> Wallet Name </th>
    <th> Description </th>
    <th> Wallet Symbol - (ID) </th>
    <th> Wallet Decimal </th>
    <th> User Balance Shortcode </th>
    <th> Include Wallet in Withdrawal Form</th>
    <th> Include Wallet in Woocommerce Currency List</th>
    <th> Actions </th>
  </tr>
  </tfoot>

</table>