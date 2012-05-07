<?php

class completaProfilo_Widget extends WP_Widget {
	/**
	 * Constructor
	 * 
	 * Registers the widget details with the parent class
	 */
	var $fr_type=null;
	
	function completaProfilo_Widget(){
		
		$id_base = 'completaProfilo_Widget';
		
		$name = __('Completa Profilo',"custom");
		
		$widget_options = array( 'titolo' => __('Completamento Profilo'),"custom");
		
		$control_ops = '';
		
		parent::WP_Widget($id_base, $name, $widget_options, $control_ops);
		
	}
	

	function form($instance){
		
		// outputs the options form on admin
		
		$instance = wp_parse_args( (array) $instance, array(  'titolo' => __('Completamento Profilo' ),"custom"));
		
		$titolo = __(strip_tags( $instance['titolo'] ),"custom");
		?>
			<label>Titolo <input id="<?php echo $this->get_field_id( 'titolo' ); ?>" name="<?php echo $this->get_field_name( 'titolo' ); ?>" type="text" value="<?php echo esc_attr( $titolo ); ?>"  style="width: 70%; float:right;"  /></label>
		<?php 
	}

	function update($new_instance, $old_instance){
		
		$instance = $old_instance;
		
		$instance['titolo'] = __(strip_tags( $new_instance['titolo'] ),"custom");
		
		return $instance;
	}
	
	function get_current_field($id){
		
		global $bp;
		
		global $wpdb;
	
		$query = "SELECT d.field_id, d.value FROM wp_bp_xprofile_data d WHERE d.user_id=$id";
		
		$ms_output= $wpdb->get_results( $wpdb->prepare($query));
		
/*INFO*///echo "get_current_field($id) ".count($ms_output)."<br />";
		
		return $ms_output;
	}
	
	//effettua la query per caricare tutti i tipi dei vari utenti e li inserisce dentro la variabile fr_type
	function globalType($id){
		
		global $bp;
		
		global $wpdb;
	
		$query = "SELECT d.value FROM wp_bp_xprofile_data d WHERE d.user_id='$id' && (d.value='Albergo/Ristorante' OR d.value='Fornitore' OR d.value='Utente')";
		
		$ms_output= $wpdb->get_results( $wpdb->prepare($query));
		
/*INFO*///echo "globalType($id) ".count($ms_output)."<br />";
		
		$this->fr_type=$ms_output;
	}
	
	//ritorna il tipo (Albergo/Ristorante o Fornitore) dell'utente avente id =$ID
	function get_type($id){
		
		if ($this->fr_type==null)
		
			 $this->globalType($id);

/*INFO*///echo "get_type($id) #".$this->fr_type[0]->value."<br />";			

		return __($this->fr_type[0]->value,"custom");
		
	}
	
	function get_total_field($id){
		
		global $bp;
		
		global $wpdb;
	
		$query = "SELECT f.id , f.group_id, f.name FROM wp_bp_xprofile_fields f WHERE parent_id=0";
		
		$ms_output= $wpdb->get_results( $wpdb->prepare($query));
		
		$user_info = get_userdata($id);
		
		$user_type=$this->get_type($id);
		
		//$user_type=BP_XProfile_ProfileData::get_value_byfieldname("Tipo profilo");
		
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
		$title = apply_filters('widget_title', empty($instance['titolo']) ? __('Completamento Profilo','custom') : $instance['titolo'], $instance, $this->id_base);
		
		//analizzo il profilo
		$current = $this->get_current_field($user_ID);
		$total = $this->get_total_field($user_ID);
		
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
				$profilo.= "<b style='text-decoration: line-through;'>".__($v->name,'custom')."</b><br />";
			else
				$profilo.= __($v->name,'custom')."<br />";
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
									".__("Profilo Completo","custom") .$perc."% 
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
			
						".__("Migliora la tua visibilità:","custom")."<br />
						<a href='".bp_loggedin_user_domain()."profile/edit/group' style='width:80%;'>
							".__("Completa il tuo profilo!","custom")."
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
									".__("Completamento Profilo","custom")." ".$perc."% 
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

?>
