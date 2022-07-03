
<?php

$dir = plugin_dir_url(dirname(__FILE__));
require plugin_dir_path(dirname(__FILE__)) . '/assets/php/create.php';
?>

<div class="user-card with-cont">
    <div class="form-container">
        <img src="<?= $dir ?>/assets/img/trs.png" alt="">
    </div>
    <input type="hidden" id="pluginUrl" value="<?= $dir ?>">
    <div class="form-container rt">
        <div class="transfer-chat-container">
            <div class="transfer-nav-head">
                <div class="transfer-larr"><</div>
                <h3 class="transfer-chat-title">Live Transfer</h3>
                <div class="transfer-cancel">x</div>
            </div>
            <form action="" method="POST" class="transfer-form">
            <?php wp_nonce_field('rimplenet_wallet_settings_nonce_field', 'rimplenet_wallet_settings_nonce_field'); ?>
                <div class="transfer-chat-box">
                    <div class="transfer-message-container">
                        <!-- <div class="start-transfer">
                            <button class="transfer-start">Start</button>
                        </div> -->
                        <div class="conversation-left">
                            <img src="<?= $dir ?>/assets/img/bot.jpg" alt="" class="transfer-bot">
                        </div>
                        <div class="transfer-conversation c-left">
                            <img src="<?= $dir ?>/assets/img/transfer.jpg" alt="" class="transfer-bot">
                            <input name="transfer_amount" type="number" id="transfer-amount" class="transfer-data" placeholder="amount" >
                        </div>
                        <div class="transfer-conversation c-right">
                            <input name="transfer_wallet" type="text" id="transfer-wallet" class="transfer-data" placeholder="wallet">
                            <img src="<?= $dir ?>/assets/img/me.jpeg" alt="" class="transfer-bot">
                        </div>
                        <div class="transfer-conversation c-left t-mb-l">
                            <img src="<?= $dir ?>/assets/img/me.jpeg" alt="" class="transfer-bot">
                            <input name="transfer_user" type="text" id="transfer-user" class="transfer-data" placeholder="User to transfer">
                        </div>
                    </div>
                    <div class="transfer-send-message">
                        <input type="text" class="transfer-input" placeholder="Complete Transfer" disabled>
                        <input type="hidden" name="create_transfer">
                        <button type="submit" class="transfer-send-button">Send</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
</script>