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
    ['attribute' => 'vlccid', 'value' => 'dcsCode.dcs_name', 'vAlign' => 'middle', 'filter'=>true],
    ['attribute' => 'milktype', 'value' => function($model) {
            return isset($model->milktype) ? Yii::$app->dropdown->getRecords('milktype')['data'][$model->milktype] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('milktype', $searchModel,'milktype')],
    ['attribute' => 'sampleno', 'value' => 'sampleno', 'vAlign' => 'middle', 'filter'=>true],
    ['attribute' => 'qty', 'value' => 'qty', 'vAlign' => 'middle', 'vAlign' => 'middle', 'filter' => Html::activeTextInput($searchModel, 'qty', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_qty', $operator, ['class' => 'form-control'])],
    [
        'attribute' => 'dtdate',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
    return Yii::$app->controls->view_date($model->dtdate);
}],
    ['attribute' => 'shift', 'value' => function($model) {
            return isset($model->shift) ? Yii::$app->dropdown->getRecords('shift')['data'][$model->shift] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('shift', $searchModel,'shift')],
];

$grid_option = [
    'id' => 'testinvillageweight',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>