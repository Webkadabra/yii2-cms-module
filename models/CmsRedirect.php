<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2018-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */
namespace webkadabra\yii\modules\cms\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Class CmsRedirect
 * @package webkadabra\yii\modules\cms\models
 *
 * @property $id
 * @property $container_app_id
 * @property $redirect_from
 * @property $redirect_to
 * @property $updated_on
 * @property $deleted_yn
 */
class CmsRedirect extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cms_redirect}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['container_app_id', 'deleted_yn'], 'integer'],
            [['redirect_from', 'redirect_to'], 'required'],
            [['redirect_from', ], 'unique'],
            [['redirect_from', ], 'unique', 'targetAttribute' => 'nodeRoute', 'targetClass' => CmsRoute::class],
            [['updated_on'], 'safe'],
            [['redirect_from',], 'string', 'max' => 255],
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['updated_on'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_on'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'container_app_id' => Yii::t('app', 'Container App ID'),
            'redirect_from' => Yii::t('app', 'Redirect from'),
            'redirect_to' => Yii::t('app', 'Redirect to'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'deleted_yn' => Yii::t('app', 'Deleted Yn'),
        ];
    }

    public function getCmsApp() {
        return $this->hasOne(CmsApp::className(), ['id' => 'container_app_id']);
    }

    public function getRouterNode() {
        return $this->hasOne(CmsRoute::className(), ['id' => 'container_app_id']);
    }
}
