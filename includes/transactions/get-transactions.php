<!-- do_action('rimplenet_hooks_and_monitors_on_started', $action='rimplenet_create_credits', $auth = null ,$request = $param); -->
<?php
class RimplenetGetTransactions extends Credits
{
    public function getTransactions($id, $type)
    {


        if (!empty($pageno)) {
            $pageno = sanitize_text_field($_GET['pageno']);
           }else{
            $pageno = 1;
           }
            if (isset($user_id) && $user_id!="any") {
                do_action('rimplenet_hooks_and_monitors_on_started', $action = 'rimplenet_get_transactions', $auth = null, $request = ['credit_id' => $id]);
                $txn_loop = $this->getTransactionsByUser($user_id, $posts_per_page, $pageno);
            }else{
                do_action('rimplenet_hooks_and_monitors_on_started', $action = 'rimplenet_get_transactions', $auth = null, $request = ['credit_id' => $id]);
                $txn_loop = $this->getAllTransactions($posts_per_page, $pageno);
            }
                           
            if( $txn_loop->have_posts() ){
                    
                     $status_code = 200;
                     $status = true;
                     $response_message = "Txns Retrieved Successful";
                     $data=$this->formatTransactions($txn_loop->get_posts());
                    
                 }
            else{
                  $status_code = 406;
                  $status = "failed";
                  $response_message = "Txns Retrieved Failed";
                  $data = "No Transaction Performed by this User"; 
                 }




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
              'author' => $user_id ,
              'posts_per_page'=>$posts_per_page,
              'paged'=>$pageno,
              'tax_query' => array(
                  'relation' => 'OR',
                  array(
                   'taxonomy' => 'rimplenettransaction_type',
                   'field'    => 'name',
                   'terms'    => array( 'CREDIT' ),
                 ),
                 array(
                   'taxonomy' => 'rimplenettransaction_type',
                   'field'    => 'name',
                   'terms'    => array( 'DEBIT' ),
                       ),
                      ),
                   )
             );
    }

    public function getAllTransactions($posts_per_page, $pageno)
    {
        return new WP_Query(
            array(  
              'post_type' => 'rimplenettransaction', 
              'post_status' => 'any',
            //   'author' => $user_id ,
              'posts_per_page'=>$posts_per_page,
              'paged'=>$pageno,
              'tax_query' => array(
                  'relation' => 'OR',
                  array(
                   'taxonomy' => 'rimplenettransaction_type',
                   'field'    => 'name',
                   'terms'    => array( 'CREDIT' ),
                 ),
                 array(
                   'taxonomy' => 'rimplenettransaction_type',
                   'field'    => 'name',
                   'terms'    => array( 'DEBIT' ),
                       ),
                      ),
                   )
             );
    }

    public function formatTransactions($data)
    {
  
      foreach ($data as $key => $value) {
        
        $txn_id=$value->ID;
        
                          
        $data[$key]->date_time = get_the_date('D, M j, Y', $txn_id).'<br>'.get_the_date('g:i A', $txn_id);
        $wallet_id = get_post_meta($txn_id, 'currency', true);
  
        $all_rimplenet_wallets = $this->getWallets();
        
        $data[$key]->wallet_symbol = $all_rimplenet_wallets[$wallet_id]['symbol'];
        $data[$key]->wallet_decimal = $all_rimplenet_wallets[$wallet_id]['decimal'];
        $data[$key]->wallet_decimal = $all_rimplenet_wallets[$wallet_id]['decimal'];
        
        
        $data[$key]->amount = get_post_meta($txn_id, 'amount', true);
        $data[$key]->txn_type = get_post_meta($txn_id, 'txn_type', true);
  
        $data[$key]->amount_formatted_disp = apply_filters("rimplenet_history_amount_formatted", $amount_formatted_disp,$txn_id, $txn_type, $amount, $amount_formatted_disp);
                          
        $data[$key]->note = get_post_meta($txn_id, 'note', true);
  
        
      }
  
  
      return $data;
    }

}
