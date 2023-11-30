<?php

use Traits\Wallet\RimplenetWalletTrait;

class RimplenetGetTransactions extends RimplenetGetWallets
{
  use RimplenetWalletTrait;
  public function getTransactions($params)
  {


    extract($params);


    $posts_per_page = 10;
    if (!empty($pageno)) {
      $pageno = sanitize_text_field($_GET['pageno']);
    } else {
      $pageno = 1;
    }
    

    if ($transaction_id !== false && !empty($transaction_id)) {
      $txn_loop = $this->getTransactionByID($transaction_id);
    }elseif(($meta_key !== null && !empty($meta_key)) || ($meta_value !== null && !empty($meta_value))){
      // do_action('rimplenet_hooks_and_monitors_on_started', $action = 'rimplenet_get_transactions', $auth = null, $request = ['credit_id' => $id]);
      $txn_loop = $this->searchTransactions($meta_key, $meta_value, $category, $posts_per_page, $pageno, $user_id ?? null);
      if ($txn_loop->have_posts()) {
        $txn_loop = $txn_loop->get_posts();
      }else{
        $txn_loop = false;
      }
    }
    elseif ($user_id) {
      $txn_loop=$this->getTransactionsByUser($user_id, 10, $pageno??1);
      if ($txn_loop->have_posts()) {
        $txn_loop = $txn_loop->get_posts();
      }else{
        $txn_loop = false;
      }
    }
    else {
      do_action('rimplenet_hooks_and_monitors_on_started', $action = 'rimplenet_get_transactions', $auth = null, $request = ['credit_id' => $id]);
      $txn_loop = $this->getAllTransactions($posts_per_page, $pageno);
      if ($txn_loop->have_posts()) {
        $txn_loop = $txn_loop->get_posts();
      }else{
        $txn_loop = false;
      }
    }

    if ($txn_loop) {
      $data = Res::success($this->formatTransactions($txn_loop), "Txns Retrieved Successful");
    } else {
      $status_code = 406;
      $data = Res::success(['No Transaction Performed by this User'], "Transaction Not found", 200);
    }

    return $data;



    if ($id !== '') :
      # get single credit
      do_action('rimplenet_hooks_and_monitors_on_started', $action = 'rimplenet_get_transactions', $auth = null, $request = ['credit_id' => $id]);

      return $this->creditById($id, $type);
    else :
      # get all credits
      do_action('rimplenet_hooks_and_monitors_on_started', $action = 'rimplenet_get_transactions', $auth = null, $request = []);

      return $this->getAllCredits();
    endif;

    return $this->response;
  }

  public function getTransactionsByUser($user_id, $posts_per_page, $pageno)
  {
    // die;
    return new WP_Query(
      array(
        'post_type' => 'rimplenettransaction',
        'post_status' => 'any',
        'author' => $user_id,
        'posts_per_page' => $posts_per_page,
        'paged' => $pageno,
        'tax_query' => array(
          'relation' => 'OR',
          array(
            'taxonomy' => 'rimplenettransaction_type',
            'field'    => 'name',
            'terms'    => array('CREDIT'),
          ),
          array(
            'taxonomy' => 'rimplenettransaction_type',
            'field'    => 'name',
            'terms'    => array('DEBIT'),
          ),
        )
      )
    );
  }

  public function searchTransactions($meta_key, $meta_value, $category, $posts_per_page, $pageno, $user_id=null)
  {
    // die;
    return new WP_Query(
      array(
        'post_type' => 'rimplenettransaction',
        'post_status' => 'any',
        'author' => ($user_id) ? $user_id : null,
        // 'author' => $user_id,
        'posts_per_page' => $posts_per_page,
        'paged' => $pageno,
        'tax_query' => array(
          'relation' => 'OR',
          array(
            'taxonomy' => 'rimplenettransaction_type',
            'field'    => 'name',
            'terms'    => array('CREDIT'),
          ),
          array(
            'taxonomy' => 'category',
            'field'    => 'name',
            'terms'    => array('CREDIT'),
          ),
          array(
            'taxonomy' => 'rimplenettransaction_type',
            'field'    => 'name',
            'terms'    => array('DEBIT'),
          ),
        ),
        'meta_query' => array(
          array(
              'key'       => $meta_key,
              'value'     => $meta_value,
              'compare'   => 'LIKE'
          )
        )
      )
    );
  }


  public function getTransactionByID($post_id)
  {
    // die;
    // return new WP_Query(
    //   array(
    //     'post_type' => 'rimplenettransaction',
    //     'post_status' => 'any',
    //     'author' => $user_id,
    //     'posts_per_page' => $posts_per_page,
    //     'paged' => $pageno,
    //     'tax_query' => array(
    //       'relation' => 'OR',
    //       array(
    //         'taxonomy' => 'rimplenettransaction_type',
    //         'field'    => 'name',
    //         'terms'    => array('CREDIT'),
    //       ),
    //       array(
    //         'taxonomy' => 'rimplenettransaction_type',
    //         'field'    => 'name',
    //         'terms'    => array('DEBIT'),
    //       ),
    //     ),
    //   )
    // );

    return get_post($post_id);
    // return get_post(35);
  }

