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
                    $type = $transferType ?? '';

                 extract( (array) $transfer); $id = $transferId; ?>
                    <tr>
                        <td> <?= $transferDesc ?: '__' ?> </td>
                        <td> <?= $transferFrom ?: '__' ?> </td>
                        <td> <?= $transferTo ?: '__' ?> </td>
                        <td> <?= $symbol.$transferAmount ?: '__' ?> </td>
                        <td class="text-<?= $type == 'CREDIT' ? 'success' : 'danger' ?>"> <?= $transferType ?: '__' ?> </td>
                        <td> <?= strtoupper($currency) ?: '__' ?> </td>
                        <td> <?= $formattedDate ?: '__' ?> </td>
                        <td>
                        <?php

                            $viewUrl = add_query_arg(array('post_type' => $_GET["post_type"], 'page' => 'transfers', 'tab' => 'view_transfer', 'viewing_user' => get_current_user_id(), 'transfer' => $id), admin_url("edit.php"));
                            $editUrl = add_query_arg(array('post' => $transferId, 'action' => 'edit'), admin_url("post.php"));
                        ?>
                        <a href="<?= $viewUrl ?: '__' ?>" target="_blank" class="btn-outline-warning btn-sm btn">View</a>
                        <a href="<?= $editUrl ?: '__' ?>" target="_blank" class="btn-outline-primary btn-sm btn">Edit</a>
                        <a href="#" target="_blank" class="btn-outline-danger btn-sm btn">Delete</a>
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