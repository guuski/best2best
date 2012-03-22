<?php

class ReviewAjaxHelper
{
    private static $instance;
    private $capability;
    private $mapper;
    
	private function __construct() 
	{
        //aggiungi hooks
        add_action('wp_ajax_new_review',array(&$this,'save'));
        add_action('wp_ajax_delete_review',array(&$this,'delete'));
        add_action('wp_ajax_update_review_status',array(&$this,'update_status'));
    }
    
    function set_permission_object(BPReviewPermission $permission)
	{        
        $instance=self::get_instance();
        $instance->capability=$permission;
    }
	
    function set_mapper_object(BPReviewMapper $mapper)
	{       
        $instance=self::get_instance();
        $instance->mapper=$mapper;
    }
    
    public function get_instance()
	{
        if(!isset (self::$instance))
			self::$instance=new self();
        return self::$instance;
    }
    
    //save
   
function save() 
{
    global $bp;

	check_admin_referer( 'new_review', '_wpnonce_new_review' );
      
	if ( !is_user_logged_in() )
	{
		echo '-1';
		return false;
	}

	if ( empty( $_POST['content'] ) )
	{
		//echo '-1<div....'
		echo '<div id="message" class="error"><p>' . __( 'Inserisci del testo per favore', 'reviews' ) . '</p></div>';
		return false;
	}
        
        if(!$this->capability->can_write())
		{
            echo '-1';
			return false;
        }
        $content=$_POST['content'];       
        $review_id=$this->mapper->save_review($content);

	if ( empty( $review_id ) ) 
	{
		echo '-1<div id="message" class="error"><p>' . __( 'Problema nell\' inserimento della tua review, prova di nuovo', 'reviews' ) . '</p></div>';
		return false;
	}
        
	do_action( 'bp_reviews_action_saved_review', $review_id );
	
	if ( bp_has_reviews( 'include=' . $review_id ) ) : ?>
		<?php while ( bp_reviews() ) : bp_the_review(); ?>
			<?php bp_reviews_load_template('reviews/entry.php') ?>
		<?php endwhile; ?>
	 <?php endif;
}
   
    function delete()
	{     	
		check_admin_referer( 'bp_review_delete_link' );
        $error=false;
        $message='';
        $review_id=$_REQUEST['tid'];
		
		if ( !is_user_logged_in() ) 
		{
			$message=__('Operazione non valida','reviews');
			$error=true;
		}
        
		if ( empty($review_id)||!is_numeric($review_id) ) 
		{
			$message=__('Operazione non valida','reviews');
			$error=true;
		}
	
		if(!$error){
                         
            $review=BPReviewMapper::get_comment($review_id);
      
            if(!BPReviewUserPermissions::can_delete($review,$this->mapper))
			{
                $error=true;
                $message=__('Non sei autorizzato a compiere questa azione','reviews');
			}
		
			if(!$error&& $this->mapper->delete_review($review_id,true))
			{
				$message=__('Cancellazione riuscita','reviews');
				do_action( 'bp_reviews_action_delete_review', $review_id );
			}
		}
		
  if($error)
      $op_message="-1<div class='error' id='message'>";
  else 
       $op_message="<div class='updated' id='message'>";
  echo $op_message."<p>".$message."</p></div>";
}    

  function update_status()
  {
            
	check_admin_referer( 'bp_review_approve_link' );

	if ( !is_user_logged_in() ) {
		echo '-1';
		return false;
	}
        $review_id=$_POST['id'];
		
	if ( empty( $review_id ) || !is_numeric($review_id ) ) 
	{
		echo '-1';
		return false;
	}
      
        $action=$_POST['current_action'];
        
		if(!($action=='hide'||$action=='approve'))
		{
			//echo '-1<div....'
			echo '<div id="message" class="error"><p>' . __( 'Operazione non valida!.', 'reviews' ) . '</p></div>';	
			return false;
        }
       	
        $review='';;
		
        if($action=='approve')
		{
            $status=1;
            $status_message=__('La Review è stata approvata! Sarà visibile nella scheda reviews del tuo profilo.','reviews');
        }
		else
		{            
           $status=0;
           $status_message=__('Review in attesa di moderazione! Vedi la tab reviews in moderazione nel tuo profilo. Per il momento solo tu e il mittente potete vederla','reviews');
        }   
        
        $review=BPReviewMapper::get_comment($review_id);
        
        if(!$this->capability->can_approve($review,$this->mapper))
		{
              echo '-1<div id="message" class="error"><p>'.__('Non hai i privilegi per effettuare quest\'operazione.','reviews') .'</p></div>';
              return false;
        }
                       
        $review->comment_approved=$status;
		
        if($this->mapper->update_review($review))
		{
            do_action( 'bp_reviews_action_update_review', $review_id,$action );
            echo '<div id="message" class="updated"><p>'.$status_message.'</p></div>';
        }
        else
			echo '-1<div id="message" class="error"><p>' . __( 'Errore durante l\'operazione!. Prova più tardi', 'reviews' ) . '</p></div>';
	   
	}   
}

ReviewAjaxHelper::get_instance();
ReviewAjaxHelper::set_permission_object(new BPReviewUserPermissions());
ReviewAjaxHelper::set_mapper_object(new BPUserReviewMapper());
?>