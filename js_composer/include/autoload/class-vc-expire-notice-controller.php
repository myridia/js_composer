<?php
/**
 * Autoload expire notice controller.
 *
 * @note we require our autoload files everytime and everywhere after plugin load.
 * @since 8.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Controller for plugin license expire notice system.
 *
 * @since 8.1
 */
class Vc_Expire_Notice_Controller {
	/**
	 * The slug of user meta that stores expire notice list that user close.
	 *
	 * @var string
	 * @since 8.1
	 */
	public $expire_notice_list = 'wpb_expire_close_list';

	/**
	 * Vc_Expire_Notice_Controller constructor.
	 *
	 * @since 8.1
	 */
	public function __construct() {
		add_action( 'admin_init', [
			$this,
			'init',
		] );

		add_action( 'admin_notices', [
			$this,
			'handle_expire_notice',
		] );

		add_action( 'wp_ajax_wpb_dismiss_expire_notice', [
			$this,
			'dismiss_expire_notice',
		] );
	}

	/**
	 * Init expire notice system.
	 *
	 * @since 8.1
	 */
	public function init() {
		$this->show_notices();
	}

	/**
	 * Check if WPBakery plugin has update available or not.
	 *
	 * @return bool
	 */
	public function check_for_plugin_update() {
		$plugin_slug = 'js_composer/js_composer.php';
		$updates = get_site_transient( 'update_plugins' );

		if ( isset( $updates->response[ $plugin_slug ] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Show expire notices to user.
	 *
	 * @since 8.1
	 */
	public function show_notices() {
		if ( vc_license()->isExpired() && $this->check_for_plugin_update() ) {
			add_action(
				'admin_notices',
				function () {
					vc_include_template( 'params/notice/expire-notice-assets.php' );
				}
			);
		}
	}

	/**
	 * Dismiss support expire notice.
	 *
	 * @since 8.1
	 * @return void
	 */
	public function dismiss_expire_notice() {
		vc_user_access()->checkAdminNonce()->validateDie();

		if ( empty( WPB_VC_VERSION ) ) {
			wp_send_json_error( false );
		}
		$version_number = WPB_VC_VERSION;

		$is_set = $this->save_expire_notice_close( $version_number );

		if ( $is_set ) {
			wp_send_json_success( true );
		} else {
			wp_send_json_error( false );
		}
	}

	/**
	 * Save expire notice close to user meta.
	 *
	 * @since 8.1
	 * @param string $version_number
	 * @return bool|int
	 */
	public function save_expire_notice_close( $version_number ) {
		$user_id = get_current_user_id();
		$notice_list = json_decode( get_user_meta( $user_id, $this->expire_notice_list, true ) );
		if ( ! is_array( $notice_list ) ) {
			$notice_list = [];
		}
		if ( ! in_array( $version_number, $notice_list ) ) {
			$notice_list[] = $version_number;
		}

		return update_user_meta( $user_id, $this->expire_notice_list, wp_json_encode( $notice_list ) );
	}

	/**
	 * When user has active license and support period is expired
	 * This method responsible to show an admin_notice.
	 *
	 * @since 8.1
	 */
	public function handle_expire_notice() {
		if ( vc_license()->isActivated() && vc_license()->isExpired() && $this->check_for_plugin_update() ) {
			$user_id = get_current_user_id();
			$notice_list = json_decode( get_user_meta( $user_id, $this->expire_notice_list, true ) );
			if ( empty( WPB_VC_VERSION ) ) {
				return;
			}
			$version_number = WPB_VC_VERSION;
			if ( is_array( $notice_list ) && in_array( $version_number, $notice_list ) ) {
				return;
			}
			$url = vc_updater()->getUpdaterUrl();
			$this->output_expire_notice( sprintf( ' ' . esc_html__( 'There is a new version of the WPBakery available. Automatic update is unavailable for this plugin. Visit the %1$slicense%2$s section for more information.', 'js_composer' ), '<a href="' . esc_url( $url ) . '">', '</a>' ), false, true );
		}
	}

	/**
	 * Output notice
	 *
	 * @since 8.1
	 * @param string $message
	 * @param bool $success
	 * @param bool $dismissible
	 */
	public function output_expire_notice( $message, $success = true, $dismissible = false ) {
		$classes = (bool) $success ? 'updated' : 'error';
		$classes = $dismissible ? $classes . ' notice is-dismissible wpb-notice wpb-expire-notice' : $classes;
		printf( '<div class="%s"><p>%s</p></div>', esc_attr( $classes ), wp_kses( $message, [
			'a' => [
				'href' => [],
				'title' => [],
				'target' => [],
				'rel' => [],
			],
		] ) );
	}
}

new Vc_Expire_Notice_Controller();
