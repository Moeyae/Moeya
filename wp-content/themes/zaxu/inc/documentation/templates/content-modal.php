<?php
/*
 * @Description: Content modal
 * @Version: 2.6.9
 * @Author: ZAXU
 * @Link: https://www.zaxu.com
 * @Package: ZAXU
 */

if ( !defined('ABSPATH') ) exit;

$name = $email = '';

if ( is_user_logged_in() ) {
    $user  = wp_get_current_user();
    $name  = $user->display_name;
    $email = $user->user_email;
}
?>
<div id="zaxudocs-contact-modal" class="zaxudocs-contact-modal">
    <div class="zaxudocs-modal-header">
        <h1><?php _e('How can we help?', 'zaxu'); ?></h1>
        <a href="#" id="zaxudocs-modal-close" class="zaxudocs-modal-close"><i class="zaxudocs-icon zaxudocs-icon-times"></i></a>
    </div>

    <div class="zaxudocs-modal-body">
        <form id="zaxudocs-contact-modal-form" action="" method="post">
            <div class="zaxudocs-form-row">
                <label for="name"><?php _e('Name', 'zaxu'); ?></label>

                <div class="zaxudocs-form-field">
                    <input type="text" name="name" id="name" placeholder="" value="<?php echo $name; ?>" required />
                </div>
            </div>

            <div class="zaxudocs-form-row">
                <label for="name"><?php _e('Email', 'zaxu'); ?></label>

                <div class="zaxudocs-form-field">
                    <input type="email" name="email" id="email" placeholder="you@example.com" value="<?php echo $email; ?>" <?php disabled( is_user_logged_in() ); ?> required />
                </div>
            </div>

            <div class="zaxudocs-form-row">
                <label for="name"><?php _e('Subject', 'zaxu'); ?></label>

                <div class="zaxudocs-form-field">
                    <input type="text" name="subject" id="subject" placeholder="" value="" required />
                </div>
            </div>

            <div class="zaxudocs-form-row">
                <label for="name"><?php _e('Message', 'zaxu'); ?></label>

                <div class="zaxudocs-form-field">
                    <textarea type="message" name="message" id="message" required></textarea>
                </div>
            </div>

            <div class="zaxudocs-form-action">
                <input type="submit" name="submit" value="<?php echo esc_attr_e('Send', 'zaxu'); ?>">
                <input type="hidden" name="doc_id" value="<?php the_ID(); ?>">
                <input type="hidden" name="action" value="zaxudocs_contact_feedback">
            </div>
        </form>
    </div>
</div>