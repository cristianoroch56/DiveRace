<?php
/**
 * Fired during plugin deactivation or "disconnect"
 *
 * @category   Bibblio_Related_Posts_Disconnector
 * @package    Bibblio_Related_Posts
 * @subpackage Bibblio_Related_Posts/includes
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

/**
 * This class defines all code necessary to run during the plugin's deactivation
 *
 * @package    Bibblio_Related_Posts
 * @subpackage Bibblio_Related_Posts/includes
 */
class Bibblio_Related_Posts_Disconnector {

	/**
	 * Fired during deactivation
	 */
	public static function disconnect() {
		try {
			$bibblio = new Bibblio_Related_Posts_Support();
			$bibblio->bibblio_init();

			// if the user has not entered (valid) credentials, we will not have an api object.
			if ( $bibblio->api_object ) {
				if ( $bibblio->get_content_item_count( true ) > 0 ) {
					$bibblio->delete_all_content();
				}
			}
		} catch ( Exception $e ) {
			// do nothing (except ensure this function cause an error).
			unset( $e );
		}

		try {
			$options = [
				// older versions of the plugin.
				'accountPlan',
				'autoAdd',
				'clientID',
				'clientSecret',
				'contentItemCount',
				'recommendationKey',
				'recommendationUsage',
				'catalogueId',
				// current version's options.
				'account_plan',
				'auto_add',
				'appended_rcm',
				'auto_appended_module_header',
				'add_previous',
				'catalogue_id',
				'client_id',
				'client_secret',
				'content_item_count',
				'modules',
				'plan_name',
				'recommendation_key',
				'recommendation_usage',
				'recency_preference',
				'token',
				'token_expires_at',
				'last_error',
				'selected_post_types',
				'importer_id',
				'importer_last_processed',
				'bibblio_update_fixes',
			];

			Bibblio_Related_Posts_Configs::delete( $options );

			// older versions of the plugin.
			delete_post_meta_by_key( '_bibblio_ingestion_lock' );
			delete_post_meta_by_key( 'contentItemId' );

			// current version's metadata.
			delete_post_meta_by_key( 'bibblio_content_item_id' );
			delete_post_meta_by_key( '_bibblio_ingestion_error' );
			delete_post_meta_by_key( '_bibblio_ingestion_error_type' );

		} catch ( Exception $e ) {
			// do nothing (except ensure this function cause an error).
			unset( $e );
		}

		// remove any pending cronjobs.
		wp_clear_scheduled_hook( 'bibblio_new_import_event' );
	}
}
