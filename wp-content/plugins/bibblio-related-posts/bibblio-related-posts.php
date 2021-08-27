<?php
/**
 * The plugin bootstrap file
 *
 * @package Bibblio_Related_Posts
 * @author  Bibblio <dev@bibblio.org>
 * @license GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name:       Related Posts by Bibblio
 * Plugin URI:        https://www.bibblio.org/
 * Description:       Quickly add content recommendations to your WordPress posts.
 * Version:           1.3.7
 * Author:            Bibblio
 * Author URI:        https://www.bibblio.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bibblio-related-posts-activator.php
 */
function activate_bibblio_related_posts() {
	include_once plugin_dir_path( __FILE__ ) . 'includes/class-bibblio-related-posts-activator.php';
	Bibblio_Related_Posts_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bibblio-related-posts-disconnector.php
 */
function deactivate_bibblio_related_posts() {
	include_once plugin_dir_path( __FILE__ ) . 'includes/class-bibblio-related-posts-disconnector.php';
	Bibblio_Related_Posts_Disconnector::disconnect();
}

register_activation_hook( __FILE__, 'activate_bibblio_related_posts' );
register_deactivation_hook( __FILE__, 'deactivate_bibblio_related_posts' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bibblio-related-posts.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
function run_bibblio_related_posts() {
	$plugin = new Bibblio_Related_Posts();
	$plugin->run();
}

run_bibblio_related_posts();
