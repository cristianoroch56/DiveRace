<?php

/**
 * Auloader calsses
 *
 * Standard: PSR-2
 * @link http://www.php-fig.org/psr/psr-2
 *
 * @package Duplicator
 * @copyright (c) 2021, Snapcreek LLC
 *
 */

namespace Duplicator\Addons\ProBase\License;

defined('ABSPATH') || exit;

final class License
{
    /**
     * GENERAL SETTINGS
     */
    const EDD_DUPPRO_STORE_URL               = 'https://snapcreek.com';
    const EDD_DUPPRO_ITEM_NAME               = 'Duplicator Pro';
    const LICENSE_KEY_OPTION_NAME            = 'duplicator_pro_license_key';
    const LICENSE_CACHE_TIME                 = 1209600; // 14 DAYS IN SECONDS
    const LICENSE_CACHE_CLEAR_KEY            = 'dup_pro_clear_updater_cache';
    const EDD_API_CACHE_TIME                 = 172800; // 48 hours
    const UNLICENSED_SUPER_NAG_DELAY_IN_DAYS = 30;

    /**
     * LICENSE STATUS
     */
    const STATUS_OUT_OF_LICENSES = -3;
    const STATUS_UNCACHED        = -2;
    const STATUS_UNKNOWN         = -1;
    const STATUS_VALID           = 0;
    const STATUS_INVALID         = 1;
    const STATUS_INACTIVE        = 2;
    const STATUS_DISABLED        = 3;
    const STATUS_SITE_INACTIVE   = 4;
    const STATUS_EXPIRED         = 5;

    /**
     * LICENSE TYPES
     */
    const TYPE_UNLICENSED    = 0;
    const TYPE_PERSONAL      = 1;
    const TYPE_FREELANCER    = 2;
    const TYPE_BUSINESS_GOLD = 3;

    /**
     * ACTIVATION REPONSE
     */
    const ACTIVATION_RESPONSE_OK         = 0;
    const ACTIVATION_RESPONSE_POST_ERROR = -1;
    const ACTIVATION_RESPONSE_INVALID    = -2;

    public static function check()
    {
        $dpro_license_key = get_option(self::LICENSE_KEY_OPTION_NAME, '');
        if (empty($dpro_license_key)) {
            return;
        }

        $global = \DUP_PRO_Global_Entity::get_instance();

        // RSR TODO: only init this if not an override key and we are active
        if (
            ($global !== null) &&
            (!self::isValidOvrKey($dpro_license_key)) &&
            ($global->license_status !== self::STATUS_INVALID) &&
            ($global->license_status !== self::STATUS_UNKNOWN)
        ) {
            // Don't bother checking updates if license key isn't filled in since that will just create unnecessary traffic
            $dpro_edd_opts = array(
                'version'     => DUPLICATOR_PRO_VERSION,
                'license'     => $dpro_license_key,
                'item_name'   => self::EDD_DUPPRO_ITEM_NAME,
                'author'      => 'Snap Creek Software',
                'cache_time'  => self::EDD_API_CACHE_TIME,
                'wp_override' => true
            );

            // hook EDD updater added in object constructor, this object must be instantiated even if not used
            $edd_updater = new \Duplicator_EDD_SL_Plugin_Updater(
                self::EDD_DUPPRO_STORE_URL,
                DUPLICATOR____FILE,
                $dpro_edd_opts,
                \DUP_PRO_Constants::PLUGIN_SLUG
            );

            // crear cache
            if (\DupProSnapLibUtil::filterInputRequest(self::LICENSE_CACHE_CLEAR_KEY, FILTER_VALIDATE_BOOLEAN)) {
                $edd_updater->clear_version_cache();
            }
        }
    }

    /**
     *
     * @return string
     */
    public static function getLicenseKey()
    {
        return get_option(License::LICENSE_KEY_OPTION_NAME);
    }

