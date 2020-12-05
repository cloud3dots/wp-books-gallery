<?php
/**
*	Front Parent Class
*/
class WBG_Front
{
	private $wbg_version;

	function __construct( $version ){
		$this->wbg_version = $version;
		$this->wbg_assets_prefix = substr(WBG_PRFX, 0, -1) . '-';
	}

	function wbg_front_assets(){

		wp_enqueue_style(	'wbg-front-style',
							WBG_ASSETS . 'css/' . $this->wbg_assets_prefix . 'front-style.css',
							array(),
							$this->wbg_version,
							FALSE );
		if ( !wp_script_is( 'jquery' ) ) {
			wp_enqueue_script('jquery');
		}
		wp_enqueue_script(  'wbg-front-script',
							WBG_ASSETS . 'js/wbg-front-script.js',
							array('jquery'),
							$this->wbg_version,
							TRUE );
	}

	function wbg_load_shortcode(){
		add_shortcode( 'wp_books_gallery', array( $this, 'wbg_load_shortcode_view' ) );
	}

	function wbg_load_shortcode_view($attr){
		$output = '';
		ob_start();
		include WBG_PATH . 'front/view/' . $this->wbg_assets_prefix . 'front-view.php';
		$output .= ob_get_clean();
		return $output;
	}

	function wbg_load_single_template( $template ) {

		global $post;

		if ( 'books' === $post->post_type  ) {
			return WBG_PATH . 'front/view/wbg-single-book.php';
		}

		return $template;
	}

	function wbg_add_extra_user_fields( $user ) {
		$args = array(
    		'post_type' => 'books',
    		'posts_per_page' => -1,
			);
		$query = new WP_Query($args);
?>
<h3>Select a Book</h3>
<select name="book">
    <?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
        <option value="<?php the_ID(); ?>"
               <? if (get_user_meta( $user->ID, "book", true) == get_the_ID() ) echo 'selected'; ?>>
    <?php the_title(); ?>
    </option>
<?php endwhile; endif; ?>
</select>
<?php
	}

	function wbg_validate_extra_user_fields( &$errors, $update = null, &$user  = null ) {
		if (!$_POST['book']) {
			$errors->add('book', "<strong>ERROR</strong>: YOUR ERROR MESSAGE.");
		}
	}

	function wbg_save_extra_user_fields( $user_id ) {
		update_user_meta($user_id, 'book', sanitize_text_field( $_POST['book'] ) );
	}
}
?>
