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
    [
        'attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function ($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
    ['attribute' => 'shift_code', 'value' => 'shiftCode.shift', 'vAlign' => 'middle',],
    ['attribute' => 'applicable_code', 'value' => 'applicable_code', 'vAlign' => 'middle',],
    ['attribute' => 'applicable_for', 'value' => 'applicable_for', 'vAlign' => 'middle',],
    ['attribute' => 'Party_name', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->partyCode, 'party_name');
        }, 'vAlign' => 'middle',],
];

$grid_option = [
    'id' => 'purchase-rate-applicability-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['view', 'id' => Yii::$app->request->get('id')]);
?>