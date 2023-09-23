<?php

/**
 * Class Draw Mlm
 */
class RimplenetMlmMatrix 
{
  
  function __construct()
  {
    
    add_shortcode('rimplenet-draw-mlm-tree', array($this, 'DrawMlmTree'));
    add_action('init', array($this,'update_user_matrix_completion_bonus'), 25, 0 );


  }

   public function update_user_matrix_completion_bonus()
   {

    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    $matrix_id_array = $this->getMLMMatrix();


    foreach ($matrix_id_array as $obj) {

      $matrix_subs = get_post_meta($obj,'matrix_subscriber');
     

    }

     
   }

   public function DrawMlmTree($atts) {
          

      ob_start();

      include plugin_dir_path( __FILE__ ) . 'layouts/design-mlm-tree.php';
       
      $output = ob_get_clean();

      return $output;
    
  }


  public function getMatrixCapacity($matrix_id='')
  {
    $mlm_matrix_post = get_post($matrix_id);
    $width = $mlm_matrix_post->width;
    $depth = $mlm_matrix_post->depth;
    
    $totalusers = $width;
    $sumtotal = $width;
    for ($i=2; $i <=$depth ; $i++) { 
     $sumtotal = $sumtotal * $width;
     $totalusers = $totalusers + $sumtotal;
    }

    $totalusers = $totalusers+1;


    return $totalusers;
    
  }

  public function getMatrixCapacityUsed($matrix_id='',$user_id='')
  {
    $subscribers = $this->getSubscribersDownlineArr($matrix_id, $user_id);
    $subscribers_count = count($subscribers);

    return $subscribers_count;
    
  }

  public function getMatrixCapacityFilledStatus($matrix_id='',$user_id='')
  {

    if ($this->getMatrixCapacityUsed($matrix_id, $user_id)>=$this->getMatrixCapacity($matrix_id)) {
      return true;
    }
    else{
      return false;

    }
    
  }


