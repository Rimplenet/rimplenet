<?php
global $current_user,$wp;
wp_get_current_user();
// $wallet_obj = new Rimplenet_Wallets();

$wallet_obj = new RimplenetGetUser();
// $wallet_obj->createQuery();
$wallet_obj->get_user(1, "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3QiLCJpYXQiOjE2NTEwOTQyODcsImV4cCI6MTY1MTA5Nzg4NywiZGF0YSI6eyJJRCI6IjI1IiwidXNlcl9sb2dpbiI6InRhaXdvb28iLCJ1c2VyX25pY2VuYW1lIjoidGFpd29vbyIsInVzZXJfZW1haWwiOiJ0YWl3b0BnbWFpbC5jb20iLCJ1c2VyX3VybCI6IiIsInVzZXJfcmVnaXN0ZXJlZCI6IjIwMjItMDQtMjcgMjA6NDE6NDkiLCJ1c2VyX2FjdGl2YXRpb25fa2V5IjoiIiwidXNlcl9zdGF0dXMiOiIwIiwiZGlzcGxheV9uYW1lIjoidGFpd29vbyJ9fQ.TX-xxGCl5JMn3IUu8bBdk3vAJr-FI5UQfOd_pztX0Vw");
$all_wallets=$wallet_obj->response;

var_dump($all_wallets);
die;


?>

<style>
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
</style>

<h2> ACTIVE WALLETS</h2>
<table class="wp-list-table widefat fixed striped posts" >

 <thead>
  <tr>
    <th> Wallet Name </th>
    <th> Description </th>
    <th> Wallet Symbol - (ID) </th>
    <th> Wallet Decimal </th>
    <th> User Balance Shortcode </th>
    <!-- <th> Include Wallet in Withdrawal Form</th> -->
    <!-- <th> Include Wallet in Woocommerce Currency List</th> -->
    <th> Actions </th>
  </tr>
 </thead>
  
 <tbody>

<?php
    foreach ($all_wallets as $key => $value) {
        // $wallet_id = get_post_meta($txn_id, 'rimplenet_wallet_id', true);
        $user_balance_shortcode  = '[rimplenet-wallet action="view_balance" wallet_id="'.$value['post_id'].'"]';
        $edit_wallet_link = '<a href="'.get_edit_post_link($value['post_id']).'" target="_blank">Edit Wallet</a>';
        if(!empty($linked_page_id)){
            $view_wallet_page_link = ' | <a href="'.get_permalink($linked_page_id).'" target="_blank">View Wallet</a>' ;
        }
        
    //     //$view_linked_product_link = ' | <a href="'.get_post_permalink($linked_woocommerce_product_id).'"  target="_blank">View Linked Product</a>';

 ?>
  
  <tr>
    <td><?php echo $value['wallet_name']; ?></td>
    <td><?php echo $value['wallet_note']; ?></td>
    <td><?php echo $value['wallet_symbol']; ?> - (<?php echo $value['wallet_id']; ?>)</td>
    <td><?php echo $value['wallet_decimal']; ?></td>
    <td> <code class="rimplenet_click_to_copy"> <?php echo $user_balance_shortcode; ?></code> </td>
    <!-- <td><?php //echo $value['include_in_withdrawal_form']; ?></td> -->
    <!-- <td><?php //echo $value['include_in_woocommerce_currency_list']; ?></td> -->
    <td> 
      <?php echo $edit_wallet_link; ?> <?php //echo $view_wallet_page_link; ?> <?php //echo $edit_linked_product_link; ?> <?php //echo $view_linked_product_link; ?>
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
    <!-- <th> Include Wallet in Withdrawal Form</th> -->
    <!-- <th> Include Wallet in Woocommerce Currency List</th> -->
    <th> Actions </th>
  </tr>
  </tfoot>

</table>
