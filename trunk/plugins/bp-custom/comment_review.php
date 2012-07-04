<?php
/*
ottimo lavoro, 
occorre raffinarlo un attimo, prendi spunto da www.best2best.it, il secondo widget "Cosa succede in giro" contiene ad esempio 
"fruttaverdurabio su Servizio inesistente" => link alla recensione. Ecco dovresti utilizzare lo stesso stile grafico e aggiungere 
appunto il link alla recensione, per il resto ok.
risultato finale: 
"fruttaverdurabio su Servizio inesistente: Mi sembra una affermazione grat[...]"
se riesci, puoi mettere sul nome dell'autore del commento (in questo caso fruttaverdurabio) il link al suo profilo per 
gli utenti loggati, o alla pagina di registrazione per gli utenti non loggati !? 

ci aggiorniamo

GBP
*/

class commentReview_Widget extends WP_Widget
{
	
	function commentReview_Widget()
	{
		$id_base = 'commentReview_Widget';
		
		$name = __('Commenti alle review','custom');
		
		$widget_options = array( 'titolo' => __('Commenti alle review','custom'), 'numerolog'=> 6, 'numerounlog'=> 3, 'lunghezza'=> 20);
		
		$control_ops = array( 'width' => 300, 'height' => 350 );
		
		parent::WP_Widget($id_base, $name, $widget_options, $control_ops);
	}
	

	function form($instance)
	{
		$instance = wp_parse_args( (array) $instance, array(  'titolo' => __('Commenti alle review','custom') ,  'numerolog'=> 6, 'numerounlog'=> 3, 'lunghezza' => 20) );
		
		$titolo 		= __(strip_tags( $instance['titolo'] ),		'custom');
		
		$numerolog 		= strip_tags( $instance['numerolog'] );
		
		$numerounlog	= strip_tags( $instance['numerounlog'] );
		
		$lunghezza		= strip_tags( $instance['lunghezza'] );
		
		?>
		
			<p><label><?php _e('Titolo','custom')		?><input id="<?php echo $this->get_field_id( 'titolo' ); 	?>" name="<?php echo $this->get_field_name( 'titolo' ); 	?>" type="text" value="<?php echo esc_attr( $titolo ); 		?>" style="width: 60%; float:right;" /></label></p>
					
			<p><label><?php _e('Numero log','custom')	?><input id="<?php echo $this->get_field_id( 'numerolog' );	?>" name="<?php echo $this->get_field_name( 'numerolog' ); 	?>" type="text" value="<?php echo esc_attr( $numerolog ); 	?>" style="width: 60%; float:right;" /></label></p>
			
			<p><label><?php _e('Numero unlog','custom')	?><input id="<?php echo $this->get_field_id( 'numerounlog' );?>" name="<?php echo $this->get_field_name( 'numerounlog' );?>" type="text" value="<?php echo esc_attr( $numerounlog ); ?>" style="width: 60%; float:right;" /></label></p>
			
			<p><label><?php _e('Lunghezza','custom')	?><input id="<?php echo $this->get_field_id( 'lughezza' );	?>" name="<?php echo $this->get_field_name( 'lunghezza' );?>" type="text" value="<?php echo esc_attr( $lunghezza ); 	?>" style="width: 60%; float:right;" /></label></p>
					
		
		<?php
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['titolo'] 		= __(strip_tags( $new_instance['titolo'] ) 		,'custom');
		
		$instance['numerolog'] 		= __(strip_tags( $new_instance['numerolog'] )	,'custom');
		
		$instance['numerounlog'] 	= __(strip_tags( $new_instance['numerounlog'] )	,'custom');
		
		$instance['lunghezza'] 		= __(strip_tags( $new_instance['lunghezza'] )	,'custom');
		
		return $instance;
	}
	
