<?php

/**
 * Our main plugin class
 */
class WBG_Master
{

	protected $wbg_loader;
	protected $wbg_version;

	/**
	 * Class Constructor
	 */
	function __construct() {
		$this->wbg_version = WBG_VERSION;
		add_action( 'plugins_loaded', array( $this, 'wbg_load_plugin_textdomain' ) );
		$this->wbg_load_dependencies();
		$this->wbg_trigger_admin_hooks();
		$this->wbg_trigger_front_hooks();
	}

	function wbg_load_plugin_textdomain() {
		load_plugin_textdomain( WBG_TXT_DOMAIN, FALSE, WBG_TXT_DOMAIN . '/languages/' );
	}

	private function wbg_load_dependencies() {
		require_once WBG_PATH . 'admin/' . WBG_CLS_PRFX . 'admin.php';
		require_once WBG_PATH . 'front/' . WBG_CLS_PRFX . 'front.php';
		require_once WBG_PATH . 'inc/' . WBG_CLS_PRFX . 'loader.php';
		$this->wbg_loader = new WBG_Loader();
	}

	private function wbg_trigger_admin_hooks() {
		$wbg_admin = new WBG_Admin($this->wbg_version());
		$this->wbg_loader->add_action('admin_enqueue_scripts', $wbg_admin, WBG_PRFX . 'enqueue_assets');
		$this->wbg_loader->add_action('init', $wbg_admin, WBG_PRFX . 'custom_post_type_books', 0);
		$this->wbg_loader->add_action('init', $wbg_admin, WBG_PRFX . 'taxonomy_for_books', 0);
		$this->wbg_loader->add_action('add_meta_boxes', $wbg_admin, WBG_PRFX . 'book_details_metaboxes');
		$this->wbg_loader->add_action('save_post', $wbg_admin, WBG_PRFX . 'save_book_meta', 1, 1);
		$this->wbg_loader->add_action('admin_menu', $wbg_admin, WBG_PRFX . 'admin_menu', 0);
		// Add actions for required plugins.
		$this->wbg_loader->add_action('tgmpa_register', $wbg_admin, WBG_PRFX . 'register_required_plugins', 0);
		// Add actions for extending Quick Edit.
		$this->wbg_loader->add_filter( 'manage_posts_columns', $wbg_admin, WBG_PRFX . 'add_column_lenders', 10, 2 );
		$this->wbg_loader->add_filter( 'manage_edit-post_columns', $wbg_admin, WBG_PRFX . 'remove_column_lenders' );
		$this->wbg_loader->add_action( 'quick_edit_custom_box', $wbg_admin, WBG_PRFX . 'quickedit_posts_custom_box', 10, 2 );
	}

	function wbg_trigger_front_hooks() {
		$wbg_front = new WBG_Front($this->wbg_version());
		$this->wbg_loader->add_action('wp_enqueue_scripts', $wbg_front, WBG_PRFX . 'front_assets');
		$this->wbg_loader->add_filter('single_template', $wbg_front, 'wbg_load_single_template', 10);
		$wbg_front->wbg_load_shortcode();
	}

	function wbg_run() {
		$this->wbg_loader->wbg_run();
	}

	function wbg_version() {
		return $this->wbg_version;
	}

	function wbg_register_settings() {
		if ( ! get_option( 'wbg_flush_rewrite_rules_flag' ) ) {
			add_option( 'wbg_flush_rewrite_rules_flag', true );
		}
		require_once WBG_PATH . 'inc/' . WBG_CLS_PRFX . 'activator.php';
		WBG_Activator::activate();
	}

	function wbg_unregister_settings() {
		global $wpdb;

		$tbl = $wpdb->prefix . 'options';
		$search_string = WBG_PRFX . '%';

		$sql = $wpdb->prepare("SELECT option_name FROM $tbl WHERE option_name LIKE %s", $search_string);
		$options = $wpdb->get_results($sql, OBJECT);

		if (is_array($options) && count($options)) {
			foreach ($options as $option) {
				delete_option($option->option_name);
				delete_site_option($option->option_name);
			}
		}

		require_once WBG_PATH . 'inc/' . WBG_CLS_PRFX . 'deactivator.php';
		WBG_Deactivator::deactivate();
	}
}
