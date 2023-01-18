<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
        ['attribute' => 'login_type', 'value' => function($model) {
            return Yii::$app->general->getStaticDropdownVal('user_login_type', $model, 'login_type');
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('user_login_type', $searchModel, 'login_type')],
        ['attribute' => 'receiver_type', 'value' => function($model) {
            return isset($model->receiver_type) ? Yii::$app->dropdown->getRecords('receiver_type')['data'][$model->receiver_type] : '';
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
        ['attribute' => 'message'],
];

$grid_option = [
    'id' => 'bulk-notification-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'update' => function ($url, $model) {
            $class = !empty($model->status) ? 'disabled' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => '' . $class, 'data-val' => $model->bulk_notification_id];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', $url, $options);
        },
        'mapping' => function ($url, $model) {
            $class = ($model->login_type == 'all') ? 'disabled' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Applicability', 'class' => '' . $class,];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/sms/tbl-bulk-notification/bulk-notification-applicability', 'id' => $model->bulk_notification_id], $options);
        },
        'delete' => ['option' => 'bulk_notification_id,bulk_notification_id,tbl-bulk-notification/delete'],
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
