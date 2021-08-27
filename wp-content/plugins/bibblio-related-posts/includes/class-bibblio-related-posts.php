<?php
/**
 * The file that defines the core plugin class
 *
 * @package    Bibblio_Related_Posts
 * @subpackage Bibblio_Related_Posts/includes
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

/**
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 */
class Bibblio_Related_Posts {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @access protected
	 * @var    Bibblio_Related_Posts_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @access protected
	 * @var    string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @access protected
	 * @var    string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The admin plugin path with $_GET['page'] parameters.
	 *
	 * @access public
	 * @var    string    $admin_path    The current path of the plugin admin page.
	 */
	public static $admin_path;

	/**
	 * Unique identifier for retrieving translated strings.
	 *
	 * @access private
	 * @var    string    $text_domain    Text-domain of the plugin.
	 */

	private $text_domain = 'bibblio-related-posts';

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 */
	public function __construct() {

		$this->plugin_name  = 'bibblio_related_posts';
		$this->version      = '1.3.7';
		static::$admin_path = admin_url( 'admin.php?page=' . $this->plugin_name );

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_widget_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Bibblio_Related_Posts_Loader. Orchestrates the hooks of the plugin.
	 * - Bibblio_Related_Posts_i18n. Defines internationalization functionality.
	 * - Bibblio_Related_Posts_Admin. Defines all hooks for the admin area.
	 * - Bibblio_Related_Posts_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @access private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bibblio-related-posts-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bibblio-related-posts-i18n.php';

		/**
		 * The class responsible for defining all default plugin settings
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bibblio-related-posts-configs.php';

		/**
		 * The class-wrapper responsible for core functionality by Bibblio API.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/class-bibblio-api-client.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-bibblio-related-posts-admin.php';

		/**
		 * The file responsible for (admin) ajax functionality
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/ajax.php';

		/**
		 * The class responsible for importing process manipulation
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bibblio-related-posts-support.php';

		/**
		 * The class responsible for output content of the plugin in the sidebar
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bibblio-related-posts-widget.php';

		/**
		 * The class responsible for rendering the Related Content Module
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bibblio-related-content-module.php';

		/**
		 * The class responsible for Bibblio processing Exception
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bibblioexception.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-bibblio-related-posts-public.php';

		$this->loader = new Bibblio_Related_Posts_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Bibblio_Related_Posts_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @access private
	 */
	private function set_locale() {
		$plugin_i18n = new Bibblio_Related_Posts_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @access private
	 */
	private function define_admin_hooks() {

		$plugin_admin   = new Bibblio_Related_Posts_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_support = new Bibblio_Related_Posts_Support();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Hooks into admin_menu hook to add custom page.
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_page' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'post_add_meta_box' );
		$this->loader->add_action( 'manage_posts_columns', $plugin_admin, 'add_custom_column' );
		$this->loader->add_action( 'manage_posts_custom_column', $plugin_admin, 'return_post_status' );
		$this->loader->add_action( 'save', $plugin_support, 'bibblio_handle_post_save' );
		$this->loader->add_action( 'publish_future_post', $plugin_support, 'bibblio_handle_post_save' );
		$this->loader->add_action( 'edit_post', $plugin_support, 'bibblio_handle_post_save' );
		$this->loader->add_action( 'before_delete_post', $plugin_support, 'bibblio_delete_post' );
		$this->loader->add_action( 'trashed_post', $plugin_support, 'bibblio_delete_post' );
		$this->loader->add_action( 'wp_ajax_send_user_feedback', $plugin_admin, 'send_feedback' );
		$this->loader->add_action( 'wp_ajax_nopriv_send_user_feedback', $plugin_admin, 'send_feedback' );
		$this->loader->add_action( 'wp_ajax_nopriv_plan_name', $plugin_admin, 'return_plan_name' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'add_button_editor' );
		$this->loader->add_action( 'after_wp_tiny_mce', $plugin_admin, 'bibblio_tinymce_extra_vars' );

		// Hooks into the bulk edit menu to add bibblio import option.
		$this->loader->add_action( 'bulk_edit_custom_box', $plugin_admin, 'bibblio_bulk_edit_import', 10, 2 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @access private
	 */
	private function define_public_hooks() {
		$plugin_public  = new Bibblio_Related_Posts_Public( $this->get_plugin_name(), $this->get_version() );
		$plugin_support = new Bibblio_Related_Posts_Support();

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );
		$this->loader->add_action( 'cron_schedules', $plugin_support, 'add_custom_cron_time' );
		$this->loader->add_action( 'plugins_loaded', $plugin_support, 'bibblio_new_cron_event' );
		$this->loader->add_action( 'bibblio_new_import_event', $plugin_support, 'import_posts' );

		$this->loader->add_filter( 'the_content', $plugin_public, 'append_rcm_below_post' );
	}

	/**
	 * Register all of the hooks shared between public-facing and admin functionality
	 * of the plugin.
	 *
	 * @access private
	 */
	private function define_widget_hooks() {
		$this->loader->add_action( 'widgets_init', $this, 'widgets_init' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return Bibblio_Related_Posts_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Registers widgets with WordPress
	 *
	 * @access public
	 */
	public function widgets_init() {
		register_widget( 'Bibblio_Related_Posts_Widget' );
	}
}
