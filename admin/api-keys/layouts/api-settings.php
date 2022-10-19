<?php

if (isset($_POST['submit']) && isset($_POST['in_restriction_nonce_field'])) :

        $allowed_ip_domain = $_POST['allowed_ip_domain'] ?? '';
        update_option('allowed_ip_domain', $allowed_ip_domain);

    echo '<div class="updated">
    <p>Your settings have been saved</p>
    </div> ';
endif;

$allowed_ip_domain = get_option('allowed_ip_domain');
?>

<form method="POST">
    <input type="hidden" name="rimplenet_general_settings_form_submitted" value="true">
    <input type="hidden" id="in_restriction_nonce_field" name="in_restriction_nonce_field" value="d634014d33"><input type="hidden" name="_wp_http_referer" value="/testrimplenet/wp-admin/edit.php?post_type=rimplenettransaction&amp;page=settings_general">
    <table class="form-table">
        <tbody>
            <tr>
                <th><label for="allowed_ip_domain"> Allowed Domain/IP</label></th>
                <td>
                    <textarea name="allowed_ip_domain" id="allowed_ip_domain" style="width:95%; height:100px" spellcheck="false"><?= trim($allowed_ip_domain ?? '') ?></textarea>
                </td>
            </tr>

        </tbody>
    </table>
    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Submit">
    </p>
</form>