<?php
/**
 * Admin-side handling: per-post "disable enlargement" option.
 *
 * @package Inlarge
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the post meta and the classic-editor meta box that let an author
 * disable Inlarge for an individual post. Enlargement is enabled by
 * default, so the stored meta only ever records the "disabled" state.
 */
class Inlarge_Admin {

	const NONCE_ACTION = 'inlarge_save_meta';
	const NONCE_NAME   = 'inlarge_nonce';

	/**
	 * Wire up hooks.
	 */
	public static function init() {
		// Late priority so custom post types (usually registered on init:10)
		// are already available when we read the post-type list.
		add_action( 'init', array( __CLASS__, 'register_meta' ), 99 );
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_box' ) );
		add_action( 'save_post', array( __CLASS__, 'save_meta' ), 10, 2 );
	}

	/**
	 * Post types that should expose the per-post option.
	 *
	 * Only the post types enabled on the settings page get the meta box, so a
	 * globally-disabled type shows no per-post toggle.
	 *
	 * @return string[]
	 */
	protected static function post_types() {
		return inlarge_enabled_post_types();
	}

	/**
	 * Register the meta so it is available to the block editor (REST) too.
	 */
	public static function register_meta() {
		foreach ( self::post_types() as $post_type ) {
			$args = array(
				'type'          => 'boolean',
				'single'        => true,
				'default'       => false,
				'show_in_rest'  => true,
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			);

			register_post_meta( $post_type, INLARGE_META_KEY, $args );
			register_post_meta( $post_type, INLARGE_GALLERY_META_KEY, $args );
		}
	}

	/**
	 * Add the classic-editor meta box.
	 */
	public static function add_meta_box() {
		foreach ( self::post_types() as $post_type ) {
			add_meta_box(
				'inlarge',
				__( 'Inlarge', 'inlarge' ),
				array( __CLASS__, 'render_meta_box' ),
				$post_type,
				'side',
				'default'
			);
		}
	}

	/**
	 * Render the meta box contents.
	 *
	 * @param WP_Post $post Current post.
	 */
	public static function render_meta_box( $post ) {
		$enabled           = ! (bool) get_post_meta( $post->ID, INLARGE_META_KEY, true );
		$galleries_enabled = (bool) get_post_meta( $post->ID, INLARGE_GALLERY_META_KEY, true );

		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );
		?>
		<p>
			<label>
				<input type="checkbox" name="inlarge_enabled" value="1" <?php checked( $enabled ); ?> />
				<?php esc_html_e( 'Enable image enlargement for this post', 'inlarge' ); ?>
			</label>
		</p>
		<p class="description">
			<?php esc_html_e( 'Checked by default. Uncheck to turn enlargement off for this post only.', 'inlarge' ); ?>
		</p>
		<hr />
		<p>
			<label>
				<input type="checkbox" name="inlarge_galleries" value="1" <?php checked( $galleries_enabled ); ?> />
				<?php esc_html_e( 'Apply to WordPress galleries', 'inlarge' ); ?>
			</label>
		</p>
		<p class="description">
			<?php esc_html_e( 'Off by default. Check this to make images in WordPress galleries enlargeable regardless of their link setting.', 'inlarge' ); ?>
		</p>
		<?php
	}

	/**
	 * Persist the option on save.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 */
	public static function save_meta( $post_id, $post ) {
		// Bail on autosave / revisions / bulk edits without our form.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}
		if ( ! in_array( $post->post_type, self::post_types(), true ) ) {
			return;
		}

		// Only act when our classic meta box was actually submitted.
		// (The block editor writes the meta via REST and is handled separately.)
		if ( ! isset( $_POST[ self::NONCE_NAME ] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST[ self::NONCE_NAME ] ) ), self::NONCE_ACTION ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Checkbox is "Enable ..."; checked (present) = enabled (the default).
		$enabled = ! empty( $_POST['inlarge_enabled'] );

		if ( $enabled ) {
			// Keep the DB clean: default (enabled) stores nothing.
			delete_post_meta( $post_id, INLARGE_META_KEY );
		} else {
			update_post_meta( $post_id, INLARGE_META_KEY, true );
		}

		// Galleries are opt-in; store only the "included" state.
		$galleries_enabled = ! empty( $_POST['inlarge_galleries'] );

		if ( $galleries_enabled ) {
			update_post_meta( $post_id, INLARGE_GALLERY_META_KEY, true );
		} else {
			delete_post_meta( $post_id, INLARGE_GALLERY_META_KEY );
		}
	}
}