	function widget($args, $instance)
	{
		global $user_ID;
		//utente loggato
			
		extract($args);
			
		$title = apply_filters('widget_title', empty($instance['titolo']) ? __('Commenti alle review','custom') : $instance['titolo'], $instance, $this->id_base);
			
		$numerolog =  empty($instance['numerolog']) ? 10 : $instance['numerolog'];	
			
		$numerounlog =  empty($instance['numerounlog']) ? 10 : $instance['numerounlog'];	
			
		$lunghezza =  empty($instance['lunghezza']) ? 30 : $instance['lunghezza'];	
		
		$cR_link = "";
		
		$this->cR_getScript();
		
		echo $before_title . $title . $after_title;
		
		
		//FRONT-END
		if ($user_ID>0) //sono loggato
		{
			
			$attivo = get_userdata($user_ID);
			
			$cR_link = bp_core_get_user_domain($attivo->user_login).$attivo->user_login;
			
			$this->cR_getreview( $lunghezza ,$numerolog, $cR_link);
			
		}
		else
		{
			
			$cR_link = get_bloginfo('url').DS."registrati";
			
			$this->cR_getreview( $lunghezza ,$numerounlog, $cR_link);
		
		}
	}
	
	//FUNZIONI SPECIFICHE DEL WIDGET
	function cR_getreview($lunghezza, $numero, $cR_link)
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
				//'number'			=> $cR_num,			
		);

		
		//lancia la QUERY!
		$loop = new WP_Query($query_args);		
						
		$cR_commenti = get_comments($comment_args);
				
		$cR_idReview = array();
		
		$cR_titleReview = array();
		
		if ( $loop->have_posts() ) :
		
		
			while( $loop->have_posts() ) :
			
				$loop->the_post();
				
				$cR_idReview[$loop->post->ID]	=$loop->post->ID;
				$cR_titleReview[$loop->post->ID]=$loop->post->post_title;
				
				
			endwhile;
			
		endif;
		
				//$comment->comment_content
				//$comment->comment_author
				//$comment->comment_date_gmt
				foreach($cR_commenti as $comment) :
				
					if ( $numero>0 && in_array($comment->comment_post_ID,$cR_idReview) ) :
						
//======================================================================						
?>

<div style="border-bottom: 1px solid #000;"><?php
										echo ( 
											"<a href='".bp_core_get_user_domain( $comment->user_id )."'>"									.
												$comment->comment_author							.
											"</a> su <a href='".get_permalink( $comment->comment_post_ID)."'>"	.
												$cR_titleReview[$comment->comment_post_ID] 		.										
																						
											": </a>"													
											);
											
?>

						<span class='cR_box'
								onmouseover='cR_labelon(this)' 
								onmouseout='cR_labeloff(this)'
								onclick='cR_open("cR_labelhidden<?php echo $numero;?>","cR_labelprev<?php echo $numero;?>")'>
								<?php if($lunghezza<=strlen($comment->comment_content)) :?>
								<label id='cR_labelprev<?php echo $numero;?>' class='cR_labelprev'><?php
										//======================================
										
										echo ("".substr($comment->comment_content,0,$lunghezza)."..."); 
										//======================================
										
							?></label>
							<label id='cR_labelhidden<?php echo $numero;?>' class='cR_labelhidden'  style='display:none;'><?php
										//======================================
										echo ("".$comment->comment_content.""); 
										//======================================
										
								?></label>
							<?php else: ?><label class='cR_labelprev' ><?php
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
	
	function cR_getScript()
	{ 
		
//======================================================================
?>
	
			<script language='JavaScript' type='text/javascript'>
			<!--

				function cR_open(labelhidden, labelprev)
				{
					jQuery('label#'+labelhidden).fadeToggle("fast");
					jQuery('label#'+labelprev).toggle();
					
				}
				
				function cR_labelon(t)
				{	
					t.style.color = '#87badd';
					t.style.background ='transparent' ;
					t.style.cursor = 'pointer';
				}

				function cR_labeloff(t)
				{
					t.style.color = '#000';
					t.style.background ='transparent' ;
					t.style.cursor = 'default';
				}	
			//-->
			</script>

			<style type='text/css'>
				.cR_box{
					
					background-color:transparent;
					
					font-weight:bold;
					color:#000;
					
					
				}
			
				.cR_labelhidden{
					color:#787878;
				}
				
				.cR_labelprev{
					color:#787878;
					
				}
				
				.cR_label{
					
				}
			</style>
<?php
//======================================================================
	
	}
}
?>
