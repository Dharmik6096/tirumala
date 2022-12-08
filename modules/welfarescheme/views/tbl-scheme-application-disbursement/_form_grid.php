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
        }, 'visible' => false,],
        [
        'attribute' => 'disburse_date',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true,
                'filter' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->disburse_date);
        }],
        ['attribute' => 'bank_name'],
        ['attribute' => 'branch_name'],
        ['attribute' => 'party_name'],
        ['attribute' => 'payment_ref_id'],
        ['attribute' => 'payment_detail'],
        ['attribute' => 'remarks'],
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
        'delete' => ['option' => 'party_name,disburse_id,/welfarescheme/tbl-scheme-application-disbursement/delete'],
    ]  
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
