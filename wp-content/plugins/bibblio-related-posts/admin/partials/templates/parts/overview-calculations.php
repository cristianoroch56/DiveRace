<?php
/**
 * Performs the calculations and sets the variables needed for the Overview page
 *
 * @category   Bibblio_Related_Posts_Overview
 * @package    Bibblio_Related_Posts
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

$bibblio = new Bibblio_Related_Posts_Support();
$bibblio->bibblio_init();

$account_plan = $bibblio->get_account_plan( $force_refresh );

// calculate recommendation limit.
if ( $account_plan && isset( $account_plan['limits'] ) && isset( $account_plan['limits']['monthly'] ) && isset( $account_plan['limits']['monthly']['recommendations'] ) ) {
	$recommendations_limit = $account_plan['limits']['monthly']['recommendations'];
} else {
	$recommendations_limit = false;
}

// calculate recommendation usage.
$get_monthly_usage = $bibblio->get_recommendation_usage( $force_refresh );
if ( is_array( $get_monthly_usage ) && end( $get_monthly_usage ) && isset( end( $get_monthly_usage )['total'] ) ) {
	$recs_usage = (int) end( $get_monthly_usage )['total'];
} else {
	$recs_usage = false;
}

// calculate recommendation percent used.
if ( $recommendations_limit && $recs_usage ) {
	$recs_percent = $recs_usage / $recommendations_limit * 100;
} else {
	$recs_percent = 0;
}

// calculate storage limit.
if ( $account_plan && isset( $account_plan['limits'] ) && isset( $account_plan['limits']['total'] ) && isset( $account_plan['limits']['total']['content-items'] ) ) {
	$content_item_limit = $account_plan['limits']['total']['content-items'];
} else {
	$content_item_limit = false;
}

// calculate storage usage.
$content_item_usage = $bibblio->get_content_item_count( $force_refresh );

// calculate storage percent used.
if ( $content_item_usage && $content_item_limit ) {
	$storage_percent = $content_item_usage / $content_item_limit * 100;
} else {
	$storage_percent = 0;
}

// calculate recs label colour.
if ( $recs_percent >= 100 ) {
	$recs_percent = 100;
	$recs_colour  = 'red';
} elseif ( $recs_percent >= 85 ) {
	$recs_colour = 'orange';
} else {
	$recs_colour = 'blue';
}

// calculate storage label and thermometer colour.
if ( $storage_percent >= 100 ) {
	$storage_percent = 100;
	$storage_colour  = 'red';
} elseif ( $storage_percent >= 85 ) {
	$storage_colour = 'orange';
} else {
	$storage_colour = 'green';
}

// calculate import progress.
$posts_count     = $bibblio->count_published_posts();
$unindexed_posts = $bibblio->count_posts_to_import() + $bibblio->count_posts_with_import_error();

if ( is_int( $posts_count ) ) {
	$indexed_count = $posts_count - $unindexed_posts;

	if ( $posts_count > 0 ) {
		$indexed_percent = $indexed_count / $posts_count * 100;
	} else {
		$indexed_percent = 100;
	}
}
