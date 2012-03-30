<?php 
/**
 * @Author	Jonathon byrd
 * @link http://www.jonathonbyrd.com
 * @Package Wordpress
 * @SubPackage Widgets
 * @copyright Proprietary Software, Copyright Byrd Incorporated. All Rights Reserved
 * @Since 1.0.0
 * 
 * Plugin Name: Document Widget
 * Plugin URI: http://www.redrokk.com
 * Description: <a href="http://redrokk.com" target="_blank">redrokk</a> Designs and develops software for WordPress, Drupal, Joomla, Cakephp, SugarCRM, Symfony and more!
 * Version: 1.0.0
 * Author: redrokk
 * Author URI: http://www.redrokk.com
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
add_action( 'widgets_init', create_function( '', 'register_widget("NetworkList_Widget");' ) );


/**
 * 
 * @author byrd
 * Document Widget
 */
class NetworkList_Widget extends WP_Widget
{
	/**
	 * Constructor
	 * 
	 * Registers the widget details with the parent class
	 */
	function NetworkList_Widget()
	{
		// widget actual processes
		parent::WP_Widget( $id = 'foo_widget', $name = get_class($this), $options = array( 'description' => 'A Foo Widget' ) );
	}

	function form($instance)
	{
		// outputs the options form on admin
		?>
		
		Form goes here
		
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
		// outputs the content of the widget
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		?>
		
		Widget view to users goes here
		
		<?php 
	}

}
