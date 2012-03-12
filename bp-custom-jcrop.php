<?php 
define( 'BP_AVATAR_FULL_WIDTH', null );
define( 'BP_AVATAR_FULL_HEIGHT', null );

add_action('bp_after_profile_avatar_upload_content', 'removeJcrop');
function removeJcrop() {
  	global $bp;

	$image = apply_filters( 'bp_inline_cropper_image', getimagesize( bp_core_avatar_upload_path() . $bp->avatar_admin->image->dir ) );

	$full_height = 150;
	$full_width  = 150;

	$width  = $image[0] / 2;
	$height = $image[1] / 2;
?>

	<script type="text/javascript">
			jQuery('#avatar-to-crop').Jcrop({
				onChange: showPreviewModified,
				onSelect: showPreviewModified,
				onSelect: updateCoords,
				setSelect: [ 50, 50, <?php echo $width ?>, <?php echo $height ?> ]
			});
			updateCoords({x: 50, y: 50, w: <?php echo $width ?>, h: <?php echo $height ?>});
			function showPreviewModified(coords) {
				if ( parseInt(coords.w) > 0 ) {
					var rx = <?php echo $full_width; ?> / coords.w;
					var ry = <?php echo $full_height; ?> / coords.h;

					jQuery('#avatar-crop-preview').css({
					<?php if ( $image ) : ?>
						width: Math.round(rx * <?php echo $image[0]; ?>) + 'px',
						height: Math.round(ry * <?php echo $image[1]; ?>) + 'px',
					<?php endif; ?>
						marginLeft: '-' + Math.round(rx * coords.x) + 'px',
						marginTop: '-' + Math.round(ry * coords.y) + 'px'
					});
				}
			}
			function updateCoords(c) {
				jQuery('#x').val(c.x);
				jQuery('#y').val(c.y);
				jQuery('#w').val(c.w);
				jQuery('#h').val(c.h);
			};
	</script>
	<style>#avatar-crop-pane { width: 150px; height: 150px; overflow: hidden; }
	</style>
  <?php
}

add_filter( 'bp_core_pre_avatar_handle_crop', 'bp_core_avatar_handle_crop_corrected',10,2);

function bp_core_avatar_handle_crop_corrected($val, $args = '' ) {
	global $bp;

	error_log("###########1-".$args);
	
	extract( $args, EXTR_SKIP );
	
	print_r($args,false);
	
	if ( !$original_file ) { error_log("original_file: ".$original_file);
		return false; }
	
	error_log("2");
	
	$original_file = bp_core_avatar_upload_path() . $original_file;

	if ( !file_exists( $original_file ) ) { error_log("file_exists original_file");
		return false; }
	
	error_log("3");
	
	if ( !$item_id )
		$avatar_folder_dir = apply_filters( 'bp_core_avatar_folder_dir', dirname( $original_file ), $item_id, $object, $avatar_dir );
	else
		$avatar_folder_dir = apply_filters( 'bp_core_avatar_folder_dir', bp_core_avatar_upload_path() . '/' . $avatar_dir . '/' . $item_id, $item_id, $object, $avatar_dir );

	if ( !file_exists( $avatar_folder_dir ) ) { error_log("file_exists avatar_folder_dir");
		return false; }
	
	error_log("4");
	
	require_once( ABSPATH . '/wp-admin/includes/image.php' );
	require_once( ABSPATH . '/wp-admin/includes/file.php' );

	// Delete the existing avatar files for the object
	bp_core_delete_existing_avatar( array( 'object' => $object, 'avatar_path' => $avatar_folder_dir ) );

	// Make sure we at least have a width and height for cropping
	if ( !(int)$crop_w )
		$crop_w = bp_core_avatar_full_width();

	if ( !(int)$crop_h )
		$crop_h = bp_core_avatar_full_height();

	// Set the full and thumb filenames
	$full_filename  = wp_hash( $original_file . time() ) . '-bpfull.jpg';
	$thumb_filename = wp_hash( $original_file . time() ) . '-bpthumb.jpg';

	// Crop the image
	$full_cropped  = wp_crop_image( $original_file, (int)$crop_x, (int)$crop_y, (int)$crop_w, (int)$crop_h, (int)$crop_w, (int)$crop_h, false, $avatar_folder_dir . '/' . $full_filename );
	$thumb_cropped = wp_crop_image( $original_file, (int)$crop_x, (int)$crop_y, (int)$crop_w, (int)$crop_h, bp_core_avatar_thumb_width(), bp_core_avatar_thumb_height(), false, $avatar_folder_dir . '/' . $thumb_filename );

	// Remove the original
	@unlink( $original_file );

	return false;
}
?>
