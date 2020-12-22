<?php
/**
 * General action, hooks activator
*/
class WBG_Activator{

	public static function activate() {
		self::set_default_roles();
		self::add_extended_user_profile_fields();
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

	private static function add_extended_user_profile_fields() {
		// // Insert New Group.
		// if (function_exists('xprofile_insert_field_group')) {
		// 	$field_group = array('name' => 'Instructor', 'description' => 'Instructor only field group');
		// 	$group_id = xprofile_insert_field_group($field_group);
		// 	$fields = array(array('field_group_id' => 1, 'type' => 'textbox', 'name' => 'Location', 'description' => 'Student Location'), array('field_group_id' => 1, 'type' => 'textarea', 'name' => 'Bio', 'description' => 'About Student'), array('field_group_id' => $group_id, 'type' => 'textbox', 'name' => 'Speciality', 'description' => 'Instructor Speciality'));
		// 	foreach ($fields as $field) {
		// 		xprofile_insert_field($field);
		// 	}
		// }

		// // Insert New Field.
		// xprofile_insert_field(
		// 	array (
		// 		'field_group_id'  => 1,
		//   		'name'            => 'Twitter',
		// 		'field_order'     => 1,
		// 		'is_required'     => false,
		// 		'type'            => 'textbox'
		// 	)
		// );
	}
}