  public function getAllTransactions($posts_per_page, $pageno)
  {
    return new WP_Query(
      array(
        'post_type' => 'rimplenettransaction',
        'post_status' => 'any',
        //   'author' => $user_id ,
        'posts_per_page' => $posts_per_page,
        'paged' => $pageno,
        'tax_query' => array(
          'relation' => 'OR',
          array(
            'taxonomy' => 'rimplenettransaction_type',
            'field'    => 'name',
            'terms'    => array('CREDIT'),
          ),
          array(
            'taxonomy' => 'rimplenettransaction_type',
            'field'    => 'name',
            'terms'    => array('DEBIT'),
          ),
        ),
      )
    );
  }

  public function formatTransactions($data)
  {
    

    if (is_null($data)) {
      return false;
    }

    if (is_array($data)) {
      foreach ($data as $key => $value) {

        // var_dump($value);
        // die;
        $txn_id = $value->ID;
        $data[$key]->transaction_id=$value->ID;
        unset($value->ID);


        $data[$key]->date_time = get_the_date('D, M j, Y', $txn_id) . '<br>' . get_the_date('g:i A', $txn_id);
        $wallet_id = get_post_meta($txn_id, 'currency', true);

        // $all_rimplenet_wallets = $this->getWallets();
        // var_dump($all_rimplenet_wallets, $wallet_id);
        // die;
        $wallet = $this->getWallet($wallet_id);
        // $data[$key]->wallet_symbol = $all_rimplenet_wallets[$wallet_id]['symbol'];
        // $data[$key]->wallet_decimal = $all_rimplenet_wallets[$wallet_id]['decimal'];
        // $data[$key]->wallet_decimal = $all_rimplenet_wallets[$wallet_id]['decimal'];
        // $data[$key]->wallet_id = $all_rimplenet_wallets[$wallet_id]['wallet_id'];
        $data[$key]->wallet_symbol = $wallet['wallet_symbol'];
        $data[$key]->wallet_decimal = intval($wallet['wallet_decimal']);
        // $data[$key]->wallet_decimal = $all_rimplenet_wallets[$wallet_id]['decimal'];
        $data[$key]->wallet_id = $wallet_id;


        $data[$key]->amount = intval(get_post_meta($txn_id, 'amount', true));
        $data[$key]->txn_type = get_post_meta($txn_id, 'txn_type', true);

        $data[$key]->amount_formatted_disp = $this->getRimplenetWalletFormattedAmount($data[$key]->amount, $wallet_id);

        // $data[$key]->amount_formatted_disp = apply_filters("rimplenet_history_amount_formatted", $amount_formatted_disp,$txn_id, $txn_type, $amount, $amount_formatted_disp);

        $data[$key]->note = get_post_meta($txn_id, 'note', true);
      }
    } else {
      $txn_id = $data->ID;
      $data->transaction_id=$data->ID;
      unset($data->ID);


      $data->date_time = get_the_date('D, M j, Y', $txn_id) . '<br>' . get_the_date('g:i A', $txn_id);
      $wallet_id = get_post_meta($txn_id, 'currency', true);

      
      $wallet = $this->getWallet($wallet_id);
      // $data[$key]->wallet_symbol = $all_rimplenet_wallets[$wallet_id]['symbol'];
      // $data[$key]->wallet_decimal = $all_rimplenet_wallets[$wallet_id]['decimal'];
      // $data[$key]->wallet_decimal = $all_rimplenet_wallets[$wallet_id]['decimal'];
      // $data[$key]->wallet_id = $all_rimplenet_wallets[$wallet_id]['wallet_id'];
      $data->wallet_symbol = $wallet['wallet_symbol'];
      $data->wallet_decimal = intval($wallet['wallet_decimal']);
      // $data[$key]->wallet_decimal = $all_rimplenet_wallets[$wallet_id]['decimal'];
      $data->wallet_id = $wallet_id;


      $data->amount = intval(get_post_meta($txn_id, 'amount', true));
      $data->txn_type = get_post_meta($txn_id, 'txn_type', true);

      $data->amount_formatted_disp = $this->getRimplenetWalletFormattedAmount($data->amount, $wallet_id);

      // $data[$key]->amount_formatted_disp = apply_filters("rimplenet_history_amount_formatted", $amount_formatted_disp,$txn_id, $txn_type, $amount, $amount_formatted_disp);

      $data->note = get_post_meta($txn_id, 'note', true);
    }


    return $data;
  }

