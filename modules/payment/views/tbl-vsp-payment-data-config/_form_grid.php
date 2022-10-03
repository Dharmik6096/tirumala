<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\web\View;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'label' => Yii::t('app', 'Company'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
        ['attribute' => 'plant_code', 'label' => Yii::t('app', 'Plant'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
        ['attribute' => 'mcc_plant_code', 'label' => Yii::t('app', 'MCC'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
        ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['label' => Yii::t('app', 'Soc. Code'), 'visible' => TRUE, 'attribute' => 'dcs_code', 'filter' => true],
        ['label' => Yii::t('app', 'Ex. Code'), 'attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
        }, 'filter' => false],
        ['label' => Yii::t('app', 'Ref. Code'), 'visible' => TRUE, 'attribute' => 'ref_code', 'filter' => true,
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        },],
        ['attribute' => 'dcs_name', 'visible' => TRUE, 'filter' => true, 'label' => Yii::t('app', 'Society Name'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'vAlign' => 'middle'],
//    'vsp_payment_data_config_code',
    [
        'attribute' => 'date_time_of_collection',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true,
                'filter' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->date_time_of_collection);
        }],
        ['attribute' => 'shift_code', 'value' => 'shiftCode.shift', 'vAlign' => 'middle', 'filter' => false],
];

$grid_option = [
    'id' => 'tbl-vsp-payment-data-config-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
//        'view' => true,
        'update' => true,
//        'update' => function ($url, $model) {
//            $disable = '';
//            $options = ['title' => Yii::t('app', 'update'), 'class' => $disable];
//            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/general/tbl-mcc-refrigeration-chart/update', 'id' => $model->refrigeration_chart_code], $options);
//        },
        'delete' => ['option' => 'mcc_plant_code,vsp_payment_data_config_code,tbl-vsp-payment-data-config/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>



