<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use kartik\grid\GridView;

$operator = ['=' => '=', '>' => '>', '<' => '<', '>=' => '>=', '<=' => '<='];
?>
<?php

$attribute = [
    ['attribute' => 'route_code', 'label' => Yii::t('app', 'Route Code'), 'visible' => false, 'filter' => false],
    ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
        }, 'filter' => false],
    ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Society Code'), 'visible' => false, 'filter' => false],
    ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'vAlign' => 'middle', 'filter' => true],
    [
        'attribute' => 'collection_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->collection_date);
}],
    ['attribute' => 'shift_code', 'filter' => false, 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }
    ],
    ['attribute' => 'doc_no'],
    ['attribute' => 'sample_no', 'value' => 'sample_no', 'vAlign' => 'middle', 'filter' => true],
    ['attribute' => 'milk_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
        }
    ],
    ['attribute' => 'milk_quality_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkQualityTypeCode, 'milk_quality_type_name');
        }, 'visible' => false, 'filter' => false
    ],
    ['attribute' => 'quantity', 'value' => 'quantity', 'vAlign' => 'middle', 'vAlign' => 'middle', 'filter' => Html::activeTextInput($searchModel, 'quantity', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_qty', $operator, ['class' => 'form-control'])],
    ['attribute' => 'converted_quantity', 'visible' => false, 'filter' => false],
];

$grid_option = [
    'id' => 'tbl-weight-collection-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>