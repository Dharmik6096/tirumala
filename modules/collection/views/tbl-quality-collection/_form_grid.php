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
    ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC Code'), 'visible' => false, 'filter' => false],
    ['attribute' => 'bmc_code', 'value' => 'bmcCode.bmc_name', 'vAlign' => 'middle', 'filter' => true, 'visible' => false],
    [
        'attribute' => 'date_time_of_collection',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->date_time_of_collection);
        }],
    ['attribute' => 'shift_code', 'filter' => false, 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }
    ],
    ['attribute' => 'doc_no'],
    ['attribute' => 'sample_no', 'value' => 'sample_no', 'vAlign' => 'middle', 'filter' => true],
    ['attribute' => 'fat', 'value' => 'fat', 'vAlign' => 'middle', 'filter' => Html::activeTextInput($searchModel, 'fat', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_fat', $operator, ['class' => 'form-control'])],
    ['attribute' => 'snf', 'value' => 'snf', 'vAlign' => 'middle', 'filter' => Html::activeTextInput($searchModel, 'snf', ['class' => 'form-control wd60']) . Html::activeDropDownList($searchModel, 'operator_snf', $operator, ['class' => 'form-control'])],
    ['attribute' => 'water', 'visible' => false, 'value' => 'water', 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'clr', 'visible' => false, 'value' => 'clr', 'vAlign' => 'middle', 'filter' => false],
];

$grid_option = [
    'id' => 'tbl-quality-collection-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>