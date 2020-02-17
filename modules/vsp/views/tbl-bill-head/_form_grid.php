<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
?>
<div class="grid-search clearfix">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
</div>
<?php
$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        },
        'filter' => false,],
    ['attribute' => 'bill_head_code'],
    ['attribute' => 'bill_head_name'],
    ['attribute' => 'bill_head_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('calc_type', $searchModel, 'bill_head_type'),
        'value' => function($model) {
            return isset($model->bill_head_type) ? Yii::$app->dropdown->getRecords('calc_type')['data'][$model->bill_head_type] : 'N/A';
        },],
    ['attribute' => 'default_bill_head_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->defaultBillHeadCode, 'default_bill_head_name');
        },],
    ['attribute' => 'general_formula'],
    ['attribute' => 'sequence_no'],
];
$grid_option = [
    'id' => 'bill-head-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'mapping' => function ($url, $model) {
            $disable = ($model->is_active == 0) ? 'disabled' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Applicability', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/vsp/tbl-bill-head/bill-head-applicability', 'id' => $model->bill_head_code], $options);
        },
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>