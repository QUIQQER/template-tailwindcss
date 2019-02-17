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
 * body class
 */
$bodyClass = '';
$startPage = false;

switch ($Template->getLayoutType()) {
    case 'layout/startPage':
        $bodyClass = 'start-page';
        $startPage = true;
        break;

    case 'layout/productLandingPage':
        $bodyClass = 'product-landing-page';
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
 * Mega menu
 */
$MegaMenu = false;

if ($Template->getAttribute('template-header')) {
    /**
     * Mega menu
     */
    $MegaMenu = new QUI\Menu\MegaMenu(array(
        'showStart' => false
    ));
}
$templateSettings['MegaMenu'] = $MegaMenu;


$templateSettings['BricksManager'] = QUI\Bricks\Manager::init();
$templateSettings['Breadcrumb']    = $Breadcrumb;
$templateSettings['bodyClass']     = $bodyClass;
$templateSettings['startPage']     = $startPage;


/* neu for tailwindcss */
/* Logo in menu */
$logoAlt = "QUIQQER Project";
$logoUrl = $Project->getMedia()->getPlaceholder();

if ($Project->getMedia()->getLogoImage()) {
    $Logo    = $Project->getMedia()->getLogoImage();
    $logoAlt     = $Logo->getAttribute('title');
    $logoUrl = $Logo->getSizeCacheUrl(400, 300);
}

$templateSettings['logoUrl'] = $logoUrl;
$templateSettings['logoAlt'] = $logoAlt;

$templateSettings['navBackground']    = '';


$Engine->assign($templateSettings);
