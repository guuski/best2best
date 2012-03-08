<?php

function bp_is_review_component(){
    global $bp;
    return bp_is_current_component($bp->reviews->slug);
}

class BP_Review_Template{
   var $reviews;
   var $current_review=-1;
   var $review_count;
   var $review;
   var $pag_page=10;
   var $page_num=0;
   var $mapper=null;
   function __construct(BPReviewMapper $review,$type,$page_number, $per_page, $max,$include){
       
       global $bp;
		
        $this->pag_page  = !empty( $_REQUEST['tpage'] ) ? intval( $_REQUEST['tpage'] ) : (int)$page_number;
        $this->pag_num   = !empty( $_REQUEST['num'] )   ? intval( $_REQUEST['num'] )   : (int)$per_page;
        $this->type      = $type;//approved,pending etc
         $this->mapper=$review;
        if($include)
            $this->reviews=$review->get_included($include);
        else if($type=='approved')
             $this->reviews=$review->get_approved($this->pag_page,$this->page_num);
        else if($type=='pending')
            $this->reviews=$review->get_pending($this->pag_page,$this->page_num);
        else if($type=='my-writeup')
            $this->reviews=$review->get_by_user($bp->loggedin_user->id,$this->pag_page,$this->page_num);
        
       
        if ( !$max || $max >= (int)$this->reviews['total'] )
			$this->total_reviews_count = (int)$this->reviews['total'];
		else
			$this->total_reviews_count = (int)$max;
               //   print_r($this->reviews);
	$this->reviews = $this->reviews['reviews'];
       
       if ( $max ) {
			if ( $max >= count( $this->reviews ) ) {
				$this->review_count = count( $this->reviews );
			} else {
				$this->review_count = (int)$max;
			}
		} else {
			$this->review_count = count( $this->reviews );
		}

		if ( (int)$this->total_reviews_count && (int)$this->pag_num ) {
			$this->pag_links = paginate_links( array(
				'base'      => add_query_arg( 'tpage', '%#%' ),
				'format'    => '',
				'total'     => ceil( (int)$this->total_reviews_count / (int)$this->pag_num ),
				'current'   => (int) $this->pag_page,
				'prev_text' => '&larr;',
				'next_text' => '&rarr;',
				'mid_size'   => 1
			) );
		}
	}

	function has_reviews() {
		if ( $this->review_count )
			return true;

		return false;
	}

	function next_review() {
		$this->current_review++;
		$this->review = $this->reviews[$this->current_review];
              
		return $this->review;
	}

	function rewind_reviews() {
		$this->current_review = -1;
		if ( $this->review_count > 0 ) {
			$this->review = $this->reviews[0];
		}
	}

	function reviews() {
		if ( $this->current_review + 1 < $this->review_count ) {
			return true;
		} elseif ( $this->current_review + 1 == $this->review_count ) {
			do_action('review_loop_end');
			// Do some cleaning up after the loop
			$this->rewind_reviews();
		}

		$this->in_the_loop = false;
		return false;
	}

	function the_review() {
		
		$this->in_the_loop = true;
		$this->review = $this->next_review();

		if ( 0 == $this->current_review ) // loop has just started
			do_action('review_loop_start');
	}    
   
}


/*template tags*/

function bp_has_reviews($args = ''){
    global $bp, $reviews_template;
   
    
    
     
    switch(bp_current_action()){
        
        case 'my-reviews':
        default:
            $type='approved';
            break;
        case 'pending':
            $type='pending';
            break;
        case 'my-writeup':
            $type='my_writeup';
            break;
        
    }
       
	$user_id      = $bp->displayed_user->id;
	$page         = 1;
        $include=false;
        $defaults = array(
                        'type'            => $type,
                        'page'            => $page,
                        'per_page'        => 20,
                        'max'             => false,
                        'user_id'         => $user_id,
                        'include'         =>$include
                       
                );

	$r = wp_parse_args( $args, $defaults );
	extract( $r );

	

	
	if ( !empty( $max ) && ( $per_page > $max ) )
		$per_page = $max;

	
        $review= new BPUserReviewMapper();
        $review=apply_filters('bp_reviews_mapper',$review,$r);
//::get_post_id();//get it from current user
      
	$reviews_template = new BP_Review_Template($review, $type, $page, $per_page, $max,$include);
      
	return apply_filters( 'bp_has_reviews', $reviews_template->has_reviews(), $reviews_template );
}

function bp_the_review() {
	global $reviews_template;
	return $reviews_template->the_review();
}

function bp_reviews() {
	global $reviews_template;
        
	return $reviews_template->reviews();
}

function bp_get_the_review(){
     global $reviews_template;
    return $reviews_template->review;
}

function bp_the_review_content(){
    echo bp_get_the_review_content();
}

