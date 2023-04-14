<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccCode, 'name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => false],
        ['attribute' => 'user_type', 'visible' => true, 'filter' => true],
        ['attribute' => 'member_code', 'visible' => true, 'filter' => true],
        ['attribute' => 'member_name', 'value' => function($model) {
            if (!empty($model->member_code)) {
                return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
            } else {
                return $model->member_name;
            }
        }, 'vAlign' => 'middle', 'visible' => true, 'filter' => true],
//        ['attribute' => 'member_name', 'visible' => true, 'filter' => true],
    ['attribute' => 'mobile_no', 'visible' => true, 'filter' => true],
        ['attribute' => 'address', 'visible' => true, 'filter' => true],
        ['attribute' => 'ai_request_for', 'visible' => true, 'filter' => true],
        ['attribute' => 'animal_inspector_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->animalInspectorCode, 'ai_name');
        }],
        [
        'attribute' => 'expected_visit_date', 'filter' => true,
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->expected_visit_date);
        },
    ],
        ['attribute' => 'remarks', 'visible' => true, 'filter' => true],
        ['attribute' => 'status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('animal_req_status', $searchModel, 'status'),
        'value' => function ($model) {
            return (isset($model->status) && $model->status != '') ? Yii::$app->dropdown->getRecords('animal_req_status')['data'][$model->status] : '';
        },],
];

$grid_option = [
    'id' => 'animal-inspector-request-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'update' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
