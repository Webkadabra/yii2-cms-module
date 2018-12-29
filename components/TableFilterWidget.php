<?php
/**
 * Alphatech, <http://www.alphatech.com.ua>
 *
 * Copyright (C) 2018-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 */

namespace webkadabra\yii\modules\cms\components;


use yii\base\Widget;

class TableFilterWidget extends Widget
{
    public function run() {
        ?>
        <style>
            span.highlighted {
                background-color: #fff700;
            }
        </style>
        <script>
            function highlightTextNodes(element, searchTerm) {
                var sourceValue = element.getAttribute('data-searchable-value');
                var regex = new RegExp("([^<]*)?("+searchTerm+")([^>]*)?","gim");
                var tempinnerHTML = element.outerHTML;
                element.innerHTML = sourceValue.replace(regex,'$1<span class="highlighted">$2</span>$3');
            }
            function doTableSearch(input, myTable) {
                var  filter, table, tr, td, tds, i, it, mc = 0;
                filter = input.value.toUpperCase();
                table = document.getElementById(myTable);
                tr = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");
                // Loop through all table rows, and hide those who don't match the search query
                for (i = 0; i < tr.length; i++) {
                    var hasMatch = false;
                    tds = tr[i].getElementsByTagName("td");
                    for (it = 0; it < tds.length; it++) {
                        td = tds[it];
                        var thisHasMatch = false;
                        if (td) {
                            var searchable = td.querySelector('span[data-searchable-value]');
                            if (searchable) {

                                var searchInText = searchable.getAttribute('data-searchable-value');
                                if (searchInText.toUpperCase().indexOf(filter) > -1) {
                                    thisHasMatch = true;
                                    hasMatch = true;
                                }
                            }
                        }
                        if (thisHasMatch) {
                            highlightTextNodes(searchable, input.value);
                        }
                    }
                    if (hasMatch) {
                        mc++; // match counter
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
                if (mc === 0) {
                    $('#'+myTable).hide();
                    $('#'+myTable+'_emptyContent').show();
                } else {
                    $('#'+myTable).show();
                    $('#'+myTable+'_emptyContent').hide();
                }
            }
            function setupTableSearch(options) {
                window.yii.TableSearch = window.yii.TableSearch || (function($) {
                    var typeTimer;
                    var pub = {
                        options: options,
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
                                typeTimer = setTimeout(function(){doTableSearch(that, options.tableId);}, 100)
                            }).on('change', function(e) {
                                var that = this;
                                clearTimeout(typeTimer);
                                typeTimer = setTimeout(function(){doTableSearch(that, options.tableId);}, 500)
                            }).on('drop', function(e) {
                                var that = this;
                                clearTimeout(typeTimer);
                                typeTimer = setTimeout(function(){doTableSearch(that, options.tableId);}, 100)
                            }).on('cut', function(e) {
                                var that = this;
                                clearTimeout(typeTimer);
                                typeTimer = setTimeout(function(){doTableSearch(that, options.tableId);}, 100)
                            }).on('blur', function(e) {
                                doTableSearch(this, options.tableId);
                            }).select().focus();
                        },
                    };
                    return pub;
                })(window.jQuery);
                window.yii.TableSearch.init(options);
            }
        </script>
        <?php

        $this->view->registerJs('setupTableSearch('.\yii\helpers\Json::encode([
                'formId' => 'megaSearch',
                'tableId' => 'cmsTable',
            ]).')', \yii\web\View::POS_LOAD);
    }
}