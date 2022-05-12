<?php
global $current_user, $wp;
wp_get_current_user();
$wallet_obj = new Rimplenet_Wallets();
$all_wallets = $wallet_obj->getWallets();
$WALLET_CAT_NAME = 'RIMPLENET WALLETS';


if (isset($_POST['rimplenet_wallet_submitted']) || wp_verify_nonce($_POST['rimplenet_wallet_settings_nonce_field'], 'rimplenet_wallet_settings_nonce_field')) {

  $rimplenet_wallet_name = sanitize_text_field($_POST['rimplenet_wallet_name']);
  $rimplenet_wallet_desc = sanitize_text_field($_POST['rimplenet_wallet_desc']);
  $rimplenet_wallet_decimal = sanitize_text_field($_POST['rimplenet_wallet_decimal']);
  $rimplenet_min_withdrawal_amount = sanitize_text_field($_POST['rimplenet_min_withdrawal_amount']);
  $rimplenet_max_withdrawal_amount = sanitize_text_field($_POST['rimplenet_max_withdrawal_amount']);
  $rimplenet_wallet_symbol = sanitize_text_field($_POST['rimplenet_wallet_symbol']);
  $rimplenet_wallet_symbol_position = sanitize_text_field($_POST['rimplenet_wallet_symbol_position']);
  $include_in_withdrawal_form = sanitize_text_field($_POST['include_in_withdrawal_form']);
  $include_in_woocommerce_currency_list = sanitize_text_field($_POST['include_in_woocommerce_currency_list']);
  $include_in_woocommerce_product_payment_wallet = sanitize_text_field($_POST['include_in_woocommerce_product_payment_wallet']);
  $rimplenet_wallet_id = sanitize_text_field($_POST['rimplenet_wallet_id']);



  $rimplenet_rules_before_wallet_withdrawal = sanitize_text_field($_POST['rimplenet_rules_before_wallet_withdrawal']);
  $rimplenet_rules_after_wallet_withdrawal = sanitize_text_field($_POST['rimplenet_rules_after_wallet_withdrawal']);



  // Create wallet on RIMPLENET CPT
  $args = array(
    'post_title' => $rimplenet_wallet_name,
    'post_content' => $rimplenet_wallet_desc,
    'post_status' => 'publish',
    'post_type' => "rimplenettransaction",
  );

  $wallet_id = wp_insert_post($args);
  wp_set_object_terms($wallet_id, $WALLET_CAT_NAME, 'rimplenettransaction_type');
  $metas = array(
    'rimplenet_wallet_name' => $rimplenet_wallet_name,
    'rimplenet_wallet_decimal' => $rimplenet_wallet_decimal,
    'rimplenet_min_withdrawal_amount' => $rimplenet_min_withdrawal_amount,
    'rimplenet_max_withdrawal_amount' => $rimplenet_max_withdrawal_amount,
    'rimplenet_wallet_symbol' => $rimplenet_wallet_symbol,
    'rimplenet_wallet_symbol_position' => $rimplenet_wallet_symbol_position,
    'rimplenet_wallet_id' => strtolower($rimplenet_wallet_id),
    'include_in_withdrawal_form' => $include_in_withdrawal_form,
    'include_in_woocommerce_currency_list' => $include_in_woocommerce_currency_list,
    'include_in_woocommerce_product_payment_wallet' => $include_in_woocommerce_product_payment_wallet,

    'rimplenet_rules_before_wallet_withdrawal' => $rimplenet_rules_before_wallet_withdrawal,
    'rimplenet_rules_after_wallet_withdrawal' => $rimplenet_rules_after_wallet_withdrawal,
  );
  foreach ($metas as $key => $value) {
    update_post_meta($wallet_id, $key, $value);
  }

  echo '<div class="updated">
            <p>Your Wallet have been created successfully</p>
        </div> ';
}



