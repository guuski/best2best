<?php

//--------------------------------------------------------------------------------------------------------------------------------------------------------
//	FORM 1/2 - orignal
//--------------------------------------------------------------------------------------------------------------------------------------------------------

include_once('bps-multiselect.php');

add_action ('bp_profile_search_form', 'bps_form');										//ACTION

/**
 *
 */
function bps_form ($form_id)
{
	global $field;
	global $bps_options;

	$action = bp_get_root_domain (). '/'. bp_get_members_root_slug (). '/';				//bp_get_members_root_slug ADESIONI?

	if ($form_id == '')
	{
			$form_id = 'bps_action';
		?>	
			<div class="item-list-tabs">
			
			<ul>
				<!-- 1 -->	
				<li>
					<?php echo $bps_options['header']; ?>
				</li>
			
				<!-- 2 -->	
				<?php if (in_array ('Enabled', (array)$bps_options['show'])) { ?>
					<li class="last" style ="float: left;">    <!-- display: none;">-->							<!-- NO HIDE sballa!--> 
						<input id="bps_show" type="submit" value="<?php echo $bps_options['message']; ?>" />
					</li>
				<?php } ?>
			</ul>
			
		
		<?php if (in_array ('Enabled', (array)$bps_options['show'])) { ?>
			
			<script type="text/javascript">
				
				jQuery(document).ready(function($) 
				{
					//$('#bps_action').hide();																		//NO HIDE
					$('#bps_show').click(function(){
						$('#bps_action').toggle();
					});
				});
			</script>
		<?php } ?>
		</div>
		<?php
	}

	echo "<form action='$action' method='post' id='$form_id' class='standard-form'>";

	if (bp_has_profile ('hide_empty_fields=0'))  while (bp_profile_groups ())
	{
		bp_the_profile_group ();
		
		while (bp_profile_fields ())
		{
			bp_the_profile_field ();
			$fname = 'field_'. $field->id;
			$posted = $_POST[$fname];
			$posted_to = $_POST[$fname. '_to'];

			if (!in_array ($field->id, (array)$bps_options['fields'])) 
				continue;

			echo '<div '. bp_get_field_css_class ('editfield'). '>';

			if (!method_exists ($field, 'get_children'))
				$field = new BP_XProfile_Field ($field->id);
				
			$options = $field->get_children ();

			switch (bp_get_the_profile_field_type ())
			{
				case 'textbox':
					echo "<label for='$fname'>$field->name</label>";
					echo "<input type='text' name='$fname' id='$fname' value='$posted' />";
				break;

				case 'textarea':
					echo "<label for='$fname'>$field->name</label>";
					echo "<textarea rows='5' cols='40' name='$fname' id='$fname'>$posted</textarea>";
				break;

				case 'selectbox':
					echo "<label for='$fname'>$field->name</label>";
					echo "<select name='$fname' id='$fname'>";
					echo "<option value=''></option>";

					foreach ($options as $option)
					{
						$selected = ($option->name == $posted)? "selected='selected'": "";
						echo "<option $selected value='$option->name'>$option->name</option>";
					}
					echo "</select>";
				break;

				case 'multiselectbox':
					echo "<label for='$fname'>$field->name</label>";
					echo "<select name='{$fname}[]' id='$fname' multiple='multiple'>";

					foreach ($options as $option)
					{
						$selected = (in_array ($option->name, (array)$posted))? "selected='selected'": "";
						echo "<option $selected value='$option->name'>$option->name</option>";
					}
					echo "</select>";
				break;

				case 'radio':
					echo "<div class='radio'>";
					echo "<span class='label'>$field->name</span>";
					echo "<div id='$fname'>";

					foreach ($options as $option)
					{
						$selected = ($option->name == $posted)? "checked='checked'": "";
						echo "<label><input $selected type='radio' name='$fname' value='$option->name'>$option->name</label>";
					}
					
					echo '</div>';
					echo "<a class='clear-value' href='javascript:clear(\"$fname\");'>". __('Clear', 'buddypress'). "</a>";
					echo '</div>';
				break;

				case 'checkbox':
					echo "<div class='checkbox'>";
					echo "<span class='label'>$field->name</span>";

					foreach ($options as $option)
					{
						$selected = (in_array ($option->name, (array)$posted))? "checked='checked'": "";
						echo "<label><input $selected type='checkbox' name='{$fname}[]' value='$option->name'>$option->name</label>";
					}
					echo '</div>';
				break;
				
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
											
				case '			':  // box aggruppata! 
								
					echo "<div class='				'>";
					echo "<span class='label'>$field->name</span>";

				
					echo '</div>';
				break;
				
												
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
				// box aggruppata! 
				default:
				
					
				break;
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
			}
//======================================================================
//inserire multiselect
			bps_getHTMLfrontend();
			echo '</div>';
		}
	}

	
	echo "<div class='submit'>";
		echo "<input type='submit' value='". __('Search', 'buddypress'). "' />";
	echo '</div>';
	
	echo "<input type='hidden' name='bp_profile_search' value='true' />";									//bp_profile_search --> TRUE
	echo "<input type='hidden' name='num' value='9999' />";					//?! boh!
	
	
	//////////////////////////////////////////////////////////////////////////////////////////
	
	
	//////////////////////////////////////////////////////////////////////////////////////////
	
	echo '</form>';
		
}








