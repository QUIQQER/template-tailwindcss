<?php

/**
 * This file contains QUI\TemplateTailwindCss\Controls\Separator
 */

namespace QUI\TemplateTailwindCss\Controls;

use QUI;

/**
 * Class Separator
 * 
 *
 * @package quiqqer/bricks
 */
class Separator extends QUI\Control
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
            'class'  => 'quiqqer-control-separator',
            'design' => 'simple',
            'icon'   => 'fa-star-o'
        ]);

        parent::__construct($attributes);

        $this->addCSSFile(
            dirname(__FILE__).'/Separator.css'
        );
    }

    /**
     * (non-PHPdoc)
     *
     * @see \QUI\Control::create()
     */
    public function getBody()
    {
        $Engine = QUI::getTemplateManager()->getEngine();

        $this->setAttribute('class', $this->getDesignCSSClasses());

        $icon = $this->getAttribute('icon');

        // workaround -> input erlaubt auch Bilder, benötigt wird nur FontAwesome
        if (strpos($icon, 'fa') !== 0) {
            $icon = null;
        }

        $Engine->assign([
            'this' => $this,
            'icon' => $icon
        ]);

        return $Engine->fetch(dirname(__FILE__).'/Separator.html');
    }

    /**
     * Get the css classes depend on design 
     * 
     * @return string - css classes
     */
    public function getDesignCSSClasses()
    {
        switch ($this->getAttribute('design')) {
            case 'simple':
            default:
                $cssClasses = 'quiqqer-control-separator quiqqer-control-separator__simple rounded-lg shadow-lg';
                break;

            case 'lines':
                $cssClasses = 'quiqqer-control-separator quiqqer-control-separator__lines';
                break;

            case 'quote':
                $cssClasses = 'quiqqer-control-separator quiqqer-control-separator__quote';
                break;

            case 'dark':
                $cssClasses = 'quiqqer-control-separator quiqqer-control-separator__dark rounded-lg shadow-lg';
                break;

            case 'iconLeft':
                $cssClasses = 'quiqqer-control-separator quiqqer-control-separator__icon quiqqer-control-separator__icon__left rounded-lg';
                break;

            case 'iconCenter':
                $cssClasses = 'quiqqer-control-separator quiqqer-control-separator__icon quiqqer-control-separator__icon__center rounded-lg';
                break;

            case 'iconRight':
                $cssClasses = 'quiqqer-control-separator quiqqer-control-separator__icon quiqqer-control-separator__icon__right rounded-lg';
                break;
        }
        
        return $cssClasses;
    }
}
