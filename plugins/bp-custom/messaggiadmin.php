<?php

//questo plugin è da finire


class MessaggiAdmin_Widget extends WP_Widget{
	/**
	 * Constructor
	 * 
	 * Registers the widget details with the parent class
	 */
	function MessaggiAdmin_Widget()
	{
		$id_base = 'MessaggiAdmin_Widget';
		$name = __('Messaggi Admin','custom');
		$widget_options = array( 'titolo' => __('Messaggi','custom'), 'numero'=> 3, 'messaggio'=> '');
		$control_ops = array( 'width' => 600, 'height' => 350 );
		
		parent::WP_Widget($id_base, $name, $widget_options, $control_ops);
	}
	

	function form($instance)
	{
		// outputs the options form on admin
		$instance = wp_parse_args( (array) $instance, array(  'titolo' => __('Messaggi','custom') , 'numero'=> 5, 'messaggio' => '') );
		$messaggio = __(strip_tags( $instance['messaggio'] ),'custom');
		$numero = __(strip_tags( $instance['numero'] ),'custom');
		$titolo = __(strip_tags( $instance['titolo'] ),'custom');

		
		?>
		<table style='font-size:12px;'>
		<tr>
			<td>
				<div>
					<p><label>Titolo	<input id="<?php echo $this->get_field_id( 'titolo' ); 	?>" name="<?php echo $this->get_field_name( 'titolo' ); ?>" type="text" value="<?php echo esc_attr( $titolo ); ?>" style="width: 50%; float:right;" /></label></p>
					<p><label>Numero	<input id="<?php echo $this->get_field_id( 'numero' );	?>" name="<?php echo $this->get_field_name( 'numero' ); ?>" type="text" value="<?php echo esc_attr( $numero ); ?>" style="width: 50%; float:right;" /></label></p>
					<!--
					<p><label>Messaggi	<textarea id='ma_messaggi' name='ma_messaggi' type="text" style="width: 50%; float:right;" onkeyup="jQuery('input#'+'<?php echo $this->get_field_name( 'messaggio');?>').val(jQuery('input#'+'ma_messaggi').val())"><?php echo esc_attr( $messaggio);?></textarea></label></p>
					<p><label>Messaggi	<input id="<?php echo $this->get_field_id( 'messaggio');?>" name="<?php echo $this->get_field_name('messaggio');?>" type="text" value="<?php echo esc_attr( $messaggio);?>"style="width: 50%; height:100px; float:right;" /></label></p>
					-->
					<p><label>Messaggi	<br /><textarea id="<?php echo $this->get_field_id( 'messaggio');?>" name="<?php echo $this->get_field_name('messaggio');?>" type="text" style="width: 200px; height:200px; float:left;"><?php echo esc_attr( $messaggio);?></textarea></label></p>
				</div>
			</td>
			<td>
				<div>
					<?php 
						$messaggi = $this->getMessage();
						$num=0;
						foreach ($messaggi as $k => $v){
							if ($num<$numero){
								echo "<p>".($num+1).") ".$v->date_sent." <b>".__($v->message,'custom')."</b></p>";
								$num++;
							}
						}
					?>
				</div>
			</td>
			
		</tr>
		</table>
		<?php
	}

	function update($new_instance, $old_instance)
	{
		echo "update";
		$instance = $old_instance;
		$instance['titolo'] 	= __(strip_tags( $new_instance['titolo'] ) 		,'custom');
		$instance['numero'] 	= __(strip_tags( $new_instance['numero'] )		,'custom');
		$instance['messaggio'] 	= __( $new_instance['messaggio'] 	,'custom');
		
		if ($instance['messaggio']!=''){
			echo $message;
			$this->saveMessage($instance['messaggio']);
			$instance['messaggio']='';
		}
		return $instance;
	}

	function widget($args, $instance)
	{
		global $user_ID;
		
		if ($user_ID==0) {
			//non visualizzo nulla
			}
		else
		{	
			extract($args);
			$title = apply_filters('widget_title', empty($instance['titolo']) ? __('Amici','custom') : $instance['titolo'], $instance, $this->id_base);
			// outputs the content of the widget
			$numero =  empty($instance['numero']) ? 10000 : $instance['numero'];	
			$messaggi = $this->getMessage();
			if (count($messaggi)>0){
			/*
				if ( $title )
					echo $before_title . $title . $after_title;
					*/
				echo "<br /><b>".__('Comunicazioni','custom')."</b><br />";
				$num=0;
				foreach ($messaggi as $k => $v){
					if ($num<$numero){
						//echo $v->date_sent." ".$v->message."<br />";
						
						//verifico se è stato creato il metadato in caso contrario lo creo
						if ($this->get_meta($user_ID)==""){
							$this->add_meta($user_ID);
							//echo "creato metadato per ".$user_ID."<br />";
						}	
						//verifico se l'utente non ha letto il messaggio	
						if ($this->get_meta($user_ID)==""){
							echo ($num+1).") ".__($v->message,'custom')."<br />";
							$num++;
						}
						//echo $this->get_meta($user_ID);
					}
				}
			
			}

		
		}
	}
	//==================================================================
	/*
	wp_usermeta
		`umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		`user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
		`meta_key` varchar(255) DEFAULT NULL,
		`meta_value` longtext
	
	
	get_user_meta($user_id, $key, $single); 
	add_user_meta( $user_id, $meta_key, $meta_value, $unique ) 
	update_user_meta( $user_id, $meta_key, $meta_value, $prev_value ) 
	delete_user_meta( $user_id, $meta_key, $meta_value )
	
	*/

	//==================================================================
	function get_meta($user_id){
		//echo "<br />".$user_id." ".$key."<br />";
		return get_user_meta($user_id, 'MessaggiAdmin', true);
	}
	function update_meta($user_id,$meta_value){
		update_user_meta( $user_id, 'MessaggiAdmin', $meta_value, '' );
	}
	function delete_meta($user_id){
		delete_user_meta( $user_id, 'MessaggiAdmin');
	}
	function add_meta($user_id){
		//metavalue è false di partenza ed indica che il messaggio non è stato letto
		add_user_meta( $user_id, 'MessaggiAdmin', '', true );
	}
	
	
	
	function getNumberOfMessageUnread(){
		global $bp;
		global $wpdb;
		global $user_ID;
		
		if ($user_ID==0) return null;
		else{
			return messages_get_unread_count($user_ID);
			//ritorna il numero di messaggi non letti;
			//return messages_get_unread_count($user_ID);
			
			
			}
	}
	function getMessage(){
		global $bp;
		global $wpdb;
		$query = "SELECT id, message, date_sent FROM wp_bp_messages_messages WHERE thread_id=1 AND subject='Comunicazioni Amministratore' ORDER BY date_sent DESC";
		$ms_output= $wpdb->get_results( $wpdb->prepare($query));
		return $ms_output;
	}
	function saveMessage($message){
		echo "savemessage";
		global $bp;
		global $wpdb;
		global $user_ID;
		
		$query = "INSERT INTO wp_bp_messages_messages ( thread_id, sender_id, subject, message , date_sent) VALUES ( 1,1,'Comunicazioni Amministratore','$message',CURRENT_TIMESTAMP)";
		echo $query;
		$wpdb->get_results( $wpdb->prepare($query));
		
	}
}


?>
