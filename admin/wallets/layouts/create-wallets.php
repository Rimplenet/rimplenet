<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['rimplenet_wallet_submitted']) || wp_verify_nonce($_POST['rimplenet_wallet_settings_nonce_field'], 'rimplenet_wallet_settings_nonce_field')) {


        $req = [
            'wallet_name'           => sanitize_text_field($_POST['rimplenet_wallet_name']),
            'wallet_id'             => sanitize_text_field($_POST['rimplenet_wallet_id']),
            'wallet_symbol'         => sanitize_text_field($_POST['rimplenet_wallet_symbol']),
            'wallet_symbol_pos'     => sanitize_text_field($_POST['rimplenet_wallet_symbol_pos'] ?? 'left'),
            'wallet_note'           => sanitize_text_field($_POST['rimplenet_wallet_desc'] ?? $_POST['rimplenet_wallet_name']),
            'wallet_type'           => sanitize_text_field($_POST['rimplenet_wallet_type']),
            'wallet_decimal'        => intval($_POST['rimplenet_wallet_decimal']) ?? 2,
            'max_withdrawal_amount' => intval($_POST['rimplenet_max_withdrawal_amount'] == "" ? CreateWallet::MAX_AMOUNT : $_POST['rimplenet_max_withdrawal_amount']) ?? CreateWallet::MAX_AMOUNT,
            'min_withdrawal_amount' => intval($_POST['rimplenet_min_withdrawal_amount'] == "" ? CreateWallet::MIN_AMOUNT : $_POST['rimplenet_min_withdrawal_amount']) ?? CreateWallet::MIN_AMOUNT,
            // 'inc_i_w_cl'            => $_POST['rimplenet_inc_in_woocmrce_curr_list'] ?? false,
            // // 'e_a_w_p'               => $_POST['rimplenet_enable_as_woocmrce_pymt_wlt'] ?? false,
            // // 'r_b_b_w'               => sanitize_text_field($_POST['rimplenet_rules_before_withdrawal'] ?? ''),
            // // 'r_a_b_w'               => sanitize_text_field($_POST['rimplenet_rules_after_withdrawal'] ?? '')
        ];

        // var_dump($req);
        // die;




        $wallets = new RimplenetCreateWallets();

        // var_dump($req);
        // die;
        // var_dump($wallets->createWallet($req));
        // die;
        $wallets->req=$req;
        $wallets->createWallet();
        if ($wallets::$response['status']!=false) {
            echo '<div class="updated">
               <p>Your Wallet have been created successfully</p>
           </div> ';
        } else {
            // var_dump($wallets::$response);
            foreach ($wallets::$response['error'] as $key => $value) {
                echo "<div class='error'>
               <p>".$wallets::$response['message'].": ".$value."</p>
           </div> ";
            }

        // for ($i=0; $i < count($wallets::$response['error']); $i++) { 
        //     echo "<div class='error'>
        //        <p>".$wallets::$response['message'].": ".$wallets::$response['error'][$i]."</p>
        //    </div> ";
        // }
        }
    }
}
?>