  public function getUsersDueforMatrixCompletionBonus($matrix_id='')
  {
    $subscribers = get_post_meta($matrix_id, 'matrix_subscriber');
    $subscribers_count = count($subscribers);

  }
  
function getFullDummySubsArr($matrix_id){
    $arr1 = array();
    
    $matrix_max_cap = $this->getMatrixCapacity($matrix_id);
    $subscribers = range(1,$matrix_max_cap);
    
    $width = get_post_meta($matrix_id, 'width',true);
     
     
     
    $total_downline_subs =  $matrix_max_cap - 1;
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
    
    return $arr1;

} 

function getFullDummySubscribersArr($matrix_id){
    $arr1 = array();
    
    $matrix_max_cap = $this->getMatrixCapacity($matrix_id);
    $subscribers = range(1,$matrix_max_cap);
    
    $width = get_post_meta($matrix_id, 'width',true);
     
     
    $total_downline_subs =  $matrix_max_cap - 1;
    $limit = $total_downline_subs/$width;
    
    $arr1[$subscribers[0]*-1] = NULL;
    $offset = 1;
    
    for ($parent=1; $parent <= $limit ; $parent++) {
        
        $intSubs = array_slice($subscribers, $offset, $width);
    
        foreach ($intSubs as $key => $sub) {
            $sub_neg = $sub * -1; //transform sub in negative
            $arr1[$sub_neg]  = $subscribers[$parent-1] * -1; //transform value in negative
        }
       $offset = ($width * $parent)+1;
    
        
    }
    
    
    return $arr1;

}

function getFullDummyandFullRealSubsArr($matrix_id,$user_id){
   
     $matrix_max_capacity = $this->getMatrixCapacity($matrix_id);
     
     $array_user_DL = $this->getSubscribersDownlineArr($matrix_id, $user_id);
     $FullDummySubs_Arr = $this->getFullDummySubscribersArr($matrix_id);
     $width = get_post_meta($matrix_id, 'width',true);
     
    if(count($array_user_DL)<$matrix_max_capacity){
        //Remove last values from Arr
     $all_parent_arr_values = array_values(array_keys($array_user_DL));
     $arr = $this->RECselectDownlineDummyandRealArr($matrix_id, $all_parent_arr_values, $array_user_DL);
     
    }
    else{
     $arr = $this->getSubscribersDownlineArr($matrix_id, $user_id); 
    }
    
    
    return $arr;

} 

public function RECselectDownlineDummyandRealArr($matrix_id, $AllParentArrValues, $generatedArrDL, $counter=1){
      
      $count_parent_arr = array_count_values($generatedArrDL);
      $mlm_matrix_post = get_post($matrix_id);
      
      $width = $mlm_matrix_post->width;
      $depth = $mlm_matrix_post->depth;
    
      $max_capacity = $this->getMatrixCapacity($matrix_id);
      
      $limit = ($max_capacity-1)/$width;
     if(count($generatedArrDL)>=$max_capacity){
        return $generatedArrDL;
     }
     
     
	  $cur_user_id = key($generatedArrDL);//Get first array value which is parent user id
    foreach($AllParentArrValues as $parent_id){
        
        $child_user_id = $parent_id; //in this case $child_user_id is ating $parent_id
        $depth_limit = $this->getDepthPositionToParentinMatrix($child_user_id,$cur_user_id,$matrix_id, $generatedArrDL);
       
       if($depth_limit<$depth){ // if this parent depth position to matrix start user is less than matrix depth, look for its children
        $parent_occurence = $count_parent_arr[$parent_id]; //check  & count how many children has this parent 
        if(empty($parent_occurence) ){
            $parent_occurence = 0;
        }
        
        if($parent_occurence<$width){
            $loop_frequency = $width - $parent_occurence;
            for ($x = 1; $x <= $loop_frequency; $x++) {
                
                 $counter_neg = $counter * -1;
                 $generatedArrDL[$counter_neg] =    $parent_id;
                 
                 $counter++;
            }
            
        }
       }
        
        if(count($generatedArrDL)>=$max_capacity){
        return $generatedArrDL;
        }
        
    }
    
    $AllParentArrValues= array_values(array_keys($generatedArrDL));;
    return $this->RECselectDownlineDummyandRealArr($matrix_id, $AllParentArrValues, $generatedArrDL,$counter);
}


function getDepthPositionToParentinMatrix($child_user_id,$parent_user_id,$matrix_id, $ArrDL, $depth_up=0 ){
      
      
      //var_dump($ArrDL);
      $mlm_matrix_post = get_post($matrix_id);
      
      $width = $mlm_matrix_post->width;
      $depth = $mlm_matrix_post->depth;
      
      
      if (array_key_exists($child_user_id,$ArrDL)){
        $depth_up++;
        $child_user_id = $ArrDL[$child_user_id];
        return $this->getDepthPositionToParentinMatrix($child_user_id,$parent_user_id,$matrix_id, $ArrDL, $depth_up);
      }
      
      else{
        return $depth_up-1;
      }
      
}
 
function getSubscribersDownlineArr($matrix_id, $user_id,$pointer=1){
    
    
  $subscribers_with_placement = get_post_meta( $matrix_id, 'matrix_subscriber_with_placement');
  $arr_subscribers_child_parent = array();
  foreach ($subscribers_with_placement as $subscriber) {

    $child_parent = explode( ':', $subscriber );

    $parent = $child_parent[1];
    $child = $child_parent[0];
    if ($child==$user_id) {
      $parent = NULL;
    }
    $arr_subscribers_child_parent[$child] = $parent;


  }

    $Allarr = $arr_subscribers_child_parent;
    $selectedArr = array("$user_id"=>NULL);
    
    $ret_Arr = $this->RECselectDownlineArr($matrix_id, $Allarr, $selectedArr);
    
    return $ret_Arr; 
    
    
}

public function RECselectDownlineArr($matrix_id, $Allarr, $selectedArr, $pointer=0){
    
      $mlm_matrix_post = get_post($matrix_id);
      
      $width = $mlm_matrix_post->width;
      $depth = $mlm_matrix_post->depth;
    
      $max_capacity = $this->getMatrixCapacity($matrix_id);
      $node_limit = ($max_capacity-1)/$width;
      
	   //$child_user_id = key(array_slice($selectedArr, -1, 1, true));//Get last array key
	   $cur_user_id = key($selectedArr);//Get first array value which is current user id
	   $checkArrDL = $selectedArr;
	   foreach($checkArrDL as $child_user_id=>$value){
	       $depth_limit = $this->getDepthPositionToParentinMatrix($child_user_id,$cur_user_id,$matrix_id, $selectedArr);
           if($depth_limit>$depth){
                unset($selectedArr[$child_user_id]);
                $max_depth_reached = "yes";
            }
        }
	  // echo $child_user_id." - ".$parent_user_id."<br>";
      $depth_limit = $this->getDepthPositionToParentinMatrix($child_user_id,$parent_user_id,$matrix_id, $selectedArr);
      //echo $depth_limit."<br>";
     if(count($selectedArr)>=$max_capacity or $pointer>=$depth+$node_limit or $max_depth_reached=="yes"){
		 
        return $selectedArr;
     }
    
    
    $array_worked = $selectedArr;
    
   
     $array_val = array_keys($array_worked);
     $user_id = $array_val[$pointer]; 
     
   
    
    $DL = array_keys($Allarr,$user_id);
    foreach($DL as $value){
      $selectedArr[$value] =    $user_id;
    }
    
    $pointer++;
    
    return $this->RECselectDownlineArr($matrix_id, $Allarr, $selectedArr,$pointer);
}

  public function parseMatrixTree($tree, $root = null) {
    $return = array();
    # Traverse the tree and search for direct children of the root
    foreach($tree as $child => $parent) {
        # A direct child is found
        if($parent == $root) {
            # Remove item from tree (we don't need to traverse this again)
            unset($tree[$child]);
            # Append the child into result array and parse its children
            $return[] = array(
                'parent_user_id' => $parent,
                'user_id' => $child,
                'name' => $child,
                'children' => $this->parseMatrixTree($tree, $child)
            );
        }
    }
    return empty($return) ? null : $return;    
  }


