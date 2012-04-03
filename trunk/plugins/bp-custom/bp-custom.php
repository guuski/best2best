<?php 
/**
 * @Author	Giovanni Giannone
 * @link http://
 * @Package Wordpress
 * @SubPackage Widgets
 * @copyright 
 * @Since 1.0.0
 * 
 * Plugin Name: WidgetAmici
 * Plugin URI: 
 * Description: Questo plugin aggiunge un widget per gli utenti buddypress che visualizza gli avatar degli amici.
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
add_action( 'widgets_init', create_function( '', 'register_widget("bpcustom_Widget");' ) );


/**
 * 
 * @author byrd
 * Document Widget
 */
class bpcustom_Widget extends WP_Widget
{
	/**
	 * Constructor
	 * 
	 * Registers the widget details with the parent class
	 */
	function bpcustom_Widget()
	{
		// widget actual processes
		parent::WP_Widget( $id = 'bpcustom_Widget', $name = get_class($this), $options = array( 'description' => 'bpcustom_Widget' ) );
	}
	

	function form($instance)
	{
		// outputs the options form on admin
		?>
		
		admin
		
		<?php 
	}

	function update($new_instance, $old_instance)
	{
		// processes widget options to be saved
		$instance = wp_parse_args($old_instance, $new_instance);
		return $instance;
	}

	function widget($args, $instance)
	{
		global $bp;
		//$user_ID=$bp->displayed_user->id;
		$user_ID=bp_displayed_user_id();
		
		$attivo=array();
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Amici') : $instance['title'], $instance, $this->id_base);
		// outputs the content of the widget
		
		//$title = apply_filters( 'widget_title', $instance['title'] );
		
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
			$listfriend = friends_get_friend_user_ids($user_ID);
		
			foreach ($listfriend as $k => $v){
				$attivo = get_userdata($v);
				
				 echo  "<a href='".bp_core_get_user_domain($attivo->user_login).$attivo->user_login."' >".get_avatar($v,42)."</a>";
				
				//echo  "<a href='".$attivo->user_url."' >".get_avatar($v,34)."</a>";
				//echo  "<a href='".$attivo->primary_blog."' >".get_avatar($v,34)."</a>";
				//echo bp_member_permalink()."permalink";
				//echo bp_member_name()."nome utente";
	 
			}
		?>
		
	
		
		<?php 
	}

}
