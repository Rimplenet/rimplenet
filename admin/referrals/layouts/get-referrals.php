<?php
global $current_user,$wp;
wp_get_current_user();

$user_id = sanitize_text_field($_GET['rimplenet_user_id']);

$referral_obj = new RimplenetGetreferrals();
$all_referrals = $referral_obj->getreferrals(['user_id' => $user_id])['data'];

?>


<style>
  @media screen and (max-width: 600px) {	

      table {width:100%;}

      /* thead {display: none;} */

      tr:nth-of-type(2n) {background-color: inherit;}

      tr td:first-child {background: #f0f0f0; font-weight:bold;font-size:1.3em;}

      /* tbody td {display: block;  text-align:center;} */

      tbody td:before {

        /* content: attr(data-th); */

        display: block;

        text-align:center; 

      }

}
</style>

<h2> USER REFERRALS</h2>
<table class="wp-list-table widefat fixed striped posts" >

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

