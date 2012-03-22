<?php
//main component
//extends BP_Component and Is implemented as singleton instance to allow other devs changing hooks


//this class needs refractoring to allow other components(groups etc) to use it easily without much coding
class BPUserReview extends BP_Component{
    
    private static $instance;// singleton instance
    private $mapper;
    public static function get_instance() {
        if (!isset(self::$instance)) {
               self::$instance = new self();
        }

        return self::$instance;
    }
    function set_mapper_object(BPReviewMapper $mapper){
        $instance=self::get_instance();
        $instance->mapper=$mapper;
    }
    
    /**
     * Private constructor 
     */
    private function __construct() {
        
        parent::start(
                'reviews',//unique id
                __( 'Review', 'reviews' ),
                untrailingslashit(BP_REVIEWS_PLUGIN_DIR)//base path., remove slash
        );
        //now let us bind the screen functions
        
        add_action('bp_screens',array(&$this,'delete_review'),4);
        add_action('bp_screens',array(&$this,'save_review'),4);
        add_action('bp_screens',array(&$this,'update_review'),4);
      
    }
    
    /**
     * Include files
     */
    function includes() {
  
		$includes = array(
			'core/template-tags.php',
			'core/classes.php',
                        'inc/ajax.php',
                        'core/hooks.php'
			);
                
             
            parent::includes( $includes );
            self::set_mapper_object(new BPUserReviewMapper());//update the singleton isntance with the current mapper object, override it for groups if ya want

	}

	//setup initial
	function setup_globals() {
            global $bp;
                   if(!defined('BP_REVIEWS_SLUG'))
                       define('BP_REVIEWS_SLUG','reviews');
           	//all other globals
                // Note that global_tables is included in this array.
		$globals = array(
			'slug'                  => BP_REVIEWS_SLUG,
			'root_slug'             => isset( $bp->pages->reviews->slug ) ? $bp->pages->reviews->slug : BP_REVIEWS_SLUG,
                        'notification_callback'=>'bp_reviews_format_notifications'
                        //we may add notification call back here, should we?
			
			
		);

		parent::setup_globals( $globals );//it will call do_action("bp_reviews_setup_global") after setting up the constants properly
                
               if(bp_is_current_component($bp->reviews->root_slug)){
                 //setup something if you want to
                  $this->mapper->associate_review_page();//make sure to call it when a review page is opened for a user  
                  $bp->reviews->post=$this->mapper->get_post();//do we really need it
                  $bp->reviews->post->id=$bp->reviews->post->ID;//do we really need it
                   
               }  
               

                
        }//end of setup global
        
        //setup nav
        function setup_nav() {
            global $bp;
                         
            // Add 'reviews' to the user's main navigation
            $main_nav = array(
			'name'                => sprintf( __( 'Reviews <span>%d</span>', 'reviews' ),$this->mapper->get_total_count() ),
			'slug'                => $this->slug,
			'position'            => 86,
			'screen_function'     =>array(&$this,'screen_home'),
			'default_subnav_slug' => 'my-reviews',
			'item_css_id'         => $this->id
		);

            $review_link = trailingslashit( $bp->loggedin_user->domain . $this->slug );//without a trailing slash
           
            if(bp_is_my_profile()){
                $nav_text=sprintf(__('Le mie Review <span>%d</span>','reviews'),$this->mapper->get_comment_count_by_status(1));
                $review_link = trailingslashit( $bp->loggedin_user->domain . $this->slug );//without a trailing slash
            }
            else{
                $nav_text=sprintf (__('Review di %s <span>%d</span>', 'reviews'),  bp_core_get_user_displayname ($bp->displayed_user->id),$this->mapper->get_comment_count_by_status(1));
		
            $review_link = trailingslashit( $bp->displayed_user->domain . $this->slug );//without a trailing slash
            }
            $sub_nav[] = array(
                    'name'            => $nav_text,
                    'slug'            => 'my-reviews',
                    'parent_url'      => $review_link,
                    'parent_slug'     => $this->slug,
                    'screen_function' => array(&$this,'screen_home'),
                    'position'        => 10,
                    'item_css_id'     => 'review-my-review'
            );

            // Add the Create review link
            if(review_current_user_can_write())
                $sub_nav[] = array(
                        'name'            => __('Scrivi una Review', 'reviews' ),
                        'slug'            => 'create',
                        'parent_url'      => $review_link,
                        'parent_slug'     => $this->slug,
                        'screen_function' => array(&$this,'screen_write'),
                        'user_has_access' => (is_user_logged_in()&&!bp_is_my_profile()&&bp_is_user()),//only allow on other's profile
                        'position'        => 20
                );
            
            //if this is single gallery, add edit gallery link too!
            // Add the Upload link to gallery nav
            $sub_nav[] = array(
                    'name'            => sprintf(__('Da moderare <span>%d</span>', 'reviews' ),$this->mapper->get_comment_count_by_status(0)),
                    'slug'            => 'pending',
                    'parent_url'      => $review_link,
                    'parent_slug'     => $this->slug,
                    'screen_function' => array(&$this,'screen_pending'),
                    'user_has_access' =>  bp_is_my_profile(),
                    'position'        => 30
            );

	 parent::setup_nav( $main_nav, $sub_nav );//will call bp_ureview_setup_nav
         
         
	}
       
