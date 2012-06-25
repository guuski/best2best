<?php

class BPBD {
	var $get_params = array();
	var $filterable_fields = array();
	
	/**
	 * PHP 4 constructor
	 *
	 * @package BP Better Directories
	 * @since 1.0
	 */
	function bpbd() {
		$this->__construct();
	}

	/**
	 * PHP 5 constructor
	 *
	 * @package BP Better Directories
	 * @since 1.0
	 */	
	function __construct() {
		define( 'BPBD_INSTALL_DIR', trailingslashit( dirname(__FILE__) ) );
		define( 'BPBD_INSTALL_URL', plugins_url() . '/bp-better-directories/' );
		
		if ( version_compare( BP_VERSION, '1.3', '<' ) ) {
			require( BPBD_INSTALL_DIR . 'includes/1.5-abstraction.php' );
		}
	
		$this->setup_get_params();
		
		add_action( 'init', array( $this, 'setup' ) );
		
		add_action( 'wp_ajax_members_filter', array( $this, 'filter_ajax_requests' ), 1 );
		
		add_action( 'wp_print_styles', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action('wp_enqueue_scripts', array( $this, 'my_add_frontend_scripts'));
	}
	
	function my_add_frontend_scripts() {
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-widget');
		wp_enqueue_script('jquery-ui-button');
	}
	
	
	function setup() {
		global $bp;
		
		// Temporary backpat for 1.2
		if ( function_exists( 'bp_is_current_component' ) ) {
			$is_members = bp_is_current_component( 'members' );
		} else {
			$is_members = $bp->members->slug == bp_current_component();
		}
		
		if ( $is_members && !bp_is_single_item() ) {			
			// Add the sql filters
			add_filter( 'bp_core_get_paged_users_sql', array( $this, 'users_sql_filter' ), 10, 2 );
			add_filter( 'bp_core_get_total_users_sql', array( $this, 'users_sql_filter' ), 10, 2 );
			
/////	//////////	//////////	//////////	//////////	//////////	//////////	//////////	//////////	//////////	//////////	//////////	//////////	/////
			// Add the filter UI
		
// 			add_action( 'bp_before_directory_members', array( $this, 'filter_ui' ) );					
			add_action ('bp_members_directory_member_sub_types', array( $this, 'filter_ui'));		
/////	//////////	//////////	//////////	//////////	//////////	//////////	//////////	//////////	//////////	//////////	//////////	/////			
		}
	}
	
	function setup_get_params() {
		$filterable_fields = get_blog_option( BP_ROOT_BLOG, 'bpdb_fields' );
		
		// Set so it can be used object-wide
		$this->filterable_fields = $filterable_fields;
		
		if ( is_array( $filterable_fields ) ) {
			$filterable_keys = array_keys( $filterable_fields );
		}
		
		$potential_fields = isset( $_GET ) ? $_GET : array();
		
		$cookie_fields = isset( $_COOKIE['bpbd-filters'] ) ? (array)json_decode( stripslashes( $_COOKIE['bpbd-filters'] ) ) : null;
		
		if ( isset( $_COOKIE['bpbd-filters'] ) )
			$potential_fields = array_merge( $potential_fields, $cookie_fields );
		
		foreach ( (array)$filterable_keys as $filterable_key ) {
			if ( !empty( $potential_fields[$filterable_key] ) ) {
		
				// Get the field id for keying the array
				$field_id = $filterable_fields[$filterable_key]['id'];
				
				// Put the field data into the get_params property array
				$this->get_params[$field_id] = $filterable_fields[$filterable_key];
				
				// Add the filtered value from $_GET
				if ( is_array( $potential_fields[$filterable_key] ) ) {
					$values = array();
					foreach( $potential_fields[$filterable_key] as $key => $value ) {
						$values[$key] = urldecode( $value );
					}
				} else {
					$values = urldecode( $potential_fields[$filterable_key] );
				}
				
				$this->get_params[$field_id]['value'] = $values;
			}
		}
		
		// Todo: sort order?
	}
	
	function users_sql_filter( $s, $sql ) {
		global $bp, $wpdb;
		
		$bpbd_select = array();
		$bpbd_from = array();
		$bpbd_where = array();
		$counter = 1;
		
		// Build the additional queries
		foreach( $this->get_params as $field_id => $field ) {
			$table_shortname = 'bpbd' . $counter;
		
			// Since we're already doing the join, let's bring the extra content into
			// the template. This'll be unset in the total_users filter
			$bpbd_select[] = $wpdb->prepare( ", {$table_shortname}.value as {$field['slug']}" );
			
			$bpbd_from[] = $wpdb->prepare( "INNER JOIN {$bp->profile->table_name_data} {$table_shortname} ON ({$table_shortname}.user_id = u.ID)" );
			
			if ( 'textbox' == $field['type'] || 'multiselectbox' == $field['type'] || 'checkbox' == $field['type'] ) {
				// Multiselect and checkbox values may be stored as arrays, so we
				// have to do multiple LIKEs. Hack alert
				$clauses = array();
				foreach( (array)$field['value'] as $val ) {
					$clauses[] = $table_shortname . '.value LIKE "%%' . $val . '%%"';
				}
				$where = implode( ' OR ', $clauses );
			} else {
				// Everything else is an exact match
				$op = '=';
				$value = $wpdb->prepare( "%s", $field['value'] );
				$where = $table_shortname . '.value' . $op . ' ' . $value;
			}
			
			$bpbd_where[] = $wpdb->prepare( "AND {$table_shortname}.field_id = %d AND ({$where})", $field['id'] );
			
			$counter++;
		}
		
		if ( !empty( $bpbd_from ) && !empty( $bpbd_where ) ) {
			// The total_sql query won't have this index
			if ( isset( $sql['select_main'] ) )
				$sql['select_main'] .= ' ' . implode( ' ', $bpbd_select );			
			
			$sql['from'] .= ' ' . implode( ' ', $bpbd_from );
			$sql['where'] .= ' ' . implode( ' ', $bpbd_where );
					
			$s = join( ' ', (array)$sql );
		}
		
		return $s;
	}	

	function filter_ajax_requests() {
		header("Cache-Control: no-cache, must-revalidate");
		add_filter( 'bp_core_get_paged_users_sql', array( $this, 'users_sql_filter' ), 10, 2 );
		add_filter( 'bp_core_get_total_users_sql', array( $this, 'users_sql_filter' ), 10, 2 );
	}
	
	function filter_ui() {
		if ( empty( $this->filterable_fields ) ) {
			// Nothing to see here.
			return;
		}
		
		?>
	<li>
<style>.ui-button-text-only .ui-button-text {padding: 0px 6px; }
	div.item-list-tabs ul.bpbd-search-terms li a,ul.bpbd-search-terms  div.item-list-tabs ul.bpbd-search-terms li span {display: inline;}
	div.item-list-tabs ul li:first-child {margin-left: 5px;}
</style>
		<form id="bpbd-filter-form" method="get" action="<?php bp_root_domain() ?>/<?php bp_members_root_slug() ?>">
		<div id="bpbd-filters">
			<h4><?php _e( 'Narrow Results', 'bpbd' ) ?> <span id="bpbd-clear-all"><a href="#"><?php _e( 'Clear All', 'bpbd' ); ?></a></span></h4>
			<ul style="display:none; width:100%;">
			<?php foreach ( $this->filterable_fields as $slug => $field ) : ?>
				<li  style="margin-left:0px; border-bottom: 1px solid #0A9BDE; padding-bottom: 5px;" id="bpbd-filter-crit-<?php echo esc_attr( $field['slug'] ) ?>" class="bpbd-filter-crit bpbd-filter-crit-type-<?php echo esc_attr( $field['type'] ) ?>">
					<?php $this->render_field( $field ) ?>
				</li>
			<?php endforeach ?>
			</ul>
		
			<input type="submit" class="button" value="<?php _e( 'Submit', 'bpbd' ) ?>" />
		</div>
		
		</form>
		</li>
		<?php
	}
	
	function render_field( $field ) {		
		?>
		<label for="<?php echo esc_attr( $field['slug'] ) ?>"><?php echo esc_html( $field['name'] ) ?> <span class="bpbd-clear-this" style="padding: 2px;">
		<a href="#" style="padding: 2px 5px;"><?php _e( 'Clear', 'bpbd' ); ?></a></span></label>
		
		<?php
		
		$field_data = new BP_XProfile_Field( $field['id'] );
		?>
				<?php 
// 				print_r($field_data);
				
		$options = $field_data->get_children();
// 		print_r($options);
// 		if($field_data->type=='box selezione multipla raggruppata') {	
// 			foreach ( $options as $option ) {
// 				$c_data = new BP_XProfile_Field( $option->id );
// 				$c_data->group_id=3;
// 				$cc=$c_data->get_children();
// 				print_r($c_data);
// 				echo "-------------gbp--------\n";
// 				print_r($cc);
				
// 			}
// 		}
// 		print_r($field);
// 		print_r($field_data);
		?>			 <?php 
		// Get the current value for this item, if any, out of the $_GET params
		$value = isset( $this->get_params[$field['id']] ) ? $this->get_params[$field['id']]['value'] : false;

		// Display the field based on type
		switch ( $field['type'] ) {
			case 'radio' :
				?>
				
				<ul>
				<?php foreach ( $options as $option ) : ?>
					<li style="margin-left:0px;">
						<input type="radio" name="<?php echo esc_attr( $field['slug'] ) ?>" value="<?php echo urlencode( $option->name ) ?>" <?php checked( $value, $option->name, true ) ?>/> <?php echo esc_html( $option->name ) ?>
					</li>
				<?php endforeach ?>
				</ul>
				
				<?php
				break;
			case 'selectbox' :
				?>
				
				<select name="<?php echo esc_attr( $field['slug'] ) ?>">
					<option value="">--------</option>
					<?php foreach( $options as $option ) : ?>
						<option <?php selected( $value, $option->name, true ) ?>><?php echo $option->name ?></option>
					<?php endforeach ?>
				</select>
				
				<?php
				
				break;
			case 'multiselectbox' :
				?>
				
				<select name="<?php echo esc_attr( $field['slug'] ) ?>[]" multiple="multiple">
					<?php foreach( $options as $option ) : ?>
						<option <?php if ( is_array( $value ) && in_array( $option->name, $value ) ) : ?>selected="selected"<?php endif ?>/><?php echo $option->name ?></option>
					<?php endforeach ?>
				</select>
				
				<?php
				
				break;
			case 'checkbox' :
				switch ($field_data->type) {
					case 'box selezione multipla raggruppata': ?>
					<ul style="width:100%;"><li>
					
				<?php 
				$this->renderChildrens($options, $field);
				
				/* foreach ( (array)$options as $option ) :
				
				
				$c_data = new BP_XProfile_Field( $option->id );
				$c_data->group_id=3;
				$cc=$c_data->get_children();
				if(count((array)$cc)>0) { ?>
					<li style="width: 100%; display: block; height: 20px; color: #444;"><?=$option->name ?></li> 
				<?php 
					foreach ( (array)$cc as $children ) :
					?>
					<li>
						<input id="<?php echo esc_attr( $field['slug'] ).urlencode( $children->name ) ?>" type="checkbox" name="<?php echo esc_attr( $field['slug'] ) ?>[]" value="<?php echo urlencode( $children->name ) ?>" <?php if ( is_array( $value ) && in_array( $children->name, $value ) ) : ?>checked="checked"<?php endif ?>/><label for="<?php echo esc_attr( $field['slug'] ).urlencode( $children->name ) ?>"><?php echo esc_html( $children->name ) ?></label>
					</li>
							<?php
					endforeach;
				}
				else {
				?>
					<li >
						<input id="<?php echo esc_attr( $field['slug'] ).urlencode( $option->name ) ?>" type="checkbox" name="<?php echo esc_attr( $field['slug'] ) ?>[]" value="<?php echo urlencode( $option->name ) ?>" <?php if ( is_array( $value ) && in_array( $option->name, $value ) ) : ?>checked="checked"<?php endif ?>/><label for="<?php echo esc_attr( $field['slug'] ).urlencode( $option->name ) ?>"><?php echo esc_html( $option->name ) ?></label>
					</li>
				<?php
				}
				endforeach;
				 */
				?>
				</li></ul>
					
					
					<?php 
					break;
					
					default:
				?>
				
				<ul style="width:100%;">
				<?php foreach ( (array)$options as $option ) : ?>
					<li >
						<input id="<?php echo esc_attr( $field['slug'] ).urlencode( $option->name ) ?>" type="checkbox" name="<?php echo esc_attr( $field['slug'] ) ?>[]" value="<?php echo urlencode( $option->name ) ?>" <?php if ( is_array( $value ) && in_array( $option->name, $value ) ) : ?>checked="checked"<?php endif ?>/><label for="<?php echo esc_attr( $field['slug'] ).urlencode( $option->name ) ?>"><?php echo esc_html( $option->name ) ?></label>
					</li>
				<?php endforeach ?>
				</ul>
				
				<?php
				}
				break;
			case 'textbox' :
			default :
				?>

				<input id="bpbd-filter-<?php echo esc_attr( $field['slug'] ) ?>" type="text" name="<?php echo esc_attr( $field['slug'] ) ?>" value=""/>
				
				<ul class="bpbd-search-terms">
				<?php if ( is_array( $value ) && !empty( $value ) ) : ?>		
					<?php foreach ( (array)$value as $sterm ) : ?>
						<?php if ( !trim( $sterm ) ) continue; ?>
						<li id="bpbd-value-<?php echo sanitize_title( $sterm ) ?>"><span class="bpbd-remove" style="display:inline; padding: 2px 3px;"><a href="#">x</a><?php echo esc_html( $sterm ) ?></span></li>
					<?php endforeach ?>	
				<?php endif ?>				
				</ul>
				
				<?php /* Comma-separated string */ ?>
				<input class="bpbd-hidden-value" id="bpbd-filter-value-<?php echo esc_attr( $field['slug'] ) ?>" type="hidden" name="bpbd-filter-value-<?php echo esc_attr( $field['slug'] ) ?>" value="<?php echo esc_attr( implode( ',', (array)$value ) ) ?>" />
				
				<?php
				
				break;
		}
	}
	
	function enqueue_styles() {
		if ( bp_is_directory() && bp_is_members_component() ) {
			wp_enqueue_style( 'jquery-loadmask-css', BPBD_INSTALL_URL . '/includes/lib/jquery.loadmask/jquery.loadmask.css' ); 
			wp_enqueue_style( 'bpbd-css', BPBD_INSTALL_URL . '/includes/css/style.css' );
			wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
		}
	}
	
	function enqueue_scripts() {
		if ( bp_is_directory() && bp_is_members_component() ) {
			wp_enqueue_script( 'jquery-loadmask', BPBD_INSTALL_URL . '/includes/lib/jquery.loadmask/jquery.loadmask.min.js', array( 'jquery' ) );	
			wp_enqueue_script( 'bpbd-js', BPBD_INSTALL_URL . '/includes/js/bpbd.js', array( 'jquery', 'dtheme-ajax-js', 'jquery-loadmask' ) );
			
		}
	}
	
	function renderChildrens($options, $field, $inner = 0) {
		 
		foreach ( (array)$options as $option ) {
			$c_data = new BP_XProfile_Field( $option->id );
			$c_data->group_id=3;
			$cc=$c_data->get_children();
			if(count((array)$cc)>0 && isset($cc[0])) { ?>
				<div class="bd_container">
				<span class="bd_title" style="line-height: 14px !important; width: auto; display: block; height: 14px; margin-top: 0px; color: #444; padding: 0px; cursor:pointer;"><?=$option->name ?></span>
				<span class="bd_content" style="padding:0; display:inline-block;"><?php    
		 		$this->renderChildrens((array)$cc,$field,$inner+1);
		 		?></span> 
		 		</div><?php
			}
			else {
				$value = isset( $this->get_params[$field['id']] ) ? $this->get_params[$field['id']]['value'] : false;
			?>
				<span class="bd_input" style="padding:0; display:none;">
					<input id="<?php echo esc_attr( $field['slug'] ).urlencode( $option->name ) ?>" type="checkbox" name="<?php echo esc_attr( $field['slug'] ) ?>[]" value="<?php echo urlencode( $option->name ) ?>" <?php if ( is_array( $value ) && in_array( $option->name, $value ) ) : ?>checked="checked"<?php endif ?>/><label for="<?php echo esc_attr( $field['slug'] ).urlencode( $option->name ) ?>"><?php echo esc_html( $option->name ) ?></label>
				</span>
			<?php
			}
		}
	}

}

?>