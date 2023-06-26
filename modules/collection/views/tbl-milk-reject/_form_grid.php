<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        },
        'filter' => false, 'visible' => FALSE],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'source_org_type'],
    ['attribute' => 'source_org_code', 'filter' => false, 'value' => function($model) {
            return $model->source_org_type == 'bmc' ? Yii::$app->general->getforeignkey($model->sourceBmcCode, 'bmc_name') : Yii::$app->general->getforeignkey($model->sourcePlantCode, 'name');
        }],
    ['attribute' => 'dest_org_type'],
    ['attribute' => 'dest_org_code', 'filter' => false, 'value' => function($model) {
            return $model->source_org_type == 'bmc' ? Yii::$app->general->getCustomer($model, $model->dest_org_type) : Yii::$app->general->getforeignkey($model->destBmcCode, 'bmc_name');
        }],
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
    ['attribute' => 'shift_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'sample_no'],
    ['attribute' => 'milk_type_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkType, 'animal_type_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'fat'],
    ['attribute' => 'snf'],
    ['attribute' => 'clr'],
    ['attribute' => 'qty'],
    ['attribute' => 'rejection_reason_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->rejectReason, 'rejection_reason');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'return_type', 'value' => function($model) {
            return Yii::$app->dropdown->getRecords('return_type')['data'][$model->return_type];
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'action_taken', 'visible' => FALSE],
    ['attribute' => 'remarks', 'visible' => FALSE]
];
$grid_option = [
    'id' => 'reject-type',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'update' => true,
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
