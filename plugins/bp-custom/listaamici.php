<?php

class listaAmici_Widget extends WP_Widget
{
	/**
	 * Constructor
	 * 
	 * Registers the widget details with the parent class
	 */
	function listaAmici_Widget()
	{
		// widget actual processes
		//parent::WP_Widget( $id = 'bpcustom_Widget', $name = get_class($this), $options = array( 'description' => 'bpcustom_Widget' ) );
		//parent::WP_Widget( false, $name = __( 'Groups', 'buddypress' ) );
		parent::WP_Widget( $id = 'listaAmici_Widget', $name = 'Lista Amici', $options = array( 'titolo' => __('Amici','custom'), 'numero' =>3 ));
		
	}
	

	function form($instance)
	{
		// outputs the options form on admin
		$instance = wp_parse_args( (array) $instance, array(  'titolo' => __('Amici','custom') , 'numero' => 5) );
		$numero = strip_tags( $instance['numero'] );
		$titolo = strip_tags( $instance['titolo'] );

		?>
		<p><label>Titolo		<input id="<?php echo $this->get_field_id( 'titolo' ); ?>" name="<?php echo $this->get_field_name( 'titolo' ); ?>" type="text" value="<?php echo esc_attr( $titolo ); ?>"  style="width: 50%; float:right;"  /></label></p>
		<p><label>Numero Amici 	<input id="<?php echo $this->get_field_id( 'numero' ); ?>" name="<?php echo $this->get_field_name( 'numero' ); ?>" type="text" value="<?php echo esc_attr( $numero ); ?>"  style="width: 50%; float:right;"  /></label></p>
		
		<?php 
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['titolo'] = __(strip_tags( $new_instance['titolo'] ),"custom");
		$instance['numero'] = __(strip_tags( $new_instance['numero'] ),"custom");
		return $instance;
	}

	function widget($args, $instance)
	{
		global $bp;
		$user_ID=bp_displayed_user_id();

		if ($user_ID==0) $user_ID=$bp->displayed_user->id;
		if ($user_ID==0) {
			//non visualizzo nulla
			}
		else
		{
		
		
		$attivo=array();
		extract($args);
		$title = apply_filters('widget_title', empty($instance['titolo']) ? __('Amici','custom') : $instance['titolo'], $instance, $this->id_base);
		// outputs the content of the widget
		$numero =  empty($instance['numero']) ? 10000 : $instance['numero'];
		
		if ( $title )
			echo $before_title . $title . $after_title;
			
			$listfriend = friends_get_friend_user_ids($user_ID);
			$cont=0;
			
			echo "<div>";
			foreach ($listfriend as $k => $v){
				$attivo = get_userdata($v);
				
				if ($cont<$numero){
					echo  "<a href='".bp_core_get_user_domain($attivo->user_login).$attivo->user_login."' >".get_avatar($v,42)."</a>";
					$cont++;
				}
			}
			echo "</div>";
		?>
		
	
		
		<?php 
	
		}

		
	}

}
?>
