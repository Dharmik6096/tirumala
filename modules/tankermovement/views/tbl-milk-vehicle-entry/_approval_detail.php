<?php

use yii\web\View;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
?>
<div class="clearfix"></div>
<div class="hide_toolbar_only hide_filters_only">

    <?php
    $attribute = [
            ['attribute' => 'level', 'filter' => false],
            ['attribute' => 'approval_mode', 'filter' => false],
            ['attribute' => 'user_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->userCode, 'name');
            }, 'filter' => false],
            ['attribute' => 'login_type', 'filter' => false],
            ['attribute' => 'department', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->departmentId, 'department');
            }, 'filter' => false],
            ['attribute' => 'updated_by', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->updatedBy, 'name');
            }, 'filter' => false],
            ['attribute' => 'created_at', 'value' => function($model) {
                return Yii::$app->controls->view_datetime($model->created_at);
            }, 'filter' => false],
            ['attribute' => 'remarks', 'filter' => false],
    ];

    $grid_option = [
        'id' => 'milk-vehicle-approval-list',
        'attributes' => $attribute,
        'active_column' => FALSE,
        'default_sorting' => FALSE
    ];

    Yii::$app->grid->bind($approvalDataProvider, $approvalModel, $grid_option);
    ?>
</div>
