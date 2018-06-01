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
    ['attribute' => 'mccid', 'value' => 'bmcCode.bmc_name', 'vAlign' => 'middle', 'filter'=>true],
    ['attribute' => 'sampleno', 'value' => 'sampleno', 'vAlign' => 'middle', 'filter'=>true],
    ['attribute' => 'fat', 'value' => 'fat', 'vAlign' => 'middle', 'filter' => Html::activeTextInput($searchModel, 'fat', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_fat', $operator, ['class' => 'form-control'])],
    ['attribute' => 'snf', 'value' => 'snf', 'vAlign' => 'middle', 'filter' => Html::activeTextInput($searchModel, 'snf', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_snf', $operator, ['class' => 'form-control'])],
    ['attribute' => 'water', 'value' => 'water', 'vAlign' => 'middle', 'filter'=>false],
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
    'id' => 'testinvillagequality',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>