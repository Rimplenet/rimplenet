<?php

$transfers = [];
if (isset($_GET)) {
    if (isset($_GET['tab']) && $_GET['tab'] == 'api_keys') :
        $instance = (new RimplenetGetApiKeys())->getKeys([], true);
        $keys = Utils::$response;
    endif;
    if (isset($_GET['tab']) && $_GET['tab'] == 'view_transfer' && isset($_GET['transfer']) && $_GET['transfer'] !== '') :
        $instance = (new RimplenetGetTransfers())->transfers(['transfer_id' =>(int) $_GET['transfer']]);
        $transfers = Utils::$response;
    endif;
    // echo json_encode($transfers);
}

// echo json_encode($transfers['data']);
// exit;