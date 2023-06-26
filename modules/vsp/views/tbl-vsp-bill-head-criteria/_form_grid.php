<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false],
        ['attribute' => 'bill_head_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->billHead, 'bill_head_name');
        }],
        ['attribute' => 'general_formula_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->generalFormula, 'formula');
        }],
        ['attribute' => 'criteria_name'],
        ['attribute' => 'criteria_code', 'visible' => FALSE],
];

$grid_option = [
    'id' => 'vsp-bill-head-criteria-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'mapping' => function ($url, $model) {
            $disable = '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Applicability', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/vsp/tbl-vsp-bill-head-criteria/vsp-bill-head-applicability', 'id' => $model->vsp_criteria_code], $options);
        },
        'update' => function($url, $model) {
            $disable = !empty($model->isApplicability) ? 'link-disable' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->vsp_criteria_code], $options);
        },
        'update_to_date' => function ($url, $model) {
            $disable = empty($model->isApplicability) ? 'link-disable' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit To Date', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa fa-pencil-alt-square"></i>', ['/vsp/tbl-vsp-bill-head-criteria/update-to-date', 'id' => $model->vsp_criteria_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
