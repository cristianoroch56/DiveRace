<?php
/**
 * A minimum debugger to check component versions
 *
 * @package    Bibblio_Related_Posts
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

$required_php_ver = '5.6.0';
$required_wp_ver  = '4.0.0';

$debug_errors = 0;

// PHP VERSION.
$php_version = phpversion();

if ( version_compare( PHP_VERSION, $required_php_ver, '<' ) ) {
	$debug_errors++;
	echo 'Your PHP version is not supported.<br /><br />';
}

// WordPress VERSION.
if ( file_exists( '../../../wp-includes/version.php' ) ) {
	include '../../../wp-includes/version.php';
	if ( version_compare( $wp_version, $required_wp_ver, '<' ) ) {
		$debug_errors++;
		echo 'WordPress version is not supported.<br /><br />';
	}
} else {
	$debug_errors++;
	echo 'Your WordPress version could not be detected - it might not be supported.<br /><br />';
}

if ( 0 === $debug_errors ) {
	echo 'Your PHP, WordPress and cURL versions all appear to be supported.';
}
