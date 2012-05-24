<?php


class viewReferral_Widget extends WP_Widget
{
	
	function viewReferral_Widget()
	{
		$id_base = 'viewReferral_Widget';
		
		$name = __('Visualizza Referral','custom');
		
		$widget_options = array( 'titolo' => __('Referral','custom'), 'numerolog'=> 6, 'numerounlog'=> 3, 'lunghezza'=> 20);
		
		$control_ops = array( 'width' => 300, 'height' => 350 );
		
		parent::WP_Widget($id_base, $name, $widget_options, $control_ops);
	}
	

	function form($instance)
	{
		$instance = wp_parse_args( (array) $instance, array(  'titolo' => __('Visualizza Referral','custom') ,  'numerolog'=> 6, 'numerounlog'=> 3, 'lunghezza' => 20) );
		
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
			
		$title = apply_filters('widget_title', empty($instance['titolo']) ? __('Visualizza Referral','custom') : $instance['titolo'], $instance, $this->id_base);
			
		$numerolog =  empty($instance['numerolog']) ? 10 : $instance['numerolog'];	
			
		$numerounlog =  empty($instance['numerounlog']) ? 10 : $instance['numerounlog'];	
			
		$lunghezza =  empty($instance['lunghezza']) ? 30 : $instance['lunghezza'];	
		
		$vR_link = "";
		
		$this->vR_getScript();
		
		echo $before_title . $title . $after_title;
		
		
		//FRONT-END
		if ($user_ID>0) //sono loggato
		{
			
			$attivo = get_userdata($user_ID);
			
			$vR_link = bp_core_get_user_domain($attivo->user_login).$attivo->user_login;
			
			$this->vR_getReferral( $lunghezza ,$numerolog, $vR_link);
			
		}
		else
		{
			
			$vR_link = get_bloginfo('url').DS."registrati";
			
			$this->vR_getReferral ($lunghezza ,$numerounlog, $vR_link);
		
		}
	}
	
	//FUNZIONI SPECIFICHE DEL WIDGET
	function vR_getReferral($lunghezza, $numero, $vR_link)
	{
		$query_args = array(
			
			'post_type'			=> 'referral',
			'post_status'		=> 'publish',
			'order by'			=> 'post_date_gmt',
			'order'				=> 'DESC'
		);
		

		
		//lancia la QUERY!
		$loop = new WP_Query($query_args);		
						
		
		if ( $loop->have_posts() ) :
		
			while( $loop->have_posts() ) :
			
				$loop->the_post();
				
				if ($numero>0) :
					
					$numero--;
					
					$vR_postID = $loop->post->ID;
					$vR_autorID = $loop->post->post_author;
					$user_info = get_userdata($vR_autorID);
					
					$vR_autor = $user_info->user_login;
					$vR_title = $loop->post->post_title;
					$vR_content = $loop->post->post_content;
					
					if ($vR_content=='') $vR_content="non c'è contenuto";
					
						
				
?>
<div>
									<?php
										//echo "<!-- gbp "; print_r($comment); echo " -->";
										echo ( 
											"<a href='".bp_core_get_user_domain( $vR_autorID )."'>"									.
												$vR_autor							.
											"</a>"													.
											" su "											.
											"<a href='".get_bloginfo('url').DS."index.php?p=".$vR_postID."'>"	.
												$vR_title		.										
																						
											"</a>"													
											);
											
?>

						<span class='vR_box'
								onmouseover='vR_labelon(this)' 
								onmouseout='vR_labeloff(this)'
								onclick='vR_open("vR_labelhidden<?php echo $numero;?>","vR_labelprev<?php echo $numero;?>")'>
								
								<label id='vR_labelprev<?php echo $numero;?>' class='vR_labelprev'>
									<?php
										//======================================
										echo (
										"Vedi..."
											
											); 
										//======================================
										?>
								</label>
								<label id='vR_labelhidden<?php echo $numero;?>' class='vR_labelhidden'  style='display:none;'>
									<?php
										//======================================
										$tipologia_rapporto  = get_post_meta( $loop->post->ID, 'tipologia_rapporto', true );  
										$anzianita_rapporto  = get_post_meta( $loop->post->ID, 'anzianita_rapporto', true );
										$utente_consigliato  = get_post_meta( $loop->post->ID, 'utente_consigliato', true );
										$voto_complessivo    = get_post_meta( $loop->post->ID, 'voto_complessivo', true );
										
										echo (
											"<br />".
											"TIPOLOGIA RAPPORTO: ".$tipologia_rapporto."<br />".
											"ANZIANITÀ RAPPOSTO: ".$anzianita_rapporto."<br />".
											"UTENTE CONSIGLIATO: ".$utente_consigliato."<br />".
											"VOTO COMPLESSIVO:	 ".$voto_complessivo."<br />"
											); 
										/*
										echo (
											$vR_content
											); 
										*/
										//======================================
										?>
								</label>
							
						</span>
					
					</div>
<?php

				endif;
				
			endwhile;
			
		endif;
		
	}
	
	function vR_getScript()
	{ 
		
//======================================================================
?>
	
			<script language='JavaScript' type='text/javascript'>
			<!--

				function vR_open(labelhidden, labelprev)
				{
					jQuery('label#'+labelhidden).fadeToggle("fast");
					jQuery('label#'+labelprev).toggle();
					
				}
				
				function vR_labelon(t)
				{	
					t.style.color = '#87badd';
					t.style.background ='transparent' ;
					t.style.cursor = 'pointer';
				}

				function vR_labeloff(t)
				{
					t.style.color = '#000';
					t.style.background ='transparent' ;
					t.style.cursor = 'default';
				}	
			//-->
			</script>

			<style type='text/css'>
				.vR_box{
					
					background-color:transparent;
					
					font-weight:bold;
					color:#000;
					
					
				}
			
				.vR_labelhidden{
					color:#787878;
				}
				
				.vR_labelprev{
					color:#787878;
					
				}
				
				.vR_label{
					
				}
			</style>
<?php
//======================================================================
	
	}
}
?>
