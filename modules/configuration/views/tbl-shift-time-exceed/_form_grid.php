<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;

?>
<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'bmc_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'dcs_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'visible' => true, 'filter' => false],
        ['attribute' => 'date_time_of_collection',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->date_time_of_collection);
        }],
        ['attribute' => 'org_type', 'visible' => true],
        ['attribute' => 'org_code', 'visible' => true],
        ['attribute' => 'shift_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }, 'vAlign' => 'middle', 'filter' => FALSE],
        ['attribute' => 'standard_time', 'filter' => true],
        ['attribute' => 'exceed_time', 'filter' => true],
        ['attribute' => 'remarks', 'filter' => true],
        ['attribute' => 'status', 'filter' => false],
        ['attribute' => 'status_datetime', 'filter' => false],
        ['attribute' => 'status_by', 'filter' => false],
        ['attribute' => 'status_remarks', 'filter' => false],
];

$grid_option = [
    'id' => 'tbl-shift-time-android',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'views' => function($url, $model) use ($pending_approval) {
            $icon = '<i class="fa fa-eye"></i>';
            $url = ['/configuration/tbl-shift-time-exceed/view', 'id' => $model->shift_time_exceed_code];
            if ($pending_approval) {
                $icon = '<i class="fa fa-check"></i>';
                $url = ['/configuration/tbl-shift-time-exceed/approve-shift-time-exceed', 'id' => $model->process_approval_code];

                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Approve AMCS -  Shift Time Exceed Provision'];
                return Html::a($icon, $url, $options);
            } else {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Shift Time Exceed View'];
                return Html::a($icon, $url, $options);
            }
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>