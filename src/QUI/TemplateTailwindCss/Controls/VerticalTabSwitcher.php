<?php

/**
 * This file contains QUI\Bricks\Controls\VerticalTabSwitcher
 */

namespace QUI\TemplateTailwindCss\Controls;

use QUI;

/**
 * Class CustomerReviews
 *
 * @package quiqqer/bricks
 */
class VerticalTabSwitcher extends QUI\Control
{
    /**
     * constructor
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        // default options
        $this->setAttributes([
            'class'     => 'quiqqer-verticalTabSwitcher',
            'qui-class' => 'package/quiqqer/template-tailwindcss/bin/Controls/VerticalTabSwitcher',
            'navTitle'  => false,
            'imagePos'  => 'top',
            'entries'   => []
        ]);

        parent::__construct($attributes);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \QUI\Control::create()
     */
    public function getBody()
    {
        $Engine  = QUI::getTemplateManager()->getEngine();
        $entries = $this->getAttribute('entries');

        if (is_string($entries)) {
            $entries = json_decode($entries, true);
        }



        $Engine->assign([
            'this'       => $this,
            'entries'    => $entries,
            'navTitle' => $this->getAttribute('navTitle'),
            'imagePos' => $this->getAttribute('imagePos')
        ]);

        $this->addCSSFile(dirname(__FILE__) . '/VerticalTabSwitcher.css');


        return $Engine->fetch(dirname(__FILE__) . '/VerticalTabSwitcher.html');
    }
}
