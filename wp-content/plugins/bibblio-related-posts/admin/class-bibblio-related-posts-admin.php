<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @category   Bibblio_Related_Posts_Admin
 * @package    Bibblio_Related_Posts
 * @subpackage Bibblio_Related_Posts/admin
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

if ( ! function_exists( 'wp_unslash' ) ) {
	/**
	 * Provides backwards compatibility to WordPress 4.0
	 *
	 * @param  string $string Input to be unslashed.
	 * @return string
	 */
	function wp_unslash( $string ) {
		return stripslashes( $string );
	}
}

if ( ! function_exists( 'wp_json_encode' ) ) {
	/**
	 * Provides backwards compatibility to WordPress 4.0
	 *
	 * @param  object $data Data to be encoded to JSON.
	 * @return string
	 */
	function wp_json_encode( $data ) {
		return wp_json_encode( $data );
	}
}

/**
 * The admin-specific functionality of the plugin.
 */
class Bibblio_Related_Posts_Admin {

	/**
	 * The name of this plugin
	 *
	 * @access private
	 * @var    string    $plugin_name
	 */
	private $plugin_name;

	/**
	 * The version of this plugin
	 *
	 * @access private
	 * @var    string    $version
	 */
	private $version;

	/**
	 * The version of this plugin
	 *
	 * @access public
	 * @var    array    $admin_error
	 */
	public $admin_error = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name . '-bootstrap', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', array(), '4.6.3s', 'all' );
		wp_enqueue_style( $this->plugin_name . '-mudule-setting-customize', plugin_dir_url( __FILE__ ) . 'css/bibblio_module_setting_layer_customize.css', array(), '4.6.3s', 'all' );
		wp_enqueue_style( 'bibblio-rcm-css', '//cdn.bibblio.org/rcm/4.6/bib-related-content.min.css', array(), false, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'bibblio-rcm-js', '//cdn.bibblio.org/rcm/4.6/bib-related-content.min.js', array(), false, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bibblio_related_posts-admin.js', array( 'jquery', 'bibblio-rcm-js' ), $this->version, false );
	}

	/**
	 * Add the plugin's menu item
	 */
	public function add_admin_page() {
		add_menu_page(
			'Bibblio Related Posts module',
			'Bibblio',
			'manage_options',
			$this->plugin_name,
			array( $this, 'load_admin_page_content' ),
			plugin_dir_url( __FILE__ ) . '../images/icon.png',
			100
		);
	}

	/**
	 * Load the setup wizard or admin page
	 */
	public function load_admin_page_content() {
		if ( is_admin() ) {
			// multiple forms are submitted to this function, and their nonces are handled separately inside the switch statement.
			if ( wp_verify_nonce( 'bibblio-non-existent-nonce' ) ) {
				wp_die( 'Verified non-existent nonce!' );
			}

			if ( isset( $_GET['debug'] ) ) { // Input var okay.
				include_once plugin_dir_path( __FILE__ ) . 'partials/templates/debug.php';
				include_once plugin_dir_path( __FILE__ ) . 'partials/templates/debug2.php';
				return;
			}

			if ( isset( $_GET['debug1'] ) ) { // Input var okay.
				include_once plugin_dir_path( __FILE__ ) . 'partials/templates/debug.php';
				return;
			}

			if ( isset( $_GET['debug2'] ) ) { // Input var okay.
				include_once plugin_dir_path( __FILE__ ) . 'partials/templates/debug2.php';
				return;
			}

			if ( isset( $_GET['debug_cronjob'] ) ) { // Input var okay.
				echo '<h2>Debug Cronjob:</h2>';

				echo '<pre>';

				$add_previous = Bibblio_Related_Posts_Configs::get( 'add_previous' );
				$importer_id  = Bibblio_Related_Posts_Configs::get( 'importer_id' );

				echo 'Value of "add_previous": ' . esc_html( $add_previous ) . '<br />';
				echo 'Value of "importer_id": ' . esc_html( $importer_id ) . '<br />';

				try {
					$bibblio_support = new Bibblio_Related_Posts_Support();
					$bibblio_support->import_posts();
				} catch ( Exception $e ) {
					echo 'Error calling "import_posts()": ';
				}

				echo '</pre>';
				return;
			}

			if ( ! empty( $_GET['success'] ) && ( 'true' === $_GET['success'] ) ) { // Input var okay.
				include_once plugin_dir_path( __FILE__ ) . 'partials/templates/success-support-page.php';
				return;
			}

			if ( ! empty( $_POST['method'] ) ) { // Input var okay.
				$method = sanitize_text_field( wp_unslash( $_POST['method'] ) ); // Input var okay.

				switch ( $method ) {
					case 'login_credentials_action':
						$this->login_action();
						break;

					case 'save_plugin_settings':
						$this->save_plugin_settings();
						break;

					case 'save_selected_post_types':
						$this->save_selected_post_types();
						break;

					case 'save_widget_settings':
						$this->save_widget_settings();
						break;

					case 'add_previous':
						check_admin_referer( 'add_previous', 'bibblio_add_previous_nonce' );
						$bibblio_support = new Bibblio_Related_Posts_Support();
						$bibblio_support->bibblio_add_previous_posts();
						break;

					case 'cancel_importing':
						check_admin_referer( 'cancel_importing', 'bibblio_cancel_importing_nonce' );
						$bibblio_support = new Bibblio_Related_Posts_Support();
						$bibblio_support->bibblio_cancel_importing();
						break;

					case 'disconnect':
						check_admin_referer( 'disconnect', 'bibblio_disconnect_nonce' );
						include_once plugin_dir_path( __FILE__ ) . '../includes/class-bibblio-related-posts-disconnector.php';
						Bibblio_Related_Posts_Disconnector::disconnect();
						break;
				}
			}
		}

		if ( ! $this->user_logged_in() ) {
			include_once plugin_dir_path( __FILE__ ) . 'partials/templates/get-started-template.php';

		} elseif ( ! $this->has_set_auto_adding() ) {
			include_once plugin_dir_path( __FILE__ ) . 'partials/templates/plugin-settings-page.php';

		} elseif ( ! $this->has_module() ) {
			include_once plugin_dir_path( __FILE__ ) . 'partials/templates/modules-settings-page.php';

		} else {
			include_once plugin_dir_path( __FILE__ ) . 'partials/templates/admin-page-template.php';
		}
	}

