<?php

class languages_Widget extends WP_Widget
{
	var $la_lingua = array('it_IT','en_GB');
	
	function languages_Widget()
	{
		
		parent::WP_Widget( $id = 'languages_Widget', $name = 'Languages', $options = array( 'titolo' => __('Multilingua','languages'), 'lingua' => 'it_IT'));
		
	}
	

	function form($instance)
	{
		// outputs the options form on admin
		$instance = wp_parse_args( (array) $instance, array(  'titolo' => __('Multilingua','languages'), 'lingua' => 'it_IT' ) );
		
		$titolo = strip_tags( $instance['titolo'] );
		$lingua = strip_tags( $instance['lingua'] );

		?>
		
			<p>
				<label><?php _e('Titolo','languages')		?>
				
					<input id="<?php echo $this->get_field_id( 'titolo' ); ?>" name="<?php echo $this->get_field_name( 'titolo' ); ?>" type="text" value="<?php echo esc_attr( $titolo ); ?>"  style="width: 50%; float:right;"  />
				
				</label>
			</p>
		
			<p>
				<label><?php _e('Lingua','languages')		?>
				
					<input id="<?php echo $this->get_field_id( 'lingua' ); ?>" name="<?php echo $this->get_field_name( 'lingua' ); ?>" type="text" value="<?php echo esc_attr( $lingua ); ?>"  style="width: 50%; float:right;"  />
				
				</label>
			</p>
		<?php 
	}


	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['titolo'] = __(strip_tags( $new_instance['titolo'] ),'languages');
		
		$instance['lingua'] = __(strip_tags( $new_instance['lingua'] ),'languages');
		
		return $instance;
	}


	function widget($args, $instance)
	{
		global $bp;
		global $user_ID;
		if ($user_ID>0)
		{
			//UTENTE LOGGATO VEDO LA VARIABILE DELLA LINGUA UTENTE
		}
		else
		{
			//UTENTE NON LOGGATO VEDO LA VARIABILE DELLA LINGUA DEL WIDGET
		}
		/*
		//$user_ID=bp_displayed_user_id();

		//if ($user_ID==0) $user_ID=$bp->displayed_user->id;
		
		//if ($user_ID==0) {
			//non visualizzo nulla
			//}
		//else
		//{
			*/
		
			$attivo=array();
		
			extract($args);
		
			$title = apply_filters('widget_title', empty($instance['titolo']) ? __('Multilingua','languages') : $instance['titolo'], $instance, $this->id_base);
			$lingua =$instance['lingua'];
			if ( $title )
		
				echo $before_title . $title . $after_title;
				
				_e("Qui andranno le bandierine per selezionare la lingua dell'utente",'languages');
	
				echo $lingua;				
			
			
			//dovremmo aggiungere questa riga nell'hook init in una funzione secondaria ed utilizzare il widget per settare 
			//la variabile lingua
			define('WPLANG', 'it_IT');
			
			echo " ".WPLANG;
			

			echo "		<div
								onclick='alert (\"italiano\");'>
								ITALIANO
							
						</div>";
			echo "		<div
								onclick='alert (\"inglese\")'>
								INGLESE
							
						</div>";
			echo "ok";
							
		
	}

}
?>
