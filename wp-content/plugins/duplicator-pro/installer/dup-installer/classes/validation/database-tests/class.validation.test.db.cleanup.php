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

class DUPX_Validation_test_db_cleanup extends DUPX_Validation_abstract_item
{

    protected $errorMessage = '';

    protected function runTest()
    {
        if (DUPX_Validation_database_service::getInstance()->isDatabaseCreated() === false) {
            return self::LV_SKIP;
        }

        if (DUPX_Validation_database_service::getInstance()->cleanUpDatabase($this->errorMessage)) {
            return self::LV_PASS;
        } else {
            return self::LV_HARD_WARNING;
        }
    }

    public function getTitle()
    {
        return 'Database cleanup';
    }

    protected function hwarnContent()
    {
        return dupxTplRender('parts/validation/database-tests/db-cleanup', array(
            'isOk'         => false,
            'dbname'       => DUPX_Params_Manager::getInstance()->getValue(DUPX_Params_Manager::PARAM_DB_NAME),
            'isCpanel'     => (DUPX_Params_Manager::getInstance()->getValue(DUPX_Params_Manager::PARAM_DB_VIEW_MODE) === 'cpnl'),
            'errorMessage' => $this->errorMessage
            ), false);
    }

    protected function passContent()
    {
        return dupxTplRender('parts/validation/database-tests/db-cleanup', array(
            'isOk'         => true,
            'dbname'       => DUPX_Params_Manager::getInstance()->getValue(DUPX_Params_Manager::PARAM_DB_NAME),
            'isCpanel'     => (DUPX_Params_Manager::getInstance()->getValue(DUPX_Params_Manager::PARAM_DB_VIEW_MODE) === 'cpnl'),
            'errorMessage' => $this->errorMessage
            ), false);
    }
}