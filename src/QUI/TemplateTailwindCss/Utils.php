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
        $title            = false;
        $short            = false;
        $titleAndShortPos = 'page';
        $header           = false;
        $breadcrumb       = false;

        switch ($Template->getLayoutType()) {
            case 'layout/startPage':
                $title            = $Project->getConfig('settings.page.startPage.title');
                $short            = $Project->getConfig('settings.page.startPage.short');
                $titleAndShortPos = $Project->getConfig('settings.page.startPage.titleAndShortPos');
                $header           = $Project->getConfig('settings.page.startPage.header');
                $breadcrumb       = $Project->getConfig('settings.page.startPage.breadcrumb');
                break;

            case 'layout/productLandingPage':
                $title            = $Project->getConfig('settings.page.productLandingPage.title');
                $short            = $Project->getConfig('settings.page.productLandingPage.short');
                $titleAndShortPos = $Project->getConfig('settings.page.productLandingPage.titleAndShortPos');
                $header           = $Project->getConfig('settings.page.productLandingPage.header');
                $breadcrumb       = $Project->getConfig('settings.page.productLandingPage.breadcrumb');
                break;

            case 'layout/noSidebar':
                $title            = $Project->getConfig('settings.page.noSidebar.title');
                $short            = $Project->getConfig('settings.page.noSidebar.short');
                $titleAndShortPos = $Project->getConfig('settings.page.noSidebar.titleAndShortPos');
                $header           = $Project->getConfig('settings.page.noSidebar.header');
                $breadcrumb       = $Project->getConfig('settings.page.noSidebar.breadcrumb');
                break;

            case 'layout/noSidebarThin':
                $title            = $Project->getConfig('settings.page.noSidebarThin.title');
                $short            = $Project->getConfig('settings.page.noSidebarThin.short');
                $titleAndShortPos = $Project->getConfig('settings.page.noSidebarThin.titleAndShortPos');
                $header           = $Project->getConfig('settings.page.noSidebarThin.header');
                $breadcrumb       = $Project->getConfig('settings.page.noSidebarThin.breadcrumb');
                break;

            case 'layout/leftSidebar':
                $title            = $Project->getConfig('settings.page.leftSidebar.title');
                $short            = $Project->getConfig('settings.page.leftSidebar.short');
                $titleAndShortPos = $Project->getConfig('settings.page.leftSidebar.titleAndShortPos');
                $header           = $Project->getConfig('settings.page.leftSidebar.header');
                $breadcrumb       = $Project->getConfig('settings.page.leftSidebar.breadcrumb');
                break;

            case 'layout/rightSidebar':
                $title            = $Project->getConfig('settings.page.rightSidebar.title');
                $short            = $Project->getConfig('settings.page.rightSidebar.short');
                $titleAndShortPos = $Project->getConfig('settings.page.rightSidebar.titleAndShortPos');
                $header           = $Project->getConfig('settings.page.rightSidebar.header');
                $breadcrumb       = $Project->getConfig('settings.page.rightSidebar.breadcrumb');
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

        /* site own title and description position settings */
        switch ($params['Site']->getAttribute('site.settings.pageTitleAndShortPos')) {
            case 'header':
                $titleAndShortPos = 'header';
                break;
            case 'page':
                $titleAndShortPos = 'page';
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

        $showStart = $Project->getConfig('settings.nav.showStart');

        /* site own show / hide start entry in nav */
        switch ($params['Site']->getAttribute('site.settings.pageShowStart')) {
            case 'show':
                $showStart = true;
                break;
            case 'hide':
                $showStart = false;
        }

        $settingsCSS       = include 'settings.css.php';
        $settingsCssInline = '';
        $binDir            = OPT_DIR . 'quiqqer/template-tailwindcss/bin/css';

        // todo mit mor besprechen
        if (is_dir($binDir) === 'huhu') {
            file_put_contents($binDir . '/settings.css', $settingsCSS);
        } else {
            $settingsCssInline = '<style>' . $settingsCSS . '</style>';
        }

        // disable title and short in site content - do not show it twice
        if ($titleAndShortPos == 'header') {
            $Template->setAttribute('content-header', false);
        }

        $config += [
            'quiTplType'         => $Project->getConfig('templateTailwindCss.settings.standardType'),
            'typeClass'          => 'type-' . str_replace(['/', ':'], '-', $params['Site']->getAttribute('type')),
            'pageTitle'          => $title,
            'pageShort'          => $short,
            'titleAndShortPos'   => $titleAndShortPos,
            'pageHeader'         => $header,
            'pageBreadcrumb'     => $breadcrumb,
            'showStart'          => $showStart,
            'settingsCssInline' => $settingsCssInline
        ];

        // set cache
        QUI\Cache\Manager::set(
            'quiqqer/templateTailwindCss/' . $params['Site']->getId(),
            $config
        );

        return $config;
    }

    /**
     * Get the latest parent of the given page and specyfic layout type
     *
     * @param $Site \QUI\Projects\Site
     * @param $layout - layout type, if false --> there is no parent with this layout.
     *
     * @return \QUI\Projects\Site|bool
     */
    public static function getFirstSiteOfLayout($Site, $layout)
    {
        if (!$Site && $Site instanceof QUI\Projects\Site) {
            return false;
        }

        if (!$layout) {
            return false;
        }

        if ($Site->getParent()) {
            $Parent  = $Site->getParent();
            $AppPage = false;

            if ($Site->getAttribute('layout') === $layout) {
                $AppPage = $Site;
            }

            $ParentSite = self::getFirstSiteOfLayout($Parent, $layout);

            if ($ParentSite) {
                return $ParentSite;
            }

            return $AppPage;
        }

        return false;
    }
}
