<?php

use kartik\grid\GridView;
?>

<div class="">
    <h5 class="panel-heading"><?= Yii::t('app', 'MPP Collection Details') ?></h5>

    <?php
    $attribute = [
        ['attribute' => 'dcs_code',
            'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
            }, 'filter' => false],
        ['label' => Yii::t('app', 'Member Code'), 'attribute' => 'member_code', 'value' => function($model) {
                return substr($model->member_code, -4);
            }, 'visible' => TRUE, 'filter' => false],
        ['attribute' => 'member_code', 'label' => Yii::t('app', 'Member'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
            }, 'filter' => false],
        [
            'attribute' => 'date_time_of_collection',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => ['format' => 'dd-mm-yyyy',
                    'autoclose' => true]
            ],
            'value' => function($model) {
                return Yii::$app->controls->view_date($model->date_time_of_collection);
            }, 'filter' => FALSE],
        ['attribute' => 'shift_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
            }, 'filter' => FALSE],
        ['attribute' => 'sample_no', 'filter' => FALSE],
        ['attribute' => 'milk_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->milkTypeCode, 'animal_type_name');
            }, 'filter' => FALSE],
        ['attribute' => 'qty', 'filter' => FALSE],
        ['attribute' => 'fat', 'filter' => FALSE],
        ['attribute' => 'snf', 'filter' => FALSE],
        ['attribute' => 'clr', 'filter' => FALSE],
        ['attribute' => 'rtpl', 'filter' => FALSE],
        ['attribute' => 'amount', 'filter' => FALSE],
        ['attribute' => 'status', 'filter' => FALSE],
    ];


    $grid_option = [
        'id' => 'mpp-coll-grid',
        'attributes' => $attribute,
        'active_column' => FALSE,
        'default_sorting' => FALSE
    ];
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>

</div>