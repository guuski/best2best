<?php
/*
 * Admin form functions.
 */

 
/**
 * Admin form hook.
 *
 * @global  $bpml
 * @return <type>
 */
function bpml_profiles_admin_form() 
{
    global $bpml;
	
    echo '<h2>Profile fields</h2>';
    echo '<label><input type="radio" name="bpml[profiles][translation]" value="no"' . (($bpml['profiles']['translation'] == 'no' || !isset($bpml['profiles']['translation'])) ? ' checked="checked"' : '') . ' />&nbsp;No translation</label>&nbsp;&nbsp;<br />';
    echo '<label><input type="radio" name="bpml[profiles][translation]" value="user"' . ($bpml['profiles']['translation'] == 'user' ? ' checked="checked"' : '') . ' />&nbsp;Allow user to translate</label>&nbsp;&nbsp;<br />';

    echo '<br /><br />';

    echo 'Select fields that can be translated:';
    
	// get fields
    $groups = BP_XProfile_Group::get(array('fetch_fields' => true));
			
    if (empty($groups)) 
	{
        echo 'No profile fields.';
        return FALSE;
    }

    foreach ($groups as $group) 
	{
        if (empty($group->fields)) {
            echo 'No fields in this group';
            continue;
        }
		
        echo '<h4>' . $group->name . '</h4>';
        
		foreach ($group->fields as $field) 
		{
            $checked = isset($bpml['profiles']['fields'][$field->id]) ? ' checked="checked"' : '';
            echo '<label><input type="checkbox" name="bpml[profiles][fields][' . $field->id . ']" value="1"' . $checked . ' />&nbsp;' . $field->name . '</label>&nbsp;&nbsp;';
        }
    }

	//----------------------------TRANSLATE FIELD TITLES (o NAMES)-------------------------------
    echo '<br /><br /><br />Translate field titles<br />';
    $checked = isset($bpml['profiles']['translate_fields_title']) ? ' checked="checked"' : '';    
	echo '<label><input type="checkbox" name="bpml[profiles][translate_fields_title]" value="1"' . $checked . ' />&nbsp;Yes</label>&nbsp;&nbsp;';
	//-------------------------------------------------------------------------------------
	
    echo '<br /><br /><input type="submit" value="Save Settings" name="bpml_save_options" class="submit button-primary" />';
    echo '<br /><br />';
}


/**
 * Processes admin page submit.
 *
 * @global <type> $wpdb
 */
function bpml_admin_save_settings_submit() 
{
    if (current_user_can('manage_options') && wp_verify_nonce(isset($_POST['_wpnonce']), 'bpml_save_options') && isset($_POST['bpml'])) 
	{
        if (isset($_POST['bpml_clear_google_cache'])) 
		{
            bpml_activities_clear_cache('all', 'bpml_google_translation');
            bpml_store_admin_notice('settings_saved', '<p>Cache cleared</p>');
        }
		else if (isset($_POST['bpml_clear_all_activity_data'])) 
		{
            bpml_activities_clear_all_data();
            bpml_store_admin_notice('settings_saved', '<p>Activities data cleared</p>');
        }
		else if (isset($_POST['bpml_admin_clear_activity_translations_single'])) 
		{
            bpml_admin_clear_activity_translations_single(key($_POST['bpml_admin_clear_activity_translations_single']));
            bpml_store_admin_notice('settings_saved', '<p>Activity data cleared</p>');
        } 
		else if (isset($_POST['bpml_admin_clear_activity_data_single'])) 
		{
            bpml_admin_clear_activity_data_single(key($_POST['bpml_admin_clear_activity_data_single']));
            bpml_store_admin_notice('settings_saved', '<p>Activity data cleared</p>');
        }
		else if (isset($_POST['bpml_reset_options'])) 
		{
            bpml_save_settings(bpml_default_settings());
            bpml_store_admin_notice('settings_saved', '<p>Settings set to default</p>');
        } 
		else 
		{
            bpml_admin_save_settings_submit_recursive(&$_POST['bpml']);
            bpml_save_settings($_POST['bpml']);
			
			//DO_ACTION
            do_action('bpml_settings_saved', $_POST['bpml']);
			
            bpml_store_admin_notice('settings_saved', '<p>Settings saved</p>');
        }
		
		//REDIRECT
        wp_redirect(admin_url('options-general.php?page=bpml'));
        
		exit;
    }
}

/**
 * Sets POST values.
 *
 * @param <type> $array
 */
function bpml_admin_save_settings_submit_recursive(&$array) 
{
    foreach ($array as $key => &$value) 
	{
        if (is_array($value)) 
		{
            bpml_admin_save_settings_submit_recursive(&$value);
        }
		else if ($value == '0' || $value == '1' || $value == '-1') 
		{
            $value = intval($value);
        }
    }
}

/**
 * Renders admin page.
 *
 * @global <type> $bpml
 */
