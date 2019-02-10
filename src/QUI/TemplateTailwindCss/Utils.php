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


        $config = [];

        /* @var $Project QUI\Projects\Project */
        $Project  = $params['Project'];
        $Template = $params['Template'];

        /**
         * General page settings: Title? Description? Header? Breadcrumb?
         */
        $title      = false;
        $short       = false;
        $header     = false;
        $breadcrumb = false;

        switch ($Template->getLayoutType()) {
            case 'layout/startPage':
                $title      = $Project->getConfig('settings.page.startPage.title');
                $short       = $Project->getConfig('settings.page.startPage.desc');
                $header     = $Project->getConfig('settings.page.startPage.header');
                $breadcrumb = $Project->getConfig('settings.page.startPage.breadcrumb');
                break;

            case 'layout/noSidebar':
                $title      = $Project->getConfig('settings.page.noSidebar.title');
                $short       = $Project->getConfig('settings.page.noSidebar.desc');
                $header     = $Project->getConfig('settings.page.noSidebar.header');
                $breadcrumb = $Project->getConfig('settings.page.noSidebar.breadcrumb');
                break;

            case 'layout/noSidebarThin':
                $title      = $Project->getConfig('settings.page.noSidebarThin.title');
                $short       = $Project->getConfig('settings.page.noSidebarThin.desc');
                $header     = $Project->getConfig('settings.page.noSidebarThin.header');
                $breadcrumb = $Project->getConfig('settings.page.noSidebarThin.breadcrumb');
                break;

            case 'layout/leftSidebar':
                $title      = $Project->getConfig('settings.page.leftSidebar.title');
                $short       = $Project->getConfig('settings.page.leftSidebar.desc');
                $header     = $Project->getConfig('settings.page.leftSidebar.header');
                $breadcrumb = $Project->getConfig('settings.page.leftSidebar.breadcrumb');
                break;

            case 'layout/rightSidebar':
                $title      = $Project->getConfig('settings.page.rightSidebar.title');
                $short      = $Project->getConfig('settings.page.rightSidebar.desc');
                $header     = $Project->getConfig('settings.page.rightSidebar.header');
                $breadcrumb = $Project->getConfig('settings.page.rightSidebar.breadcrumb');
                break;
        }

        /* site own title settings */
        switch ($params['Site']->getAttribute('site.settings.pageTitle')) {
            case 'show':
                $title = true;
                break;
            case 'hide':
                $title = false;
        }

        /* site own description settings */
        switch ($params['Site']->getAttribute('site.settings.pageShort')) {
            case 'show':
                $short = true;
                break;
            case 'hide':
                $short = false;
        }

        /* site own header settings */
        switch ($params['Site']->getAttribute('site.settings.pageHeader')) {
            case 'show':
                $header = true;
                break;
            case 'hide':
                $header = false;
        }

        /* site own breadcrumb settings */
        switch ($params['Site']->getAttribute('site.settings.pageBreadcrumb')) {
            case 'show':
                $breadcrumb = true;
                break;
            case 'hide':
                $breadcrumb = false;
        }

        $settingsCSS = include 'settings.css.php';

        $config += [
            'quiTplType'     => $Project->getConfig('templateTailwindCss.settings.standardType'),
            'typeClass'      => 'type-' . str_replace(['/', ':'], '-', $params['Site']->getAttribute('type')),
            'pageTitle'      => $title,
            'pageShort'      => $short,
            'pageHeader'     => $header,
            'pageBreadcrumb' => $breadcrumb,
            'settingsCSS'    => '<style>' . $settingsCSS . '</style>',
        ];

        // set cache
        QUI\Cache\Manager::set(
            'quiqqer/templateTailwindCss/' . $params['Site']->getId(),
            $config
        );

        return $config;
    }
}
