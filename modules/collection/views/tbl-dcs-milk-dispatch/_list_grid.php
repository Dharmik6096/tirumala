<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>
<div class="col-sm-12 padding-left-0 padding-right-0 ">
    <h5 class="panel-heading"><?= Yii::t('app', 'Milk Dispatch Details') ?></h5>


    <?php
    $attribute = [
        ['attribute' => 'dcs_code', 'filter' => false],
        ['attribute' => 'ex_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
            }],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
            }, 'filter' => false],
        ['attribute' => 'date_time_of_dispatch',
            'filter' => false,
            'value' => function($model) {
                return Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->dcsMilkDispatch, 'date_time_of_dispatch'));
            }, 'filter' => false],
        ['attribute' => 'shift_code', 'label' => Yii::t('app', 'Shift'), 'value' => function($model) {
                return Yii::$app->general->getmultiforeignkey($model->dcsMilkDispatch, ['shiftCode'], 'shift');
            }, 'vAlign' => 'middle', 'filter' => false],
//        ['attribute' => 'doc_no', 'vAlign' => 'middle', 'filter' => false],
//        ['attribute' => 'sample_no', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'milk_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->milkType, 'animal_type_name');
            }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'milk_quality_type_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->milkQualityType, 'milk_quality_type_name');
            }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'dispatch_qty', 'filter' => false],
        ['attribute' => 'avg_fat', 'filter' => false],
        ['attribute' => 'avg_snf', 'filter' => false],
        ['attribute' => 'avg_clr', 'filter' => false],
        ['attribute' => 'rtpl', 'filter' => false],
        ['attribute' => 'total_amount', 'filter' => false],
        ['attribute' => 'status', 'filter' => FALSE],
    ];


    $grid_option = [
        'id' => 'milk-dispatch-grid',
        'attributes' => $attribute,
        'active_column' => FALSE,
        'default_sorting' => FALSE
    ];
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>