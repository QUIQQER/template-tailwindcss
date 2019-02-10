<?php

$Convert = new \QUI\Utils\Convert();


/**
 * colors
 */
$colorFooterBackground = '#414141';
$colorFooterFont       = '#D1D1D1';
$colorMain             = '#dd151b';
$buttonFontColor       = '#ffffff';
$colorFooterLinks      = '#E6E6E6';
$colorMainContentBg    = '#ffffff';
$colorMainContentFont  = '#5d5d5d';

$navBarMainColorLighter = $Convert->colorBrightness($colorFooterBackground, 0.9);


if ($Project->getConfig('templateTailwindCss.settings.colorMainContentFont')) {
    $colorMainContentFont = $Project->getConfig('templateTailwindCss.settings.colorMainContentFont');
}

$navBarHeight = (int)$Project->getConfig('templateTailwindCss.settings.navBarHeight');
$navPos = $Project->getConfig('templateTailwindCss.settings.navPos');
$colorMainButton = $Convert->colorBrightness($colorMain, 0.7);


/* template tailwind css */
$headerHeight = (int)$Project->getConfig('settings.header.height');
$headerImagePosition = $Project->getConfig('settings.header.imagePosition');

QUI\System\Log::writeRecursive('<-------------------------------------->' . $headerImagePosition);
ob_start();

?>
/**
 * Farbeinstellungen
 */


.color-main {
    /*color: */<?php //echo $colorMain; ?>/*;*/
}

body,
.mainFontColor {
    /*color: */<?php //echo $colorMainContentFont; ?>/* !important;*/
}

<?php if ($headerHeight) { ?>
.page-header {
    height: <?php echo $headerHeight; ?>px;
    background-position: <?php echo $headerImagePosition; ?>;
}


<?php };
?>

/**
 * Menüposition
 */
<?php  if ($navPos == 'fix') { ?>
.header-bar {
    /*position: fixed !important;*/
}

.body-container {
    /*top: */<?php //echo $navBarHeight; ?>/*px;*/
}

<?php }; ?>


<?php

$settingsCSS = ob_get_contents();
ob_end_clean();

return $settingsCSS;

?>
