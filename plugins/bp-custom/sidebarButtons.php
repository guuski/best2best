<?php


class sidebarButtons_Widget extends WP_Widget
{
	
	function sidebarButtons_Widget()
	{
		$id_base = 'sidebarButtons_Widget';
		
		$name = __('Sidebar Buttons','custom');
		
		$widget_options = array( );
		
		$control_ops = array( 'width' => 300, 'height' => 350 );
		
		wp_enqueue_script( 'bp-jquery-autocomplete', '/wp-content/plugins/buddypress/bp-messages/js/autocomplete/jquery.autocomplete.js', array( 'jquery' ), '20110723' );
		wp_enqueue_script( 'bp-jquery-autocomplete-fb','/wp-content/plugins/buddypress/bp-messages/js/autocomplete/jquery.autocompletefb.js', array(), '20110723' );
		wp_enqueue_style( 'bp-messages-autocomplete', '/wp-content/plugins/buddypress/bp-messages/css/autocomplete/jquery.autocompletefb.css', array(), '20110723' );
		wp_enqueue_script( 'bp-jquery-bgiframe', '/wp-content/plugins/buddypress/bp-messages/js/autocomplete/jquery.bgiframe.js', array(), '20110723' );
		wp_enqueue_script( 'bp-jquery-dimensions', '/wp-content/plugins/buddypress/bp-messages/js/autocomplete/jquery.dimensions.js', array(), '20110723' );
		
		
		parent::WP_Widget($id_base, $name, $widget_options, $control_ops);
	}
	

	function form($instance)
	{
		$instance = wp_parse_args( (array) $instance, array(  'titolo' => __('Sidebar Buttons','custom') ));
		
		$titolo 		= __(strip_tags( $instance['titolo'] ),		'custom');
		
		?>
		
		<p>
			
			<label>
				
				<?php _e("Titolo","custom") ?>		
				
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
			
			$this->vr_getButtons( $vR_link );
			
		}
	}
	
	//FUNZIONI SPECIFICHE DEL WIDGET
	function vr_getButtons($vR_link )
	{
		global $bp;
		
?>

<style>
<!--
a.smallbutton {	padding: 0 0px;	line-height: 20px; width: 48%; }
.review-autoc .friend-tab {display:none;}
input.loading,input.loading:hover  {background-position: 85% 50%;}
-->
</style>

<div style=" height: 90px; width: 210px; margin: 5px 0 0 -10px; font-size: 12px; border: 0; border-top: 1px solid black; padding-top: 5px;">
	<a class="button smallbutton" style="float: left; font-size: 12px;" href="<?php echo wpml_get_home_url()?>gruppi"><?php _e('Gruppi','custom')?></a> 
	<a class="button smallbutton" style="float: right; font-size: 12px;" href="<?php bp_members_directory_permalink()?>"><?php _e('Adesioni','custom')?></a>
	
	<a class="button smallbutton" style="float: left; font-size: 12px;" href="<?=$vR_link?>friends"><?php _e('Mio Network','custom')?></a>

	<a class="button smallbutton" style="float: right; font-size: 12px;" href="<?=$vR_link?>groups"><?php _e('Miei Gruppi','custom')?></a>

	<div class = "add-reviews" style="margin-top:0; width:210px;height: 22px; position:relative; ">
		<a style="height: 14px;line-height: 14px;display:block;width:172px; font-size: 12px; background-color:none;" class="add-reviews button" title="Scrivi una recensione" onclick="jQuery('.review-autoc').fadeToggle()"><?php _e('Add Review','reviews')?></a>
	</div>
</div>
<div style="margin-bottom: 5px; border-bottom: 1px solid #000; width: 210px; margin-left: -10px; font-size: 12px; position:relative; display: block;">

<ul class="first acfb-holder review-autoc" style="display:none">
<li><h6 style="margin-top:0"><?php _e('Cerca una attivit&agrave; che vuoi recensire:','custom');?></h6></li>
		<li >
			<input type="text" name="send-to-input" class="send-to-input" id="send-to-input" style="width:205px" />
		</li>
	</ul>
</div>

<script>jQuery(document).ready(function(){
			var myac= jQuery("ul.review-autoc").autoCompletefb({urlLookup:'<?php echo site_url( 'wp-admin/admin-ajax.php'  ) ?>'});
			
			jQuery(".send-to-input",myac.params.ul).result(function(e,d,f){
				e.stopPropagation();
				var f = ".friend-tab".replace(/\./,'');
				var d = String(d).split(' (');
				var un = d[1].substr(0, d[1].length-1);
				var ln = '#link-' + un;
				var l = jQuery(ln).attr('href');
				location.href=l+'review/screen-two#user-activity';
				jQuery(".send-to-input",myac.params.ul).addClass('loading');
				 
				});
		});
		</script>
<?php
	//scan only	
	__('Attivit&agrave;','custom');
	__('Offerte','custom');
	__("Manca poco, stiamo implementando una nuova funzionalita' che vi permettera' di realizzare una vetrina dei vostri prodotti e servizi. \\n\\nContinuate a sostenerci. \\n - Lo staff.",'custom');
	__('Vorrei creare il nuovo profilo per %s','custom');
	__("Invia richiesta","custom");
	__("Non hai trovato il profilo che cercavi e vorresti crearlo? Invia un messaggio all'amministratore e ci penseremo noi per te.", 'custom' );
	__('Attenzione, questo account non è stato verificato. <a href="&#109;&#97;&#105;&#108;&#116;&#111;&#58;&#105;&#110;&#102;&#111;&#64;&#98;&#101;&#115;&#116;&#50;&#98;&#101;&#115;&#116;&#46;&#105;&#116;?subject=Credenziali+di+accesso&body=Gentile Amministratore,%0A%0A %0A%0Achiedo di avere le credenziali d’accesso per il profilo di '.xprofile_get_field_data( 'Nome', bp_displayed_user_id()).' ('.bp_displayed_user_id().'). %0A%0A%0A%0ACordiali saluti.">Contatta lo Staff Best2Best</a> se sei tu il proprietario','custom');
	__('Ghost Account','custom');
	__('Il network utile per i tuoi contatti commerciali','custom');
	__('La tua presenza ad una Fiera, un nuovo prodotto, un nuovo contratto, offerte speciali, promozioni…','custom');	
	}
}
?>
