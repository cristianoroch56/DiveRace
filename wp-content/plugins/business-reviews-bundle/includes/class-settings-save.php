<?php

namespace WP_Business_Reviews_Bundle\Includes;

use WP_Business_Reviews_Bundle\Includes\Core\Database;

class Settings_Save {

    public function __construct(Activator $activator) {
        $this->activator = $activator;
    }

    public function register() {
        add_action('admin_post_brb_settings_save', array($this, 'save_from_post_array'));
    }

    public function save_from_post_array() {
        global $wpdb;

        if (!function_exists('wp_nonce_field')) {
            function wp_nonce_field() {}
        }

        if (!current_user_can('manage_options')) {
            die('The account you\'re logged in to doesn\'t have permission to access this page.');
        }

        if (!empty($_POST)) {
            $nonce_result_check = $this->check_nonce();
            if ($nonce_result_check === false) {
                die('Unable to save changes. Make sure you are accessing this page from the Wordpress dashboard.');
            }
        }

        $notice_code = null;

        if (isset($_POST['active']) && isset($_GET['active'])) {
            $active = $_GET['active'] == '1' ? '1' : '0';
            update_option('brb_active', $active);
            $notice_code = 'settings_active_' . $active;
        }

        if (isset($_POST['save'])) {
            $fields = array('brb_demand_assets', 'brb_minified_assets', 'brb_license', 'brb_google_api_key', 'brb_google_places_api', 'brb_yelp_api_key');
            foreach ($fields as $key => $value) {
                if (isset($_POST[$value])) {
                    update_option($value, trim(sanitize_text_field($_POST[$value])));
                }
            }
            $notice_code = 'settings_save';
        }

        if (isset($_POST['create_db'])) {
            $this->activator->create_db();
            $notice_code = 'settings_create_db';
        }

        /*if (isset($_POST['reset'])) {
            $this->activator->delete_all_options();
            $notice_code = 'settings_reset';
        }*/

        if (isset($_POST['install'])) {
            $install_multisite = $_POST['install_multisite'];
            $this->activator->drop_db($install_multisite);
            $this->activator->delete_all_options($install_multisite);
            $this->activator->delete_all_collections($install_multisite);
            $this->activator->activate();
            $notice_code = 'settings_install';
        }

        if (isset($_POST['reset_all'])) {
            $reset_all_multisite = $_POST['reset_all_multisite'];
            $this->activator->drop_db($reset_all_multisite);
            $this->activator->delete_all_options($reset_all_multisite);
            $this->activator->delete_all_collections($reset_all_multisite);
            $notice_code = 'settings_reset_all';
        }

        if (isset($_POST['brb_license'])) {
            $brb_license = trim(sanitize_text_field($_POST['brb_license']));
            if (strlen($brb_license) > 0) {
                $request = wp_remote_post('https://admin.richplugins.com/plugins/license-activate', array(
                    'timeout'   => 15,
                    'sslverify' => false,
                    'body'      => array(
                        'license' => $brb_license,
                        'slug'    => 'brb',
                        'plugin'  => 'Business Reviews Bundle',
                        'active'  => '1',
                        'siteurl' => get_option('siteurl')
                    )
                ));

                if (!is_wp_error($request)) {
                    $request = json_decode(wp_remote_retrieve_body($request));
                }
                if ($request) {
                    if ($request->status == 'error') {
                        update_option('brb_notice_msg', $request->msg);
                        $notice_code = 'custom_msg';
                    }
                }
                delete_transient('license_status_' . $brb_license);
            }
        }

        $brb_license = get_option('brb_license');
        if (isset($_POST['brb_license_deactive']) && strlen($brb_license) > 0) {
            $request = wp_remote_post('https://admin.richplugins.com/plugins/license-activate', array(
                'timeout'   => 15,
                'sslverify' => false,
                'body'      => array(
                    'license' => $brb_license,
                    'slug'    => 'brb',
                    'plugin'  => 'Business Reviews Bundle',
                    'active'  => '0'
                )
            ));

            if (!is_wp_error($request)) {
                $request = json_decode(wp_remote_retrieve_body($request));
            }
            if ($request) {
                if ($request->status == 'error') {
                    update_option('brb_notice_msg', $request->msg);
                    $notice_code = 'custom_msg';
                }
            }
            delete_transient('license_status_' . $brb_license);
        }

        if (isset($_POST['migrate'])) {
            update_option('brb_notice_msg', $this->migrate());
            $notice_code = 'custom_msg';
        }

        $this->redirect_to_tab($notice_code);
    }

