<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['rimplenet_create_credit_submitted']) || wp_verify_nonce($_POST['rimplenet_create_credit_nonce_field'], 'rimplenet_create_credit_nonce_field')) {

        $req = [
            'note'          => sanitize_text_field($_POST['rimplenet_credit_debit_note'] ?? ''),
            'user_id'       => (int) $_POST['rimplenet_user'],
            'wallet_id'     => sanitize_text_field(strtolower($_POST['rimplenet_wallet'])),
            'request_id'      => sanitize_text_field($_POST['request_id']) ?? rand(5, 6),
            'amount_to_add' => floatval(str_replace('-', '', $_POST['rimplenet_amount'])),
        ];
        $wallets = new RimplenetCreateCredits();

        // var_dump($wallets->createCredits($req));
        // die;
        if ($wallets->createCredits($req) && empty($wallets->response['error'])) {
            echo '<div class="updated">
               <p>Credit has been created successfully</p>
           </div> ';
        } else {
            var_dump($wallets->response['error']);
        }
    }
}
?>


<?php 


$wallet_obj = new RimplenetGetWallets();
// $wallet_obj->createQuery();
$wallet_obj->getWallets();
$all_wallets=$wallet_obj->response['data'];

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
        </style>


        <form method="POST" style="max-width:700px; margin:auto;border:1px solid #ccc; border-radius:11px;padding: 13px;">
            <table class="form-table">
                <tbody>

                    <tr>
                        <th>
                            <label for="rimplenet_wallet"> Select Wallet </label>
                        </th>
                        <td>
                            <select name="rimplenet_wallet" id="rimplenet_wallet" style="width: 100%; height: 40px;" required="">
                                <option value=""> Select Wallet ID </option>
                                 <?php
                                    foreach($all_wallets as $wallet){
                                        $wallet_id_op = $wallet['post_id'] ?? '';
                                        $disp_info = $wallet['wallet_name'];
                               
                                ?>
                                <option value="<?php echo $wallet_id_op; ?>" > <?php echo $disp_info; ?> </option> 
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
                            <select name="rimplenet_txn_type" id="rimplenet_txn_type" style="width: 100%; height: 40px;" required="">
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
                            <select name="rimplenet_user" id="rimplenet_user" class="form-control" style="width: 100%; height: 40px;" required="">

                                <option value=""> Select User </option>

                               

                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th><label for="rimplenet_amount"> Amount </label></th>
                        <td><input name="rimplenet_amount" id="rimplenet_amount" type="text" value="" placeholder="20" class="regular-text" required="" style="width:100%;max-width: 400px; height: 40px;"></td>
                    </tr>
                    <tr>
                        <th><label for="rimplenet_credit_debit_note"> Transaction Note </label></th>
                        <td>
                            <textarea id="rimplenet_credit_debit_note" name="rimplenet_credit_debit_note" rows="4" placeholder="Leave Note here" style="width:100%;max-width: 400px;"></textarea>

                        </td>
                    </tr>

                </tbody>
            </table>
            <input type="hidden" name="rimplenet_create_credit_submitted" value="true" />
            <?php wp_nonce_field('rimplenet_create_credit_nonce_field', 'rimplenet_create_credit_nonce_field'); ?>
            <!-- <input type="hidden" name="rimplenet_credit_debit_submitted" value="true"> -->
            <!-- <input type="hidden" id="rimplenet_create_credit_nonce_field" name="rimplenet_create_credit_nonce_field" value="20d5c54801"><input type="hidden" name="_wp_http_referer" value="/testrimplenet/wp-admin/edit.php?post_type=rimplenettransaction&amp;page=settings_wallets"> -->
            <center>
                <input type="submit" name="submit" id="submit" class="button button-primary" value="APPLY ACTION">
            </center>
        </form>
    </div>
</div>