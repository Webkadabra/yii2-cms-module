<?php
namespace webkadabra\yii\modules\cms\components;

use webkadabra\yii\modules\cms\models\CmsApp;
use webkadabra\yii\modules\cms\models\CmsDocumentVersion;
use webkadabra\yii\modules\cms\models\CmsRoute;
use yii;
use yii\base\BootstrapInterface;

/**
 * Class CmsRouter
 * @author Sergii Gamaiunov <devkadabra@gmail.com>
 * @package webkadabra\yii\modules\cms\components
 */
class CmsRouter implements BootstrapInterface
{
    /**
     * @var string key for `CmsApp` model
     */
    public $containerAppCode;

    protected $_containerAppId;

    public $templateMap = [];

    public function getContainerId() {
        if (!$this->_containerAppId) {
            if ($this->containerAppCode) {
                $site = CmsApp::findByCode($this->containerAppCode);
                if ($site) {
                    $this->_containerAppId = $site->id;
                }
            }
        }
        return $this->_containerAppId;
    }
    /**
     * @param yii\base\Application $app
     * @todo add cache
     */
    public function bootstrap($app)
    {
        if (!$this->containerAppCode) {
            $this->containerAppCode = Yii::$app->id;
        }
        $routeArray = [];
        $route = $app->urlManager->parseRequest($app->request);
        if ($route[0] === '' && ($app->request->pathInfo === '' || $app->request->pathInfo == '/')) {
            $row = CmsRoute::findOne([
                'nodeHomePage' => 1,
                'deleted_yn' => 0,
                'container_app_id' => $this->getContainerId(),
            ]);
        } else if ($route[0]) {
            $route_norm = trim($route[0]);
            $route_norm = trim($route_norm, '/');
            $filter = [
                'nodeRoute' => $route_norm,
                'container_app_id' => $this->getContainerId(),
            ];
            $row = CmsRoute::findOne($filter);
//            if (!$routes = Yii::$app->cache->get('cms-routes')) {
//                $routes = CmsRoute::find()->where([
//                    'deleted_yn' => 0,
//                ])
//                    ->andWhere(['not', ['nodeRoute' => null]])
//                    ->andWhere(['not', ['nodeRoute' => '']])
//                    ->indexBy('nodeRoute')->asArray()->all();
//                Yii::$app->cache->set('cms-routes', $routes);
//            }
//            if (isset($routes[$route_norm])) {
//                $row = new CmsRoute();
//                $row->setAttributes($routes[$route_norm], false);
//            } else {
//                $row = null;
//            }
//            if (!$props = Yii::$app->cache->get('cms-route-'.$route_norm)) {
//                $props = CmsRoute::find()->where([
//                    'deleted_yn' => 0,
//                    'nodeRoute' => $route_norm,
//                ])->asArray()->one();
//
//                if($props) {
//                    Yii::$app->cache->set('cms-route-'.$route_norm, $props);
//                    $row = new CmsRoute();
//                    $row->setAttributes($props, false);
//                }
//            } else {
//                $row = new CmsRoute();
//                $row->setAttributes($props, false);
//            }
            if (!isset($row)) $row = null;

        } else if ($app->request->pathInfo) {
            $route_norm = trim($app->request->pathInfo, '/');
            $row = CmsRoute::findOne([
                'nodeRoute' => $route_norm,
                'container_app_id' => $this->getContainerId(),
            ]);
        } else {
            $row = null;
        }
        if ($row) {
            Yii::$app->params['view_node'] = $row;
            /** @var CmsRoute $row */
            if ($row->nodeType === 'forward') {
                Yii::$app->getResponse()->redirect($row->redirect_to);
                Yii::$app->end();
            }
            $viewingDocument = $row;
            // preview mode
            if (($versionId = Yii::$app->request->get('previewVersion')) && Yii::$app->user->can('previewCms')) {
                $version = CmsDocumentVersion::find()->where([
                    'id' => $versionId,
                    'node_id' => $row->id
                ])->one();
                if ($version) {
                    Yii::$app->params['view_preview_node'] = $version;
                    $viewingDocument = $version;
                }
            } else if ($row->version_id && $row->publishedVersion) {
                Yii::$app->params['view_node'] = $row->publishedVersion;
                $viewingDocument = $row->publishedVersion;
            }
            Yii::$app->view->registerMetaTag([
                'name' => 'description',
                'content' => $viewingDocument->getMeta_description()
            ]);
            Yii::$app->view->registerMetaTag([
                'name' => 'keywords',
                'content' => $viewingDocument->getMeta_keywords()
            ]);
            $pageTitle = $viewingDocument->getPage_title() ? $viewingDocument->getPage_title() : Yii::$app->name;
            Yii::$app->view->title = $pageTitle;
            // resetting view will result in meta tags drop!
//            $app->set('view', [
//                'class' => 'yii\web\View',
//                'title' => $pageTitle,
//                // todo: make it possible to override theme settings too
////                    'theme' => [
////                    'baseUrl' => '@web/themes/mobile',
////                    'basePath' => '@app/themes/mobile'
////                    ]
//            ]);

            if($viewingDocument->viewTemplate) {
                Yii::$app->params['view_template'] = '//cms-templates/'
                    . (isset($this->templateMap[$viewingDocument->viewTemplate])
                        ? $this->templateMap[$viewingDocument->viewTemplate]
                        : $viewingDocument->viewTemplate);
            }
            if ($viewingDocument->nodeType === 'controller') {
                if ($viewingDocument->nodeProperties) {
                    $viewingDocument->unpackOptions();
                    $data = $viewingDocument->nodeProperties;
                    if ($data && is_array($data) && isset($data['controller_route']) && $data['controller_route']) {
                        $routeArray[$route[0]] = strtolower($data['controller_route']);
                        if (isset($data['action_parameters']) && $data['action_parameters']) {
                            //$bodyParams = Yii::$app->request->bodyParams;
                            foreach ($data['action_parameters'] as $paramKey => $paramValue) {
                                // echo $paramValue;
                                // $bodyParams[$paramKey] = $paramValue;
                                $_GET[$paramKey] = $paramValue;
                            }
                            // Yii::$app->request->setBodyParams($bodyParams);
                        }
                    }
                }
            } else if ($viewingDocument->nodeType === 'document') {
                /** @see \frontend\controllers\CmsController::actionPage */
                $routeArray[$route[0]] = '/cms-web/view/page';
                $_GET['id'] = $viewingDocument->id;
            }
        }
        $app->urlManager->addRules($routeArray);
    }

