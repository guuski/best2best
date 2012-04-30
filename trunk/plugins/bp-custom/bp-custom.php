<?php 
/**
 * @Author	Giovanni Giannone
 * @link http://
 * @Package Wordpress
 * @SubPackage Widgets
 * @copyright 
 * @Since 1.0.0
 * 
 * Plugin Name: bp-custom
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



//=========================================================================================================================
//===============LISTA AMICI===============================================================================================
//=========================================================================================================================

add_action( 'widgets_init', create_function( '', 'register_widget("listaAmici_Widget");' ) );
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
		parent::WP_Widget( $id = 'listaAmici_Widget', $name = 'Lista Amici', $options = array( 'titolo' => 'Amici', 'numero' =>3 ));
		
	}
	

	function form($instance)
	{
		// outputs the options form on admin
		$instance = wp_parse_args( (array) $instance, array(  'titolo' => 'Amici' , 'numero' => 5) );
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
		$instance['titolo'] = strip_tags( $new_instance['titolo'] );
		$instance['numero'] = strip_tags( $new_instance['numero'] );
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
		$title = apply_filters('widget_title', empty($instance['titolo']) ? 'Amici' : $instance['titolo'], $instance, $this->id_base);
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

//=========================================================================================================================
//===============COMPLETA PROFILO==========================================================================================
//=========================================================================================================================

add_action( 'widgets_init', create_function( '', 'register_widget("completaProfilo_Widget");' ) );
class completaProfilo_Widget extends WP_Widget {
	/**
	 * Constructor
	 * 
	 * Registers the widget details with the parent class
	 */
	var $fr_type=null;
	function completaProfilo_Widget()
	{
		$id_base = 'completaProfilo_Widget';
		$name = 'Completa Profilo';
		$widget_options = array( 'titolo' => 'Completamento Profilo ');
		$control_ops = '';
		
		parent::WP_Widget($id_base, $name, $widget_options, $control_ops);
		
	}
	

	function form($instance)
	{
		// outputs the options form on admin
		$instance = wp_parse_args( (array) $instance, array(  'titolo' => 'Completamento Profilo ' ));
		$titolo = strip_tags( $instance['titolo'] );

		?>
			<label>Titolo <input id="<?php echo $this->get_field_id( 'titolo' ); ?>" name="<?php echo $this->get_field_name( 'titolo' ); ?>" type="text" value="<?php echo esc_attr( $titolo ); ?>"  style="width: 70%; float:right;"  /></label>
		<?php 
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['titolo'] = strip_tags( $new_instance['titolo'] );
		return $instance;
	}
	
	function get_current_field($id){
		global $bp;
		global $wpdb;
	
		$query = "SELECT d.field_id, d.value FROM wp_bp_xprofile_data d WHERE d.user_id=$id";
		$ms_output= $wpdb->get_results( $wpdb->prepare($query));
		
		return $ms_output;
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
	function get_type($ID){
		if ($this->fr_type==null)
			 $this->globalType();
			 
		foreach ( (array)$this->fr_type as $k){
			if ($k->user_id==$ID)
				return $k->value;
		}
		return "Non riconosciuto";
	}
	
	function get_total_field(){
		global $bp;
		global $wpdb;
		global $user_ID;
	
		$query = "SELECT f.id , f.group_id, f.name FROM wp_bp_xprofile_fields f WHERE parent_id=0";
		$ms_output= $wpdb->get_results( $wpdb->prepare($query));
		
		$user_info = get_userdata($user_ID);
		//$user_type=$this->get_type($user_info->ID);
		$user_type=BP_XProfile_ProfileData::get_value_byfieldname("Tipo profilo");
		
		$output=array();
		$count=0;
		foreach ($ms_output as $k =>$value){
			if ($value->name=='Categoria' ||
				$value->name=='Categoria attivita&#39;' ||
				$value->name=='Lista aree di copertura' ||
				$value->name=='Descrivi prodotti / servizi' ||
				$value->name=='Macro categoria attività') {
					if ($user_type=='Fornitore'){
						$output[$count]=$value;
						$count++;
						}
			} else if (	$value->name=='Numero letti / coperti' || 
						$value->name=='Numero stelle' ) {
							if ($user_type=='Albergo/Ristorante' ){
								$output[$count]=$value;
								$count++;
							}
			} else {
				$output[$count]=$value;
				$count++;
				}
		}
		return $output;
	}
	function adatta($tot,$parz){
		
		foreach ($parz as $k => $v){
			$trovato=false;
			foreach ($tot as $k1 =>$v1){
				
				if ($v->field_id==$v1->id && $v->value!='' && $v->value!='a:0:{}'){
					$trovato=true;
					//echo  "#".$v->value."#<br />";
				}
			}
			
			if (!$trovato){
				unset($parz[$k]);
				
				}
			}
		return $parz;
	}
	function widget($args, $instance)
	{
		global $bp;
		global $user_ID;
		
		if ($user_ID==0) {
			//non visualizzo nulla
			}
		else
		{
		echo $this->ms_getScript();//carico gli script
		
		$attivo=array();
		extract($args);
		$title = apply_filters('widget_title', empty($instance['titolo']) ? 'Completamento Profilo ' : $instance['titolo'], $instance, $this->id_base);
		
		//analizzo il profilo
		$current = $this->get_current_field($user_ID);
		$total = $this->get_total_field();
		$current = $this->adatta($total,$current);
		
		$cPar=count($current);
		$cTot=count($total);
		$profilo="";
		foreach($total as $k => $v) {
			$trovato=false;
			$visibile=false;
			
			foreach($current as $kcur => $vcur) {
				if ($vcur->field_id==$v->id) $trovato=true;
			}
			
			if ($trovato)
				$profilo.= "<b style='text-decoration: line-through;'>".$v->name."</b><br />";
			else
				$profilo.= $v->name."<br />";
			//$cTot++;
		}
		
		
		//if ( $title )
			//echo $before_title . $title .(int)(100*$cPar/$cTot)."% ". $after_title;
			
		if ($cPar>=$cTot) { 
			$perc=(int)(100*$cPar/$cTot);
			echo "		<div class='box_percentuale_profilo'
								onmouseover='ms_labelprofiloon(this)' 
								onmouseout='ms_labelprofilooff(this)'
								onclick='ms_openprofilo(\"completaprofilo\")'>
								
								<div class='box_percentuale_profilo_perc' style='width:$perc%;'
								onmouseover='ms_labelprofiloon(this)' 
								onmouseout='ms_labelprofilooff(this)'></div> 
								
								<label value='chiuso' 
								onmouseover='ms_labelprofiloon(this)' 
								onmouseout='ms_labelprofilooff(this)'>			
									Profilo Completo " .$perc."% 
								</label>
							
						</div>
							
							<div id=\"completaprofilo\" class='box_completa_profilo' style='display:none;'>
								$profilo
							</div>";
		}
		else {
			//completamento Registrazione===================================
			$perc=(int)(100*$cPar/$cTot);
			echo "		
			
						Migliora la tua visibilità:<br />
						<a href='".bp_loggedin_user_domain()."profile/edit/group' style='width:80%;'>
							Completa il tuo profilo!
						</a>
						<div class='box_percentuale_profilo'
								onmouseover='ms_labelprofiloon(this)' 
								onmouseout='ms_labelprofilooff(this)'
								onclick='ms_openprofilo(\"completaprofilo\")'>
								
								<div class='box_percentuale_profilo_perc' style='width:$perc%;'
								onmouseover='ms_labelprofiloon(this)' 
								onmouseout='ms_labelprofilooff(this)'></div> 
								
								<label value='chiuso' 
								onmouseover='ms_labelprofiloon(this)' 
								onmouseout='ms_labelprofilooff(this)'>			
									Completamento Profilo " .$perc."% 
								</label>
							
						</div>
							
							<div id=\"completaprofilo\" class='box_completa_profilo' style='display:none;'>
								$profilo
							</div>
					
					";
							
		
		}
			//==============================================================
		?>
		
	
		
		<?php 
	
		}

		
	}
	
	function ms_getScript(){ ?>
	
	<script language='JavaScript' type='text/javascript'>
	<!--

		function ms_openprofilo(divname)
		{
			if (jQuery('div#'+divname).val()=="aperto") {
				jQuery('div#'+divname).hide("slow");	
				jQuery('div#'+divname).val("chiuso");
				//jQuery('div#'+divname).css("color","red");
			}
			else{
				jQuery('div#'+divname).val("aperto");
				jQuery('div#'+divname).show("slow");
			}
		}
		function ms_labelprofiloon(t)
		{	
			t.style.cursor = 'pointer';
		}

		function ms_labelprofilooff(t)
		{
			t.style.cursor = 'default';
		}	
	//-->
	</script>
	
	<script>
	<!--
		function ms_labelon(t)
		{	
			t.style.color = '#ffffff';
			t.style.background ='#eaeaea' ;
			t.style.cursor = 'pointer';
		}

		function ms_labeloff(t)
		{
			t.style.color = 'rgb(68,68,68)';
			t.style.background ='#ffffff' ;
			t.style.cursor = 'default';
		}	
	//-->	
	</script>
	
	<style type='text/css'>
		.box_percentuale_profilo{
			position:relative;
			background-color:#87badd;
			color:rgb(68,68,68);
			border:2px solid #ffffff;
			font-weight:bold;
			height:20px;
			}
		.box_percentuale_profilo label{
			position:relative;
			margin: 0px auto;
			margin-left:10px;
			color:#ffffff;
			}
			
		.box_percentuale_profilo_perc{
			position:absolute;
			top:0px; left:0px; bottom:0px;
			margin:0px; 
			padding:0px; 
			height:100%; 
			background-color:#0a76b7;
			//border-right: 1px solid #555555;
		}
		.box_completa_profilo{
			border:2px solid #ffffff;
			border-top:0px;
			padding-left:4px;
			background-color:white;
		}
	</style>

	<?php
	
	}

}

 
//=========================================================================================================================
//===============LISTA FORNITORI E ALBERGHI================================================================================
//=========================================================================================================================

