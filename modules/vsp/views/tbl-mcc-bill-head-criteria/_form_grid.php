<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false],
        ['attribute' => 'mcc_bill_head_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccBillHead, 'bill_head_name');
        }],
        ['attribute' => 'general_formula_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->generalFormula, 'formula');
        }],
        ['attribute' => 'criteria_name'],
        ['attribute' => 'criteria_code', 'visible' => FALSE],
];

$grid_option = [
    'id' => 'mcc-bill-head-criteria-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'mapping' => function ($url, $model) {
            $disable = '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Applicability', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/vsp/tbl-mcc-bill-head-criteria/mcc-bill-head-criteria-applicability', 'id' => $model->criteria_code], $options);
        },
        'update_to_date_applicability' => function ($url, $model) {
            $disable = '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Update To Date Applicability', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-share"></i>', ['/vsp/tbl-mcc-bill-head-criteria/update-to-date-applicability', 'id' => $model->criteria_code], $options);
        },
        'update' => function($url, $model) {
            $disable = !empty($model->isApplicability) ? 'link-disable' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->criteria_code], $options);
        },
        'update_to_date' => function ($url, $model) {
            $disable = empty($model->isApplicability) ? 'link-disable' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit To Date', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa fa-pencil-square"></i>', ['/vsp/tbl-mcc-bill-head-criteria/update-to-date', 'id' => $model->criteria_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
