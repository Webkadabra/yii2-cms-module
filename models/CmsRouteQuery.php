<?php

namespace webkadabra\yii\modules\cms\models;

use creocoder\nestedsets\NestedSetsQueryBehavior;

/**
 * This is the ActiveQuery class for [[CmsRoute]].
 * @author Sergii Gamaiunov <devkadabra@gmail.com>
 * @package webkadabra\yii\modules\cms\models
 * @see CmsRoute
 */
class CmsRouteQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return CmsRoute[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CmsRoute|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}