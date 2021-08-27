<?php
/**
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @category   Bibblio_Related_Posts_Public
 * @package    Bibblio_Related_Posts
 * @subpackage Bibblio_Related_Posts/includes
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

/**
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 */
class Bibblio_Related_Posts_Public {
	/**
	 * The ID of this plugin.
	 *
	 * @access private
	 * @var    string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @access private
	 * @var    string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bibblio_Related_Posts_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bibblio_Related_Posts_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bibblio_related_posts-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'bibblio-rcm-css', '//cdn.bibblio.org/rcm/4.6/bib-related-content.css', array(), false, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bibblio_Related_Posts_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bibblio_Related_Posts_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bibblio_related_posts-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'bibblio-rcm-js', '//cdn.bibblio.org/rcm/4.6/bib-related-content.js', array(), false, true );
	}

	/**
	 * Registers the Bibblio shortcode
	 */
	public function register_shortcodes() {
		add_shortcode( 'bibblio', array( $this, 'bibblio_shortcode' ) );
	}

	/**
	 * Processes shortcode bibblio
	 *
	 * @param array $atts The attributes from the shortcode.
	 *
	 * @return mixed $output Output of the buffer
	 */
	public function bibblio_shortcode( $atts ) {
		if ( is_single() && ( get_post_meta( get_the_ID(), 'bibblio_content_item_id', true ) ) !== false ) {
			$id_key = get_post_meta( get_the_ID(), 'bibblio_content_item_id', true );
			$args   = shortcode_atts(
				array(
					'style'               => '',
					'query_string_params' => '{}',
					'recommendation_type' => '',
				),
				$atts
			);

			$content = null;

			// query string params are stored in base64 format to avoid breaking shortcode syntax.
			$query_string_params = $args['query_string_params'] ? base64_decode( $args['query_string_params'] ) : '{}'; // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions
			$recommendation_type = ( isset( $args['recommendation_type'] ) && ( 'related' === $args['recommendation_type'] ) ) ? 'related' : 'optimised'; // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions

			$options = array(
				'recommendationKey'   => Bibblio_Related_Content_Module::get_recommendation_key(),
				'recommendationType'  => trim( $recommendation_type ),
				'contentItemId'       => $id_key,
				'classes'             => trim( $args['style'] ),
				'queryStringParams'   => $query_string_params,
				'hideUntilRecsLoaded' => true,
			);

			return '<div class="widget_bibblio_recent_posts">' . Bibblio_Related_Content_Module::get_module_html( $options ) . '</div>';
		}
	}

	/**
	 * Returns the HTML and (JS) for rendering a given RCM module's name
	 *
	 * @param string $name The name of the module to be displayed.
	 *
	 * @return string
	 */
	public function get_rcm( $name ) {
		if ( $name && is_single() && ( get_post_meta( get_the_ID(), 'bibblio_content_item_id', true ) ) ) {
			$rcm     = array();
			$modules = Bibblio_Related_Posts_Configs::get( 'modules' );
			foreach ( $modules as $module ) {
				if ( $name === $module['name'] ) {
					$rcm = $module;
				}
			}

			if ( $rcm && isset( $rcm['classes'] ) ) {
				$id_key = get_post_meta( get_the_ID(), 'bibblio_content_item_id', true );

				$options = array(
					'recommendationKey'   => Bibblio_Related_Content_Module::get_recommendation_key(),
					'recommendationType'  => $rcm['recommendationType'],
					'contentItemId'       => $id_key,
					'classes'             => $rcm['classes'],
					'queryStringParams'   => $rcm['queryParams'],
					'hideUntilRecsLoaded' => true,
				);

				return Bibblio_Related_Content_Module::get_module_html( $options );
			}

			return '';
		}
	}

	/**
	 * Checks if auto appended RCM has been given a heading
	 *
	 * @return string
	 */
	public function check_auto_append_rcm_heading() {
		if ( is_single() && Bibblio_Related_Posts_Configs::get( 'auto_appended_module_header' ) ) {
			return '<h3>' . Bibblio_Related_Posts_Configs::get( 'auto_appended_module_header' ) . '</h3>';
		} else {
			return '';
		}
	}

	/**
	 * Adds a chosen module to the bottom of posts
	 *
	 * @param string $content The WordPress post body.
	 *
	 * @return string
	 */
	public function append_rcm_below_post( $content ) {
		$rcm = '';

		if ( Bibblio_Related_Posts_Configs::get( 'appended_rcm' ) ) {
			// check the content item has been ingested (also ensures only select custom post types show the recommendations).
			$content_item_id = get_post_meta( get_the_ID(), 'bibblio_content_item_id', true );
			if ( $content_item_id ) {
				$appended_rcm                = Bibblio_Related_Posts_Configs::get( 'appended_rcm' );
				$auto_appended_module_header = $this->check_auto_append_rcm_heading();
				$rcm                         = $this->get_rcm( $appended_rcm );
			}
		}

		if ( $rcm ) {
			return $content . '<div class="widget_bibblio_recent_posts">' . $auto_appended_module_header . $rcm . '</div>';
		} else {
			return $content;
		}

	}

	/**
	 * Renders a Related Content Module of the given name
	 *
	 * @param string $name The Bibblio module name to be rendered.
	 */
	public static function render_module( $name ) {
		$plugin_public = new Bibblio_Related_Posts_Public( '', '' );
		echo esc_attr( $plugin_public->get_rcm( $name ) );
	}
}
