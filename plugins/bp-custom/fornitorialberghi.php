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
		
			if ($user_type=="Fornitore" || $user_type=="Albergo/Ristorante" || $user_type=="Utente")
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
			}
		
		//echo $before_widget;
			if ( $title )
				echo $before_title . $title . $after_title.'<div class="avatar-block">';

				$listfriend = friends_get_friend_user_ids($user_attivo);
			
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
