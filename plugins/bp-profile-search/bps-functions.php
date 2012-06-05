<?php

//INCLUDE
include 'bps-searchform.php';

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
//	FILTER F -  3 - giovanni
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------


					add_filter ('bp_core_get_users', 'cM_visualizzaALLfield', 99, 2);													//FILTER


/**
 *
 */
function cM_visualizzaALLfield($results, $params)	
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	global $bp;
	global $wpdb;
	global $bps_list;
	global $bps_options;

																							//POST!  --bp_profile_search_categorie 			(3)
	if (
			$_POST['bp_profile_search_categorie'] != true
		&&  $_POST['bp_profile_search_categorie_reset'] == true  
	)  
	{			
	
		return $results;
	}
	
																						//POST!  --bp_profile_search_categorie_reset   - RESET
	if ($_POST['bp_profile_search_categorie_reset'] == true)  				{
		
		remove_filter ('bp_core_get_users', 'bps_search', 99, 2);									//REMOVE Filter
		
		
		return $results;
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	

	cM_getScript();															// F - cM_getScript
	$cM_array = cM_getALLfield();													// F - cM_getALLfield()
	
	foreach ($cM_array as $k => $v)
	{	
		//CONTO LE OCCORRENZE
		$cM_cont=0;
		$cM_array2 = cM_getIDfield();																	// F - cM_getIDfield()
		
		foreach ($cM_array2 as $key_c_utente => $c_utente)
		{
			$field_selected=explode(", ",$c_utente->value);
			if (in_array($v->name, $field_selected))
			{
				$cM_cont++;
			}
		}
			
	?>

	<span class='cM_box'
		onmouseover='cM_labelon(this)' 
		onmouseout='cM_labeloff(this)'
		onclick='cM_open("cM_labelhidden<?php echo $v->id;?>","cM_labelprev<?php echo $v->id;?>")'>
							
		<label id='cM_labelprev<?php echo $v->id;?>' class='cM_labelprev'>

		<?php
			//======================================
										
			echo ($v->name ."<span>($cM_cont)</span><br />"); 
			
			//======================================
											
		?>
			</label>
							
			<label id='cM_labelhidden<?php echo $v->id;?>' class='cM_labelhidden'  style='display:none;'>
				<?php echo $v->name ?><br/>
		<?php
				
		echo "<div style='position:relative; height:60px; width:90%;'>";
		
		$cM_array2 = cM_getIDfield();																	// F - cM_getIDfield
		
		foreach ($cM_array2 as $key_c_utente => $c_utente)
		{
			$field_selected=explode(", ",$c_utente->value);
			
			if (in_array($v->name, $field_selected))
			{
				$attivo = get_userdata($c_utente->user_id);
				
				echo  "				
					<a style='float:left;' href='".bp_core_get_user_domain($attivo->user_login).$attivo->user_login."' >
						".get_avatar($c_utente->user_id,42)."
					</a>
					";			
			}
		}
		echo "</div>";
		
		?>
		</label>
	</span>
						
	<?php
			
	}
	
	remove_filter ('bp_core_get_users', 'cM_visualizzaALLfield', 99, 2);									//REMOVE Filter
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
//	FILTER F -  2 - categorie
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------

		
		//add_filter ('bp_core_get_users', 'bps_search_categorie', 99, 2);													//FILTER

/**
 *
 */
function bps_search_categorie ($results, $params)	
{


	global $bp;
	global $wpdb;
	global $bps_list;
	global $bps_options;

	if ($_POST['bp_profile_search_categorie'] != true)  				//POST!  --bp_profile_search_categorie 			(2)
		return $results;

		
		
		
		
	$bps_list += 1;
	
	if ($bps_list != $bps_options['filtered'])  
		return $results;

	$noresults['users'] = array ();
	$noresults['total'] = 0;

	$emptyform = true;

	if (bp_has_profile ('hide_empty_fields=0')):
	
		while (bp_profile_groups ()):
		
			bp_the_profile_group ();
			
			while (bp_profile_fields ()): 
				bp_the_profile_field ();

				$id = bp_get_the_profile_field_id ();
				$value = $_POST["field_$id"];
				$to = $_POST["field_{$id}_to"];

				if ($value == '' && $to == '')  continue;

				switch (bp_get_the_profile_field_type ())
				{
					case 'textbox':
					
					
					case 'textarea':
						$sql = "SELECT user_id from {$bp->profile->table_name_data}";
						if ($bps_options['searchmode'] == 'Partial Match')
							$sql .= " WHERE field_id = $id AND value LIKE '%%$value%%'";
						else					
							$sql .= " WHERE field_id = $id AND value LIKE '$value'";
						break;

					case 'selectbox':
					
					
					
					case 'radio':
						$sql = "SELECT user_id from {$bp->profile->table_name_data}";
						$sql .= " WHERE field_id = $id AND value = '$value'";
						break;

					case 'multiselectbox':
					
					
					
					case 'checkbox':
						$sql = "SELECT user_id from {$bp->profile->table_name_data}";
						$sql .= " WHERE field_id = $id";
						$like = array ();
						foreach ($value as $curvalue)
							$like[] = "value LIKE '%\"$curvalue\"%'";
						$sql .= ' AND ('. implode (' OR ', $like). ')';	
						break;

				}

				$found = $wpdb->get_results ($sql);
				
				if (!is_array ($userids)) 
					$userids = bps_conv ($found, 'user_id');													//bps_conv(	)
				else
					$userids = array_intersect ($userids, bps_conv ($found, 'user_id'));						//bps_conv(	)

				if (count ($userids) == 0)  
					return $noresults;
					
				$emptyform = false;
				
			endwhile;
		endwhile;
	endif;

	if ($emptyform == true)
		return $noresults;

	remove_filter ('bp_core_get_users', 'bps_search', 99, 2);									//REMOVE Filter

	$params['per_page'] = count ($userids);
	$params['include']  = $wpdb->escape (implode (',', $userids));
	
	$results = bp_core_get_users ($params);

	return $results;
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------
//	FILTER F - orignal
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------

add_filter ('bp_core_get_users', 'bps_search', 99, 2);													//FILTER

/**
 *
 */
function bps_search ($results, $params)	
{
	global $bp;
	global $wpdb;
	global $bps_list;
	global $bps_options;

	
		if ($_POST['bp_profile_search'] != true)  				//POST!  --bp_profile_search
			return $results;

			
			
	$bps_list += 1;
	
	if ($bps_list != $bps_options['filtered'])  
		return $results;

	$noresults['users'] = array ();
	$noresults['total'] = 0;

	$emptyform = true;

	if (bp_has_profile ('hide_empty_fields=0')):
	
		while (bp_profile_groups ()):
		
			bp_the_profile_group ();
			
			while (bp_profile_fields ()): 
				bp_the_profile_field ();

				$id = bp_get_the_profile_field_id ();
				$value = $_POST["field_$id"];
				$to = $_POST["field_{$id}_to"];

				if ($value == '' && $to == '')  continue;

				switch (bp_get_the_profile_field_type ())
				{
					case 'textbox':
					
					
					case 'textarea':
						$sql = "SELECT user_id from {$bp->profile->table_name_data}";
						if ($bps_options['searchmode'] == 'Partial Match')
							$sql .= " WHERE field_id = $id AND value LIKE '%%$value%%'";
						else					
							$sql .= " WHERE field_id = $id AND value LIKE '$value'";
						break;

					case 'selectbox':
					
					
					
					case 'radio':
						$sql = "SELECT user_id from {$bp->profile->table_name_data}";
						$sql .= " WHERE field_id = $id AND value = '$value'";
						break;

					case 'multiselectbox':
					
					
					
					case 'checkbox':
						$sql = "SELECT user_id from {$bp->profile->table_name_data}";
						$sql .= " WHERE field_id = $id";
						$like = array ();
						foreach ($value as $curvalue)
							$like[] = "value LIKE '%\"$curvalue\"%'";
						$sql .= ' AND ('. implode (' OR ', $like). ')';	
						break;

				}

				$found = $wpdb->get_results ($sql);
				
				if (!is_array ($userids)) 
					$userids = bps_conv ($found, 'user_id');													//bps_conv(	)
				else
					$userids = array_intersect ($userids, bps_conv ($found, 'user_id'));						//bps_conv(	)

				if (count ($userids) == 0)  
					return $noresults;
					
				$emptyform = false;
				
			endwhile;
		endwhile;
	endif;

	if ($emptyform == true)
		return $noresults;

	remove_filter ('bp_core_get_users', 'bps_search', 99, 2);									//REMOVE Filter

	$params['per_page'] = count ($userids);
	$params['include']  = $wpdb->escape (implode (',', $userids));
	
	$results = bp_core_get_users ($params);

	return $results;
}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------	

//----------------------------------------------------------------------------------------------------------------------------------------------------------------	
/**
 *
 */
function bps_conv ($objects, $field)
{
	$array = array ();

	foreach ($objects as $object)
		$array[] = $object->$field;

	return $array;	
}


//----------------------------------------------------------------------------------------------------------------------------------------------------------------	

//----------------------------------------------------------------------------------------------------------------------------------------------------------------	
/**
 *
 */
function bps_fields ($name, $values)
{
	global $field;
	global $dateboxes;

	if (bp_is_active ('xprofile')) : 
	
		if (function_exists ('bp_has_profile')) : 
			
			if (bp_has_profile ('hide_empty_fields=0')) :
			
				$dateboxes = array ();
				$dateboxes[0] = '';

				while (bp_profile_groups ()) : 
				
					bp_the_profile_group (); 

					echo '<strong>'. bp_get_the_profile_group_name (). ':</strong><br />';

					while (bp_profile_fields ()) : 
					
						bp_the_profile_field(); 
						
						switch (bp_get_the_profile_field_type ())
						{
							case 'datebox':	
								
								$disabled = 'disabled="disabled"';
								$dateboxes[bp_get_the_profile_field_id ()] = bp_get_the_profile_field_name ();
							
								break;
								
							default:
								$disabled = '';
								
								break;
						}
						?>
						<label><input type="checkbox" name="<?php echo $name; ?>[]" value="<?php echo $field->id; ?>" <?php echo $disabled; ?>
						<?php if (in_array ($field->id, (array)$values))  echo ' checked="checked"'; ?> />
						<?php bp_the_profile_field_name(); ?>
						<?php if (bp_get_the_profile_field_is_required ()) 
							_e (' (required) ', 'buddypress');
						else
							_e (' (optional) ', 'buddypress'); ?>
						<?php bp_the_profile_field_description (); ?></label><br />

						<?php 		
					endwhile;
				endwhile; 
			endif;
		endif; 
	endif;

	//
	return true;
}


//----------------------------------------------------------------------------------------------------------------------------------------------------------------	
//	------------- FUNZIONI categorie (GIOVANNI)			--- la prima è in testa al file
//----------------------------------------------------------------------------------------------------------------------------------------------------------------

	
	
/**
 *
 */
function cM_getIDfield()
{
	global $bp;
	global $wpdb;
	
	/*seleziono dentro la tabella wp_bp_xprofile_data solo le righe aventi
	user_id uguale a quello dell'utente e value=ms Categorie Acquisti*/
	$query = "SELECT d.user_id, d.value FROM wp_bp_xprofile_data d , wp_bp_xprofile_fields f WHERE f.type='box selezione multipla raggruppata' AND d.field_id=f.id ";
	$cM_output= $wpdb->get_results( $wpdb->prepare($query) );
	
	/*
	if (isset($ms_output[0])) {
		$field_selected=explode(", ",$ms_output[0]->value);
		return $field_selected;
	}
	*/
	
	return $cM_output;
}

/**
 *
 */
function cM_getALLfield()
{
	global $bp;
	global $wpdb;
	
	$query = "SELECT f.name, f.id FROM wp_bp_xprofile_fields f WHERE f.type='multiselectboxrag' ORDER BY name ASC";		
	$ms_output= $wpdb->get_results( $wpdb->prepare($query));
 
	return  $ms_output;
}

/**
 *
 */	
function cM_visualizzaFIELDutente()																								//? cu a chiama?!
{
	$cM_array = cM_getIDfield();								//F - cM_getIDfield
	
	foreach ($cM_array as $key_c_utente => $c_utente)
	{
	
		$attivo = get_userdata($c_utente->user_id);
		echo  "<div style='width:50px; height:50px;'> 
					<a href='".bp_core_get_user_domain($attivo->user_login).$attivo->user_login."' >
						".get_avatar($c_utente->user_id,42)."
					</a>
				</div>";
		
		$field_selected=explode(", ",$c_utente->value);
	
		foreach ($field_selected as $k => $v)
			echo $v . "<br />";
			
		echo "<hr />";
	}
}
	
	
	
	
	
/**
 *
 */
function cM_getScript()
{ 		
	?>	
	<script language='JavaScript' type='text/javascript'>
	<!--
		function cM_open(labelhidden, labelprev)
		{
			jQuery('label#'+labelhidden).fadeToggle("fast");
			jQuery('label#'+labelprev).toggle();
			
		}
		
		function cM_labelon(t)
		{	
			t.style.color = '#87badd';
			t.style.background ='transparent' ;
			t.style.cursor = 'pointer';
		}

		function cM_labeloff(t)
		{
			t.style.color = '#000';
			t.style.background ='transparent' ;
			t.style.cursor = 'default';
		}	
	//-->
	</script>

	<style type='text/css'>
		
		.cM_box
		{		
			background-color:transparent;			
			font-weight:bold;
			color:#000;						
		}
	
		.cM_labelhidden
		{
			color:#787878;
		}
		
		.cM_labelprev
		{
			color:#787878;
		}
		
		.cM_label
		{
			
		}
	</style>
	<?php	
}







//------------------------------------------------------------------------------------------------------------------//------------------------------------------------------------------------------------------------------------------
// inutilità! :)
//------------------------------------------------------------------------------------------------------------------//------------------------------------------------------------------------------------------------------------------


//------------------------------------------------------------------------------------------------------------------
// SHORTCODE
//------------------------------------------------------------------------------------------------------------------
add_shortcode ('bp_profile_search_form', 'bps_shortcode');
function bps_shortcode ($attr, $content)
{
	ob_start ();
	bps_form ('bps_shortcode');
	return ob_get_clean ();
}



//------------------------------------------------------------------------------------------------------------------
// WIDGET
//------------------------------------------------------------------------------------------------------------------

//CLASS
class bps_widget extends WP_Widget
{
	function bps_widget ()
	{
		$widget_ops = array ('description' => 'Your BP Profile Search form');
		$this->WP_Widget ('bp_profile_search', 'BP Profile Search', $widget_ops);
	}

	function widget ($args, $instance)
	{
		extract ($args);
		$title = apply_filters ('widget_title', esc_attr ($instance['title']));
	
		echo $before_widget;
		if ($title)
			echo $before_title. $title. $after_title;
		bps_form ('bps_widget');
		echo $after_widget;
	}

	function update ($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags ($new_instance['title']);
		return $instance;
	}

	function form ($instance)
	{
		$title = strip_tags ($instance['title']);
	?>
		<p>
		<label for="<?php echo $this->get_field_id ('title'); ?>"><?php _e ('Title:', 'wpm'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id ('title'); ?>" name="<?php echo $this->get_field_name ('title'); ?>" type="text" value="<?php echo esc_attr ($title); ?>" />
		</p>
	<?php
	}
}


// Register WIDGET
add_action ('widgets_init', 'bps_widget_init');
function bps_widget_init ()
{
	register_widget ('bps_widget');
}
	
?>