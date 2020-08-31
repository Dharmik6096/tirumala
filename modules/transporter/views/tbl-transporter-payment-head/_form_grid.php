<?php

use yii\helpers\Html;
?>
<div class="grid-search">
    <?php
//        echo $this->render('_search', ['model' => $searchModel]);
    ?>
</div>
<?php
$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false],
    ['attribute' => 'transporter_payment_head'],
//    ['attribute' => 'transporter_code'],
    ['attribute' => 'type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('calc_type', $searchModel), 'value' => function($model) {
            return (Yii::$app->dropdown->getRecords('calc_type')['data'][$model->type] != '') ? Yii::$app->dropdown->getRecords('calc_type')['data'][$model->type] : '';
        },],
    ['attribute' => 'is_default', 'value' => function($model) {
            return $model->is_default == 1 ? 'Yes' : 'No';
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'transporter-payment-head-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
        'update' => true,
//        'delete' => ['option' => 'transporter_name,transporter_code,tbl-transporter/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