//--------------------------------------------------------------------------------------------------------------------------------------------------------
//	FORM 2/2 - categorie
//--------------------------------------------------------------------------------------------------------------------------------------------------------

add_action ('bp_profile_search_form_categorie', 'bps_form_categorie');										//ACTION

/**
 *
 */
function bps_form_categorie ($form_id)
{
	global $field;
	global $bps_options;

	$action = bp_get_root_domain (). '/'. bp_get_members_root_slug (). '/';				//bp_get_members_root_slug ADESIONI?

	if ($form_id == '')
	{
			$form_id = 'bps_action_categorie';
		?>	
			<div class="item-list-tabs">
			
			<ul>
				<!-- 1 -->	
				<li>
					<?php echo $bps_options['header']; ?>
				</li>
			
				<!-- 2 -->	
				<?php if (in_array ('Enabled', (array)$bps_options['show'])) { ?>
					<li class="last" style ="float: left;">    <!-- display: none;">-->
						<input id="bps_show_categorie" type="submit" value="<?php echo 'Categorie' ?>" />							<!-- Categorie-->
					</li>
				<?php } ?>
			</ul>
			
		
		<?php if (in_array ('Enabled', (array)$bps_options['show'])) { ?>
			
			<script type="text/javascript">
				
				jQuery(document).ready(function($) 
				{
					//$('#bps_action_categorie').hide();																//NO HIDE
					$('#bps_show_categorie').click(function(){
						$('#bps_action_categorie').toggle();
					});
				});
			</script>
		<?php } ?>
		</div>
		<?php
	}

	echo "<form action='$action' method='post' id='$form_id' class='standard-form'>";
/*
	if (bp_has_profile ('hide_empty_fields=0'))  while (bp_profile_groups ())
	{
		bp_the_profile_group ();
		while (bp_profile_fields ())
		{
			bp_the_profile_field ();
			$fname = 'field_'. $field->id;
			$posted = $_POST[$fname];
			$posted_to = $_POST[$fname. '_to'];


			if (!in_array ($field->id, (array)$bps_options['fields']))  continue;

echo '<div '. bp_get_field_css_class ('editfield'). '>';

			if (!method_exists ($field, 'get_children'))
				$field = new BP_XProfile_Field ($field->id);
			$options = $field->get_children ();

			switch (bp_get_the_profile_field_type ())
			{
			case 'textbox':
echo "<label for='$fname'>$field->name</label>";
echo "<input type='text' name='$fname' id='$fname' value='$posted' />";
			break;

			case 'textarea':
echo "<label for='$fname'>$field->name</label>";
echo "<textarea rows='5' cols='40' name='$fname' id='$fname'>$posted</textarea>";
			break;

			case 'selectbox':
echo "<label for='$fname'>$field->name</label>";
echo "<select name='$fname' id='$fname'>";
echo "<option value=''></option>";

			foreach ($options as $option)
			{
				$selected = ($option->name == $posted)? "selected='selected'": "";
echo "<option $selected value='$option->name'>$option->name</option>";
			}
echo "</select>";
			break;

			case 'multiselectbox':
echo "<label for='$fname'>$field->name</label>";
echo "<select name='{$fname}[]' id='$fname' multiple='multiple'>";

			foreach ($options as $option)
			{
				$selected = (in_array ($option->name, (array)$posted))? "selected='selected'": "";
echo "<option $selected value='$option->name'>$option->name</option>";
			}
echo "</select>";
			break;

			case 'radio':
echo "<div class='radio'>";
echo "<span class='label'>$field->name</span>";
echo "<div id='$fname'>";

			foreach ($options as $option)
			{
				$selected = ($option->name == $posted)? "checked='checked'": "";
echo "<label><input $selected type='radio' name='$fname' value='$option->name'>$option->name</label>";
			}
echo '</div>';
echo "<a class='clear-value' href='javascript:clear(\"$fname\");'>". __('Clear', 'buddypress'). "</a>";
echo '</div>';
			break;

			case 'checkbox':
echo "<div class='checkbox'>";
echo "<span class='label'>$field->name</span>";

			foreach ($options as $option)
			{
				$selected = (in_array ($option->name, (array)$posted))? "checked='checked'": "";
echo "<label><input $selected type='checkbox' name='{$fname}[]' value='$option->name'>$option->name</label>";
			}
echo '</div>';
			break;
			}

echo '</div>';
		}
	}
	
	
	
	*/

echo "<div class='submit'>";
	echo "<input type='submit' name= 'categorie_submit' id ='categorie_submit'  value='". __('Categorie', 'buddypress'). "' />";
echo '</div>';
//echo "<input type='hidden' name='bp_profile_search_categorie' value='true' />";								//bp_profile_search_categorie ----> TRUE
//echo "<input type='hidden' name='num' value='9999' />";


echo "<div class='submit'>";
	echo "<input type='submit' name= 'reset_categorie_submit' id ='reset_categorie_submit' value='". __('Reset', 'buddypress'). "' />";
echo '</div>';

//echo "<input type='hidden' name='bp_profile_search_categorie_reset' value='true' />";						// (RESET) bp_profile_search_categorie_reset ---> TRUE




echo '</form>';
}
?>
