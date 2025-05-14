<?php
/**
 * Control View Post template.
 *
 * @var bool $is_mobile
 * @var integer $post_id
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>

<li class="vc_pull-right vc_hide-mobile <?php echo esc_attr( $is_mobile ? 'vc_hide-desktop' : '' ); ?>">
	<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"
		class="vc_icon-btn vc_back-button"
		title="<?php echo esc_attr__( 'Exit WPBakery Page Builder edit mode', 'js_composer' ); ?>">
		<i class="vc-composer-icon vc-c-icon-close"></i>
		<?php if ( $is_mobile ) : ?>
			<p><?php esc_html_e( 'Close', 'js_composer' ); ?></p>
		<?php endif; ?>
	</a>
</li>
