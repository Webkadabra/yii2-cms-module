<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2015-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Pages');
$this->params['breadcrumbs'][] = $this->title;
$this->context->layout = '//slim';

$this->beginBlock('actions');
echo Html::a(Yii::t('app', 'Create New Page'), [
    'create',
    'appId' => Yii::$app->request->get('appId')
], ['class' => 'btn btn-primary']);
$this->endBlock();
$tabs[] = [
    'encode' => false,
    'label' => '<i class="fa fa-cog"></i> &nbsp; Настройки сайта',
    'url' =>['/cms/apps'],
    'headerOptions'=>['class' => 'pull-right', 'style' => 'position: absolute; right: 25px'],
    'linkOptions'=>['style' => 'border: 0',],
    'class' => 'btn btn-default pull-right',
    'contentOptions'=>['class' => 'btn btn-default pull-right'],
]
?>

<div class="ui-card">
    <div class="ui-card-tabs">
        <?php echo \yii\bootstrap\Tabs::widget([
            'options' => ['class' => 'tabs'],
            'items' => $tabs,
//            'items' => \common\models\SavedObjectFilter::makeTabsConfig($searchModel, $tabs),
        ]); ?>
    </div>
    <style>
        span.highlighted {
            background-color: #fff700;
        }
    </style>
    <script>
        function    highlightTextNodes(element, searchTerm) {
            var sourceValue = element.getAttribute('data-searchable-value');
//            var regex = new RegExp(">([^<]*)?("+searchTerm+")([^>]*)?<","gim");
//            var tempinnerHTML = element.outerHTML;
//            element.innerHTML = element.outerHTML.replace(regex,'>$1<span class="highlighted">$2</span>$3<');
            var regex = new RegExp("([^<]*)?("+searchTerm+")([^>]*)?","gim");
            var tempinnerHTML = element.outerHTML;
            element.innerHTML =sourceValue.replace(regex,'$1<span class="highlighted">$2</span>$3');
        }
        function doTableSearch(input, myTable) {
            var  filter, table, tr, td, i;
            filter = input.value.toUpperCase();
            table = document.getElementById(myTable);
            tr = table.getElementsByTagName("tr");
            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    var searchable = td.querySelector('span[data-searchable-value]');
                    var searchInText = searchable.getAttribute('data-searchable-value');
                    if (searchInText.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        highlightTextNodes(searchable, input.value);
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
        function setupTableSearch(options) {
            window.yii.TableSearch = window.yii.TableSearch || (function($) {
                var typeTimer;
                var pub = {
                    isActive: true,
                    init: function(options) {
                        var form  = $('#' + options.formId);
                        form.on('submit', function(e) {
                            e.preventDefault();
                            return false;
                        })
                        var input = form.find('input[type="text"]');
                        input.on('keyup', function(e) {
                            var that = this;
                            clearTimeout(typeTimer);
                            typeTimer = setTimeout(function(){doTableSearch(that, options.tableId);}, 500)
                        }).select().focus();
                    },
                };
                return pub;
            })(window.jQuery);
            window.yii.TableSearch.init(options);
        }
    </script>
    <?php
    $this->registerJs('setupTableSearch('.\yii\helpers\Json::encode([
            'formId' => 'megaSearch',
            'tableId' => 'cmsTable',
        ]).')', \yii\web\View::POS_LOAD);
    ?>
    <form id="megaSearch" class="form-horizontal" action="/orders/index" method="get" style="padding:15px 15px 0 15px;text-align: center;margin:0;overflow:hidden">

        <!--            <label class="control-label col-sm-3" for="ordersearch-created_at">Дата заказа</label>-->
        <div class="col-sm-6 col-sm-offset-3">
            <input type="text" id="megaSearch-query" class="form-control" name="megaSearch[query]">
            <div class="help-block help-block-error "></div>
        </div>

        <!--        <div class="form-group">-->
        <!--            <button type="submit" class="btn btn-primary">Search</button>            <button type="reset" class="btn btn-default" onclick="clearForm(&quot;#e8ea03a514a89d913c1a149646137ca1&quot;);return false;">Reset</button> -->
        <!--        </div>-->

    </form>
    <?php Pjax::begin(); ?>
    <div class="card-section card-section--roots">
        <?php echo \yii\grid\GridView::widget([
            'id'=>'cmsTable',
            'layout' => '{items}{pager}{summary}',
            'tableOptions' => [
                'width' => '100%',
				'class' => 'table table--no-sort table-striped',
            ],
            'dataProvider' => $dataProvider,
            'columns' => [
////            ['class' => 'yii\grid\SerialColumn'],
//            'name',
//            'product_type',
                [
                    'attribute' => 'name',
                    'value' => function($model) {
                        return $model->getNameExtendedPrefix() . ' ' . Html::tag('span', $model->name, ['data-searchable-value' => $model->name]);
                    },
                    'format' => 'raw',
                ],
//            [
//                'attribute' => 'available_yn',
//                'format' => 'boolean',
//            ],
                [
                    'format' => 'raw',
                    'value' => function($model /** @var \common\modules\cms\models\CmsRoute $model */) {
                        if (!$model->sitemap_yn && !$model->getIsRedirectType()) {
                            return '<span class="fa-stack fa-1x text-muted" title="Not in sitemap">
  <i class="fa  fa-ban fa-stack-2x"></i>
  <strong class="fa-stack-1x fa fa-sitemap"></strong>
</span>';
                        } else {
                            return '';
                        }
                    },
                    'options' => [
                        'width' => '1%'
                    ]
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-cog"></span>', $url, [
                                'title' => Yii::t('app', 'Settings'),
                                'class' => 'btn btn-sm btn-default'
                            ]);
                        }
                    ],
                    'options' => [
                        'width' => '1%'
                    ]
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
        <br />
    </div>
</div>
