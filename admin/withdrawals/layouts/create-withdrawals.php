<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['rimplenet_wallet_submitted']) || wp_verify_nonce($_POST['rimplenet_wallet_settings_nonce_field'], 'rimplenet_wallet_settings_nonce_field')) {


       
        $req = [
            'note'          => sanitize_text_field($_POST['rimplenet_credit_debit_note'] ?? ''),
            'user_id'       => (int) $_POST['rimplenet_user'] ?? '',
            'wallet_id'     => sanitize_text_field(strtolower($_POST['rimplenet_wallet'])),
            'request_id'      => sanitize_text_field($_POST['request_id']) ?? rand(5, 6),
            'amount' => floatval(str_replace('-', '', $_POST['rimplenet_amount'])),
            'request_id'=> sanitize_text_field("request".$_POST['rimplenet_create_debit_nonce_field'])
        ];

        $req = [
            'request_id'           => sanitize_text_field("request".$_POST['rimplenet_create_debit_nonce_field']),
            'user_id'             => (int) $_POST['rimplenet_user'] ?? '',
            'amount_to_withdraw'         => floatval(str_replace('-', '', $_POST['rimplenet_amount'])),
            'wallet_id'     => sanitize_text_field(strtolower($_POST['rimplenet_wallet'])),
            // 'wdr_dest'           => sanitize_text_field($req['wdr_dest'] ?? ''),
            // 'wdr_dest_data'           => sanitize_text_field($req['wdr_dest_data'] ?? ''),
            'note'        => sanitize_text_field($_POST['rimplenet_withdrawal_note'] ?? '') ?? 'Withdrawal',
            'extra_data' => sanitize_text_field($_POST['extra_data'] ?? '') ?? '',
        ];
        
        $this->createWithdrawals();

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


        <form action="" method="POST" class="rimplenet-withdrawal-form" id="rimplenet-withdrawal-form" style="max-width:700px; margin:auto;border:1px solid #ccc; border-radius:11px;padding: 13px;"> 
           <div class="clearfix"></div><br>
            <div class="row">
             <div class="col-md-6">
               <label for="rimplenet_withdrawal_wallet"> <strong> Choose Wallet </strong> </label>
               <select name="rimplenet_withdrawal_wallet" id="rimplenet_withdrawal_wallet" class="rimplenet_withdrawal_wallet rimplenet-select" required="">
                   <?php
                     
                     if($wallet_id=='all'){
                        
                        foreach($all_wallets as $wallet){
                          $wallet_id_op = $wallet['id'];
                          if($wallet['include_in_withdrawal_form']=='yes'){
                           $user_wdr_bal = $wallet_obj->get_withdrawable_wallet_bal($user_id, $wallet_id_op);
                           $dec = $wallet['decimal'];
                           $symbol = $wallet['symbol'];
                           $symbol_position = $all_wallets[$wallet_id_op]['symbol_position'];
                           
                           $disp_info = getRimplenetWalletFormattedAmount($user_wdr_bal,$wallet_id_op,'wallet_name');
                           
                          ?>
                            <option value="<?php echo $wallet_id_op; ?>"> <?php echo $disp_info; ?> </option> 
                        <?php
                           }
                         }
                         
                         
                     }
                     else{
                         $withdrawal_wallets_op = explode(",",$wallet_id);
                         foreach($withdrawal_wallets_op as $wallet_id_op){
                           $wallet_id_op = trim($wallet_id_op);
                           $user_wdr_bal = $wallet_obj->get_withdrawable_wallet_bal($user_id, $wallet_id_op);
                           $dec = $all_wallets[$wallet_id_op]['decimal'];
                           $symbol = $all_wallets[$wallet_id_op]['symbol'];
                           $symbol_position = $all_wallets[$wallet_id_op]['symbol_position'];
                           
                           $disp_info = getRimplenetWalletFormattedAmount($user_wdr_bal,$wallet_id_op,'wallet_name');
                           
                         ?>
                        <option value="<?php echo $wallet_id_op; ?>"> <?php echo $disp_info; ?> </option> 
                    <?php
                         }
                     }
                     ?>
                </select>
                <!--<p style="float:right;"><small>SWAP FEE ~ 0.009 ETH</small></p>-->
             </div>
             
           
             
             <div class="col-md-6">
                  <label for="rimplenet_amount_to_withdraw"> <strong> <?php echo $wdr_amt_text_label; ?> </strong> </label>
                  <input name="rimplenet_amount_to_withdraw" id="rimplenet_amount_to_withdraw" class="rimplenet_amount_to_withdraw rimplenet-input" placeholder="<?php echo $wdr_amt_text_placeholder; ?>" type="text" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>"  value="" required="">       
                  <!--<p class="mb-0 text-right">1 USD ~ 0.0001 ETH <a href="#">Expected rate - No extra
                    fees</a></p>-->
                  <?php 
                   do_action('rimplenet_withdrawal_form_before_withdrawal_destination',$wallet_id, $user_id,$title,$button_text);  
                   $placeholder_text = apply_filters( 'rimplenet_withdrawal_field_placeholder', $wdr_dest_text_placeholder, $wallet_id,$user_id, $title,$button_text);
                  ?> 
             </div>
            
            <div class="clearfix"></div><br> 
            
              <?php if($wdr_dest=="bank"){ ?>
                <div class="col-md-6">
                  <label for="rimplenet_withdrawal_bank"> <strong> Bank </strong> </label>
                  <!--<select name="rimplenet_withdrawal_bank" id="rimplenet_withdrawal_bank" class="rimplenet_withdrawal_bank rimplenet-select" required="">
                   <option value="Other"> Other </option> 
                  </select>
                  -->
                  <input type="text" name="rimplenet_withdrawal_bank" id="rimplenet_withdrawal_bank" class="rimplenet_withdrawal_bank rimplenet-input" placeholder="Bank Name" value="" required="">       
                  
                  <!--<p style="float:right;"><small>Bottom Text ~ 0.009 ETH</small></p>-->
                </div>

             <div class="col-md-6">
                  <label for="rimplenet_withdrawal_account_number"> <strong> Account Number </strong> </label>
                  <input name="rimplenet_withdrawal_account_number" id="rimplenet_withdrawal_account_number" class="rimplenet_withdrawal_account_number rimplenet-input" placeholder="<?php echo $wdr_amt_text_placeholder; ?>" type="text" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>"  value="" required="">       
                  <!--<p class="mb-0 text-right">1 USD ~ 0.0001 ETH <a href="#">Expected rate - No extra
                    fees</a></p>-->
             </div>
             
             <div class="col-md-12">
                  <label for="rimplenet_withdrawal_account_name"> <strong> Account Name </strong> </label>
                  <input name="rimplenet_withdrawal_account_name" id="rimplenet_withdrawal_account_name" class="rimplenet_withdrawal_account_name rimplenet-input" placeholder="John Doe" type="text" value="" required="">       
                  <!--<p class="mb-0 text-right">1 USD ~ 0.0001 ETH <a href="#">Expected rate - No extra
                    fees</a></p>-->
             </div>
             
             <?php }
               elseif($wdr_dest=="crypto_address"){
             ?>
             
              
             <div class="col-md-12">
                  <label for="rimplenet_withdrawal_crypto_address"> <strong> Crypto Address </strong> </label>
                  <input name="rimplenet_withdrawal_crypto_address" id="rimplenet_withdrawal_crypto_address" class="rimplenet_withdrawal_crypto_address rimplenet-input" placeholder="<?php echo $crypto_address_placeholder; ?>" type="text" value="" required="">       
                  <!--<p class="mb-0 text-right">1 USD ~ 0.0001 ETH <a href="#">Expected rate - No extra
                    fees</a></p>-->
             </div>
             <?php }
               else{
             ?>
             
            
            <div class="clearfix"></div><br> 
            <div class="col-md-12">
             <label for="rimplenet_withdrawal_destination"><strong> <?php echo $wdr_dest_text_label; ?></strong> </label>
             <textarea name="rimplenet_withdrawal_destination" id="rimplenet_withdrawal_destination" class="rimplenet_withdrawal_destination rimplenet-textarea" rows="3" placeholder="<?php echo $wdr_dest_text_placeholder; ?>" required></textarea>
            </div>
            <?php } ?>
            <?php do_action('rimplenet_withdrawal_form_after_withdrawal_destination', $wallet_id, $user_id, $title,$button_text);  ?>
            
            <div class="clearfix"></div><br> 
            
            <div class="col-md-12">
             <label for="rimplenet_withdrawal_note"><strong> <?php echo $wdr_note_text_label; ?></strong> </label>
             <input type="text" name="rimplenet_withdrawal_note" id="rimplenet_withdrawal_note" class="rimplenet_withdrawal_note rimplenet-input" placeholder="<?php echo $wdr_note_text_placeholder; ?>" maxlength="30" value=""> 
            </div>
            <?php do_action('rimplenet_withdrawal_form_after_withdrawal_note', $wallet_id, $user_id, $title,$button_text);  ?>
            
             
            <div class="clearfix"></div><br> 
             <div class="col-md-12">
                <?php wp_nonce_field( 'rimplenet_wallet_withdrawal_nonce', 'rimplenet_wallet_withdrawal_nonce' ); ?>
                <div class="clearfix"></div>
                <br>
                <center>
                    
                  <input name="request_id" type="hidden" value="<?php echo time(); ?>" required=""> 
                  <input class="rimplenet-button rimplenet_submit_withdrawal_form" id="rimplenet_submit_withdrawal_form" value="<?php echo $button_text; ?>" type="submit" >
                </center>
             </div>
            
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