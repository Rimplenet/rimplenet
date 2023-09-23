<?php
global $current_user,$wp;
wp_get_current_user();

$user_id = sanitize_text_field($_GET['rimplenet_user_id'] ?? 0);

$referral_obj = new RimplenetGetreferrals();
$all_referrals = $referral_obj->getreferrals(['user_id' => $user_id])['data'];

?>

<h2> USER REFERRALS</h2>
<div class="table-responsive bg-white p-5 mr-3 ml-3">
<table class="table table-sm table-borderless table-striped" style="width:100%" id="rimplenetmyTable">

<thead>
 <tr>
    <th> User ID </th>
    <th> User Name </th>
 </tr>
</thead>
 
<tbody>

<?php
  if (is_array($all_referrals)) {
    foreach ($all_referrals as $refaree_id) {
?>
 
 <tr>
    <td><?php  echo $refaree_id; ?></td>
    <td><?php echo get_userdata($refaree_id)->user_login; ?></td>

   
 </tr>

 <?php
    }
    } else {
 ?>

  <tr>

  <td><?php  echo $all_referrals; ?></td>

  </tr>

<?php } ?>
 
</tbody>

<tfoot>
 <tr>
    <th> User ID </th>
    <th> User Name </th>
 </tr>
 </tfoot>

</table>
</div>



