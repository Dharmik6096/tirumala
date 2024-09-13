<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }],
        ['attribute' => 'dcs_ref_code', 'label' => Yii::t('app', 'DCS') . ' Ref Code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'filter' => TRUE],
        ['attribute' => 'created_by', 'label' => (Yii::t('app', 'Notification Sender')), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->userCode, 'name');
        }, 'filter' => FALSE],
        [
        'attribute' => 'entry_datetime',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->entry_datetime);
        }],
        ['attribute' => 'login_type', 'value' => function($model) {
            return Yii::$app->general->getStaticDropdownVal('user_login_type', $model, 'login_type');
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('user_login_type', $searchModel, 'login_type')],
        ['attribute' => 'receiver_type', 'value' => function($model) {
            return Yii::$app->general->getStaticDropdownVal('receiver_type', $model, 'receiver_type');
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('receiver_type', $searchModel, 'receiver_type')],
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
        ['attribute' => 'notificaton_type', 'value' => function($model) {
            return Yii::$app->general->getStaticDropdownVal('notification_type', $model, 'notification_type');
        }, 'filter' => FALSE],
        ['attribute' => 'message'],
        ['attribute' => 'title', 'filter' => FALSE],
        ['attribute' => 'campaign_name', 'filter' => FALSE, 'visible' => false],
        ['attribute' => 'from_date', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }, 'visible' => false, 'filter' => FALSE],
        ['attribute' => 'to_date', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }, 'visible' => false, 'filter' => FALSE],
        ['attribute' => 'auto_scrolling', 'label' => Yii::t('app', 'Is Auto Scrolling'), 'value' => function($model) {
            return $model->auto_scrolling == 1 ? 'Active' : 'In Active';
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'bulk-notification-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
//        'update' => function ($url, $model) {
//            $class = !empty($model->status) ? 'disabled' : '';
//            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => '' . $class, 'data-val' => $model->bulk_notification_id];
//            return GhostHtml::a('<i class="fa fa-pencil"></i>', $url, $options);
//        },
        'mapping' => function ($url, $model) {
            $class = ($model->login_type == 'all') ? 'disabled' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Applicability', 'class' => '' . $class,];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/sms/tbl-bulk-notification/bulk-notification-applicability', 'id' => $model->bulk_notification_id], $options);
        },
        'delete' => ['option' => 'bulk_notification_id,bulk_notification_id,tbl-bulk-notification/delete'],
        'view_document' => function ($url, $model) {
            $document_url = $model->file_path;
            $class = ($document_url != '') ? '' : 'disabled';
            $options = ['target' => '_blank', 'class' => '' . $class,];
            return GhostHtml::a('<i class="fa fa-eye"></i>', $document_url, $options);
        },
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
