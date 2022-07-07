<?php

$dir = plugin_dir_url(dirname(__FILE__));
require plugin_dir_path(dirname(__FILE__)) . '/assets/php/create.php';
require plugin_dir_path(dirname(__FILE__)) . '/assets/php/helpers.php';
?>

<br>
<br>
<input type="hidden" id="__rt_dir" value="<?= $dir ?>">
<div class="rimplenet-bs5">
    <div class="container">
        <div class="row bg-white">
            <div class="col-md-6">

                <h1 class="display-4">About <span class="fw-bold text-primary">Transfer</span></h1>
                <p class="text-start">
                    This page allows you to transfer funds from one user to another
                </p>
            </div>
            <div class="col-md-6">
                <form class="row mt-5 align-items-center transfer-form" method="POST">

                    <?php wp_nonce_field('rimplenet_wallet_settings_nonce_field', 'rimplenet_wallet_settings_nonce_field'); ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="" for="autoSizingInputGroup">Wallet</label>
                            <select class="form-select" aria-label="Default select example"  name="transfer_wallet"" id="transfer-wallet">
                                <?= returnWalletHtml() ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="" for="autoSizingInputGroup">Amount</label>
                            <input name="transfer_amount" class="form-control" type="number" id="transfer-amount" placeholder="Amount">
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="" for="autoSizingInputGroup">Transfer From</label>
                            <div class="input-group">
                                <div class="input-group-text"> <span class="wallet_symbol">$</span> <span class="wallet_balance">0.00</span></div>
                                <input name="transfer_from" type="text" id="transfer-from" class="form-control user-transfer" placeholder="Username">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="" for="autoSizingInputGroup">Transfer To</label>
                            <div class="input-group">
                                <div class="input-group-text"> <span class="wallet_symbol">$</span> <span class="wallet_balance">0.00</span></div>
                                <input class="form-control user-transfer" name="transfer_user" type="text" id="transfer-user" placeholder="Username">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 mt-3">
                        <div class="col-12">
                            <input type="hidden" name="create_transfer">
                            <button type="submit" class="btn btn-primary transfer-send-button">Initiate Transfer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
</script>