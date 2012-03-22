<?php


abstract class BPReviewMapper 
{
    var $object_id;
    var $post_id;
        
	abstract function get_post_id();
	abstract function get_post_type(); 
	abstract function associate_review_page($object_id='');
	abstract function get_component_link();
   
	function get_post()
	{
		$post_id=$this->get_post_id();
		return get_post($post_id);
	}
         
	abstract function get_total_count() ;
   
   function get_included($review_ids)
   {
       if(is_array($review_ids))
           $review_ids=join (',', $review_ids);
       
        $review_ids="(".$review_ids.")";
		global $bp,$wpdb;
        
		$sql=$wpdb->prepare("SELECT * FROM {$wpdb->comments}  WHERE comment_ID IN {$review_ids}");
       
          $reviews=$wpdb->get_results($sql);
       
          
          return array('reviews'=>$reviews,'total'=>count($reviews));
       
   }
   function get_approved($pag,$limit)
   {
       $reviews=array();
       $reviews['reviews']=$this->get_comment_by_status(1,$pag,$limit);
       $reviews['total']=$this->get_comment_count_by_status(1);
      
       return $reviews;
   }
   
   function get_pending($pag,$limit){
       
       $reviews=array();
       $reviews['reviews']=$this->get_comment_by_status(0,$pag,$limit);
       $reviews['total']=$this->get_comment_count_by_status(0);
       return $reviews;
   }
   
   function get_by_user($user_id,$page=1,$limit=0){
        global $wpdb;
          $pag_sql='';
          $post_type=$this->get_post_type();
          if ( $limit && $page ) {
			$pag_sql = $wpdb->prepare( " LIMIT %d, %d", intval( ( $page - 1 ) * $limit), intval( $limit ) );
		}
                
          $sql=$wpdb->prepare("SELECT * from {$wpdb->posts} p, {$wpdb->comments} c LEFT JOIN ON p.ID=c.comment_post_ID WHERE p.post_type=%s AND c.user_id=%d {$pag_sql}",$post_type,$user_id);
       
          $reviews=$wpdb->get_results($sql);
       
          $count=$wpdb->get_results($wpdb->prepare("SELECT count('*') FROM {$wpdb->posts} p, {$wpdb->comments} c LEFT JOIN ON p.ID=c.comment_post_ID WHERE p.post_type=%s AND c.user_id=%d ",$post_type,$user_id));
       
          
          return array('reviews'=>$reviews,'total'=>$count);
       
   }    
   
  
   function get_comment_by_status($is_approved,$page=1,$limit=20){
          global $wpdb;
          $pag_sql='';
          $post_id=$this->get_post_id();
          if ( $limit && $page ) {
			$pag_sql = $wpdb->prepare( " LIMIT %d, %d", intval( ( $page - 1 ) * $limit), intval( $limit ) );
		}
                
           $or_sql='';
          
          if(is_user_logged_in())
              $or_sql=$wpdb->prepare('OR user_id=%d',  bp_loggedin_user_id ());
          $sql=$wpdb->prepare("SELECT * from {$wpdb->comments} WHERE comment_post_ID=%d AND (comment_approved=%d {$or_sql} ){$pag_sql} ORDER BY comment_date_gmt DESC",$post_id,$is_approved);
       
          $reviews=$wpdb->get_results($sql);
       
          return $reviews;
   }
   
   function get_comment_count_by_status($is_approved){
        global $wpdb;  
        
        $post_id=$this->get_post_id();
        $or_sql='';
          //logged in user should be able to shee his review, even if it is pending      
        if(is_user_logged_in())
             $or_sql=$wpdb->prepare('OR user_id=%d',  bp_loggedin_user_id ());
        
        $sql=$wpdb->prepare("SELECT count('*') from {$wpdb->comments} WHERE comment_post_ID=%d AND (comment_approved=%d {$or_sql}) ",$post_id,$is_approved);
       
            $reviews=$wpdb->get_var($sql);
       return $reviews;
   }
   