<style>
    .user-card {
        width: 97%;
        display: flex;
        margin-top: 20px;
        margin: 0 8px 16px;
        background-color: #fff;
        border: 1px solid #dcdcde;
        box-sizing: border-box;
    }

    .form-container {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    form {
        width: 100%;
    }

    .control {
        display: flex;
        flex-direction: column;
        width: 70%;
        margin: 0 auto;
        margin-top: 8px;
    }

    label {
        margin: 5px 0;
        font-weight: 600;
        color: #787878;
    }

    .form-input {
        padding: 4px !important;
        border: 2px solid #e1e1e1 !important;
    }

    .rt {
        background: #f8f8f8;
    }

    .form-container img {
        width: 80%;
    }

    .error {
        border-color: #c58383 !important;
    }

    .success {
        border-color: #5ce68f !important;
    }
</style>

<?php

$dir = plugin_dir_url(dirname(__FILE__));
?>

<div class="user-card">
    <div class="form-container">
        <img src="<?= $dir ?>/assets/img/wallet-concept-illustration_114360-1985.webp" alt="">
    </div>
    <div class="form-container rt">

        <!--         
    <form method="POST">
            <h2>CREATE NEW WALLET</h2> <br>
            <input type="hidden" name="rimplenet_wallet_submitted" value="true" />
            <?php // wp_nonce_field('rimplenet_wallet_settings_nonce_field', 'rimplenet_wallet_settings_nonce_field'); 
            ?>

            <table class="form-table">
                <tbody>

                    <tr>
                        <th><label for="rimplenet_wallet_name"> Wallet Name </label></th>
                        <td><input name="rimplenet_wallet_name" id="rimplenet_wallet_name" style="width: 100%;max-width: 400px; height: 40px;" type="text" value="<?php echo get_option('rimplenet_wallet_name'); ?>" placeholder="e.g United Bunny Wallet" class="regular-text" required style="<?php echo $input_width; ?>" /></td>
                    </tr>
                    <tr>
                        <th><label for="rimplenet_wallet_desc"> Wallet Description </label></th>
                        <td>
                            <textarea id="rimplenet_wallet_desc" style="width: 100%;max-width: 400px; height: 40px;" name="rimplenet_wallet_desc" placeholder="Description here" style="<?php echo $input_width; ?>"></textarea>

                        </td>
                    </tr>
                    <tr>
                        <th><label for="rimplenet_wallet_symbol"> Wallet Symbol <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Wallet Symbol of the Wallet e.g $ for Dollars, ₦ for Naira,  ₿  for Bitcoin or maybe ETH for Ethereum"></span></label></th>
                        <td><input name="rimplenet_wallet_symbol" id="rimplenet_wallet_symbol" style="width: 100%;max-width: 400px; height: 40px;" type="text" value="<?php echo get_option('rimplenet_wallet_symbol'); ?>" placeholder="e.g $" class="regular-text" required style="<?php echo $input_width; ?>" /></td>
                    </tr>

                    <tr>
                        <th><label for="rimplenet_wallet_decimal"> Wallet Decimal <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Wallet Decimal of the Wallet, it sometimes 2 is for fiat currecny wallet &amp; 6 or more for cryptocurrency wallet"></span> </label></th>
                        <td><input name="rimplenet_wallet_decimal" id="rimplenet_wallet_decimal" style="width: 100%;max-width: 400px; height: 40px;" type="number" min="1" value="<?php echo get_option('rimplenet_wallet_decimal'); ?>" placeholder="e.g 2" class="regular-text" style="<?php echo $input_width; ?>" /></td>
                    </tr>

                    <tr>
                        <th><label for="rimplenet_wallet_decimal"> Wallet Type </label></th>
                        <td><select name="rimplenet_wallet_type" id="rimplenet_wallet_type" style="width: 100%;max-width: 400px; height: 40px;" required="">
                                <option value=""> Select Wallet Type </option>
                                <option value="fiat" selected=""> Fiat Currency Wallet </option>
                                <option value="crypto"> Cryptocurrency Wallet </option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th><label for="rimplenet_min_withdrawal_amount"> Wallet Minimum Withdrawal Amount </label></th>
                        <td><input name="rimplenet_min_withdrawal_amount" id="rimplenet_min_withdrawal_amount" style="width: 100%;max-width: 400px; height: 40px;" type="text" value="<?php echo get_option('rimplenet_min_withdrawal_amount'); ?>" placeholder="e.g 10" class="regular-text" style="<?php echo $input_width; ?>" /></td>
                    </tr>

                    <tr>
                        <th><label for="rimplenet_max_withdrawal_amount"> Wallet Maximum Withdrawal Amount </label></th>
                        <td><input name="rimplenet_max_withdrawal_amount" id="rimplenet_max_withdrawal_amount" style="width: 100%;max-width: 400px; height: 40px;" type="text" value="<?php echo get_option('rimplenet_max_withdrawal_amount'); ?>" placeholder="e.g 99.99" class="regular-text" style="<?php echo $input_width; ?>" /></td>
                    </tr>

                    <tr>
                        <th><label for="rimplenet_wallet_id"> Wallet ID <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Wallet ID should be unique for each of your created wallet, wallet id should be lowercase alphabets and underscore (blank space is not allowed) e.g btc for United State Dollars, ngn for Nigerian Naira , btc for Bitcoin, you can as well use savings_wallet"></span> </label></th>
                        <td><input name="rimplenet_wallet_id" id="rimplenet_wallet_id" style="width: 100%;max-width: 400px; height: 40px;" type="text" value="<?php echo get_option('rimplenet_wallet_id'); ?>" placeholder="e.g usd" class="regular-text" required style="<?php echo $input_width; ?>" /></td>
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
        </form> -->






        <style>
            #regForm {
                /* background-color: #ffffff; */
                /* margin: 100px auto; */
                /* font-family: Raleway; */
                /* padding: 40px; */
                /* width: 70%; */
                min-width: 300px;
            }

            h1 {
                text-align: center;
            }

            /* input {
                padding: 10px;
                width: 100%;
                font-size: 17px;
                font-family: Raleway;
                border: 1px solid #aaaaaa;
            } */

            /* Mark input boxes that gets an error on validation: */
            input.invalid {
                background-color: #ffdddd;
            }

            /* Hide all steps by default: */
            .tab {
                display: none;
            }

            button {
                background-color: rgb(0 126 255);
                color: #ffffff;
                border: none;
                padding: 10px 20px;
                font-size: 17px;
                /* font-family: Raleway; */
                cursor: pointer;
                /* padding-right: 50%; */
            }

            button:hover {
                opacity: 0.8;
            }

            #prevBtn {
                background-color: #bbbbbb;
            }

            /* Make circles that indicate the steps of the form: */
            .step {
                height: 15px;
                width: 15px;
                margin: 0 2px;
                /* background-color: #bbbbbb; */
                background-color: rgb(0 126 255);
                border: none;
                border-radius: 50%;
                display: inline-block;
                opacity: 0.5;
            }

            .step.active {
                opacity: 1;
            }

            /* Mark the steps that are finished and valid: */
            .step.finish {
                background-color: #04AA6D;
            }

            .required-red{
                color: red; 
                font-size: 18px;
            }
        </style>


        <form id="regForm" method="POST">
            <!-- One "tab" for each step in the form: -->
            <div class="tab">
                <h2>CREATE NEW WALLET</h2> <br>
                <input type="hidden" name="rimplenet_wallet_submitted" value="true" />
                <?php wp_nonce_field('rimplenet_wallet_settings_nonce_field', 'rimplenet_wallet_settings_nonce_field'); ?>
                <table class="form-table">
                    <tbody>

                        <tr>
                            <th><label for="rimplenet_wallet_name required"> Wallet Name  <sup class="required-red"><strong>*</strong></sup></label></th>
                            <td><input name="rimplenet_wallet_name" id="rimplenet_wallet_name" style="width: 100%;max-width: 400px; height: 40px;" type="text" value="<?php echo get_option('rimplenet_wallet_name'); ?>" placeholder="e.g United Bunny Wallet" class="regular-text" required style="<?php echo $input_width; ?>" /></td>
                        </tr>
                        <tr>
                            <th><label for="rimplenet_wallet_desc"> Wallet Description <sup class="required-red"><strong>*</strong></sup> </label></th>
                            <td>
                                <textarea id="rimplenet_wallet_desc" style="width: 100%;max-width: 400px; height: 40px;" name="rimplenet_wallet_desc" placeholder="Description here" style="<?php echo $input_width; ?>"></textarea>

                            </td>
                        </tr>
                        <tr>
                            <th><label for="rimplenet_wallet_symbol"> Wallet Symbol <sup class="required-red"><strong>*</strong></sup><span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Wallet Symbol of the Wallet e.g $ for Dollars, ₦ for Naira,  ₿  for Bitcoin or maybe ETH for Ethereum"></span></label></th>
                            <td><input name="rimplenet_wallet_symbol" id="rimplenet_wallet_symbol" style="width: 100%;max-width: 400px; height: 40px;" type="text" value="<?php echo get_option('rimplenet_wallet_symbol'); ?>" placeholder="e.g $" class="regular-text" required style="<?php echo $input_width; ?>" /></td>
                        </tr>

                        <tr>
                            <th><label for="rimplenet_wallet_decimal"> Wallet Decimal <sup class="required-red"><strong>*</strong></sup><span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Wallet Decimal of the Wallet, it sometimes 2 is for fiat currecny wallet &amp; 6 or more for cryptocurrency wallet"></span> </label></th>
                            <td><input name="rimplenet_wallet_decimal" id="rimplenet_wallet_decimal" style="width: 100%;max-width: 400px; height: 40px;" type="number" min="1" value="<?php echo get_option('rimplenet_wallet_decimal'); ?>" placeholder="e.g 2" class="regular-text" style="<?php echo $input_width; ?>" /></td>
                        </tr>

                        <tr>
                            <th><label for="rimplenet_wallet_decimal"> Wallet Type <sup class="required-red"><strong>*</strong></sup> </label></th>
                            <td><select name="rimplenet_wallet_type" id="rimplenet_wallet_type" style="width: 100%;max-width: 400px; height: 40px;" required="">
                                    <option value=""> Select Wallet Type </option>
                                    <option value="fiat" selected=""> Fiat Currency Wallet </option>
                                    <option value="crypto"> Cryptocurrency Wallet </option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <th><label for="rimplenet_min_withdrawal_amount"> Wallet Minimum Withdrawal Amount </label></th>
                            <td><input name="rimplenet_min_withdrawal_amount" id="rimplenet_min_withdrawal_amount" style="width: 100%;max-width: 400px; height: 40px;" type="text" value="<?php echo get_option('rimplenet_min_withdrawal_amount'); ?>" placeholder="e.g 10" class="regular-text" style="<?php echo $input_width; ?>" /></td>
                        </tr>

                        <tr>
                            <th><label for="rimplenet_max_withdrawal_amount"> Wallet Maximum Withdrawal Amount </label></th>
                            <td><input name="rimplenet_max_withdrawal_amount" id="rimplenet_max_withdrawal_amount" style="width: 100%;max-width: 400px; height: 40px;" type="text" value="<?php echo get_option('rimplenet_max_withdrawal_amount'); ?>" placeholder="e.g 99.99" class="regular-text" style="<?php echo $input_width; ?>" /></td>
                        </tr>

                        <tr>
                            <th><label for="rimplenet_wallet_id"> Wallet ID <sup class="required-red"><strong>*</strong></sup><span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Wallet ID should be unique for each of your created wallet, wallet id should be lowercase alphabets and underscore (blank space is not allowed) e.g btc for United State Dollars, ngn for Nigerian Naira , btc for Bitcoin, you can as well use savings_wallet"></span> </label></th>
                            <td><input name="rimplenet_wallet_id" id="rimplenet_wallet_id" style="width: 100%;max-width: 400px; height: 40px;" type="text" value="<?php echo get_option('rimplenet_wallet_id'); ?>" placeholder="e.g usd" class="regular-text" required style="<?php echo $input_width; ?>" /></td>
                        </tr>


                    </tbody>
                </table>
            </div>
           
            <div class="tab">
                <table class="form-table">
                    <tbody>

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

                       
                    </tbody>
                </table>
            </div>
           


            <div style="overflow:auto;">
                <div style="
                /* float:right; */
                ">
                    <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
                </div>
            </div>
            <!-- Circles which indicates the steps of the form: -->
            <div style="text-align:center;margin-top:40px;">
                <span class="step"></span>
                <span class="step"></span>
                <!-- <span class="step"></span> -->
            </div>
        </form>
    </div>
