<?php 
/**
 * @Author	Giovanni Giannone
 * @link http://
 * @Package Wordpress
 * @SubPackage Widgets
 * @copyright 
 * @Since 1.0.0
 * 
 * Plugin Name: bp-message
 * Plugin URI: 
 * Description: Questo plugin aggiunge un widget per gli utenti buddypress che visualizza le anteprime dei Messaggi
 * Version: 1.0.0
 * Author: Giovanni Giannone
 * Author URI: http://www.
 * 
 * 
 */
 

defined('ABSPATH') or die("Cannot access pages directly.");

/**
 * Initializing 
 * 
 * The directory separator is different between linux and microsoft servers.
 * Thankfully php sets the DIRECTORY_SEPARATOR constant so that we know what
 * to use.
 */
defined("DS") or define("DS", DIRECTORY_SEPARATOR);

/**
 * Actions and Filters
 * 
 * Register any and all actions here. Nothing should actually be called 
 * directly, the entire system will be based on these actions and hooks.
 */
add_action( 'widgets_init', create_function( '', 'register_widget("bpmessage_Widget");' ) );

/**
 * 
 * @author byrd
 * Document Widget
 */
class bpmessage_Widget extends WP_Widget
{
	/**
	 * Constructor
	 * 
	 * Registers the widget details with the parent class
	 */
	function bpmessage_Widget()
	{
		parent::WP_Widget( $id = 'bpmessage_Widget', $name = 'bp-message', $options = array( 'titolo' => 'Messaggi', 'numero'=> 3, 'messaggio'=> ''));	
	}
	

	function form($instance)
	{
		// outputs the options form on admin
		$instance = wp_parse_args( (array) $instance, array(  'titolo' => 'Messaggi' , 'numero'=> 5, 'messaggio' => '') );
		$messaggio = strip_tags( $instance['messaggio'] );
		$numero = strip_tags( $instance['numero'] );
		$titolo = strip_tags( $instance['titolo'] );

		?>
		
		<p><label>Titolo	<input id="<?php echo $this->get_field_id( 'titolo' ); 	?>" name="<?php echo $this->get_field_name( 'titolo' ); ?>" type="text" value="<?php echo esc_attr( $titolo ); ?>" style="width: 30%" /></label></p>
		<p><label>Numero	<input id="<?php echo $this->get_field_id( 'numero' );?>" name="<?php echo $this->get_field_name( 'numero' ); ?>" type="text" value="<?php echo esc_attr( $numero ); ?>" style="width: 30%" /></label></p>
		<p><label>Messaggi	<input id="<?php echo $this->get_field_id( 'messaggio' );?>" name="<?php echo $this->get_field_name( 'messaggio' ); ?>" type="text" value="<?php echo esc_attr( $messaggio ); ?>" style="width: 70%" /></label></p>
		
		<?php 
		$messaggi = $this->getMessage();
			$num=0;
			foreach ($messaggi as $k => $v){
				if ($num<$numero){
					echo $v->date_sent." ".$v->message."<br />";
					$num++;
				}
			}
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['titolo'] = strip_tags( $new_instance['titolo'] );
		$instance['numero'] = strip_tags( $new_instance['numero'] );
		$instance['messaggio'] = strip_tags( $new_instance['messaggio'] );
		
		if ($instance['messaggio']!=''){
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
			$title = apply_filters('widget_title', empty($instance['titolo']) ? 'Amici' : $instance['titolo'], $instance, $this->id_base);
			// outputs the content of the widget
			$numero =  empty($instance['numero']) ? 10000 : $instance['numero'];			
			if ( $title )
				echo $before_title . $title . $after_title;
				
			$messaggi = $this->getMessage();
			$num=0;
			foreach ($messaggi as $k => $v){
				if ($num<$numero){
					echo $v->date_sent." ".$v->message."<br />";
					$num++;
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
		return get_user_meta($user_id, "Messaggi Amministratore", true);
	}
	function update_meta($user_id,$meta_value){
		update_user_meta( $user_id, "Messaggi Amministratore", $meta_value, '' );
	}
	function delete_meta($user_id){
		delete_user_meta( $user_id, "Messaggi Amministratore");
	}
	function add_meta($user_id){
		add_user_meta( $user_id, "Messaggi Amministratore", '', true );
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
		global $bp;
		global $wpdb;
		global $user_ID;
		$query = "INSERT INTO wp_bp_messages_messages ( thread_id, sender_id, subject, message , date_sent) VALUES ( 1,1,'Comunicazioni Amministratore','$message',CURRENT_TIMESTAMP)";
		echo $query;
		$wpdb->get_results( $wpdb->prepare($query));
		
	}
	function deleteALLMessage(){
		global $bp;
		global $wpdb;
		global $user_ID;
		$query = "INSERT INTO wp_bp_messages_messages ( thread_id, sender_id, subject, message , date_sent) VALUES ( 1,1,'Comunicazioni Amministratore','$message',CURRENT_TIMESTAMP)";
		$wpdb->get_results( $wpdb->prepare($query));
	}
	function getMessagebyThreadId($thread_id){
		
	}
}
