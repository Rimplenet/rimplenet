
<?php

$dir = plugin_dir_url(dirname(__FILE__));
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
                        <input type="text" class="transfer-data" placeholder="amount">
                    </div>
                    <div class="transfer-conversation c-right">
                        <input type="text" class="transfer-data" placeholder="wallet">
                        <img src="<?= $dir ?>/assets/img/me.jpeg" alt="" class="transfer-bot">
                    </div>
                    <div class="transfer-conversation c-left">
                        <img src="<?= $dir ?>/assets/img/me.jpeg" alt="" class="transfer-bot">
                        <input type="text" class="transfer-data" placeholder="User to transfer">
                    </div>
                </div>
                <div class="transfer-send-message">
                    <input type="text" class="transfer-input" placeholder="Complete Transfer" disabled>
                    <button class="transfer-send-button">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
</script>