    public function redirect_to_tab($notice_code = '') {
        if (empty($_GET['brb_tab'])) {
            wp_safe_redirect(wp_get_referer());
            exit;
        }

        $tab = sanitize_text_field(wp_unslash($_GET['brb_tab']));

        $query_args = array(
            'brb_tab' => $tab,
        );

        if (!empty($notice_code)) {
            $query_args['brb_notice'] = $notice_code;
        }

        wp_safe_redirect(add_query_arg($query_args, wp_get_referer()));
        exit;
    }

    private function check_nonce() {
        $nonce_actions = array('active', 'save', 'create_db', 'reset', 'reset_all');
        $nonce_form_prefix = 'brb-form_nonce_';
        $nonce_action_prefix = 'brb-wpnonce_';
        foreach ($nonce_actions as $key => $value) {
            if (isset($_POST[$nonce_form_prefix.$value])) {
                check_admin_referer($nonce_action_prefix.$value, $nonce_form_prefix.$value);
                return true;
            }
        }
        return false;
    }

    private function migrate() {
        global $wpdb;

        $notice_msg = '<b>Migration log</b>:<br><br>';

        if (isset($_POST['migrate_google'])) {
            $reviews_count = 0;
            $google_places = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "grp_google_place");
            foreach ($google_places as $google_place) {

                $brb_google_place = $wpdb->get_row(
                    $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . Database::BUSINESS_TABLE . " WHERE place_id = %s AND platform = %s", $google_place->place_id, 'google')
                );

                $brb_business_id = 0;
                if (!$brb_google_place) {
                    $wpdb->insert($wpdb->prefix . Database::BUSINESS_TABLE, array(
                        'place_id' => $google_place->place_id,
                        'name'     => $google_place->name,
                        'photo'    => $google_place->photo,
                        'icon'     => $google_place->icon,
                        'address'  => $google_place->address,
                        'rating'   => $google_place->rating,
                        'url'      => $google_place->url,
                        'website'  => $google_place->website,
                        'platform' => 'google'
                    ));
                    $brb_business_id = $wpdb->insert_id;

                    if (isset($wpdb->last_error ) && strlen($wpdb->last_error ) > 0) {
                        $notice_msg = $notice_msg . '<span style="color:red">Google place <b>' . $google_place->name . '</b> error: ' . $wpdb->last_error  . '</span>';
                    } else {
                        $notice_msg = $notice_msg . 'Google place <b>' . $google_place->name . '</b> imported ';
                    }
                    $notice_msg = $notice_msg . '<br>';
                }

                $google_reviews = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "grp_google_review WHERE google_place_id = %d", $google_place->id));

                foreach ($google_reviews as $google_review) {

                    if ($brb_business_id > 0) {
                        $notice_msg = $notice_msg . $this->insert_google_review($brb_business_id, $google_review);
                        $reviews_count++;
                    } else {
                        $brb_google_review = $wpdb->get_row(
                            $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . Database::REVIEW_TABLE . " WHERE time = %s AND business_id = %d AND platform = %s", $google_review->time, $brb_google_place->id, 'google')
                        );
                        if (!$brb_google_review) {
                            $notice_msg = $notice_msg . $this->insert_google_review($brb_google_place->id, $google_review);
                            $reviews_count++;
                        }
                    }

                }
            }
            $notice_msg = $notice_msg . 'Imported ' . $reviews_count . ' Google reviews';
        }

        if (isset($_POST['migrate_yelp'])) {

            if (isset($_POST['migrate_google'])) {
                $notice_msg = $notice_msg . '<br><br>';
            }

            $reviews_count = 0;
            $yelp_businesses = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "yrw_yelp_business");
            foreach ($yelp_businesses as $yelp_business) {

                $brb_yelp_business = $wpdb->get_row(
                    $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . Database::BUSINESS_TABLE . " WHERE place_id = %s AND platform = %s", $yelp_business->business_id, 'yelp')
                );

                $brb_business_id = 0;
                if (!$brb_yelp_business) {
                    $wpdb->insert($wpdb->prefix . Database::BUSINESS_TABLE, array(
                        'place_id'     => $yelp_business->business_id,
                        'name'         => $yelp_business->name,
                        'photo'        => $yelp_business->photo,
                        'address'      => $yelp_business->address,
                        'rating'       => $yelp_business->rating,
                        'url'          => $yelp_business->url,
                        'website'      => $yelp_business->website,
                        'review_count' => $yelp_business->review_count,
                        'platform' => 'yelp'
                    ));
                    $brb_business_id = $wpdb->insert_id;

                    if (isset($wpdb->last_error ) && strlen($wpdb->last_error ) > 0) {
                        $notice_msg = $notice_msg . '<span style="color:red">Yelp business <b>' . $yelp_business->name . '</b> error: ' . $wpdb->last_error  . '</span>';
                    } else {
                        $notice_msg = $notice_msg . 'Yelp business <b>' . $yelp_business->name . '</b> imported ';
                    }
                    $notice_msg = $notice_msg . '<br>';
                }

                $yelp_reviews = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "yrw_yelp_review WHERE yelp_business_id = %d", $yelp_business->id));

                foreach ($yelp_reviews as $yelp_review) {

                    if ($brb_business_id > 0) {
                        $notice_msg = $notice_msg . $this->insert_yelp_review($brb_business_id, $yelp_review);
                        $reviews_count++;
                    } else {
                        $brb_yelp_review = $wpdb->get_row(
                            $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . Database::REVIEW_TABLE . " WHERE time_str = %s AND business_id = %d AND platform = %s", $yelp_review->time, $brb_yelp_business->id, 'yelp')
                        );
                        if (!$brb_yelp_review) {
                            $notice_msg = $notice_msg . $this->insert_yelp_review($brb_yelp_business->id, $yelp_review);
                            $reviews_count++;
                        }
                    }

                }
            }
            $notice_msg = $notice_msg . 'Imported ' . $reviews_count . ' Yelp reviews';
        }

        return $notice_msg;
    }

    private function insert_google_review($business_id, $google_review) {
        global $wpdb;

        $notice_msg = '';
        $wpdb->insert($wpdb->prefix . Database::REVIEW_TABLE, array(
            'business_id' => $business_id,
            'rating'      => $google_review->rating,
            'text'        => $google_review->text,
            'time'        => $google_review->time,
            'language'    => $google_review->language,
            'author_name' => $google_review->author_name,
            'author_url'  => $google_review->author_url,
            'author_img'  => $google_review->profile_photo_url,
            'platform'    => 'google'
        ));
        if (isset($wpdb->last_error ) && strlen($wpdb->last_error ) > 0) {
            $notice_msg = $notice_msg . '<span style="color:red">Google review <b>' . $google_review->author_name . '</b> error: ' . $wpdb->last_error  . '</span>';
        } else {
            $notice_msg = $notice_msg . 'Google review <b>' . $google_review->author_name . '</b> imported ';
        }
        return $notice_msg . '<br>';
    }

    private function insert_yelp_review($business_id, $yelp_review) {
        global $wpdb;

        $notice_msg = '';
        $wpdb->insert($wpdb->prefix . Database::REVIEW_TABLE, array(
            'business_id' => $business_id,
            'rating'      => $yelp_review->rating,
            'text'        => $yelp_review->text,
            'url'         => $yelp_review->url,
            'time_str'    => $yelp_review->time,
            'author_name' => $yelp_review->author_name,
            'author_img'  => $yelp_review->author_img,
            'platform'    => 'yelp'
        ));
        if (isset($wpdb->last_error ) && strlen($wpdb->last_error ) > 0) {
            $notice_msg = $notice_msg . '<span style="color:red">Yelp review <b>' . $yelp_review->author_name . '</b> error: ' . $wpdb->last_error  . '</span>';
        } else {
            $notice_msg = $notice_msg . 'Yelp review <b>' . $yelp_review->author_name . '</b> imported ';
        }
        return $notice_msg . '<br>';
    }
}
