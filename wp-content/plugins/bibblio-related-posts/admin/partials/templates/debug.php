<?php
/**
 * This file is used for debug purposes only
 *
 * @category   Debug
 * @package    Bibblio_Related_Posts
 * @subpackage Debug
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

if ( ( ! function_exists( 'is_admin' ) ) || ( ! is_admin() ) ) {
	die( 'You are not permitted to access this page.' );
}

/**
 * Checks if the user has completed the Bibblio setup wizard.
 */
function is_wizard_finished() {
	try {
		$bibblio = new Bibblio_Related_Posts_Admin( null, null );
		return ( $bibblio->wizard_finished() ) ? 'true' : 'false';
	} catch ( Exception $e ) {
		return 'ERROR';
	}
}

/**
 * Returns information about the posts the site has.
 *
 * @return array
 */
function get_post_counts() {
	try {
		$bibblio = new Bibblio_Related_Posts_Support();
		$bibblio->bibblio_init();
	} catch ( Exception $e ) {
		return 'ERROR: ' . $e->getMessage();
	}

	try {
		$published = $bibblio->count_published_posts();
	} catch ( Exception $e ) {
		$published = 'ERROR: ' . $e->getMessage();
	}

	try {
		$to_import = count( $bibblio->get_posts_to_import() );
	} catch ( Exception $e ) {
		$to_import = 'ERROR: ' . $e->getMessage();
	}

	try {
		$import_errors = count( $bibblio->get_posts_with_import_error() );
	} catch ( Exception $e ) {
		$import_errors = 'ERROR: ' . $e->getMessage();
	}

	return array(
		'PUBLISHED_POSTS'          => $published,
		'POSTS_TO_IMPORT'          => $to_import,
		'POSTS_WITH_IMPORT_ERRORS' => $import_errors,
	);
}

/**
 * Outputs the collected data
 *
 * @param  object $data Collected data to output.
 * @return string
 */
