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
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'dcs_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }],
        ['attribute' => 'dcs_ref_code', 'label' => Yii::t('app', 'DCS') . ' Ref Code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        }, 'filter' => TRUE],
        ['attribute' => 'member_code', 'filter' => false, 'visible' => false],
        ['attribute' => 'member_code', 'label' => Yii::t('app', 'Member Name'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
        }, 'vAlign' => 'middle', 'visible' => false],
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
            return Yii::$app->general->getStaticDropdownVal('login_type', $model, 'login_type');
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('login_type', $searchModel, 'login_type')],
        ['attribute' => 'department', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->departmentId, 'department');
        }, 'filter' => true],
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
        ['attribute' => 'app_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->apiMaster, 'api_name');
        }, 'vAlign' => 'middle', 'visible' => false],
        ['attribute' => 'notificaton_type', 'value' => function($model) {
            return Yii::$app->general->getStaticDropdownVal('notification_type', $model, 'notification_type');
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('notification_type', $searchModel, 'notification_type'),],
        ['attribute' => 'message'],
        ['attribute' => 'title'],
        ['attribute' => 'campaign_name', 'visible' => false],
        ['attribute' => 'from_date', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        }, 'visible' => false, 'filter' => FALSE],
        ['attribute' => 'to_date', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        }, 'visible' => false, 'filter' => FALSE],
        ['attribute' => 'auto_scrolling', 'label' => Yii::t('app', 'Is Auto Scrolling'), 'value' => function($model) {
            return $model->auto_scrolling == 1 ? 'Active' : 'In Active';
        }, 'filter' => false],
        ['attribute' => 'filename',
        'format' => 'raw',
        'value' => function ($model) {
            $link = '';
            if (!empty($model->file_path)) {
                $link = Html::a($model->filename, $model->file_path, ['target' => '_blank']);
            }
            return $link;
        },
    ],
        [
        'attribute' => 'status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('file_status', $searchModel, 'status'),
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('file_status')['data'][$model->status]) ? Yii::$app->dropdown->getRecords('file_status')['data'][$model->status] : '';
        }],
];

$grid_option = [
    'id' => 'bulk-notification-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view' => true,
//        'edit' => function ($url, $model) {
//            $class = $model->status == 0 ? '' : 'disabled';
//            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => 'edit-record ' . $class, 'title' => Yii::t('app', 'Edit')];
//            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/sms/tbl-bulk-notification/update', 'id' => $model->bulk_notification_id], $options);
//        },
        'mapping' => function ($url, $model) {
            $class = ($model->login_type == 'all' || !in_array($model->notification_type, [1, 2, 3])) ? 'disabled' : '';
            $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Applicability', 'class' => '' . $class,];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/sms/tbl-bulk-notification/bulk-notification-applicability', 'id' => $model->bulk_notification_id], $options);
        },
        'view_document' => function ($url, $model) {
            $document_url = $model->file_path;
            $class = ($document_url != '') ? '' : 'disabled';
            $options = ['target' => '_blank', 'class' => '' . $class,];
            return GhostHtml::a('<i class="fa fa-file-pdf"></i>', $document_url, $options);
        },
        'delete' => ['option' => 'bulk_notification_id,bulk_notification_id,tbl-bulk-notification/delete,disableDelete()'],
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