    /**
     * WIP for `UrlManager.allowStrictParsing=true`
     * @param $app
     * @throws \yii\base\ExitException
     */
    public function __bootstrap($app)
    {
        $routeArray = [];
        $langs = array_keys($app->urlManager->languages);
        $route = explode('/', trim($app->request->pathInfo, '/'));

        if (in_array($route[0], $langs)) {
            array_shift($route);
        }

        if ((empty($route) || $route[0] === '') &&  ($app->request->pathInfo === '' || $app->request->pathInfo == '/' || in_array(trim($app->request->pathInfo, '/'), $langs))) {
            $row = CmsRoute::findOne(['nodeHomePage' => 1]);
            $route[0] = '/';
        } else if ($route[0]) {
            $route_norm = trim($route[0]);
            $route_norm = trim($route_norm, '/');
            $row = CmsRoute::findOne(['nodeRoute' => $route_norm]);
        } else if ($app->request->pathInfo) {
            $route_norm = trim($app->request->pathInfo, '/');
            $row = CmsRoute::findOne(['nodeRoute' => $route_norm]);
        } else {
            $row = null;
        }

        if ($row) {
            if ($row->nodeType === 'forward') {
                Yii::$app->getResponse()->redirect($row->redirect_to);
                Yii::$app->end();
            }
            Yii::$app->view->registerMetaTag([
                'name' => 'description',
                'content' => $row->getMeta_description()
            ]);
            Yii::$app->view->registerMetaTag([
                'name' => 'keywords',
                'content' => $row->getMeta_keywords()
            ]);


            $pageTitle = $row->getPage_title() ? $row->getPage_title() : Yii::$app->name;
            Yii::$app->view->title = $pageTitle;
            Yii::$app->params['view_node'] = $row;
            if($row->viewTemplate) {
                Yii::$app->params['view_template'] = '//cms-templates/'. (isset($this->templateMap[$row->viewTemplate]) ? $this->templateMap[$row->viewTemplate] : $row->viewTemplate);
            }

            if ($row->nodeType === 'controller') {
                if ($row->nodeProperties) {
                    $row->unpackOptions();
                    $data =  $row->nodeProperties;
                    if ($data && is_array($data) && isset($data['controller_route']) && $data['controller_route']) {
                        $routeArray[$route_norm] = strtolower($data['controller_route']);
                        if (isset($data['action_parameters']) && $data['action_parameters'] && isset($data['action_parameters']['alias']) && $data['action_parameters']['alias']) {
                            foreach ($data['action_parameters'] as $paramKey => $paramValue) {
                                $_GET[$paramKey] = $paramValue;
                            }
                        }
                    }
                }

            } else if ($row->nodeType === 'document') {
                $routeArray[$route[0]] = '/cms-web/view/page';
            }
        }
        $app->urlManager->addRules($routeArray);
    }
}