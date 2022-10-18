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
        <div class="row">
            <form class="row mt-5 align-items-center transfer-form" method="POST">
                <?php wp_nonce_field('rimplenet_wallet_settings_nonce_field', 'rimplenet_wallet_settings_nonce_field'); ?>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th><label for="allowed_ip_domain">App Name</label></th>
                            <td>
                                <input name="app_name" class="form-control" type="text" id="app-name" placeholder="App Name">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="allowed_ip_domain">Key Type</label></th>
                            <td>

                                <select class="form-select" aria-label="Default select example" name="key-type"" id=" key-type">
                                    <option value="">Select Key</option>
                                    <option value="read-only">Read Only</option>
                                    <option value="read-write">Read Write</option>
                                    <option value="write-only">Write Only</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="allowed_ip_domain">Allowed Actions</label></th>
                            <td>
                                <?php
                                foreach (ApiKey::$actionType as $action) :
                                    echo '<div class="form-check">
                                    <input class="form-check-input" name="allowed_actions[]" type="checkbox" value="' . $action . '" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                       ' . ucwords(str_replace('_', ' ', $action)) . '
                                    </label>
                                </div>';
                                endforeach;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="allowed_ip_domain"> Allowed Domain/IP</label></th>
                            <td>
                                <textarea name="allowed_ip_domain" id="allowed_ip_domain" style="width:95%; height:100px" spellcheck="false"><?= trim($allowed_ip_domain ?? '') ?></textarea>
                            </td>
                        </tr>

                    </tbody>
                </table>
                <p class="submit">

                    <input type="hidden" name="create_key">
                    <button type="submit" class="btn btn-primary transfer-send-button">Create Key</button>
                </p>
            </form>
        </div>
    </div>
</div>

<script>
</script>