add_action( 'widgets_init', create_function( '', 'register_widget("FornitoriAlberghi_Widget");' ) );
class FornitoriAlberghi_Widget extends WP_Widget {
	/**
	 * Constructor
	 * 
	 * Registers the widget details with the parent class
	 */
	var $fr_type=null;
	function FornitoriAlberghi_Widget(){
		//parent::WP_Widget( $id = 'bpcustom_Widget', $name = 'Amici', $options = array( 'titolo' => 'Amici', 'numero' =>3 ));
		parent::WP_Widget( $id = 'FornitoriAlberghi_Widget', $name = 'Fornitori Alberghi');
	}
	

	function form($instance){

		echo "Questo widget se l'utente è un fornitore, visualizza la lista degli alberghi suoi amici, se invece è un albergo, visualizza la lista dei fornitori";
			

		// outputs the options form on admin
/*
		?>
		<p><label>Titolo		<input id="<?php echo $this->get_field_id( 'titolo' ); ?>" name="<?php echo $this->get_field_name( 'titolo' ); ?>" type="text" value="<?php echo esc_attr( $titolo ); ?>" style="width: 30%" /></label></p>
		<p><label>Numero Amici 	<input id="<?php echo $this->get_field_id( 'numero' ); ?>" name="<?php echo $this->get_field_name( 'numero' ); ?>" type="text" value="<?php echo esc_attr( $numero ); ?>" style="width: 30%" /></label></p>
		
		<?php 
*/
	}

