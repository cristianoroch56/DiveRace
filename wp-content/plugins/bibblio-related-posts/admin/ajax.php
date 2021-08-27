<?php
/**
 * This file handles the incoming ajax actions
 *
 * @category   Bibblio_Related_Posts_Ajax
 * @package    Bibblio_Related_Posts
 * @subpackage Bibblio_Related_Posts/admin
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

/**
 * Called from the Admin "Overview" tab and updates account plan and usages
 */
function bibblio_update_storage_overview() {
	try {
		$force_refresh = true;
		require plugin_dir_path( __FILE__ ) . 'partials/templates/parts/overview-calculations.php';

		// check for auth errors and show the relevant message.
		if ( in_array( 'Error 403', $bibblio->error, true ) ) {
			echo 'Error 403';
		} elseif ( ! isset( $storage_percent ) || ! isset( $storage_colour ) ) {
			echo 'Error - unknown';
		} else {
			require plugin_dir_path( __FILE__ ) . 'partials/templates/parts/overview-storage.php';
		}
	} catch ( Exception $e ) {
		wp_die();
	}
	wp_die();
}

/**
 * Called from the Admin "Overview" tab and updates recommendation usage counts
 */
function bibblio_update_recs_overview() {
	try {
		$force_refresh = true;
		require plugin_dir_path( __FILE__ ) . 'partials/templates/parts/overview-calculations.php';

		// check for auth errors and show the relevant message.
		if ( in_array( 'Error 403', $bibblio->error, true ) ) {
			echo 'Error 403';
		} elseif ( ! is_array( $get_monthly_usage ) ) {
			echo 'Error - unknown';
		} else {
			require plugin_dir_path( __FILE__ ) . 'partials/templates/parts/overview-recommendations.php';
		}
	} catch ( Exception $e ) {
		wp_die();
	}
	wp_die();
}

/**
 * Sanitize recommendation module input parameters (sanitizing and eliminating fields)
 *
 * @param array $module Recommendation module's fields.
 * @return array
 */
function sanitize_module( $module ) {
	if ( isset( $module['name'] ) && isset( $module['classes'] ) ) {
		return array(
			'name'               => sanitize_text_field( $module['name'] ),
			'classes'            => esc_attr( $module['classes'] ),
			'queryParams'        => wp_unslash( $module['queryParams'] ),
			'recommendationType' => wp_unslash( $module['recommendationType'] ),
		);
	} else {
		return array(
			'name'        => 'Unnamed',
			'classes'     => 'bib--row-3 bib--default bib--hover bib--white-label',
			'queryParams' => '',
		);
	}
}

/**
 * Called during the setup wizard for the initial creation of the Bibblio Related Content Module design
 */
function bibblio_create_module() {
	check_admin_referer( 'bibblio_create_module', 'bibblio_create_module_nonce' );

	if ( isset( $_POST['modules'] ) && is_array( $_POST['modules'] ) ) { // Input var okay.
		$modules = array_map( 'sanitize_module', wp_unslash( $_POST['modules'] ) ); // Input var okay.
	} else {
		$modules = [];
	}

	$admin = new Bibblio_Related_Posts_Admin( null, null );
	if ( $admin->save_module_settings( $modules ) ) {

		if ( isset( $_POST['appended_rcm'] ) ) { // Input var okay.
			$appended_rcm = sanitize_text_field( wp_unslash( $_POST['appended_rcm'] ) ); // Input var okay.
			Bibblio_Related_Posts_Configs::set( 'appended_rcm', $appended_rcm );
		}

		echo 'true';
	} else {
		echo 'false';
	}

	wp_die();
}

/**
 * Called from the Admin "Modules" tab and updates defined Bibblio Related Content Modules
 */
function bibblio_update_modules() {
	check_admin_referer( 'bibblio_update_modules', 'bibblio_update_module_nonce' );

	if ( isset( $_POST['modules'] ) && is_array( $_POST['modules'] ) ) { // Input var okay.
		$modules = array_map( 'sanitize_module', wp_unslash( $_POST['modules'] ) ); // Input var okay.
	} else {
		$modules = [];
	}

	$admin = new Bibblio_Related_Posts_Admin( null, null );
	if ( $admin->save_module_settings( $modules ) ) {

		// handle updating the stored "appended RCM" name when module is saved.
		$update_appended_rcm_if = ( isset( $_POST['update_appended_rcm_if'] ) ) ? sanitize_text_field( wp_unslash( $_POST['update_appended_rcm_if'] ) ) : false; // Input var okay.
		$update_appended_rcm_to = ( isset( $_POST['update_appended_rcm_to'] ) ) ? sanitize_text_field( wp_unslash( $_POST['update_appended_rcm_to'] ) ) : false; // Input var okay.
		if ( $update_appended_rcm_if && $update_appended_rcm_to ) {
			if ( Bibblio_Related_Posts_Configs::get( 'appended_rcm' ) === $update_appended_rcm_if ) {
				Bibblio_Related_Posts_Configs::set( 'appended_rcm', $update_appended_rcm_to );
			}
		}

		// handle clearing the stored "appened RCM" when the module is deleted.
		$remove_appended_rcm_if = ( isset( $_POST['remove_appended_rcm_if'] ) ) ? sanitize_text_field( wp_unslash( $_POST['remove_appended_rcm_if'] ) ) : false; // Input var okay.
		if ( $remove_appended_rcm_if && ( Bibblio_Related_Posts_Configs::get( 'appended_rcm' ) === $remove_appended_rcm_if ) ) {
			Bibblio_Related_Posts_Configs::delete( 'appended_rcm' );
		}

		echo 'true';
	} else {
		echo 'false';
	}

	wp_die();
}

