<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['rimplenet_wallet_withdrawal_nonce']) || wp_verify_nonce($_POST['rimplenet_wallet_withdrawal_nonce'], 'rimplenet_wallet_withdrawal_nonce')) {



        // $req = [
        //     'note'          => sanitize_text_field($_POST['rimplenet_credit_debit_note'] ?? ''),
        //     'user_id'       => (int) $_POST['rimplenet_user'] ?? '',
        //     'wallet_id'     => sanitize_text_field(strtolower($_POST['rimplenet_wallet'])),
        //     'request_id'      => sanitize_text_field($_POST['request_id']) ?? rand(5, 6),
        //     'amount' => floatval(str_replace('-', '', $_POST['rimplenet_amount'])),
        //     'request_id' => sanitize_text_field("request" . $_POST['rimplenet_create_debit_nonce_field'])
        // ];

        $req = [
            'request_id'           => sanitize_text_field("request" . $_POST['rimplenet_create_debit_nonce_field']),
            'user_id'             => (int) $_POST['rimplenet_user'] ?? '',
            'amount_to_withdraw'         => floatval(str_replace('-', '', $_POST['rimplenet_amount_to_withdraw'])),
            'wallet_id'     => sanitize_text_field(strtolower($_POST['rimplenet_wallet'])),
            'wdr_dest'           => sanitize_text_field($_POST['rimplenet_withdrawal_destination'] ?? ''),
            'wdr_dest_data'           => sanitize_text_field($req['wdr_dest_data'] ?? 'option'),
            'note'        => sanitize_text_field($_POST['rimplenet_withdrawal_note'] ?? '') ?? 'Withdrawal',
            'extra_data' => sanitize_text_field($_POST['extra_data'] ?? '') ?? '',
        ];

    



        $wallets = new RimplenetCreateWithdrawals();
        $wallets->req = $req;
        $wallets->createWithdrawals();
        if ($wallets::$response['status'] != false) {
            echo '<div class="updated">
               <p>Your Withdrawal Request has been created successfully</p>
           </div> ';
        } else {
            // var_dump($wallets::$response);
            foreach ($wallets::$response['error'] as $key => $value) {
                echo "<div class='error'>
               <p>" . $wallets::$response['message'] . ": " . $value . "</p>
           </div> ";
            }
        }
    }
}

// $wdr_dest = $_GET['rimplenet_withdrawal_destination'] ?? 'bank';

