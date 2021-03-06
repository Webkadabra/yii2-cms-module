<?php

namespace webkadabra\yii\modules\cms\models;

use webkadabra\yii\modules\cms\AdminModule;
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
                'currentLanguage'=> Yii::$app->cms->language,
                'languages' => Yii::$app->cms->languages,
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
        if (class_exists('omgdef\multilingual\MultilingualQuery')) {
            return new \omgdef\multilingual\MultilingualQuery(get_called_class());
        }
        return parent::find();
    }

    public function getName()
    {
        return $this->contentBlockName
            ? $this->contentBlockName
            : Yii::t('app', 'Untitled');
    }

    /**
     * @return array|mixed options for dropdown/select 2 plugin with list of available blocks in this template
     */
    public function blockIdDropdownOptions() {
        $templatesWC = AdminModule::getInstance()->templateListWithConfigs();
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
        $return = $this->page && isset($templatesCells[$this->page->viewTemplate]) ? $templatesCells[$this->page->viewTemplate] : [];
        // since content block can be renamed to any free name, make sure we add current option to `$return` so that UI
        // elements like dropdown or "Select 2 plugin" can build up UX properly:
        if (!isset($return[$this->contentBlockName])) {
            $return[$this->contentBlockName] = $this->contentBlockName;
        }
        return $return;
    }

    /**
     * @return CmsDocumentVersion main category for this product
     */
    public function getPage() {
        return $this->hasOne(CmsDocumentVersion::className(), ['id' => 'version_id']);
    }
}
