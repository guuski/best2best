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
		//parent::WP_Widget( $id = 'bpcustom_Widget', $name = get_class($this), $options = array( 'description' => 'bpcustom_Widget' ) );
		//parent::WP_Widget( false, $name = __( 'Groups', 'buddypress' ) );
		parent::WP_Widget( $id = 'bpcustom_Widget', $name = 'Amici', $options = array( 'titolo' => 'Amici', 'numero' =>3 ));
	}
	

	function form($instance)
	{
		// outputs the options form on admin
		$instance = wp_parse_args( (array) $instance, array(  'titolo' => 'amici' , 'numero' => 5) );
		$numero = strip_tags( $instance['numero'] );
		$titolo = strip_tags( $instance['titolo'] );

		?>
		<p><label>Titolo		<input id="<?php echo $this->get_field_id( 'titolo' ); ?>" name="<?php echo $this->get_field_name( 'titolo' ); ?>" type="text" value="<?php echo esc_attr( $titolo ); ?>" style="width: 30%" /></label></p>
		<p><label>Numero Amici 	<input id="<?php echo $this->get_field_id( 'numero' ); ?>" name="<?php echo $this->get_field_name( 'numero' ); ?>" type="text" value="<?php echo esc_attr( $numero ); ?>" style="width: 30%" /></label></p>
		
		<?php 
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['titolo'] = strip_tags( $new_instance['titolo'] );
		$instance['numero'] = strip_tags( $new_instance['numero'] );
		return $instance;
	}

	function widget($args, $instance)
	{
		global $bp;
		//$user_ID=$bp->displayed_user->id;
		$user_ID=bp_displayed_user_id();
		if ($user_ID==0) $user_ID=$bp->displayed_user->id;
		if ($user_ID==0) {
			//non visualizzo nulla
			}
		else
		{
		
		
		$attivo=array();
		extract($args);
		$title = apply_filters('widget_title', empty($instance['titolo']) ? 'Amici' : $instance['titolo'], $instance, $this->id_base);
		// outputs the content of the widget
		$numero =  empty($instance['numero']) ? 10000 : $instance['numero'];
		
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
			$listfriend = friends_get_friend_user_ids($user_ID);
		
			$cont=0;
			foreach ($listfriend as $k => $v){
				$attivo = get_userdata($v);
				
				if ($cont<$numero){
					echo  "<a href='".bp_core_get_user_domain($attivo->user_login).$attivo->user_login."' >".get_avatar($v,42)."</a>";
					$cont++;
				}
			}
			
			echo "<br /><br />";
		?>
		
	
		
		<?php 
		}
	}

}
