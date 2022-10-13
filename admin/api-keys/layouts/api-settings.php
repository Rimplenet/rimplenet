<?php

if (isset($_POST['submit']) && isset($_POST['in_restriction_nonce_field'])) :

    $data = [
        'allowed_ip_address' => $_POST['allowed_ip_address'] ?? '',
        'allowed_domains' => $_POST['allowed_domains'] ?? ''
    ];

    foreach ($data as $key => $value) :
        update_option($key, $value);
        continue;
    endforeach;

    echo '<div class="updated">
    <p>Your settings have been saved</p>
    </div> ';
endif;

$allowed_ip_address = get_option('allowed_ip_address');
$allowed_domains = get_option('allowed_domains');
?>

<form method="POST">
    <input type="hidden" name="rimplenet_general_settings_form_submitted" value="true">
    <input type="hidden" id="in_restriction_nonce_field" name="in_restriction_nonce_field" value="d634014d33"><input type="hidden" name="_wp_http_referer" value="/testrimplenet/wp-admin/edit.php?post_type=rimplenettransaction&amp;page=settings_general">
    <table class="form-table">
        <tbody>
            <tr>
                <th><label for="allowed_domains"> Allowed Domains</label></th>
                <td>
                    <textarea name="allowed_domains" id="allowed_domains" style="width:95%; height:100px" spellcheck="false"><?= trim($allowed_domains ?? '') ?></textarea>

                </td>
            </tr>
            <tr>
                <th><label for="allowed_ip_address"> Allowed IP Addresses</label></th>
                <td>
                    <textarea name="allowed_ip_address" id="allowed_ip_address" style="width:95%; height:100px" spellcheck="false"><?= trim($allowed_ip_address ?? '') ?></textarea>
                </td>
            </tr>

        </tbody>
    </table>
    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Submit">
    </p>
</form>