<?php

namespace app\components;

use nullref\datatable\DataTable;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

class CustomDataTable extends DataTable {

    public function init() {
        parent::init();
        CustomDataTableAsset::register($this->getView());
    }

    public function run() {
        $id = isset($this->id) ? $this->id : $this->getId();
        echo Html::beginTag('table', ArrayHelper::merge(['id' => $id], $this->tableOptions));
        echo Html::endTag('table');

        $globalVariable = $this->globalVariable ? "window[$id] =" : '';
        if ($this->withColumnFilter) {
            $encodedParams = Json::encode($this->getParams());
            $js = "
                $.fn.dataTableExt.sErrMode = 'console';
                (function() {
                    var params = {$encodedParams};
                    var table;
                    {$globalVariable} table = jQuery('#{$id}').DataTable(params);
                    var filterRow = jQuery('<tr></tr>');
                    jQuery('#{$id} thead tr th').each(function(i) {
                        var cell = jQuery('<td></td>')
                            .attr('colspan', jQuery(this).attr('colspan'))
                            .attr('class', jQuery(this).attr('class'))
                            .attr('tabindex', jQuery(this).attr('tabindex'))
                            .attr('aria-controls', jQuery(this).attr('aria-controls'))
                            .removeClass('sorting sorting_disabled')
                            .appendTo(filterRow);

                        if (params.columns && params.columns[i] && params.columns[i].renderFilter) {
                            cell.html(jQuery.isFunction(params.columns[i].renderFilter) ? params.columns[i].renderFilter(table) : params.columns[i].renderFilter);
                        }
                    });
                    jQuery('#{$id} thead').append(filterRow);
                    jQuery('#{$id} thead tr:eq(1) td').each(function(i) {
                        jQuery(':input', this).on('keyup change', function() {
                            if (table.column(i).search() !== jQuery(this).val()) {
                                table
                                    .column(i)
                                    .search(jQuery(this).val())
                                    .draw();
                            }
                        });
                    });
                })();
            ";

            $this->getView()->registerJs($js);
        } else {
            $this->getView()->registerJs($globalVariable . 'jQuery("#' . $id . '").DataTable(' . Json::encode($this->getParams()) . ');');
        }
    }

}

?>