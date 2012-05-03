<?php
/*[16:39:10 CEST] Giambattista Pisasale: ci sei ?!
[16:39:14 CEST] Giambattista Pisasale: ho una bella micro attività per te
[16:39:17 CEST] Giambattista Pisasale: sempre un widget
[16:39:34 CEST] Giovanni Giannone: ok
[16:39:39 CEST] Giambattista Pisasale: deve contenere per gli utenti non loggati la lista degli ultimi commenti a tutte le review
[16:39:57 CEST] Giambattista Pisasale: 3 commenti per i non loggati, 6 per gli utenti loggati
[16:40:21 CEST] Giambattista Pisasale: deve contenere pero' solo i primi N caratteri, poi cliccando si deve aprire la review intera
[16:40:29 CEST] Giambattista Pisasale: N => configurabile lato amministratore
[16:40:46 CEST] Giovanni Giannone: ok ci inizio a lavorare
[16:40:47 CEST] Giambattista Pisasale: da fare in collaborazione con Andrea ovviamente
[16:40:49 CEST] Giambattista Pisasale: yeah
[16:41:00 CEST] Giovanni Giannone: si
[16:41:01 CEST] Giambattista Pisasale: crea attività su jira, stimala, ti darò conferma della stima
[16:41:09 CEST] Giovanni Giannone: ok
[16:41:27 CEST] Giambattista Pisasale: penso non ci sia bisogno di sub-task, ma fai pure te, vedro' come lo imposti e ne parliamo assieme
[16:41:46 CEST] Giovanni Giannone: ok
*/
 
class commentReview_Widget extends WP_Widget
{
	
	function commentReview_Widget()
	{
		$id_base = 'commentReview_Widget';
		
		$name = __('Commenti alle review','custom');
		
		$widget_options = array( 'titolo' => __('Commenti alle review','custom'), 'numerolog'=> 6, 'numerounlog'=> 3, 'lunghezza'=> 30);
		
		$control_ops = array( 'width' => 300, 'height' => 350 );
		
		parent::WP_Widget($id_base, $name, $widget_options, $control_ops);
	}
	

	function form($instance)
	{
		$instance = wp_parse_args( (array) $instance, array(  'titolo' => __('Commenti alle review','custom') ,  'numerolog'=> 6, 'numerounlog'=> 3, 'lunghezza' => 30) );
		
		$titolo 		= __(strip_tags( $instance['titolo'] ),		'custom');
		
		$numerolog 		= strip_tags( $instance['numerolog'] );
		
		$numerounlog	= strip_tags( $instance['numerounlog'] );
		
		$lunghezza	= strip_tags( $instance['lunghezza'] );
		
		?>
		
			<p><label>Titolo		<input id="<?php echo $this->get_field_id( 'titolo' ); 		?>" name="<?php echo $this->get_field_name( 'titolo' ); 	?>" type="text" value="<?php echo esc_attr( $titolo ); 		?>" style="width: 60%; float:right;" /></label></p>
					
			<p><label>Numero log	<input id="<?php echo $this->get_field_id( 'numerolog' );	?>" name="<?php echo $this->get_field_name( 'numerolog' ); 	?>" type="text" value="<?php echo esc_attr( $numerolog ); 	?>" style="width: 60%; float:right;" /></label></p>
			
			<p><label>Numero unlog	<input id="<?php echo $this->get_field_id( 'numerounlog' );	?>" name="<?php echo $this->get_field_name( 'numerounlog' );?>" type="text" value="<?php echo esc_attr( $numerounlog ); ?>" style="width: 60%; float:right;" /></label></p>
			
			<p><label>Lunghezza		<input id="<?php echo $this->get_field_id( 'lughezza' );	?>" name="<?php echo $this->get_field_name( 'lunghezza' );?>" type="text" value="<?php echo esc_attr( $lunghezza ); 	?>" style="width: 60%; float:right;" /></label></p>
					
		
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
			
		$numerolog =  empty($instance['numerolog']) ? 10000 : $instance['numerolog'];	
			
		$numerounlog =  empty($instance['numerounlog']) ? 10000 : $instance['numerounlog'];	
			
		$lunghezza =  empty($instance['lunghezza']) ? 10000 : $instance['lunghezza'];	
		
		$this->cR_getScript();
		
		echo $before_title . $title . $after_title;
		
		//FRONT-END
		if ($user_ID>0) //sono loggato
			
			$this->cR_getreview( $lunghezza ,$numerolog);
				
		else
			
			$this->cR_getreview( $lunghezza ,$numerounlog);
	}
	
