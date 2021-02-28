<?php

/**
 * This file contains QUI\TemplateTailwindCss\Controls\SiteListPreview
 */

namespace QUI\TemplateTailwindCss\Controls;

use QUI;
use QUI\Projects\Site\Utils;

/**
 * Class SiteListPreview
 *
 * @package quiqqer/template-tailwindcss
 */
class SiteListPreview extends QUI\Control
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
            'class'           => 'qui-control-siteListPreview',
            'limit'           => 3,
            'order'           => 'c_date DESC',
            'Site'            => false,
            'site'            => false,
            // if true returns all sites of certain type
            'byType'          => false,
            'where'           => false,
            'itemtype'        => 'http://schema.org/ItemList',
            'child-itemtype'  => 'https://schema.org/ListItem',
            'child-itemprop'  => 'itemListElement',
            // layout / design
            'display'         => 'default',
            // Custom children template (path to html file); overwrites "display"
            'displayTemplate' => false,
            // Custom children template css (path to css file); overwrites "display"
            'displayCss'      => false,
            'nodeName'        => 'section',
            // list of sites to display,
            'children'        => false
        ]);

        parent::__construct($attributes);
    }

    /**
     * Return the inner body of the element
     * Can be overwritten
     *
     * @return String
     *
     * @throws QUI\Exception
     */
    public function getBody()
    {
        try {
            $Engine = QUI::getTemplateManager()->getEngine();
        } catch (QUI\Exception $Exception) {
            QUI\System\Log::writeException($Exception);

            return '';
        }

        if (!$this->getAttribute('site')) {
            return '';
        }

        $limit    = $this->getAttribute('limit');
        $Project  = $this->getProject();
        $children = [];
        $where    = $this->getAttribute('where');

        if (!$limit) {
            $limit = 3;
        }

        if (empty($where)) {
            $where = [];
        }

        $where['active'] = 1;

        if ($this->getAttribute('site')) {
            // for bricks
            $children = Utils::getSitesByInputList($Project, $this->getAttribute('site'), [
                'where' => $where,
                'limit' => $limit,
                'order' => $this->getAttribute('order')
            ]);
        } elseif ($this->getAttribute('children')) {
            $children = $this->getAttribute('children');
        }

        if (!\is_array($children) || \count($children) < 1) {
            return '';
        }

        $Engine->assign([
            'this'     => $this,
            'Site'     => $this->getSite(),
            'Project'  => $this->getProject(),
            'children' => $children,
            'MetaList' => new QUI\Controls\Utils\MetaList(),
            'Events'   => $this->Events
        ]);

        // load custom template (if set)
        if ($this->getAttribute('displayTemplate')
            && \file_exists($this->getAttribute('displayTemplate'))
        ) {
            if ($this->getAttribute('displayCss')
                && \file_exists($this->getAttribute('displayCss'))
            ) {
                $this->addCSSFile($this->getAttribute('displayCss'));
            }

            return $Engine->fetch($this->getAttribute('displayTemplate'));
        }

        switch ($this->getAttribute('display')) {
            default:
            case 'default':
                $css      = \dirname(__FILE__).'/SiteListPreview.css';
                $template = \dirname(__FILE__).'/SiteListPreview.html';
                break;
        }

        $this->addCSSFile($css);

        return $Engine->fetch($template);
    }

    public function getTemplate()
    {
        /*switch ($this->getAttribute('display')) {
            default:
            case 'default':
                $css      = \dirname(__FILE__).'/SiteListPreview.css';
                $template = \dirname(__FILE__).'/SiteListPreview.html';
                break;
        }

        return $template;*/
    }


    /**
     * @return mixed|QUI\Projects\Site
     */
    protected function getSite()
    {
        if ($this->getAttribute('Site')) {
            return $this->getAttribute('Site');
        }

        $Site = QUI::getRewrite()->getSite();

        $this->setAttribute('Site', $Site);

        return $Site;
    }
}
