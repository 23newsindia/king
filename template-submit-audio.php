<?php
/**
 * Submit video page.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$GLOBALS['hide'] = 'hide';
global $king_submit_errors;

if ( isset( $_POST['king_music_post_upload_form_submitted'] ) && wp_verify_nonce( $_POST['king_music_post_upload_form_submitted'], 'king_music_post_upload_form' ) ) {


	$video_url    = '';
	$video_upload = '';
	$video_embed  = '';
	$title        = sanitize_text_field( wp_unslash( $_POST['king_post_title'] ) );
	if ( isset( $_POST['video_url'] ) ) {
		$video_url = wp_unslash( $_POST['video_url'] ); // Input var okey.
	}
	if ( isset( $_POST['acf']['field_654ad5b1f1f25'] ) ) {
		$video_upload = esc_url( wp_unslash( $_POST['acf']['field_654ad5b1f1f25'] ) ); // Input var okey.
	}
	if ( isset( $_POST['acf']['field_59c9812458fe6'] ) ) {
		$video_embed = wp_unslash( $_POST['acf']['field_59c9812458fe6'] ); // Input var okey.
	}
	$tags    = sanitize_text_field( sanitize_text_field( wp_unslash( $_POST['king_post_tags'] ) ) );
	$content = wp_unslash( $_POST['king_post_content'] );


	$category = isset( $_POST['king_post_category'] ) ? $_POST['king_post_category'] : '' ;

	$king_submit_errors = array();

	if ( get_field( 'maximum_title_length', 'option' ) ) {
		$title_length = get_field( 'maximum_title_length', 'option' );
	} else {
		$title_length = '140';
	}

	if ( get_field( 'maximum_content_length', 'option' ) ) {
		$content_length = get_field( 'maximum_content_length', 'option' );
	} else {
		$content_length = '2000';
	}

	if ( trim( $title ) === '' ) {
		$king_submit_errors['title_empty'] = esc_html__( 'Title is required.', 'king' );
	} elseif ( strlen( $title ) > $title_length ) {
		$king_submit_errors['title_empty'] = esc_html__( 'Title is too long.', 'king' );
	}

	// Content must be set.
	if ( trim( $content ) === '' ) {
		$king_submit_errors['content_empty'] = esc_html__( 'Content is required.', 'king' );
	} elseif ( strlen( $content ) > $content_length ) {
		$king_submit_errors['content_empty'] = esc_html__( 'Content is too long.', 'king' );
	}

	// VideoURL must be set.
	if ( trim( $video_url ) === '' && trim( $video_upload ) === '' ) {
		$king_submit_errors['videourl_empty'] = esc_html__( 'Media is required.', 'king' );
	}

	if ( empty( $king_submit_errors ) ) {

		switch ( $_POST['submit_type'] ) {
			case 'send':
			if ( is_super_admin() ) {
				$poststatus = 'publish';
			} elseif ( get_field( 'verified_posts', 'option' ) === true && get_field( 'verified_account', 'user_' . get_current_user_id() ) ) {
				$poststatus = 'publish';
			} elseif ( get_field( 'disable_post_moderation', 'option' ) ) {
				$poststatus = 'publish';
			} elseif ( get_field( 'enable_user_groups', 'options' ) && king_groups_permissions( 'groups_create_posts_without_approval' ) && get_field( 'groups_create_posts_without_approval', 'options' ) ) {
				$poststatus = 'publish';
			} else {
				$poststatus = 'pending';
			}
			break;
			case 'save':
			$poststatus = 'draft';
			break;
		}

		$post_id = wp_insert_post(
			array(
				'post_title'    => wp_strip_all_tags( $title ),
				'post_content'  => $content,
				'tags_input'    => $tags,
				'post_category' => $category,
				'post_status'   => $poststatus,
			)
		);
		set_post_format( $post_id, 'audio' );

		update_field( 'video-url', $video_url, $post_id );
		update_post_meta( $post_id, '_video-url', 'field_654bf17128933' );

		if ( isset( $_POST['king_nsfw'] ) ) {
			$king_nsfw = '1';
			update_field( 'nsfw_post', $king_nsfw, $post_id );
			update_post_meta( $post_id, '_nsfw_post', 'field_57d041d6ab8e2' );
		}
		require_once KING_INCLUDES_PATH . 'videothumbs.php';
		$ktype = king_source( $video_url );

		if ( 'vimeo.com' === $ktype || 'dailymotion.com' === $ktype || 'metacafe.com' === $ktype || 'vine.co' === $ktype || 'instagram.com' === $ktype || 'vid.me' === $ktype || 'tiktok.com' === $ktype || 'soundcloud.com' === $ktype ) {

			$image_url = king_get_thumb( $video_url );

		} elseif ( 'youtube.com' === $ktype || 'youtu.be' === $ktype ) {

			$image_url = king_youtube( $video_url );

		} else {
			$image_url = king_get_thumb( $video_url );
		}

		$attach_id = king_upload_user_file_video( $image_url , $post_id );

		// Set selected image as the featured image.
		set_post_thumbnail( $post_id, $attach_id );

		do_action( 'acf/save_post', $post_id );

		if ( $post_id ) {
			$permalink = get_permalink( $post_id );
			wp_redirect( $permalink );
			exit;
		}
	}
}


?>
<?php
acf_form_head();
get_header();
?>

<header class="page-top-header">
	<h1 class="page-title"><?php echo esc_html_e( 'Submit Music', 'king' ); ?></h1>
</header><!-- .page-header -->

<?php get_template_part( 'template-parts/king-header-nav' ); ?>
<?php if ( ! is_user_logged_in() ) : ?>
	<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i><?php esc_html_e( 'You do not have permission to create a post !', 'king' ); ?>
	<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_login'] ); ?>" class="king-alert-button"><?php esc_html_e( 'Log in ', 'king' ); ?></a>
	<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_register'] ); ?>"><?php esc_html_e( 'Register', 'king' ); ?></a>
</div>
<?php elseif ( get_field( 'disable_video', 'options' ) !== false || get_field( 'disable_users_submit', 'options' ) !== false ) : ?>
<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i>
	<?php esc_html_e( 'You do not have permission to view this page!', 'king' ); ?></div>
<?php elseif ( get_field( 'only_verified', 'options' ) === true && ! get_field( 'verified_account', 'user_' . get_current_user_id() ) && ! is_super_admin() ) : ?>  
<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i><?php esc_html_e( 'You do not have permission to view this page!', 'king' ); ?></div>
<?php elseif ( get_field( 'enable_user_groups', 'options' ) && ! king_groups_permissions( 'groups_create_posts' ) && ! is_super_admin() ) : ?>
<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i><?php esc_html_e( 'You do not have permission to view this page!', 'king' ); ?></div>
<?php else : ?>
	<!-- #primary BEGIN -->
	<div id="primary" class="page-content-area">
		<main id="main" class="page-site-main king-submit-video king-submit-music">
			<?php if ( get_field( 'custom_message_video', 'options' ) ) : ?>
				<div class="king-custom-message">
					<?php echo get_field( 'custom_message_video', 'options' ); ?>
				</div>
			<?php endif; ?>

			<form id="king_posts_form" action="" method="POST" enctype="multipart/form-data">
				<div class="king-form-group">
					<label for="king_post_title"><?php esc_html_e( 'Title', 'king' ); ?></label>
					<input class="form-control bpinput" name="king_post_title" id="king_post_title" type="text" value="<?php echo esc_attr( isset( $_POST['king_post_title'] ) ? $_POST['king_post_title'] : '' ); ?>" maxlength="<?php echo get_field( 'maximum_title_length', 'option' ); ?>" required />
				</div>
				<?php if ( isset( $king_submit_errors['title_empty'] ) ) : ?>
					<div class="king-error"><?php echo esc_attr( $king_submit_errors['title_empty'] ); ?></div>
				<?php endif; ?>
				<?php
				$include    = array();
				$categories = get_terms(
					'category',
					array(
						'include'    => $include,
						'hide_empty' => false,
					)
				);

				$categories_count = count( $categories );
				if ( $categories_count > 1 ) :
					?>
					<div class="king-form-group form-categories">
						<span class="form-label"><?php esc_html_e( 'Select Category', 'king' ); ?></span>
						<ul>
							<?php
							foreach ( $categories as $cat ) {
								if ( $cat->parent == 0 ) {
									$catsfor = get_field( 'category_for', $cat );
									if ( $catsfor && in_array( 'for_music', $catsfor, true ) || ! $catsfor ) :
										echo '<li class="form-categories-item"><input type="checkbox" id="king_post_cat-' . esc_attr( $cat->term_id ) . '" name="king_post_category[]" value="' . esc_attr( $cat->term_id ) . '" /><label for="king_post_cat-' . esc_attr( $cat->term_id ) . '">' . esc_attr( $cat->name ) . '</label></li>';
									endif;
									foreach ( $categories as $subcategory ) {
										if ( $subcategory->parent == $cat->term_id ) {
											$scatsfor = get_field( 'category_for', $subcategory );
											if ( $scatsfor && in_array( 'for_music', $scatsfor, true ) || ! $scatsfor ) :
												echo '<li class="form-categories-item"><input type="checkbox" id="king_post_cat-' . esc_attr( $subcategory->term_id ) . '" name="king_post_category[]" value="' . esc_attr( $subcategory->term_id ) . '" /><label class="king-post-subcat" for="king_post_cat-' . esc_attr( $subcategory->term_id ) . '">' . esc_attr( $subcategory->name ) . '</label></li>';
											endif;
										}
									}
								}
							}
							?>
						</ul>
					</div>
				<?php endif; ?>

					<div class="inside acf-fields -top"><div class="acf-field acf-field-true-false acf-field-654ad532f1f24" data-name="video_tab" data-type="true_false" data-key="field_654ad532f1f24">

						<div class="acf-input">
							<div class="acf-true-false">
								<input type="hidden" name="acf[field_654ad532f1f24]" value="0">	<label>
									<input type="checkbox" id="acf-field_654ad532f1f24" name="acf[field_654ad532f1f24]" value="1" class="acf-switch-input" autocomplete="off">
									<div class="acf-switch"><span class="acf-switch-on" style="min-width: 40.0375px;">upload</span><span class="acf-switch-off" style="min-width: 40.0375px;">url</span><div class="acf-switch-slider"></div></div>				</label>
								</div>
							</div>
						</div>
						<div class="acf-field acf-field-oembed acf-field-654bf17128933" data-name="video-url" data-type="oembed" data-key="field_654bf17128933" data-conditions="[[{&quot;field&quot;:&quot;field_654ad532f1f24&quot;,&quot;operator&quot;:&quot;!=&quot;,&quot;value&quot;:&quot;1&quot;}]]">
							
								<div class="acf-input">
									<div class="acf-oembed">
										<input class="input-value" name="video_url" value="<?php echo esc_attr( isset( $_POST['video_url'] ) ? $_POST['video_url'] : '' ); ?>" type="hidden">
										<div class="title">
											<input class="input-search" value="<?php echo esc_attr( isset( $_POST['video_url'] ) ? $_POST['video_url'] : '' ); ?>" placeholder="<?php echo esc_html_e( 'Enter URL', 'king' ); ?>" autocomplete="off" type="text">
											<div class="acf-actions -hover">
												<a data-name="clear-button" href="#" class="acf-icon -cancel grey"></a>
											</div>
										</div>

										<div class="canvas">
											<div class="canvas-media">
											</div>

										</div>

									</div>
								</div>
							</div>
							<div class="acf-field acf-field-file acf-field-654ad5b1f1f25 acf-hidden" data-name="video_upload" data-type="file" data-key="field_654ad5b1f1f25" data-conditions="[[{&quot;field&quot;:&quot;field_654ad532f1f24&quot;,&quot;operator&quot;:&quot;==&quot;,&quot;value&quot;:&quot;1&quot;}]]" hidden="">

								<div class="acf-input">
									<div class="acf-file-uploader" data-library="uploadedTo" data-mime_types="mp3" data-uploader="wp">
										<input type="hidden" name="acf[field_654ad5b1f1f25]" value="" data-name="id" disabled="">	<div class="show-if-value file-wrap">
											<div class="file-icon">
												<img data-name="icon" src="" alt="">
											</div>
											<div class="file-info">
												<a data-name="filename" href="" target="_blank"></a>
												<strong><?php esc_html_e( 'size:', 'king' ); ?></strong><span data-name="filesize"></span>
											</div>
											<div class="acf-actions -hover">
												<a class="acf-icon -pencil dark" data-name="edit" href="#" title="Edit"></a>
												<a class="acf-icon -cancel dark" data-name="remove" href="#" title="Remove"></a>
											</div>
										</div>
										<div class="hide-if-value">

											<p><a data-name="add" class="acf-button button" href="#"><?php esc_html_e( 'Upload Music', 'king' ); ?></a></p>


										</div>
									</div>
								</div>
							</div>
							<div class="acf-field acf-field-image acf-field-654bf1aa28934 acf-hidden" data-name="_thumbnail_id" data-type="image" data-key="field_654bf1aa28934" data-conditions="[[{&quot;field&quot;:&quot;field_654ad532f1f24&quot;,&quot;operator&quot;:&quot;==&quot;,&quot;value&quot;:&quot;1&quot;}]]" hidden="">

								<div class="acf-input">
									<div class="acf-image-uploader" data-preview_size="medium" data-library="uploadedTo" data-mime_types="jpg, png, gif, jpeg, webp" data-uploader="wp">
										<input type="hidden" name="acf[field_654bf1aa28934]" value="" disabled="">	<div class="show-if-value image-wrap" style="max-width: 300px">
											<img src="" alt="" data-name="image" style="max-height: 300px;">
											<div class="acf-actions -hover">
												<a class="acf-icon -pencil dark" data-name="edit" href="#" title="Edit"></a>
												<a class="acf-icon -cancel dark" data-name="remove" href="#" title="Remove"></a>
											</div>
										</div>
										<div class="hide-if-value">
											<p><a data-name="add" class="acf-button button" href="#"><?php esc_html_e( 'Upload Thumbnail', 'king' ); ?></a></p>
										</div>
									</div>
								</div>
							</div>
							
							<div class="king-repeater acf-field acf-field-repeater acf-field-654bf1eb28935 acf-hidden" data-name="music_list" data-type="repeater" data-key="field_654bf1eb28935" data-conditions="[[{&quot;field&quot;:&quot;field_654ad532f1f24&quot;,&quot;operator&quot;:&quot;==&quot;,&quot;value&quot;:&quot;1&quot;}]]" hidden="">

								<div class="acf-input">
									<div class="acf-repeater -block -empty" data-min="0" data-max="30" data-pagination="0">
										<input type="hidden" name="acf[field_654bf1eb28935]" value="" class="acf-repeater-hidden-input" disabled="">			
										<table class="acf-table">
											<tbody class="ui-sortable" style="">
												<tr class="acf-row acf-clone" data-id="acfcloneindex">		<td class="acf-row-handle order ui-sortable-handle" title="Drag to reorder">
														<span>1</span>
														<div class="acf-row-handle remove">
															<a class="acf-icon -plus small acf-js-tooltip" href="#" data-event="add-row" title="Add row"></a>
															<a class="acf-icon -minus small acf-js-tooltip" href="#" data-event="remove-row" title="Remove row"></a>
														</div>
													</td>

												<td class="acf-fields">
													<div class="acf-field acf-field-text acf-field-654bf26328936" data-name="music_title" data-type="text" data-key="field_654bf26328936" data-conditions="[[{&quot;field&quot;:&quot;field_654ad532f1f24&quot;,&quot;operator&quot;:&quot;==&quot;,&quot;value&quot;:&quot;1&quot;}]]">

													<div class="acf-input">
														<div class="acf-input-wrap"><input type="text" id="acf-field_654bf1eb28935-acfcloneindex-field_654bf26328936" name="acf[field_654bf1eb28935][acfcloneindex][field_654bf26328936]" placeholder="<?php esc_html_e( 'music title', 'king' ); ?>" disabled=""></div></div>
													</div>
													<div class="acf-field acf-field-file acf-field-654bf28928937" data-name="music_file" data-type="file" data-key="field_654bf28928937" data-conditions="[[{&quot;field&quot;:&quot;field_654ad532f1f24&quot;,&quot;operator&quot;:&quot;==&quot;,&quot;value&quot;:&quot;1&quot;}]]">

														<div class="acf-input">
															<div class="acf-file-uploader" data-library="uploadedTo" data-mime_types="mp3" data-uploader="wp">
																<input type="hidden" name="acf[field_654bf1eb28935][acfcloneindex][field_654bf28928937]" value="" data-name="id" disabled="">	<div class="show-if-value file-wrap">
																	<div class="file-icon">
																		<img data-name="icon" src="" alt="">
																	</div>
																	<div class="file-info">
																		<a data-name="filename" href="" target="_blank"></a>
																		<strong><?php esc_html_e( 'size:', 'king' ); ?></strong><span data-name="filesize"></span>
																	</div>
																	<div class="acf-actions -hover">
																		<a class="acf-icon -pencil dark" data-name="edit" href="#" title="Edit"></a>
																		<a class="acf-icon -cancel dark" data-name="remove" href="#" title="Remove"></a>
																	</div>
																</div>
																<div class="hide-if-value">

																	<p><a data-name="add" class="acf-button button" href="#"><?php esc_html_e( 'Upload Music', 'king' ); ?></a></p>


																</div>
															</div>
														</div>
													</div>
												</td>
											</tr>
										</tbody>
										</table>
										<div class="acf-actions">
											<a class="acf-button acf-repeater-add-row button button-primary" href="#" data-event="add-row"><i class="fas fa-plus"></i></a>
											<div class="clear"></div>
										</div>
									</div>
								</div>
							</div>
						</div>

					<?php if ( isset( $king_submit_errors['videourl_empty'] ) ) : ?>
						<div class="king-error"><?php echo esc_attr( $king_submit_errors['videourl_empty'] ); ?></div>
					<?php endif; ?>

					<div class="king-form-group">
						<?php 
						if ( get_field( 'enable_ai', 'options' ) ) :
							$use = get_field('use_in_these_pages', 'options');
							$for = false;
							if ( in_array( 'music', $use ) ) :
								$for = true;
							endif;
							if ( $for ) :
								?>
								<?php get_template_part( 'template-parts/pages/king-ai' ); ?>
							<?php endif; ?>
						<?php endif; ?>
						<label for="king_post_content"><?php esc_html_e( 'Content', 'king' ); ?></label>
						<div class="tinymce" id="king_post_content"><?php echo wp_kses_post( isset( $_POST['king_post_content'] ) ? $_POST['king_post_content'] : '' ); ?></div>
					</div>
					<?php if ( isset( $king_submit_errors['content_empty'] ) ) : ?>
						<div class="king-error"><?php echo esc_attr( $king_submit_errors['content_empty'] ); ?></div>
					<?php endif; ?>

					<div class="king-form-group">
						<label for="king_post_tags"><?php esc_html_e( 'Tags', 'king' ); ?></label>
						<input class="form-control bpinput" name="king_post_tags" id="king_post_tags" type="text" value="<?php echo esc_attr( isset( $_POST['king_post_tags'] ) ? $_POST['king_post_tags'] : '' ); ?>" />
					</div>
					<span class="help-block"><?php esc_html_e( 'Separate each tag by comma. (tag1, tag2, tag3)', 'king' ) ?></span>

					<?php if ( get_field( 'enable_nsfw', 'options' ) ) : ?>
						<div class="king-nsfw">
							<input id="king_nsfw" type="checkbox" name="king_nsfw" value="0">
							<label for="king_nsfw"><?php esc_html_e( 'NSFW', 'king' ); ?></label>
						</div>
					<?php endif; ?>

					<button class="king-submit-button" data-loading-text="<?php esc_html_e( 'Loading...', 'king' ) ?>" type="submit" value="send" id="submit-loading" name="submit_type"><?php esc_html_e( 'Submit Post', 'king' ); ?></button>
					<?php if ( get_field( 'enable_save_posts', 'options' ) ) : ?>
						<button class="king-submit-button" data-loading-text="<?php esc_html_e( 'Loading...', 'king' ) ?>" name="submit_type" id="submit-loading2" type="submit" value="save"><?php esc_html_e( 'Save', 'king' ); ?></button>
					<?php endif; ?>
					<?php echo wp_nonce_field( 'king_music_post_upload_form', 'king_music_post_upload_form_submitted', true, false ); ?>

				</form>
			</main><!-- #main -->
		</div><!-- .main-column -->

	<?php endif; ?>
	<?php wp_enqueue_media(); ?>
	<?php get_template_part( 'template-parts/footer-parts' ); ?>
