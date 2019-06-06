<?php

namespace webkadabra\yii\modules\cms\models;
use creocoder\nestedsets\NestedSetsBehavior;
use webkadabra\yii\modules\cms\AdminModule;
use webkadabra\yii\modules\cms\Module;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\UrlManager;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "{{%cms_route}}".
 * @author Sergii Gamaiunov <devkadabra@gmail.com>
 * @package webkadabra\yii\modules\cms\models
 *
 * @property string $id
 * @property string $version_id
 * @property string $nodeBackendName
 * @property string $nodeRoute
 * @property string $nodeParentRoute
 * @property string $nodeType
 * @property integer $nodeEnabled
 * @property string $nodeProperties
 * @property string $nodeContentPageID
 * @property integer $nodeHomePage
 * @property string $nodeAccessLockType
 * @property string $nodeAccessLockConfig
 * @property string $nodeLastEdit
 * @property string $nodeOrder
 * @property string $viewLayout
 * @property string $viewTemplate
 * @property integer $deleted_yn
 * @property integer $sitemap_yn
 *
 * @property CmsDocumentVersion[] $versions
 * @property CmsDocumentVersion $publishedVersion
 */
class CmsRoute extends \yii\db\ActiveRecord
{
    use \webkadabra\yii\modules\cms\CmsPageTrait;
    use \webkadabra\yii\modules\cms\CmsPageFormTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_router_node}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nodeEnabled', 'nodeContentPageID', 'nodeHomePage',
                'nodeOrder', 'deleted_yn', 'sitemap_yn',], 'integer'],
            [['nodeType'], 'required'],
            [['nodeType', 'nodeProperties', 'nodeAccessLockType'], 'safe'],
            [['nodeBackendName', 'nodeRoute', 'nodeParentRoute', 'nodeAccessLockConfig'], 'string', 'max' => 255],
            [['viewLayout'], 'string', 'max' => 100],
            [['viewTemplate'], 'string', 'max' => 200],
            [['nodeHomePage'], 'unique'],

            [['nodeRoute'], 'required'],
            [['nodeRoute'], 'unique'],
            [['sitemap_yn'], 'integer'],
            [['nodeEnabled'], 'integer'],

        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['nodeLastEdit'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['nodeLastEdit'],
                ],
                'value' => new Expression('NOW()'),
            ],
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'deleted_yn' => true
                ],
                'allowDeleteCallback' => function ($model) {
                    /** @var $model CmsRoute */
                    return !$model->versions && !$model->contentBlocks; // allow to delete empty draft orders
                }
            ],
        ];
    }

    public function beforeSave($insert)
    {
        if (isset($this->modifiedProperties) AND is_array($this->modifiedProperties)) {
            $this->nodeProperties = json_encode($this->modifiedProperties);
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nodeBackendName' => Yii::t('cms', 'Title'),
            'nodeRoute' => Yii::t('cms', 'Route'),
            'nodeParentRoute' => 'Node Parent Route',
            'nodeType' => Yii::t('cms', 'Page Type'),
            'nodeEnabled' => Yii::t('cms', 'Page is published'),
            'nodeProperties' => 'Node Properties',
            'nodeContentPageID' => 'Node Content Page ID',
            'nodeHomePage' => 'Node Home Page',
            'nodeAccessLockType' => 'Node Access Lock Type',
            'nodeAccessLockConfig' => 'Node Access Lock Config',
            'nodeLastEdit' => 'Node Last Edit',
            'nodeOrder' => 'Node Order',
            'viewLayout' => 'Node Layout',
            'viewTemplate' => 'View Template',
            'deleted_yn' => 'Deleted Yn',
            'sitemap_yn' => Yii::t('cms', 'Visible in sitemap'),
        ];
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->nodeBackendName
            ? $this->nodeBackendName
            : Yii::t('app', 'Untitled');
    }

    /**
     * @inheritdoc
     * @return CmsRouteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CmsRouteQuery(get_called_class());
    }

    public function getViewTemplate() {
        $old_new_template_names_map = [
            'article_one_column' => 'article-one-column'
        ];
        return '//cms-templates/' . $this->viewTemplate;
    }

    public function getContentBlocks() {
        return $this->hasMany(CmsContentBlock::className(), ['Pages_pageID' => 'id']);
    }

    public function getLocalizedContentBlocks() {
        $query = $this->hasMany(CmsContentBlock::className(), ['Pages_pageID' => 'id']);
        if (Yii::$app->cms->enableMultiLanguage) {
            $query->localized(Yii::$app->cms->language);
        }
        return $query;
    }

    public function getVersions() {
        return $this->hasMany(CmsDocumentVersion::className(), ['node_id' => 'id']);
    }

    public function getPublishedVersion() {
        return $this->hasOne(CmsDocumentVersion::className(), ['id' => 'version_id']);
    }

    /**
     * @return string document permalink, relative to frontend root
     */
    public function getPermalink()
    {
        $route = '/' . ltrim($this->nodeRoute, '/'); // avoid double slash in the beginning in case page has `/` set as a route  (for home page)
        return Yii::$app->urlManager->createAbsoluteUrl($route);
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->nodeRoute;
    }

    /**
     * Generate data for backend dropdown list
     * @return array
     */
    public function getTypeDropdownData()
    {
        return self::typeDropDownData();
    }

    const TYPE_DOCUMENT = 'document';

    const TYPE_CONTROLLER = 'controller';

    /**
     * Generate data for backend dropdown list
     * @return array
     */
    public static function typeDropDownData()
    {
        return array(
            static::TYPE_DOCUMENT => Yii::t('cms','Document'),
            static::TYPE_CONTROLLER => Yii::t('cms','Built-in Module'),
        );
    }

    public function getIsHomePage() {
        return $this->nodeHomePage || $this->nodeRoute == '/';
    }

    public static function templatesDropdownOptions() {
        $templatesWC = AdminModule::getInstance()->templateListWithConfigs();
        $templatesList = array();
        foreach ($templatesWC as $templateID => $templateOptions) {
            $templatesList[ltrim($templateID,'/')] = isset($templateOptions['label']) ? $templateOptions['label'] : $templateID;
        }
        return $templatesList;
    }

    public function getSafeViewLayout() {
        return self::safeViewLayout($this->viewLayout);
    }

    public static function safeViewLayout($viewLayout) {
        $test = strstr($viewLayout, '..');
        if ($test || !$viewLayout)
            return null;
        return $viewLayout;
    }

    /** required for tree manager input */
    public function getIcon() {}
    /** required for tree manager input */
    public function getIcon_type() {}
    /** required for tree manager input */
    public function getDisabled() {
        return false;
    }
}
