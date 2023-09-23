<?php 
global $post, $wpdb,$current_user;
wp_get_current_user();
$userinfo = wp_get_current_user();
$user_id = $userinfo->ID; 

?>

<?php get_header(); ?>



<div class="clearfix"></div><br>
<div class='rimplenetmlm'>
 <div class="container">
    
   <?php
    $content = $post->post_content;
    echo apply_filters('the_content', $content);
   ?>
 </div>
</div>


<div class="clearfix"></div><br>
<?php get_footer(); ?>