<?php

namespace webkadabra\yii\modules\cms\models;

/**
 * This is the ActiveQuery class for [[CmsRoute]].
 * @author Sergii Gamaiunov <devkadabra@gmail.com>
 * @package webkadabra\yii\modules\cms\models
 * @see CmsRoute
 */
class CmsRouteQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

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