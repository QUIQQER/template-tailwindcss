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
 * Mega menu
 */
$MegaMenu = false;

if ($Template->getAttribute('template-header')) {
    /**
     * Mega menu
     */
    $Start = false;
    $Start = $Site;

    QUI\System\Log::writeRecursive('<-------------------------------------->');
    QUI\System\Log::writeRecursive(QUI\TemplateTailwindCss\Utils::getFirstSiteOfType($Site, 'layout/productLandingPage'));

    /*if ($Start->getParent()) {
//        QUI\System\Log::writeRecursive($Start->getParent());
        QUI\System\Log::writeRecursive('hzuhuhuhuhuhuhuhuhu');
    } else {
        QUI\System\Log::writeRecursive("nie ma wiecej rodzicow");
    }

    if ($productPageMenu) {
        $Start = $Site;
        QUI\System\Log::writeRecursive('<-------------------------------------->');
//        QUI\System\Log::writeRecursive($Start->getParent()->getAttribute('layout'));

    }*/

    $MegaMenu = new QUI\Menu\MegaMenu([
        'showStart' => false,
        'Start'     => $Start->getParent()
    ]);

}
$templateSettings['MegaMenu'] = $MegaMenu;


$templateSettings['BricksManager'] = QUI\Bricks\Manager::init();
$templateSettings['Breadcrumb']    = $Breadcrumb;
$templateSettings['bodyClass']     = $bodyClass;


/* neu for tailwindcss */
/* Logo in menu */
$logoAlt = "QUIQQER Project";
$logoUrl = $Project->getMedia()->getPlaceholder();

if ($Project->getMedia()->getLogoImage()) {
    $Logo    = $Project->getMedia()->getLogoImage();
    $logoAlt = $Logo->getAttribute('title');
    $logoUrl = $Logo->getSizeCacheUrl(400, 300);
}

$templateSettings['logoUrl'] = $logoUrl;
$templateSettings['logoAlt'] = $logoAlt;

$templateSettings['navBackground'] = '';


$Engine->assign($templateSettings);
