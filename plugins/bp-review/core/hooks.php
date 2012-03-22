<?php


add_filter( 'get_the_review_content','bp_reviews_filter_kses');
add_filter( 'get_the_review_content','make_clickable',9);
add_filter( 'get_the_review_content',        'convert_chars' );
add_filter( 'get_the_review_content', 'stripslashes_deep' );
add_filter( 'get_the_review_content',        'wptexturize' );
add_filter( 'get_the_review_content', 'force_balance_tags' );
add_filter( 'get_the_review_content','wpautop');


function bp_reviews_filter_kses($content){
   
    if(function_exists('bp_activity_filter_kses'))
        return bp_activity_filter_kses($content);
    //do not bother if the activity module is active, let us reuse it
    global $allowedtags;

	$review_allowedtags = $allowedtags;
	$review_allowedtags['span']          = array();
	$review_allowedtags['span']['class'] = array();
	$review_allowedtags['div']           = array();
	$review_allowedtags['div']['class']  = array();
	$review_allowedtags['div']['id']     = array();
	$review_allowedtags['a']['class']    = array();
	$review_allowedtags['a']['id']       = array();
	$review_allowedtags['a']['rel']      = array();
	$review_allowedtags['img']           = array();
	$review_allowedtags['img']['src']    = array();
	$review_allowedtags['img']['alt']    = array();
	$review_allowedtags['img']['class']  = array();
	$review_allowedtags['img']['width']  = array();
	$review_allowedtags['img']['height'] = array();
	$review_allowedtags['img']['class']  = array();
	$review_allowedtags['img']['id']     = array();
	$review_allowedtags['img']['title']  = array();
	$review_allowedtags['code']          = array();

	$review_allowedtags = apply_filters( 'bp_review_allowed_tags', $review_allowedtags );
	return wp_kses( $content, $review_allowedtags );
}


add_action('admin_init','review_add_remove_comment_widget');
function review_add_remove_comment_widget(){
 remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' ); 
 
}
add_action('wp_dashboard_setup','review_add_recent_comments_widget');
function review_add_recent_comments_widget(){
    
 $recent_comments_title = __( 'Commenti recenti' );
wp_add_dashboard_widget( 'dashboard_recent1_comments', $recent_comments_title, 'review_fix_dashboard_recent_comments', 'wp_dashboard_recent_comments_control' );

}

function review_fix_dashboard_recent_comments() {
	global $wpdb;

	if ( current_user_can('edit_posts') )
		$allowed_states = array('0', '1');
	else
		$allowed_states = array('1');

	// Select all comment types and filter out spam later for better query performance.
	$comments = array();
	$start = 0;

	$widgets = get_option( 'dashboard_widget_options' );
	$total_items = isset( $widgets['dashboard_recent_comments'] ) && isset( $widgets['dashboard_recent_comments']['items'] )
		? absint( $widgets['dashboard_recent_comments']['items'] ) : 5;

	while ( count( $comments ) < $total_items && $possible = $wpdb->get_results( "SELECT * FROM $wpdb->comments c LEFT JOIN $wpdb->posts p ON c.comment_post_ID = p.ID WHERE p.post_status != 'trash' AND p.post_type!='ureviews' ORDER BY c.comment_date_gmt DESC LIMIT $start, 50" ) ) {

		foreach ( $possible as $comment ) {
			if ( count( $comments ) >= $total_items )
				break;
			if ( in_array( $comment->comment_approved, $allowed_states ) && current_user_can( 'read_post', $comment->comment_post_ID ) )
				$comments[] = $comment;
		}

		$start = $start + 50;
	}

	if ( $comments ) :
?>

		<div id="the-comment-list" class="list:comment">
<?php
		foreach ( $comments as $comment )
			_wp_dashboard_recent_comments_row( $comment );
?>

		</div>

<?php
		if ( current_user_can('edit_posts') ) { ?>
			<?php _get_list_table('WP_Comments_List_Table')->views(); ?>
<?php	}

		wp_comment_reply( -1, false, 'dashboard', false );
		wp_comment_trashnotice();

	else :
?>

	<p><?php _e( 'Nessun commento ancora.' ); ?></p>

<?php
	endif; // $comments;
}

