<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
?>

<?php
$attribute = [
    ['attribute' => 'receiver_detail', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->userCode, 'name');
        }],
    ['attribute' => 'message', 'contentOptions' => ['class' => 'is_ellipsis']],
    ['attribute' => 'header_info'],
    ['attribute' => 'send_status', 'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('send_status')['data'][$model->send_status]) ? Yii::$app->dropdown->getRecords('send_status')['data'][$model->send_status] : 'Pending';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('send_status', $searchModel, 'send_status')],
    ['attribute' => 'module_type', 'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('module_type')['data'][$model->module_type]) ? Yii::$app->dropdown->getRecords('module_type')['data'][$model->module_type] : '';
        }, 'filter' => Yii::$app->dropdown->dropdownfilterStatic('module_type', $searchModel, 'module_type')],
    ['attribute' => 'entry_datetime', 'value' => function ($model) {
            return Yii::$app->controls->view_datetime($model->entry_datetime);
        }, 'filter' => false],
    ['attribute' => 'send_mail', 'label' => 'Send Mail Count'],
    ['attribute' => 'mail_receiver_detail'],
];

$grid_option = [
    'id' => 'alert-notification-portal-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
    'actions' => [
        'view-detail' => function ($url, $model) {
            $url = Url::to(['/sms/tbl-alert-notification-portal/send-mail', 'alert_notification_id' => $model->alert_notification_id]);
            return GhostHtml::a('<i class="fa fa-paper-plane"></i>', $url, ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Send Mail', 'class' => 'send-mail', 'data-alert_notification_id' => $model->alert_notification_id]);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<div id="SendMailContainer"></div>
<?php
$script = "
$(document).ready(function(){
    $(document).on('click', '.send-mail', function(e){
        e.preventDefault();
        $('#pageloader').show();
        $('#loadercontent').show();
        var alert_notification_id = $(this).attr('data-alert_notification_id');
        $.ajax({
            type: 'get',
            url: '" . Url::to(['send-mail']) . "',
            data: {'alert_notification_id': alert_notification_id},
            success: function(data) {     
                $('#SendMailContainer').html(data);
                $('#SendMailModal').modal('show'); 
                $('#loadercontent').hide();
                $('#pageloader').hide();
            },    
            error: function(data) {    
                $('#loadercontent').hide();
                $('#pageloader').hide();
            }
        });
    });
});
";
$this->registerJs($script, View::POS_END, 'send-mail-script');
?>
