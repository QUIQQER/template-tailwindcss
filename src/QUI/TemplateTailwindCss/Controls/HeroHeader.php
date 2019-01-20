<?php

/**
 * This file contains QUI\TemplateTailwindCss\Controls\HeroHeader
 */

namespace QUI\TemplateTailwindCss\Controls;

use QUI;

/**
 * Class ProductGallery
 */
class HeroHeader extends QUI\Control
{
    /**
     * constructor
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        $this->setAttributes([
            'nodeName' => 'section',
            'class'    => 'heroHeader'
        ]);

        $this->addCSSFile(dirname(__FILE__) . '/HeroHeader.css');

        parent::__construct($attributes);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \QUI\Control::create()
     *
     * @throws QUI\Exception
     */
    public function getBody()
    {
        $Engine = QUI::getTemplateManager()->getEngine();

        $minHeight = '80vh';
        if ($this->getAttribute('minHeight')) {
            $minHeight = $this->getAttribute('minHeight');
        }

        $imageHeight = 300;
        if ($this->getAttribute('imageHeight')) {
            $imageHeight = $this->getAttribute('imageHeight');
        }

        $Engine->assign([
            'this'            => $this,
            'backgroundImage' => $this->getAttribute('backgroundImage'),
            'minHeight'       => $minHeight,
            'image'           => $this->getAttribute('image'),
            'imageHeight'     => $imageHeight
        ]);

        return $Engine->fetch(dirname(__FILE__) . '/HeroHeader.html');
    }
}