add_action('load-edit-comments.php','review_fix_comment_issues');

function review_fix_comment_issues(){
    add_filter('comments_clauses','review_fix_clauses');
    add_filter('wp_count_comments','review_fix_comment_count',10,2);
    
}
function review_fix_clauses($clauses){
    global $wpdb;
    $clauses['join']="JOIN {$wpdb->posts} ON {$wpdb->posts}.ID = {$wpdb->comments}.comment_post_ID";
    $clauses['where']=$clauses['where']." AND {$wpdb->posts}.post_type!='ureviews'";
   
    return $clauses;
}

function review_fix_comment_count($count,$post_id){
    
    if($post_id)
    return $count;
    global $wpdb;
    $where='';
    $count = $wpdb->get_results( "SELECT comment_approved, COUNT( * ) AS num_comments FROM {$wpdb->comments}  JOIN {$wpdb->posts} ON {$wpdb->posts}.ID = {$wpdb->comments}.comment_post_ID WHERE {$wpdb->posts}.post_type!='ureviews' GROUP BY comment_approved", ARRAY_A );
   
	$total = 0;
	$approved = array('0' => 'moderated', '1' => 'approved', 'spam' => 'spam', 'trash' => 'trash', 'post-trashed' => 'post-trashed');
	foreach ( (array) $count as $row ) {
		// Don't count post-trashed toward totals
		if ( 'post-trashed' != $row['comment_approved'] && 'trash' != $row['comment_approved'] )
			$total += $row['num_comments'];
		if ( isset( $approved[$row['comment_approved']] ) )
			$stats[$approved[$row['comment_approved']]] = $row['num_comments'];
	}

	$stats['total_comments'] = $total;
	foreach ( $approved as $key ) {
		if ( empty($stats[$key]) )
			$stats[$key] = 0;
	}

	$stats = (object) $stats;
	wp_cache_set("comments-{$post_id}", $stats, 'counts');

	return $stats;
}


  function bp_reviews_format_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {
	global $bp;
    
             $review_id      = $item_id;
	     $poster_user_id   = $secondary_item_id;
             $user_fullname = bp_core_get_user_displayname( $poster_user_id );
	switch ( $action ) {
		case 'new_review':
			
			$review_link  = bp_loggedin_user_domain() . $bp->reviews->slug . '/pending/';
			$review_title = __('Nuove Review','reviews');

			if ( (int)$total_items > 1 ) {
				$text_title = sprintf( __( 'Hai riveduto %1$d nuove reviews', 'reviews' ), (int)$total_items );
                                
                                $notifications=bp_reviews_get_notifications(bp_loggedin_user_id());
                                $text=bp_review_build_notification($notifications);
                                
				
			} else {
				
				$text_title =  sprintf( __( '%1$s ti ha scritto una nuova review', 'reviews' ), $user_fullname );
				
			}
		break;
                
                case 'approved_review':
                    
                       
			$review_link  = bp_core_get_user_domain($poster_user_id) . $bp->reviews->slug . '/my-reviews/';
			$review_title = __('Review approvate','reviews');

			if ( (int)$total_items > 1 ) {
				$text_title = sprintf( __( 'Congratulazioni! %1$d delle tue reviews sono state approvate', 'reviews' ), (int)$total_items );
                                
                                $notifications=bp_reviews_get_notifications(bp_loggedin_user_id());
                                $text=bp_review_build_notification($notifications);
                                
				
			} else {
				
				$text_title =  sprintf( __( '%1$s ha approvato la tua review', 'reviews' ), $user_fullname );
				
			}
				
			
                 break;   
	}

	if ( 'string' == $format ) {
		$return =  '<a href="' . $review_link. '" title="' . $review_title . '">' . $text_title . '</a>'.( (int)$total_items>1?$text:'');
	} else {
		$return =  array(
			'text' => $text,
			'link' => $review_link
		);
	}

	do_action( 'reviews_format_notifications', $action, $item_id, $secondary_item_id, $total_items );

	return $return;
}

