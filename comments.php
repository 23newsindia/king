<?php
/**
 * The template for displaying comments and posts.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form, as well as properly structured lists for posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package king
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">
	<?php if ( get_field( 'enable_user_groups', 'options' ) && ! king_groups_permissions( 'groups_write_comments' ) && ! is_super_admin() ) : ?>
		<div class="no-respond">
			<h3 class="entry-title">
				<i class="fas fa-lock fa-lg"></i>
				<?php esc_html_e( 'You do not have permission to write comments on this post.', 'king' ); ?>
			</h3>
			<?php if ( ! is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_login'] ); ?>" class="king-alert-button"><?php esc_html_e( 'Log in', 'king' ); ?></a>
				<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_register'] ); ?>"><?php esc_html_e( 'Register', 'king' ); ?></a>
			<?php endif; ?>
		</div>
	<?php else : ?>
		<?php comment_form(); ?>
		<div class="king-error comment-error" style="display: none;"></div>
	<?php endif; ?>

	<?php
	// Display comments if available.
	if ( have_comments() ) : ?>
		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'callback' => 'king_comment',
				'style'    => 'ul',
				'short_ping' => true,
			) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav id="comment-nav-below" class="navigation comment-navigation">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'king' ); ?></h2>
				<div class="nav-links">
					<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'king' ) ); ?></div>
				</div><!-- .nav-links -->
			</nav><!-- #comment-nav-below -->
		<?php endif; ?>
	<?php endif; ?>

	<?php
	// Display note if comments are closed but exist.
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'king' ); ?></p>
	<?php endif; ?>
</div><!-- #comments -->