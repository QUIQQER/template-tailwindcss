<?php

/**
 * This file contains QUI\TemplateTailwindCss\Controls\Separator
 */

namespace QUI\TemplateTailwindCss\Controls;

use QUI;

/**
 * Class Separator
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
            'class'           => 'quiqqer-control-separator-container',
            'design'          => 'simple',
            'icon'            => 'fa-star-o',
            'backgroundImage' => null,
            'overlayColor'    => '#222',
            'overlayOpacity'  => 0.5,
            'colorScheme'     => false
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

//        $this->setAttribute('class', $this->getDesignCSSClasses());
        $css = $this->getDesignCSSClasses();

        $icon = $this->getAttribute('icon');

        // workaround -> input erlaubt auch Bilder, benötigt wird nur FontAwesome
        if (strpos($icon, 'fa') !== 0) {
            $icon = null;
        }

        $overlayColor   = null;
        $overlayOpacity = null;
        $colorScheme    = false;

        if ($this->getAttribute('overlayColor')) {
            $overlayColor = $this->getAttribute('overlayColor');
        }

        if ($this->getAttribute('overlayOpacity') && $this->getAttribute('overlayOpacity') >= 0 && $this->getAttribute('overlayOpacity') <= 1) {
            $overlayOpacity = $this->getAttribute('overlayOpacity');
        }

        if ($this->getAttribute('colorScheme')) {
            $colorScheme = $this->getAttribute('colorScheme');
        }


        $Engine->assign([
            'this'           => $this,
            'css'            => $css,
            'icon'           => $icon,
            'overlayColor'   => $overlayColor,
            'overlayOpacity' => $overlayOpacity,
            'colorScheme' => $colorScheme
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

            case 'border':
                $cssClasses = 'quiqqer-control-separator quiqqer-control-separator__border rounded-lg border p-8';
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

            case 'light':
                $cssClasses = 'quiqqer-control-separator quiqqer-control-separator__light rounded-lg shadow-lg';
                break;

            case 'backgroundImage':
                $cssClasses = 'quiqqer-control-separator quiqqer-control-separator__background rounded-lg';
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
