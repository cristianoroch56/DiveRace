<?php
/**
 *
 * @package templates/default
 *
 */
defined('ABSPATH') || defined('DUPXABSPATH') || exit;

$paramsManager = DUPX_Params_Manager::getInstance();
if (!DUPX_MU::newSiteIsMultisite()) {
    return;
}
?>
<div id="subsite-map-container" class="<?php echo $paramsManager->getValue(DUPX_Params_Manager::PARAM_REPLACE_MODE) == 'mapping' ? '' : 'no-display'; ?> margin-top-1">
    <div id="s3-subsite-mapping">
        <div class="url-mapping-header" >
            <span class="left" >Old URLs</span>
            <span class="right" >New URLs</span>
        </div>
        <?php
        $paramsManager->getHtmlFormParam(DUPX_Params_Manager::PARAM_MU_REPLACE);
        ?>
    </div>
</div>
