<?php 	get_header() ;?>

	<div id="content">
		<div class="padder">

			<div id="item-header">
				<?php locate_template( array( 'members/single/member-header.php' ), true ) ?>
			</div>

			<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav">
					<ul>
						<?php bp_get_displayed_user_nav() ?>
					</ul>
				</div>
			</div>


			<div id="item-body">

				<div class="item-list-tabs no-ajax" id="subnav">
					<ul>
						<?php bp_get_options_nav() ?>
					</ul>
				</div>

					<?php if (bp_album_has_pictures() ) : bp_album_the_picture();?>
					
				<div class="picture-single activity">
					<h3><?php bp_album_picture_title() ?></h3>
					
                	<div class="picture-outer-container">
                		<div class="picture-inner-container">
			                <div class="picture-middle">
            					<img id="picture-edit-thumb" src='<?php bp_album_picture_middle_url() ?>' />
				                <?php bp_album_adjacent_links() ?>
			                </div>
		                </div>
	                </div>
	                
                    
				<?php // JLL_MOD - adds face-tagging
                global $wpdb, $bp;
				$table_name = $wpdb->prefix . "bp_friends";
				$table_two_name = $wpdb->prefix . "bp_notifications";
				$currentuserid = $bp->loggedin_user->id;
				$currentusername = $bp->loggedin_user->fullname;
				$pid = bp_album_get_picture_id();
				$notifs = $wpdb->get_results( "SELECT item_id FROM " . $table_two_name. " WHERE user_id=" . $currentuserid . " AND secondary_item_id =" . $pid, ARRAY_A );
				foreach ( $notifs as $notif ) {
					bp_core_delete_all_notifications_by_type( $notif[item_id], album, 'user_tagged' );
				}
					//populate autofill
                    $friends = $wpdb->get_results( "SELECT friend_user_id, initiator_user_id FROM " . $table_name. " WHERE is_confirmed=1 AND (friend_user_id=" . $currentuserid  . " OR initiator_user_id=" . $currentuserid . ")", ARRAY_A );
                    for($i=0; $i<count($friends); $i++)
                    {
                        $singlefriend = $friends[$i];
                        if ( $currentuserid == $singlefriend[initiator_user_id] ) {
                            $friendids[] = $singlefriend[friend_user_id];
                            $friendnames[] = bp_core_get_user_displayname( $singlefriend[friend_user_id] );
                        } else {
                            $friendids[] = $singlefriend[initiator_user_id];
                            $friendnames[] = bp_core_get_user_displayname( $singlefriend[initiator_user_id] );
                        }		
                    }
					// populate default tags
					$thepic = bp_album_get_picture_id();
					$table_tags = $wpdb->prefix . "bp_album_tags";
					$default_tags = $wpdb->get_results( "SELECT * FROM " . $table_tags. " WHERE photo_id=" . $thepic, ARRAY_A );
                    for($i=0; $i<count($default_tags); $i++)
                    {
                        $singletag = $default_tags[$i];
						$d_tags_id[] = $singletag[id];
						$d_tags_tagged_id[] = $singletag[tagged_id];
						$d_tags_tagged_name[] = $singletag[tagged_name];
						$d_tags_height[] = $singletag[height];
						$d_tags_width[] = $singletag[width];
						$d_tags_top[] = $singletag[top_pos];
						$d_tags_left[] = $singletag[left_pos];
                    }
                ?>
				<script type="text/javascript">
					$(document).ready(function(){
                        $("#picture-edit-thumb").tag({
							autoComplete: [ <?php // set autofill
								echo '{ value: "' . $currentusername . '", label: "' . $currentusername . '", fid: "' . $currentuserid  . '", plink: "urlurlurlurl" },';
								for ( $i = 0; $i < count( $friends ); $i++ ) {
								echo '{ value: "' . $friendnames[$i] . '", label: "' . $friendnames[$i] . '", fid: "' . $friendids[$i] . '", plink: "urlurlurlurl" }, ';
								} ?>
								],
							defaultTags: [ <?php // set default tags
								for ( $i = 0; $i < count( $default_tags ); $i++ ) {
								echo '{ id: "' . $d_tags_id[$i] . '", label: "' . $d_tags_tagged_name[$i] . '", fid: "' . $d_tags_tagged_id[$i] . '", height: "' . $d_tags_height[$i] . '", width: "' . $d_tags_width[$i] . '", top: "' . $d_tags_top[$i] . '", left: "' . $d_tags_left[$i] . '", plink: "' . bp_core_get_user_domain($d_tags_tagged_id[$i]) . '" }, ';
								} ?>	  
								],
							save : function( width,height,top_pos,left,label,the_tag,fid ){
											var pid = "<?php bp_album_picture_id(); ?>";
											var fid = $("#fid").html(); 
											var oid = "<?php echo $bp->displayed_user->id; ?>";
											if (window.XMLHttpRequest)
											  {// code for IE7+, Firefox, Chrome, Opera, Safari
											  xmlhttp=new XMLHttpRequest();
											  }
											else
											  {// code for IE6, IE5
											  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
											  }
											xmlhttp.onreadystatechange=function()
											  {
											  if (xmlhttp.readyState==4 && xmlhttp.status==200)
												{
													var tagsetid = xmlhttp.responseText;
													the_tag.setId(tagsetid); // set tag id from db
													$("#fid").html("");
													$("#enable").html("Tag this photo");
													$("#enable").attr('class','tag-button');
													$("div.picture-middle").find('a').attr('id','');
												}
											  }
											xmlhttp.open("GET","<?php global $bp; echo $bp->root_domain; ?>/wp-content/plugins/bp-phototag/photo-tagging/phototag-gethint.php?wid="+width+"&hei="+height+"&top="+top_pos+"&left="+left+"&lab="+label+"&pid="+pid+"&fid="+fid+"&oid="+oid,true);
											xmlhttp.send();
										},
							remove: function(id){
											//alert('Here I can do some ajax to delete tag #'+id+' in my db');
											if (window.XMLHttpRequest)
											  {// code for IE7+, Firefox, Chrome, Opera, Safari
											  xmlhttp=new XMLHttpRequest();
											  }
											else
											  {// code for IE6, IE5
											  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
											  }
											xmlhttp.open("GET","<?php global $bp; echo $bp->root_domain; ?>/wp-content/plugins/bp-phototag/photo-tagging/phototag-gethint.php?d="+id+"&pid=<?php bp_album_picture_id(); ?>",true);
											xmlhttp.send();
										},
                        });
						$("#enable").click(function(){
									$("#picture-edit-thumb").parent().mousedown(function(e){
										$("#picture-edit-thumb").showDrag(e);
										$("#picture-edit-thumb").parent().unbind('mousedown');
									});
									$("#enable").html("Click on the photo to add a tag...");
									$("#enable").attr('class','');
									$("a.remover").attr('id','removelinks');
						});
                    });
                </script>
                <div id="fid" style="display:none;"></div><div id="uid" style="display:none;"><?php echo $bp->loggedin_user->id; ?></div><div id="did" style="display:none;"><?php echo $bp->displayed_user->id; ?></div><div id="pid" style="display:none;"><?php echo $pid; ?></div>
             <?php if ( empty( $friendids ) ) { $friendids = array(); } ?>
             <?php if ( $bp->loggedin_user->id == $bp->displayed_user->id || in_array($bp->loggedin_user->id,$friendids) || is_admin() ) { ?>
             <div id="enable" class="tag-button">Tag this photo</div> 
             <?php } ?>
                    
                    
                    
					<p class="picture-description"><?php bp_album_picture_desc() ?></p>
	                <p class="picture-meta">
	                <?php bp_album_picture_edit_link()  ?>	
	                <?php bp_album_picture_delete_link()  ?></p>
	                
				<?php bp_album_load_subtemplate( apply_filters( 'bp_album_template_screen_comments', 'album/comments' ) ); ?>
				</div>
					
					<?php else : ?>
					
				<div id="message" class="info">
					<p><?php echo bp_word_or_name( __( "This url is not valid.", 'bp-phototag' ), __( "Either this url is not valid or picture has restricted access.", 'bp-phototag' ),false,false ) ?></p>
				</div>
					
					<?php endif; ?>

			</div><!-- #item-body -->

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php locate_template( array( 'sidebar.php' ), true ) ?>

<?php get_footer() ?>