<?php
//INCLUDED from api/class-base-api.php ~ main plugin file
use Traits\Wallet\RimplenetWalletTrait;
class RetrieveTransactions extends RimplenetGetTransactions
{
    
  public function __construct() {
    add_action( 'rest_api_init', array($this,'register_api_routes') );
  }
  
  public function register_api_routes() {
          register_rest_route( 'rimplenet/v1','/transactions', array(
            'methods' => 'GET',
            'permission_callback' => '__return_true',
            'callback' => array($this,'api_retrieve_txns'),
          ) );
   }
  
    
  public function api_retrieve_txns(WP_REST_Request $request ) {

    $param = [
      'transaction_id' => sanitize_text_field($request['transaction_id']) ?? false,
      'user_id' => sanitize_text_field($request['user_id']) ?? false,
      'type' => sanitize_text_field($request['type'] ?? false),
      'id' => sanitize_text_field($request['id'] ?? false),
  ];
    // return $this->getTransactionsOld($request);
    return $this->getTransactions($param);
     
     

//     //$wallet_obj = new Rimplenet_Wallets();
//     //$all_wallets = $wallet_obj->getWallets();
    
//    //Get inputs   
// //    $request_id = $request['request_id'];   
//    $txn_type = $request['txn_type'] ?? 'any';
//    $user_id = $request['user_id'] ?? 1;
// //    $security_secret = $request['security_secret'] ?? '1234';
//     $security_code = $request['security_secret'] ?? '1234';
//     $pageno = $request['pageno'] ?? '1';
//    $extra_data = $request['extra_data'];
//    if(!empty($extra_data)){ $extra_data_json  = json_decode($extra_data); }
   
//    //Save inputed data in array
//    $inputed_data = array(
//     //    "request_id"=>$request_id, 
//        "txn_type"=>$txn_type, 
//        "user_id"=>$user_id, 
//        "security_code"=>$security_code);
//    //Filter out empty inputs
//     $empty_input_array = array(); 
//     foreach($inputed_data as $input_key=>$single_data){ 
//         if(empty($single_data)){
//           $empty_input_array[$input_key]  = "field_required" ;
//         }
//     } 
    
//     $security_code_ret = get_option('security_code', "1234"); // get security code set
//     //Checks
//      if(!empty($empty_input_array)){
//           //if atleast one required input is empty
//           $status_code = 400;
//         $status = "one_or_more_input_required";
//           $response_message = "One or more input field is required";
//           $data = $empty_input_array;
//           $data["error"] = "one_or_more_input_required";
//      }
//      elseif(!empty($security_code) AND $security_code!=$security_code_ret ){
//           // throw error if security fails 
//           $status_code = 401;
//       $status = "incorrect_security_credentials";
//           $response_message = "Security verification failed";
//           $data = array(
//               "error"=>"incorrect_security_credentials"
//           ); 
//      }
//      elseif(!empty($extra_data) AND json_last_error() === JSON_ERROR_NONE ){
//           // throw error if extra_data is not json 
//           $status_code = 406;
//           $status = "extra_data_not_json";
//           $response_message = "extra_data input field should be json";
//           $data = array(
//               "extra_data"=>$extra_data,
//               "error"=>json_last_error()
//           ); 
//      }
//      else{
//         //get some info here to retun or some nice date like belo

//         if (!empty($pageno)) {
//           $pageno = sanitize_text_field($_GET['pageno']);
//          }else{
//           $pageno = 1;
//          }
//           if (isset($user_id) && $user_id!="any") {

//             // var_dump($user_id);
//             // die;
//             $txn_loop = new WP_Query(
//                 array(  
//                   'post_type' => 'rimplenettransaction', 
//                   'post_status' => 'any',
//                   'author' => $user_id ,
//                   'posts_per_page'=>$posts_per_page ?? 15,
//                   'paged'=>$pageno,
//                   'tax_query' => array(
//                       'relation' => 'OR',
//                       array(
//                        'taxonomy' => 'rimplenettransaction_type',
//                        'field'    => 'name',
//                        'terms'    => array( 'CREDIT' ),
//                      ),
//                      array(
//                        'taxonomy' => 'rimplenettransaction_type',
//                        'field'    => 'name',
//                        'terms'    => array( 'DEBIT' ),
//                            ),
//                           ),
//                        )
//                  );
//           }else{
//             $txn_loop = new WP_Query(
//                 array(  
//                   'post_type' => 'rimplenettransaction', 
//                   'post_status' => 'any',
//                 //   'author' => $user_id ,
//                   'posts_per_page'=>$posts_per_page ?? 15,
//                   'paged'=>$pageno,
//                   'tax_query' => array(
//                       'relation' => 'OR',
//                       array(
//                        'taxonomy' => 'rimplenettransaction_type',
//                        'field'    => 'name',
//                        'terms'    => array( 'CREDIT' ),
//                      ),
//                      array(
//                        'taxonomy' => 'rimplenettransaction_type',
//                        'field'    => 'name',
//                        'terms'    => array( 'DEBIT' ),
//                            ),
//                           ),
//                        )
//                  );
//           }
                         
//           if( $txn_loop->have_posts() ){
                  
//                    $status_code = 200;
//                    $status = true;
//                    $response_message = "Txns Retrieved Successful";
//                    $data=$this->formatTransactions($txn_loop->get_posts());
                  
//                }
//           else{
//                 $status_code = 406;
//                 $status = "failed";
//                 $response_message = "Txns Retrieved Failed";
//                 $data = "No Transaction Performed by this User"; 
//                }
        
//      }
     
//      //RETURN RESPONSE
//       if($status_code) { 
//       //learn more about status_code to return at https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
            
//             //OR if !is_wp_error($request) 
//            return new WP_REST_Response(
//            array(
//             'status_code' => $status_code,
//             'status' => $status,
//             'message' => $response_message,
//             'data' => $data 
//             )
//            );
          
//         }
//         else {
            
//           $status_code = 400;
//           $response_message = "Unknown Error";
//           $data = array(
//               "error"=>"unknown_error"
//           ); 
//           return new WP_Error($status_code, $response_message, $data);
//         }
  }
  

