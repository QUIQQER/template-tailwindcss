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

        $LoginControl = new QUI\Users\Controls\Login();

        $Engine->assign([
            'LoginControl' => $LoginControl
        ]);

        return $Engine->fetch(dirname(__FILE__) . '/LoginAndRegister.html');
    }
}