	/**
	 * Log in to Bibblio when the user submits their credentials and store default config in WordPress
	 */
	public function login_action() {
		check_admin_referer( 'bibblio_login_form', 'bibblio_login_form_nonce' );

		if ( isset( $_POST['client_id'] ) && isset( $_POST['client_secret'] ) ) { // Input var okay.
			try {
				$client_id     = sanitize_text_field( wp_unslash( $_POST['client_id'] ) ); // Input var okay.
				$client_secret = sanitize_text_field( wp_unslash( $_POST['client_secret'] ) ); // Input var okay.

				$api       = new Bibblio_Api_Client( $client_id, $client_secret );
				$api_token = $api->get_available_token();

				if ( $api_token ) {
					$plan_name = $api->get_account_plan()['name'];

					// set default (zero/empty) values on first install - the importer and AJAX calls will update these to correct values.
					$content_item_count   = 0;
					$recommendation_usage = [
						[],
						[],
						[],
						[],
						array(
							'year'  => date( 'Y' ),
							'month' => date( 'm' ),
							'total' => 0,
						),
					];
					$recommendation_key   = ( $api ) ? $api->create_recommendation_key() : null;

					Bibblio_Related_Posts_Configs::set(
						array(
							'client_id'            => $client_id,
							'client_secret'        => $client_secret,
							'token'                => $api_token,
							'recommendation_key'   => $recommendation_key,
							'plan_name'            => $plan_name,
							'recommendation_usage' => $recommendation_usage,
							'content_item_count'   => $content_item_count,
						)
					);
				}
			} catch ( BibblioException $e ) {
				$error = $e->getMessage();

				// auth failure.
				if ( '4' === $error[0] ) {
					$this->admin_error['login_failed'] = $error;
				} else {
					$this->admin_error['server_error'] = $error;
					$this->admin_error['ssl_error']    = $this->is_ssl_error( $error );
				}
			}
		}
	}

	/**
	 * Save "posts" settings (whether to import past and future posts)
	 */
	public function save_plugin_settings() {
		check_admin_referer( 'bibblio_save_plugin_options', 'bibblio_save_plugin_options_nonce' );

		$this->save_selected_post_types( false );

		Bibblio_Related_Posts_Configs::set( 'auto_add', ( isset( $_POST['auto_add'] ) ? true : false ) ); // Input var okay.
		Bibblio_Related_Posts_Configs::set( 'appended_rcm', ( isset( $_POST['appended_rcm'] ) ? sanitize_text_field( wp_unslash( $_POST['appended_rcm'] ) ) : false ) ); // Input var okay.

		if ( isset( $_POST['add_previous'] ) ) { // Input var okay.
			$bibblio_support = new Bibblio_Related_Posts_Support();
			$bibblio_support->schedule_importer();
		}
	}

