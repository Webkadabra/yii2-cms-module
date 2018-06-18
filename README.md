# yii2-cms-module

A CMS & routing module for Yii 2 applications.

## Installation via Composer

You can install package with a command:

> composer require webkadabra/yii2-cms-module

## Setup

Add `cmsRouter` component to your main application configuration (e.g. `config/main.php` or `frontend/config/main.php`, 
depending on your application structure):

```
// ...
'components' => [
        // ...
        'cmsRouter' =>[
            'class' => 'webkadabra\yii\modules\cms\components\CmsRouter',
            'containerAppCode' => null, // default
        ],
        // ...
],
// ...
```

Add `cmsRouter` component to `bootstrap` property at your main application config (usually, the same file as in previous step):

```
// ...
    'bootstrap' => ['log',  'cmsRouter', /* ... other conponents ... */],
],
// ...
```

Add `cms` module to your admin or backend application (it can be the same application config):

```
// ...
'modules' => [
        // ...
        'cms' => [
            'class' => 'common\modules\cms\Module',
            'mode' => \common\modules\cms\Module::MODE_BACKEND,
            'availableControllerRoutes' => [
                'site' => [
                    'label' => 'Global',
                    'actions' => [
                        'index' => 'Home page',
                        'contact' => 'Contact page',
                        'page' => [
                            'label' => 'Static page',
                            'params' => [
                                ['view' => 'File name'],
                            ],
                            #'params'=>['id', 'dummy'], // For multiple params
                        ],
                    ],
                ],
                'rss'=>[
                    'label' => 'RSS feeds controller',
                    'actions'=>[
                        'view'=>array(
                            'label'=>'Display feed',
                            'params'=>['id'], // Required or available action params
                        ],
                    ],
                ],
                'store' => array(
                    'label' => 'Store',
                    'actions' => array(
                        'index' => 'Store front page',
                        'good' => [
                            'label' => 'Offer page',
                            'params' => [
                                ['alias' => 'Offer code (alias or SKU)'],
                            ],
                        ],
                        'listCategory' => [
                            'label' => 'Offers in a category',
                            'params' => [
                                ['alias' => 'Category code (alias)'],
                            ],
                        ),
                        'promotion' => [
                            'label' => 'Promo page',
                            'params' => [
                                ['promoid' => 'Promotion ID'],
                                ['promoName' => 'Promotion name'],
                            ],
                        ],
                    ],
                ],
            ]
        ],
        // ...
],
// ...
```

Thanks, pull requests and donations are welcome!

- Sergii
