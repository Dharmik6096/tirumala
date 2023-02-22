<?php

use kartik\grid\GridView;
?>

<div class="">
    <h5 class="panel-heading"><?= Yii::t('app', 'Indent Master Details') ?></h5>

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
            'attribute' => 'indent_date',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => ['format' => 'dd-mm-yyyy',
                    'autoclose' => true]
            ],
            'value' => function($model) {
                return Yii::$app->controls->view_date($model->indent_date);
            }, 'filter' => FALSE],
        ['attribute' => 'product_code',
            'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
            }, 'filter' => false],
        ['attribute' => 'qty', 'filter' => FALSE],
    ];


    $grid_option = [
        'id' => 'indent-master-grid',
        'attributes' => $attribute,
        'active_column' => FALSE,
        'default_sorting' => FALSE
    ];
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>

</div>