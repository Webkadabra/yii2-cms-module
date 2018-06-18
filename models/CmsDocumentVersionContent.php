<?php

namespace webkadabra\yii\modules\cms\models;

use omgdef\multilingual\MultilingualQuery;
use Yii;

/**
 * This is the model class for table "cms_document_version_content".
 * @author Sergii Gamaiunov <devkadabra@gmail.com>
 * @package webkadabra\yii\modules\cms\models
 *
 * @property string $id
 * @property string $version_id
 * @property string $contentType
 * @property string $content
 * @property string $sort_order
 * @property string $contentBlockName
 */
class CmsDocumentVersionContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_document_version_content';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['version_id'], 'required'],
            [['version_id', 'sort_order'], 'integer'],
            [['contentType', 'content'], 'string'],
            [['contentBlockName'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'version_id' => 'Version ID',
            'contentType' => 'Content Type',
            'content' => 'Content',
            'sort_order' => 'Sort Order',
            'contentBlockName' => 'Content Block Name',
        ];
    }

    public static function primaryKey()
    {
        return ['id'];
    }

    public function behaviors()
    {
        $behaviors = [];
        if (class_exists('omgdef\multilingual\MultilingualBehavior')) {
            $behaviors['ml'] = [
                'class' => \omgdef\multilingual\MultilingualBehavior::className(),
                'currentLanguage'=> isset(Yii::$app->langasync) ? Yii::$app->langasync->language : Yii::$app->language,
                'languages' => [
                    'ru' => 'Russian',
                    'en' => 'English',
                    'uk' => 'Ukrainian',
                ],
                //'languageField' => 'language',
                //'localizedPrefix' => '',
                //'requireTranslations' => false',
                'dynamicLangClass' => true,
                //'langClassName' => PostLang::className(), // or namespace/for/a/class/PostLang
                'defaultLanguage' => 'ru',
                'langForeignKey' => 'version_content_id',
                'tableName' => "{{%cms_document_version_content_lang}}",
                'attributes' => [
                    'content',
                ]
            ];
        }
        return $behaviors;
    }

    public static function find()
    {
        return new MultilingualQuery(get_called_class());
    }

    public function getName()
    {
        return $this->contentBlockName;
    }

    public function blockIdDropdownOptions() {
        $templatesWC = Yii::$app->getModule('cms')->templateListWithConfigs();
        $templatesList = $templatesCells = array();
        foreach ($templatesWC as $templateID => $templateOptions) {
            $templatesList[ltrim($templateID,'/')] = isset($templateOptions['label']) ? $templateOptions['label'] : $templateID;
            if (isset($templateOptions['blocks']))
                foreach ($templateOptions['blocks'] as $block) {
                    if (is_array($block)) {
                        $templatesCells[ltrim($templateID,'/')][$block['id']] = $block['id'].' - '. $block['hint'];
                    } else {
                        $templatesCells[ltrim($templateID,'/')][$block] = $block;
                    }
                }
        }
        return $this->page && isset($templatesCells[$this->page->viewTemplate]) ? $templatesCells[$this->page->viewTemplate] : [];
    }

    /**
     * @return CmsDocumentVersion main category for this product
     */
    public function getPage() {
        return $this->hasOne(CmsDocumentVersion::className(), ['id' => 'version_id']);
    }
}
