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
            }, 'visible' => true, 'filter' => false],
            ['attribute' => 'plant_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->plantCode, 'name');
            }, 'visible' => false, 'filter' => false],
            ['attribute' => 'mcc_plant_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
            }, 'visible' => false, 'filter' => false],
            ['attribute' => 'bmc_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
            }, 'filter' => false],
            ['attribute' => 'milk_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->milkType, 'animal_type_name');
            }, 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->milkQualityType, 'milk_quality_type_name');
            }, 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'bmc_silos_info_code',
            'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->silosInfoCode, 'silo_no');
            }, 'filter' => false],
            ['attribute' => 'qty_diff_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->qtyDiffType, 'qty_diff_type_name');
            }, 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'fat', 'filter' => false],
            ['attribute' => 'snf', 'filter' => false],
            ['attribute' => 'opening_bal', 'filter' => false],
            ['attribute' => 'purchase_qty', 'filter' => false],
            ['attribute' => 'balance_qty', 'filter' => false],
            ['attribute' => 'qty_diff', 'filter' => false],
            ['attribute' => 'type', 'filter' => false],
            ['attribute' => 'remarks', 'filter' => false],
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
