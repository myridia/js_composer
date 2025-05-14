<?php
/**
 * UI Header template.
 *
 * @var string $header_css_class
 * @var string $title
 * @var array $controls
 * @var string $search_template
 * @var string $header_tabs_template
 * @var array $header_tabs_template_variables
 * @var bool $stacked_bottom
 * @var bool $is_ai_token_usage
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<!-- param window header-->
<div
	class="<?php echo esc_attr( $header_css_class ); ?> vc_ui-panel-header-container <?php echo ( isset( $stacked_bottom ) && $stacked_bottom ) || ! isset( $stacked_bottom ) ? 'vc_ui-panel-header-o-stacked-bottom' : ''; ?>"
	data-vc-ui-element="panel-heading">
	<div class="vc_ui-panel-header">
		<div class="vc_ui-panel-header-controls">
			<?php foreach ( $controls as $key => $control ) : ?>
				<?php if ( is_array( $control ) && isset( $control['template'] ) ) : ?>
					<?php vc_include_template( $control['template'], isset( $control['variables'] ) ? $control['variables'] : [] ); ?>
				<?php else : ?>
					<button type="button" class="vc_general vc_ui-control-button vc_ui-<?php echo esc_attr( $control ); ?>-button" data-vc-ui-element="button-<?php echo esc_attr( $control ); ?>">
						<i class="vc-composer-icon vc-c-icon-<?php echo esc_attr( $control ); ?>"></i></button>
				<?php endif ?>
			<?php endforeach; ?>
		</div>
		<div class="vc_ui-panel-header-header vc_ui-grid-gap" data-vc-panel-container=".vc_ui-panel-header-container">
			<h3 class="vc_ui-panel-header-heading"
				data-vc-ui-element="panel-title">
				<?php echo isset( $title ) ? esc_html( $title ) : ''; ?>
				<?php if ( ! empty( $is_ai_token_usage ) ) : ?>
					<?php vc_include_template( 'editors/popups/ai/token-usage.tpl.php' ); ?>
				<?php endif ?>
			</h3>
			<?php if ( ! empty( $search_template ) ) : ?>
				<?php vc_include_template( $search_template ); ?>
			<?php endif ?>
		</div>
		<div class="vc_ui-panel-header-content" data-vc-ui-element="panel-header-content">
			<?php
			if ( ! empty( $header_tabs_template ) ) :
				$payload = isset( $header_tabs_template_variables ) && is_array( $header_tabs_template_variables ) ? $header_tabs_template_variables : [];
				$payload = isset( $box ) ? array_merge( $payload, [ 'box' => $box ] ) : $payload;

				vc_include_template( $header_tabs_template, $payload );
				endif
			?>
		</div>
	</div>
</div>
