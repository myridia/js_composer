<?php
/**
 * Notice param template.
 *
 * @var array $notice
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$link = empty( $notice['link'] ) ? '' : $notice['link'];
$alt = empty( $notice['title'] ) ? __( 'wpbakery notice', 'js_composer' ) : $notice['title'];
?>
<div id="wpb-notice-<?php echo esc_attr( $notice['id'] ); ?>" class="updated wpb-notice">
	<?php if ( ! empty( $notice['image'] ) ) : ?>
        <?php // phpcs:ignore:WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="wpb-notice-image"  data-notice-link="<?php echo esc_attr( $link ); ?>" style="<?php echo empty( $notice['link'] ) ?: 'cursor: pointer'; ?>">
			<img src="<?php echo esc_attr( $notice['image'] ); ?>" alt="<?php echo esc_attr( $alt ); ?>">
		</div>
	<?php endif; ?>
	<div class="wpb-notice-text">
		<?php if ( ! empty( $notice['title'] ) ) : ?>
			<p class="title">
				<?php echo esc_html( $notice['title'] ); ?>
			</p>
		<?php endif; ?>
		<?php if ( ! empty( $notice['description'] ) ) : ?>
			<div class="wpb-notice-context">
				<?php echo esc_html( $notice['description'] ); ?>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $notice['button_text'] ) ) : ?>
			<button type="button" class="button button-primary wpb-notice-button" data-notice-link="<?php echo esc_attr( $link ); ?>">
				<?php echo esc_html( $notice['button_text'] ); ?>
			</button>
		<?php endif; ?>
	</div>

	<button type="button" class="notice-dismiss wpb-notice-dismiss">
		<span class="screen-reader-text"><?php esc_attr_e( 'Dismiss this notice', 'js_composer' ); ?></span>
	</button>
</div>
