<?php
$message = function ($title, $message, $type) {
    $resp = '<script>';
    $resp .= 'swal({
        title: "' . $title . '",
        text: "' . $message . '",
        icon: "' . $type . '",
      })';
      $resp.='
      setTimeout(() => {window.location ="?post_type=rimplenettransaction&page=apiKeys&tab=api_keys" }, 1000)
      ';
    $resp .= '</script>';

    return $resp;
};
if ($_SERVER['REQUEST_METHOD'] == "GET") :
    if (isset($_GET['action']) && $_GET['action'] == 'delete_key' && isset($_GET['key_id']) && $_GET['key_id'] > 0) :
        $key = sanitize_text_field($_GET['key_id'] ?? 0);
        $init = new RimplenetDeleteAPIKey;
        $init->delete($key);

        # Account for error that may occur durning the create process
        if (isset(Utils::$response['message'])) :
            $error = Utils::$response['message'];
        endif;

        // # Check the status code returned is not for error
        $code = (int) Utils::$response['status_code'];

        if ($code >= 400) :
            echo $message("Error", ucfirst(str_replace('_', ' ', $error)), 'error');
        else :
            echo $message("Success", Utils::$response['message'], 'success');
        endif;

    endif;
endif;
