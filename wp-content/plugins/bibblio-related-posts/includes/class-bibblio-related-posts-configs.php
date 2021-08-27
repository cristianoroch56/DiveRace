<?php
/**
 * Works as a layer for plugin configuration (options)
 *
 * @category   Bibblio_Related_Posts_Configs
 * @package    Bibblio_Related_Posts
 * @subpackage Bibblio_Related_Posts/includes
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

/**
 * Works as a layer for plugin configuration (options)
 */
class Bibblio_Related_Posts_Configs {
	/**
	 * Prefixes an option name with a string to namespace it
	 *
	 * @param  string $key The option name to be namespaced.
	 * @return string      The namespaced option name
	 */
	public static function namespace_option_name( $key ) {
		if ( $key ) {
			return 'bibblio_' . $key;
		} else {
			return $key;
		}
	}

	/**
	 * Returns the value(s) of one or more given options
	 *
	 * @param  string $key The option name(s) to be retrieved.
	 * @return array|mixed       The value(s) of the retrived option(s)
	 */
	public static function get( $key ) {
		if ( is_array( $key ) ) {
			$return = [];
			foreach ( $key as $k ) {
				$return[ $k ] = self::get( $k );
			}
			return $return;

		} else {
			$key = self::namespace_option_name( $key );
			return get_option( $key, null );
		}
	}

	/**
	 * Returns the value(s) of one or more given options, bypassing caches
	 *
	 * @param  string $key The option name(s) to be retrieved.
	 * @return array|mixed The value(s) of the retrived option(s)
	 */
	public static function get_uncached( $key ) {
		if ( is_array( $key ) ) {
			$return = [];
			foreach ( $key as $k ) {
				$return[ $k ] = self::get_uncached( $k );
			}
			return $return;

		} else {
			global $wpdb;
			$key = self::namespace_option_name( $key );
			$row = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $key ) ); // db call ok, cache ok.
			if ( $row && isset( $row->option_value ) ) {
				return $row->option_value;
			} else {
				return null;
			}
		}
	}

	/**
	 * Sets the value(s) of one or more given options
	 *
	 * @param array|string $key   The name, or array of names+values, of options to be set.
	 * @param null|mixed   $value The value to be set (for a single name).
	 */
	public static function set( $key, $value = null ) {
		if ( is_array( $key ) ) {
			foreach ( $key as $k => $v ) {
				self::set( $k, $v );
			}
		} else {
			$key   = self::namespace_option_name( $key );
			$value = ( false === $value ) ? 0 : $value; // NOTE: "false" values come back as NULL.
			update_option( $key, $value );
		}
	}

	/**
	 * Deletes one or more given options
	 *
	 * @param array|string $key The name, or array of names, of options to be deleted.
	 */
	public static function delete( $key ) {
		if ( is_array( $key ) ) {
			foreach ( $key as $k ) {
				self::delete( $k );
			}
		} else {
			$key = self::namespace_option_name( $key );
			delete_option( $key );
		}
	}
}
