<?php
$dir = plugin_dir_url(dirname(__FILE__));
$linkUrl = function($tab){
    return add_query_arg(array('post_type' => $_GET["post_type"], 'page' => 'transfers', 'tab' => $tab, 'viewing_user' => get_current_user_id()), admin_url("edit.php"));
};
?>
<br>
<br>
<div class="rimplenet-bs5">
    <div class="container">
        <div class="row">
            <div class="col-4">
                <div class="card" style="width: 18rem;">
                    <img src="<?= $dir.'assets/img/transfers.jpg' ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Transfers</h5>
                        <p class="card-text">Lorem ipsum, dolor sit amet consectetur.</p>
                        <a href="<?= $linkUrl('transfers') ?>" class="btn btn-primary">History</a>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card" style="width: 18rem;">
                    <img src="<?= $dir.'assets/img/transfer.png' ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Send Money</h5>
                        <p class="card-text">Fugit velit repellendus ut id dicta aspernatur.</p>
                        <a href="<?= $linkUrl('create') ?>" class="btn btn-primary">Transfer</a>
                    </div>
                </div>
            </div
        </div>
    </div>
</div>