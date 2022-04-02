<?php

if(isset( $_POST['rimplenet_general_settings_form_submitted'] ) || wp_verify_nonce( $_POST['rimplenet_general_settings_nonce_field'], 'rimplenet_general_settings_nonce_field' ) )  {

$rimplenet_rules_for_user_account_activation = sanitize_textarea_field( $_POST['rimplenet_rules_for_user_account_activation'] );

update_option( 'rimplenet_rules_for_user_account_activation', $rimplenet_rules_for_user_account_activation );


 echo '<div class="updated">
            <p>Your Settings have been saved successfully</p>
        </div> ';


}

$rimplenet_rules_for_user_account_activation = get_option( 'rimplenet_rules_for_user_account_activation','');


$input_width = 'width:95%';
?>



<div class="rimplenet_admin_div" style="<?php echo $input_width; ?>">



<h2>GENERAL SETTINGS</h2>
  <form method="POST">
    <input type="hidden" name="rimplenet_general_settings_form_submitted" value="true" />
    <?php wp_nonce_field( 'rimplenet_general_settings_nonce_field', 'rimplenet_general_settings_nonce_field' ); ?>

    <table class="form-table">
        <tbody>

            


            <tr>
                <th><label for="rimplenet_rules_for_user_account_activation"> Rules to Achieve before User Account is activated </label></th>
                <td>
                	<textarea name="rimplenet_rules_for_user_account_activation" id="rimplenet_rules_for_user_account_activation" style="<?php echo $input_width; ?>"><?php echo  sanitize_textarea_field($rimplenet_rules_for_user_account_activation); ?></textarea>

                	</td>
            </tr>

           

        </tbody>
    </table>

    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="SAVE SETTINGS">
    </p>
  </form>
</div>