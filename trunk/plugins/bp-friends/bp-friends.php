<?php 
/**
 * @Author	Giovanni Giannone
 * @link http://
 * @Package Wordpress
 * @SubPackage Widgets
 * @copyright 
 * @Since 1.0.0
 * 
 * Plugin Name: bp-friends
 * Plugin URI: 
 * Description: Questo plugin aggiunge un widget per gli utenti buddypress che visualizza gli avatar degli amici.
 * Version: 1.0.0
 * Author: Giovanni Giannone
 * Author URI: http://www.
 * 
 * 
 */
 
 
/*
 * widget, visitando un profilo devo visualizzare la lista dei fornitori 
 * se il profilo è albergo, o dei clienti se il profilo è fornitore.
 * I fornitori di $nome sono:
...
	se io non sono loggato devo visualizzare nel profilo pubblico i forntori o alberghi
 * */
 
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
add_action( 'widgets_init', create_function( '', 'register_widget("bp_friends");' ) );

/**
 * 
 * @author Giovanni Giannone
 * Document Widget
 */
class bp_friends extends WP_Widget
{
	/**
	 * Constructor
	 * 
	 * Registers the widget details with the parent class
	 */
	var $fr_type=null;
	function bp_friends()
	{
		//parent::WP_Widget( $id = 'bpcustom_Widget', $name = 'Amici', $options = array( 'titolo' => 'Amici', 'numero' =>3 ));
		parent::WP_Widget( $id = 'bp_friends', $name = 'Friends');
	}
	

	function form($instance)
	{
		// outputs the options form on admin
/*
		?>
		<p><label>Titolo		<input id="<?php echo $this->get_field_id( 'titolo' ); ?>" name="<?php echo $this->get_field_name( 'titolo' ); ?>" type="text" value="<?php echo esc_attr( $titolo ); ?>" style="width: 30%" /></label></p>
		<p><label>Numero Amici 	<input id="<?php echo $this->get_field_id( 'numero' ); ?>" name="<?php echo $this->get_field_name( 'numero' ); ?>" type="text" value="<?php echo esc_attr( $numero ); ?>" style="width: 30%" /></label></p>
		
		<?php 
*/
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		//$instance['titolo'] = strip_tags( $new_instance['titolo'] );
		//$instance['numero'] = strip_tags( $new_instance['numero'] );
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
		$user_info = get_userdata($user_ID);
		
		/*
		 * $user_info = get_userdata(1);
      echo 'Username: ' . $user_info->user_login . "\n";
      echo 'User level: ' . $user_info->user_level . "\n";
      echo 'User ID: ' . $user_info->ID . "\n";
		 * 
		 * deprecato
		 * $userinfo = get_userdatabylogin($username);
		 * $userinfo->user_status
		*/
		$user_type=$this->get_type($user_info->ID);
		if ($user_type=="Fornitore" || $user_type=="Albergo/Ristorante" || $user_type=="Utente")
		{
			if ($user_type=="Fornitore")
				$title = apply_filters('widget_title', empty($instance['titolo']) ? __('I clienti di ').bp_get_displayed_user_fullname(): $instance['titolo'], $instance, $this->id_base);
			else if ($user_type=="Albergo/Ristorante")
				$title = apply_filters('widget_title', empty($instance['titolo']) ? __('I fornitori di ').bp_get_displayed_user_fullname(): $instance['titolo'], $instance, $this->id_base);
			else if ($user_type=="Utente")
				$title = apply_filters('widget_title', empty($instance['titolo']) ? __('Gli amici di ').bp_get_displayed_user_fullname(): $instance['titolo'], $instance, $this->id_base);
				
			// outputs the content of the widget
		

		//echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title.'<div class="avatar-block">';

		
			$listfriend = friends_get_friend_user_ids($user_ID);
			
			foreach ($listfriend as $k => $v){
				$attivo = get_userdata($v);		
					if ($user_type=="Fornitore" && $this->get_type($v)=="Albergo/Ristorante"){
						echo  "<a href='".bp_core_get_user_domain($attivo->user_login).$attivo->user_login."' >".get_avatar($v,42)."</a>";	
					}
					else if ($user_type=="Albergo/Ristorante" && $this->get_type($v)=="Fornitore"){
						echo  "<a href='".bp_core_get_user_domain($attivo->user_login).$attivo->user_login."' >".get_avatar($v,42)."</a>";
					}
					else if ($user_type=="Utente"){
						echo  "<a href='".bp_core_get_user_domain($attivo->user_login).$attivo->user_login."' >".get_avatar($v,42)."</a>";
					}					
			}
		
			echo "<br /><br />";
			echo "<br /><br /></div>".$after_widget;

			
			?>
		
	
			


			<?php 
		}
		}
	}
	
	//effettua la query per caricare tutti i tipi dei vari utenti
	function globalType(){
		global $bp;
		global $wpdb;
	
		$query = "SELECT d.user_id, d.value FROM wp_bp_xprofile_data d WHERE d.value='Albergo/Ristorante' OR d.value='Fornitore' OR d.value='Utente'";
		$ms_output= $wpdb->get_results( $wpdb->prepare($query));
		$this->fr_type=$ms_output;
		
	}
	//ritorna il tipo (Albergo/Ristorante o Fornitore) dell'utente avente id =$ID
	function get_type($ID)
	{
		if ($this->fr_type==null)
			 $this->globalType();
			 
		
		
		foreach ( (array)$this->fr_type as $k){
			if ($k->user_id==$ID)
				return $k->value;
		}
		return "Non riconosciuto";
	}
}


//INSERT INTO wp_bp_xprofile_fields ( group_id, parent_id, type, name , description , is_required , is_default_option, field_order, option_order , order_by, can_delete) VALUES (1,2,'option','Utente','',0,1,0,1,'',1)
