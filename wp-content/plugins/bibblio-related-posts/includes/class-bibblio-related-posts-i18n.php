<?php
/**
 * Defines the internationalization functionality
 *
 * @category   Bibblio_Related_Posts_I18n
 * @package    Bibblio_Related_Posts
 * @subpackage Bibblio_Related_Posts/includes
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

/**
 * Defines the internationalization functionality
 */
class Bibblio_Related_Posts_I18n {
	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'bibblio_Related_Posts',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
