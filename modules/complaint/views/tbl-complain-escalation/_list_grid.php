<?php

use kartik\grid\GridView;
?>

<div class="margin-top-20">
    <h5 class="panel-heading"><?= Yii::t('app', 'Complain Escalation Transaction') ?></h5>

    <?php
    $attribute = [
            ['attribute' => 'user_type', 'filter' => FALSE,
            'value' => function ($model) {
                return !empty($model->user_type) ? Yii::$app->dropdown->getRecords('login_type')['data'][$model->user_type] : '';
            },],
            ['attribute' => 'department', 'value' => function ($model) {
                return Yii::$app->general->getforeignkey($model->departmentId, 'department');
            }, 'filter' => FALSE],
            ['attribute' => 'level', 'filter' => FALSE],
            ['attribute' => 'escalation_time', 'filter' => FALSE],
    ];


    $grid_option = [
        'id' => 'escalation-transaction-list-grid',
        'attributes' => $attribute,
        'active_column' => FALSE,
        'default_sorting' => FALSE
    ];
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>

</div>