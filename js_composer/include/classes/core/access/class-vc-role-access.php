<?php
/**
 * Handles role-based access control functionality.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_path_dir( 'CORE_DIR', 'access/abstract-class-vc-access.php' );

/**
 * Class Vc_Role_Access
 */
class Vc_Role_Access extends Vc_Access {
	/**
	 * The name of the role being managed.
	 *
	 * @var bool|string
	 */
	protected $roleName = false;

	/**
	 * Parts of the access system being managed, keyed by part and role name.
	 *
	 * @var array
	 */
	protected $parts = [];

	/**
	 * Vc_Role_Access constructor.
	 */
	public function __construct() {
		require_once ABSPATH . 'wp-admin/includes/user.php';
	}

	/**
	 *  Manage access for a specific part of the system.
	 *
	 *  This method retrieves or creates a controller for a specific part of the access system,
	 *  ensuring that the correct role and part-specific access rules are applied.
	 *
	 * @param string $part
	 * @return \Vc_Role_Access_Controller
	 * @throws \Exception
	 */
	public function part( $part ) {
		$role_name = $this->getRoleName();
		if ( ! $role_name ) {
			throw new Exception( 'roleName for vc_role_access is not set, please use ->who(roleName) method to set!' );
		}
		$key = $part . '_' . $role_name;
		if ( ! isset( $this->parts[ $key ] ) ) {
			require_once vc_path_dir( 'CORE_DIR', 'access/class-vc-role-access-controller.php' );
			$this->parts[ $key ] = new Vc_Role_Access_Controller( $part );
			$role_access_controller = $this->parts[ $key ];
			$role_access_controller->setRoleName( $this->getRoleName() );
		}

		$role_access_controller = $this->parts[ $key ];
		$role_access_controller->setValidAccess( $this->getValidAccess() ); // send current status to upper level.
		$this->setValidAccess( true ); // reset.

		return $role_access_controller;
	}

	/**
	 * Set role to get access to data.
	 *
	 * @param string $roleName
	 * @return $this
	 * @internal param $role
	 */
	public function who( $roleName ) {
		$this->roleName = $roleName;

		return $this;
	}

	/**
	 * Get the name of the role currently being managed.
	 *
	 * @return null|string
	 */
	public function getRoleName() {
		return $this->roleName;
	}
}
