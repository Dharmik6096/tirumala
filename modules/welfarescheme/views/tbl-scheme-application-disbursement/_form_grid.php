<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'application_id', 'label' => Yii::t('app', 'Application Id')],
        ['attribute' => 'application_id', 'value' => function($model) {
            return $model->application_id . '/' . $model->applicationId->customer_type . '/' . Yii::$app->general->getCustomer($model->applicationId, $model->applicationId->customer_type) . '(' . Yii::$app->general->getCustomer($model->applicationId, $model->applicationId->customer_type, true) . ')/' . Yii::$app->general->getCustomer($model->applicationId, $model->applicationId->customer_type, FALSE, FALSE, true);
        }, 'filter' => false],
        ['attribute' => 'scheme_id', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->schemeId, 'scheme_name');
        }, 'filter' => false],
        [
        'attribute' => 'disburse_date',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->disburse_date);
        }, 'filter' => false,],
        ['attribute' => 'disburse_value'],
        ['attribute' => 'payment_mode'],
        ['attribute' => 'bank_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bankCode, 'bank_name');
        }],
        ['attribute' => 'branch_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->branchCode, 'branch_name');
        }],
        ['attribute' => 'ifsc'],
        ['attribute' => 'bank_account_no'],
        ['attribute' => 'beneficiary_name'],
        ['attribute' => 'party_relation', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->relationshipId, 'relationship');
        }],
        ['attribute' => 'payment_ref_id'],
        ['attribute' => 'payment_detail', 'visible' => false],
        ['attribute' => 'remarks', 'visible' => false],
];

$grid_option = [
    'id' => 'tbl-scheme-application-disbursement-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'edit' => function ($url, $model) {
            $disable = '';
            $options = ['title' => Yii::t('app', 'Edit'), 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', ['/welfarescheme/tbl-scheme-application-disbursement/update', 'id' => $model->disburse_id], $options);
        },
    //  'delete' => ['option' => 'beneficiary_name,disburse_id,/welfarescheme/tbl-scheme-application-disbursement/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
