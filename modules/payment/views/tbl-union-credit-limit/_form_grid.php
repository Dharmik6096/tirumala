<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
?>

<?php
$attribute = [
//    ['attribute' => 'union_credit_limit_code','value'=>'union_credit_limit_code',],
    ['attribute' => 'union_code','value'=>'unionCode.union_name',],
    ['attribute' => 'credit_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('credit_type_data', $searchModel),
        'value' => function($model) {
        return (Yii::$app->dropdown->getRecords('credit_type_data')['data'][$model->credit_type] != '') ? Yii::$app->dropdown->getRecords('credit_type_data')['data'][$model->credit_type] : '';}, 'vAlign' => 'middle'],
    ['attribute' => 'credit_value','value'=>'credit_value',],
    ['attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
        return Yii::$app->controls->view_date($model->wef_date);
    }],
];

$grid_option = [
    'id' => 'union-credit-limit-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'update' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>