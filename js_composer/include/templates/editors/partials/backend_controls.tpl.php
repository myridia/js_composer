<?php
/**
 * Backend controls template.
 *
 * @var string $shortcode
 * @var string $name
 * @var string $name_css_class
 * @var string $position
 * @var array $controls
 * @var bool $add_allowed
 * @var string $extended_css
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$edit_access = vc_user_access_check_shortcode_edit( $shortcode );
$all_access = vc_user_access_check_shortcode_all( $shortcode );
$move_access = vc_user_access()->part( 'dragndrop' )->checkStateAny( true, null )->get();
?>
<div class="vc_controls<?php echo ! empty( esc_attr( $extended_css ) ) ? ' ' . esc_attr( $extended_css ) : ''; ?>">
	<div class="vc_controls-<?php echo esc_attr( $position ); ?>">
		<a class="<?php echo esc_attr( $name_css_class ); ?>">
				<span class="vc_btn-content"
				<?php //phpcs:ignore ?>
				title="<?php
				if ( $all_access && $move_access ) :
					printf( esc_attr__( 'Drag to move %s', 'js_composer' ), esc_attr( $name ) );
					//phpcs:ignore 
					?>"><i class="vc-composer-icon vc-c-icon-dragndrop"></i>
					<?php
					else :
						print( esc_attr( $name ) );
						echo '">';
					endif;
					echo esc_html( $name );
					?>
				</span>
		</a>
		<?php foreach ( $controls as $control ) : ?>
			<?php if ( 'add' === $control && $add_allowed ) : ?>
				<a class="vc_control-btn vc_control-btn-prepend vc_edit" href="#"
						title="<?php printf( esc_attr__( 'Prepend to %s', 'js_composer' ), esc_attr( $name ) ); ?>">
					<span class="vc_btn-content"><i class="vc-composer-icon vc-c-icon-add"></i></span>
				</a>
			<?php elseif ( $edit_access && 'edit' === $control ) : ?>
				<a class="vc_control-btn vc_control-btn-edit" href="#"
						title="<?php printf( esc_attr__( 'Edit %s', 'js_composer' ), esc_attr( $name ) ); ?>">
					<span class="vc_btn-content"><i class="vc-composer-icon vc-c-icon-mode_edit"></i></span>
				</a>
			<?php elseif ( $all_access && 'clone' === $control ) : ?>
				<a class="vc_control-btn vc_control-btn-clone" href="#"
						title="<?php printf( esc_attr__( 'Clone %s', 'js_composer' ), esc_attr( $name ) ); ?>">
					<span class="vc_btn-content"><i class="vc-composer-icon vc-c-icon-clone"></i></span>
				</a>
			<?php elseif ( $all_access && 'copy' === $control ) : ?>
				<a class="vc_control-btn vc_control-btn-copy" href="#"
						title="<?php printf( esc_attr__( 'Copy %s', 'js_composer' ), esc_attr( $name ) ); ?>">
					<span class="vc_btn-content"><i class="vc-composer-icon vc-c-icon-copy"></i></span>
				</a>
			<?php elseif ( $all_access && 'paste' === $control ) : ?>
				<a class="vc_control-btn vc_control-btn-paste" href="#"
						title="<?php esc_attr_e( 'Paste', 'js_composer' ); ?>">
					<span class="vc_btn-content"><i class="vc-composer-icon vc-c-icon-paste"></i></span>
				</a>
			<?php elseif ( $all_access && 'delete' === $control ) : ?>
				<a class="vc_control-btn vc_control-btn-delete" href="#"
						title="<?php printf( esc_attr__( 'Delete %s', 'js_composer' ), esc_attr( $name ) ); ?>">
					<span class="vc_btn-content"><i class="vc-composer-icon vc-c-icon-delete_empty"></i></span>
				</a>
			<?php endif ?>
		<?php endforeach ?>
	</div>
</div>
<?php
