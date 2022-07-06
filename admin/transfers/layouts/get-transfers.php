<?php

require plugin_dir_path(dirname(__FILE__)) . '/assets/php/get.php';
?>
<!-- <h2> Active Users</h2> -->
<div class="table-responsive bg-white p-5 mr-3 ml-3 rimplenet-bs5">
    <table class="table table-sm table-borderless table-striped rimplenet-bs5" style="width:100%" id="rimplenetmyTable">

        <thead>
            <tr>
                <th> Description </th>
                <th> From </th>
                <th> To </th>
                <th> Amount </th>
                <th> Type </th>
                <th> Currency </th>
                <th> Date </th>
                <!-- <th> Include Wallet in Withdrawal Form</th> -->
                <!-- <th> Include Wallet in Woocommerce Currency List</th> -->
                <th> Actions </th>
            </tr>
        </thead>

        <tbody>


            <?php 
            if (isset($transfers['data']) && !empty($transfers['data']) && is_iterable($transfers['data'])) : 
                foreach ($transfers['data'] as $transfer) :
                 extract( (array) $transfer); $id = $transferId; ?>
                    <tr>
                        <td> <?= $transferDesc ?: '__' ?> </td>
                        <td> <?= $transferFrom ?: '__' ?> </td>
                        <td> <?= $transferTo ?: '__' ?> </td>
                        <td> <?= $symbol.$transferAmount ?: '__' ?> </td>
                        <td> <?= $transferType ?: '__' ?> </td>
                        <td> <?= strtoupper($currency) ?: '__' ?> </td>
                        <td> <?= $formattedDate ?: '__' ?> </td>
                        <td>
                        <?php

                        $editUrl = add_query_arg(array('post_type' => $_GET["post_type"], 'page' => 'users', 'tab' => 'update', 'viewing_user' => $current_user->ID, 'user' => $id), admin_url("edit.php"));
                        ?>
                        <a href="<?= $editUrl ?: '__' ?>" target="_blank" class="btn-primary btn">Edit</a>
                        <a href="#" target="_blank" class="btn-primary btn">Delete</a>
                        </td>
                    </tr>
            <?php endforeach;  endif; ?>

        </tbody>

        <tfoot>
            <tr>
                <th> Description </th>
                <th> From </th>
                <th> From </th>
                <th> Amount </th>
                <th> Type </th>
                <th> Currency </th>
                <th> Date </th>
                <!-- <th> Include Wallet in Withdrawal Form</th> -->
                <!-- <th> Include Wallet in Woocommerce Currency List</th> -->
                <th> Actions </th>
            </tr>
        </tfoot>

    </table>
</div>