if (isset($_POST['rimplenet_credit_debit_submitted']) || wp_verify_nonce($_POST['rimplenet_credit_debit_nonce_field'], 'rimplenet_credit_debit_nonce_field')) {

  $wallet_id = sanitize_text_field($_POST["rimplenet_wallet"]);
  $rimplenet_amount = sanitize_text_field($_POST["rimplenet_amount"]);
  $rimplenet_txn_type = sanitize_text_field($_POST["rimplenet_txn_type"]);
  $rimplenet_user = sanitize_text_field($_POST["rimplenet_user"]);
  $rimplenet_note = sanitize_text_field($_POST["rimplenet_credit_debit_note"]);


  if ($rimplenet_txn_type == "credit") {

    $funds_note = $rimplenet_note;
    $funds_id = $wallet_obj->add_user_mature_funds_to_wallet($rimplenet_user, $rimplenet_amount, $wallet_id, $funds_note);
  } elseif ($rimplenet_txn_type == "debit") {

    $funds_note = $rimplenet_note;
    $rimplenet_amount = $rimplenet_amount * -1;
    $funds_id = $wallet_obj->add_user_mature_funds_to_wallet($rimplenet_user, $rimplenet_amount, $wallet_id, $funds_note);
  }

  if (!empty($funds_note)) {
    $funds_note = "";
  }


  if ($funds_id > 1) {

    $success_message = "Funds Operation {$rimplenet_txn_type} Performed Successfully";

    add_post_meta($funds_id, 'approval_user', $current_user->ID);
    $key_appr_time = 'approval_time_by_user_' . $current_user->ID;
    add_post_meta($funds_id, $key_appr_time, time());
  } else {
    $error_message = "Error Adding funds";
  }
}

if (!empty($success_message)) {
  echo '<div class="notice notice-success">
                <p>' . $success_message . '</p>
            </div> ';
}

if (!empty($error_message)) {
  echo '<div class="notice notice-error">
                <p>' . $error_message . '</p>
            </div> ';
}


$input_width = 'width:95%';
$all_wallets = $wallet_obj->getWallets();
?>



