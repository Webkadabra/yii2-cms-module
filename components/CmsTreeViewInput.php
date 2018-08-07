<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2018-present Sergii Gamaiunov <hello@webkadabra.com>
 * All rights reserved.
 */

namespace webkadabra\yii\modules\cms\components;

use kartik\base\Config;
use kartik\tree\Module;
use kartik\tree\TreeViewInput;

/**
 * Class CmsTreeViewInput for manipulaiton of CMS tree, overriding module id for tree manager to use `treemanagercms`
 *
 * @package webkadabra\yii\modules\cms\components
 */
class CmsTreeViewInput extends TreeViewInput
{
    /**
     * override treemanager module id
     * @inheritdoc
     */
    public function renderTree()
    {
        $this->_module = Config::getModule('treemanagercms');
        return parent::renderTree();
    }
}