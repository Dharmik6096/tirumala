<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Html;
?>

<div class="grid-search clearfix">
    <?php
    if (Yii::$app->session->get('organizations_type') !== 'UNION' || count(explode(',', Yii::$app->session->get('Unions'))) > 1)
        echo $this->render('_search', ['model' => $searchModel]);
    ?></div>

<?php
$attribute = [
    ['attribute' => 'purchase_rate_code', 'value' => 'purchase_rate_code',],
    ['attribute' => 'reference_code', 'value' => 'reference_code',],
    [
        'attribute' => 'wef_date',
        'filterType' => GridView::FILTER_DATE,
        'width' => '200px',
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
    ['attribute' => 'shift_id',
        'filter' => false,
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftId, 'shift');
        },],
    ['attribute' => 'shift_applicability',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->shiftApplicability, 'shift');
        },],
    ['attribute' => 'rate_gen_method_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->rateMethod, 'method');
        },],
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false],
    ['attribute' => 'for_member', 'value' => function($model) {
            return $model->for_member == 1 ? 'YES' : 'NO';
        }, 'filter' => false],
    'description',
];

$grid_option = [
    'id' => 'purchase-rate-grid',
    'attributes' => $attribute,
    'active_column' => false,
    //'rowcolor' => 'danger',
    'rowOptions' => function ($model) {
        $rowcolor = '';
        if ($model->flg_sentbox_entry == 'E') {
            $rowcolor = 'danger';
        }
        return ['class' => $rowcolor];
    },
    'actions' => [
        'view' => true,
//                'update_data' => function ($url, $model) {
//                    $disable = ($model->is_active == 0) ? 'disabled' : '';
//                    if ($disable == '') {
//                        $disable = (strtoupper($model->originating_org_type) == 'UNION') ? '' : 'disabled';
//                        if ($model->rate_gen_method_code == 3 || $model->flg_sentbox_entry == 'Y') {
//                            $disable = 'disabled';
//                        }
//                    }
//                    $options = ['title' => Yii::t('app', 'Edit'), 'class' => $disable];
//                    return GhostHtml::a('<span class="glyphicon glyphicon-pencil"></span>', ['/dcsoperation/tbl-dcs-purchase-rate-details/create-rate', 'id' => $model->purchase_rate_code, 'method' => $model->rate_gen_method_code], $options);
//                },
        'mapping' => function ($url, $model) {
            $disable = ($model->is_active == 0) ? 'disabled' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Applicability', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/dcsoperation/tbl-dcs-purchase-rate/purchase-rate-applicability', 'id' => $model->purchase_rate_code], $options);
        },
        'view_rate' => function ($url, $model) {
            $disable = ($model->is_active == 0) ? 'disabled' : '';
            $options = ['title' => Yii::t('app', 'Rate Chart'), 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View Rate Chart', 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-bar-chart" aria-hidden="true"></i>', ['/dcsoperation/tbl-dcs-purchase-rate-details/rate-chart', 'id' => $model->purchase_rate_code, 'milk_type' => 1, 'milk_quality' => 1], $options);
        },
        'export_rate_chart' => function ($url, $model) {
            $options = ['title' => Yii::t('app', 'Export Rate Chart'),];
            return GhostHtml::a('<i class="fa fa-download" aria-hidden="true"></i>', ['/dcsoperation/tbl-dcs-purchase-rate-details/export-rate-chart', 'id' => $model->purchase_rate_code], $options);
        }
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>