	/**
	 * Save selected post types
	 *
	 * @param boolean $check_nonce Whether or not to check for a submitted form nonce.
	 */
	public function save_selected_post_types( $check_nonce = true ) {
		if ( $check_nonce ) {
			check_admin_referer( 'bibblio_selected_post_types', 'bibblio_selected_post_types_nonce' );
		}

		// get existing custom post types (if any are selected).
		$existing_post_types = Bibblio_Related_Posts_Configs::get( 'selected_post_types' );
		$existing_post_types = ( $existing_post_types && ( ( count( $existing_post_types ) ) > 0 ) ) ? $existing_post_types : [];

		// merge the new selected post types with existing.
		$new_post_types      = ( isset( $_POST['custom_post_types'] ) && is_array( $_POST['custom_post_types'] ) ) ? array_values( array_map( 'sanitize_text_field', wp_unslash( $_POST['custom_post_types'] ) ) ) : []; // Input var okay.
		$new_post_types      = array_map( 'wp_unslash', $new_post_types );
		$new_post_types      = array_map( 'esc_html', $new_post_types );
		$selected_post_types = array_merge( $existing_post_types, $new_post_types );
		$selected_post_types = array_unique( $selected_post_types );

		Bibblio_Related_Posts_Configs::set( 'selected_post_types', $selected_post_types );
	}

	/**
	 * Save widget settings (eg: recommendation key to use)
	 */
	public function save_widget_settings() {
		check_admin_referer( 'bibblio_save_widget_options', 'bibblio_save_widget_options_nonce' );

		if ( isset( $_POST['recsKey'] ) ) { // Input var okay.
			$recs_key = sanitize_text_field( wp_unslash( $_POST['recsKey'] ) ); // Input var okay.
			Bibblio_Related_Posts_Configs::set( 'recommendation_key', $recs_key );
		} else {
			wp_safe_redirect( admin_url() . 'admin.php?page=bibblio_related_posts' );
		}
	}

	/**
	 * Save module settings (eg: layout and name)
	 *
	 * @param string $modules    The modules the user has defined.
	 */
	public function save_module_settings( $modules ) {
		Bibblio_Related_Posts_Configs::set( 'modules', $modules );
		return true;
	}

	/**
	 * Handle redirect back to the admin page
	 */
	public function return_to_admin_panel() {
		check_admin_referer( 'bibblio_tabs', 'bibblio_tabs_nonce' );

		if ( isset( $_GET['tab'] ) && ( 'support_tab' === $_GET['tab'] ) ) { // Input var okay.
			wp_safe_redirect( admin_url() . 'admin.php?page=bibblio_related_posts&tab=support_tab' );
		} else {
			wp_safe_redirect( admin_url() . 'widgets.php' );
		}
	}

	/**
	 * Add a meta-box to add/edit posts page to toggle saving to Bibblio
	 */
	public function post_add_meta_box() {
		$bibblio_support = new Bibblio_Related_Posts_Support();
		$post_types      = $bibblio_support->get_selected_post_types();

		// prevent WordPress from showing the metabox if no custom post types are selected.
		$post_types = ( $post_types ) ? $post_types : 'BIBBLIO_HIDE_METABOX_NO_SELECTED_POST_TYPES';

		if ( $this->user_logged_in() ) {
			add_meta_box(
				'import_post_to_bibblio',
				__( 'Import to Bibblio', 'bibblio_text' ),
				array(
					$this,
					'bibblio_post_add_meta_box',
				),
				$post_types,
				'side'
			);
		}
	}

