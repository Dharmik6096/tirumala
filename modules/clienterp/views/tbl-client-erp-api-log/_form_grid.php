<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use kartik\grid\GridView;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => FALSE],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'filter' => FALSE],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => FALSE],
    ['attribute' => 'end_point'],
    ['attribute' => 'request_url'],
    ['attribute' => 'date1', 'filter' => FALSE],
    [
        'attribute' => 'date2',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->date2);
        }],
    ['attribute' => 'desc1'],
    ['attribute' => 'request_timestamp', 'filter' => FALSE],
    ['attribute' => 'response_timestamp', 'filter' => FALSE],
    ['attribute' => 'status_code'],
    ['attribute' => 'status_response', 'filter' => array('SUCCESS'=>'SUCCESS','ERROR'=>'ERROR'),],
];

$grid_option = [
    'id' => 'product-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'log_view' => function ($url, $model) {
            $options = ['data-val' => $model->log_id, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View Logs'];
            return GhostHtml::a('<i class="fa fa-eye"></i>', ['/clienterp/tbl-client-erp-api-log/view', 'id' => $model->log_id, 'erp_process_name' => 1], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