<div class="rimplenet_admin_div" style="<?php echo $input_width; ?>">


  <?php
  $txn_loop = new WP_Query(
    array(
      'post_type' => 'rimplenettransaction',
      'post_status' => 'publish',
      'posts_per_page' => -1,
      'tax_query' => array(
        array(
          'taxonomy' => 'rimplenettransaction_type',
          'field'    => 'name',
          'terms'    => $WALLET_CAT_NAME,
        ),
      ),
    )
  );
  if ($txn_loop->have_posts()) {





  ?>

    <h2>
      <center>CREDIT / DEBIT WALLET</center>
    </h2>
    <form method="POST" style="max-width:700px; margin:auto;border:1px solid #ccc; border-radius:11px;padding: 13px;">
      <table class="form-table">
        <tbody>

          <tr>
            <th>
              <label for="rimplenet_wallet"> Select Wallet </label>
            </th>
            <td>
              <select name="rimplenet_wallet" id="rimplenet_wallet" style="width: 100%; height: 40px;" required>
                <option value=""> Select Wallet ID </option>
                <?php
                foreach ($all_wallets as $wallet) {
                  $wallet_id_op = $wallet['id'];
                  $user_wdr_bal = $wallet_obj->get_withdrawable_wallet_bal($user_id, $wallet_id_op);
                  $dec = $wallet['decimal'];
                  $symbol = $wallet['symbol'];
                  $symbol_position = $all_wallets[$wallet_id_op]['symbol_position'];

                  $disp_info = $wallet['name'];

                ?>
                  <option value="<?php echo $wallet_id_op; ?>"> <?php echo $disp_info; ?> </option>
                <?php

                }
                ?>
              </select>

            </td>
          </tr>

          <tr>
            <th>
              <label for="rimplenet_txn_type"> Transaction Type </label>
            </th>
            <td>
              <select name="rimplenet_txn_type" id="rimplenet_txn_type" style="width: 100%; height: 40px;" required>
                <option value=""> Select Transaction Type </option>
                <option value="credit"> Credit </option>
                <option value="debit"> Debit </option>
              </select>
            </td>
          </tr>

          <tr>
            <th>
              <label for="rimplenet_user"> Select User </label>
            </th>
            <td>
              <select name="rimplenet_user" id="rimplenet_user" class="form-control" style="width: 100%; height: 40px;" required>

                <option value=""> Select User </option>
                <?php
                //$args = array( 'search' => 'john' );
                $users = get_users();
                // Array of WP_User objects.
                foreach ($users as $user) {
                ?>

                  <option value="<?php echo $user->ID; ?>"> <?php echo $user->user_login . " - " . $user->user_email; ?> </option>

                <?php
                }
                ?>

              </select>
            </td>
          </tr>

          <tr>
            <th><label for="rimplenet_amount"> Amount </label></th>
            <td><input name="rimplenet_amount" id="rimplenet_amount" type="text" value="" placeholder="20" class="regular-text" required style="width:100%;max-width: 400px; height: 40px;" /></td>
          </tr>
          <tr>
            <th><label for="rimplenet_credit_debit_note"> Transaction Note </label></th>
            <td>
              <textarea id="rimplenet_credit_debit_note" name="rimplenet_credit_debit_note" rows="4" placeholder="Leave Note here" style="width:100%;max-width: 400px;"></textarea>

            </td>
          </tr>

        </tbody>
      </table>
      <input type="hidden" name="rimplenet_credit_debit_submitted" value="true" />
      <?php wp_nonce_field('rimplenet_credit_debit_nonce_field', 'rimplenet_credit_debit_nonce_field'); ?>

      <center>
        <input type="submit" name="submit" id="submit" class="button button-primary" value="APPLY ACTION">
      </center>
    </form>


    <br>
    <h2> ACTIVE WALLETS</h2>
    <table class="wp-list-table widefat fixed striped posts">

      <thead>
        <tr>
          <th> Wallet Name </th>
          <th> Description </th>
          <th> Wallet Symbol - (ID) </th>
          <th> Wallet Decimal </th>
          <th> User Balance Shortcode </th>
          <th> Include Wallet in Withdrawal Form</th>
          <th> Include Wallet in Woocommerce Currency List</th>
          <th> Actions </th>
        </tr>
      </thead>

      <tbody>

        <?php

        while ($txn_loop->have_posts()) {
          $txn_loop->the_post();
          $txn_id = get_the_ID();
          $status = get_post_status();
          $title = get_the_title();
          $content = get_the_content();


          $wallet_decimal = get_post_meta($txn_id, 'rimplenet_wallet_decimal', true);
          $wallet_symbol = get_post_meta($txn_id, 'rimplenet_wallet_symbol', true);
          $wallet_id = get_post_meta($txn_id, 'rimplenet_wallet_id', true);
          $user_balance_shortcode  = '[rimplenet-wallet action="view_balance" wallet_id="' . $wallet_id . '"]';
          $include_in_withdrawal_form = get_post_meta($txn_id, 'include_in_withdrawal_form', true);
          $include_in_woocommerce_currency_list = get_post_meta($txn_id, 'include_in_woocommerce_currency_list', true);

          $edit_wallet_link = '<a href="' . get_edit_post_link($txn_id) . '" target="_blank">Edit Wallet & Rules</a>';
          //$edit_linked_product_link = ' | <a href="'.get_edit_post_link($linked_woocommerce_product_id).'"  target="_blank">Edit Linked Product</a>'; 
          if (!empty($linked_page_id)) {
            $view_wallet_page_link = ' | <a href="' . get_permalink($linked_page_id) . '" target="_blank">View Wallet Page</a>';
          }

          //$view_linked_product_link = ' | <a href="'.get_post_permalink($linked_woocommerce_product_id).'"  target="_blank">View Linked Product</a>';

        ?>
          <tr>
            <td><?php echo $title; ?></td>
            <td><?php echo $content; ?></td>
            <td><?php echo $wallet_symbol; ?> - (<?php echo $wallet_id; ?>)</td>
            <td><?php echo $wallet_decimal; ?></td>
            <td> <code class="rimplenet_click_to_copy"> <?php echo $user_balance_shortcode; ?></code> </td>
            <td><?php echo $include_in_withdrawal_form; ?></td>
            <td><?php echo $include_in_woocommerce_currency_list; ?></td>
            <td>
              <?php echo $edit_wallet_link; ?> <?php echo $view_wallet_page_link; ?> <?php echo $edit_linked_product_link; ?> <?php echo $view_linked_product_link; ?>
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
          <th> Include Wallet in Withdrawal Form</th>
          <th> Include Wallet in Woocommerce Currency List</th>
          <th> Actions </th>
        </tr>
      </tfoot>

    </table>

  <?php

  }
  wp_reset_postdata();
  ?>


  <h2>CREATE NEW WALLET</h2>
  <form method="POST">
    <input type="hidden" name="rimplenet_wallet_submitted" value="true" />
    <?php wp_nonce_field('rimplenet_wallet_settings_nonce_field', 'rimplenet_wallet_settings_nonce_field'); ?>

    <table class="form-table">
      <tbody>

        <tr>
          <th><label for="rimplenet_wallet_name"> Wallet Name </label></th>
          <td><input name="rimplenet_wallet_name" id="rimplenet_wallet_name" type="text" value="<?php echo get_option('rimplenet_wallet_name'); ?>" placeholder="e.g United Bunny Wallet" class="regular-text" required style="<?php echo $input_width; ?>" /></td>
        </tr>
        <tr>
          <th><label for="rimplenet_wallet_desc"> Wallet Description </label></th>
          <td>
            <textarea id="rimplenet_wallet_desc" name="rimplenet_wallet_desc" placeholder="Description here" style="<?php echo $input_width; ?>"></textarea>

          </td>
        </tr>
        <tr>
          <th><label for="rimplenet_wallet_symbol"> Wallet Symbol </label></th>
          <td><input name="rimplenet_wallet_symbol" id="rimplenet_wallet_symbol" type="text" value="<?php echo get_option('rimplenet_wallet_symbol'); ?>" placeholder="e.g $" class="regular-text" required style="<?php echo $input_width; ?>" /></td>
        </tr>

        <tr>
          <th><label for="rimplenet_wallet_decimal"> Wallet Decimal </label></th>
          <td><input name="rimplenet_wallet_decimal" id="rimplenet_wallet_decimal" type="number" min="1" value="<?php echo get_option('rimplenet_wallet_decimal'); ?>" placeholder="e.g 2" class="regular-text" required style="<?php echo $input_width; ?>" /></td>
        </tr>

        <tr>
          <th><label for="rimplenet_min_withdrawal_amount"> Wallet Minimum Withdrawal Amount </label></th>
          <td><input name="rimplenet_min_withdrawal_amount" id="rimplenet_min_withdrawal_amount" type="text" value="<?php echo get_option('rimplenet_min_withdrawal_amount'); ?>" placeholder="e.g 10" class="regular-text" required style="<?php echo $input_width; ?>" /></td>
        </tr>

        <tr>
          <th><label for="rimplenet_max_withdrawal_amount"> Wallet Maximum Withdrawal Amount </label></th>
          <td><input name="rimplenet_max_withdrawal_amount" id="rimplenet_max_withdrawal_amount" type="text" value="<?php echo get_option('rimplenet_max_withdrawal_amount'); ?>" placeholder="e.g 99.99" class="regular-text" required style="<?php echo $input_width; ?>" /></td>
        </tr>

        <tr>
          <th><label for="rimplenet_wallet_id"> Wallet ID </label></th>
          <td><input name="rimplenet_wallet_id" id="rimplenet_wallet_id" type="text" value="<?php echo get_option('rimplenet_wallet_id'); ?>" placeholder="e.g usd" class="regular-text" required style="<?php echo $input_width; ?>" /></td>
        </tr>

        <tr>
          <th scope="row">Wallet Symbol Display Position</th>
          <td>
            <fieldset>
              <legend class="screen-reader-text"><span>Wallet Symbol Display Position</span></legend>
              <label><input type="radio" name="rimplenet_wallet_symbol_position" value="left" checked="checked">
                <span class="">Left - (Suitable for Fiat Wallet) </span></label> <br>

              <label><input type="radio" name="rimplenet_wallet_symbol_position" value="right">
                <span class=""> Right - (Suitable for crytocurrency wallet) </span></label> <br>

            </fieldset>
          </td>
        </tr>

        <tr>
          <th scope="row">Include in Withdrawal Form</th>
          <td>
            <fieldset>
              <legend class="screen-reader-text"><span>Include in Withdrawal Form</span></legend>
              <label><input type="radio" name="include_in_withdrawal_form" value="yes" checked="checked">
                <span class="">Yes Include - (This will show in Withdrawal form.) </span></label> <br>

              <label><input type="radio" name="include_in_withdrawal_form" value="no">
                <span class=""> No, Don't Include - (This will not show in Withdrawal form) </span></label> <br>

            </fieldset>
          </td>
        </tr>

        <tr>
          <th scope="row">Include in Woocommerce Currencies</th>
          <td>
            <fieldset>
              <legend class="screen-reader-text"><span>Include in Woocommerce Currencies</span></legend>
              <label><input type="radio" name="include_in_woocommerce_currency_list" value="yes" checked="checked">
                <span class="">Yes Include - (This will show in Woocommerce Currency List, be careful as some payment processors may not recognized it.) </span></label> <br>

              <label><input type="radio" name="include_in_woocommerce_currency_list" value="no">
                <span class=""> No, Don't Include - (This will not show in Woocommerce Currency List) </span></label> <br>

            </fieldset>
          </td>

        </tr>


        <tr>
          <th scope="row">Include as Woocommerce Product Payment Wallet</th>
          <td>
            <fieldset>
              <legend class="screen-reader-text"><span>Include as Woocommerce Product Payment Wallet</span></legend>
              <label><input type="radio" name="include_in_woocommerce_product_payment_wallet" value="yes" checked="checked">
                <span class="">Yes Include - (This will show in Woocommerce Product Payment Wallet) </span></label> <br>

              <label><input type="radio" name="include_in_woocommerce_product_payment_wallet" value="no">
                <span class=""> No, Don't Include - (This will not show as Woocommerce Product Payment Wallet) </span></label> <br>

            </fieldset>
          </td>

        </tr>


        <tr>
          <td>
            <h2>WALLET RULES</h2>
          </td>
        </tr>


        <tr>
          <th><label for="rimplenet_rules_before_wallet_withdrawal"> Rules to Achieve before User Qualifies to Withdraw from this wallet </label></th>
          <td>
            <textarea name="rimplenet_rules_before_wallet_withdrawal" id="rimplenet_rules_before_wallet_withdrawal" style="<?php echo $input_width; ?>"></textarea>

          </td>
        </tr>

        <tr>
          <th><label for="rimplenet_rules_after_wallet_withdrawal"> Rules to Apply to User after Withdrawal </label></th>
          <td>
            <textarea name="rimplenet_rules_after_wallet_withdrawal" id="rimplenet_rules_after_wallet_withdrawal" style="<?php echo $input_width; ?>"></textarea>

          </td>
        </tr>

      </tbody>
    </table>

    <p class="submit">
      <input type="submit" name="submit" id="submit" class="button button-primary" value="CREATE WALLET">
    </p>
  </form>
</div>