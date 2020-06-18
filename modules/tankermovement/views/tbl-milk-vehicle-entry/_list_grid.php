<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>
<div class="col-sm-12 padding-left-0 padding-right-0 ">
    <h5 class="panel-heading"><?= Yii::t('app', 'Milk Receipt Transaction Detail') ?></h5>


    <?php
    $attribute = [
        ['attribute' => 'entry_type', 'filter' => false],
        ['attribute' => 'grn_no', 'filter' => false],
        ['attribute' => 'challan_no', 'filter' => false],
        ['attribute' => 'source_org_code', 'filter' => false],
        ['attribute' => 'source_org_type', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->bmcCodeSource, 'bmc_name') . '-' . strtoupper($model->source_org_type);
            }, 'filter' => false],
        ['attribute' => 'destination_code', 'filter' => false],
        ['attribute' => 'destination_type', 'value' => function($model) {
                $rel = Yii::$app->general->getDestRelation($model->destination_type);
                $att = strtolower($model->destination_type) == 'bmc' ? 'bmc_name' : (strtolower($model->destination_type) == 'vendor' ? 'customer_name' : 'name');
                if (!empty($rel))
                    return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att) . '-' . strtoupper($model->destination_type);
            }, 'filter' => false],
        ['attribute' => 'milk_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->milkType, 'animal_type_name');
            }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->milkQualityType, 'milk_quality_type_name');
            }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'chamber_no', 'filter' => false],
        ['attribute' => 'fat', 'value' => 'fat', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'snf', 'value' => 'snf', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'clr', 'value' => 'clr', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'chamber_quantity', 'value' => 'chamber_quantity', 'vAlign' => 'middle', 'filter' => false],
    ];


    $grid_option = [
        'id' => 'milk-vehicle-tr-grid',
        'attributes' => $attribute,
        'active_column' => FALSE,
        'actions' => [
            'edit' => function ($url, $model) {
                $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => 'edit-record', 'data-val' => $model->milk_vehicle_entry_transaction_code, 'data-name' => $model->milk_vehicle_entry_transaction_code, 'title' => Yii::t('app', 'Edit')];
                return GhostHtml::a_alert('<i class="fa fa-pencil"></i>', ['/tankermovement/tbl-milk-vehicle-entry/update-transaction'], $options);
            },
        ]
    ];
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>


</div>