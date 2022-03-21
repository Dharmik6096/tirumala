<?php

use kartik\grid\GridView;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Location Wise KM'));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
        <?php
        $attribute = [
            ['attribute' => 'union_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
                }, 'filter' => FALSE],
            ['attribute' => 'from_type', 'value' => function($model) {
                    return strtoupper($model->from_type);
                }, 'filter' => FALSE,
            ],
            ['attribute' => 'from_dest', 'value' => function($model) {
                    $rel = Yii::$app->general->getDestRelation($model->from_type);
                    $att = strtolower($model->from_type) == 'bmc' ? 'bmc_name' : (strtolower($model->from_type) == 'vendor' ? 'customer_name' : 'name');
                    if (!empty($rel))
                        return Yii::$app->general->getforeignkey($model->{$rel . 'Source'}, $att);
                }, 'filter' => false],
            ['attribute' => 'to_type', 'value' => function($model) {
                    return strtoupper($model->to_type);
                }, 'filter' => FALSE,],
            ['attribute' => 'to_dest', 'value' => function($model) {
                    $rel = Yii::$app->general->getDestRelation($model->to_type);
                    $att = strtolower($model->to_type) == 'bmc' ? 'bmc_name' : (strtolower($model->to_type) == 'vendor' ? 'customer_name' : 'name');
                    if (!empty($rel))
                        return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att) ;
                }, 'filter' => false],
            [
                'attribute' => 'wef_date',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['format' => 'dd-mm-yyyy',
                        'autoclose' => true]
                ],
                'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }, 'filter' => FALSE,],
            ['attribute' => 'total_kms', 'filter' => FALSE,]
        ];

        $grid_option = [
            'id' => 'location-wise-km-list',
            'attributes' => $attribute,
            'active_column' => false,
        ];
        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
        ?>

    </div>
</div>
