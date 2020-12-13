<?php
/**
 * General action, hooks deactivator
*/
class WBG_Deactivator{

	public static function deactivate() {
		self::remove_extended_user_profile_fields();
	}

	public static function remove_extended_user_profile_fields() {

		// if (function_exists('xprofile_delete_field')) {
		// 	xprofile_delete_field(
		// 		xprofile_get_field_id_from_name('Twitter')
		// 	);
		// }

		// if (function_exists('xprofile_insert_field_group')) {
		// 	$fields = array(array('field_group_id' => 1, 'type' => 'textbox', 'name' => 'Location', 'description' => 'Student Location'), array('field_group_id' => 1, 'type' => 'textarea', 'name' => 'Bio', 'description' => 'About Student'));
		// 	foreach ($fields as $field) {
		// 		$field_id = xprofile_get_field_id_from_name($field['name']);
		// 		xprofile_delete_field( $field_id );
		// 	}
		// 	// $field_group = array('name' => 'Instructor', 'description' => 'Instructor only field group');
		// 	// $group_id = xprofile_insert_field_group($field_group);
		// 	// $fields = array(array('field_group_id' => $group_id, 'type' => 'textbox', 'name' => 'Speciality', 'description' => 'Instructor Speciality'));
		// }
	}
}