$wallet_obj = new RimplenetGetWallets();
$wallet_obj->getWallets();
$all_wallets=$wallet_obj::$response['data'];
?>
<style>
    .user-card {
        /* width: 97%; */
        display: flex;
        margin-top: 20px;
        /* margin: 0 8px 16px; */
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
<!-- rimplenet-bs5 -->

<div class="rimplenet-bs5 col-md-12">
    <div class="user-card mx-auto col-md-12">


        <div class="form-container">
            <img src="<?= $dir ?>/assets/img/wallet-concept-illustration_114360-1985.webp" alt="">
        </div>
        <div class="form-container rt">
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

                .required-red {
                    color: red;
                    font-size: 18px;
                }
            </style>


            <form action="" method="POST" class="rimplenet-withdrawal-form" id="rimplenet-withdrawal-form" style="max-width:700px; margin:auto;border:1px solid #ccc; border-radius:11px;padding: 13px;">
                <div class="clearfix"></div><br>
                <input type="hidden" name="rimplenet_user" value="<?= get_current_user_id(); ?>">
                <tr>
                    <th>
                        <label for="rimplenet_wallet"> Select Wallet </label> <br>
                    </th>
                    <td>
                        <select name="rimplenet_wallet" id="rimplenet_wallet" style="width: 100% !important; height: 40px;" required="">
                            <option value=""> Select Wallet ID </option>
                            <?php
                            foreach ($all_wallets as $wallet) {
                                $wallet_id_op = $wallet['post_id'] ?? '';
                                $disp_info = $wallet['wallet_name'];
                                $walletID = $wallet['wallet_id'];

                            ?>
                                <option value="<?php echo $walletID; ?>"> <?php echo $disp_info; ?> - <?= $walletID ?></option>
                            <?php

                            }
                            ?>
                        </select>

                    </td>
                </tr>
                <div class="clearfix"></div><br>




                <tr>
                    <th>
                        <label for="rimplenet_amount_to_withdraw"> <strong> Amount To Withdraw </strong> </label>
                    </th>
                    <td>
                        <input name="rimplenet_amount_to_withdraw" id="rimplenet_amount_to_withdraw" class="rimplenet_amount_to_withdraw rimplenet-input" placeholder="e.g 1000 , no space, comma, currency sign or special character" type="text" min="<?php echo $min_price ?? 0; ?>" max="<?php echo $max_price ?? 999999; ?>" value="" style="width: 100%; height: 40px;" required="">
                        <!--<p class="mb-0 text-right">1 USD ~ 0.0001 ETH <a href="#">Expected rate - No extra fees</a></p>-->
                    </td>
                </tr>
                <div class="clearfix"></div><br>



                <?php if (isset($wdr_dest) && $wdr_dest == "bank") { ?>
                    <tr>
                        <th>
                            <label for="rimplenet_withdrawal_bank"> <strong> Bank </strong> </label>
                        </th>
                        <td>
                            <input type="text" name="rimplenet_withdrawal_bank" id="rimplenet_withdrawal_bank" class="rimplenet_withdrawal_bank rimplenet-input" placeholder="Bank Name" value="" required="" style="width: 100%; height: 40px;">
                        </td>
                    </tr>
                    <div class="clearfix"></div><br>

                    <tr>
                        <th>
                            <label for="rimplenet_withdrawal_account_number">Account Number</label>
                        </th>
                        <td>
                            <input name="rimplenet_withdrawal_account_number" id="rimplenet_withdrawal_account_number" class="rimplenet_withdrawal_account_number rimplenet-input" placeholder="<?php echo $wdr_amt_text_placeholder; ?>" type="text" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" value="" required="" style="width: 100%; height: 40px;">
                            <!--<p class="mb-0 text-right">1 USD ~ 0.0001 ETH <a href="#">Expected rate - No extra fees</a></p>-->
                        </td>
                    </tr>
                    <div class="clearfix"></div><br>

                    <tr>
                        <th>
                            <label for="rimplenet_withdrawal_account_name"> <strong> Account Name </strong> </label>
                        </th>
                        <td>
                            <input name="rimplenet_withdrawal_account_name" id="rimplenet_withdrawal_account_name" class="rimplenet_withdrawal_account_name rimplenet-input" placeholder="John Doe" type="text" value="" required="" style="width: 100%; height: 40px;">
                            <!--<p class="mb-0 text-right">1 USD ~ 0.0001 ETH <a href="#">Expected rate - No extra fees</a></p>-->
                        </td><br>
                    </tr>

                <?php } elseif (isset($wdr_dest) && $wdr_dest == "crypto_address") {
                ?>


                    <tr>
                        <th>
                            <label for="rimplenet_withdrawal_crypto_address"> <strong> Crypto Address </strong> </label>
                        </th>
                        <td>
                            <input name="rimplenet_withdrawal_crypto_address" id="rimplenet_withdrawal_crypto_address" class="rimplenet_withdrawal_crypto_address rimplenet-input" placeholder="<?php echo $crypto_address_placeholder; ?>" type="text" value="" required="">
                            <!--<p class="mb-0 text-right">1 USD ~ 0.0001 ETH <a href="#">Expected rate - No extra
                        fees</a></p>-->
                        </td>
                    </tr> <br>
                <?php } else {
                ?>
                    <tr class="col-md-12">
                        <label for="rimplenet_withdrawal_destination"><strong>Withdrawal Destination</strong> </label>
                        <textarea name="rimplenet_withdrawal_destination" id="rimplenet_withdrawal_destination" class="rimplenet_withdrawal_destination rimplenet-textarea" rows="3" placeholder="Insert your Withdrawal Destination e.g Account Name or Crypto Address" style="width: 100%; height: 40px;" required></textarea>
                    </tr>
                <?php } ?>
                <div class="clearfix"></div><br>



                <tr>
                    <label for="rimplenet_withdrawal_note"><strong> Withdrawal Note (optional)</strong> </label>
                    <input type="text" name="rimplenet_withdrawal_note" id="rimplenet_withdrawal_note" class="rimplenet_withdrawal_note rimplenet-input" placeholder="<?php echo $wdr_note_text_placeholder; ?>" maxlength="30" value="" style="width: 100%; height: 40px;">
                </tr>



                <tr>
                    <?php wp_nonce_field('rimplenet_wallet_withdrawal_nonce', 'rimplenet_wallet_withdrawal_nonce'); ?>
                    <div class="clearfix"></div>
                    <br>
                    <center>

                        <input name="request_id" type="hidden" value="<?php echo time(); ?>" required="">
                        <input class="button button-primary p-1 col-md-6 rimplenet_submit_withdrawal_form" id="rimplenet_submit_withdrawal_form" value="Withdraw" type="submit">
                    </center>
                </tr>


            </form>
        </div>
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