	/**
	 * Logic and config for the posts metabox
	 *
	 * @param object $post The WordPress post being created or edited.
	 */
	public function bibblio_post_add_meta_box( $post ) {
		wp_nonce_field( 'bibblio_additional_custom_box', 'bibblio_meta_box_nonce' );

		$bibblio = new Bibblio_Related_Posts_Support();
		$bibblio->bibblio_init();
		$message         = array();
		$content_item_id = $bibblio->get_content_item_id( $post->ID );
		$must_import     = ( get_post_meta( $post->ID, '_bibblio_ingest', true ) ) ? true : false;

		if ( $content_item_id ) {
			$message['label']       = esc_html__( 'Imported', 'bibblio_text' );
			$message['description'] = esc_html__( 'Uncheck if you want to delete this post from Bibblio', 'bibblio_text' );
		} else {
			$message['label']       = esc_html__( 'Import post to Bibblio?', 'bibblio_text' );
			$message['description'] = esc_html__( 'Check to import this post to Bibblio', 'bibblio_text' );
		}

		// new post.
		if ( ! isset( $_GET['action'] ) ) { // Input var okay, CSRF ok.
			// if future posts are enabled...
			$checked = ( Bibblio_Related_Posts_Configs::get( 'auto_add' ) ) ? ' checked="checked"' : '';

			// edit post.
		} elseif ( 'edit' === $_GET['action'] ) { // Input var okay, CSRF ok.
			// if post was set to be ingested, or has been ingested...
			$checked = ( $must_import || $content_item_id ) ? ' checked="checked"' : '';
		}

		echo '<input type="checkbox" id="bibblio_import_checkbox" name="bibblio_import_checkbox"' . esc_attr( $checked ) . '/>';
		echo '<label for="bibblio_import_checkbox">' . esc_html( $message['label'] ) . '</label> ';

		echo '<p class="howto">' . esc_html( $message['description'] ) . '</p>';

		if ( $content_item_id ) {
			echo '<p><i>This post\'s Bibblio Content Item Id:</i><br /> <strong>' . esc_html( $content_item_id ) . '</strong></p>';
		}
	}

	/**
	 * Add a custom column to the posts listing page, for indicating if a posts is stored in Bibblio
	 *
	 * @param  array $columns The WordPress post-listing columns.
	 * @return array
	 */
	public function add_custom_column( $columns ) {
		$bibblio_support = new Bibblio_Related_Posts_Support();
		$selected_types  = $bibblio_support->get_selected_post_types();

		// only show the Bibblio column for selected types.
		if ( isset( $_GET['post_type'] ) && ! ( in_array( wp_unslash( $_GET['post_type'] ), $selected_types, true ) ) ) { // Input var okay, CSRF ok.
			return $columns;
		}

		if ( ! $this->user_logged_in() ) {
			return $columns;
		}

		$columns['bibblio'] = 'Bibblio';
		return $columns;
	}

	/**
	 * Logic for setting the custom column's value (if a post is in Bibblio)
	 *
	 * @param string $name The WordPress column's name.
	 */
	public function return_post_status( $name ) {
		global $post;
		if ( ( $this->user_logged_in() ) && ( 'bibblio' === $name ) ) {
			$content_item_id = trim( (string) get_post_meta( $post->ID, 'bibblio_content_item_id', true ) );

			if ( $content_item_id ) {
				echo '<i class="fa fa-check-square-o" aria-hidden="true" title="Content Item ID: ' . esc_html( $content_item_id ) . '"></i>';
			} elseif ( get_post_meta( $post->ID, '_bibblio_ingestion_error', true ) ) {
				switch ( get_post_meta( $post->ID, '_bibblio_ingestion_error_type', true ) ) {
					case 'title and content missing':
						echo 'Error importing - "title" and content body are missing.';
						break;
					case 'title missing':
						echo 'Error importing - "title" is missing.';
						break;
					case 'content missing':
						echo 'Error importing - content body is missing.';
						break;
					case 'server':
						echo 'Error importing - error communicating with Bibblio server.';
						break;
					case 'auth failed or limit reached':
						echo 'Error importing - auth failed or content item limit reached.';
						break;
					default:
						echo 'Error importing';
						break;
				}
			}
		}
	}

	/**
	 * Support form emailer
	 */
	public function send_feedback() {
		check_admin_referer( 'bibblio_support', 'bibblio_support_nonce' );

		if ( isset( $_POST['full_name'] ) && isset( $_POST['company'] ) && isset( $_POST['message'] ) && isset( $_POST['email'] ) ) { // Input var okay.
			$to        = 'support@bibblio.org';
			$full_name = sanitize_text_field( wp_unslash( $_POST['full_name'] ) );  // Input var okay.
			$company   = sanitize_text_field( wp_unslash( $_POST['company'] ) );  // Input var okay.
			$message   = sanitize_text_field( wp_unslash( $_POST['message'] ) );  // Input var okay.
			$email     = sanitize_email( wp_unslash( $_POST['email'] ) );  // Input var okay.
			$headers   = array( 'Content-Type: text/html; charset=UTF-8' );

			$subject  = 'From: ' . $full_name;
			$subject .= ', site: ' . str_replace( array( 'http://', 'https://' ), '', site_url() );

			if ( ! empty( $full_name ) && ! empty( $email ) && ! empty( $message ) ) {
				$message = 'Email: ' . $email . "\r\n\r\n" . $message;
				$message = 'Company: ' . $company . "\r\n\r\n" . $message;
				$message = "-----------\r\n\r\n" . $message;
				$message = nl2br( $message );
				wp_mail( $to, $subject, $message, $headers );
			}
		}
	}