	//FUNZIONI SPECIFICHE DEL WIDGET
	function cR_getreview($lunghezza, $numero)
	{
		$query_args = array(
			'post_status'		=> 'publish',	
			'post_type'			=> 'review'	,
			'orderby' 			=> 'date',
		 	'order'				=> 'ASC'		
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
		
		if ( $loop->have_posts() ) :
		
		
			while( $loop->have_posts() ) :
			
				$loop->the_post();
				
				$cR_idReview[$loop->post->ID]=$loop->post->ID;
				
/*#*/			//echo 'ID: '.$loop->post->ID.' POST_TYPE: '.$loop->post->post_type.'<br />';
				
			endwhile;
			
		endif;
		
				//$comment->comment_content
				//$comment->comment_author
				//$comment->comment_date_gmt
				foreach($cR_commenti as $comment) :
				
					if ( $numero>0 && in_array($comment->comment_post_ID,$cR_idReview) ) :
						
//======================================================================						
?>

						<div class='cR_box'
								onmouseover='cR_labelon(this)' 
								onmouseout='cR_labeloff(this)'
								onclick='cR_open("cR_labelhidden<?php echo $numero;?>","cR_labelprev<?php echo $numero;?>")'>
								
								<label id='cR_label<?php echo $numero;?>' class="cR_label" value='chiuso' >	
									<?php
										//==============================
										echo ( 
											//$comment->comment_post_ID. ' )  ' .
											//$comment->comment_date_gmt . '<br />' . 
											$comment->comment_author ."<br />"
											);
										//==============================
									?>		
								</label>
								<label id='cR_labelprev<?php echo $numero;?>' class='cR_labelprev'>
									<?php
										//======================================
										echo (
											substr($comment->comment_content, 0, $lunghezza)." [...]"
											); 
										//======================================
										?>
								</label>
								<label id='cR_labelhidden<?php echo $numero;?>' class='cR_labelhidden'  style='display:none;'>
									<?php
										//======================================
										echo (
											$comment->comment_content
											); 
										//======================================
										?>
								</label>
							
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
					if (jQuery('label#'+labelhidden).val()=="aperto") 
					{
						jQuery('label#'+labelhidden).hide("slow");	
						jQuery('label#'+labelhidden).val("chiuso");
				
						jQuery('label#'+labelprev).show();
					}
					else
					{
						jQuery('label#'+labelhidden).val("aperto");
						jQuery('label#'+labelhidden).show("slow");
						
						jQuery('label#'+labelprev).hide();	
					}
				}
				
				function cR_labelon(t)
				{	
					t.style.color = '#000';
					t.style.background ='#87badd' ;
					t.style.cursor = 'pointer';
				}

				function cR_labeloff(t)
				{
					t.style.color = '#000';
					t.style.background ='#0a76b7' ;
					t.style.cursor = 'default';
				}	
			//-->
			</script>

			<style type='text/css'>
				.cR_box{
					position:relative;
					background-color:#0a76b7;
					border:2px solid #ffffff;
					font-weight:bold;
					color:#000;
					margin-bottom:5px;
					padding:4px;
				}
			
				.cR_labelhidden{
					color:#fff;
				}
				
				.cR_labelprev{
					color:#fff;
					
				}
				
				.cR_label{
					
				}
			</style>
<?php
//======================================================================
	
	}
}
?>