      function get_all_comment_count(){
        global $wpdb;  
        
        $post_id=$this->get_post_id();
        
        $sql=$wpdb->prepare("SELECT count('*') from {$wpdb->comments} WHERE comment_post_ID=%d ",$post_id);
       
            $reviews=$wpdb->get_var($sql);
       return $reviews;
   }
   function get_comment($comment_id){
       global $wpdb;
        $comment=$wpdb->get_row($wpdb->prepare("SELECT * from {$wpdb->comments} WHERE comment_ID=%d ",$comment_id));
       return $comment;
   }
   
   function attach(SplObserver $observer) {
       
   }
   
   function save_review($content){
       global $bp;
       	$review_parent = $this->get_post_id();
        $user_id=bp_loggedin_user_id(); 
        $review_data= array(
		'comment_content' => apply_filters( 'bp_review_content', $content ),
                'comment_approved'=>0,
		'comment_author_url' =>  bp_core_get_user_domain($user_id),
		'user_id' => $user_id,
                'comment_author'=>  bp_core_get_user_displayname($user_id),
		'comment_post_ID' => $review_parent
	) ;

        
        $review_id=wp_insert_comment($review_data);
        return $review_id;
   }
   
   function update_review($review){
       if(!is_array($review))
           $review=(array)$review;
    
      return wp_update_comment($review);
   }
   
   function delete_review($review_id,$force_delete){
        return wp_delete_comment($review_id, $force_delete);
   }
   function update($review){
       
   }
}

class BPUserReviewMapper extends BPReviewMapper
{
    function __construct() 
	{
        $this->post_id=false;
        $this->object_id=false;
       
    }
    
    function get_post_id($user_id=null) 
	{
        global $bp;
        if(!$user_id)
            $user_id=$bp->displayed_user->id;
        
        $this->post_id=get_user_meta($user_id, "associated_review_page",true);
        return $this->post_id;
    }
    
    function get_post_type() {
        return 'reviews';
    }
    
    function associate_review_page($user_id=null)
	{
       if(empty($user_id))
           $user_id=bp_displayed_user_id ();
       
       $post_id=$this->get_post_id();
       
	   if(empty($post_id))
	   {
         
         $title=sprintf(__("Review per l utente [%s] con ID:[%d]",'reviews'),bp_core_get_user_displayname($user_id),$user_id);
         
		 $postarray = 	array
			(
				'post_title'=>$title,
				'post_author'=>$user_id,
				'post_type'=>'ureviews',
				///////////////////////
				'post_content'=>'prova contenuto last',
				///////////////////////
				'post_status'=>'publish'
			);
		 
         $post_id=  wp_insert_post($postarray);
         update_user_meta($user_id,'associated_review_page' , $post_id) ;
       }
   }
   
   function get_component_link() 
   {
       global $bp;
       return bp_displayed_user_domain().$bp->reviews->root_slug;
   }
   
  function get_total_count() {
      
      if(!bp_is_my_profile())
          return  $this->get_comment_count_by_status(1);
      else
      return    parent::get_all_comment_count();
  } 
   
} 

class BPUserReviewNotifier
{
    private static $instance;

    public function get_instance()
	{
        if(!isset(self::$instance))
            self::$instance=new self();
			
        return self::$instance;
    }
    private function __construct() {
        add_action('bp_reviews_action_saved_review',array($this,'on_save'));
        add_action('bp_reviews_action_update_review',array($this,'on_update'),10,2);
    }
    
    function attach(SplObserver $observer) {
        $this->notifiers->attach($observer);
    }
    
    
    function on_save($review_id)
	{
        $comment=BPReviewMapper::get_comment($review_id);
        $post=get_post($comment->comment_post_ID);
        self::local_notify($post->post_author,$comment->user_id,$review_id,'new_review');
    }
    
    function on_delete(){
        
    }
    
