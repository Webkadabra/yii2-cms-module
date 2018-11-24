<?php

namespace webkadabra\yii\modules\cms\models;

use omgdef\multilingual\MultilingualQuery;
use webkadabra\yii\modules\cms\AdminModule;
use Yii;

/**
 * This is the model class for table "{{%cms_content_blocks}}".
 * @author Sergii Gamaiunov <devkadabra@gmail.com>
 * @package webkadabra\yii\modules\cms\models
 *
 * @property string $contentID
 * @property string $contentBlockName
 * @property string $content
 * @property string $Pages_pageID
 * @property string $contentType
 * @property string $sort_order
 */
class CmsContentBlock extends \yii\db\ActiveRecord
{
    const TYPE_HTML = 'XHTML';
    const TYPE_CSS_INLINE = 'CSS';
    const TYPE_CSS_LINK = 'CSSLINK';
    const TYPE_JAVASCRIPT_INLINE = 'JAVASCRIPT';
    const TYPE_JAVASCRIPT_FILE = 'JAVASCRIPTFILE';
    const TYPE_MODULE = 'MODULE';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_content_blocks}}';
    }

    public function getId()
    {
        return $this->contentID;
    }

    public function getName()
    {
        return $this->contentBlockName;
    }

    public static function primaryKey()
    {
        return ['contentID'];
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
                'langForeignKey' => 'contenct_block_id',
                'tableName' => "{{%cms_content_blocks_lang}}",
                'attributes' => [
                    'content',
                ]
            ];
        }
        return $behaviors;
    }

    public static function find()
    {
        if (class_exists('MultilingualQuery')) {
            return new MultilingualQuery(get_called_class());
        }
        return parent::find();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['contentType'], 'string'],
            [['Pages_pageID'], 'required'],
            [['Pages_pageID'], 'integer'],
            [['contentBlockName'], 'string', 'max' => 100],
        ];
    }

    /**
     * @return ShopCatalogCategory main category for this product
     */
    public function getPage() {
        return $this->hasOne(CmsRoute::className(), ['id' => 'Pages_pageID']);
    }


    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contentID' => 'Content ID',
            'contentBlockName' => 'Content Block Name',
            'content' => 'Content',
            'Pages_pageID' => 'Pages Page ID',
            'contentType' => 'Content Type',
        ];
    }

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
        return $this->page && isset($templatesCells[$this->page->viewTemplate]) ? $templatesCells[$this->page->viewTemplate] : [];
    }

    public static function typeDropdownOptions() {
        return [
            self::TYPE_HTML => 'HTML',
            self::TYPE_CSS_LINK => 'CSS file link',
            self::TYPE_CSS_INLINE => 'Inline CSS',
            self::TYPE_JAVASCRIPT_INLINE => 'Inline Javascript',
            self::TYPE_JAVASCRIPT_FILE => 'Javascript file',
        ];
    }
}