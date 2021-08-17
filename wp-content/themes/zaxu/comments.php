<?php
/*
 * @Description: Comments
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

if ( post_password_required() ) {
	return;
}
?>

<section class="site-comments-container site-overlay-element">
	<div class="comments-wrap">
		<header class="comments-header">
			<div class="comments-title">
				<span class="close"></span>
				<h3><?php echo __('Comments', 'zaxu'); ?></h3>
			</div>
		</header>
		<div class="comments-content">
			<div class="comments-box">
				<?php
					// Check comment author must fill out name and email start
						$name_email_required = get_option('require_name_email');
						if ($name_email_required == 1) {
							$name_field = '<input id="author" name="author" type="text" placeholder="' . __('Name', 'zaxu') . '*" data-label="' . __('Name', 'zaxu') . '" required />';
							$email_field = '<input id="email" name="email" type="text" placeholder="' . __('Email', 'zaxu') . '*" data-label="' . __('Email', 'zaxu') . '" required />';
						} else {
							$name_field = '<input id="author" name="author" type="text" placeholder="' . __('Name', 'zaxu') . '" data-label="' . __('Name', 'zaxu') . '" />';
							$email_field = '<input id="email" name="email" type="text" placeholder="' . __('Email', 'zaxu') . '" data-label="' . __('Email', 'zaxu') . '" />';
						}
					// Check comment author must fill out name and email end
					
					comment_form(
						array(
							'fields' => apply_filters('comment_form_default_fields',
								array(
									'author' => '
										<div class="form-author">
											' . $name_field . '
										</div>
									',
									'email' => '
										<div class="form-email">
											' . $email_field . '
										</div>
									',
									'url' => '
										<div class="form-website">
											<input id="url" name="url" type="text" placeholder="' . __('Website', 'zaxu') . '" data-label="' . __('Website', 'zaxu') . '" />
										</div>
									',
								)
							),
							'comment_field' => '
								<div class="form-comment">
									<textarea id="comment" name="comment" placeholder="' . __('Comment', 'zaxu') . '*" data-label="' . __('Comment', 'zaxu') . '" required></textarea>
								</div>
							',
							'must_log_in' => '<p class="must-log-in">' .  sprintf( wp_kses( __('You must be <a href="%1$s">logged in</a> to post a comment.', 'zaxu'), array( 'a' => array( 'href' => array() ) ) ), wp_login_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',
							'logged_in_as' => '<p class="logged-in-as">' . sprintf( wp_kses( __('You are logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'zaxu'), array( 'a' => array( 'href' => array() ) ) ), admin_url('profile.php'), isset($user_identity) ? $user_identity : '', wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) ) ) . '</p>',
							'comment_notes_before' => '',
							'comment_notes_after' => '',
							'id_form' => 'comment-form',
							'class_submit' => 'button-primary',
							'id_submit' => 'submit',
							'title_reply' => __('Comment', 'zaxu'),
							'title_reply_to' => __('Reply', 'zaxu'),
							'cancel_reply_link' => __('Cancel', 'zaxu'),
							'label_submit' => __('Send', 'zaxu'),
						)
					);
				?>
			</div>
			<div class="main-comments">
				<?php if ( have_comments() ) : ?>
					<?php if ( get_comment_pages_count() > 1 && get_option('page_comments') ) : ?>
						<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
							<div class="nav-links">
								<div class="nav-previous">
									<?php previous_comments_link( __('Older Comments', 'zaxu') ); ?>
								</div>
								<div class="nav-next">
									<?php next_comments_link( __('Newer Comments', 'zaxu') ); ?>
								</div>
							</div>
						</nav>
					<?php endif; ?>

					<ul id="comments-list">
						<?php
							wp_list_comments(
								array(
									'style' => 'ul',
									'short_ping' => true,
									'callback' => 'zaxu_comment'
								)
							);
						?>
					</ul>

					<?php if ( get_comment_pages_count() > 1 && get_option('page_comments') ) : ?>
						<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
							<div class="nav-links">
								<div class="nav-previous">
									<?php previous_comments_link( __('Older Comments', 'zaxu') ); ?>
								</div>
								<div class="nav-next">
									<?php next_comments_link( __('Newer Comments', 'zaxu') ); ?>
								</div>
							</div>
						</nav>
					<?php endif; ?>
				<?php else : ?>
					<?php zaxu_no_item_tips( __('This post doesn\'t have any comment. Be the first one!', 'zaxu') ); ?>
				<?php endif;
					if ( !comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments') ) : ?>
					<?php zaxu_no_item_tips( __('Comments are closed.', 'zaxu') ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
