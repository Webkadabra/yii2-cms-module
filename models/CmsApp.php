<?php

namespace webkadabra\yii\modules\cms\models;

use Yii;

/**
 * This is the model class for table "cms_app" representing a container for CMS routes
 * @author Sergii Gamaiunov <devkadabra@gmail.com>
 * @package webkadabra\yii\modules\cms\models
 *
 * @property string $id
 * @property string $name
 * @property string $code
 * @property integer $active_yn
 * @property integer $domain
 * @property integer $base_url todo: remove (can be accessed via url manager)
 * @property integer $url_component a name of Yii::$app component to handle urls (IF different from `urlManager`)
 */
class CmsApp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_app';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active_yn'], 'integer'],
            [['name', 'domain', 'base_url'], 'string', 'max' => 100],
            [['url_component'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('cms', 'Site Name'),
            'code' => Yii::t('cms', 'Code'),
            'domain' => Yii::t('cms', 'Domain'),
            'active_yn' => Yii::t('cms', 'Active'),
            'base_url' => Yii::t('cms', 'Site URL'),
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'name' => Yii::t('cms', 'Internal use only'),
            'code' => Yii::t('cms', 'App ID as it appears in `config.php` (for multiple websites only)'),
            'domain' => Yii::t('cms', 'Website dmain, e.g. `example.com`'),
            'base_url' => Yii::t('cms', 'E.g. `https://example.com`'),
            'url_component' => Yii::t('cms', 'Optional custom URL manager component name, available in `Yii::$app`. By default, component `urlManager` will be used.'),
        ];
    }

    private static $foundByCode = [];
    public static function findByCode($code) {
        if (!isset(self::$foundByCode[$code])) {
            self::$foundByCode[$code] = CmsApp::find()->where(['code' => $code])->one();
        }
        return self::$foundByCode[$code];
    }

    public function getPermalink() {
        $this->base_url;
    }
}
