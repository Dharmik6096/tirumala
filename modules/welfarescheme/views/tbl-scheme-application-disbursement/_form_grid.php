<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
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
        }],
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
        ['attribute' => 'party_relation', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->relationshipId, 'relationship');
        }],
        ['attribute' => 'bank_name'],
        ['attribute' => 'branch_name'],
        ['attribute' => 'party_name'],
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
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/welfarescheme/tbl-scheme-application-disbursement/update', 'id' => $model->disburse_id], $options);
        },
    //  'delete' => ['option' => 'party_name,disburse_id,/welfarescheme/tbl-scheme-application-disbursement/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
