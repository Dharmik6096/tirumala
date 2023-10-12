<?php

use yii\web\View;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\helpers\Html;
?>
<div class="clearfix"></div>
<div class="hide_toolbar_only hide_filters_only">

    <?php
    $attribute = [
            ['attribute' => 'union_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
            }, 'visible' => true,],
            ['attribute' => 'plant_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->plantCode, 'name');
            }, 'visible' => false,],
            ['attribute' => 'mcc_plant_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
            }, 'visible' => false,],
            ['attribute' => 'bmc_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
            }],
            ['attribute' => 'milk_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->milkType, 'animal_type_name');
            }, 'vAlign' => 'middle'],
            ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->milkQualityType, 'milk_quality_type_name');
            }, 'vAlign' => 'middle'],
            ['attribute' => 'bmc_silos_info_code',
            'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->silosInfoCode, 'silo_no');
            }],
            ['attribute' => 'qty_diff_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->qtyDiffType, 'qty_diff_type_name');
            }, 'vAlign' => 'middle'],
            ['attribute' => 'fat'],
            ['attribute' => 'snf'],
            ['attribute' => 'opening_bal'],
            ['attribute' => 'purchase_qty'],
            ['attribute' => 'balance_qty'],
            ['attribute' => 'qty_diff'],
            ['attribute' => 'type'],
            ['attribute' => 'remarks'],
    ];

    $grid_option = [
        'id' => 'bmc-dispatch-stock-list',
        'attributes' => $attribute,
        'active_column' => FALSE,
        'actions' => [
            'edit' => function($url, $model) {
                $url = str_replace('edit', 'update', $url);
                $url = ['/tankermovement/tbl-bmc-dispatch-stock/update', 'id' => $model->bmc_dispatch_stock_code];
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Update'];
                return Html::a('<i class="fa fa-pencil"></i>', $url, $options);
            },
        ]
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
