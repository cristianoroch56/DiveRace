<?php

/**
 * Storage page controller
 *
 * @package Duplicator
 * @copyright (c) 2021, Snapcreek LLC
 *
 */

namespace Duplicator\Controllers;

use Duplicator\Core\Controllers\ControllersManager;
use Duplicator\Core\Controllers\AbstractMenuPageController;

class StoragePageController extends AbstractMenuPageController
{

    protected function __construct()
    {
        $this->parentSlug   = ControllersManager::MAIN_MENU_SLUG;
        $this->pageSlug     = ControllersManager::STORAGE_SUBMENU_SLUG;
        $this->pageTitle    = __('Storage', 'duplicator_pro');
        $this->menuLabel    = __('Storage', 'duplicator_pro');
        $this->capatibility = self::getDefaultCapadibily();
        $this->menuPos      = 40;

        add_filter('duplicator_render_page_content_' . $this->pageSlug, array($this, 'renderContent'));
    }

    public function renderContent($currentLevelSlugs)
    {
        require(DUPLICATOR____PATH . '/views/storage/controller.php');
    }
}
