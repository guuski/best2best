<?php


class sidebarButtons_Widget extends WP_Widget
{
	
	function sidebarButtons_Widget()
	{
		$id_base = 'sidebarButtons_Widget';
		$name = __('Sidebar Buttons','custom');
		$widget_options = array( );
		$control_ops = array( 'width' => 300, 'height' => 350 );
		parent::WP_Widget($id_base, $name, $widget_options, $control_ops);
	}
	

	function form($instance)
	{
		$instance = wp_parse_args( (array) $instance, array(  'titolo' => __('Sidebar Buttons','custom') ));
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
		/*il widget con la lista referral invece andrebbe completato aggiungendo 
		 * la visualizzazione dei referral solo dell'utente di cui sto visitando il profilo.*/
		 
		global $bp;
		
		$user=0;
			
		if ($bp->loggedin_user->id!=0)
		
			$user = $bp->loggedin_user->id;
		
		extract($args);
			
		$title = apply_filters('widget_title', empty($instance['titolo']) ? __('Sidebar Buttons','custom') : $instance['titolo'], $instance, $this->id_base);
		
		$vR_link = "";
		
				
		//FRONT-END
		if ($user>0) //sono loggato
		{
			
			$attivo = get_userdata($user);
			
			$vR_link = bp_core_get_user_domain($user);
			
			$this->vr_getButtons( $lunghezza ,$numerolog, $vR_link , $user);
			
		}
	}
	
	//FUNZIONI SPECIFICHE DEL WIDGET
	function vr_getButtons($lunghezza, $numero, $vR_link ,$user)
	{
		global $bp;
		
?>
<style>a.smallbutton{padding: 0 10px; line-height: 18px;}</style>
<div style="border-bottom: 1px solid #000; margin-bottom: 5px; height: 85px;">
	<hr style="border: 0; border-top: 1px solid; margin: 5px 0;" />
	<a class="button smallbutton" style="float: left" href="/gruppi">Gruppi</a> <a
		class="button smallbutton" style="float: right" href="/adesioni/"
	>Adesioni</a>
	<a class="button smallbutton" style="float: left" href="<?=$vR_link?>friends"><?php _e('Mio Network','custom')?></a>
	<a class="button smallbutton" style="float: left" href="<?=$vR_link?>groups"><?php _e('Miei Gruppi','custom')?></a>
</div>
<?php	
	}
}
?>
