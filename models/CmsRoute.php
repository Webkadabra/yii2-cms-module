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
 * @property string $container_app_id
 * @property string $tree_root
 * @property string $tree_left
 * @property string $tree_right
 * @property string $tree_level
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
 * @property CmsApp $cmsApp
 */
class CmsRoute extends \yii\db\ActiveRecord
{
    use \webkadabra\yii\modules\cms\CmsPageTrait;
    use \webkadabra\yii\modules\cms\CmsPageFormTrait;
    use \kartik\tree\models\TreeTrait;

    const IMG_SUB = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAMCAMAAABhq6zVAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAG2YAABzjgAA4VsAAIYrAAB8KAAAzkYAADQZAAAcfP/pwnAAAAMAUExURQAAAP///////wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEjKtwcAAAADdFJOU///ANfKDUEAAAAcSURBVHjaYmBCAgz4OQxUloFBXJYCAAAA//8DAFVgARPcMjyVAAAAAElFTkSuQmCC';
    const IMG_SUB2 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAMCAMAAABhq6zVAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAAZQTFRFCAgI////vE5+ngAAAAJ0Uk5T/wDltzBKAAAAF0lEQVR42mJgRAIM9OIwMCJDXMoAAgwAK7YAi7Pk8iwAAAAASUVORK5CYII=';

    public $appendTo;

    public $moveToRoot;

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
            [['tree_root', 'tree_level', 'nodeEnabled', 'nodeContentPageID', 'nodeHomePage',
                'nodeOrder', 'deleted_yn', 'sitemap_yn',], 'integer'],
            [['nodeType'], 'required'],
            [['nodeType', 'nodeProperties', 'nodeAccessLockType'], 'safe'],
            [['nodeBackendName', 'nodeRoute', 'nodeParentRoute', 'nodeAccessLockConfig'], 'string', 'max' => 255],
            [['viewLayout'], 'string', 'max' => 100],
            [['viewTemplate'], 'string', 'max' => 200],
            [['nodeHomePage'], 'unique'],


            [['container_app_id'], 'required'],
            [['nodeRoute'], 'required'],
            [['nodeRoute'], 'unique'],
            [['sitemap_yn'], 'integer'],
            [['nodeEnabled'], 'integer'],
            [['appendTo', 'moveToRoot',], 'integer'], // tree manipulation attributes

        ];
    }

    public function afterValidate()
    {
        parent::afterValidate();
        if ($this->isAttributeSafe('redirect_to') && $this->getIsRedirectType() && !$this->getRedirect_to()) {
            // we definitely need a URL to be redirected to if this is a redirect route:
            $this->addError('redirect_to', Yii::t('cms', 'This field is mandatory.'));
        }
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
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree_root',
                'leftAttribute' => 'tree_left',
                'rightAttribute' => 'tree_right',
                'depthAttribute' => 'tree_level',
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
            'tree_root' => 'Tree Root',
            'tree_level' => 'Tree Level',
            'nodeBackendName' => Yii::t('cms', 'Internal name'),
            'nodeRoute' => Yii::t('cms', 'Route'),
            'nodeParentRoute' => 'Node Parent Route',
            'nodeType' => 'Node Type',
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
            'moveToRoot' => Yii::t('cms', 'Move page to root folder'),
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'moveToRoot' => Yii::t('cms', 'This does not affect page URL.'),
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
    public function getCmsApp() {
        return $this->hasOne(CmsApp::className(), ['id' => 'container_app_id']);
    }

    /**
     * @return string document permalink, relative to frontend root
     */
    public function getPermalink()
    {
        if ($this->cmsApp->url_component) {
            /** @see UrlManager::createAbsoluteUrl() */
            $c = $this->cmsApp->url_component;
            $comp =  Yii::$app->get($c);
            return $comp->createAbsoluteUrl('/'.$this->nodeRoute);
        }
        return rtrim($this->cmsApp->base_url, '/') . '/' . $this->nodeRoute;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->nodeRoute;
    }

    /**
     * Use this with CGridView
     * Example:
     * <code>
     * ...
     * 'columns'=>array(
     *    array(
     *        'name'=>'nameExtendedWImage',
     *        'type'=>'raw',
     *    ),
     * ...
     * </code>
     */
    public function getNameExtendedWImage($remove=0)
    {
        return $this->getNameExtendedPrefix() . ' ' . $this->name;
    }

    public function getNameExtendedPrefix($remove=0)
    {
        $level = $this->tree_level-($remove);
        $prefix = $level > 0 ? str_repeat("—", $level) . ' · ' : '';
        if ($level == 0) {
            $prefix = ' ';
        } else {
            if ($level == 1) {
                $prefix = '<img src="'.static::IMG_SUB.'" > ';
            } else {
                $prefix = '<img src="'.static::IMG_SUB.'">';
                $prefix .= str_repeat('<img src="'.static::IMG_SUB2.'">', $level - 1);
                $prefix .= ' ';
            }
        }
        return $prefix;
    }

    /**
     * Generate data for backend dropdown list
     * @return array
     */
    public function getTypeDropdownData()
    {
        return self::typeDropDownData();
    }

    /**
     * Generate data for backend dropdown list
     * @return array
     */
    public static function typeDropDownData()
    {
        return array(
            'document' => Yii::t('app','Document'),
            'controller' => Yii::t('app','Built-in Module'),
            'forward' => Yii::t('app','Redirect'),
        );
    }

    public function getIsRedirectType() {
        return $this->nodeType == 'forward';
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
