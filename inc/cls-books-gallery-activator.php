<?php
/**
 * General action, hooks activator
*/
class WBG_Activator{

	public static function activate() {
		self::set_default_roles();
	}

	public static function set_default_roles() {
		$are_defaults_set = get_option( 'wbg_default_roles_set' );
		if ( true === $are_defaults_set ) {
			return;
		}
		if ( get_role( 'book_club_lenders' ) === null ) {
			add_role(
				'book_club_lenders', __( 'Book Club Lenders' ), array(
					'read' => true,
				)
			);
		}
		if ( get_role( 'book_club_borrowers' ) === null ) {
			add_role(
				'book_club_borrowers', __( 'Book Club Borrowers' ), array(
					'read' => true,
				)
			);
		}
		$roles      = get_editable_roles();
		$role_names = array_keys( $roles );

		self::set_default_capabilities_for_each_role( $role_names );
		update_option( 'wbg_default_roles_set', true );
	}

	private static function set_default_capabilities_for_each_role( $role_names ) {
		foreach ( $role_names as $name ) {
			self::set_default_capability_for_one_role( $name );
		}
	}

	private static function set_default_capability_for_one_role( $name ) {
		$role = get_role( $name );
		$role->add_cap( 'lend_book' );
	}
}
