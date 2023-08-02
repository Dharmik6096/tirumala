<?php

use kartik\grid\GridView;
?>

<div class="">
    <h5 class="panel-heading"><?= Yii::t('app', 'Indent Master Details') ?></h5>

    <?php
    $attribute = [
        ['label' => Yii::t('app', 'DCS Code'), 'attribute' => 'dcs_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code');
            }, 'visible' => TRUE, 'filter' => false],
        ['label' => Yii::t('app', 'Ref Code'), 'attribute' => 'dcs_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
            }, 'visible' => TRUE, 'filter' => false],
        ['attribute' => 'dcs_code',
            'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
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
        ['attribute' => 'rate', 'filter' => FALSE],
        ['attribute' => 'amount', 'filter' => FALSE],
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