function bpml_admin_page() 
{
    
	bpml_delete_setting('admin_notices');
    
	global $bpml;
	
    echo '<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
<h2>BuddyPress Multilingual</h2><div class="bpml-admin-form">';
    echo '<form action="" method="post">';
    
	wp_nonce_field('bpml_save_options');

    echo '<h2>General</h2>';
    echo 'Enable debugging <em>(visible on frontend for admin only)</em><br />';
    echo '<label><input type="radio" name="bpml[debug]" value="1"';
    
	if ($bpml['debug'])
        echo ' checked="checked"';
    
	echo '/> Yes</label>&nbsp;&nbsp;';
    echo '<label><input type="radio" name="bpml[debug]" value="0"';
    
	if (!$bpml['debug'])
        echo ' checked="checked"';
		
    echo '/> No</label>';
    echo '<br /><br />';

	//DO_ACTION
    do_action('bpml_settings_form_before');

    echo '<h2>Activities</h2>';
    echo 'Filter activity entries per language<br /><em>Default: No (activities are all displayed and optionally translated)</em><br />';
    echo '<label><input type="radio" name="bpml[activities][filter]" value="1"';
    
	if ($bpml['activities']['filter'])
        echo ' checked="checked"';
		
    echo '/> Yes</label>&nbsp;&nbsp;';
    echo '<label><input type="radio" name="bpml[activities][filter]" value="0"';
	
    if (!$bpml['activities']['filter'])
        echo ' checked="checked"';
		
    echo '/> No</label>';
    echo '<br /><br />';
    echo '<input type="submit" value="Clear all BPML language activity data" name="bpml_clear_all_activity_data" class="submit button-secondary" onclick="if (confirm(\'Are you sure?\\nAll activities will loose language data!\') == false) { return false; }" />';
    echo '<br /><br />';
    echo 'Show activity language assign dropdown for admin<br />';
    echo '<label><input type="radio" name="bpml[activities][show_activity_switcher]" value="1"';
    
	if ($bpml['activities']['show_activity_switcher'])
        echo ' checked="checked"';
    echo '/> Yes</label>&nbsp;&nbsp;';
    echo '<label><input type="radio" name="bpml[activities][show_activity_switcher]" value="0"';
    
	if (!$bpml['activities']['show_activity_switcher'])
        echo ' checked="checked"';
    echo '/> No</label>';

    echo '<br /><br />';

    echo 'Display activity updates without language in:<br />';
    echo '<label><input type="radio" name="bpml[activities][display_orphans]" value="default"';
    
	
	if ($bpml['activities']['display_orphans'] === 'default')
        echo ' checked="checked"';
    echo '/>  Default language</label>&nbsp;&nbsp;';
    echo '<label><input type="radio" name="bpml[activities][display_orphans]" value="all"';
    
	if ($bpml['activities']['display_orphans'] === 'all')
        echo ' checked="checked"';
    echo '/> All languages</label>&nbsp;&nbsp;';
    echo '<label><input type="radio" name="bpml[activities][display_orphans]" value="none"';
    if ($bpml['activities']['display_orphans'] === 'none')
        echo ' checked="checked"';
    echo '/> Don\'t display</label>';

    echo '<br /><br />';

    echo 'Auto-assing default language to activities without language data<br />';
    echo '<label><input type="radio" name="bpml[activities][orphans_fix]" value="1"';
    if ($bpml['activities']['orphans_fix'])
        echo ' checked="checked"';
    echo '/> Yes</label>&nbsp;&nbsp;';
    echo '<label><input type="radio" name="bpml[activities][orphans_fix]" value="0"';
    if (!$bpml['activities']['orphans_fix'])
        echo ' checked="checked"';
    echo '/> No</label>';

    echo '<br /><br />';

    echo 'Enable Google Translation for titles and content<br /><em>will not be used if filtering is enabled</em><br />';
    echo '<label><input type="radio" class="bpml-show-collected-activities" name="bpml[activities][enable_google_translation]" value="store"';
    if ($bpml['activities']['enable_google_translation'] === 'store')
        echo ' checked="checked"';
    echo '/> Store in DB</label>&nbsp;&nbsp;';
    echo '<label><input type="radio" class="bpml-show-collected-activities" name="bpml[activities][enable_google_translation]" value="js"';
    if ($bpml['activities']['enable_google_translation'] === 'js')
        echo ' checked="checked"';
    echo '/> Use as JS (enable user control)</label>&nbsp;&nbsp;';
    echo '<label><input type="radio" class="bpml-hide-collected-activities" name="bpml[activities][enable_google_translation]" value="0"';
    if (!$bpml['activities']['enable_google_translation'])
        echo ' checked="checked"';
    echo '/> No</label>';
    echo '&nbsp;&nbsp;<input type="submit" value="Clear cached translations" name="bpml_clear_google_cache" class="submit button-secondary" />';

    echo '<br /><br />';

    echo '<div id="bpml-collected-activities">';
    echo '<strong>Collected activities:</strong><br />';
    echo '<em>Soon as some activity is registered on site it will be displayed here.</em><br /><br />';
    echo '<a href="javascript:void(0);" id="bpml-activities-select-all">Set all for translation</a>&nbsp;|&nbsp;';
    echo '<a href="javascript:void(0);" id="bpml-activities-clear-all">Clear all</a><br /><br />';
    ksort($bpml['collected_activities']);
    foreach ($bpml['collected_activities'] as $type => $activity) {

        echo '<table class="bpml-collected-activities-wrapper">';

        echo '<tr><td colspan="3"><div class="bpml-collected-activities-title">' . $type . '</div></td></tr>';

        echo '<tr>';

        echo '<td><label class="bpml-activities-link-option">Translate links&nbsp;<select name="bpml[collected_activities][' . $type . '][translate_links]">';
        echo '<option value="0"';
        if (0 == intval($activity['translate_links'])) {
            echo ' selected="selected" ';
        }
        echo '>No</option>';
        echo '<option value="-1"';
        if (-1 == intval($activity['translate_links'])) {
            echo ' selected="selected" ';
        }
        echo '>All</option>';
        for ($index = 1; $index < 6; $index++) {
            echo '<option';
            if ($index == $activity['translate_links']) {
                echo ' selected="selected" ';
            }
            echo ' value="' . $index . '">Limit to ' . $index . '&nbsp;</option>';
        }
        echo '</select></label></td>';

        echo '<td><div class="bpml-activities-hide-titlecontent-option"><label><input type="checkbox" name="bpml[collected_activities][' . $type . '][translate_title]" value="1"';
        echo (isset($activity['translate_title']) && $activity['translate_title']) ? ' checked="checked"' : '';
        echo ' />';
        echo '&nbsp;Translate title&nbsp;&nbsp;</label></div>';

        echo '<div class="bpml-activities-hide-cache-option"><label><input type="checkbox" name="bpml[collected_activities][' . $type . '][translate_title_cache]" value="1"';
        echo (isset($activity['translate_title_cache']) && $activity['translate_title_cache']) ? ' checked="checked"' : '';
        echo ' />';
        echo '&nbsp;Cache title translation&nbsp;&nbsp;</label></div></td>';

        echo '<td><div class="bpml-activities-hide-titlecontent-option"><label><input type="checkbox" name="bpml[collected_activities][' . $type . '][translate_content]" value="1"';
        echo (isset($activity['translate_content']) && $activity['translate_content']) ? ' checked="checked"' : '';
        echo ' />';
        echo '&nbsp;Translate content&nbsp;&nbsp;</label></div>';

        echo '<div class="bpml-activities-hide-cache-option"><label><input type="checkbox" name="bpml[collected_activities][' . $type . '][translate_content_cache]" value="1"';
        echo (isset($activity['translate_content_cache']) && $activity['translate_content_cache']) ? ' checked="checked"' : '';
        echo ' />';
        echo '&nbsp;Cache content translation&nbsp;&nbsp;</label></div></td>';

        echo '</tr>';
        echo '<tr><td colspan="3">
            <input type="submit" value="Clear cached translations" name="bpml_admin_clear_activity_translations_single[' . $type . ']" class="submit button-secondary" onclick="if (confirm(\'Are you sure?\\nThis activity will loose cached translation!\') == false) { return false; }" />
            <input type="submit" value="Clear all data" name="bpml_admin_clear_activity_data_single[' . $type . ']" class="submit button-secondary" onclick="if (confirm(\'Are you sure?\\nThis activity will loose language data!\') == false) { return false; }" />
            </td></tr>';

		//DO_ACTION	
        do_action('bpml_settings_form_collected_activities', $type, $activity);

        echo '</table>';
    }
    echo '</div>';

	//DO_ACTION	
    do_action('bpml_settings_form_after');

    echo '<br /><br /><input type="submit" value="Save Settings" name="bpml_save_options" class="submit button-primary" />';
    echo '&nbsp;<input type="submit" value="Reset Settings" name="bpml_reset_options" class="submit button-secondary" onclick="if (confirm(\'Are you sure?\\nAll custom settings will be lost!\') == false) { return false; }" />';
    echo '</form>';
    echo '</div></div>';
}

/**
 * Clears cached translation by activity type.
 *
 * @global  $wpdb
 * @param <type> $type
 */
function bpml_admin_clear_activity_translations_single($type) 
{
    global $wpdb;
	
    $results = $wpdb->get_results("SELECT id from {$wpdb->prefix}bp_activity WHERE type='" . $type . "'");
    
	foreach ($results as $key => $result) 
	{
        bpml_activities_clear_cache($result->id, 'bpml_google_translation');
    }
}

/**
 * Clears cached data by activity type.
 *
 * @global  $wpdb
 * @param <type> $type
 */
function bpml_admin_clear_activity_data_single($type) 
{
    global $wpdb;

    $results = $wpdb->get_results("SELECT id FROM {$wpdb->prefix}bp_activity WHERE type='" . $type . "'");

    foreach ($results as $key => $result) 
	{
        bpml_activities_clear_cache($result->id, 'bpml_google_translation');
        bpml_activities_clear_cache($result->id, 'bpml_lang');
        bpml_activities_clear_cache($result->id, 'bpml_lang_recorded');
        bpml_activities_clear_cache($result->id, 'bpml_lang_orphan');
    }
}
