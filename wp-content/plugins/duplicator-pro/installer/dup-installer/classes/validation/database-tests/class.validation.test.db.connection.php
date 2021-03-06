<?php
/**
 * Validation object
 *
 * Standard: PSR-2
 * @link http://www.php-fig.org/psr/psr-2 Full Documentation
 *
 * @package SC\DUPX\U
 *
 */
defined('ABSPATH') || defined('DUPXABSPATH') || exit;

class DUPX_Validation_test_db_connection extends DUPX_Validation_abstract_item
{

    protected function runTest()
    {
        if (DUPX_Validation_database_service::getInstance()->skipDatabaseTests()) {
            return self::LV_SKIP;
        }

        DUPX_Validation_database_service::getInstance()->setSkipOtherTests(true);
        if (DUPX_Validation_database_service::getInstance()->getDbConnection() === false) {
            return self::LV_FAIL;
        } else {
            DUPX_Validation_database_service::getInstance()->setSkipOtherTests(false);
            return self::LV_PASS;
        }
    }

    public function getTitle()
    {
        return 'Verify Host Connection';
    }

    protected function failContent()
    {
        return dupxTplRender('parts/validation/database-tests/db-connection', array(
            'isOk'         => false,
            'dbhost'       => DUPX_Params_Manager::getInstance()->getValue(DUPX_Params_Manager::PARAM_DB_HOST),
            'dbuser'       => DUPX_Params_Manager::getInstance()->getValue(DUPX_Params_Manager::PARAM_DB_USER),
            'dbpass'       => DUPX_Params_Manager::getInstance()->getValue(DUPX_Params_Manager::PARAM_DB_PASS),
            'mysqlConnErr' => mysqli_connect_error()
            ), false);
    }

    protected function passContent()
    {
        return dupxTplRender('parts/validation/database-tests/db-connection', array(
            'isOk'         => true,
            'dbhost'       => DUPX_Params_Manager::getInstance()->getValue(DUPX_Params_Manager::PARAM_DB_HOST),
            'dbuser'       => DUPX_Params_Manager::getInstance()->getValue(DUPX_Params_Manager::PARAM_DB_USER),
            'dbpass'       => '*****',
            'mysqlConnErr' => ''
            ), false);
    }
}