    function on_update($review_id,$action){
        if($action!='approve')
            return;
        
		$comment=BPReviewMapper::get_comment($review_id);
        $post=get_post($comment->comment_post_ID);
        self::local_notify($comment->user_id,$post->post_author,$review_id,'approved_review');
    }
    
    function local_notify($user_id,$who_wrote_it,$comment_id,$action){
        global $bp;
        bp_core_add_notification( $comment_id, $user_id, $bp->reviews->id, $action, $who_wrote_it );
        $this->notify_by_email($user_id, $who_wrote_it, $comment_id, $action);
    }
    
    function delete_notification($review_id,$action){
        global $bp;
        bp_core_delete_notifications_by_item_id(bp_loggedin_user_id(), $review_id, $bp->reviews->id,$action);
    }
  
 function notify_by_email($user_id,$other_id,$comment_id,$action)
 {
     global $bp;
     
     if($action=='new_review'&& bp_get_user_meta( $user_id, 'notification_reviews_new_review', true )!='yes'||$action=='approved_review'&&bp_get_user_meta( $user_id, 'notification_reviews_approved_review', true )!='yes' )
             return;
     $user_name=bp_core_get_user_displayname($other_id);
     $comment=get_comment($comment_id);
     $content=$comment->comment_content;
    
	switch($action){
        case 'new_review':
            $review_link  = bp_core_get_user_domain($user_id) . $bp->reviews->slug . '/pending/';
           
          $subject=sprintf(__('%s ti ha scritto una nuova review','reviews'),$user_name);
          
            
            $message = sprintf( __(
'%1$s ti ha scritto una nuova review:

"%2$s"

Per vederla, loggati e visita: %3$s

---------------------
', 'reviews' ), $user_name, $content, $review_link );
		
                
        break;
            break;
        case 'approved_review':
            $review_link  = bp_core_get_user_domain($other_id) . $bp->reviews->slug ;
            $subject=sprintf(__('%s ha approvato la tua review','reviews'),$user_name);
          
           
           $message = sprintf( __(
'%1$s ha approvato la tua review:

"%2$s"

Per vederla visita: %3$s

---------------------
', 'reviews' ), $user_name, $content, $review_link );
		
                
        break;
    }
       
  
	
		
		$ud       = bp_core_get_core_userdata( $user_id );
		$to       = $ud->user_email;
		$sitename = wp_specialchars_decode( get_blog_option( bp_get_root_blog_id(), 'blogname' ), ENT_QUOTES );
		$subject  = '[' . $sitename . '] ' . $subject;

				
		wp_mail( $to, $subject, $message );
	    
 }   
}
BPUserReviewNotifier::get_instance();


interface BPReviewPermission{
 
   function can_write(); 
   function can_delete($review,BPReviewMapper $mapper);
   function can_approve($review,BPReviewMapper $mapper);
   
}


class BPReviewUserPermissions implements BPReviewPermission{
    
    
    function can_write(){
        global $bp;
        $can=false;
        if(is_user_logged_in()&&  bp_is_user()&&!bp_is_my_profile())
            $can=true;
        return apply_filters('bp_review_user_can_write',$can);
    }
    
    function can_delete($review,BPReviewMapper $mapper){
        
     if(!is_user_logged_in())
         return false;
     
     global $bp;
     $can=false;
       
     if(is_super_admin()||($review->comment_post_ID==$mapper->get_post_id(bp_loggedin_user_id()))||($review->comment_approved==0&&$review->user_id==bp_loggedin_user_id()))
             $can=true;
     return apply_filters('bp_review_user_can_delete',$can,$review,$review->comment_post_ID);
        
    }
    
 function can_approve($review,  BPReviewMapper $mapper){
     global $bp;
     if(!is_user_logged_in())
         return false;
      $can=false;
     
     if($review->comment_post_ID==$mapper->get_post_id(bp_loggedin_user_id()))
             $can=true;
     return apply_filters('bp_review_user_can_approve',$can,$review,$review->comment_post_ID);
 }   
    
}
?>