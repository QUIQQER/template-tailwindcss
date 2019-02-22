<?php

$Convert = new \QUI\Utils\Convert();


/**
 * colors
 */
$colorPrimary = false;
$colorButtonPrimary = false;
$colorButtonSecondary = false;
$colorButtonSuccess = '';
$colorButtonDanger = '';
$colorButtonWarning = '';
$colorButtonInfo = '';
$colorButtonLight = '';
$colorButtonDark = '';
$colorButtonLink = '';
$colorButtonInfo = '';
$colorButtonWhite = '';

//$navBarMainColorLighter = $Convert->colorBrightness($colorFooterBackground, 0.9);


if ($Project->getConfig('settings.color.button.primary')) {
    $colorButtonPrimary = $Project->getConfig('settings.color.button.primary');
}

if ($Project->getConfig('settings.color.button.secondary')) {
    $colorButtonSecondary = $Project->getConfig('settings.color.button.secondary');
}

$navBarHeight = (int)$Project->getConfig('templateTailwindCss.settings.navBarHeight');
$navPos = $Project->getConfig('templateTailwindCss.settings.navPos');
//$colorMainButton = $Convert->colorBrightness($colorMain, 0.7);


/* template tailwind css */
$headerHeight = (int)$Project->getConfig('settings.header.height');
$headerImagePosition = $Project->getConfig('settings.header.imagePosition');

ob_start();

?>
/*******************/
/* buttons: colors */
/*******************/
/* color: primary */
<?php if ($colorButtonPrimary) { ?>
.btn,
.btn-primary,
.button,
button,
.btn-primary {
    background-color: <?php echo $colorButtonPrimary; ?>;
    border-color: <?php echo $colorButtonPrimary; ?>;
}

.btn-primary-outline,
.btn-primary-outline:hover {
    background-color: transparent;
    color: <?php echo $colorButtonPrimary; ?>;
    border-color: <?php echo $colorButtonPrimary; ?>;
}
<?php };
?>

/* color: secondary */
<?php if ($colorButtonSecondary) { ?>
.btn-secondary {
    background-color: <?php echo $colorButtonSecondary; ?>;
    border-color: <?php echo $colorButtonSecondary; ?>;
}

.btn-secondary-outline,
.btn-secondary-outline:hover {
    background-color: transparent;
    color: <?php echo $colorButtonSecondary; ?>;
    border-color: <?php echo $colorButtonSecondary; ?>;
}
<?php };
?>


<?php if ($headerHeight) { ?>
.page-header {
    min-height: <?php echo $headerHeight; ?>px;
    background-position: <?php echo $headerImagePosition; ?>;
}

.page-header-inner {
    min-height: <?php echo $headerHeight; ?>px;
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
