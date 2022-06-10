<?php

require plugin_dir_path(dirname(__FILE__)) . '/assets/php/get.php';
?>

<h2> Active Users</h2>
<div class="table-responsive bg-white p-5 mr-3 ml-3 rimplenet-bs5">
<table class="table table-sm table-borderless table-striped rimplenet-bs5" style="width:100%" id="rimplenetmyTable">

        <thead>
            <tr>
                <th> Username </th>
                <th> Email </th>
                <th> Display Name </th>
                <th> Created Date </th>
                <th> User Balance Shortcode </th>
                <!-- <th> Include Wallet in Withdrawal Form</th> -->
                <!-- <th> Include Wallet in Woocommerce Currency List</th> -->
                <th> Actions </th>
            </tr>
        </thead>

        <tbody>

                <?php if(!empty($users)): foreach($users as $user):?>
                    <tr>
                        <td> <?= $user['user_login'] ?? '__' ?> </td>
                        <td> <?= $user['user_email'] ?? '__' ?> </td>
                        <td> <?= $user['display_name'] ?? '__' ?> </td>
                        <td> <?= date('Y m d', strtotime($user['user_registered'])) ?? '__' ?> </td>
                        <td></td>
                        <td>
                        <a href="#" target="_blank" class="btn-primary btn">Edit</a>
                        <a href="#" target="_blank" class="btn-primary btn">Update</a>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>

        </tbody>

        <tfoot>
            <tr>
                <th> Username </th>
                <th> Email </th>
                <th> Display Name </th>
                <th> Created Date </th>
                <th> User Balance Shortcode </th>
                <!-- <th> Include Wallet in Withdrawal Form</th> -->
                <!-- <th> Include Wallet in Woocommerce Currency List</th> -->
                <th> Actions </th>
            </tr>
        </tfoot>

    </table>
</div>