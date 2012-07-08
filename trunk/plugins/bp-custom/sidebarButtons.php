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

<style>a.smallbutton {
padding: 0 0px;
line-height: 20px;
width: 48%;
}</style>
<div style="border-bottom: 1px solid #000; margin-bottom: 5px; height: 65px; width: 210px; margin-left: -10px; font-size: 12px">
	
	<hr style="border: 0; border-top: 1px solid; margin: 5px 0;" />

	<a class="button smallbutton" style="float: left; font-size: 12px;" href="/gruppi"><?php _e('Gruppi','custom')?></a> 
	<a class="button smallbutton" style="float: right; font-size: 12px;" href="<?php bp_members_directory_permalink()?>"><?php _e('Adesioni','custom')?></a>
	
	<a class="button smallbutton" style="float: left; font-size: 12px;" href="<?=$vR_link?>friends"><?php _e('Mio Network','custom')?></a>

	<a class="button smallbutton" style="float: right; font-size: 12px;" href="<?=$vR_link?>groups"><?php _e('Miei Gruppi','custom')?></a>

	
</div>

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
	__('La tua presenza ad una Fiera, un nuovo prodotto, un nuovo contratto, offerte speciali, promozioni…','buddypress');	
	}
}
?>
