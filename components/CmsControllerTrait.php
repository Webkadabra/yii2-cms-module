<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2018-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */

namespace webkadabra\yii\modules\cms\components;

use webkadabra\yii\modules\cms\models\CmsContentBlock;
use webkadabra\yii\modules\cms\models\CmsDocumentVersion;
use webkadabra\yii\modules\cms\models\CmsRoute;
use yii;

trait CmsControllerTrait
{
    /** @var CmsRoute|CmsDocumentVersion */
    public $node;

    protected function parseBlock($in) {
        return $in;
    }

    public function printBlock($name, $capture = false) {
        $this->preparePageBlocks();
        $output = '';
        if (!empty($this->contentBlocks[$name])) {
            foreach ($this->contentBlocks[$name] as $blockKey => $blockContent) {
                if ($capture) {
                    $output .= $this->parseBlock($blockContent);
                } else {
                    echo $this->parseBlock($blockContent);
                }
            }
        }
        if (!empty($this->cmsCssBlocks[$name])) {
            foreach ($this->cmsCssBlocks[$name] as $blockKey => $blockConfig) {
                if (isset($blockConfig['type']) && isset($blockConfig['value'])) {
                    if ($blockConfig['type'] == CmsContentBlock::TYPE_CSS_INLINE) {
                        $this->view->registerCss($blockConfig['value']);
                    } else if ($blockConfig['type'] == CmsContentBlock::TYPE_CSS_LINK) {
                        $this->view->registerCssFile($blockConfig['value'], [
                            'depends' => [
                                'frontend\assets\AppAsset'
                            ]]);
                    }
                }
            }
        }
        if (!empty($this->cmsJsBlocks[$name])) {
            foreach ($this->cmsJsBlocks[$name] as $blockKey => $blockConfig) {
                if (isset($blockConfig['type']) && isset($blockConfig['value'])) {
                    if ($blockConfig['type'] == CmsContentBlock::TYPE_JAVASCRIPT_FILE) {
                        $this->view->registerJsFile($blockConfig['value'], ['position' => yii\web\View::POS_END]);
                    } else if ($blockConfig['type'] == CmsContentBlock::TYPE_JAVASCRIPT_INLINE) {
                        $this->view->registerJs($blockConfig['value']);
                    }
                }
            }
        }
        if ($capture) {
            return $output;
        }

        return null;
    }

    public function hasContentBlock($name) {
        $this->preparePageBlocks();
        return !empty($this->contentBlocks[$name]);
    }

    public $contentBlocks;
    protected $cmsCssBlocks = array();
    protected $cmsJsBlocks = array();
    protected $pagePrepared = false;

    /**
     * @todo refactor as trait
     */
    protected function preparePageBlocks() {
        if ($node = $this->getCmsNode()) {
            if ($this->pagePrepared === false) {
                // do not use `asArray()` while looking up the content, because in that case model will not have events raised and translations would not work
                if (Yii::$app->cms->enableMultiLanguage) {
                    $data = $node->getLocalizedContentBlocks()->all();
                } else {
                    $data = $node->getContentBlocks()->all();
                }
                foreach ($data as $value) {
                    $blockName = (isset($value['contentBlockName']) ? $value['contentBlockName'] : $i++);
                    if ($value['contentType'] == CmsContentBlock::TYPE_HTML)
                        $this->contentBlocks[$blockName][] = $value['content'];
                    else if (in_array($value['contentType'], [CmsContentBlock::TYPE_CSS_LINK, CmsContentBlock::TYPE_CSS_INLINE]))
                        $this->cmsCssBlocks[$blockName][] = [
                            'type' => $value['contentType'],
                            'value' => $value['content'],
                        ];
                    else if (in_array($value['contentType'], [CmsContentBlock::TYPE_JAVASCRIPT_FILE, CmsContentBlock::TYPE_JAVASCRIPT_INLINE]))
                        $this->cmsJsBlocks[$blockName][] = [
                            'type' => $value['contentType'],
                            'value' => $value['content'],
                        ];
                }
                $this->pagePrepared = true;
            }
        }
    }

    public function hasCmsNode() {
        if (isset(Yii::$app->params['view_preview_node']) && Yii::$app->params['view_preview_node']) {
            return Yii::$app->params['view_preview_node'];
        }
        return isset(Yii::$app->params['view_node']) && Yii::$app->params['view_node'];
    }

    /**
     * @return CmsRoute
     */
    public function getCmsNode() {
        if (isset(Yii::$app->params['view_preview_node']) && Yii::$app->params['view_preview_node']) {
            return Yii::$app->params['view_preview_node'];
        }
        return isset(Yii::$app->params['view_node']) && Yii::$app->params['view_node'] ? Yii::$app->params['view_node'] : null;
    }

    public function getViewName($base) {
        return isset(Yii::$app->params['view_template']) ? Yii::$app->params['view_template'] : $base;
    }
}