function output_data( $data ) {
	$data = wp_json_encode( $data );
	$data = base64_encode( $data ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions
	return $data;
}

/**
 * Retrieves information about installed plugins
 *
 * @return array
 */
function get_plugin_info() {
	$return = [];

	foreach ( get_plugins() as $path => $plugin ) {
		try {
			$return[] = array(
				'name'           => $plugin['Name'],
				'version'        => $plugin['Version'],
				'url'            => $plugin['PluginURI'],
				'path'           => $path,
				'active'         => is_plugin_active( $path ),
				'network_active' => is_plugin_active_for_network( $path ),
			);
		} catch ( Exception $e ) {
			$return[] = array(
				'ERROR'  => $e->getMessage(),
				'plugin' => $plugin,
			);
		}
	}

	return $return;
}

/**
 * Checks if a PHP function is disabled or not
 *
 * @param  string $function_name Name of the PHP function.
 * @return boolean
 */
function function_not_disabled( $function_name ) {
	$disabled_functions = explode( ',', ini_get( 'disable_functions' ) );
	return ( ! in_array( $function_name, $disabled_functions, true ) );
}

$data = [];

// get disabled functions.
try {
	$disabled_functions = trim( ini_get( 'disable_functions' ) );
	$disabled_functions = ( $disabled_functions ) ? explode( ',', $disabled_functions ) : $disabled_functions;
} catch ( Exception $e ) {
	$disabled_functions = 'error';
}

// get Bibblio plugin object.
try {
	$plugin = new Bibblio_Related_Posts();
} catch ( Exception $e ) {
	$plugin = 'ERROR: ' . $e->getMessage();
}


// get MySQL version.
try {
	global $wpdb;
	$mysql_ver = $wpdb->get_row( 'SELECT @@version AS version' )->version; // db call ok, cache ok.
} catch ( Exception $e ) {
	$mysql_ver = 'ERROR: ' . $e->getMessage();
}

// get Bibblio Plugin version.
try {
	$plugin_ver = $plugin->get_version();
} catch ( Exception $e ) {
	$plugin_ver = 'ERROR: ' . $e->getMessage();
}

// get operation system info.
if ( PHP_OS == 'Linux' ) { // loose comparison ok.
	try {
		if ( function_not_disabled( 'exec' ) ) {
			$temp = exec( 'cat /etc/*elease*', $linux_distro ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions
		} else {
			$linux_distro = 'ERROR: exec() is disabled';
		}
	} catch ( Exception $e ) {
		$linux_distro = 'ERROR: ' . $e->getMessage();
	}
} else {
	$linux_distro = 'false';
}

if ( function_exists( 'apache_get_version' ) && function_not_disabled( 'apache_get_version' ) ) {
	$apache_version = apache_get_version();
} else {
	$apache_version = 'false';
}

// system info.
$data['APACHE_VERSION'] = $apache_version;
$data['PHP_VERSION']    = ( function_not_disabled( 'phpversion' ) ) ? phpversion() : 'ERROR: phpversion() is disabled!';
$data['MYSQL_VERSION']  = $mysql_ver;
$data['WP_VERSION']     = get_bloginfo( 'version' );
$data['PLUGIN_VERSION'] = $plugin_ver;
$data['OS_NAME']        = PHP_OS;
$data['OS_INFO']        = ( function_not_disabled( 'php_uname' ) ) ? php_uname() : 'ERROR: php_uname() is disabled!';
$data['CURRENT_USER']   = ( function_not_disabled( 'get_current_user' ) ) ? get_current_user() : 'ERROR: get_current_user() is disabled!';
$data['LINUX_DISTRO']   = $linux_distro;
$data['PHP_SAPI']       = ( PHP_SAPI ) ? PHP_SAPI : 'false';

// php settings.
$data['PHP_SETTINGS'] = array(
	'MAX_EXECUTION_TIME' => ini_get( 'max_execution_time' ),
	'MEMORY_LIMIT'       => ini_get( 'memory_limit' ),
	'DISPLAY_ERRORS'     => ini_get( 'display_errors' ),
	'POST_MAX_SIZE'      => ini_get( 'post_max_size' ),
	'DISABLED_FUNCTIONS' => $disabled_functions,
);

// WordPress info.
$data['WP_SETTINGS']['WP_CONTENT_DIR']      = WP_CONTENT_DIR;
$data['WP_SETTINGS']['WP_PLUGIN_DIR']       = WP_PLUGIN_DIR;
$data['WP_SETTINGS']['WP_PLUGIN_URL']       = WP_PLUGIN_URL;
$data['WP_SETTINGS']['WP_MULTISITE']        = is_multisite();
$data['WP_SETTINGS']['WPMU_PLUGIN_DIR']     = WPMU_PLUGIN_DIR;
$data['WP_SETTINGS']['WPMU_PLUGIN_URL']     = WPMU_PLUGIN_URL;
$data['WP_SETTINGS']['WP_DEBUG']            = WP_DEBUG;
$data['WP_SETTINGS']['WP_DEBUG_LOG']        = WP_DEBUG_LOG;
$data['WP_SETTINGS']['SCRIPT_DEBUG']        = SCRIPT_DEBUG;
$data['WP_SETTINGS']['WP_MEMORY_LIMIT']     = WP_MEMORY_LIMIT;
$data['WP_SETTINGS']['WP_MAX_MEMORY_LIMIT'] = WP_MAX_MEMORY_LIMIT;

$data['WP_SETTINGS']['CUSTOM_POST_TYPES'] = array();
$post_types                               = get_post_types( array( 'public' => true ) );
foreach ( $post_types as $p_type ) {
	$count                                      = (int) wp_count_posts( $p_type )->publish;
	$data['WP_SETTINGS']['CUSTOM_POST_TYPES'][] = array(
		'name'  => $p_type,
		'count' => $count,
	);
}
$data['WP_SETTINGS']['PLUGINS'] = get_plugin_info();

if ( $data['WP_SETTINGS']['WP_MULTISITE'] ) {
	$data['MULTISITE']                           = [];
	$data['MULTISITE']['main_network_id']        = get_main_network_id();
	$data['MULTISITE']['current_network_id']     = get_current_network_id();
	$data['MULTISITE']['network']                = get_network();
	$data['MULTISITE']['network_active_plugins'] = wp_get_active_network_plugins();
}

global $wp_registered_sidebars;
$data['WP_SETTINGS']['SIDEBARS']             = $wp_registered_sidebars;
$data['WP_SETTINGS']['WIDGETS']              = get_option( 'sidebars_widgets', array() );
$data['WP_SETTINGS']['CRONJOBS']             = ( defined( 'DISABLE_WP_CRON' ) && constant( 'DISABLE_WP_CRON' ) ) ? 'disabled' : 'enabled';
$data['WP_SETTINGS']['WP_CRON_LOCK_TIMEOUT'] = ( defined( 'WP_CRON_LOCK_TIMEOUT' ) && constant( 'WP_CRON_LOCK_TIMEOUT' ) ) ? WP_CRON_LOCK_TIMEOUT : 60;

// WordPress options.
$options                                  = wp_load_alloptions();
$data['WP_OPTIONS']['siteurl']            = $options['siteurl'];
$data['WP_OPTIONS']['home']               = $options['home'];
$data['WP_OPTIONS']['active_plugins']     = $options['active_plugins'];
$data['WP_OPTIONS']['mailserver_url']     = $options['mailserver_url'];
$data['WP_OPTIONS']['template']           = $options['template'];
$data['WP_OPTIONS']['stylesheet']         = $options['stylesheet'];
$data['WP_OPTIONS']['db_version']         = $options['db_version'];
$data['WP_OPTIONS']['initial_db_version'] = $options['initial_db_version'];
$data['WP_OPTIONS']['timezone_string']    = $options['timezone_string'];
$data['WP_OPTIONS']['cron']               = $options['cron'];
$data['WP_OPTIONS']['widget_bibblio_related_posts'] = $options['widget_bibblio_related_posts'];
// collect all option keys and values mentioning Bibblio.
foreach ( $options as $key => $value ) {
	if ( is_int( strpos( $key, 'bibblio_' ) ) ) {
		$data['WP_OPTIONS'][ $key ] = $options[ $key ];
	}

	if ( is_int( strpos( $value, 'bibblio' ) ) ) {
		$data['WP_OPTIONS'][ $key ] = $options[ $key ];
	}
}

$data['POST_COUNTS']                             = get_post_counts();
$data['WP_OPTIONS']['bibblio_wizard_finished']   = is_wizard_finished();
$data['WP_OPTIONS']['bibblio_importer_next_run'] = wp_next_scheduled( 'bibblio_new_import_event' );
$data['WP_OPTIONS']['bibblio_current_timestamp'] = ( function_not_disabled( 'time' ) ) ? time() : 'ERROR: time() is disabled!';


// get wp_postmeta values relating to Bibblio (this might be slow! consider changing the "LIKE" to "_bibblio%").
try {
	global $wpdb;
	$rows = $wpdb->get_results( 'SELECT `meta_key`, COUNT(`meta_value`) AS `count` FROM `' . $wpdb->prefix . 'postmeta` WHERE `meta_key` LIKE "%bibblio%" GROUP BY `meta_key`;' ); // db call ok, cache ok.
	foreach ( $rows as $row ) {
		$data['WP_POSTMETA'] = array(
			'name'  => $row->meta_key,
			'count' => $row->count,
		);
	}
} catch ( Exception $e ) {
	$data['WP_POSTMETA'] = [];
}
?>

<script>
function copyToClipboard( element ) {
	var temp = jQuery("<input>");
	jQuery("body").append(temp);
	temp.val(jQuery(element).text()).select();
	if (!document.execCommand("copy") ) {
		alert( 'Copy failed, please manually select and copy all of the above data.')
	}
	temp.remove();
}
</script>

<div class="tab tab_modules">
	<div class="module module_create">
		<h3 class="module_title">GENERAL DATA:</h3>
		<div class="module_content">
			<textarea id="general" style="width: 75%;" rows="15"><?php echo 'BIB_GD=' . esc_textarea( output_data( $data ) ); ?></textarea>
			<br /><a href="javascript:copyToClipboard( '#general')">Copy</a>
		</div>
	</div>
</div>
