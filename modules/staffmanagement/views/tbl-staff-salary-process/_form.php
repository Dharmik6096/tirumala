<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use kartik\grid\GridView;
?>
<div class="">
    <?php
    $attribute = [
        ['attribute' => 'staff_member_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->staffMemberCode, 'staff_member_name');
            }, 'filter' => FALSE],
        [
            'attribute' => 'disbursement_date',
            'width' => '200px',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => ['format' => 'dd-mm-yyyy',
                    'autoclose' => true]
            ],
            'value' => function($model) {
                return Yii::$app->controls->view_date($model->disbursement_date);
            }, 'filter' => FALSE],
        ['attribute' => 'Status', 'value' => function($model) {
                return empty($model->disbursement_date) ? 'Process' : 'Disburse';
            }, 'filter' => FALSE],
        ['attribute' => 'month',
            'value' => function($model) {
                return date('m/Y', strtotime($model->month));
            }, 'filter' => FALSE
        ],
        ['attribute' => 'value',
            'value' => function($model) {
                return Yii::$app->general->decimalformat($model->value);
            }, 'filter' => FALSE
        ],
        ['attribute' => 'effective_working_days', 'filter' => FALSE],
        ['attribute' => 'lwp', 'filter' => FALSE],
        ['attribute' => 'bank_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->bankCode, 'bank_name');
            }, 'filter' => FALSE],
        ['attribute' => 'branch_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->branchCode, 'branch_name');
            }, 'filter' => FALSE],
        ['attribute' => 'designation_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->designationCode, 'designation_name');
            }, 'filter' => FALSE],
    ];
    $grid_option = [
        'id' => 'salary-process-inner-list',
        'attributes' => $attribute,
        'active_column' => false,
    ];
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
