<?php


//$withdrawal_obj = new Rimplenet_Withdrawals();



$wallet_obj = new Rimplenet_Wallets();
$all_wallets = $wallet_obj->getWallets();


$input_width = 'width:98%';
?>



<div class="rimplenet_admin_div" style="<?php echo $input_width; ?>">
 <h2> WITHDRAWALS TRANSACTIONS</h2>
    <?php
    // display withdrawal txns
     display_withdrawal_txns();
    
    ?>
</div>