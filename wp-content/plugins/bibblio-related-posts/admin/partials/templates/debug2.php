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
 * Network debug page
 *
 * @package Bibblio_Debug
 * @author  Bibblio <dev@bibblio.org>
 * @license GPL-2.0+
 */

/**
 * Measures how long it takes to make a HTTP GET request to a given url.
 *
 * @param  string $url URL to access.
 * @return array
 */
function check_connectivity( $url ) {
	$start = microtime( true );

	$args['timeout'] = 30;
	try {
		$response = wp_remote_get( $url, $args );
		$end      = microtime( true );

		if ( is_array( $response ) ) {
			$return['STATUS'] = $response['response']['code'];
		} else {
			$return['STATUS'] = $response->get_error_message();
		}

		$return['SECONDS'] = $end - $start;
		return $return;

	} catch ( Exception $e ) {
		$end                 = microtime( true );
		$return['EXCEPTION'] = $e;
		$return['SECONDS']   = $end - $start;
		return $return;
	}
}

/**
 * Checks if firewall application is installed.
 *
 * @param  string $binary Executable file path.
 * @return boolean|string
 */
function check_for_firewall( $binary ) {
	try {
		if ( function_not_disabled( 'exec' ) ) {
			$result = exec( 'which ' . escapeshellarg( $binary ) );  // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions
		} else {
			$result = 'ERROR: exec() is disabled!';
		}
	} catch ( Exception $e ) {
		$result = 'ERROR: ' . $e->getMessage();
	}

	$result = ( trim( $result ) ) ? $result : 'not found';
	return $result;
}

if ( ! function_exists( 'output_data' ) ) {
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
}

if ( ! function_exists( 'function_not_disabled' ) ) {
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
}

/**
 * Retrieves DNS information for a domain
 *
 * @param  string $domain Domain name to resolve.
 * @return string
 */
function get_dns( $domain ) {
	try {
		$result = dns_get_record( $domain );
	} catch ( Exception $e ) {
		$result = false;
	}

	if ( ! $result ) {
		$result = 'ERROR - unable to get DNS for ' . $domain;
	}

	return $result;
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
		<h3 class="module_title">NETWORKING DATA:</h3>
		<div class="module_content">
			<textarea id="networking" style="width: 75%;" rows="15">Loading...
<?php
if ( function_not_disabled( 'flush' ) ) {
	flush();
}

if ( function_not_disabled( 'ob_get_contents' ) && function_not_disabled( 'ob_flush' ) ) {
	if ( ob_get_contents() ) {
		ob_flush();
	}
}

if ( function_not_disabled( 'set_time_limit' ) ) {
	set_time_limit( 0 );
}

// Check URL connectivity.
$sites                              = [];
$sites['http://api.wordpress.org']  = check_connectivity( 'http://api.wordpress.org/' );  // https://github.com/WordPress/WordPress/blob/df0958697a5d897a15ea78d37a001e2f3752875f/wp-admin/includes/credits.php#L29 .
$sites['http://www.example.com']    = check_connectivity( 'http://www.example.com/' );
$sites['https://www.youtube.com']   = check_connectivity( 'https://www.youtube.com/' );
$sites['https://www.facebook.com']  = check_connectivity( 'https://www.facebook.com/' );
$sites['http://amazon.com']         = check_connectivity( 'http://amazon.com/' );
$sites['http://api.bibblio.org']    = check_connectivity( 'http://api.bibblio.org/' );
$sites['https://api.bibblio.org']   = check_connectivity( 'https://api.bibblio.org/' );
$sites['https://www.wikipedia.org'] = check_connectivity( 'https://www.wikipedia.org/' );

$firewalls = [];
if ( in_array( PHP_OS, [ 'Darwin', 'FreeBSD', 'Linux', 'NetBSD', 'OpenBSD', 'SunOS', 'Unix' ], true ) ) {
		$firewalls['UNCOMPLICATED_FIREWALL'] = check_for_firewall( 'ufw' );
		$firewalls['IPFIREWALL']             = check_for_firewall( 'ipfw' );
		$firewalls['IPTABLES']               = check_for_firewall( 'iptables' );
}

$data                        = [];
$data['FIREWALLS']           = $firewalls;
$data['SITES']               = $sites;
$data['BIBBLIO_IP']          = gethostbyname( 'api.bibblio.org' );
$data['BIBBLIO_DNS_RECORDS'] = get_dns( 'api.bibblio.org' );

echo 'BIB_ND=' . esc_textarea( output_data( $data ) );
?>
</textarea>
			<br /><a href="javascript:copyToClipboard( '#networking')">Copy</a>
		</div>
	</div>
</div>

<script>jQuery( '#networking').text(jQuery( '#networking').text().replace( 'Loading...\n', '') );</script>
