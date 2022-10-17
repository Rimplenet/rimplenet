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

                <h1 class="display-4">Create <span class="fw-bold text-primary">API Keys</span></h1>
                <p class="text-start">
                    This page allows you to create new API Key
                </p>
            </div>
            <div class="col-md-6">
                <form class="row mt-5 align-items-center transfer-form" method="POST">

                    <?php wp_nonce_field('rimplenet_wallet_settings_nonce_field', 'rimplenet_wallet_settings_nonce_field'); ?>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="" for="autoSizingInputGroup">App Name</label>
                            <input name="app_name" class="form-control" type="text" id="app-name" placeholder="App Name">
                        </div>
                        <!-- <div class="col-md-6">
                            <label class="" for="autoSizingInputGroup">Action</label>
                            <input name="action" class="form-control" type="text" id="action" placeholder="Create Api Token">
                        </div> -->

                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="" for="autoSizingInputGroup">Key Type</label>
                            <div class="input-group">
                                <div class="input-group-text"> Permission </div>
                                <select class="form-select" aria-label="Default select example" name="key-type"" id=" key-type">
                                    <option value="">Select Key</option>
                                    <option value="read-only">Read Only</option>
                                    <option value="read-write">Read Write</option>
                                    <option value="write-only">Write Only</option>
                                </select>
                            </div>
                            <!-- <label class="" for="autoSizingInputGroup">Key Type</label> -->
                        </div>
                        <div class="col-12">
                            <label class="" for="autoSizingInputGroup">Allowed Actions</label>
                            <?php
                            foreach (ApiKey::$actionType as $action) :
                                echo '<div class="form-check">
                                    <input class="form-check-input" name="allowed_actions[]" type="checkbox" value="'.$action.'" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                       '.ucwords(str_replace('_', ' ', $action)).'
                                    </label>
                                </div>';
                            endforeach;
                            ?>
                        </div>
                    </div>
                    <div class="row mb-3 mt-3">
                        <div class="col-12">
                            <input type="hidden" name="create_key">
                            <button type="submit" class="btn btn-primary transfer-send-button">Create Key</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
</script>