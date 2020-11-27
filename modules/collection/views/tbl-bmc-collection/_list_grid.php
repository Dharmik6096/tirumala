<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>
<div class="col-sm-12 padding-left-0 padding-right-0 ">
    <h5 class="panel-heading"><?= Yii::t('app', 'BMC Collection Details') ?></h5>


    <?php
    $attribute = [
        ['attribute' => 'customer_type', 'value' => 'customer_type', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
            }, 'filter' => FALSE],
        ['attribute' => 'customer_code', 'filter' => false],
        ['attribute' => 'ex_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                return Yii::$app->general->getCustomer($model, $model->customer_type, TRUE);
            }],
        ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
                return Yii::$app->general->getCustomer($model, $model->customer_type);
            }, 'filter' => false],
        ['attribute' => 'date_time_of_collection',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => ['format' => 'dd-mm-yyyy',
                    'autoclose' => true]
            ],
            'value' => function($model) {
                return Yii::$app->controls->view_date($model->date_time_of_collection);
            }, 'filter' => false],
        ['attribute' => 'shift_code', 'value' => 'shiftCode.shift', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'doc_no', 'vAlign' => 'middle', 'filter' => false],
//        ['attribute' => 'sample_no', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'milk_type_code', 'value' => function($model) {
                return isset($model->milkType) ? $model->milkType->animal_type_name : '';
            }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
                return isset($model->milkQualityType) ? $model->milkQualityType->milk_quality_type_name : '';
            }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'qty', 'value' => 'qty', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'fat', 'value' => 'fat', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'snf', 'value' => 'snf', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'clr', 'value' => 'clr', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'qty_mode',
            'filter' => FALSE,
            'value' => function ($model) {
                return isset($model->qty_mode) ? Yii::$app->dropdown->getRecords('p_ltr_kg')['data'][$model->qty_mode] : '';
            },],
        ['attribute' => 'converted_qty', 'value' => 'converted_qty', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'rtpl', 'value' => 'rtpl', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'amount', 'value' => 'amount', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'status', 'filter' => FALSE],
    ];


    $grid_option = [
        'id' => 'bmc-coll-grid',
        'attributes' => $attribute,
        'active_column' => FALSE,
        'default_sorting' => FALSE
//        'actions' => [
//            'edit' => function ($url, $model) {
//                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => 'edit-record', 'data-val' => $model->milk_collection_code, 'data-name' => $model->milk_collection_code, 'title' => Yii::t('app', 'Edit')];
//                return GhostHtml::a_alert('<i class="fa fa-pencil"></i>', ['/collection/tbl-bmc-collection/update-collection'], $options);
//            },
//        ]
    ];
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>


</div>