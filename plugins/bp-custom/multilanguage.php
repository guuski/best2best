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
		
			<p>
				
				<label><?php _e('Titolo','custom') ?>		
				
					<input id="<?php echo $this->get_field_id( 'titolo' ); 		?>" name="<?php echo $this->get_field_name( 'titolo' ); 	?>" type="text" value="<?php echo esc_attr( $titolo ); 		?>" style="width: 60%; float:right;" />
					
				</label>
				
			</p>
		
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
	
}
?>
