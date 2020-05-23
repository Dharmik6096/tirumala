<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use kartik\grid\GridView;

?>
<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'staff_member_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->staffMemberCode, 'staff_member_name');
        }],
    ['attribute' => 'Status', 'value' => function($model) {
            return empty($model->disbursement_date) ? 'Process' : 'Disburse';
        }, 'filter' => FALSE],
    [
        'attribute' => 'disbursement_date',
        // 'filter' => Yii::$app->controls->search_date($searchModel, 'disbursement_date'),
        'width' => '200px',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->disbursement_date);
        }],
    ['attribute' => 'month',
        'value' => function($model) {
            return date('m/Y', strtotime($model->month));
        },
    ],
    ['attribute' => 'value',
        'value' => function($model) {
            return Yii::$app->general->decimalformat($model->value);
        },
    ],
    ['attribute' => 'effective_working_days',],
    ['attribute' => 'lwp',],
    ['attribute' => 'bank_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bankCode, 'bank_name');
        }, 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'branch_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->branchCode, 'branch_name');
        }, 'filter' => FALSE, 'visible' => FALSE],
    ['attribute' => 'designation_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->designationCode, 'designation_name');
        }, 'filter' => FALSE, 'visible' => FALSE],
];
$grid_option = [
    'id' => 'salary-process-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => TRUE
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>