  public function getTransactionsOld($request)
  {
    //$wallet_obj = new Rimplenet_Wallets();
    //$all_wallets = $wallet_obj->getWallets();

    //Get inputs   
    //    $request_id = $request['request_id'];   
    $txn_type = $request['txn_type'] ?? 'any';
    $user_id = $request['user_id'] ?? 1;
    //    $security_secret = $request['security_secret'] ?? '1234';
    $security_code = $request['security_secret'] ?? '1234';
    $pageno = $request['pageno'] ?? '1';
    $extra_data = $request['extra_data'];
    if (!empty($extra_data)) {
      $extra_data_json  = json_decode($extra_data);
    }

    //Save inputed data in array
    $inputed_data = array(
      //    "request_id"=>$request_id, 
      "txn_type" => $txn_type,
      "user_id" => $user_id,
      "security_code" => $security_code
    );
    //Filter out empty inputs
    $empty_input_array = array();
    foreach ($inputed_data as $input_key => $single_data) {
      if (empty($single_data)) {
        $empty_input_array[$input_key]  = "field_required";
      }
    }

    $security_code_ret = get_option('security_code', "1234"); // get security code set
    //Checks
    if (!empty($empty_input_array)) {
      //if atleast one required input is empty
      $status_code = 400;
      $status = "one_or_more_input_required";
      $response_message = "One or more input field is required";
      $data = $empty_input_array;
      $data["error"] = "one_or_more_input_required";
    } elseif (!empty($security_code) and $security_code != $security_code_ret) {
      // throw error if security fails 
      $status_code = 401;
      $status = "incorrect_security_credentials";
      $response_message = "Security verification failed";
      $data = array(
        "error" => "incorrect_security_credentials"
      );
    } elseif (!empty($extra_data) and json_last_error() === JSON_ERROR_NONE) {
      // throw error if extra_data is not json 
      $status_code = 406;
      $status = "extra_data_not_json";
      $response_message = "extra_data input field should be json";
      $data = array(
        "extra_data" => $extra_data,
        "error" => json_last_error()
      );
    } else {
      //get some info here to retun or some nice date like belo

      if (!empty($pageno)) {
        $pageno = sanitize_text_field($_GET['pageno'] ?? 1);
      } else {
        $pageno = 1;
      }
      if (isset($user_id) && $user_id != "any") {

        // var_dump($user_id);
        // die;
        $txn_loop = new WP_Query(
          array(
            'post_type' => 'rimplenettransaction',
            'post_status' => 'any',
            'author' => $user_id,
            'posts_per_page' => $posts_per_page ?? 15,
            'paged' => $pageno,
            'tax_query' => array(
              'relation' => 'OR',
              array(
                'taxonomy' => 'rimplenettransaction_type',
                'field'    => 'name',
                'terms'    => array('CREDIT'),
              ),
              array(
                'taxonomy' => 'rimplenettransaction_type',
                'field'    => 'name',
                'terms'    => array('DEBIT'),
              ),
            ),
          )
        );
      } else {
        $txn_loop = new WP_Query(
          array(
            'post_type' => 'rimplenettransaction',
            'post_status' => 'any',
            //   'author' => $user_id ,
            'posts_per_page' => $posts_per_page ?? 15,
            'paged' => $pageno,
            'tax_query' => array(
              'relation' => 'OR',
              array(
                'taxonomy' => 'rimplenettransaction_type',
                'field'    => 'name',
                'terms'    => array('CREDIT'),
              ),
              array(
                'taxonomy' => 'rimplenettransaction_type',
                'field'    => 'name',
                'terms'    => array('DEBIT'),
              ),
            ),
          )
        );
      }

      if ($txn_loop->have_posts()) {

        $status_code = 200;
        $status = true;
        $response_message = "Txns Retrieved Successful";
        $data = $this->formatTransactions($txn_loop->get_posts());
      } else {
        $status_code = 406;
        $status = "failed";
        $response_message = "Txns Retrieved Failed";
        $data = "No Transaction Performed by this User";
      }
    }

    //RETURN RESPONSE
    if ($status_code) {
      //learn more about status_code to return at https://developer.mozilla.org/en-US/docs/Web/HTTP/Status

      //OR if !is_wp_error($request) 
      return new WP_REST_Response(
        array(
          'status_code' => $status_code,
          'status' => $status,
          'message' => $response_message,
          'data' => $data
        )
      );
    } else {

      $status_code = 400;
      $response_message = "Unknown Error";
      $data = array(
        "error" => "unknown_error"
      );
      return new WP_Error($status_code, $response_message, $data);
    }
  }



