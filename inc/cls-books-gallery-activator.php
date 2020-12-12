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
		if ( get_role( 'book_club_lender' ) === null ) {
			add_role(
				'book_club_lender', __( 'Book Club Lender' ), array(
					'read' => true,
				)
			);
		}
		if ( get_role( 'book_club_borrower' ) === null ) {
			add_role(
				'book_club_borrower', __( 'Book Club Borrower' ), array(
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

		if ( 'administrator' == $name ) {
			self::set_book_club_borrow_capability( $role );
			self::set_book_club_lend_capability( $role );
			$role->add_cap( 'book_club_delete_book' );
			return;
		}

		if ( 'book_club_lender' == $name ) {
			self::set_book_club_lend_capability( $role );
		}

		if ( 'book_club_borrower' == $name ) {
			self::set_book_club_borrow_capability( $role );
		}
	}

	private static function set_book_club_lend_capability( $role ) {
		$role->add_cap( 'book_club_lend_book' );
		$role->add_cap( 'book_club_add_book' );
		$role->add_cap( 'book_club_edit_book' );
	}

	private static function set_book_club_borrow_capability( $role ) {
		$role->add_cap( 'book_club_borrow_book' );
	}

}
