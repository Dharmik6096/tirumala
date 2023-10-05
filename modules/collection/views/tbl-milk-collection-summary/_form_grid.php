<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

$operator = ['=' => '=', '>' => '>', '<' => '<', '>=' => '>=', '<=' => '<='];
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'filter' => false, 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }],
        ['attribute' => 'plant_code', 'filter' => false, 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }],
        ['attribute' => 'mcc_plant_code', 'filter' => false, 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }],
        ['attribute' => 'bmc_code', 'filter' => false, 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }],
        ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => false],
        ['label' => Yii::t('app', 'Ref. Code'), 'attribute' => 'ref_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        },],
        ['attribute' => 'date_time_of_collection',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->date_time_of_collection);
        }],
        ['attribute' => 'shift_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }, 'vAlign' => 'middle', 'filter' => Yii::$app->dropdown->dropdownfilter('shift', $searchModel, 'shift_code', Yii::t('app', 'Select'))],
        ['attribute' => 'avg_fat', 'filter' => Html::activeTextInput($searchModel, 'avg_fat', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_fat', $operator, ['class' => 'form-control'])],
        ['attribute' => 'avg_snf', 'filter' => Html::activeTextInput($searchModel, 'avg_snf', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_snf', $operator, ['class' => 'form-control'])],
        ['attribute' => 'total_qty', 'filter' => Html::activeTextInput($searchModel, 'total_qty', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_qty', $operator, ['class' => 'form-control'])],
        ['attribute' => 'total_amount'],
        ['attribute' => 'kg_fat', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'kg_snf', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'sample_count', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'avg_rate', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'auto_count', 'filter' => false, 'visible' => FALSE],
        ['attribute' => 'manual_count', 'filter' => false, 'visible' => FALSE],
];

$grid_option = [
    'id' => 'milk-collection-summary-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => TRUE,
        'edit' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Update'];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/collection/tbl-milk-collection-summary/update', 'id' => $model->milk_collection_summary_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
