<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'filter' => false, 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => FALSE],
    ['attribute' => 'plant_code', 'filter' => false, 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => FALSE],
    ['attribute' => 'mcc_plant_code', 'filter' => false, 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'visible' => FALSE],
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
    ['attribute' => 'android_key'],
    ['attribute' => 'hour'],
    ['attribute' => 'app_password'],
    [
        'attribute' => 'created_at',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->created_at);
        }],
    [
        'attribute' => 'created_by',
        'value' => function($model) {
            return str_replace('01#', '', Yii::$app->general->getforeignkey($model->userCode, 'username'));
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'action-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