	function update($new_instance, $old_instance){
		$instance = $old_instance;
		//$instance['titolo'] = strip_tags( $new_instance['titolo'] );
		//$instance['numero'] = strip_tags( $new_instance['numero'] );
		return $instance;
	}

	function widget($args, $instance){
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
			echo "</div>";

			
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
	function get_type($ID){
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


//=========================================================================================================================
//===============MESSAGGI ADMIN============================================================================================
//=========================================================================================================================
add_action( 'widgets_init', create_function( '', 'register_widget("MessaggiAdmin_Widget");' ) );
class MessaggiAdmin_Widget extends WP_Widget{
	/**
	 * Constructor
	 * 
	 * Registers the widget details with the parent class
	 */
	function MessaggiAdmin_Widget()
	{
		$id_base = 'MessaggiAdmin_Widget';
		$name = 'Messaggi Admin';
		$widget_options = array( 'titolo' => 'Messaggi', 'numero'=> 3, 'messaggio'=> '');
		$control_ops = array( 'width' => 600, 'height' => 350 );
		
		parent::WP_Widget($id_base, $name, $widget_options, $control_ops);
	}
	

	function form($instance)
	{
		// outputs the options form on admin
		$instance = wp_parse_args( (array) $instance, array(  'titolo' => 'Messaggi' , 'numero'=> 5, 'messaggio' => '') );
		$messaggio = strip_tags( $instance['messaggio'] );
		$numero = strip_tags( $instance['numero'] );
		$titolo = strip_tags( $instance['titolo'] );

		
		?>
		<table style='font-size:12px;'>
		<tr>
			<td>
				<div>
					<p><label>Titolo	<input id="<?php echo $this->get_field_id( 'titolo' ); 	?>" name="<?php echo $this->get_field_name( 'titolo' ); ?>" type="text" value="<?php echo esc_attr( $titolo ); ?>" style="width: 50%; float:right;" /></label></p>
					<p><label>Numero	<input id="<?php echo $this->get_field_id( 'numero' );	?>" name="<?php echo $this->get_field_name( 'numero' ); ?>" type="text" value="<?php echo esc_attr( $numero ); ?>" style="width: 50%; float:right;" /></label></p>
					<p><label>Messaggi	<input id="<?php echo $this->get_field_id( 'messaggio');?>" name="<?php echo $this->get_field_name('messaggio');?>" type="text" value="<?php echo esc_attr( $messaggio);?>"style="width: 50%; float:right;" /></label></p>
				</div>
			</td>
			<td>
				<div>
					<?php 
						$messaggi = $this->getMessage();
						$num=0;
						foreach ($messaggi as $k => $v){
							if ($num<$numero){
								echo "<p>".($num+1).") ".$v->date_sent." <b>".$v->message."</b></p>";
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
			$messaggi = $this->getMessage();
			if (count($messaggi)>0){
			/*
				if ( $title )
					echo $before_title . $title . $after_title;
					*/
				echo "<br /><b>Comunicazioni</b><br />";
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
						if ($this->get_meta($user_ID)=="false"){
							echo ($num+1).") ".$v->message."<br />";
							$num++;
						}
						//echo $this->get_meta($user_ID);
					}
				}
			
			}

		
		}
	}
	/*
	 * Come inviare un post e leggerlo
	 * 
	 *
	 * =================================================================
	 $(function() {
 
    $('#text').keyup(function(event) {
 
        var char = event.keyCode;
        var value = $(this).val();
 
        if(char == 188) {
 
            $(this).val(value.replace(/<+/g, '&lt;'));
 
        }
 
        if(value.indexOf('>') != -1) {
 
            $(this).val(value.replace(/>+/g, '&gt;'));
 
        }  
    });
 
});
	 * */
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

