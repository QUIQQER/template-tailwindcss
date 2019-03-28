<?php

$Locale = QUI::getLocale();

/**
 * Emotion
 */
QUI\Utils\Site::setRecursivAttribute($Site, 'image_emotion');

// Inhaltsverhalten
if ($Site->getAttribute('templateTailwindCss.showTitle') ||
    $Site->getAttribute('templateTailwindCss.showShort')
) {
    $Template->setAttribute('content-header', false);
}

/**
 * Breadcrumb
 */
$Breadcrumb = new QUI\Controls\Breadcrumb();

/**
 * Template config
 */
$templateSettings = QUI\TemplateTailwindCss\Utils::getConfig([
    'Project'  => $Project,
    'Site'     => $Site,
    'Template' => $Template
]);

/**
 * body class, productPageMenu
 */
$bodyClass       = '';
$productPageMenu = false;
switch ($Template->getLayoutType()) {
    case 'layout/startPage':
        $bodyClass = 'start-page';
        break;

    case 'layout/productLandingPage':
        $bodyClass       = 'product-landing-page';
        $productPageMenu = true;
        break;

    case 'layout/noSidebar':
        $bodyClass = 'no-sidebar';
        break;

    case 'layout/noSidebarThin':
        $bodyClass = 'no-sidebar-thin';
        break;

    case 'layout/rightSidebar':
        $bodyClass = 'right-sidebar';
        break;

    case 'layout/leftSidebar':
        $bodyClass = 'left-sidebar';
        break;
}

/**
 * Dropdown Language switch
 */
/*$showDropDownFlag = true;
$DropDownFlag     = '';
$showFlags        = false;
$showText         = false;

switch ($Project->getConfig('templatePresentation.settings.dropdownLangNav')) {
    case 'flag':
        $showFlags        = true;
        $showDropDownFlag = true;
        break;

    case 'text':
        $showText         = true;
        $showDropDownFlag = true;
        break;

    case 'flagAndText':
        $showFlags        = true;
        $showText         = true;
        $showDropDownFlag = true;
        break;
}

if ($showDropDownFlag) {

    $DropDown = new QUI\Bricks\Controls\LanguageSwitches\DropDown(array(
        'Site'      => $Site,
        'showFlags' => $showFlags,
        'showText'  => $showText
    ));

    $DropDownFlag = $DropDown->create();
}*/

$LangSwitch = new QUI\Bricks\Controls\LanguageSwitches\Flags([
    'Site'      => $Site,
    'showFlags' => true,
    'showText'  => false
]);

$templateSettings['LangSwitch'] = $LangSwitch;

/**
 * Logo in menu
 */
$logoText   = "QUIQQER Project";
$logoUrl    = $Project->getMedia()->getPlaceholder();
$logoUrlAlt = false; // second url logo for different background color (see template settings "altLogo")

// Project logo (comes from PROJECT settings)
if ($Project->getMedia()->getLogoImage()) {
    $Logo     = $Project->getMedia()->getLogoImage();
    $logoText = $Logo->getAttribute('title');
    $logoUrl  = $Logo->getSizeCacheUrl(400, 300);
}

// Second logo (comes from TEMPLATE settings)
if ($Project->getConfig('settings.nav.logoAlt')) {
    $LogoAlt = QUI\Projects\Media\Utils::getImageByUrl(
        $Project->getConfig('settings.nav.logoAlt')
    );

    $logoUrlAlt = $LogoAlt->getSizeCacheUrl(400, 300);
}

// Project logo (comes from SITE settings)
if ($Site->getAttribute('site.settings.pageLogo')) {
    $Logo = QUI\Projects\Media\Utils::getImageByUrl(
        $Site->getAttribute('site.settings.pageLogo')
    );

    $logoText = $Logo->getAttribute('title');
    $logoUrl  = $Logo->getSizeCacheUrl(400, 300);

    // avoid the project logo showing up in .header-bar-alt
    $logoUrlAlt = false;
}

