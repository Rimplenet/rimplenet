<?php
global $current_user,$wp;
wp_get_current_user();
// $referral_obj = new Rimplenet_referrals();

$user_id = sanitize_text_field($_GET['rimplenet_user_id'] ?? 0);
$user_referral = sanitize_text_field($_GET['rimplenet_user_referral'] ?? 0);

$referral_obj = new RimplenetCreateReferrals();
// $referral_obj->createQuery();
$create_referrals = $referral_obj->createReferrals(['user_id' => $user_id, 'user_referral' => $user_referral]);
$create_referrals = $referral_obj->response;


?>


<section class="section3 py-5">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <h1><strong class="stron"><?php
            echo $referral_obj->response['message']; 
            if ($referral_obj->response['data']) {
                echo '<br>' . $referral_obj->response['data'];
            }
           ?></strong></h1>
        </div>
      </div>
      
    </div>
  </div>
</section>
