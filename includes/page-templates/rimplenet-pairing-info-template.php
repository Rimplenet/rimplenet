<?php
 global $current_user;
 wp_get_current_user();

$atts = shortcode_atts( array(

    'default' => 'empty',
    'user_id' => $current_user->ID,

), $atts);


$user_id = $atts['user_id'];
if (isset($_GET['rimplenet-view-pairing'])) {
$mlm_pairing_post = get_post(sanitize_text_field($_GET['rimplenet-view-pairing']));
}
else{
$mlm_pairing_post = get_post($atts['default']);
}

if(isset($_GET['rimplenet-user-id']) AND current_user_can('manage_options' ) ){
    
 $user_id = $_GET['rimplenet-user-id'];
 
}

if (!empty($mlm_pairing_post->ID)) {


$width = $mlm_pairing_post->width;
$depth = $mlm_pairing_post->depth;


$pairing_id = $mlm_pairing_post->ID;

$all_pairing_subs_arr = get_post_meta($pairing_id, 'pairing_subscriber');
$all_pairing_completed_subs_arr = get_post_meta($pairing_id, 'pairing_completers');


 if(in_array($user_id, $all_pairing_completed_subs_arr)){
  $pairing_info =  __('<p class="not_pairing_member">'.$mlm_pairing_post->info_after_pairing_complete.'</p>');    
 }
  elseif(in_array( $user_id, $all_pairing_subs_arr )){
    $pairing_info =  __('<p class="not_pairing_member">'.$mlm_pairing_post->info_inside_pairing_or_before_pairing_complete.'</p>'); 
 }
 elseif(!in_array( $user_id, $all_pairing_subs_arr )){
    
     $pairing_info =  __('<p class="not_pairing_member">'.$mlm_pairing_post->info_before_pairing_entry.'</p>');
    
 }
 else{
    $pairing_info =  __('<p class="not_pairing_member">Unknown Pairing Error</p>');  
 }
 
   $rimplenetPairing = new RimplenetMlmPairing();
 
 $array_user_DL = $rimplenetPairing->getPairersIncoming($pairing_id, $user_id);
 $array_user_UPL = $rimplenetPairing->getSubscribersUpline($pairing_id, $user_id);
 
 //echo var_dump($array_user_UPL);

?>

<?php get_header(); ?>

<div class="clearfix"></div><br>
<div class='rimplenetmlm' style="max-width:600px;margin:auto;">
<div class="rimplenet-pairing-form">
 <div class="rimplenet-pairing-info-div-1" id="rimplenet-pairing-info-div-1">
  <div class="rimplenet-pairing-info-div-2" id="rimplenet-pairing-info-div-2">

        <span>Name</span><br>
        <strong> <?php echo $cur; ?> <?php echo $mlm_pairing_post->post_title; ?></strong>
        <hr>

       <span>Description</span><br>
       <strong> <?php echo $mlm_pairing_post->post_content; ?> </strong>
       
       <!--
        <hr>
       <span>Amount</span><br>
       <strong> <?php echo $mlm_pairing_post->price; ?></strong>
       -->
       
        <hr>

       <strong>Extra Info</strong><br>
       <?php echo do_shortcode(nl2br($pairing_info)); ?>

   </div>
 </div>



<?php

}

else{
    echo 'Invalid Pairing ID Supplied';
}



function numToOrdinalWord($num)
		{
			$first_word = array('eth','First','Second','Third','Fouth','Fifth','Sixth','Seventh','Eighth','Ninth','Tenth','Elevents','Twelfth','Thirteenth','Fourteenth','Fifteenth','Sixteenth','Seventeenth','Eighteenth','Nineteenth','Twentieth');
			$second_word =array('','','Twenty','Thirty','Forty','Fifty');

			if($num <= 20)
				return $first_word[$num];

			$first_num = substr($num,-1,1);
			$second_num = substr($num,-2,1);

			return $string = str_replace('y-eth','ieth',$second_word[$second_num].'-'.$first_word[$first_num]);
		}
?>

 </div>
</div>
<div class="clearfix"></div><br>

<?php get_footer(); ?>