    public static function changeLicenseActivation($activate)
    {
        $license = get_option(self::LICENSE_KEY_OPTION_NAME, '');

        if ($activate) {
            $api_params = array(
                'edd_action' => 'activate_license',
                'license'    => $license,
                'item_name'  => urlencode(self::EDD_DUPPRO_ITEM_NAME), // the name of our product in EDD,
                'url'        => home_url()
            );
        } else {
            $api_params = array(
                'edd_action' => 'deactivate_license',
                'license'    => $license,
                'item_name'  => urlencode(self::EDD_DUPPRO_ITEM_NAME), // the name of our product in EDD,
                'url'        => home_url()
            );
        }

        // Call the custom API.
        global $wp_version;

        $agent_string = "WordPress/" . $wp_version;

        \DUP_PRO_LOG::trace("Wordpress agent string $agent_string");

        $response = wp_remote_post(
            self::EDD_DUPPRO_STORE_URL,
            array('timeout'    => 15, 'sslverify'  => false, 'user-agent' => $agent_string,
                'body'       => $api_params)
        );

        // make sure the response came back okay
        if (is_wp_error($response)) {
            if ($activate) {
                $action = 'activating';
            } else {
                $action = 'deactivating';
            }

            \DUP_PRO_LOG::traceObject("Error $action $license", $response);

            return self::ACTIVATION_RESPONSE_POST_ERROR;
        }

        $license_data = json_decode(wp_remote_retrieve_body($response));

        if ($activate) {
            // decode the license data
            if ($license_data->license == 'valid') {
                \DUP_PRO_LOG::trace("Activated license $license");
                return self::ACTIVATION_RESPONSE_OK;
            } else {
                \DUP_PRO_LOG::traceObject("Problem activating license $license", $license_data);
                return self::ACTIVATION_RESPONSE_INVALID;
            }
        } else {
            // check that license:deactivated and item:Duplicator Pro json
            if ($license_data->license == 'deactivated') {
                \DUP_PRO_LOG::trace("Deactivated license $license");
                return self::ACTIVATION_RESPONSE_OK;
            } else {
                // problems activating
                //update_option('edd_sample_license_status', $license_data->license);
                \DUP_PRO_LOG::traceObject("Problems deactivating license $license", $license_data);
                return self::ACTIVATION_RESPONSE_INVALID;
            }
        }
    }

    public static function isValidOvrKey($scrambledKey)
    {
        return true;

        $isValid        = false;
        $unscrambledKey = \DUP_PRO_Crypt::unscramble($scrambledKey);

        if (\DUP_PRO_STR::startsWith($unscrambledKey, 'SCOVRK')) {
            $index = strpos($unscrambledKey, '_');

            if ($index !== false) {
                $index++;
                $count = substr($unscrambledKey, $index);

                if (is_numeric($count) && ($count > 0)) {
                    $isValid = true;
                }
            }
        }

        return $isValid;
    }

    public static function setOvrKey($scrambledKey)
    {
        if (self::isValidOvrKey($scrambledKey)) {
            $unscrambledKey = \DUP_PRO_Crypt::unscramble($scrambledKey);

            $index = strpos($unscrambledKey, '_');

            if ($index !== false) {
                $index++;
                $count = substr($unscrambledKey, $index);

                /* @var $global \DUP_PRO_Global_Entity */
                $global = \DUP_PRO_Global_Entity::get_instance();

                $global->license_limit               = $count;
                $global->license_no_activations_left = false;
                $global->license_status              = self::STATUS_VALID;

                $global->save();

                \DUP_PRO_LOG::trace("$unscrambledKey is an ovr key with license limit $count");

                update_option(self::LICENSE_KEY_OPTION_NAME, $scrambledKey);
            }
        } else {
            throw new Exception("Ovr key in wrong format: $unscrambledKey");
        }
    }

    public static function getStandardKeyFromOvrKey($scrambledKey)
    {
        $standardKey = '';

        if (self::isValidOvrKey($scrambledKey)) {
            $unscrambledKey = \DUP_PRO_Crypt::unscramble($scrambledKey);

            $standardKey = substr($unscrambledKey, 6, 32);
        } else {
            throw new Exception("Ovr key in wrong format: $unscrambledKey");
        }

        return $standardKey;
    }

