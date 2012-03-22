<?php
$review=bp_get_the_review();

?>   
<li id="review-<?php echo $review->comment_ID;?>">
<div class="reviews">
   <div class="item-avatar">
	<a href="<?php echo bp_core_get_user_domain($review->user_id); ?>" >
            <?php echo bp_core_fetch_avatar( array('type'=>'thumb','item_id'=>$review->user_id )); ?>
	</a>
    </div>

    <div class="review-content">
	<?php  if($review->comment_approved==0):?>
            <span class='pending-notice'><?php bp_the_review_pending_notice();?></span>
        <?php endif;?>
        <div class="activity-header">
            
            <?php printf(__('%s on <span class="date-time">%s</span>:','reviews'),  bp_core_get_userlink($review->user_id), get_date_from_gmt($review->comment_date_gmt,'F j, Y')) ;  ?>
	</div>
    

        <div class='review-entry'>
            <?php bp_the_review_content();?>
        </div>
            
        <div class="review-action">
            <?php if(review_can_delete()):?>
                <?php bp_review_delete_link();?>
            <?php endif;?>
            <?php if(review_can_approve()):?>
                 <?php bp_review_approve_unapprove_link()?>
            <?php endif;?>
        </div>
      </div>    
</div>
</li>