function bp_review_build_notification($notifications){
    global $bp;
    $output="<ul>";
    foreach($notifications as $notification){
       $output.="<li>";
       $review_id  = $notification->item_id;
       $poster_user_id   = $notification->secondary_item_id;
       $user_fullname = bp_core_get_user_displayname( $poster_user_id );
       if($notification->component_action=='new_review'){
           $review_link  = bp_loggedin_user_domain() . $bp->reviews->slug . '/pending/';
           $user_fullname = bp_core_get_user_displayname( $poster_user_id );
	   $text =  sprintf( __( '%1$s ti ha scritto una nuova review', 'reviews' ), $user_fullname );
           
       } 
       else if($notification->component_action=='approved_review'){
        $review_link  = bp_core_get_user_domain($poster_user_id) . $bp->reviews->slug . '/my-reviews/?tid='.$review_id;
        $review_title = __('Reviews approvate','reviews');

        
	$text =  sprintf( __( '%1$s ha approvato la tua review', 'reviews' ), $user_fullname );
       
       }
     $output.="<a href='".$review_link."'>".$text."</a>"  ;
     $output.="</li>"  ;
    }
    
    $output.="</ul>";
    return $output;
}
function bp_reviews_get_notifications($user_id){
    		
    global $bp, $wpdb;
    return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$bp->core->table_name_notifications} WHERE component_name=%s AND user_id = %d AND is_new = 1", $bp->reviews->id,$user_id ) );
	
}

function bp_reviews_delete_between_user_by_type($user_id, $poster_id, $component_name,$component_action=false ) {
		global $bp, $wpdb;
                if(bp_is_my_profile())
                    return;
		if ( $component_action )
			$component_action_sql = $wpdb->prepare( "AND component_action = %s", $component_action );
		else
			$component_action_sql = '';
                
                if ( $poster_id )
			$secondary_item_sql = $wpdb->prepare( "AND secondary_item_id = %d", $poster_id );
		else
			$secondary_item_sql = '';
		
               
		return $wpdb->query( $wpdb->prepare( "DELETE FROM {$bp->core->table_name_notifications} WHERE user_id = %d AND component_name = %s {$component_action_sql} {$secondary_item_sql}", $user_id, $component_name ) );
	}
        
function bp_reviews_screen_notification_settings() {
	global $bp;

	if ( !$new_review = bp_get_user_meta( $bp->displayed_user->id, 'notification_reviews_new_review', true ) )
		$new_review = 'yes';

	if ( !$approved_review = bp_get_user_meta( $bp->displayed_user->id, 'notification_reviews_approved_review', true ) )
		$approved_review = 'yes'; ?>

	<table class="notification-settings" id="activity-notification-settings">
		<thead>
			<tr>
				<th class="icon">&nbsp;</th>
				<th class="title"><?php _e( 'Reviews', 'reviews' ) ?></th>
				<th class="yes"><?php _e( 'Sì', 'reviews' ) ?></th>
				<th class="no"><?php _e( 'No', 'reviews' )?></th>
			</tr>
		</thead>

		<tbody>
			<tr id="review-notification-settings-new-review">
				<td>&nbsp;</td>
				<td><?php _e( 'Un utente ti ha scritto una review', 'reviews' ) ?></td>
				<td class="yes"><input type="radio" name="notifications[notification_reviews_new_review]" value="yes" <?php checked( $new_review, 'yes', true ) ?>/></td>
				<td class="no"><input type="radio" name="notifications[notification_reviews_new_review]" value="no" <?php checked( $new_review, 'no', true ) ?>/></td>
			</tr>
			<tr id="review-notification-settings-approved-review">
				<td>&nbsp;</td>
				<td><?php _e( "Quando un utente approva la tua review", 'reviews' ) ?></td>
				<td class="yes"><input type="radio" name="notifications[notification_reviews_approved_review]" value="yes" <?php checked( $approved_review, 'yes', true ) ?>/></td>
				<td class="no"><input type="radio" name="notifications[notification_reviews_approved_review]" value="no" <?php checked( $approved_review, 'no', true ) ?>/></td>
			</tr>

			<?php do_action( 'bp_activity_screen_notification_settings' ) ?>
		</tbody>
	</table>

<?php
}
add_action( 'bp_notification_settings', 'bp_reviews_screen_notification_settings', 10 );
        
?>