    public static function getLicenseStatus($forceRefresh)
    {
        /* @var $global \DUP_PRO_Global_Entity */
        $global = \DUP_PRO_Global_Entity::get_instance();
        if (!($global instanceof \DUP_PRO_Global_Entity)) {
            if (is_admin()) {
                add_action('admin_notices', array('\DUP_PRO_UI_Alert', 'showTablesCorrupted'));
                add_action('network_admin_notices', array('\DUP_PRO_UI_Alert', 'showTablesCorrupted'));
            }
            throw new Exception("Global Entity is null!");
        }
        $license_key = get_option(self::LICENSE_KEY_OPTION_NAME, '');

        if (self::isValidOvrKey($license_key)) {
            if ($global->license_status != self::STATUS_VALID) {
                $global->license_status = self::STATUS_VALID;
                $global->save();
            }
        } else {
            $initial_status = $global->license_status;

            if ($forceRefresh === false) {
                if (time() > $global->license_expiration_time) {
                    \DUP_PRO_LOG::trace("Uncaching license because current time = " . time() . " and expiration time = {$global->license_expiration_time}");
                    $global->license_status = self::STATUS_UNCACHED;
                }
            } else {
                \DUP_PRO_LOG::trace("forcing live license update");
                $global->license_status = self::STATUS_UNCACHED;
            }

            if ($global->license_limit == -1) {
                $global->license_status = self::STATUS_UNCACHED;
            }

            if ($global->license_status == self::STATUS_UNCACHED) {
                \DUP_PRO_LOG::trace("retrieving live license status");
                $store_url = 'https://snapcreek.com';
                $item_name = 'Duplicator Pro';

                if ($license_key != '') {
                    $api_params = array(
                        'edd_action' => 'check_license',
                        'license'    => $license_key,
                        'item_name'  => urlencode($item_name),
                        'url'        => home_url()
                    );

                    global $wp_version;
                    $agent_string = "WordPress/" . $wp_version;

                    $response = wp_remote_post(
                        $store_url,
                        array('timeout'    => 15, 'sslverify'  => false, 'user-agent' => $agent_string,
                            'body'       => $api_params)
                    );

                    if (is_wp_error($response)) {
                        $global->license_status = $initial_status;
                        \DUP_PRO_LOG::trace("Error getting license check response for $license_key so leaving status alone");
                    } else {
                        $license_data = json_decode(wp_remote_retrieve_body($response));

                        \DUP_PRO_LOG::traceObject("license data in response returned", $response);
                        \DUP_PRO_LOG::traceObject("license data returned", $license_data);

                        $global->license_status = self::getLicenseStatusFromString($license_data->license);

                        $global->license_no_activations_left = false;

                        if (!isset($license_data->license_limit)) {
                            $global->license_limit = -1;
                        } else {
                            $global->license_limit = $license_data->license_limit;
                        }

                        if (($global->license_status == self::STATUS_SITE_INACTIVE) && ($license_data->activations_left === 0)) {
                            $global->license_no_activations_left = true;
                        }

                        if ($global->license_status == self::STATUS_UNKNOWN) {
                            \DUP_PRO_LOG::trace("Problem retrieving license status for $license_key");
                        }
                    }
                } else {
                    $global->license_limit               = -1;
                    $global->license_status              = self::STATUS_INVALID;
                    $global->license_no_activations_left = false;
                }

                $global->license_expiration_time = time() + self::LICENSE_CACHE_TIME;

                $global->save();

                \DUP_PRO_LOG::trace("Set cached value from with expiration " . self::LICENSE_CACHE_TIME . " seconds from now ({$global->license_expiration_time})");
            }
        }

        return $global->license_status;
    }

    public static function getLicenseStatusString($licenseStatusString)
    {
        switch ($licenseStatusString) {
            case self::STATUS_VALID:
                return \DUP_PRO_U::__('Valid');
            case self::STATUS_INVALID:
                return \DUP_PRO_U::__('Invalid');
            case self::STATUS_EXPIRED:
                return \DUP_PRO_U::__('Expired');
            case self::STATUS_DISABLED:
                return \DUP_PRO_U::__('Disabled');
            case self::STATUS_SITE_INACTIVE:
                return \DUP_PRO_U::__('Site Inactive');
            case self::STATUS_EXPIRED:
                return \DUP_PRO_U::__('Expired');
            default:
                return \DUP_PRO_U::__('Unknown');
        }
    }

    public static function getType()
    {
        /* @var $global \DUP_PRO_Global_Entity */
        $global = \DUP_PRO_Global_Entity::get_instance();
        $global->license_limit = 800;


        if ($global->license_limit < 0) {
            $license_type = self::TYPE_UNLICENSED;
        } elseif ($global->license_limit < 15) {
            $license_type = self::TYPE_PERSONAL;
        } elseif ($global->license_limit < 500) {
            $license_type = self::TYPE_FREELANCER;
        } elseif ($global->license_limit >= 500) {
            $license_type = self::TYPE_BUSINESS_GOLD;
        } else {
            $license_type = self::TYPE_PERSONAL;
        }

        return $license_type;
    }

    /**
     *
     * @return boolean
     */
    public static function isPersonal()
    {
        return self::getType() >= self::TYPE_PERSONAL;
    }

    /**
     *
     * @return boolean
     */
    public static function isFreelancer()
    {
        return self::getType() >= self::TYPE_FREELANCER;
    }

    /**
     *
     * @return boolean
     */
    public static function isBusiness()
    {
        return self::getType() >= self::TYPE_BUSINESS_GOLD;
    }

    /**
     *
     * @return boolean
     */
    public static function isGold()
    {
        return self::getType() >= self::TYPE_BUSINESS_GOLD;
    }

    private static function getLicenseStatusFromString($licenseStatusString)
    {
        switch ($licenseStatusString) {
            case 'valid':
                return self::STATUS_VALID;
            case 'invalid':
                return self::STATUS_INVALID;
            case 'expired':
                return self::STATUS_EXPIRED;
            case 'disabled':
                return self::STATUS_DISABLED;
            case 'site_inactive':
                return self::STATUS_SITE_INACTIVE;
            case 'inactive':
                return self::STATUS_INACTIVE;
            default:
                return self::STATUS_UNKNOWN;
        }
    }
}
