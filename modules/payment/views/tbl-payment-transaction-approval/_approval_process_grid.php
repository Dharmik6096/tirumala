<?php
$attributes = [
    ['attribute' => 'level','filter' => false],
    ['attribute' => 'approval_mode','label' => Yii::t('app', 'Mode'), 'filter' => false],
    ['attribute' => 'user_code', 'value' => function($model){
        return !empty($model->userCode) ? Yii::$app->general->getforeignkey($model->userCode, 'name') : '';
    },'label' => Yii::t('app', 'User'), 'filter' => false],
    ['attribute' => 'login_type','filter' => false],
    ['attribute' => 'department', 'value' => function($model){
        return Yii::$app->general->getforeignkey($model->departmentId, 'department');
    }, 'filter' => false],
    ['attribute' => 'user_code', 'value' => function($model){
        return !empty($model->updatedBy) ? Yii::$app->general->getforeignkey($model->updatedBy, 'name') : '';
    },'label' => Yii::t('app', 'Status By'), 'filter' => false],
    ['attribute' => 'status', 'value' => function($model){
        $status = 'Pending';
        if ($model->status == '1') {
            $status = 'Approved';
        } else if ($model->status == '2'){
            $status = 'Rejected';
        }
        return  $status;
    }, 'filter' => false],
    [
        'attribute' => 'payment_date',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->created_at);
        },'label' => Yii::t('app', 'Date'),'filter' => false],
    ['attribute' => 'remarks','filter' => false],
];

$grid_option = [
    'id' => 'payment-approval-grid',
    'attributes' => $attributes,
    'active_column' => false,
    'default_sorting' => FALSE,
];

Yii::$app->grid->bind($approvalDataProvider, $approval, $grid_option, '', false);
?>

