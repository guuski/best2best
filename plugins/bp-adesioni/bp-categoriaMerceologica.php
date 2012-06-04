<?php
/*
	Plugin Name: bp_adesioni
	Plugin URI: http://wordpress.org/extend/plugins/
	Description: Agginge un nuovo tipo campo, nel campo profilo, chiamato 'box selezione multipla raggruppata'
	Author: Giovanni Giannone
	Version: 1.0
	Author URI: http://
*/
	
/*
	pagina: http://www.best2best.it/adesioni/ [^]

	nel drill down dovrebbe poter essere possibile selezionare anche "categoria merceologica", 
	dove il risultato della query sono le aziende ordinate alfabeticamente per categoria merceologica
	quindi: categorie dalla a alla z, all'interno delle categorie le aziende ordinate dalla a alla z
*/

load_plugin_textdomain( 'cM', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

add_action( 'bp_members_directory_member_types', 'cM_adesioni_Type');

add_action( 'bp_members_directory_member_sub_types', 'cM_adesioni_SubType');

add_action( 'bp_directory_members_content', 'cM_adesioni_Cont');

add_action( 'bp_members_directory_order_options', 'cM_adesioni_Cont');


/*
 * do_action( 'bp_before_directory_members_page' );
 * do_action( 'bp_before_directory_members' ); 
 * do_action( 'bp_before_directory_members_content' ); 
 * do_action( 'bp_members_directory_member_types' ); 
 * do_action( 'bp_members_directory_member_sub_types' ); 
 * do_action( 'bp_directory_members_content' );
 * do_action( 'bp_members_directory_order_options' );
 * do_action( 'bp_after_directory_members_content' ); 
 * do_action( 'bp_after_directory_members' ); 
 * do_action( 'bp_after_directory_members_page' ); 
 * 
 * */

function cM_adesioni_Type(){
	if ( is_user_logged_in() ) :
	?>
		<li id="categorie-merceologiche">
			<a href="<?php echo ABSPATH . 'wp-content/plugins/bp-adesioni/catMER.php' ?>">
				<?php printf( __( 'Categorie Merceologiche', 'buddypress' ) ); ?>
			</a>
		</li>
	<?php
	endif;
	}
	
function cM_adesioni_SubType(){
	if ( is_user_logged_in() ) :
	?>
		<h4>Subtype</h4>
		
	<?php
		cM_Main();
			
		
	endif;
	}
	
function cM_adesioni_Cont(){
	if ( is_user_logged_in() ) :
	?>
		<h1>Content</h1>
	<?php
	endif;
	}


/*====================================================================*/
/*====================================================================*/




/*====================================================================*/
/*====================================================================*/

/*Questo metodo mi ritorna l'id del nuovo field creato dall'utente oppure ritorna
 * -1 in caso di assenza del field*/
function cM_newfieldisset(){
	global $bp;
	global $wpdb;
	global $cM_nome;
	$query = "SELECT * FROM wp_bp_xprofile_fields f";
	$cM_output= $wpdb->get_results( $wpdb->prepare($query));
	foreach( (array)$cM_output as $field ){
		if ($field->type==$cM_nome) 
			return $field->id;
		}//end-foreach
	return -1;
}

function cM_caricaCategorie(){
	global $bp;
	global $wpdb;
	$cM_parent=cM_newfieldisset();
	/*seleziono dentro la tabella wp_bp_xprofile_data solo le righe aventi
	user_id uguale a quello dell'utente e value=ms Categorie Acquisti*/
	$query = "SELECT f.id, f.parent_id, f.name FROM wp_bp_xprofile_fields f WHERE f.type='multiselectboxrag' ORDER BY f.name ASC";
	$cM_array=array();
	$cM_output= $wpdb->get_results( $wpdb->prepare($query));
	 
	return  cM_caricaCT_ric($cM_output,$cM_parent);
}

function cM_caricaCT_ric($cM_db,$cM_par){
	
		if (is_array($cM_db)){
			$cM_mat='';
			foreach ($cM_db as $k => $v){
				if ($v->parent_id==$cM_par){
						$cM_mat["$v->name"]= cM_caricaCT_ric($cM_db,$v->id);
				}//end-if
			}//end-foreach
			return $cM_mat;
		}//end-if
	return '';
	}
	
function cM_Main(){
		$cnt=0;
		$HTML="";
		$cM_array = cM_caricaCategorie();
		foreach ($cM_array as $k => $v){
			if (is_array($v)){
					$cnt++;
					$HTML.="$k<br />";
					$HTML.=cM_getHTML_ric($v);
				}
			else{
				$cnt++;
				$HTML.="$k<br />";
			}
		}
		echo $HTML;
	}
	
function cM_getHTML_ric($cM_ins){
	$HTML="";
	global $cnt;
	foreach($cM_ins as $k => $v) {
			if (is_array($v)){
					$cnt++;
					$HTML.=__("$k",'cM');
					$HTML.=cM_getHTML_ric($v);
				}
			else{
				$cnt++;
				$HTML.=__("$k",'cM'); 
			}
			
		}//end-foreach	
	return $HTML;
}
/*====================================================================*/
/*====================================================================*/

/*====================================================================*/
/*====================================================================*/
/*
function bp_adesioni_CatMerc(){


?>
		

				<li id="categorie-merceologiche"><a href="<?php echo bp_loggedin_user_domain() . '/non so dove reindirizzare/' ?>"><?php printf( __( 'Categorie Merceologiche', 'buddypress' ) ); ?></a></li>

		
	<?php 
		//do_action( 'bp_before_directory_members_page' ); 
	?>

	<div id="content">
		<div class="padder">

		<?php 
			//do_action( 'bp_before_directory_members' ); 
		?>

		<form action="" method="post" id="members-directory-form" class="dir-form">

			<h3><?php _e( 'Members Directory', 'buddypress' ); ?></h3>

			<?php 
				//do_action( 'bp_before_directory_members_content' ); 
			?>

			<div id="members-dir-search" class="dir-search" role="search">

				<?php bp_directory_members_search_form(); ?>

			</div><!-- #members-dir-search -->

			<div class="item-list-tabs" role="navigation">
				<ul>
					<li class="selected" id="members-all"><a href="<?php echo trailingslashit( bp_get_root_domain() . '/' . bp_get_members_root_slug() ); ?>"><?php printf( __( 'All Members <span>%s</span>', 'buddypress' ), bp_get_total_member_count() ); ?></a></li>

					<?php if ( is_user_logged_in() && bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>

						<li id="members-personal"><a href="<?php echo bp_loggedin_user_domain() . bp_get_friends_slug() . '/my-friends/' ?>"><?php printf( __( 'My Friends <span>%s</span>', 'buddypress' ), bp_get_total_friend_count( bp_loggedin_user_id() ) ); ?></a></li>

					<?php endif; ?>
					
					<?php if ( is_user_logged_in() && bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>

						<li id="categorie-merceologiche">
						* <a href="<?php echo bp_loggedin_user_domain() . '/non so dove reindirizzare/' ?>">
						* 	<?php printf( __( 'Categorie Merceologiche', 'buddypress' ) ); ?></a></li>

					<?php endif; ?>

					<?php 
						//do_action( 'bp_members_directory_member_types' ); 
					?>

				</ul>
			</div><!-- .item-list-tabs -->

			<div class="item-list-tabs" id="subnav" role="navigation">
				<ul>

					<?php 
						//do_action( 'bp_members_directory_member_sub_types' ); 
					?>

					<li id="members-order-select" class="last filter">

						<label for="members-order-by"><?php _e( 'Order By:', 'buddypress' ); ?></label>
						<select id="members-order-by">
							<option value="active"><?php _e( 'Last Active', 'buddypress' ); ?></option>
							<option value="newest"><?php _e( 'Newest Registered', 'buddypress' ); ?></option>

							<?php if ( bp_is_active( 'xprofile' ) ) : ?>

								<option value="alphabetical"><?php _e( 'Alphabetical', 'buddypress' ); ?></option>

							<?php endif; ?>

							<?php do_action( 'bp_members_directory_order_options' ); ?>

						</select>
					</li>
				</ul>
			</div>

			<div id="members-dir-list" class="members dir-list">

				<?php locate_template( array( 'members/members-loop.php' ), true ); ?>

			</div><!-- #members-dir-list -->

			<?php 
				//do_action( 'bp_directory_members_content' ); 
			?>

			<?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>

			<?php 
				//do_action( 'bp_after_directory_members_content' ); 
			?>

		</form><!-- #members-directory-form -->

		<?php 
			//do_action( 'bp_after_directory_members' ); 
		?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php 
		//do_action( 'bp_after_directory_members_page' ); 
	?>
	
	<?php get_sidebar( 'buddypress' ); ?>
	<?php get_footer( 'buddypress' ); 

	}
	*/
	
	function cM_caricaID()
	{
		global $wpdb;
	
		$query = "SELECT ID FROM wp_users";
		$cM_array=array();
		$cM_output= $wpdb->get_results( $wpdb->prepare($query));
	 
		//foreach ($cM_output as $k =>$v) echo $v->ID;
		
		return  $cM_output;
	}
	
	cM_caricaID();
	

?>