  public function drawMLMtreeFromArray($tree) {
      global $wp;
    if(!is_null($tree) && count($tree) > 0) {
        echo '<ul>';
        foreach($tree as $b) {
            //echo var_dump($tree);
            $node_user = get_user_by('id',$b['user_id']);
            if(!empty($node_user->ID)){
                
            $link = add_query_arg( array('rimplenet-user-id'=>$b['user_id'],), home_url(add_query_arg(array($_GET),$wp->request)) );
            
            echo '<li id="matrix_node_user_'.$b['user_id'].'"> 
               <a href="'.$link.'">'.$node_user->user_login.'</a>';
            }
            elseif($b['user_id']>0){
               echo '<li id="matrix_node_user_'.$b['user_id'].'"> 
               <a href="javascript:void(0)"> Not Existed / Deleted User </a>';  
            }
            else{
               echo '<li id="matrix_node_user_'.$b['user_id'].'"> 
               <a href="javascript:void(0)"> Not Available </a>';  
            }
            
               
               $this->drawMLMtreeFromArray($b['children']);
               
            echo '</li>';
        }
        echo '</ul>';
    }
    
  }

  
  public function getNextMatrixVacantParentIdfromArray($arr,$matrix_width) {
      
    if(!is_null($arr) && count($arr) > 0) {
        
        foreach($arr as $b) {
            
            echo $b['user_id'].' => '.$b['parent_user_id'].' - '.count($b['children']);
            echo '<br>';
            
            if(count($b['children'])<$matrix_width){
                //return $b['parent_user_id'];
            }
            
               
            $this->getNextMatrixVacantParentIdfromArray($b['children'],$matrix_width);
               
        }
        
     }

  }
  
  
  
  
    
  public function parseMatrixVacantParentIdfromArray($arr,$matrix_width) {
      
      
    if(!is_null($arr) && count($arr) > 0) {
        
        foreach($arr as $b) {
            
            echo $b['user_id'].' => '.$b['parent_user_id'].' -- '.count($b['children']);
            echo '<br>';
            
            if(count($b['children'])<$matrix_width){
                //return $b['parent_user_id'];
            }
            
               
            $this->parseMatrixVacantParentIdfromArray($b['children'],$matrix_width);
               
        }
        
     }


  }
  
  
  

 
  public function getNextMatrixVacantPostion($matrix_id, $user_id) {
    
    $user_placement_method_in_matrix = get_post_meta($matrix_id,'user_placement_method_in_matrix',true);
    $matrix_width = get_post_meta($matrix_id,'width',true);
    
    if($user_placement_method_in_matrix=='first_come_first_served'){  
        $array_user_DL = get_post_meta( $matrix_id, 'matrix_subscriber');
        if(empty($array_user_DL)){return 0;}
        foreach($array_user_DL as $single_user_id){
            
           $DL_user_parsed  = $this->getSubscribersDownlineArr($matrix_id, $single_user_id);
           $DL_user_parsed  = $this->parseMatrixTree($DL_user_parsed)[0];
           
           $count_DL = count($DL_user_parsed['children']);
           if($count_DL<$matrix_width){
              return $single_user_id; 
           }
           
         }
     }
     else{//placement here is referral_based_during_registration
        $subscribers_with_placement = get_post_meta( $matrix_id, 'matrix_subscriber_with_placement');
        if(empty($subscribers_with_placement)){return 0;}
        
        $array_user_DL = $this->getSubscribersDownlineArr($matrix_id, $user_id);
        foreach($array_user_DL as $DL_single_user_id=>$parent_id){
            
           $DL_user_parsed  = $this->getSubscribersDownlineArr($matrix_id, $DL_single_user_id);
           $DL_user_parsed  = $this->parseMatrixTree($DL_user_parsed)[0];
           
           //echo 'UserID:'.$DL_user_parsed['user_id'] . '=>Parent:'.$parent_id.'-- count:'.count($DL_user_parsed['children']);
           //echo '<br><br>';
           
           $count_DL = count($DL_user_parsed['children']);
           
           if($count_DL<$matrix_width){
              return $DL_single_user_id; 
           }
           
         }
     }
     
        
      

    

    

  }



  public function getMLMMatrix($type='')
    {

    $packages_id_array = get_posts(
      array(
      'post_type' => 'rimplenettransaction', // get all posts.
      'numberposts'   => -1, // get all posts.
      'tax_query'     => array(
        array(
          'taxonomy' => 'rimplenettransaction_type',
          'field'    => 'name',
          'terms'    => 'RIMPLENET MLM MATRIX',
        ),
        ),
      'fields'        => 'ids', // Only get post IDs
      )
     );

    wp_reset_postdata();
    return $packages_id_array;
    }



}

$RimplenetMlmMatrix  = new RimplenetMlmMatrix();