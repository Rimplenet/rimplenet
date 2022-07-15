<?php
global $current_user,$wp;
wp_get_current_user();
// $wallet_obj = new Rimplenet_Wallets();

$credits = new RimplenetGetDebits();
// $wallet_obj->createQuery();
$credits->getAllDebits();
$all_credits=$credits::$response['data'];

// var_dump($all_credits);
// die;

?>
<h2> ALL Debits</h2>
<div class="table-responsive bg-white p-5 mr-3 ml-3 rimplenet-bs5">
<table class="table table-sm table-borderless table-striped rimplenet-bs5" style="width:100%" id="rimplenetmyTable">

<thead>
 <tr>
   <!-- <th> Wallet Name </th> -->
   <th> Description </th>
   <th> Note </th>
   <th> Amount </th>
   <th> Balance Before </th>
   <th> Balance After </th>
   <th> Currency </th>
   <th> Actions </th>
 </tr>
</thead>
 
<tbody>

<?php
   foreach ($all_credits as $key => $value) {
    $edit_wallet_link = '<a href="'.get_edit_post_link($value['id']).'" target="_blank" class="btn-primary btn">Edit Debit</a>';

?>
 
 <tr>
   <!-- <td><?php echo $value['wallet_name']; ?></td> -->
   <td><?php echo $value['description']; ?></td>
   <td><?php echo $value['note']; ?></td>
   <td><?php echo $value['currency'].' '.$value['amount']; ?></td>
   <td><?php echo $value['balance_before']; ?></td>
   <td><?php echo $value['balance_after']; ?></td>
   <td><?php echo $value['currency']; ?></td>
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
</div>

