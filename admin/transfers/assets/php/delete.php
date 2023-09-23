<?php
if($_SERVER['REQUEST_METHOD'] == "GET"):
    if(isset($_GET['delete_transfer']) && isset($_GET['transfer_id']) && $_GET['transfer_id'] > 0):
        $tranferId = sanitize_text_field($_GET['transfer_id'] ?? 0);
        $init = new RimplenetDeleteTransfers;
        $init->delete($tranferId);
        $response = (object) $init::$response;

        $message = $response->message ?? '';
        $status_code = $response->status_code;
        $error = $response->error ?? '';
        $data = $response->data ?? '';
        
    endif;
endif;