        //screens functions
       function screen_home(){
           global $bp;
            bp_reviews_delete_between_user_by_type(bp_loggedin_user_id(), bp_displayed_user_id(),$bp->reviews->id);
            add_action('bp_template_content',array(&$this,'home_content'));
            bp_core_load_template(apply_filters('user_review_template','members/single/plugins'));
            
        }
        
        function screen_write(){
                add_action('bp_template_content',array(&$this,'write_content'));
              bp_core_load_template(apply_filters('user_review_template','members/single/plugins'));
        }
        
        function screen_pending(){
            global $bp;
              //delete all notifications for this user
              bp_core_delete_notifications_by_type(bp_loggedin_user_id(), $bp->reviews->id, 'new_review');
              add_action('bp_template_content',array(&$this,'pending_content'));
              bp_core_load_template(apply_filters('user_review_template','members/single/plugins'));
        }
        
        //what to show on user review page
        function home_content(){
          bp_reviews_load_template('reviews/review-loop.php');
        }
                    
        function pending_content(){
            bp_reviews_load_template('reviews/review-loop.php');
        }
        function write_content(){
            bp_reviews_post_form();
        }
      /*action functions*/
      
     function save_review(){
         global $bp;
         if(bp_is_current_component($bp->reviews->slug)&&  bp_is_current_action('create')&&!empty($_POST['review-submit'])){
           // Check the nonce
            check_admin_referer( 'new_review', '_wpnonce_new_review' );
         $error=false;
         $message='';
	if ( !is_user_logged_in() ) {
           $message=__('Non sei autorizzato a compiere quet operazione','reviews');
           $error=true;
	}
        else if ( empty( $_POST['new-review'] ) ) {
		$message= __( 'Inserisci del testo per favore', 'reviews' ) ;
		$error=true;
	}
        else if(!BPReviewUserPermissions::can_write()){
              $message=__('Non ti è permesso scrivere una review.','reviews');
              $error=true;
        }
        else{
        
            $content=$_POST['new-review'];
       
            $review_id=$this->mapper->save_review($content);
            $message=__('Review inviata. In attesa di moderazione.','reviews');
            if ( empty( $review_id ) ) {
                $message= __( 'Operazione non riuscita. Prova di nuovo più tardi.', 'reviews' ) ;
                $error=true;
            }
            else
               do_action( 'bp_reviews_action_saved_review', $review_id ); 
            
         }
      
      bp_core_add_message($message,$error);
      
      //redirect
      bp_core_redirect(wp_get_referer()); 
     }
    
    }   
     function delete_review(){
         global $bp;
         $error=false;
         $message='';
         if(bp_is_current_component($bp->reviews->slug)&&  bp_is_current_action('delete')&&!empty($_REQUEST['tid'])){
             check_admin_referer( 'bp_review_delete_link' );
             //check if the user can write and xyz
           	
        $review_id=$_REQUEST['tid'];
             
        $review=BPReviewMapper::get_comment($review_id);
      
        if(!BPReviewUserPermissions::can_delete($review,$this->mapper)){
            $error=true;
            $message=__('Non sei autorizzato a compiere quest operazione','reviews');
        }
	//if current user can delete it
      if(!$error&& $this->mapper->delete_review($review_id,true))
              $message=__('Cancellazione effettuata correttamente','reviews');
      
      bp_core_add_message($message,$error);
      do_action( 'bp_reviews_action_delete_review', $review_id );
      //redirect
      bp_core_redirect(wp_get_referer());
	
         }
     }
     
     function update_review(){
         global $bp;
         if(bp_is_current_component($bp->reviews->slug)&&  bp_is_current_action('approve-unapprove')&&!empty($_REQUEST['tid'])){
           // Check the nonce
	check_admin_referer( 'bp_review_approve_link' );
        
        $review_id=$_REQUEST['tid'];
        $action=$_REQUEST['action'];
        
        $error=false;
        $message='';
	if ( !is_user_logged_in() ) {
           $message=__('Non sei autorizzato a compiere quest operazione','reviews');
           $error=true;
           
	} else if ( empty( $review_id ) || !is_numeric($review_id ) ) {
            $message=__('Operazione non valida.','reviews');
            $error=true;
            
	} else if(!($action=='hide'||$action=='approve')){
            $message= __( 'Operazione non valida.', 'reviews' );
            $error=true;
	
        }
       
        
	//if current user can delete it
        $review='';//get current comment
        if(!$error){
            if($action=='approve'){
                $status=1;
                $status_message=__('Review approvata! Adesso è visibile sul tuo profilo','reviews');
            }else{

               $status=0;
               $status_message=__('Review in attesa di moderazione! Vai alla scheda Review sul tuo profilo...','reviews');
            }   
        
           $review=BPReviewMapper::get_comment($review_id);
            //if current_user_can
            if(!BPReviewUserPermissions::can_approve($review,$this->mapper)){
                  $message=__('Non sei autorizzato a compiere quest\'operazione','reviews');
                  $error=true;
            }
          
             
            $review->comment_approved=$status;
            if(!$error&&$this->mapper->update_review($review)){
                do_action( 'bp_reviews_action_update_review', $review_id,$action );
                $message=$status_message;
            } else {
                $message= __( 'Operazione non riuscita!. Riprova di nuovo!', 'reviews' );
                $error=true;
          }
     }  
     bp_core_add_message($message,$error);
      //redirect
     bp_core_redirect(wp_get_referer());  
     }
  }   
     
  

}
?>