// Second logo (comes form SITE settings)
if ($Site->getAttribute('site.settings.pageLogoAlt')) {
    $LogoAlt = QUI\Projects\Media\Utils::getImageByUrl(
        $Site->getAttribute('site.settings.pageLogoAlt')
    );

    $logoUrlAlt = $LogoAlt->getSizeCacheUrl(400, 300);
}

// alternate target url by click on logo
$altLogoTargetUrl = $Site->getAttribute('site.settings.pageLogoTargetUrl');

if ($altLogoTargetUrl) {
    try {
        if (QUI\Projects\Site\Utils::isSiteLink($altLogoTargetUrl)) {
            $Wanted = \QUI\Projects\Site\Utils::getSiteByLink($altLogoTargetUrl);

            // so, we get the site with vhosts, and url dir
            $Output = new QUI\Output();

            $altLogoTargetUrl = $Output->getSiteUrl([
                'site' => $Wanted
            ]);
        } else {
            $parts = parse_url($altLogoTargetUrl);

            if (!isset($parts['host']) || empty($parts['host'])) {
                $altLogoTargetUrl = HOST . $altLogoTargetUrl;
            }

            if (!isset($parts['scheme']) && strpos($siteUrl, '//') !== 0) {
                $altLogoTargetUrl = '//' . $altLogoTargetUrl;
            }
        }
    } catch (QUI\Exception $Exception) {
    }
}

/**
 * Mega menu
 */
$MegaMenu = false;

if ($Template->getAttribute('template-header')) {
    /**
     * If the current site (or one of the parents)
     * has layout type layout/productLandingPage,
     * set the start id for the mega menu to the page (see README.md -> Features)
     */
    $ProductPage = QUI\TemplateTailwindCss\Utils::getFirstSiteOfLayout(
        $Site,
        'layout/productLandingPage'
    );

    // Mega menu
    $MegaMenu = new QUI\Menu\MegaMenu([
        'showStart' => $templateSettings['showStart'],
        'Start'     => $ProductPage
    ]);


    if ($ProductPage) {
        // product page
        $startPageUrl   = $ProductPage->getUrlRewritten();
        $startPageTitle = $ProductPage->getAttribute('title');

    } else {
        // startpage
        $StartPage      = $Project->get(1);
        $startPageUrl   = $StartPage->getUrlRewritten();
        $startPageTitle = $StartPage->getAttribute('title');
    }

    $hideLogo          = '';
    $alternateLogoHtml = '';
    if ($logoUrlAlt) {
        $hideLogo          = 'hide';
        $alternateLogoHtml = '
            <img src="' . $logoUrlAlt . '" alt="' . $logoText . '" class="header-bar-logo-secondary" />
        ';
    }

    $EngineForMenu = QUI::getTemplateManager()->getEngine();

    $EngineForMenu->assign([
        'startPageUrl'      => $startPageUrl,
        'startPageTitle'    => $startPageTitle,
        'logoUrl'           => $logoUrl,
        'logoText'          => $logoText,
        'hideLogo'          => $hideLogo,
        'alternateLogoHtml' => $alternateLogoHtml
    ]);

    $MegaMenu->prependHTML($EngineForMenu->fetch(dirname(__FILE__) . '/template/menu/menuPrefix.html'));
    $MegaMenu->appendHTML($EngineForMenu->fetch(dirname(__FILE__) . '/template/menu/menuSuffix.html'));
}

$templateSettings['BricksManager']    = QUI\Bricks\Manager::init();
$templateSettings['Breadcrumb']       = $Breadcrumb;
$templateSettings['bodyClass']        = $bodyClass;
$templateSettings['MegaMenu']         = $MegaMenu;
$templateSettings['logoUrl']          = $logoUrl;
$templateSettings['logoText']         = $logoText;
$templateSettings['altLogoTargetUrl'] = $altLogoTargetUrl;
$templateSettings['logoUrlAlt']       = $logoUrlAlt;
$templateSettings['navBackground']    = '';

$Engine->assign($templateSettings);
