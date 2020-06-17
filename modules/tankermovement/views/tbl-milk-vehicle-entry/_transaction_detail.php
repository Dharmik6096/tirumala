<?php

use yii\web\View;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
?>
<div class="clearfix"></div>
<div class="hide_toolbar_only hide_filters_only">

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
        'water',
        'temp',
        'clr',
        'protein',
        'density',
        'lactose',
        'freezing_point',
    ];

    $grid_option = [
        'id' => 'milk-vehicle-txn-list',
        'attributes' => $attribute,
        'active_column' => FALSE,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
