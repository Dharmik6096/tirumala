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
    ['attribute' => 'transporter_payment_head'],
//    ['attribute' => 'transporter_code'],
    ['attribute' => 'type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('calc_type', $searchModel),'value' => function($model) {
        return (Yii::$app->dropdown->getRecords('calc_type')['data'][$model->type] != '') ? Yii::$app->dropdown->getRecords('calc_type')['data'][$model->type] : '';},],
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
