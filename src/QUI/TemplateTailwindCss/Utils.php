<?php

/**
 * This file contains QUI\TemplateTailwindCss\Utils
 */

namespace QUI\TemplateTailwindCss;

use QUI;

/**
 * Help Class for Template Tailwind CSS
 *
 * @package QUI\TemplateTailwindCss
 * @author www.pcsg.de (Michael Danielczok)
 *
 * @return array
 */
class Utils
{
    /**
     * @param array $params
     * @return array
     */
    public static function getConfig($params)
    {
        try {
            return QUI\Cache\Manager::get(
                'quiqqer/templateTailwindCss/' . $params['Site']->getId()
            );
        } catch (QUI\Exception $Exception) {
        }

        $config = array();

        /* @var $Project QUI\Projects\Project */
        $Project  = $params['Project'];
        $Template = $params['Template'];

        /**
         * no header?
         * no breadcrumb?
         * Body Class
         *
         * own site type
         */

        $showHeader     = false;
        $showBreadcrumb = false;

        switch ($Template->getLayoutType()) {
            case 'layout/startPage':
                $showHeader     = $Project->getConfig('templateTailwindCss.settings.showHeaderStartPage');
                $showBreadcrumb = $Project->getConfig('templateTailwindCss.settings.showBreadcrumbStartPage');
                break;

            case 'layout/noSidebar':
                $showHeader     = $Project->getConfig('templateTailwindCss.settings.showHeaderNoSidebar');
                $showBreadcrumb = $Project->getConfig('templateTailwindCss.settings.showBreadcrumbNoSidebar');
                break;

            case 'layout/rightSidebar':
                $showHeader     = $Project->getConfig('templateTailwindCss.settings.showHeaderRightSidebar');
                $showBreadcrumb = $Project->getConfig('templateTailwindCss.settings.showBreadcrumbRightSidebar');
                break;

            case 'layout/leftSidebar':
                $showHeader     = $Project->getConfig('templateTailwindCss.settings.showHeaderLeftSidebar');
                $showBreadcrumb = $Project->getConfig('templateTailwindCss.settings.showBreadcrumbLeftSidebar');
                break;
        }


        $showPageTitle = $params['Site']->getAttribute('templateTailwindCss.showTitle');
        $showPageShort = $params['Site']->getAttribute('templateTailwindCss.showShort');

        /* site own show header */
        switch ($params['Site']->getAttribute('templateTailwindCss.showEmotion')) {
            case 'show':
                $showHeader = true;
                break;
            case 'hide':
                $showHeader = false;
        }

        $settingsCSS = include 'settings.css.php';

        $config += array(
            'quiTplType'     => $Project->getConfig('templateTailwindCss.settings.standardType'),
            'showHeader'     => $showHeader,
            'showBreadcrumb' => $showBreadcrumb,
//            'settingsCSS'    => '<style>' . $settingsCSS . '</style>',
            'typeClass'      => 'type-' . str_replace(array('/', ':'), '-', $params['Site']->getAttribute('type')),
            'showPageTitle'  => $showPageTitle,
            'showPageShort'  => $showPageShort
        );

        // set cache
        QUI\Cache\Manager::set(
            'quiqqer/templateTailwindCss/' . $params['Site']->getId(),
            $config
        );

        return $config;
    }
}