</div>



<script>
    var currentTab = 0; // Current tab is set to be the first tab (0)
    showTab(currentTab); // Display the current tab

    function showTab(n) {
        // This function will display the specified tab of the form...
        var x = document.getElementsByClassName("tab");
        x[n].style.display = "block";
        //... and fix the Previous/Next buttons:
        if (n == 0) {
            document.getElementById("prevBtn").style.display = "none";
        } else {
            document.getElementById("prevBtn").style.display = "inline";
        }
        if (n == (x.length - 1)) {
            document.getElementById("nextBtn").innerHTML = "Submit";
        } else {
            document.getElementById("nextBtn").innerHTML = "Next";
        }
        //... and run a function that will display the correct step indicator:
        fixStepIndicator(n)
    }

    function nextPrev(n) {
        // This function will figure out which tab to display
        var x = document.getElementsByClassName("tab");
        // Exit the function if any field in the current tab is invalid:
        if (n == 1 && !validateForm()) return false;
        // Hide the current tab:
        x[currentTab].style.display = "none";
        // Increase or decrease the current tab by 1:
        currentTab = currentTab + n;
        // if you have reached the end of the form...
        if (currentTab >= x.length) {
            // ... the form gets submitted:
            document.getElementById("regForm").submit();
            return false;
        }
        // Otherwise, display the correct tab:
        showTab(currentTab);
    }

    function validateForm() {
        // This function deals with validation of the form fields
        var x, y, i, valid = true;
        x = document.getElementsByClassName("tab");
        y = x[currentTab].getElementsByTagName("input");
        // A loop that checks every input field in the current tab:
        for (i = 0; i < y.length; i++) {
            // If a field is empty...
            if (y[i].value == "") {
                // add an "invalid" class to the field:
                y[i].className += " invalid";
                // and set the current valid status to false
                valid = false;
            }
        }
        // If the valid status is true, mark the step as finished and valid:
        if (valid) {
            document.getElementsByClassName("step")[currentTab].className += " finish";
        }
        return valid; // return the valid status
    }

    function fixStepIndicator(n) {
        // This function removes the "active" class of all steps...
        var i, x = document.getElementsByClassName("step");
        for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
        }
        //... and adds the "active" class on the current step:
        x[n].className += " active";
    }
</script>