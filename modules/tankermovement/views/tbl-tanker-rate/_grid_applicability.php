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
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
        ['attribute' => 'shift_code', 'value' => 'shiftCode.shift', 'vAlign' => 'middle',],
    'dcs_code',
        ['attribute' => 'code_ex', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
        }, 'vAlign' => 'middle',],
        ['attribute' => 'dcs_name', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'vAlign' => 'middle',],
        ['attribute' => 'route_name', 'value' => 'dcsCode.routeCode.route_name', 'vAlign' => 'middle', 'label' => Yii::t('app', 'Route Name')],
        ['attribute' => 'is_download', 'filter' => array('1' => 'Yes', '0' => 'No'),
        'value' => function($model) {
            return ($model->is_download == 1) ? 'Yes' : 'No';
        }],
        ['attribute' => 'download_date_time',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->download_date_time);
        }],
];

$grid_option = [
    'id' => 'purchase-rate-applicability-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['view', 'id' => Yii::$app->request->get('id')]);
?>