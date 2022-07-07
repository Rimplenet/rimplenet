<?php

require plugin_dir_path(dirname(__FILE__)) . '/assets/php/get.php';
?>
<!-- <h2> Active Users</h2> -->
<div class="table-responsive bg-white p-5 mr-3 ml-3 rimplenet-bs5">
    <table class="table table-sm table-borderless table-striped rimplenet-bs5" style="width:100%" id="rimplenetmyTable">

        <thead>
            <tr>
                <th> Username </th>
                <th> Email </th>
                <th> Display Name </th>
                <th> First Name </th>
                <th> Last Name </th>
                <th> Created Date </th>
                <!-- <th> Include Wallet in Withdrawal Form</th> -->
                <!-- <th> Include Wallet in Woocommerce Currency List</th> -->
                <th> Actions </th>
            </tr>
        </thead>

        <tbody>


            <?php if (!empty($users)) : foreach ($users as $user) : $id = $user['ID']; ?>
                    <tr>
                        <td> <?= $user['username'] ?: '__' ?> </td>
                        <td> <?= $user['user_email'] ?: '__' ?> </td>
                        <td> <?= $user['display_name'] ?: '__' ?> </td>
                        <td> <?= $user['first_name'] ?: '__' ?> </td>
                        <td> <?= $user['last_name'] ?: '__' ?> </td>
                        <td> <?= date('Y m d', strtotime($user['user_registered'])) ?? '__' ?> </td>
                        <td>
                            <?php

                            $editUrl = add_query_arg(array('post_type' => $_GET["post_type"], 'page' => 'users', 'tab' => 'update', 'viewing_user' => $current_user->ID, 'user' => $id), admin_url("edit.php"));
                            ?>
                            <a href="<?= $editUrl ?: '__' ?>" target="_blank" class="btn-outline-primary btn btn-sm">Edit</a>
                            <a href="#" target="_blank" class="btn-outline-danger btn btn-sm">Delete</a>
                        </td>
                    </tr>
            <?php endforeach;
            endif; ?>

        </tbody>

        <tfoot>
            <tr>
                <th> Username </th>
                <th> Email </th>
                <th> Display Name </th>
                <th> Created Date </th>
                <th> Last Name </th>
                <th> Created Date </th>
                <!-- <th> Include Wallet in Withdrawal Form</th> -->
                <!-- <th> Include Wallet in Woocommerce Currency List</th> -->
                <th> Actions </th>
            </tr>
        </tfoot>

    </table>
</div>