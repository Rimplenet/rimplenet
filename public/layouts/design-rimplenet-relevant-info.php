<?php
//Included from Add shortcode Function in public/class-rimplenet-mlm-public.php
//use case [rimplenet-display-info action="view_direct_downline" user_id="1"]
 global $current_user, $wp;
 wp_get_current_user();

$atts = shortcode_atts( array(

    'action' => 'empty',
    'user_id' => $current_user->ID,
    'wallet_id' => 'woocommerce_base_cur',

), $atts );


$action = $atts['action'];
$user_id = $atts['user_id'];
if(isset($_GET['rimplenet-user-id']) AND current_user_can('manage_options') ){
 $user_id = $_GET['rimplenet-user-id'];
}

$userinfo = get_user_by( 'id', $user_id );
$wallet_id = $atts['wallet_id'];

$wallet_obj = new Rimplenet_Wallets();
if(!is_user_logged_in()){
    echo '<div class="rimplenet_not_logged_in">To Access this Info, <b>LOGIN</b> or <b>REGISTER</b> first</span>';
}
elseif($action=='view_direct_downline'){
    
     $referrer = trim(get_user_meta($user_id,'rimplenet_referrer_sponsor', true));
    ?>
  <div class="rimplenet-mt">
      <?php if(!empty($referrer)){ ?>
      <center>Your Referrer: <strong> <?php echo $referrer; ?> </strong></center>
      <?php } ?>
    <div class="row">
	 	<div class="col-md-12"> 

	 
			<?php
			$args = array (
                    'order' => 'DESC',
                    'orderby' => 'user_registered',
                    'meta_query' => array(
                        'relation' => 'OR',
                        array(
                            'key'     => 'rimplenet_referrer_sponsor',
                            'value'   => $userinfo->user_login,
                            'compare' => '='
                        ),
                    )
                );
                
	      	   
	      	   $wp_user_query = new WP_User_Query($args);
	      	   $users = $wp_user_query->get_results();
	      	   
                if( !empty($users)  ){
                            ?>
				     
				     <table class="table table-responsive-md">
				      <thead class="thead-dark">
				        <tr>
				          <th scope="col">Name</th>
				          <th scope="col">Date Registered</th>
				          <th scope="col" style="display:none">Activation Status</th>
				          <th scope="col">Contact Details</th>
          				  <th scope="col">Action</th>

				        </tr>
				      </thead>
					      <tbody>
                   <?php
                     foreach ($users as $user)
                      {
                         // $date_time_registered = get_the_date('D, M j, Y',$user->user_registered).'<br>'.get_the_date('g:i A', $user->user_registered);;
                    ?>

				        <tr>
				          <th scope="row"> 
    				          <?php echo $user->display_name ?> 
    				          <br>
    				          @<?php echo $user->user_login ?>
				          </th>
				          
				          <td> <?php echo $user->user_registered; ?></td>
				          
				          <td  style="display:none"> <?php echo $activation_status; ?> </td>
				          
				          <td> <?php echo $user->user_email.'<br> '.$user->rimplenet_user_phone_number; ?> </td>
				          
				          <td>
				               <a class="btn btn-primary btn-sm" href="mailto:<?php echo $user->user_email; ?>" role="button">Mail <?php echo $user->user_login ?></a>
				              
				              <?php
				              if(!empty($user->rimplenet_user_phone_number)){
				              ?>
				                  | <a class="btn btn-success btn-sm" href="tel:<?php echo $user->rimplenet_user_phone_number; ?>" role="button">Call <?php echo $user->user_login ?></a>
				                  
				             <?php
				              }
				              if(current_user_can('manage_options') ){
				                  
                                $downline_link = add_query_arg( array('rimplenet-user-id'=>$user->ID,), home_url(add_query_arg(array($_GET),$wp->request)) );
				              ?> <br>
				                <a class="btn btn-info btn-sm" href="<?php echo $downline_link; ?>" role="button">View <?php echo $user->user_login ?>'s Downline</a>
				                  
				             <?php
				              }
				             ?>
				          	
				          </td>
				        </tr>

				    <?php
                      }
                    ?>
				         
				         
					      </tbody>
				      </table>
				         
				        <?php

				         }
				         else{
				         	echo "<center>No downline yet, you can refer with your username or referral link</center>";
				         }

                        wp_reset_postdata();

				        ?>


            </div>  
	 	 </div>
	 	 <center>
	 	 <div class="form-group">
	 	     
	 	    <h4>Your Referral Link</h4>
            <label for="RimplenetRefLink" style="display:none;">Tap to Copy your Referral Link below</label>
            <textarea class="form-control rimplenet_click_to_copy" id="RimplenetRefLink" rows="3"><?php echo get_bloginfo('url').'/register?rimplenet-ref='.$current_user->user_login; ?></textarea>
         
	 	 </center>
	   </div>
    
<?php
 }


elseif($action=='view_account_activation_status'){
    
    
    
     
    $status = get_user_meta($user_id, 'rimplenet_account_activation_status',true );
    
    if(empty($status)){$status = 'not activated';}

    echo '<span class="rimplenet_account_activation_status">'.$status.'</span>';
}

else{
  echo __('You did not specify a valid action in shortcode e.g [rimplenet-display-info action="view_account_activation_status"] has a valid action which is view_account_activation_status', 'rimplenet-text-domain'); 
}




?>