function bp_get_the_review_content($review=false){
     global $reviews_template;
     if(!$review)
         $review= $reviews_template->review;
     return apply_filters('get_the_review_content',$review->comment_content,$review);
}

function bp_the_review_pending_notice(){
    echo bp_get_the_review_pending_notice();
}

function bp_get_the_review_pending_notice($review=false){
    global $reviews_template;
    if(!$review)
        $review=$reviews_template->review;
    if(bp_is_my_profile())
        $you_or_user=__('your','reviews');
    else
        $you_or_user=__("user's",'reviews');
    return sprintf(__('Before it becomes publicly visible, It is waiting for %s approval','reviews'),$you_or_user);
    
}
function bp_reviews_pagination_count() {
	echo bp_get_reviews_pagination_count();
}
	function bp_get_reviews_pagination_count() {
		global $bp, $reviews_template;

		if ( empty( $reviews_template->type ) )
			$reviews_template->type = '';

		$start_num = intval( ( $reviews_template->pag_page - 1 ) * $reviews_template->pag_num ) + 1;
		$from_num  = bp_core_number_format( $start_num );
		$to_num    = bp_core_number_format( ( $start_num + ( $reviews_template->pag_num - 1 ) > $reviews_template->total_reviews_count ) ? $reviews_template->total_reviews_count : $start_num + ( $reviews_template->pag_num - 1 ) );
		$total     = bp_core_number_format( $reviews_template->total_reviews_count );

		
		$pag = sprintf( __( 'Viewing review %1$s to %2$s (of %3$s active reviews)', 'reviews' ), $from_num, $to_num, $total );
		
		return apply_filters( 'bp_reviews_pagination_count', $pag );
	}

function bp_reviews_pagination_links() {
	echo bp_get_reviews_pagination_links();
}
	function bp_get_reviews_pagination_links() {
		global $reviews_template;

		return apply_filters( 'bp_get_reviews_pagination_links', $reviews_template->pag_links );
	}

        function bp_review_approve_unapprove_link($review=false){
            echo bp_get_review_approve_unapprove_link($review);
        }
        
        function bp_get_review_approve_unapprove_link($review=false){
            global $reviews_template;
            if(!$review)
                $review=$reviews_template->review;
            if($review->comment_approved==1){
                    $label=__('Hide','reviews');
                    $action='hide';
            }    
            else{
                $label=__('Approve','reviews');
                $action='approve';
            }
            $link = wp_nonce_url( $reviews_template->mapper->get_component_link() . '/approve-unapprove/?tid=' . $review->comment_ID."&action=".$action, 'bp_review_approve_link' );

	   return apply_filters( 'bp_review_approve_unapprove_link', sprintf(__("<a href='%s' class='review-update'>%s</a>",'reviews'),$link,$label ));
     
        }
        
        function bp_review_delete_link($review=false){
            echo  bp_get_review_delete_link($review);
        }
        function bp_get_review_delete_link($review=false){
            global $reviews_template;
            if(!$review)
                $review=$reviews_template->review;
            
            $link = wp_nonce_url( $reviews_template->mapper->get_component_link() . '/delete/?tid=' . $review->comment_ID, 'bp_review_delete_link' );

	   return apply_filters( 'bp_review_delete_link', sprintf(__("<a href='%s' class='review-delete'>Delete</a>",'reviews'),$link ));
        }
/*form*/
function bp_reviews_post_form_action(){
    global $bp;
   echo apply_filters('bp_review_post_form_action',  $bp->displayed_user->domain.$bp->reviews->root_slug."/create/"); 
}

//load template from theme and if not found, load it from plugin
function bp_reviews_load_template($template){
 if(locate_template(array($template),false))
        locate_template(array($template),true);
    else
        include(BP_REVIEWS_PLUGIN_DIR.$template);
}
function bp_reviews_post_form(){
   bp_reviews_load_template('reviews/post-form.php') ;
   
}
//can user write review
function review_current_user_can_write(){
   $can_write=false;
   
    if(is_user_logged_in()&&!bp_is_my_profile()&&  friends_check_friendship(bp_displayed_user_id(), bp_loggedin_user_id()))
        $can_write=true;
    
    return apply_filters('bp_reviews_can_user_write',$can_write);
}

function review_can_approve($review=false){
   global $reviews_template;
   if(!$review)
       $review=$reviews_template->review;
      
   return apply_filters('review_can_approve',BPReviewUserPermissions::can_approve($review,$reviews_template->mapper));
            
}

function review_can_delete($review=false){
   global $reviews_template;
   if(!$review)
       $review=$reviews_template->review;
      
   return apply_filters('review_can_delete',BPReviewUserPermissions::can_delete($review,$reviews_template->mapper));
            
}
?>