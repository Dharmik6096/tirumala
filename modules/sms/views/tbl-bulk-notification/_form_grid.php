<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'member_code', 'filter' => false],
    ['attribute' => 'member_code', 'label' => Yii::t('app', 'Member Name'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
        }, 'vAlign' => 'middle'],
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
    [
        'attribute' => 'status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('file_status', $searchModel, 'status'),
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('file_status')['data'][$model->status]) ? Yii::$app->dropdown->getRecords('file_status')['data'][$model->status] : '';
        }],
    ['attribute' => 'app_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->apiMaster, 'api_name');
        }, 'vAlign' => 'middle'],
    'login_type',
    'campaign_name',
    'title',
    'message',
];

$grid_option = [
    'id' => 'bulk-notification-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => true,
        'edit' => function ($url, $model) {
            $class = $model->status == 0 ? '' : 'disabled';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit', 'class' => 'edit-record ' . $class, 'title' => Yii::t('app', 'Edit')];
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', ['/sms/tbl-bulk-notification/update', 'id' => $model->bulk_notification_id], $options);
        },
        'delete' => ['option' => 'bulk_notification_id,bulk_notification_id,tbl-bulk-notification/delete,disableDelete()'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