/**
 * Called from the Admin "Modules" tab and appends Bibblio Related Content Modules to posts
 */
function bibblio_append_module() {
	check_admin_referer( 'bibblio_append_module', 'bibblio_append_module_nonce' );

	$appended_rcm  = ( isset( $_POST['appended_rcm'] ) ) ? sanitize_text_field( wp_unslash( $_POST['appended_rcm'] ) ) : false; // Input var okay.
	$module_header = ( isset( $_POST['module_header'] ) ) ? sanitize_text_field( wp_unslash( $_POST['module_header'] ) ) : false; // Input var okay.

	if ( $appended_rcm ) {
		Bibblio_Related_Posts_Configs::set( 'appended_rcm', $appended_rcm );
		Bibblio_Related_Posts_Configs::set( 'auto_appended_module_header', $module_header );
	} else {
		Bibblio_Related_Posts_Configs::delete( 'appended_rcm' );
		Bibblio_Related_Posts_Configs::delete( 'auto_appended_module_header' );
	}

	echo 'true';
	wp_die();
}

/**
 * Fetches first rcm created in wizard
 */
function bibblio_append_first_module() {

	check_admin_referer( 'bibblio_append_first_rcm', 'bibblio_append_first_rcm_nonce' );

	$module        = Bibblio_Related_Posts_Configs::get( 'modules' );
	$module_header = ( isset( $_POST['module_header'] ) ) ? sanitize_text_field( wp_unslash( $_POST['module_header'] ) ) : false; // Input var okay.
	$append_module = $module[0][ name ];

	Bibblio_Related_Posts_Configs::set( 'appended_rcm', $append_module );
	Bibblio_Related_Posts_Configs::set( 'auto_appended_module_header', $module_header );

	echo 'true';
	wp_die();
}

/**
 * Sets recency value for recommendations in wizard
 */
function bibblio_set_recency() {
	check_admin_referer( 'bibblio_recency_slider', 'bibblio_recency_slider_nonce' );

	if ( isset( $_POST['recency_value'] ) && ( '' !== $_POST['recency_value'] ) ) {
		$recency_value = wp_unslash( sanitize_key( $_POST['recency_value'] ) ); // Input var ok.

		$preferences = array( 'recencyBoost' => (int) $recency_value );

		Bibblio_Related_Posts_Configs::set( 'recency_preference', $recency_value );
		$bibblio_support = new Bibblio_Related_Posts_Support();
		$bibblio_support->bibblio_init();
		$bibblio_support->update_account_preference_to_bibblio( $preferences );
	}

	echo 'true';
	wp_die();
}

/**
 * Gets recency value for recommendations in wizard
 */
function bibblio_get_recency() {
	check_admin_referer( 'bibblio_get_recency', 'bibblio_get_recency_nonce' );

	$bibblio_support = new Bibblio_Related_Posts_Support();
	$bibblio_support->bibblio_init();
	echo (int) $bibblio_support->get_recency_value();
	wp_die();
}

add_action( 'wp_ajax_bibblio_storage_overview', 'bibblio_update_storage_overview' );
add_action( 'wp_ajax_bibblio_recs_overview', 'bibblio_update_recs_overview' );
add_action( 'wp_ajax_bibblio_create_module', 'bibblio_create_module' );
add_action( 'wp_ajax_bibblio_update_modules', 'bibblio_update_modules' );
add_action( 'wp_ajax_bibblio_append_module', 'bibblio_append_module' );
add_action( 'wp_ajax_bibblio_append_first_module', 'bibblio_append_first_module' );
add_action( 'wp_ajax_bibblio_set_recency', 'bibblio_set_recency' );
add_action( 'wp_ajax_bibblio_get_recency', 'bibblio_get_recency' );
