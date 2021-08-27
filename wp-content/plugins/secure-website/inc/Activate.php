<?php
/**
 * @package Secure Website
 */
namespace Inc;

class Activate
{
	function __construct() {

	}

	public static function up() {
		flush_rewrite_rules();
	}
}