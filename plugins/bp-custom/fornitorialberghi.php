<?php

class FornitoriAlberghi_Widget extends WP_Widget {
	/**
	 * Constructor
	 * 
	 * Registers the widget details with the parent class
	 */
	var $fr_type=null;
	
	function FornitoriAlberghi_Widget(){
		
		parent::WP_Widget( $id = 'FornitoriAlberghi_Widget', $name = __('Fornitori Alberghi','custom'));
		
	}
	

	function form($instance){

		_e("Questo widget se l'utente è un fornitore, visualizza la lista degli alberghi suoi amici, se invece è un albergo, visualizza la lista dei fornitori","custom");
	}

	function update($new_instance, $old_instance){
		
		$instance = $old_instance;
		
		return $instance;
	}

	function widget($args, $instance){
		
		global $bp;
		
		global $user_ID;
		
		$title=__("Diventa un utente registrato!","custom");
		
		$fullname=$bp->loggedin_user->fullname;
		
		$user_disp = $bp->displayed_user->id;
		
		$user_attivo = $user_ID;
		
		if ($user_disp!=0) {
			
			$user_attivo=$user_disp;
			
			$fullname=bp_get_displayed_user_fullname();
		}
		
		$attivo=array();
		
		extract($args);
		
		if ($user_attivo==0) {
			
			echo $before_title . $title . $after_title.'<div class="avatar-block">';
			//visualizzo 8 utenti (amici dell'amministatore)
			
			$listfriend = friends_get_friend_user_ids(1);
			
			$cont=0;
			
			foreach ($listfriend as $k => $v){
				$attivo = get_userdata($v);
				
				if ($cont<8){
					echo  "<a href='".get_bloginfo('url').DS."registrati/' >".get_avatar($v,42)."</a>";
					$cont++;
				}
			}
			echo "</div>";
			//non visualizzo nulla
			}
		else
		{
			$user_info = get_userdata($user_attivo);
		
			$user_type=$this->get_type($user_info->ID);
		
			
			$title = apply_filters('widget_title', empty($instance['titolo']) ? __('Il Network di ','custom')." ".$fullname: $instance['titolo'], $instance, $this->id_base);
			
/*			if ($user_type=="Fornitore" || $user_type=="Albergo/Ristorante" || $user_type=="Utente")
			{
			
				if ($user_type=="Fornitore")
				{
					$title = apply_filters('widget_title', empty($instance['titolo']) ? __('I clienti di','custom')." ".$fullname: $instance['titolo'], $instance, $this->id_base);
				}
				else if ($user_type=="Albergo/Ristorante")
					{
						$title = apply_filters('widget_title', empty($instance['titolo']) ? __('I fornitori di','custom')." ".$fullname: $instance['titolo'], $instance, $this->id_base);
					}
					else if ($user_type=="Utente") 
						{
							$title = apply_filters('widget_title', empty($instance['titolo']) ? __('Gli amici di','custom')." ".$fullname: $instance['titolo'], $instance, $this->id_base);
							
						}
			} */
		
		//echo $before_widget;
			if ( $title )
				echo $before_title . $title . $after_title.'<div class="avatar-block">';

				$listfriend = friends_get_friend_user_ids($user_attivo);
				$bfriendship = unserialize(get_user_meta($user_attivo, "friendship_level",true));
				
				error_log(print_r($bfriendship,true));
				
				$fornitore=array();
				$amico=array();
				$cliente=array();
				$collega=array();
				$normale=array();
				
				foreach ($listfriend as $k => $v){
					if(isset($bfriendship[$v])){
						// TODO FIX aggiungere un check con le variabili, se cambia una vocale non fuziona più
						array_push($$bfriendship[$v], $v);
					}
					else {
						array_push($normale, $v);
					}

				}
				
				
				foreach ($normale as $v){
						
						
					$attivo = get_userdata($v);
						
					if ($user_type=="Fornitore" && $this->get_type($v)=="Albergo/Ristorante"){
						array_push($cliente, $v);
					}
					else if ($user_type=="Albergo/Ristorante" && $this->get_type($v)=="Fornitore"){
						array_push($fornitore, $v);
					}
					else if ($user_type==$this->get_type($v)){
						array_push($collega, $v);
					}
					else {
						array_push($amico, $v);
					}
				}
				error_log("fornitore ".print_r($fornitore,true));
				error_log("amico ".print_r($amico,true));
				error_log("cliente ".print_r($cliente,true));
				error_log("collega ".print_r($collega,true));
				error_log("altri ".print_r($normale,true));
				if(count($fornitore) > 0 ) {
					echo "<div class='friendtitle' style='font-weight:bold; clear:both;'>".__("Fornitori","custom")."</div>";
					foreach($fornitore as $v) {
						echo  "<a href='".bp_core_get_user_domain($v)."' >".get_avatar($v,42)."</a>";
					}						
				}
				
				if(count($cliente) > 0 ) {
					echo "<div class='friendtitle' style='font-weight:bold; clear:both;'>".__("Clienti","custom")."</div>";
					foreach($cliente as $v) {
						echo  "<a href='".bp_core_get_user_domain($v)."' >".get_avatar($v,42)."</a>";
					}
				}
				
				if(count($collega) > 0 ) {
					echo "<div class='friendtitle' style='font-weight:bold; clear:both;'>".__("Colleghi","custom")."</div>";
					foreach($collega as $v) {
						echo  "<a href='".bp_core_get_user_domain($v)."' >".get_avatar($v,42)."</a>";
					}
				}
				
				if(count($amico) > 0 ) {
					echo "<div class='friendtitle' style='font-weight:bold; clear:both;'>".__("Amici","custom")."</div>";
					foreach($amico as $v) {
						echo  "<a href='".bp_core_get_user_domain($v)."' >".get_avatar($v,42)."</a>";
					}
				}
				
				
				
				echo "</div>";

			
			?>
		
	
			


			<?php 
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
		return __('Non riconosciuto','custom');
	}
}
//INSERT INTO wp_bp_xprofile_fields ( group_id, parent_id, type, name , description , is_required , is_default_option, field_order, option_order , order_by, can_delete) VALUES (1,2,'option','Utente','',0,1,0,1,'',1)


?>