  // public function formatTransactions($data)
  // {

  //   foreach ($data as $key => $value) {
      
  //     $txn_id=$value->ID;
      
                        
  //     $data[$key]->date_time = get_the_date('D, M j, Y', $txn_id).'<br>'.get_the_date('g:i A', $txn_id);
  //     $wallet_id = get_post_meta($txn_id, 'currency', true);

  //     $all_rimplenet_wallets = $this->getWallets();
      
  //     $data[$key]->wallet_symbol = $all_rimplenet_wallets[$wallet_id]['symbol'];
  //     $data[$key]->wallet_decimal = $all_rimplenet_wallets[$wallet_id]['decimal'];
  //     $data[$key]->wallet_decimal = $all_rimplenet_wallets[$wallet_id]['decimal'];
      
      
  //     $data[$key]->amount = get_post_meta($txn_id, 'amount', true);
  //     $data[$key]->txn_type = get_post_meta($txn_id, 'txn_type', true);

  //     // $data[$key]->amount_formatted_disp = apply_filters("rimplenet_history_amount_formatted", $amount_formatted_disp,$txn_id, $txn_type, $amount, $amount_formatted_disp);
  //     $data[$key]->amount_formatted_disp = $this->getRimplenetWalletFormattedAmount($data[$key]->amount, $wallet_id);
                        
  //     $data[$key]->note = get_post_meta($txn_id, 'note', true);

      
  //   }


  //   return $data;
  // }

  // public function getWallets(){
  //   $wallet_obj = new Rimplenet_Wallets();
  //   $all_wallets = $wallet_obj->getWallets();
  //   return $all_wallets;
  // }

}

$RetrieveTxns = new RetrieveTransactions();