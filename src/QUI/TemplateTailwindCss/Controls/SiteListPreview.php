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
     * Path to list html file
     *
     * @var string
     */
    private $listHTMLFile = '';

    /**
     * Path to list css file
     *
     * @var string
     */
    private $listCSSFile = '';

    /**
     * Path to preview html file
     *
     * @var string
     */
    private $previewHTMLFile = '';

    /**
     * Path to preview css file
     *
     * @var string
     */
    private $previewCSSFile = '';

    /**
     * @var QUI\Interfaces\Template\EngineInterface
     */
    private $Engine = null;

    /**
     * constructor
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        // default options
        $this->setAttributes([
            'nodeName'              => 'section',
            'class'                 => 'qui-control-siteListPreview',
            'qui-class'             => 'package/quiqqer/template-tailwindcss/bin/Controls/SiteListPreview',
            'previewWidth'          => 700,
            'limit'                 => 3,
            'order'                 => 'c_date DESC',
            'Site'                  => false,
            'site'                  => false,
            // if true returns all sites of certain type
            'byType'                => false,
            'where'                 => false,
            'itemtype'              => 'http://schema.org/ItemList',
            'child-itemtype'        => 'https://schema.org/ListItem',
            'child-itemprop'        => 'itemListElement',
            // list layout / design
            'listTemplate'          => 'default',
            // preview layout / design
            'previewTemplate'       => 'default',
            'additionalText'        => '',

            // overwrite
            // Custom list template (path to html file); overwrites "listTemplate"
            'customListTemplate'    => false,
            // Custom list template css (path to css file); overwrites "listTemplate"
            'customListCss'         => false,
            // Custom preview template (path to html file); overwrites "listTemplate"
            'customPreviewTemplate' => false,
            // Custom preview template css (path to css file); overwrites "listTemplate"
            'customPreviewCss'      => false,

            // list of sites to display,
            'children'              => false
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
    public function getBody(): string
    {
        try {
            $this->Engine = QUI::getTemplateManager()->getEngine();
        } catch (QUI\Exception $Exception) {
            QUI\System\Log::writeException($Exception);

            return '';
        }

        if (!$this->getAttribute('site')) {
            return '';
        }

        $this->setJavaScriptControlOption('previewwidth', $this->getAttribute('previewWidth'));

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

        $additionalText = false;

        if ($this->getAttribute('additionalText') && \strlen($this->getAttribute('additionalText')) > 0) {
            $additionalText = $this->getAttribute('additionalText');
        }

        $this->Engine->assign([
            'this'           => $this,
            'Site'           => $this->getSite(),
            'Project'        => $this->getProject(),
            'children'       => $children,
            'MetaList'       => new QUI\Controls\Utils\MetaList(),
            'Events'         => $this->Events,
            'additionalText' => $additionalText
        ]);

        switch ($this->getAttribute('listTemplate')) {
            case 'default':
                $this->listCSSFile  = \dirname(__FILE__).'/SiteListPreview.List.'.$this->getAttribute('listTemplate').'.css';
                $this->listHTMLFile = \dirname(__FILE__).'/SiteListPreview.List.'.$this->getAttribute('listTemplate').'.html';
                break;

            default:
                $this->listCSSFile  = \dirname(__FILE__).'/SiteListPreview.List.default.css';
                $this->listHTMLFile = \dirname(__FILE__).'/SiteListPreview.List.default.html';
                break;
        }

        switch ($this->getAttribute('previewTemplate')) {
            case 'circle':
                $this->previewCSSFile  = \dirname(__FILE__).'/SiteListPreview.Preview.'.$this->getAttribute('previewTemplate').'.css';
                $this->previewHTMLFile = \dirname(__FILE__).'/SiteListPreview.Preview.'.$this->getAttribute('previewTemplate').'.html';
                break;

            default:
                $this->previewCSSFile  = \dirname(__FILE__).'/SiteListPreview.Preview.circle.css';
                $this->previewHTMLFile = \dirname(__FILE__).'/SiteListPreview.Preview.circle.html';
                break;
        }

        // load custom template (if set)
        $this->setCustomTemplates();

        $this->addCSSFiles([
            $this->getCSSFile(),
            $this->getPreviewCSSFile()
        ]);

        return $this->Engine->fetch($this->getHTMLFile());
    }

    /**
     * Get path to css file
     *
     * @return string
     */
    public function getCSSFile(): string
    {
        return $this->listCSSFile;
    }

    /**
     * Get path to html file
     *
     * @return string
     */
    public function getHTMLFile(): string
    {
        return $this->listHTMLFile;
    }

    /**
     * Get path to preview css file
     *
     * @return string
     */
    public function getPreviewCSSFile(): string
    {
        return $this->previewCSSFile;
    }

    /**
     * Get path to preview html file
     *
     * @return string
     */
    public function getPreviewHTMLFile(): string
    {
        return $this->previewHTMLFile;
    }

    /**
     * Get html template
     *
     * @return string
     */
    public function getPreview($params = []): string
    {
        $this->Engine->assign($params);

        return $this->Engine->fetch($this->getPreviewHTMLFile());
    }

    /**
     * @return mixed|QUI\Projects\Site
     */
    protected function getSite(): QUI\Projects\Site
    {
        if ($this->getAttribute('Site')) {
            return $this->getAttribute('Site');
        }

        $Site = QUI::getRewrite()->getSite();

        $this->setAttribute('Site', $Site);

        return $Site;
    }

    /**
     * Set custom files
     */
    protected function setCustomTemplates()
    {
        // list template
        if ($this->getAttribute('customListTemplate')
            && \file_exists($this->getAttribute('customListTemplate'))
        ) {
            if ($this->getAttribute('customListCss')
                && \file_exists($this->getAttribute('customListCss'))
            ) {
                $this->listCSSFile = $this->getAttribute('customListCss');
            }

            $this->listHTMLFile = $this->getAttribute('customListTemplate');
        }

        // preview template
        if ($this->getAttribute('customPreviewTemplate')
            && \file_exists($this->getAttribute('customPreviewTemplate'))
        ) {
            if ($this->getAttribute('customPreviewCss')
                && \file_exists($this->getAttribute('customPreviewCss'))
            ) {
                $this->listCSSFile = $this->getAttribute('customPreviewCss');
            }

            $this->listHTMLFile = $this->getAttribute('customPreviewTemplate');
        }
    }
}
