<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['rimplenet_create_debit_submitted']) || wp_verify_nonce($_POST['rimplenet_create_debit_nonce_field'], 'rimplenet_create_debit_nonce_field')) {

        $req = [
            'note'          => sanitize_text_field($_POST['rimplenet_credit_debit_note'] ?? ''),
            'user_id'       => (int) $_POST['rimplenet_user'] ?? '',
            'wallet_id'     => sanitize_text_field(strtolower($_POST['rimplenet_wallet'])),
            // 'request_id'      => sanitize_text_field($_POST['request_id']) ?? rand(5, 6),
            'amount' => floatval(str_replace('-', '', $_POST['rimplenet_amount'])),
            // 'request_id'=> sanitize_text_field("request".$_POST['rimplenet_create_debit_nonce_field'])
            'request_id'=> sanitize_text_field("admin_debit_".$_POST['rimplenet_user']."_".date('Y_m_d_H_i_s'))
        ];
        $wallets = new RimplenetCreateDebits();

        // var_dump($req);
        // die;
        // var_dump($wallets->createDebits($req));
        // die;
        $wallets->createDebits($req);
        if (empty($wallets::$response['error'])) {
            echo '<div class="updated">
               <p>Debit has been created successfully</p>
           </div> ';
        } else {
            // var_dump($wallets::$response['error']);
            foreach ($wallets::$response['error'] as $key => $value) {
                echo "<div class='error'>
               <p>".$wallets::$response['message'].": ".$value."</p>
           </div> ";
            }
        }
    }elseif (isset($_POST['rimplenet_search_user'])) {
        $users = new WP_User_Query( array(
            'search'         => '*'.esc_attr( $_POST['rimplenet_search_user'] ).'*',
            'search_columns' => array(
                'user_login',
                'user_nicename',
                'user_email',
                'user_url',
            ),
        ) );
        $users_found = $users->get_results();
    }
}
?>


<?php 


$wallet_obj = new RimplenetGetWallets();
// $wallet_obj->createQuery();
$wallet_obj->getWallets();
// $all_wallets=$wallet_obj->response['data'];
$all_wallets=$wallet_obj::$response['data'];

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

<div class="rimplenet-bs5">
    <div class="row">
    <div class="form-container col-md-6">
        <img src="<?= $dir ?>/assets/img/wallet-concept-illustration_114360-1985.webp" alt="">
    </div>
    <div class="form-container rt col-md-6">


        <form method="POST" id="rimplenet_credit_debit_submit_form" style="max-width:700px; margin:auto;border:1px solid #ccc; border-radius:11px;padding: 13px;">
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

                                        $walletID=$wallet['wallet_id'];
                               
                                ?>
                                <option value="<?php echo $walletID; ?>" > <?php echo $disp_info; ?> - <?php echo $walletID; ?> </option> 
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
                            <!-- <select name="rimplenet_user" id="rimplenet_user" class="form-control" style="width: 100%; height: 40px;" required="">

                                <option value=""> Select User </option>

                               

                            </select> -->


                            <div class="dropdown">
                                <span onclick="myFunction()" class="btn btn-primary">Click To Search For User</span>
                                <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()" style="width:100%;max-width: 400px; height: 40px;">
                                <div id="myDropdown" class="dropdown-content">
                                    <div id="searchresultinput"></div>

                                    <div id="showSearchResult"></div>
                                </div>
                            </div>
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
            <input type="hidden" name="rimplenet_create_debit_submitted" value="true" />
            <?php wp_nonce_field('rimplenet_create_debit_nonce_field', 'rimplenet_create_debit_nonce_field'); ?>
            <!-- <input type="hidden" name="rimplenet_credit_debit_submitted" value="true"> -->
            <!-- <input type="hidden" id="rimplenet_create_credit_nonce_field" name="rimplenet_create_credit_nonce_field" value="20d5c54801"><input type="hidden" name="_wp_http_referer" value="/testrimplenet/wp-admin/edit.php?post_type=rimplenettransaction&amp;page=settings_wallets"> -->
            <center>
                <input type="submit" name="submit" id="submit" class="button button-primary" value="APPLY ACTION">
            </center>
        </form>
    </div>
    </div>
</div>

<script>
    site="<?= get_site_url() ?>";
/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function myFunction() {
      
  document.getElementById("myDropdown").classList.toggle("show");
  document.getElementById("myInput").style.display="block";
  div = document.getElementById("searchresultinput");
  div.innerHTML=""
}

function filterFunction() {
  var input, filter, ul, li, a, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  div = document.getElementById("myDropdown");
  a = div.getElementsByTagName("a");
  jQuery.ajax({
        type: 'POST',
        url: site+'/wp-json/rimplenet/v1/users/search',
        data: {
            'rimplenet_search_user':filter
        },
        success:function(res){

            html=``
            jQuery.each(jQuery(res), function(key, value) {
                html += `<button style="background: white !important; color:black !important;" class="p-3 btn  btn-light mt-4 mr-3 ml-3" onclick="checkClick(${value.ID}, '${value.data.user_login} - ${value.data.user_email}')" href="#${value.ID}">${value.data.user_login} - ${value.data.user_email}</button>`
            });
            jQuery('#showSearchResult').html(html);
            // checkClick();
            }



    });
   



//   for (i = 0; i < a.length; i++) {
//     txtValue = a[i].textContent || a[i].innerText;
//     if (txtValue.toUpperCase().indexOf(filter) > -1) {
//       a[i].style.display = "";
//     } else {
//       a[i].style.display = "none";
//     }
//   }
}

function checkClick(id, name) {
    html =`<select name="rimplenet_user" id="rimplenet_user" class="form-control" style="width: 100%; height: 40px;" required="">

<option value="${id}"> ${name} </option>



</select>`

div = document.getElementById("searchresultinput");
div.innerHTML = html
document.getElementById("myInput").style.display="none";
document.getElementById("showSearchResult").innerHTML=""
}



let submitBtn=document.getElementById("rimplenet_credit_debit_submit_form");

// submitBtn.addEventListener('');

(function($) {
	// 'use strict';

    $(document).ready(function() {
        $('#rimplenet_credit_debit_submit_form').submit(function() {
            rimplenet_user=document.getElementById("rimplenet_user");
            if (rimplenet_user && rimplenet_user.length > 0 && rimplenet_user.value !=="") {
                // everything's fine...
            } else {
                alert('Please select a User');
                return false;
            }
        });
    });

})( jQuery );
</script>