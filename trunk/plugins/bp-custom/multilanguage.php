<?php


class multilanguage_Widget extends WP_Widget
{
	
	function multilanguage_Widget()
	{
		$id_base = 'multilanguage_Widget';
		
		$name = __('Multilanguage','custom');
		
		$widget_options = array( 'titolo' => __('Multilanguage','custom'));
		
		$control_ops = array( 'width' => 300, 'height' => 350 );
		
		parent::WP_Widget($id_base, $name, $widget_options, $control_ops);
	}
	

	function form($instance)
	{
		$instance = wp_parse_args( (array) $instance, array(  'titolo' => __('Multilanguage','custom') ) );
		
		$titolo 		= __(strip_tags( $instance['titolo'] ),		'custom');
		
		
		?>
		
			<p><label>Titolo		<input id="<?php echo $this->get_field_id( 'titolo' ); 		?>" name="<?php echo $this->get_field_name( 'titolo' ); 	?>" type="text" value="<?php echo esc_attr( $titolo ); 		?>" style="width: 60%; float:right;" /></label></p>
		
		<?php
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['titolo'] 		= __(strip_tags( $new_instance['titolo'] ) 		,'custom');
		
		return $instance;
	}
	
	function widget($args, $instance)
	{
		global $user_ID;
		//utente loggato
			
		extract($args);
			
		$title = apply_filters('widget_title', empty($instance['titolo']) ? __('Commenti alle review','custom') : $instance['titolo'], $instance, $this->id_base);
		
		$mL_link = "";
		
		$this->mL_getScript();
		
		echo $before_title . $title . $after_title;
		
		
		//FRONT-END

		do_action('icl_language_selector');
	}
	
	//FUNZIONI SPECIFICHE DEL WIDGET
	function multilanguage_Widget_getreview($lunghezza, $numero, $mL_link)
	{
		$query_args = array(
			'post_status'		=> 'publish',	
			'post_type'			=> 'review'	,
			'orderby' 			=> 'date',
		 	'order'				=> 'DESC'		
		);
		
		$comment_args = array(
				'status'			=> 'approve',
				'order by'			=> 'comment_date_gmt',
				'order'				=> 'DESC',
				//'number'			=> $mL_num,			
		);

		
		//lancia la QUERY!
		$loop = new WP_Query($query_args);		
						
		$mL_commenti = get_comments($comment_args);
				
		$mL_idReview = array();
		
		$mL_titleReview = array();
		
		if ( $loop->have_posts() ) :
		
		
			while( $loop->have_posts() ) :
			
				$loop->the_post();
				
				$mL_idReview[$loop->post->ID]	=$loop->post->ID;
				$mL_titleReview[$loop->post->ID]=$loop->post->post_title;
				
				
			endwhile;
			
		endif;
		
				//$comment->comment_content
				//$comment->comment_author
				//$comment->comment_date_gmt
				foreach($mL_commenti as $comment) :
				
					if ( $numero>0 && in_array($comment->comment_post_ID,$mL_idReview) ) :
						
//======================================================================						
?>

<div style="border-bottom: 1px solid #000;"><?php
										echo ( 
											"<a href='".bp_core_get_user_domain( $comment->user_id )."'>"									.
												$comment->comment_author							.
											"</a> su <a href='".get_permalink( $comment->comment_post_ID)."'>"	.
												$mL_titleReview[$comment->comment_post_ID] 		.										
																						
											": </a>"													
											);
											
?>

						<span class='mL_box'
								onmouseover='mL_labelon(this)' 
								onmouseout='mL_labeloff(this)'
								onclick='mL_open("mL_labelhidden<?php echo $numero;?>","mL_labelprev<?php echo $numero;?>")'>
								<?php if($lunghezza<=strlen($comment->comment_content)) :?>
								<label id='mL_labelprev<?php echo $numero;?>' class='mL_labelprev'><?php
										//======================================
										
										echo ("".substr($comment->comment_content,0,$lunghezza)."..."); 
										//======================================
										
							?></label>
							<label id='mL_labelhidden<?php echo $numero;?>' class='mL_labelhidden'  style='display:none;'><?php
										//======================================
										echo ("".$comment->comment_content.""); 
										//======================================
										
								?></label>
							<?php else: ?><label class='mL_labelprev' ><?php
										//======================================
										echo ("".$comment->comment_content.""); 
										//======================================
										
								?></label>
							<?php endif; ?>
						</span>
					
</div>

							
<?php
//======================================================================
						$numero--;
						
					endif;
					
				endforeach;
	}
	
	function mL_getScript()
	{ 
		
//======================================================================
?>
	
			<script language='JavaScript' type='text/javascript'>
			<!--

				function mL_open(labelhidden, labelprev)
				{
					jQuery('label#'+labelhidden).fadeToggle("fast");
					jQuery('label#'+labelprev).toggle();
					
				}
				
				function mL_labelon(t)
				{	
					t.style.color = '#87badd';
					t.style.background ='transparent' ;
					t.style.cursor = 'pointer';
				}

				function mL_labeloff(t)
				{
					t.style.color = '#000';
					t.style.background ='transparent' ;
					t.style.cursor = 'default';
				}	
			//-->
			</script>

			<style type='text/css'>
				.mL_box{
					
					background-color:transparent;
					
					font-weight:bold;
					color:#000;
					
					
				}
			
				.mL_labelhidden{
					color:#787878;
				}
				
				.mL_labelprev{
					color:#787878;
					
				}
				
				.mL_label{
					
				}
			</style>
<?php
//======================================================================
	
	}
}
?>