  public function fetchTransactions($params)
  {
    extract($params);

    $query_arr = array(
      'post_type' => 'rimplenettransaction',
      'post_status' => 'any',
      'orderby' => $orderby,
      'order' => $order,
      'posts_per_page' => $posts_per_page,
      'paged' => $pageno,
      'tax_query' => array(
        'relation' => 'OR',
        array(
          'taxonomy' => 'rimplenettransaction_type',
          'field'    => 'name',
          'terms'    => array('CREDIT'),
        ),
        array(
          'taxonomy' => 'rimplenettransaction_type',
          'field'    => 'name',
          'terms'    => array('DEBIT'),
        ),
      ),
      'meta_query' => array(
        array(
          'key'       => $meta_key,
          'value'     => $meta_value,
          'compare'   => 'LIKE'
        )
      )
    );
    if (!empty($request['user_id'])) {
      $query_arr['author'] = $request['user_id'];
    }
    $txn_loop = new WP_Query(
      $query_arr
    );

    if ($txn_loop->have_posts()) {
      $data = $txn_loop->get_posts();

      if (is_array($data)) {

        foreach ($data as $key => $value) {

          $txn_id = $value->ID;
          $key = $key;

          //delete other info from retrieved taxonomy 
          unset($data[$key]->post_author);
          unset($data[$key]->post_date);
          unset($data[$key]->post_date_gmt);
          unset($data[$key]->post_content);
          unset($data[$key]->post_title);
          unset($data[$key]->post_excerpt);
          unset($data[$key]->post_status);
          unset($data[$key]->comment_status);
          unset($data[$key]->ping_status);
          unset($data[$key]->post_password);
          unset($data[$key]->post_name);
          unset($data[$key]->to_ping);
          unset($data[$key]->pinged);
          unset($data[$key]->post_modified);
          unset($data[$key]->post_modified_gmt);
          unset($data[$key]->post_content_filtered);
          unset($data[$key]->post_parent);
          unset($data[$key]->guid);
          unset($data[$key]->menu_order);
          unset($data[$key]->post_type);
          unset($data[$key]->post_mime_type);
          unset($data[$key]->comment_count);
          unset($data[$key]->filter);

          $data[$key]->ID = intval($value->ID);

          $data[$key]->request_id = get_post_meta($txn_id, 'request_id', true);
          $data[$key]->user_id = intval(get_post_meta($txn_id, 'user_id', true));
          $data[$key]->date_time = get_the_date('l, F j, Y', $txn_id) . ' ' . get_the_date('g:iA', $txn_id);
          $data[$key]->timestamp = get_post_time('U', false, $txn_id);
          $data[$key]->amount = floatval(get_post_meta($txn_id, 'amount', true));
          //$data[$key]->amount_formatted = $this->getRimplenetWalletFormattedAmount($data[$key]->amount, 'usdt');
          $data[$key]->amount_formatted = get_post_meta($txn_id, 'currency', true) . ' ' . $data[$key]->amount;
          $data[$key]->note = get_post_meta($txn_id, 'note', true);

          $data[$key]->transaction_id = intval($txn_id);
          $data[$key]->transaction_type = get_post_meta($txn_id, 'txn_type', true);

          $data[$key]->wallet_id = get_post_meta($txn_id, 'currency', true);
          $wallet = $this->getWallet($data[$key]->wallet_id);
          $data[$key]->wallet_symbol = $wallet['wallet_symbol'];
          $data[$key]->wallet_decimal = floatval($wallet['wallet_decimal']);
          $data[$key]->amount_formatted = $wallet['wallet_symbol']. '' . get_post_meta($txn_id, 'amount', true);

          if (!empty($request['metas_to_retrieve'])) {
            $metas_to_ret = explode(",", $request['metas_to_retrieve']);
            $metas_to_return = [];
            foreach ($metas_to_ret as $met_value) {
              $metas_to_return[$met_value] = get_post_meta($txn_id, $met_value, true);
            }
            $data[$key]->metas = $metas_to_return;
          }
        }
      }

      $status_code = 200;
      $status = true;
      $message = "Transactions Retrieved";

      $api_response =  array(
        'status_code' => $status_code,
        'status' => $status,
        'message' => $message,
        'data' => $data
      );
    } else {

      $status_code = 200;
      $status = true;
      $message = "No transaction found";
      $data = [];
      $error = array(
        'msg' => "No transaction found",
        'recommendation' => "Try using modifying the filters or params"
      );


      $api_response =  array(
        'status_code' => $status_code,
        'status' => $status,
        'message' => $message,
        'data' => $data,
        'error' => $error
      );
    }



    if ($status) {

      return new WP_REST_Response($api_response, $status_code);
    } else {

      $status_code = 400;
      $response_message = "Unknown Error";
      $data = array(
          "error" => "unknown_error"
        );
      return new WP_Error($status_code, $response_message, $data);
    }
  }
}
