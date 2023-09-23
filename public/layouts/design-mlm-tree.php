<?php
 global $current_user;
 wp_get_current_user();

$atts = shortcode_atts( array(

    'default' => 'empty',
    'user_id' => $current_user->ID,

), $atts);


$user_id = $atts['user_id'];
if (isset($_GET['rimplenet-view-mlm-tree'])) {
$mlm_matrix_post = get_post(sanitize_text_field($_GET['rimplenet-view-mlm-tree']));
}
else{
$mlm_matrix_post = get_post($atts['default']);
}

if(isset($_GET['rimplenet-user-id']) AND current_user_can('manage_options' ) ){
    
 $user_id = sanitize_text_field($_GET['rimplenet-user-id']);
 
}

if (!empty($mlm_matrix_post->ID)) {


$width = $mlm_matrix_post->width;
$depth = $mlm_matrix_post->depth;


$matrix_id = $mlm_matrix_post->ID;

//echo var_dump($this->getMatrixCapacityUsed($matrix_id,$user_id));
//echo var_dump($this->getMatrixCapacityFilledStatus($matrix_id,$user_id));
//echo var_dump($this->getNextMatrixVacantPostion($matrix_id, $user_id));

$all_matx_subs_arr = get_post_meta($matrix_id, 'matrix_subscriber');

if(!in_array( $user_id,$all_matx_subs_arr )){
    
    echo __('<p class="rimplenet-not-matrix-member">You are not a member of this matrix yet</p>');
    //$array_user_DL = $this->getFullDummySubsArr($matrix_id);
    
    
 }
 
 
 
    echo '<div class="clearfix"></div>';
    
   
    
    
    $show_empty_pos = 'yes';
    if($show_empty_pos=='yes'){

     $array_user_DL = $this->getFullDummyandFullRealSubsArr($matrix_id, $user_id);
     
    }else{
        $array_user_DL = $this->getSubscribersDownlineArr($matrix_id, $user_id);
    }
    
    //echo var_dump($this->getNextMatrixVacantPostion($matrix_id, $user_id));
    //echo var_dump($this->getMatrixCapacity($matrix_id));
    //echo var_dump($array_user_DL);
    //echo var_dump($this->getDepthPositionToParentinMatrix(25,1,$matrix_id,$array_user_DL ));
    //echo var_dump($this->getFullDummyandFullRealSubsArr($matrix_id, $user_id));
   //echo var_dump($this->getSubscribersDownlineArr($matrix_id, $user_id));
   
  //$subscribers_with_placement = get_post_meta( $matrix_id, 'matrix_subscriber_with_placement');
  //echo var_dump($subscribers_with_placement);
    
    $width_size = $width * 500;
    $depth_size = $depth * 5;
    $total_size = $width_size * $depth_size;
    $style = 'width:'.$total_size.'px;';
    
    echo '<div class="clearfix"></div>
    <div class="rimplenetmlmtree">
        <div class="rimplenetmlm-inner" style="'.$style.'">';
      $this->drawMLMtreeFromArray($this->parseMatrixTree($array_user_DL ));
    echo '</div>
    </div>
    <div class="clearfix"></div>';
    
 


?>




<?php

}

else{
    echo 'Invalid Matrix ID Supplied';
}


/*
$arr1 = array();
$subscribers = get_post_meta( $mlm_matrix_post->ID, 'matrix_subscriber');

$total_downline_subs = $this->getMatrixCapacity($mlm_matrix_post->ID) - 1;
$limit = $total_downline_subs/$width;

$arr1[$subscribers[0]] = NULL;
$offset = 1;

for ($parent=1; $parent <= $limit ; $parent++) {

    $intSubs = array_slice($subscribers, $offset, $width);

    foreach ($intSubs as $key => $sub) {
        $arr1[$sub] = $subscribers[$parent-1];
    }
   $offset = ($width * $parent)+1;

    
}

echo var_dump($arr1); // Displays array(13) { [1]=> NULL [2]=> string(1) "1" [3]=> string(1) "1" [4]=> string(1) "1" [5]=> string(1) "2" [6]=> string(1) "2" [7]=> string(1) "2" [8]=> string(1) "3" [9]=> string(1) "3" [10]=> string(1) "3" [11]=> string(1) "4" [12]=> string(1) "4" [13]=> string(1) "4" } 

*/


?>