<?php

namespace webkadabra\yii\modules\cms\models;

use webkadabra\yii\modules\cms\AdminModule;
use webkadabra\yii\modules\cms\Module;
use Yii;

/**
 * This is the model class for table "cms_document_version".
 *
 * @author Sergii Gamaiunov <devkadabra@gmail.com>
 * @package webkadabra\yii\modules\cms\models
 *
 * @property string $id
 * @property string $node_id
 * @property integer $version
 * @property string $description
 * @property string $created_on
 * @property integer $published_yn
 * @property string $published_on
 * @property string $owner_user_id
 * @property string $nodeType
 * @property string $nodeProperties
 * @property string $viewLayout
 * @property string $viewTemplate
 *
 * @property CmsRoute $document
 * @property CmsDocumentVersionContent[] $contentBlocks
 * @property CmsDocumentVersionContent[] $localizedContentBlocks
 */
class CmsDocumentVersion extends \yii\db\ActiveRecord
{
    use \webkadabra\yii\modules\cms\CmsPageTrait;
    use \webkadabra\yii\modules\cms\CmsPageFormTrait;

    public $copyPageBlockIds=[];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_document_version';
    }

    public function getName() {
        return 'version #'.$this->version;
    }

    /**
     * @return \yii\db\ActiveQuery|CmsRoute
     */
    public function getDocument() {
        return $this->hasOne(CmsRoute::className(), ['id' => 'node_id']);
    }

    /**
     * @return int
     */
    public function getNodeEnabled() {
        return $this->document->nodeEnabled;
    }

    /**
     * @return mixed
     */
    public function getRoute() {
        return $this->document->route;
    }
    /**
     * @return mixed
     */
    public function getIsHomePage() {
        return $this->document->getIsHomePage();
    }

    public function getSafeViewLayout() {
        return CmsRoute::safeViewLayout($this->viewLayout);
    }

    /**
     * @return string
     */
    public function getViewTemplate() {
        return ($this->viewTemplate ? '//cms-templates/'. $this->viewTemplate : 'view');
    }

    /**
     * @return string document permalink, relative to frontend root
     */
    public function getPermalink()
    {
        return Yii::$app->urlManager->createAbsoluteUrl(['/'.$this->document->nodeRoute, 'previewVersion' => $this->id]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['copyPageBlockIds', 'each', 'rule' => ['integer']],
            [['node_id'], 'required'],
            [['node_id', 'version', 'published_yn', 'owner_user_id'], 'integer'],
            [['controller_route', 'redirect_to', 'page_title', 'meta_keywords', 'meta_description', 'body_class',], 'safe'],
            [['created_on', 'published_on'], 'safe'],
            [['nodeType',], 'string'],
            [['description', 'viewLayout', 'viewTemplate'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (trim($this->viewLayout) == '') {
            $this->viewLayout = null;
        }
        $this->cleanUnusedOptions();
        // pack 'em at the end
        if (isset($this->modifiedProperties) AND is_array($this->modifiedProperties)) {
            $this->nodeProperties = json_encode($this->modifiedProperties);
        }
        if ($this->isNewRecord) {
            if (!$this->owner_user_id && !Yii::$app->user->isGuest) {
                $this->owner_user_id = Yii::$app->user->id;
            }
            // bump version
            $max = CmsDocumentVersion::find()
                ->where(['node_id'=>$this->node_id])
                ->select('max(version)')
                ->max('version');
            $this->version = ($max + 1);
        }
        return parent::beforeSave($insert);
    }

    /**
     * @param array $data
     * @param null $formName
     * @return bool
     */
    public function load($data, $formName = null)
    {
        $this->setPostActionParameters($data);
        return parent::load($data, $formName);
    }

    protected function setPostActionParameters($data)
    {
        $key = md5($this->controller_route);
        $routesMap = AdminModule::getInstance()->availableControllerRoutes;
        $actionParameters = array();
        if ($this->controller_route && strstr($this->controller_route, '/'))
            list($controller, $action) = explode('/', $this->controller_route);
        else {
            $controller = $this->controller_route;
            $action = null;
        }
        if (isset($routesMap[$controller]['actions'][$action]['params']) && $parameters = $routesMap[$controller]['actions'][$action]['params']) {
            if (!is_array($parameters))
                $parameters = array($parameters);
            foreach ($parameters as $parameter) {

                if (is_array($parameter)) {

                    $parameterName = key($parameter);
                } else {
                    $parameterName = $parameter;
                }

                // Check if we have this parameter set at _POST
                if (isset($data[$key . '_' . $parameterName . '_param'])) {
                    $actionParameters[$parameterName] = $data[$key . '_' . $parameterName . '_param'];
                }
            }
        }
        return $this->setAction_parameters($actionParameters);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'node_id' => 'Node ID',
            'version' => 'Version',
            'description' => 'Description',
            'created_on' => 'Created On',
            'published_yn' => 'Published Yn',
            'published_on' => 'Published On',
            'owner_user_id' => 'Owner User ID',
            'nodeType' => 'Type',
            'nodeProperties' => 'Properties',
            'viewLayout' => 'Layout',
            'view_template' => 'View Template',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery|CmsDocumentVersionContent[]
     */
    public function getContentBlocks() {
        return $this->hasMany(CmsDocumentVersionContent::className(), ['version_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery|CmsDocumentVersionContent
     */
    public function getLocalizedContentBlocks() {
        $query = $this->hasMany(CmsDocumentVersionContent::className(), ['version_id' => 'id']);
        if (Yii::$app->cms->enableMultiLanguage) {
            $query->localized(Yii::$app->cms->language);
        }
        return $query;
    }
}
