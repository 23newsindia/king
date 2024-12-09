<?php
/**
 * Submit Image Page.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $king_submit_errors;
$GLOBALS['hide'] = 'hide';
if ( isset( $_POST['king_post_upload_form_submitted'] ) && wp_verify_nonce( $_POST['king_post_upload_form_submitted'], 'king_post_upload_form' ) ) {
	$title = sanitize_text_field( $_POST['king_post_title'] );
	$tags  = sanitize_text_field( $_POST['king_post_tags'] );
	$thumb = sanitize_text_field( $_POST['acf']['field_60ae46d02f589'][0] );
	$category = isset( $_POST['king_post_category'] ) ? $_POST['king_post_category'] : '';
	$content  = stripslashes( $_POST['aibox'] ); 
	$king_submit_errors = array();

	if ( get_field( 'maximum_title_length', 'option' ) ) {
		$title_length = get_field( 'maximum_title_length', 'option' );
	} else {
		$title_length = '140';
	}

	if ( trim( $title ) === '' ) {
		$king_submit_errors['title_empty'] = esc_html__( 'Title is required.', 'king' );
	} elseif ( strlen( $title ) > $title_length ) {
		$king_submit_errors['title_empty'] = esc_html__( 'Title is too long.', 'king' );
	}
	if ( is_super_admin() ) {
		$poststatus = 'publish';
	} elseif ( get_field( 'moderate_stories', 'option' ) ) {
		$poststatus = 'pending';
	} else {
		$poststatus = 'publish';
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

		$postid    = wp_insert_post(
			array(
				'post_title'    => wp_strip_all_tags( $title ),
				'post_content'  => $content,
				'tags_input'    => $tags,
				'post_category' => $category,
				'post_status'   => $poststatus,
			)
		);

		$ftag      = 'post-format-image';
		$ftaxonomy = 'post_format';
		wp_set_post_terms( $postid, $ftag, $ftaxonomy );

		if ( isset( $_POST['king_nsfw'] ) ) {
			$king_nsfw = '1';
			update_field( 'nsfw_post', $king_nsfw, $postid );
			update_post_meta( $postid, '_nsfw_post', 'field_57d041d6ab8e2' );
		}
		
		do_action( 'acf/save_post', $postid );

		set_post_thumbnail( $postid, $thumb );
		if ( $postid ) {
			update_post_meta( $postid, 'ai_post', true );
			$permalink = get_permalink( $postid );
			wp_safe_redirect( $permalink );
			exit;
		}
	}
}

acf_form_head();
get_header();
$dlev = get_field( 'select_dalle', 'options' );
?>



<?php if ( ! is_user_logged_in() ) : ?>
	<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i><?php esc_html_e( 'You do not have permission to create a post !', 'king' ); ?>
	<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_login'] ); ?>" class="king-alert-button"><?php esc_html_e( 'Log in ', 'king' ); ?></a>
	<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_register'] ); ?>"><?php esc_html_e( 'Register', 'king' ); ?></a>
</div>

<?php elseif ( get_field( 'disable_ai', 'options' ) ) : ?>
	<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i>
		<?php esc_html_e( 'You do not have permission to view this page!', 'king' ); ?></div>
	<?php elseif ( get_field( 'only_verified', 'options' ) === true && ! get_field( 'verified_account', 'user_' . get_current_user_id() ) && ! is_super_admin() ) : ?>
	<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i><?php esc_html_e( 'You do not have permission to view this page!', 'king' );?></div>
<?php elseif ( get_field( 'enable_user_groups', 'options' ) && ! king_groups_permissions( 'groups_create_posts' ) && ! is_super_admin() ) : ?>
<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i><?php esc_html_e( 'You do not have permission to view this page!', 'king' ); ?></div>
<?php else : ?>
	<!-- #primary BEGIN -->
	<div id="primary" class="king-ai-page lr-padding">

		<form id="king_posts_form" class="" action="" method="POST" enctype="multipart/form-data">
			<div class="king-ai-block">
				<div class="kingai-box">
				<div class="king-form-group">
						<input type="text" id="ai-box" name="aibox" class="bpinput" placeholder="<?php esc_html_e( 'Enter your prompt', 'king' ); ?>" autocomplete="off">
						<div class="kingai-buttons">
							<?php if ('de3' === $dlev) : ?>
								<div data-toggle="dropdown" data-target=".kingai-box" aria-expanded="false"><i class="fa-solid fa-sliders"></i></div>
							<?php endif; ?>
							<select id="ai-select" class="hide"><option value="image"></option></select>
							<button type="button" id="ai-submit"><i class="fa-solid fa-paper-plane"></i><div class="loader"></div></button>
						</div>
				</div>		
					<?php if ('de3' === $dlev) : ?>
						<div class="ai-settings">
							<input type="radio" id="aisize1" name="aisize" value="1024x1024" class="hide" checked>
							<label for="aisize1" class="ailabel"><?php esc_html_e( 'Square (1:1)', 'king' ); ?></label>
							<input type="radio" id="aisize2" name="aisize" value="1024x1792" class="hide">
							<label for="aisize2" class="ailabel"><?php esc_html_e( 'Portrait (4:7)', 'king' ); ?></label>
							<input type="radio" id="aisize3" name="aisize" value="1792x1024" class="hide">
							<label for="aisize3" class="ailabel"><?php esc_html_e( 'Landscape (7:4)', 'king' ); ?></label>				
						</div>
					<?php endif; ?>
				</div>
				<div id="ai-results" style="display: flex;"></div>
			</div>
			<div class="story-control">
				<div class="king-form-group">
					<label for="king_post_title"><?php esc_html_e( 'Title', 'king' ); ?></label>
					<input class="form-control bpinput" name="king_post_title" id="king_post_title" type="text" value="<?php echo esc_attr( isset( $_POST['king_post_title'] ) ? $_POST['king_post_title'] : '' ); ?>" maxlength="<?php echo get_field( 'maximum_title_length', 'option' );?>" required />
				</div>
				<?php
				if ( isset( $king_submit_errors['title_empty'] ) ) : ?>
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
										if ( $catsfor && in_array( 'for_ai', $catsfor, true ) || ! $catsfor ) :
											echo '<li class="form-categories-item"><input type="checkbox" id="king_post_cat-' . esc_attr( $cat->term_id ) . '" name="king_post_category[]" value="' . esc_attr( $cat->term_id ) . '" /><label for="king_post_cat-' . esc_attr( $cat->term_id ) . '">' . esc_attr( $cat->name ) . '</label></li>';
									endif;
									foreach ( $categories as $subcategory ) {
										if ( $subcategory->parent == $cat->term_id ) {
												$scatsfor = get_field( 'category_for', $subcategory );
												if ( $scatsfor && in_array( 'for_ai', $scatsfor, true ) || ! $scatsfor ) :
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
					<div class="king-form-group">
						<label for="king_post_tags"><?php esc_html_e( 'Tags', 'king' ); ?></label>
						<input class="form-control bpinput" name="king_post_tags" id="king_post_tags" type="text" value="<?php echo isset( $_POST['king_post_tags'] ) ? $_POST['king_post_tags'] : '' ?>" />
					</div>
					<span class="help-block"><?php esc_html_e( 'Separate each tag by comma. (tag1, tag2, tag3)', 'king' ); ?></span>

					<?php if ( get_field( 'enable_nsfw', 'options' ) ) : ?>
						<div class="king-nsfw">
							<input id="king_nsfw" type="checkbox" name="king_nsfw" value="0">
							<label for="king_nsfw"><?php esc_html_e( 'NSFW', 'king' ); ?></label>
						</div>
					<?php endif; ?> 
				<button class="king-submit-button" data-loading-text="<?php esc_html_e( 'Loading...', 'king' ); ?>" type="submit" value="send" name="submit_type" id="submit-loading"><?php esc_html_e( 'Create Post', 'king' ); ?></button>
				<?php echo wp_nonce_field( 'king_post_upload_form', 'king_post_upload_form_submitted', true, false ); ?>
			</div>
			<input type="hidden" id="aipost" value="1">
		</form>
	</div><!-- .main-column -->
<?php endif; ?>
<?php wp_enqueue_media(); ?>
<?php get_template_part( 'template-parts/footer-parts' ); ?>