	/**
	 * Check if the plugin is logged in to Bibblio
	 *
	 * @return boolean
	 */
	public function user_logged_in() {
		return ! ( is_null( Bibblio_Related_Posts_Configs::get( 'client_id' ) ) || is_null( Bibblio_Related_Posts_Configs::get( 'client_secret' ) ) );
	}

	/**
	 * Check if the user has enabled auto-adding of new posts
	 *
	 * @return boolean
	 */
	public function has_set_auto_adding() {
		return ! is_null( Bibblio_Related_Posts_Configs::get( 'auto_add' ) );
	}

	/**
	 * Check if the user has created any modules
	 *
	 * @return boolean
	 */
	public function has_module() {
		// deliberately allowing "falsey" values to return false.
		return Bibblio_Related_Posts_Configs::get( 'modules' );
	}

	/**
	 * Check if the user has completed the setup wizard
	 *
	 * @return boolean
	 */
	public function wizard_finished() {
		// check if we have the essential data from the user.
		return ( $this->user_logged_in() && $this->has_set_auto_adding() && $this->has_module() );
	}

	/**
	 * Check if existing posts are currently being imported
	 *
	 * @return boolean
	 */
	public function importing_old_posts() {
		return ! ! wp_next_scheduled( 'bibblio_new_import_event' );
	}

	/**
	 * Hook in to the WordPress post editor
	 */
	public function add_button_editor() {
		$modules = Bibblio_Related_Posts_Configs::get( 'modules' );
		if ( ! is_array( $modules ) ) {
			$modules = [];
		}

		if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) && $this->user_logged_in() && ( count( $modules ) > 0 ) ) {
			add_filter( 'mce_external_plugins', array( $this, 'bibblio_add_buttons' ) );
			add_filter( 'mce_buttons', array( $this, 'bibblio_register_buttons' ) );
		}
	}

	/**
	 * Add the Bibblio button to TinyMCE
	 *
	 * @param  array $plugin_array Plugins.
	 * @return array
	 */
	public function bibblio_add_buttons( $plugin_array ) {
		$plugin_array['bibblio_button_script'] = plugins_url( '/', __FILE__ ) . 'js/tinymce_buttons.js';
		return $plugin_array;
	}

	/**
	 * Add the Bibblio button to TinyMCE
	 *
	 * @param  array $buttons existing buttons.
	 * @return array
	 */
	public function bibblio_register_buttons( $buttons ) {
		array_push( $buttons, 'bibblio_button' );
		array_push( $buttons, 'bibblio_append_button' );
		return $buttons;
	}

	/**
	 * Configure TinyMCE custom button
	 */
	public function bibblio_tinymce_extra_vars() {
		$modules = Bibblio_Related_Posts_Configs::get( 'modules' );
		if ( ! is_array( $modules ) ) {
			$modules = [];
		}

		$tinymce_object = array(
			'button_name'        => esc_html__( 'Add Bibblio shortcode', 'bibblio' ),
			'button_title'       => esc_html__( 'Select shortcode style', 'bibblio' ),
			'image_title'        => esc_html__( 'Image', 'bibblio' ),
			'image_button_title' => esc_html__( 'Upload image', 'bibblio' ),
			'modules'            => $modules,
		);

		echo '<script type="text/javascript">var bibblio_tinyMCE = ' . wp_json_encode( $tinymce_object ) . ';</script>';
	}

	/**
	 * Check if a cURL error is due to an SSL problem
	 *
	 * @param  string $error The cURL error message.
	 * @return boolean
	 */
	public function is_ssl_error( $error ) {
		return is_int( strpos( $error, 'SSL certificate problem' ) );
	}

	/**
	 * Bulk edit
	 *
	 * @param  string $column_name The WordPress column being rendered.
	 */
	public function bibblio_bulk_edit_import( $column_name ) {
		?>
		<div class="bibblio-import">
			<div class="inline-edit-col">
			<?php wp_nonce_field( 'bibblio_bulk_edit_fields_nonce', 'bibblio_bulk_edit_fields_nonce' ); ?>
				<label class="inline-edit-<?php echo esc_attr( $column_name ); ?>">
					<span class="title two-line">Import to Bibblio</span>
							<select class="<?php echo esc_attr( $column_name ); ?>" name="bibblio_bulk_ingest">
								<option value="-1">— No Change —</option>
								<option value="true">Yes</option>
								<option value="false">No</option>
							</select>
				</label>
			<div>
		</div>
		<?php
	}
}
